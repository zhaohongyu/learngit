<?php

// APP路径
define('APPPATH', str_replace('\\', '/', dirname(__FILE__)));
date_default_timezone_set('Asia/Shanghai');

require_once APPPATH . '/define.php';
require_once APPPATH . '/Socketlog.php';

// 默认控制器/方法
$controller = 'Cron_Mission';
$method = 'send_sms';
$param = '';

// 初始化cli请求数据
function init_cli($argv, $argc) {
    global $controller;
    global $method;
    global $param;
    if ($argc >= 3) {
        $controller = $argv[1];
        $method = $argv[2];
        $param = isset($argv[3]) ? $argv[3] : '';
    }
}

// 初始化浏览器请求数据
function init_browser() {
    // die('不支持浏览器请求方式。');
    global $controller;
    global $method;
    global $param;
    $query_string = $_SERVER['QUERY_STRING'];
    $arr = array();
    parse_str($query_string, $arr);
    $controller = $arr['c'];
    $method = $arr['m'];
    $param = '';
    foreach ($arr as $k => $v) {
        if ($k != 'c' && $k != 'm') {
            if ($k == 'param') {
                $param .= "{$v}&";
            } else {
                $param .= "{$k}={$v}&";
            }
        }
    }
}

$is_cli = php_sapi_name();
if ($is_cli == 'cli') {
    //cli
    init_cli($argv, $argc);
} else {
    //浏览器
    init_browser();
}


$class = "{$controller}.php";
require_once APPPATH . "/{$class}";

$c = new $controller();
$c->$method();
