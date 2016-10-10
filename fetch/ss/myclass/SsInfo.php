<?php

/**
 * Created by PhpStorm.
 * User: zhaohongyu
 * Date: 2016/10/10
 * Time: 18:42
 */
class SsInfo {

    /**
     * 域名
     *
     * @var string
     */
    public $domain = null;

    /**
     * 端口
     *
     * @var string
     */
    public $port = null;

    /**
     * 加密方式
     *
     * @var string
     */
    public $encrypt_type = null;

    /**
     * 密码
     *
     * @var string
     */
    public $password = null;

    /**
     * Ss_info constructor.
     *
     * @param string $domain
     * @param string $port
     * @param string $encrypt_type
     * @param string $password
     */
    public function __construct($domain, $port, $encrypt_type, $password) {
        $this->domain       = $domain;
        $this->port         = $port;
        $this->encrypt_type = $encrypt_type;
        $this->password     = $password;
    }

}