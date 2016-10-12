<?php

/**
 * Created by PhpStorm.
 * User: zhaohongyu
 * Date: 2016/10/10
 * Time: 18:44
 */

spl_autoload_register(function ($class) {
    $require_path = dirname(__FILE__) . '/' . $class . '.php';
    require_once $require_path;
});

require_once(FCPATH . 'myclass/file_line_operate.php');

class ss_fast extends Base {

    public function __construct($fetch_url = '', $user_cookie = '') {
        $fetch_url   = empty($fetch_url) ? "https://www.ss-fast.com/ucenter/?act=free_plan" : $fetch_url;
        $user_cookie = empty($user_cookie) ? "ss_secret=6bbe%2B%2BSA8RBmuwBNmtdQe1OiuX3Cf7iOOSR3BDgCGzVnhmvvU9%2FDIUhR22rNNUWGbVmIzR57%2BH7O3jJnIjFH6miWR3PB1e2nny7179HQg9%2FRRlHKZE16HRRrexoTdH6oKHb6x3MGaS1gdF%2B8dbqS; PHPSESSID=fd0b2794949b7076a8b0103cbde59454" : $user_cookie;
        parent::__construct($fetch_url, $user_cookie);
    }

    public function getHtml() {

        // Get cURL resource
        $ch = curl_init();

        // Set url
        curl_setopt($ch, CURLOPT_URL, $this->fetch_url);

        // Set method
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        // Set options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);// 连接服务器前等待多久
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);// 从服务器接收缓冲完成前需要等待多长时间

        // Set headers
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Pragma: no-cache",
                "Cookie: " . $this->user_cookie,
                "User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.143 Safari/537.36",
            ]
        );

        // Send the request & save response to $resp
        $resp = curl_exec($ch);

        if (!$resp) {
            $err_msg = 'Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch);
            throw new Exception($err_msg, curl_errno($ch));
        }

        // Close request to clear up some resources
        curl_close($ch);

        return $resp;
    }

    public function parseHtml($response_html) {

        if (empty($response_html)) {
            return null;
        }

        preg_match("/<tbody>([\s\S]*?)<\/tbody>/", $response_html, $matches1);
        preg_match("/<tr>([\s\S]*?)<\/tr>/", $matches1[1], $matches2);
        preg_match_all("/<td>([\s\S]*?)<\/td>/", $matches2[1], $matches3);

        if (empty($matches3[1])) {
            return null;
        }

        $ss_info_arr  = $matches3[1];
        $domain       = $ss_info_arr[2];
        $port         = $ss_info_arr[3];
        $encrypt_type = $ss_info_arr[4];
        $password     = $ss_info_arr[5];
        return new SsInfo($domain, $port, $encrypt_type, $password);
    }

    public function getSsInfo() {
        $response_html = $this->getHtml();
        if (empty($response_html)) {
            return null;
        }
        return $this->parseHtml($response_html);
    }

    public static function updateConf() {
        $fetch_url   = "https://www.ss-fast.com/ucenter/?act=free_plan";
        $user_cookie = "ss_secret=6bbe%2B%2BSA8RBmuwBNmtdQe1OiuX3Cf7iOOSR3BDgCGzVnhmvvU9%2FDIUhR22rNNUWGbVmIzR57%2BH7O3jJnIjFH6miWR3PB1e2nny7179HQg9%2FRRlHKZE16HRRrexoTdH6oKHb6x3MGaS1gdF%2B8dbqS; PHPSESSID=fd0b2794949b7076a8b0103cbde59454";

        $ss_fast = new ss_fast($fetch_url, $user_cookie);

        $SsInfo = $ss_fast->getSsInfo();

        if (empty($SsInfo)) {
            exit();
        }

        $conf_path   = "/data/work/Surge/custom_rule.conf";
        $server_name = "remoteserver";
        $conf        = "{$server_name} = custom," . $SsInfo->domain . "," . $SsInfo->port . "," . $SsInfo->encrypt_type . "," . $SsInfo->password . ",http://nat.pw/ss.module";
        delTargetLine($conf_path, $server_name);// 删除原有的记录
        insertAfterTarget($conf_path, $conf, "donghui");// 添加上新的记录
    }

}