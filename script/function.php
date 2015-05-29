<?php

/**
 * 制作输出函数 打印并退出
 */
function show_msg($msg, $color = 'green') {
    header("Content-type:text/html;charset=utf-8;");
    echo "<pre style='color:{$color};'>";
    print_r($msg);
    echo "</pre>";
    exit("数据打印完毕");
}

/**
 * 简化 文件写入函数,主要用来显示数据值
 * @param type $data 记录的数据
 */
function file_log($data) {
    file_put_contents("D:/MyLog.log", $data);
}

/**
 * 制作输出函数 打印并退出
 */
function show_msg_not_exit($msg, $color = 'green') {
    header("Content-type:text/html;charset=utf-8;");
    echo "<pre style='color:{$color};'>";
    print_r($msg);
    echo "</pre>";
}
