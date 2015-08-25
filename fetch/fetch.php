<?php
/**
 * 抓取贝贝数据
 */
include_once('fetch_data.php');
//$target_url = "http://global.beibei.com/p/1.html";
//$target_url = "http://global.beibei.com/p/2.html";
//$target_url = "http://global.beibei.com/p/3.html";
//$target_url = "http://global.beibei.com/p/4.html";
set_time_limit(0);
$t1 = time();
$p = $_REQUEST['p'];
if (empty($p)) {
    die('请输入获取的页数');
}
$target_url = "http://global.beibei.com/p/$p.html";
$arr = array();
$res = fetch_band($target_url);
foreach ($res as $key => $value) {
    $res2 = fetch_sub($value['href'], $value['band_id']);
    array_unshift($res2, $res[$key]);
    $arr[] = $res2;
}
die('----------------------------------------------------------------------------');
//拼接sql语句
$sql = 'INSERT INTO t_goods (`country`, `band_id`,`goods_name`,`goods_price`,`href`) VALUES';
foreach ($arr as $k => $item) {
    foreach ($item as $key => $value) {
        $sql .= '("' . $value['country'] . '",' . $value['band_id'] . ',"' . $value['goods_name'] . '",' . $value['goods_price'] . ',"' . $value['href'] . '"),';
    }
}
$sql = rtrim($sql, ',');
$mysqli = new mysqli("192.168.1.116", "root", "123@diancaonima", "test");
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
    echo "插入数据成功,耗时" . ($t2 - $t1) . '秒';
}
	