<?php
/**
 * Created by PhpStorm.
 * User: zhaohongyu
 * Date: 16/5/11
 * Time: 下午2:37
 */

ini_set('soap.wsdl_cache_enabled', "0"); // 关闭wsdl缓存

try {
    $wsdl   = null;
    $client = new SoapClient($wsdl, array(
        "location" => "http://{$_SERVER['HTTP_HOST']}/script/php/soap/nowsdl/Server.php",
        "uri"      => "abcd",  // 资源描述符服务器和客户端必须对应
        "style"    => SOAP_RPC,
        "use"      => SOAP_ENCODED,
    ));
    // $response = $client->Add(10, 6);
    $response = $client->myarr(array(1, 2, 3, 4, 'a' => 'b'));
    echo '<pre>';
    var_dump($client);
    var_dump($response);
} catch (Exction $e) {
    echo print_r($e->getMessage(), true);
}