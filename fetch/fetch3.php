<?php
/**
 * 抓取蜜桃网数据
 */
include_once('fetch_data.php');
set_time_limit(0);
$t1 = time();
$arr = array();
for ($i = 450; $i < 483; $i++) {
    $target_url = "http://www.metao.com/oversea/0/" . ($i + 1) . "/0?0.016772315138950944&_=1440143248480";
    $res = fetch_sub3($target_url);
    $arr = array_merge($arr, $res);
}
//拼接sql语句
$sql = 'INSERT INTO t_goods (`country`, `band_id`,`goods_name`,`goods_price`,`href`) VALUES';
foreach ($arr as $key => $value) {
    $sql .= '("' . $value['country'] . '",' . 0 . ',"' . $value['goods_name'] . '",' . $value['goods_price'] . ',"' . $value['href'] . '"),';
}
$sql = rtrim($sql, ',');
$mysqli = new mysqli("192.168.10.168", "root", "123@diancaonima", "test");
//show_msg($sql);
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
    echo "插入数据成功,耗时" . ($t2 - $t1) . '秒,插入了' . count($arr) . "条记录.";
}