<?php

/**
 * Created by PhpStorm.
 * User: zhaohongyu
 * Date: 2016/10/10
 * Time: 18:15
 */
abstract class Base {

    /**
     * 要抓取的网站的url
     *
     * @var string
     */
    protected $fetch_url = null;

    /**
     * 用户cookie
     *
     * @var string
     */
    protected $user_cookie = null;

    /**
     * ss信息存放对象
     *
     * @var SsInfo
     */
    protected $SsInfo = null;

    /**
     * Base constructor.
     *
     * @param $fetch_url
     * @param $user_cookie
     */
    public function __construct($fetch_url, $user_cookie) {
        if (empty($fetch_url) || empty($user_cookie)) {
            throw new Exception("fetch_url或者user_cookie不能为空", -1);
        }
        $this->fetch_url   = $fetch_url;
        $this->user_cookie = $user_cookie;
    }

    /**
     * 获取html内容
     */
    public function getHtml() {
        throw new Exception("没有重写" . __FUNCTION__ . "方法", -2);
    }

    /**
     * 解析html内容
     */
    public function parseHtml($response_html) {
        throw new Exception("没有重写" . __FUNCTION__ . "方法", -2);
    }

}