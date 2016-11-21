<?php

include_once('./common_func.php');
include_once('./lib/QrReader.php');

// 1. 接收图片
$data_str = $_REQUEST['data_str'];

if (empty($data_str)) {
    response_json(-1, '图片链接或者图片二进制数据不能为空');
}

$blob = '';

$is_img_link = is_img_link($data_str);
if ($is_img_link) {
    $blob = file_get_contents($data_str);
} else if (is_base64($data_str)) {
    $blob = base64_decode($data_str);
}

if (empty($blob)) {
    response_json(-2, '二维码识别失败了');
}

// 2. 设置图片类型[blob]
$sourcetype = QrReader::SOURCE_TYPE_BLOB;

// 3. 调用对应方法解析二维码
$qrcode = new QrReader($blob, $sourcetype);
$text   = $qrcode->text();

if (empty($text)) {
    response_json(-3, '二维码识别失败了');
}

response_json(0, '识别成功', array(
    'text' => $qrcode->text(),
));

//$dir          = scandir('qrcodes');
//$ignoredFiles = array(
//    '.',
//    '..',
//    '.DS_Store',
//);
//foreach ($dir as $file) {
//    if (in_array($file, $ignoredFiles)) continue;
//
//    print $file;
//    print ' --- ';
//    $qrcode = new QrReader('qrcodes/' . $file);
//    print $text = $qrcode->text();
//    print "<br/>";
//}
