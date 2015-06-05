<?php

function TimeStamp() {
    return time() * 1000;
}

function generateRandomString($length = 16) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

$GLOBALS['LIB_ROOT'] = '/home/orbeus/Mars/php';
//local
//$GLOBALS['LIB_ROOT'] = 'D:/wnmp/www/orbeus';
$GLOBALS['THRIFT_ROOT'] = $GLOBALS['LIB_ROOT'] . '/thrift';
require_once( $GLOBALS['THRIFT_ROOT'] . '/Thrift.php' );
require_once( $GLOBALS['THRIFT_ROOT'] . '/transport/TSocket.php' );
require_once( $GLOBALS['THRIFT_ROOT'] . '/transport/TFramedTransport.php' );
require_once( $GLOBALS['THRIFT_ROOT'] . '/protocol/TBinaryProtocol.php' );
