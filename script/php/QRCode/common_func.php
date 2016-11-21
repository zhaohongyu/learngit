<?php
/**
 * Created by PhpStorm.
 * User: zhaohongyu
 * Date: 2016/11/21
 * Time: 20:03
 */

/*判断字符串是否经过编码方法*/
function is_base64($str) {
    if ($str == base64_encode(base64_decode($str))) {
        return true;
    } else {
        return false;
    }
}

/*是否是图片链接*/
function is_img_link($data_str) {
    $is_http  = ((strpos($data_str, 'http')) === false) ? false : true;
    $is_https = ((strpos($data_str, 'https')) === false) ? false : true;;
    return $is_http || $is_https;
}

/*返回json串*/
function response_json($code, $msg = '', $data = array()) {
    if (!is_numeric($code)) {
        return '';
    }
    $result = array(
        'errorno' => $code,
        'msg'     => $msg,
        'data'    => $data,
    );
    header("Content-type:application/json");
    die(json_encode($result));
}