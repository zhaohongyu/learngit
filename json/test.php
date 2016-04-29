<?php


/**
 * 输出json信息
 *
 * @param int    $code 返回码
 * @param string $msg  返回消息
 * @param array  $data 返回数据
 *
 * @return json
 */
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

$arr = array(
    "time" => time(),
    "rand" => rand(1, 1000),
);

response_json(10086, '获取数据成功', $arr);