<?php

use \Curl\Curl;

class Send_Msg {

    // curl
    private $curl = null;
    // 接收人
    private $receiver_arr = array(
        '18518622175',
        '15932036568',
    );
    // 短信url 名称_请求方式=>请求地址加参数
    private $sms_url_arr = array(
        'renrendai_post' => 'https://m.we.com/2.0/login/gensmscode.action?version=2.0&clientVersion=20000&channelCode=wap&mobile=%s&type=REGISTER',
        'linking_get' => 'http://linking.baidu.com/finance/msg/sendVerifyCode?phoneNum=%s',
        'jql_get' => 'http://m.jql.cn/?user&&q=check_phone&phone=%s&_=1448601493438',
    );

    public function __construct() {
        $this->mergeData();
        require_once APPPATH . '/Curl/Curl.php';
        $this->curl = new Curl();
        // 不使用https方式
        $this->curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
        // USER-AGENT
        $this->curl->setOpt(CURLOPT_USERAGENT, 'Mozilla/5.0 (iPhone; CPU iPhone OS 9_1 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13B143 Safari/601.1');
    }

    // 从配置文件中读取数据合并
    private function mergeData() {
        $receiver = parse_ini_file(RECEIVER_PATH);
        $sms_url = parse_ini_file(SMS_URL_PATH);
        $this->receiver_arr = array_merge($receiver, $this->receiver_arr);
        $this->sms_url_arr = array_merge($sms_url, $this->sms_url_arr);
    }

    // 测试 发送单条接口是否通畅
    public function test() {

        // 随机取出收信息的人
        $max = count($this->receiver_arr);
        $rec = mt_rand(0, $max - 1);
        $mobile = $this->receiver_arr[$rec];
        global $param;
        $url = $param;
        $url = sprintf($url, $mobile);
        ///*
        //$url = "http://mytestci.com/index.php/test/test/index";
        // 设置refer
        $this->curl->autoSetReferer($url);
        // 构造IP
        $ip = $this->ip_generate();
        $this->curl->setOpt(CURLOPT_HTTPHEADER, array("X-FORWARDED-FOR:{$ip}", "CLIENT-IP:{$ip}"));


        parse_str($url, $arr);
        if (isset($arr['method']) && $arr['method'] == 'get') {
            $this->sendGet($url);
        } else {
            $this->sendPost($url);
        }
	show_msg($mobile,false);
        show_msg($this->curl->response);

        // */
    }

    // 发送短信
    public function send_sms($param = '') {
        foreach ($this->receiver_arr as $mobile) {
            foreach ($this->sms_url_arr as $company => $url) {
                // 设置refer
                $this->curl->autoSetReferer($url);
                // 构造IP
                $ip = $this->ip_generate();
                $this->curl->setOpt(CURLOPT_HTTPHEADER, array("X-FORWARDED-FOR:{$ip}", "CLIENT-IP:{$ip}"));
                $company_tmp = explode('_', $company);
                $url = sprintf($url, $mobile);
                if ($company_tmp[1] == 'post') {
                    // post方式
                    $this->sendPost($url);
                } else {
                    // get方式
                    $this->sendGet($url);
                }
                // 记录日志
                $this->sendLog($url, $mobile, $company_tmp[1], $ip);
            }
        }
        $this->curl->close();
        die();
    }

    // 发送post请求
    private function sendPost($url) {
        // 提取请求数组
        $post_data = $this->getPostData($url);
        // 请求url
        $post_url = $this->getPostUrl($url);
        // 发送post请求
        $this->curl->post($post_url, $post_data);
    }

    // 发送get请求
    private function sendGet($url) {
        $this->curl->get($url);
    }

    // 记录日志
    private function sendLog($url, $mobile, $method, $ip) {
        $time = date('Y-m-d H:i:s');
        if ($this->curl->error) {
            $error_msg = 'Error: ' . $this->curl->error_code . ': ' . $this->curl->error_message;
            $msg = "{$ip} - - [{$time}] 使用 [{$method}] 方式通过 [{$url}] 给 [{$mobile}] 发送的短信失败了，失败的原因是[{$error_msg}]";
        } else {
            $msg = "{$ip} - - [{$time}] 成功使用 [{$method}] 方式通过 [{$url}] 给 [{$mobile}] 发送了一条短信。";
        }
        file_put_contents(LOG_PATH, "{$msg}\r\n", FILE_APPEND);
    }

    // 获取请求参数
    private function getUrlQuery($url) {
        $url_arr = parse_url($url);
        return $url_arr['query'];
    }

    // 将请求参数转换成数组
    private function s2a($str) {
        $arr = array();
        parse_str($str, $arr);
        return $arr;
    }

    // 根据url返回请求的数组
    private function getPostData($url) {
        return $this->s2a($this->getUrlQuery($url));
    }

    // 获取请求url
    private function getPostUrl($url) {
        $url_arr = parse_url($url);
        return $url_arr['scheme'] . '://' . $url_arr['host'] . $url_arr['path'];
    }

    private function ip_generate() {
        $ip0 = mt_rand(1, 255);
        $ip1 = mt_rand(1, 255);
        $ip2 = mt_rand(1, 255);
        $ip3 = mt_rand(1, 255);
        return "{$ip0}.{$ip1}.{$ip2}.{$ip3}";
    }

}
