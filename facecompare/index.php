<?php

/**
 * 人脸对比测试
 */
require_once './Curl/Curl.php';

use \Curl\Curl;

$curl = new Curl();
$curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
$url = "http://rekognition.com/func/api/";
//使用服务器上的图片
//$data = array('api_key' => 'On7QxkJMCOiyTt7k',
//    'api_secret' => 'qomqRUerqlk3fwaT',
//    'jobs' => 'face_compare',
//    'urls' => 'https://rekognition.com/static/img/sample/brad_pitt_01.jpg',
//    'urls_compare' => 'https://rekognition.com/static/img/sample/brad_pitt_02.jpg'
//);
//本地图片
$image_file1 = './img/lzy1.jpg';
$base64_image_content1 = base64_encode(file_get_contents($image_file1));
$image_file2 = './img/lzy2.jpg';
$base64_image_content2 = base64_encode(file_get_contents($image_file2));
$data = array('api_key' => 'On7QxkJMCOiyTt7k',
    'api_secret' => 'qomqRUerqlk3fwaT',
    'jobs' => 'face_compare',
    'base64' => $base64_image_content1,
    'base64_compare' => $base64_image_content2
);
$curl->setJsonDecoder(function($response) {
    $json_obj = json_decode($response, true);
    if (!($json_obj === null)) {
        $response = $json_obj;
    }
    return $response;
});
$curl->post($url, $data);
if ($curl->error) {
    $msg = 'Error: ' . $curl->error_code . ': ' . $curl->error_message;
    die($msg);
}
var_dump($curl->response);
$curl->close();
die();

