<?php
/**
 * Created by PhpStorm.
 * User: zhaohongyu
 * Date: 16/5/11
 * Time: 上午2:43
 */

set_time_limit(0);

require 'fetch_4493.php';

// 抓取列表页面
for ($i = 1; $i <= 41; $i++) {
    $url = sprintf("http://www.4493.com/dongmanmeinv/index-%s.htm", $i);
    myfetch($url, 7);//TODO 记得改type
}