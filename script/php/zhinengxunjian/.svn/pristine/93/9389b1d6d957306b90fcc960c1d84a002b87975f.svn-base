<?php

/**
 * 定义空模块处理
 */
function __hack_module() {
    header("location:Index/index");
}

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

/**
 * URL获取
 *
 * 兼容URL_MODEL:0 普通模式 1 PATHINFO 2 REWRITE
 */
function getUrl($url, $arr = array()) {
    $urlPath = '';
    switch (C('URL_MODEL')) {
        case 1 :
            $urlPath = U(strtolower($url), $arr);
            break;
        case 2 :
            $urlPath = U(C('REWRITE_NAME') . '/' . strtolower($url), $arr);
            break;
        default :
            $urlPath = U(strtolower($url), $arr);
    }
    return $urlPath;
}

/*
 * 格式化时间
 * type 1 普通模式  例：2012-2-15 10:11
 * 		2 人性化时间  例：今天10:11  昨天10:11
 */

function formatTime($time, $format = "Y-m-d H:i", $type = 1) {
    if (!is_numeric($time) || $time <= 0)
        return '';
    switch ($type) {
        case 1:
            return date($format, $time);
        case 2:
            $diff_sec = $_SERVER['REQUEST_TIME'] - $time;
            if ($diff_sec < 180) {
                return '刚刚';
            } elseif ($diff_sec < 3600) {
                return ceil($diff_sec / 60) . '分钟前';
            } elseif ($diff_sec < 18000) {
                return intval($diff_sec / 3600) . '小时前';
            } elseif ($time >= mktime(0, 0, 0, date('m'), date('d'), date('Y'))) {
                return '今天' . date('H:i', $time);
            } elseif ($time > (mktime(0, 0, 1, date('m'), date('d'), date('Y')) - 86400)) {
                return '昨天' . date('H:i', $time);
            } else {
                return date($format, $time);
            }
    }
}

/**
 * 格式化货币
 */
function formatMoney($money, $format = '￥%s元') {
    $money = number_format($money, 2, '.', ',');
    return str_replace('%s', $money, $format);
}

/**
 * 生成流水号
 * 
 * @param string $prefix        	
 */
function makeSerialNumber($prefix = '') {
    return $prefix . date('ymdHis') . rand_string(3, 1);
}

/**
 * 获得GTM时间
 * 
 * @return number
 */
function getSystemTimestamp() {
    return time();
}

/**
 * 获得浏览器版本
 *
 * @return string
 */
function getUserBrower() {
    $brower = $_SERVER ['HTTP_USER_AGENT'];
    if (preg_match('/360SE/', $brower)) {
        $brower = "360se";
    } elseif (preg_match('/Maxthon/', $brower)) {
        $brower = "Maxthon";
    } elseif (preg_match('/Tencent/', $brower)) {
        $brower = "Tencent Brower";
    } elseif (preg_match('/Green/', $brower)) {
        $brower = "Green Brower";
    } elseif (preg_match('/baidu/', $brower)) {
        $brower = "baidu";
    } elseif (preg_match('/TheWorld/', $brower)) {
        $brower = "The World";
    } elseif (preg_match('/MetaSr/', $brower)) {
        $brower = "Sogou Brower";
    } elseif (preg_match('/Firefox/', $brower)) {
        $brower = "Firefox";
    } elseif (preg_match('/MSIE\s6\.0/', $brower)) {
        $brower = "IE6.0";
    } elseif (preg_match('/MSIE\s7\.0/', $brower)) {
        $brower = "IE7.0";
    } elseif (preg_match('/MSIE\s8\.0/', $brower)) {
        $brower = "IE8.0";
    } elseif (preg_match('/MSIE\s9\.0/', $brower)) {
        $brower = "IE9.0";
    } elseif (preg_match('/MSIE\s10\.0/', $brower)) {
        $brower = "IE10.0";
    } elseif (preg_match('/Netscape/', $brower)) {
        $brower = "Netscape";
    } elseif (preg_match('/Opera/', $brower)) {
        $brower = "Opera";
    } elseif (preg_match('/Chrome/', $brower)) {
        $brower = "Chrome";
    } elseif (preg_match('/Gecko/', $brower)) {
        $brower = "Gecko";
    } elseif (preg_match('/Safari/', $brower)) {
        $brower = "Safari";
    } else {
        $brower = "Unknow browser";
    }
    return $brower;
}

/**
 * 获得用户终端
 */
function getUserOS() {
    $agent = strtolower($_SERVER ['HTTP_USER_AGENT']);
    $os = '';
    if (strpos($agent, 'win') !== false) {
        if (strpos($agent, 'nt 5.1') !== false) {
            $os = 'Windows XP';
        } elseif (strpos($agent, 'nt 5.2') !== false) {
            $os = 'Windows 2003';
        } elseif (strpos($agent, 'nt 5.0') !== false) {
            $os = 'Windows 2000';
        } elseif (strpos($agent, 'nt 6.0') !== false) {
            $os = 'Windows Vista';
        } elseif (strpos($agent, 'nt 6.1') !== false) {
            $os = 'Windows 7';
        } elseif (strpos($agent, 'nt') !== false) {
            $os = 'Windows NT';
        } elseif (strpos($agent, 'win 9x') !== false && strpos($agent, '4.90') !== false) {
            $os = 'Windows ME';
        } elseif (strpos($agent, '98') !== false) {
            $os = 'Windows 98';
        } elseif (strpos($agent, '95') !== false) {
            $os = 'Windows 95';
        } elseif (strpos($agent, '32') !== false) {
            $os = 'Windows 32';
        } elseif (strpos($agent, 'ce') !== false) {
            $os = 'Windows CE';
        }
    } elseif (strpos($agent, 'linux') !== false) {
        $os = 'Linux';
    } elseif (strpos($agent, 'unix') !== false) {
        $os = 'Unix';
    } elseif (strpos($agent, 'sun') !== false && strpos($agent, 'os') !== false) {
        $os = 'SunOS';
    } elseif (strpos($agent, 'ibm') !== false && strpos($agent, 'os') !== false) {
        $os = 'IBM OS/2';
    } elseif (strpos($agent, 'mac') !== false && strpos($agent, 'pc') !== false) {
        $os = 'Macintosh';
    } elseif (strpos($agent, 'powerpc') !== false) {
        $os = 'PowerPC';
    } elseif (strpos($agent, 'aix') !== false) {
        $os = 'AIX';
    } elseif (strpos($agent, 'hpux') !== false) {
        $os = 'HPUX';
    } elseif (strpos($agent, 'netbsd') !== false) {
        $os = 'NetBSD';
    } elseif (strpos($agent, 'bsd') !== false) {
        $os = 'BSD';
    } elseif (strpos($agent, 'osf1') !== false) {
        $os = 'OSF1';
    } elseif (strpos($agent, 'irix') !== false) {
        $os = 'IRIX';
    } elseif (strpos($agent, 'freebsd') !== false) {
        $os = 'FreeBSD';
    } elseif (strpos($agent, 'teleport') !== false) {
        $os = 'teleport';
    } elseif (strpos($agent, 'flashget') !== false) {
        $os = 'flashget';
    } elseif (strpos($agent, 'webzip') !== false) {
        $os = 'webzip';
    } elseif (strpos($agent, 'offline') !== false) {
        $os = 'offline';
    } else {
        $os = 'Unknown';
    }
    return $os;
}

/**
 * 获得语言
 */
function getUserLanguage() {
    if (!empty($_SERVER ['HTTP_ACCEPT_LANGUAGE'])) {
        if (preg_match("/^([^\,]*)\,.*/", $_SERVER ['HTTP_ACCEPT_LANGUAGE'], $matchs)) {
            $lang = strtolower($matchs [1]);
        } else {
            $lang = '';
        }
    } else {
        $lang = '';
    }
    return $lang;
}

/**
 * 获得来源
 */
function getUserReferer() {
    if (!empty($_SERVER ['HTTP_REFERER']) && strlen($_SERVER ['HTTP_REFERER']) > 9) {
        $pos = strpos($_SERVER ['HTTP_REFERER'], '/', 9);
        if ($pos !== false) {
            $domain = substr($_SERVER ['HTTP_REFERER'], 0, $pos);
            $path = substr($_SERVER ['HTTP_REFERER'], $pos);
        } else {
            $domain = $path = '';
        }
    } else {
        $domain = $path = '';
    }
    return array(
        "domain" => $domain,
        "path" => $path
    );
}

/**
 * 截取字符串
 * @author	zhaohy
 * @param string $string 输入字符串
 * @param integer $length 截取长度，所有字符都按1个字节进行截取
 * @param string $dot 省略符号
 */
function formatStr($string, $length, $dot = '...', $charset = 'utf-8') {
    $strlen = strlen($string);
    if ($strlen <= $length)
        return $string;
    $string = str_replace(array('&nbsp;', '&amp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;'), array(' ', '&', '"', "'", '“', '”', '—', '<', '>'), $string);
    $strcut = '';
    if (strtolower($charset) == 'utf-8') {
        $n = $tn = $noc = 0;
        while ($n < $strlen) {
            $t = ord($string[$n]);
            if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                $tn = 1;
                $n++;
            } elseif (194 <= $t && $t <= 223) {
                $tn = 2;
                $n += 2;
            } elseif (224 <= $t && $t <= 239) {
                $tn = 3;
                $n += 3;
            } elseif (240 <= $t && $t <= 247) {
                $tn = 4;
                $n += 4;
            } elseif (248 <= $t && $t <= 251) {
                $tn = 5;
                $n += 5;
            } elseif ($t == 252 || $t == 253) {
                $tn = 6;
                $n += 6;
            } else {
                $n++;
            }
            $noc++;
            if ($noc >= $length)
                break;
        }
        if ($noc > $length)
            $n -= $tn;
        $strcut = substr($string, 0, $n);
    } else {
        $dotlen = strlen($dot);
        $maxi = $length - $dotlen - 1;
        for ($i = 0; $i < $maxi; $i++) {
            $strcut .= ord($string[$i]) > 127 ? $string[$i] . $string[++$i] : $string[$i];
        }
    }
    $strcut = str_replace(array('&', '"', "'", '<', '>'), array('&amp;', '&quot;', '&#039;', '&lt;', '&gt;'), $strcut);
    $dot = empty($string[$n]) ? '' : $dot;
    return $strcut . $dot;
}

function formatEncryptedText($string) {
    return substr_replace($string, '*****', 3, 5);
}

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
 *  简化 文件写入函数,主要用来显示数据值
 * @param type $data 记录的数据
 */
function file_log($data) {
    file_put_contents("D:/MyLog.log", $data);
}

/**
 * 制作输出函数 打印不退出
 */
function show_msg_not_exit($msg, $color = 'green') {
    header("Content-type:text/html;charset=utf-8;");
    echo "<pre style='color:{$color};'>";
    print_r($msg);
    echo "</pre>";
}

/* * *
 * 自定义过滤数组空元素
 * 引用传递
 */

function my_array_filter(&$array) {
    foreach ($array as $v => $vc) {
        if ($vc == '') {
            unset($array[$v]);
        }
    }
}

/**
 * 批量转换成字符型
 */
function toString($data, $to) {
    if (is_array($data)) {
        foreach ($data as $key => $val) {
            $data[$key] = toString($val, $to);
        }
    } else {
        $encode_array = array('ASCII', 'UTF-8', 'GBK', 'GB2312', 'BIG5');
        $encoded = mb_detect_encoding($data, $encode_array);
        $to = strtoupper($to);
        if ($encoded != $to) {
            $data = mb_convert_encoding($data, $to, $encoded);
        }
    }
    return $data;
}
