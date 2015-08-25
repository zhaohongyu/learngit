<?PHP

/**
 * 通用函数
 */
//包含配置文件 
if (is_file("config.php")) {
    C(include 'config.php');
}
require_once('classes/Model.class.php');

/**
 * 数据库操作函数
 * @return \mysqli
 */
function M() {
    $db = new Model();
    if (mysqli_connect_errno())
        throw_exception(mysqli_connect_error());
    return $db;
}

// 获取配置值
function C($name = null, $value = null) {
    //静态全局变量，后面的使用取值都是在 $)config数组取
    static $_config = array();
    // 无参数时获取所有
    if (empty($name))
        return $_config;
    // 优先执行设置获取或赋值
    if (is_string($name)) {
        if (!strpos($name, '.')) {
            $name = strtolower($name);
            if (is_null($value))
                return isset($_config[$name]) ? $_config[$name] : null;
            $_config[$name] = $value;
            return;
        }
        // 二维数组设置和获取支持
        $name = explode('.', $name);
        $name[0] = strtolower($name[0]);
        if (is_null($value))
            return isset($_config[$name[0]][$name[1]]) ? $_config[$name[0]][$name[1]] : null;
        $_config[$name[0]][$name[1]] = $value;
        return;
    }
    // 批量设置
    if (is_array($name)) {
        return $_config = array_merge($_config, array_change_key_case($name));
    }
    return null; // 避免非法参数
}

function ajaxReturn($data = null, $message = "", $status) {
    $ret = array();
    $ret["data"] = $data;
    $ret["message"] = $message;
    $ret["status"] = $status;
    echo json_encode($ret);
    die();
}

//调试数组
function _dump($var) {
    if (C("debug"))
        dump($var);
}

// 浏览器友好的变量输出
function dump($var, $echo = true, $label = null, $strict = true) {
    $label = ($label === null) ? '' : rtrim($label) . ' ';
    if (!$strict) {
        if (ini_get('html_errors')) {
            $output = print_r($var, true);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        } else {
            $output = $label . print_r($var, true);
        }
    } else {
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        if (!extension_loaded('xdebug')) {
            $output = preg_replace("/\]\=\>\n(\s+)/m", '] => ', $output);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        }
    }
    if ($echo) {
        echo($output);
        return null;
    } else
        return $output;
}

/**
 * 调试输出
 * @param type $msg
 */
function _debug($msg) {
    if (C("debug"))
        echo "$msg<br />";
}

function _log($filename, $msg) {
    $time = date("Y-m-d H:i:s");
    $msg = "[$time]\n$msg\r\n";
    if (C("log")) {
        $fd = fopen($filename, "a+");
        fwrite($fd, $msg);
        fclose($fd);
    }
}

/**
 * 日志记录
 * @param type $str
 */
function L($msg) {
    $time = date("Y-m-d H:i:s");
    $clientIP = $_SERVER['REMOTE_ADDR'];
    $msg = "[$time $clientIP] $msg\r\n";
    $log_file = C("LOGFILE");
    _log($log_file, $msg);
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
 * 制作输出函数 打印并退出
 */
function show_msg($msg, $color = 'green') {
    header("Content-type:text/html;charset=utf-8;");
    echo "<pre style='color:{$color};'>";
    print_r($msg);
    echo "</pre>";
    //exit("数据打印完毕");
    exit();
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
