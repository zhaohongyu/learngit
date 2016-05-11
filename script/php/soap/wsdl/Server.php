<?php

class Test {
    public function HelloWorld() {
        return "HelloWorld";
    }

    public function Add($a, $b) {
        return $a + $b;
    }

    public function myarr($arr) {
        return array('1','2','4');
    }
}

$wsdl   = 'Test.wsdl';
$server = new SoapServer($wsdl);
$server->setClass("Test");
$server->handle();
?>