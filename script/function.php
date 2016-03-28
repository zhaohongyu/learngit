<?php

/**
 * 输出函数
 * @param mix $data 输出的数据
 * @param boolean $isExit 是否退出
 * @param string $color 颜色
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

function mylog($data, $path = '/tmp/debug.log') {
    file_put_contents($path, serialize($data) . "\r\n", FILE_APPEND);
}

if (!function_exists('sub_str')) {

    /**
     * 截取UTF-8编码下字符串的函数
     * @param   string $str 被截取的字符串
     * @param   int $length 截取的长度
     * @param   bool $append 是否附加省略号
     * @return  string
     */
    function sub_str($str, $length = 0, $append = true) {
        $str       = trim($str);
        $strlength = strlen($str);

        if ($length == 0 || $length >= $strlength) {
            return $str;
        } elseif ($length < 0) {
            $length = $strlength + $length;
            if ($length < 0) {
                $length = $strlength;
            }
        }

        if (function_exists('mb_substr')) {
            $newstr = mb_substr($str, 0, $length, 'utf-8');
        } elseif (function_exists('iconv_substr')) {
            $newstr = iconv_substr($str, 0, $length, 'utf-8');
        } else {
            $newstr = substr($str, 0, $length);
        }

        if ($append && $str != $newstr) {
            $newstr .= '...';
        }

        return $newstr;
    }

}

if (!function_exists('move_file')) {

    /**
     * 移动指定文件到指定目录
     * @param string $source_file 源文件
     * @param string $dest_dir 目标文件夹
     * @param boolean $is_delete 是否删除源文件
     * @return  mixed 成功时返回移动的文件名称--失败时返回false
     */
    function move_file($source_file, $dest_dir, $is_delete = false) {
        if (file_exists($source_file) && file_exists($dest_dir)) {
            $path_parts = pathinfo($source_file);
            $new_file   = $dest_dir . DIRECTORY_SEPARATOR . $path_parts['basename'];
            copy($source_file, $new_file);
            $is_delete ? unlink($source_file) : '';
            return $path_parts['basename'];
        }
        return false;
    }

}
if (!function_exists('dir_is_empty')) {

    /**
     * 判断目录是否为空
     * @param string $dir 目录
     * @return boolean
     */
    function dir_is_empty($dir) {
        if ($handle = opendir($dir)) {
            while ($item = readdir($handle)) {
                if ($item != '.' && $item != '..') {
                    return false;
                }
            }
            return true;
        }
    }

}

if (!function_exists('loan_file_download')) {

    /**
     * 贷款申请文件下载
     * @param  number $root 文件存储根目录
     * @param  integer $id 文件ID
     * @param  string $callback 回调函数
     * @param  string $args 回调函数参数
     * @return boolean       false-下载失败，否则输出下载文件
     */
    function loan_file_download($id, $root = ROOT_WEB, $callback = null, $args = null) {
        /* 获取下载文件信息 */
        $CI =& get_instance();
        $CI->load->model('Loan_model');
        $loan_file_info = $CI->Loan_model->get_loan_file_info_by_id($id);
        if (!$loan_file_info) {
            return false;
        }

        /* 下载文件 */
        $file_path         = $root . DIRECTORY_SEPARATOR . $loan_file_info['url'];
        $file['file_path'] = $file_path;
        $file['show_name'] = $loan_file_info['origin_file_name'];
        return downLocalFile($file, $callback, $args);
    }

}

if (!function_exists('downLocalFile')) {

    /**
     * 下载本地文件
     *
     * 关闭错误显示,否则会出现下载文件无法打开的情况,请不要在调用之前有任何输出和更改header的情况
     * ini_set('display_errors', 0);
     * @param  array $file 文件信息数组
     * @param  callable $callback 下载回调函数，一般用于增加下载次数
     * @param  string $args 回调函数参数
     * @return boolean            下载失败返回false
     */
    function downLocalFile($file, $callback = null, $args = null) {
        $file_name = $file['file_path'];
        if (file_exists($file_name)) {

            /* 调用回调函数新增下载数 */
            is_callable($callback) && call_user_func($callback, $args);

            $length   = filesize($file_name);
            $type     = getMimeType($file_name);
            $showname = $file['show_name'];
            // 下载文件需要用到的头
            header("Content-Description: File Transfer");
            header('Content-type: ' . $type);
            header('Content-Length:' . $length);
            if (preg_match('/MSIE/', $_SERVER['HTTP_USER_AGENT'])) { //for IE
                header('Content-Disposition: attachment; filename="' . rawurlencode($showname) . '"');
            } else {
                header('Content-Disposition: attachment; filename="' . $showname . '"');
            }

            $fp         = fopen($file_name, "r");
            $buffer     = 1024;
            $file_count = 0;
            // 向浏览器返回数据
            while (!feof($fp) && $file_count < $length) {
                $file_con   = fread($fp, $buffer);
                $file_count = $file_count + $buffer;
                echo $file_con;
            }
            fclose($fp);

        }
        // 文件已被删除！
        return false;
    }

}

if (!function_exists('download_count')) {
    /**
     * 文件下载统计
     * @date 2015-12-22 02:31:26
     */
    function download_count() {
        // TODO 文件下载统计
    }
}

if (!function_exists('get_first_date_and_last_date')) {
    /**
     * 获取给定日期的第一天与最后一天的日期 2016-02-05
     * @param string $date 2016-02-05
     * @return array 第一天和最后一天的数组
     * @date 2015-12-22 19:09:31
     */
    function get_first_date_and_last_date($date) {
        // 获取当前月份第一天
        $begin_date = date('Y-m-01', strtotime($date));
        // 获取当前月份最后一天--加一个月减去一天
        $end_date = date('Y-m-d', strtotime("$begin_date +1 month -1 day"));
        $date_arr = array(
            'first_date' => $begin_date,
            'last_date'  => $end_date,
        );
        return $date_arr;
    }
}

if (!function_exists('get_day_of_the_week')) {
    /**
     * 获取给定日期是星期几
     * @param string $date 2016-02-05
     * @param string $prefix 返回日期的前缀默认（星期）
     * @return array 一周的第几天索引,从0开始（0-6)易于阅读的星期显示日期
     * @date 2015-12-23 11:54:22
     */
    function get_day_of_the_week($date, $prefix = '星期') {
        $date_str = strtotime($date);
        if (!$date_str) {
            return fasle;
        }
        $weekarray  = array("日", "一", "二", "三", "四", "五", "六");
        $week_index = date("w", $date_str);
        $arr        = array(
            'week_index'                 => $week_index,
            'day_of_the_week_str'        => $weekarray[$week_index],
            'day_of_the_week_prefix_str' => $prefix . $weekarray[$week_index],
        );
        return $arr;
    }
}

if (!function_exists('compare_date')) {
    /**
     * 比较两个日期的大小
     * @param string $date1 2015-12-31 13:16:41
     * @param string $date2 2015-12-30 13:16:41
     * @return boolean 大于返回1 等于返回0 小于返回-1
     * @date 2015-12-23 11:54:22
     */
    function compare_date($date1, $date2) {
        if (strtotime($date1) > strtotime($date2)) {
            return 1;
        } elseif (strtotime($date1) == strtotime($date2)) {
            return 0;
        } else {
            return -1;
        }

    }
}

if (!function_exists('calc_date')) {
    /**
     * 计算日期时间差
     * @param string $startdate 2015-12-31 13:16:41
     * @param string $enddate 2015-12-30 13:16:41
     * @return array 相差日期信息
     * @date 2015-12-25 13:38:04
     */
    function calc_date($startdate, $enddate) {
        $date          = floor((strtotime($enddate) - strtotime($startdate)) / 86400);
        $hour          = floor((strtotime($enddate) - strtotime($startdate)) % 86400 / 3600);
        $minute        = floor((strtotime($enddate) - strtotime($startdate)) % 86400 % 3600 / 60);
        $second        = floor((strtotime($enddate) - strtotime($startdate)) % 86400 % 3600 % 60);
        $diff_date_arr = array(
            'date'          => $date,
            'hour'          => $hour,
            'minute'        => $minute,
            'second'        => $second,
            'diff_date_str' => $date . '天' . $hour . '小时' . $minute . '分' . $second . '秒',
        );
        return $diff_date_arr;
    }
}

if (!function_exists('getMimeType')) {
    /**
     * 获取文件类型
     * @param string $path 文件全路径
     * @return string 类似image/jpeg; charset=binary
     */
    function getMimeType($path) {
        $mime  = array(
            // applications
            'ai'   => 'application/postscript',
            'eps'  => 'application/postscript',
            'exe'  => 'application/octet-stream',
            'doc'  => 'application/vnd.ms-word',
            'xls'  => 'application/vnd.ms-excel',
            'ppt'  => 'application/vnd.ms-powerpoint',
            'pps'  => 'application/vnd.ms-powerpoint',
            'pdf'  => 'application/pdf',
            'xml'  => 'application/xml',
            'odt'  => 'application/vnd.oasis.opendocument.text',
            'swf'  => 'application/x-shockwave-flash',
            // archives
            'gz'   => 'application/x-gzip',
            'tgz'  => 'application/x-gzip',
            'bz'   => 'application/x-bzip2',
            'bz2'  => 'application/x-bzip2',
            'tbz'  => 'application/x-bzip2',
            'zip'  => 'application/zip',
            'rar'  => 'application/x-rar',
            'tar'  => 'application/x-tar',
            '7z'   => 'application/x-7z-compressed',
            // texts
            'txt'  => 'text/plain',
            'php'  => 'text/x-php',
            'html' => 'text/html',
            'htm'  => 'text/html',
            'js'   => 'text/javascript',
            'css'  => 'text/css',
            'rtf'  => 'text/rtf',
            'rtfd' => 'text/rtfd',
            'py'   => 'text/x-python',
            'java' => 'text/x-java-source',
            'rb'   => 'text/x-ruby',
            'sh'   => 'text/x-shellscript',
            'pl'   => 'text/x-perl',
            'sql'  => 'text/x-sql',
            // images
            'bmp'  => 'image/x-ms-bmp',
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif'  => 'image/gif',
            'png'  => 'image/png',
            'tif'  => 'image/tiff',
            'tiff' => 'image/tiff',
            'tga'  => 'image/x-targa',
            'psd'  => 'image/vnd.adobe.photoshop',
            // audio
            'mp3'  => 'audio/mpeg',
            'mid'  => 'audio/midi',
            'ogg'  => 'audio/ogg',
            'mp4a' => 'audio/mp4',
            'wav'  => 'audio/wav',
            'wma'  => 'audio/x-ms-wma',
            // video
            'avi'  => 'video/x-msvideo',
            'dv'   => 'video/x-dv',
            'mp4'  => 'video/mp4',
            'mpeg' => 'video/mpeg',
            'mpg'  => 'video/mpeg',
            'mov'  => 'video/quicktime',
            'wm'   => 'video/x-ms-wmv',
            'flv'  => 'video/x-flv',
            'mkv'  => 'video/x-matroska',
        );
        $fmime = getMimeDetect();
        switch ($fmime) {
            case 'finfo':
                $finfo = finfo_open(FILEINFO_MIME);
                if ($finfo)
                    $type = @finfo_file($finfo, $path);
                finfo_close($finfo);
                break;
            case 'mime_content_type':
                $type = mime_content_type($path);
                break;
            case 'linux':
                $type = exec('file -ib ' . escapeshellarg($path));
                break;
            case 'bsd':
                $type = exec('file -Ib ' . escapeshellarg($path));
                break;
            default:
                $pinfo = pathinfo($path);
                $ext   = isset($pinfo['extension']) ? strtolower($pinfo['extension']) : '';
                $type  = isset($mime[$ext]) ? $mime[$ext] : 'unkown';
                break;
        }
        $type = explode(';', $type);
        // 需要加上这段，因为如果使用mime_content_type函数来获取一个不存在的$path时会返回'application/octet-stream'
        if ($fmime != 'internal' AND $type[0] == 'application/octet-stream') {
            $pinfo = pathinfo($path);
            $ext   = isset($pinfo['extension']) ? strtolower($pinfo['extension']) : '';
            if (!empty($ext) AND !empty($mime[$ext])) {
                $type[0] = $mime[$ext];
            }
        }
        return $type[0];
    }
}

if (!function_exists('getMimeDetect')) {
    function getMimeDetect() {
        if (class_exists('finfo')) {
            return 'finfo';
        } else if (function_exists('mime_content_type')) {
            return 'mime_content_type';
        } else if (function_exists('exec')) {
            $result = exec('file -ib ' . escapeshellarg(__FILE__));
            if (0 === strpos($result, 'text/x-php') OR 0 === strpos($result, 'text/x-c++')) {
                return 'linux';
            }
            $result = exec('file -Ib ' . escapeshellarg(__FILE__));
            if (0 === strpos($result, 'text/x-php') OR 0 === strpos($result, 'text/x-c++')) {
                return 'bsd';
            }
        }
        return 'internal';
    }
}
