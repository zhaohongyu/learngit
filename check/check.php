<?php

/**
 * 检查清理照片应用版本
 */
function checkVersion($client_version='') {
    //服务端版本文件路径
    $version_file_path = parse_ini_file('version.ini');
    $server_version = $version_file_path["version"]; //服务端版本号
    $message = '';
    $hasNewVersion = '';
    $update_file_path = '';
    if ($server_version == $client_version) {
        $message = "已经是最新版本";
        $hasNewVersion = 0;
    } else {
        $message = "有新版本了,是否要更新";
        $hasNewVersion = 1;
        $update_file_path = getServerAddrAndPort() . '/api/check/clean.apk';
    }
    $result["version"] = array(
        'hasNewVersion' => $hasNewVersion,
        'serverVersion' => $server_version,
        'clientVersion' => $client_version,
        'message' => $message,
        'updateFilePath' => $update_file_path,
    );
    die(json_encode($result));
}
$client_version= isset($_REQUEST['client_version'])?$_REQUEST['client_version']:'';
checkVersion($client_version);

/**
 * 获得当前的域名
 *
 * @return string
 */
function getServerAddrAndPort() {
    /* 协议 */
    $protocol = (isset($_SERVER ['HTTPS']) && (strtolower($_SERVER ['HTTPS']) != 'off')) ? 'https://' : 'http://';
    /* 域名或IP地址 */
    if (isset($_SERVER ['HTTP_X_FORWARDED_HOST'])) {
        $host = $_SERVER ['HTTP_X_FORWARDED_HOST'];
    } elseif (isset($_SERVER ['HTTP_HOST'])) {
        $host = $_SERVER ['HTTP_HOST'];
    } else {
        /* 端口 */
        if (isset($_SERVER ['SERVER_PORT'])) {
            $port = ':' . $_SERVER ['SERVER_PORT'];

            if ((':80' == $port && 'http://' == $protocol) || (':443' == $port && 'https://' == $protocol)) {
                $port = '';
            }
        } else {
            $port = '';
        }
        if (isset($_SERVER ['SERVER_NAME'])) {
            $host = $_SERVER ['SERVER_NAME'] . $port;
        } elseif (isset($_SERVER ['SERVER_ADDR'])) {
            $host = $_SERVER ['SERVER_ADDR'] . $port;
        }
    }
    return $protocol . $host;
}