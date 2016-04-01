<?php
// 使用时引入此文件即可
// require_once '/Library/WebServer/Documents/socketlogtool/socketlog_config.php';

require_once '/Library/WebServer/Documents/SocketLog/php/slog.function.php';
//配置
slog(array(
    'host'                => 'localhost',//websocket服务器地址，默认localhost
    'optimize'            => false,//是否显示利于优化的参数，如果运行时间，消耗内存等，默认为false
    'show_included_files' => false,//是否显示本次程序运行加载了哪些文件，默认为false
    'error_handler'       => false,//是否接管程序错误，将程序错误显示在console中，默认为false
    'force_client_ids'    => array(//日志强制记录到配置的client_id,默认为空,client_id必须在allow_client_ids中
        'hongyuzhao_bb635a',
        //'client_02',
    ), 
    'allow_client_ids'    => array(//限制允许读取日志的client_id，默认为空,表示所有人都可以获得日志。
        'hongyuzhao_bb635a',
        //'client_02',
        //'client_03',
    ), 
),'config');

if (!function_exists('show_msg')) {
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
}

if (!function_exists('mylog')) {
    function mylog($data, $path = '/tmp/debug.log') {
        file_put_contents($path, serialize($data) . "\r\n", FILE_APPEND);
    }
}






