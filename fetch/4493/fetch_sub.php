<?php

require 'Fetch.php';

function myfetchsub($data) {
    $sub_url = $data['href'];
    $title   = $data['title'];
    $date    = $data['date'];
    $like    = $data['like'];
    $code    = $data['code'];
    $type    = $data['type'];
    $t1      = time();
    $fetch   = new Fetch();
    $n       = 0;
    if(empty($sub_url)){
        return false;
    }
    while ($n < 50) {
        $res = $fetch->fetch_4493_single($sub_url);
        if (empty($res)) {
            break;
        }
        $tmp[$n]['title']   = $title;
        $tmp[$n]['date']    = $date;
        $tmp[$n]['like']    = $like;
        $tmp[$n]['href']    = $sub_url;
        $tmp[$n]['img_url'] = $res;
        $tmp[$n]['code']    = $code;
        $tmp[$n]['type']    = $type;
        $sub_url            = $fetch->get_4493_sub_next_page($sub_url);
        $n++;
    }

    $sql = 'insert into `test`.`img` (`title`, `date`, `like`, `href`, `img_url`,`code`,`type`) values ';
    foreach ($tmp as $key => $value) {
        $sql .= "( '{$value['title']}', '{$value['date']}', {$value['like']}, '{$value['href']}', '{$value['img_url']}','{$value['code']}',{$value['type']}),";
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
        $log = date('Y-m-d H:i:s') . " fetch {$sub_url},insert success,use " . ($t2 - $t1) . ' second,insert ' . $mysqli->affected_rows . " rows.";
        file_put_contents('./sql.log', $sql . "\r\n", FILE_APPEND);
        file_put_contents('./log.log', $log . "\r\n", FILE_APPEND);
    }
    $mysqli->close();
}