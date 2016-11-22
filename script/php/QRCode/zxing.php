<?php
/**
 * Created by PhpStorm.
 * User: zhaohongyu
 * Date: 2016/11/22
 * Time: 17:04
 */

include_once('./common_func.php');

// 1. 接收图片
$data_str = $_REQUEST['data_str'];

if (empty($data_str)) {
    response_json(-1, '图片链接不能为空');
}

// 2.判断是否是有效的链接
$is_img_link = is_img_link($data_str);
if (!$is_img_link) {
    response_json(-1, '不是有效的图片链接,请传入类似http://qa.demo.eventown.com/assets/img/mobile_url.png这样的图片链接地址');
}

// 3.拼接请求地址
$url  = sprintf("http://zxing.org/w/decode?u=%s", $data_str);
$code = file_get_contents($url);

// 4.解析返回的内容
preg_match("/<table id=\"result\">(.*)<\/table>/isU", $code, $math);
preg_match("/<pre>(.*)<\/pre>/isU", $math[1], $maths);
$text = $maths[1];
if (empty($text)) {
    response_json(-3, '没能找到您上传的图片中的二维码信息');
}
response_json(0, '识别成功', array(
    'text' => $text,
));