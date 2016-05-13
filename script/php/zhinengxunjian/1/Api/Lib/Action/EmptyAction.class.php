<?php

class EmptyAction extends CommonAction {

    /**
     * 空操作
     */
    public function _empty() {
        //header("Content-Type: text/html;charset=utf-8");
        redirectUrl(PHP_FILE . getServerAddrAndPort());
    }

}
