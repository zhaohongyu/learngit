<?php
require 'simple_html_dom.php';

if (!function_exists('show_msg')) {
    /**
     * 输出函数
     *
     * @param mix     $data   输出的数据
     * @param boolean $isExit 是否退出
     * @param string  $color  颜色
     *
     * @author hongyu_zhao <hongyu_zhao@eventown.com.cn>
     */
    function show_msg($data, $isExit = true, $color = 'green') {
        header("Content-type:text/html;charset=utf-8;");
        echo "<div style='margin-left:220px;margin-top:53px;'><pre style='color:{$color};'>";
        print_r($data);
        echo "</pre>#####################################数据打印完毕##########################################################################<br/></div>";
        if ($isExit) {
            exit();
        }
    }
}

if (!function_exists('mylog')) {
    function mylog($data, $path = '/tmp/debug.log') {
        file_put_contents($path, serialize($data) . "\r\n", FILE_APPEND);
    }
}

function fetch_4493($target_url) {
    $html = new simple_html_dom();
    $html->load_file($target_url);
    $list = array();
    foreach ($html->find('div.piclist ul li') as $post) {
        $item['href']  = $post->find('a', 0)->getAttribute('href');// 套图链接
        $item['img']   = $post->find('a img', 0)->getAttribute('src');// 缩略图地址
        $item['title'] = mb_convert_encoding($post->find('a span', 0)->innertext, 'UTF-8', 'GB2312');// 标题
        $item['date']  = $post->find('.b1', 0)->innertext;// 上传日期
        $item['like']  = $post->find('.b2', 0)->innertext;// 喜欢人数
        $list[]        = $item;
    }
    $html->clear();
    unset($html);
    return $list;
}

//$target_url = "./a.html";
$target_url = "http://www.4493.com/siwameitui/index-4.htm";

$res = fetch_4493($target_url);

show_msg($res);