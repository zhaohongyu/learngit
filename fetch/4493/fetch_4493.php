<?php

require 'Fetch.php';

function myfetch($target_url, $type) {
    $t1    = time();
    $fetch = new Fetch();
    $all   = $fetch->fetch_4493($target_url);
    $sql   = 'insert into `test`.`img` (`title`, `date`, `like`, `href`, `img_url`,`code`,`type`) values ';
    foreach ($all as $key => $value) {
        $random_code = md5(uniqid(time()));
        $sql .= "( '{$value['title']}', '{$value['date']}', {$value['like']}, '{$value['href']}', '{$value['cover']}','{$random_code}',{$type}),";
    }
    $sql = rtrim($sql, ',');

    $mysqli = new mysqli('127.0.0.1', 'root', '', 'test');
    /* check connection */
    if ($mysqli->connect_errno) {
        printf("Connect failed: %s\n", $mysqli->connect_error);
        exit();
    }
    /* 插入数据 */
    if ($mysqli->query($sql) === TRUE) {
        $t2  = time();
        $log = date('Y-m-d H:i:s') . " 抓取页面{$target_url},插入数据成功,耗时" . ($t2 - $t1) . '秒,插入了' . $mysqli->affected_rows . "条记录.";
        file_put_contents('./sql.log', $sql . "\r\n", FILE_APPEND);
        file_put_contents('./log.log', $log . "\r\n", FILE_APPEND);
    }
    $mysqli->close();
}