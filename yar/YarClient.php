<?php
$server = "http://127.0.0.1/yar/YarServer.php";
$method = "myapi";

//$client = new Yar_Client($server);
//$result = $client->$method("parameter999");
//var_dump($result);

function callback($retval, $callinfo) {
    if ($callinfo == NULL) {
        //做本地的逻辑
        echo "服务器这个时候还没有返回数据呢<br/>";
        return TRUE;
    }

    var_dump($retval);
    echo "<br/>";
}


Yar_Concurrent_Client::call($server, $method, array("parameters1"), "callback");
Yar_Concurrent_Client::call($server, $method, array("parameters2"), "callback");
Yar_Concurrent_Client::call($server, $method, array("parameters3"), "callback");
Yar_Concurrent_Client::call($server, $method, array("parameters4"), "callback");
Yar_Concurrent_Client::loop('callback');//send