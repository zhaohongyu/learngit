<?php

class Test {
    public function HelloWorld() {
        return "Hello";
    }

    public function Add($a, $b) {
        return $a + $b;
    }

    public function myarr($arr) {
        return $arr;
    }
}

$wsdl   = null;
$server = new SoapServer($wsdl, array('uri' => "abcd"));
$server->setClass("Test");
$server->handle();
?>