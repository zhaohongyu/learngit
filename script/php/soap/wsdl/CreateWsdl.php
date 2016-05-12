<?php
include("SoapDiscovery.class.php");
include("Server.php");

$file_name   = 'Test';
$file_path   = 'Test.wsdl';
$server_name = 'mysoap';

$disc = new SoapDiscovery($file_name, $server_name); //第一个参数是类名（生成的wsdl文件就是以它来命名的，例如：下面的Service.wsdl），即Service类，第二个参数是服务的名字（这个可以随便写）
$wsdl = $disc->getWSDL();
file_put_contents($file_path, $wsdl);
header("Content-type: text/xml; charset=utf-8");
echo $wsdl;