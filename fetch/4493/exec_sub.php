<?php
/**
 * Created by PhpStorm.
 * User: zhaohongyu
 * Date: 16/5/11
 * Time: 上午2:43
 */

set_time_limit(0);

require '../classes/Model.class.php';
require 'fetch_sub.php';

function M() {
    $config = array(
        'username' => 'root',
        'password' => '',
        'hostname' => '127.0.0.1',
        'hostport' => '3306',
        'database' => 'test',
    );
    $db     = new Model($config);
    if (mysqli_connect_errno())
        throw_exception(mysqli_connect_error());
    return $db;
}

$limit       = 20;
$offset      = 52;
$sql         = "select * from `test`.`img` where id < 39705  limit {$offset},{$limit}";
$record_list = M()->select($sql);


foreach ($record_list as $v) {
    myfetchsub($v);
}