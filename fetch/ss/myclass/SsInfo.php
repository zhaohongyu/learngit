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
     * @param string $encrypt_type
     * @param string $password
     */
    public function __construct($domain, $encrypt_type, $password) {
        $this->domain       = $domain;
        $this->encrypt_type = $encrypt_type;
        $this->password     = $password;
    }

    /**
     * @return string
     */
    public function getDomain() {
        return $this->domain;
    }

    /**
     * @param string $domain
     */
    public function setDomain($domain) {
        $this->domain = $domain;
    }

    /**
     * @return string
     */
    public function getEncryptType() {
        return $this->encrypt_type;
    }

    /**
     * @param string $encrypt_type
     */
    public function setEncryptType($encrypt_type) {
        $this->encrypt_type = $encrypt_type;
    }

    /**
     * @return string
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password) {
        $this->password = $password;
    }

}