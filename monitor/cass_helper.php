<?php

require_once('config.php');
require_once( $GLOBALS['THRIFT_ROOT'] . '/packages/cassandra/Cassandra.php' );

if (isset($_REQUEST['cassandra_host']) && !empty($_REQUEST['cassandra_host'])) {
    $GLOBALS['CASSANDRA_HOST'] = $_REQUEST['cassandra_host'];
} else {
    die("cassandra_host not set!");
}
//$GLOBALS['CASSANDRA_HOST'] = "192.168.1.115";
$GLOBALS['CASSANDRA_PORT'] = 9160;

class CassandraIO {

    private $client = NULL;
    private $transport = NULL;

    function __construct() {
        $socket = new TSocket($GLOBALS['CASSANDRA_HOST'], $GLOBALS['CASSANDRA_PORT']);
        $socket->setSendTimeout(10000);
        $socket->setRecvTimeout(15000);

        $this->transport = new TFramedTransport($socket);
        $protocol = new TBinaryProtocol($this->transport);
        $this->client = new cassandra_CassandraClient($protocol);
        $this->transport->open();
        // $this->client->set_cql_version("3.0.0");
    }

    function __destruct() {
        $this->transport->close();
    }

    function query($query, &$error = NULL, &$result = NULL) {
        try {
            $res = $this->client->execute_cql3_query($query, Compression::NONE, ConsistencyLevel::ALL);
        } catch (Exception $e) {
            $error = $e->getMessage();
            if (!strlen($error)) {
                $error = $e;
            }
            return false;
        }
        $result = $res;
        return true;
    }

    function blobQuery($query, &$blob = NULL, &$error = NULL, &$result = NULL) {
        try {
            $prepared_query = $this->client->prepare_cql3_query($query, Compression::NONE);
            $res = $this->client->execute_prepared_cql3_query($prepared_query->itemId, [$blob], ConsistencyLevel::ALL);
        } catch (Exception $e) {
            $error = $e->getMessage();
            if (!strlen($error)) {
                $error = $e;
            }
            return false;
        }
        $result = $res;
        return true;
    }

}

// TODO: use ToInt32 instead
function charToInt($c1, $c2) {
    return 256 * ord($c1) + ord($c2);
}

// TODO: use ToInt32 instead
function charToInt4($c1, $c2, $c3, $c4) {
    return 256 * 256 * 256 * ord($c1) + 256 * 256 * ord($c2) + 256 * ord($c3) + ord($c4);
}

function read_set($input) {
    $set = array();
    if (!$input) {
        return $set;
    }
    $count = charToInt($input[0], $input[1]);
    $idx = 2;
    for ($i = 0; $i < $count; $i++) {
        $l1 = charToInt($input[$idx], $input[$idx + 1]);
        $idx += 2;
        $key = substr($input, $idx, $l1);
        $idx += $l1;
        array_push($set, $key);
    }
    return $set;
}

function read_map($input) {
    $ret = array();
    if (!$input) {
        return $ret;
    }

    for ($i = 0; $i < strlen($input); $i++) {
        echo ord($input[$i]) . ";";
    }

    $count = charToInt($input[0], $input[1]);
    $idx = 2;
    for ($i = 0; $i < $count; $i++) {
        $l1 = charToInt($input[$idx], $input[$idx + 1]);
        $idx += 2;
        $key = substr($input, $idx, $l1);
        $idx += $l1;
        $l2 = charToInt($input[$idx], $input[$idx + 1]);
        $idx += 2;
        $value = substr($input, $idx, $l2);
        $ret[$key] = $value;
        $idx += $l2;
    }
    return $ret;
}

function read_map_str_int($input) {
    $ret = array();
    if (!$input) {
        return $ret;
    }

    $count = charToInt($input[0], $input[1]);
    $idx = 2;
    for ($i = 0; $i < $count; $i++) {
        $l1 = charToInt($input[$idx], $input[$idx + 1]);
        $idx += 2;
        $key = substr($input, $idx, $l1);
        $idx += $l1;
        $l2 = charToInt($input[$idx], $input[$idx + 1]);
        $idx += 2;
        $value = charToInt4($input[$idx], $input[$idx + 1], $input[$idx + 2], $input[$idx + 3]);
        $ret[$key] = $value;
        $idx += $l2;
    }
    return $ret;
}

function make_batch_query($query_arr) {
    $batch_query = "begin batch\r\n";
    foreach ($query_arr as $q) {
        $batch_query .= $q;
    }
    $batch_query .= "apply batch;";
    return $batch_query;
}

function make_in_clause($arr) {
    $temp = array();
    foreach ($arr as $item) {
        array_push($temp, "'$item'");
    }
    $str = implode(',', $temp);
    return "($str)";
}

function ToFloat($str) {
    $sum = 0;
    for ($i = 0; $i < 4; $i++) {
        $d = ord($str[$i]);
        $base = 1;
        for ($j = 0; $j < 3 - $i; $j++) {
            $base *= 256;
        }
        $sum += $d * $base;
    }
    $sum = unpack('f', pack('i', $sum));
    return $sum[1];
}

function ToInt32($str) {
    $sum = 0;
    for ($i = 0; $i < strlen($str); $i++) {
        $d = ord($str[$i]);
        $d <<= (strlen($str) - 1 - $i) * 8;
        $sum += $d;
    }
    return $sum;
}

function ToBool($str) {
    if (ord($str) == 1) {
        return true;
    } else {
        return false;
    }
}
