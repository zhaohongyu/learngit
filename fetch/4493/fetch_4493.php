<?php

require 'Fetch.php';

function myfetch($target_url) {
    $t1    = time();
    $fetch = new Fetch();
    $all   = $fetch->fetch_4493($target_url);
    foreach ($all as $k => $v) {
        $sub_url = $v['href'];
        $n       = 0;
        while ($n < 50) {
            $res = $fetch->fetch_4493_single($sub_url);
            if (empty($res)) {
                break;
            }
            $tmp[$n]['sub_url'] = $sub_url;
            $tmp[$n]['img_url'] = $res;
            $sub_url            = $fetch->get_4493_sub_next_page($sub_url);
            $n++;
            sleep(1);
        }
        if (empty(!$tmp)) {
            $all[$k]['sub_img'] = $tmp;
        }
    }

    $sql = 'insert into `test`.`img` (`title`, `date`, `like`, `href`, `img_url`,`code`) values ';
    foreach ($all as $key => $value) {
        $random_code = md5(uniqid(time()));
        $sql .= "( '{$value['title']}', '{$value['date']}', {$value['like']}, '{$value['href']}', '{$value['cover']}','{$random_code}'),";
        if (is_array($value['sub_img']) && !empty($value['sub_img'])) {
            foreach ($value['sub_img'] as $v) {
                $sql .= "( '{$value['title']}', '{$value['date']}', {$value['like']}, '{$v['sub_url']}', '{$v['img_url']}','{$random_code}'),";
            }
        }
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