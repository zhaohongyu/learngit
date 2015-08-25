<?php

error_reporting(E_ALL || ~E_NOTICE);
/**
 * 抓取amazon数据
 */
include_once('fetch_data.php');
set_time_limit(0);
$t1 = time();
$p = $_REQUEST['p'];
if (empty($p)) {
    die('请输入获取的页数');
}
$target_url = "http://www.amazon.cn/s/?srs=1494169071&rh=n%3A42692071&page=" . ($p) . "&ie=UTF8";
$res = fetch_amazon($target_url);
//show_msg($res);
//拼接sql语句
$sql = 'INSERT INTO t_goods_amazon (`country`, `band`,`goods_name`,`goods_price`,`goods_price_usd`,`href`) VALUES';
foreach ($res as $key => $value) {
    $sql .= '("' . $value['country'] . '","' . $value['band'] . '","' . $value['goods_name'] . '","' . $value['goods_price'] . '","' . $value['goods_price_usd'] . '","' . $value['href'] . '"),';
}
$sql = rtrim($sql, ',');
$mysqli = new mysqli("192.168.10.168", "root", "123@diancaonima", "test");
/* check connection */
if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}
/* 插入数据 */
if ($mysqli->query($sql) === TRUE) {
    echo "<pre>";
    echo $sql;
    $t2 = time();
    echo "</pre>";
    echo "插入数据成功,耗时" . ($t2 - $t1) . '秒,插入了' . count($res) . "条记录.";
}