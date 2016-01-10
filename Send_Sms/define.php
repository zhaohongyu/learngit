<?php

// 定义一些使用到的常量
// index文件的绝对路径
define('INDEX_PATH', str_replace('\\', '/', APPPATH . "/index.php"));

// PHP的路径
define ('PHP_PATH', str_replace('\\','/', "/usr/local/php/bin/php"));

// LOG路径
$time_day = date('Y-m-d');
$log_path = APPPATH . "/{$time_day}_sms_send.log";
define('LOG_PATH', str_replace('\\', '/', $log_path));

//接收人的配置文件路径
define('DATA_PATH', str_replace('\\', '/', APPPATH . "/data"));
define('RECEIVER_PATH', str_replace('\\', '/', DATA_PATH . "/receiver.ini"));
//短信接口配置文件路径
define('SMS_URL_PATH', str_replace('\\', '/', DATA_PATH . "/sms_url.ini"));
