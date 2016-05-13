<?php

//初始接口
class CommonAction extends Action {

    function _initialize() {
        // 用户权限检查
        if (C('USER_AUTH_ON') && !in_array(MODULE_NAME, explode(',', C('NOT_AUTH_MODULE')))) {
            import('ORG.Util.RBAC');
            if (!RBAC::AccessDecision()) {
                //检查认证识别号
                if (!$_SESSION [C('USER_AUTH_KEY')]) {
                    //跳转到认证网关
                    redirect(PHP_FILE . C('USER_AUTH_GATEWAY'));
                }
                // 没有权限 抛出错误
                if (C('RBAC_ERROR_PAGE')) {
                    // 定义权限错误页面
                    redirect(C('RBAC_ERROR_PAGE'));
                } else {
                    if (C('GUEST_AUTH_ON')) {
                        $this->assign('jumpUrl', PHP_FILE . C('USER_AUTH_GATEWAY'));
                    }
                    // 提示错误信息 对不起,您暂时还没有权限进行此操作
                    //$this->error(L("common_initialize_no_auth"), getUrl("Public/noauth"));
                    $this->error(L("common_initialize_no_auth"), getUrl("Index/index"));
                }
            }
        }
    }


    /**
     * 生成验证码
     */
    public function verifyImg() {
        import('ORG.Util.Image');
        echo Image::buildImageVerify(4, 1, 'png', 75, 28, 'verify');
    }

    /**
     * 获取当前用户的ID
     * @return type 用户id
     */
    protected function getUserId() {
        return isset($_SESSION[C('USER_AUTH_KEY')]) ? $_SESSION[C('USER_AUTH_KEY')] : 0;
    }

    /**
     * 清除Cookie
     * @param unknown_type $cookieName
     */
    protected function clearCookie($cookieName) {
        setcookie($cookieName, null, getSystemTimeStamp() - 31536000, '/');
    }

    /**
     * 上传文件
     * @param type $savePath 上传路径 视频和图片请区分放置
     * @return type
     */
    protected function _upload($savePath) {
        //监测文件夹是否存在,不存在则创建
        if (!is_dir($savePath)) {
            mkdir($savePath);
        }
        import('ORG.Net.UploadFile');
        $config['savePath'] = $savePath; // 文件路径;
        $config['maxSize'] = 20971520; //20M最大
        $config['thumb'] = true;
        $config['thumbPrefix'] = 'm_,s_';
        $config['thumbMaxWidth'] = '200,50';
        $config['thumbMaxHeight'] = '200,50';
        $config['allowExts'] = array('jpg', 'gif', 'png', 'jpeg', "mov", "mp4", "flv", 'JPG', 'GIF', 'PNG', 'JPEG', "MOV", "MP4", "FLV");
        $config['autoSub'] = true; // 开启子目录保存
        $config['subType'] = 'date'; //// 时间保存
        $config['dateFormat'] = 'Y-m-d';
        $upload = new UploadFile($config); // 实例化上传类并传入参数
        if ($upload->upload()) {
            return $upload->getUploadFileInfo();
        } else {
            // 捕获上传异常
            return $upload->getErrorMsg();
        }
    }
    
    /*
     * 获取服务器信息
     */
    public function getServerInfo() {
        $systeminfo = array();
        // 服务器信息
        $serverinfo[L('framework_core_version')]		= 'ThinkPHP ' . THINK_VERSION;
        $serverinfo[L('server_system_php_version')]	 	= PHP_OS.' / PHP v'.PHP_VERSION;
        $serverinfo[L('server_time')]					= date("Y年n月j日 H:i:s");
        $serverinfo[L('server_operating_system')] 		= $_SERVER['SERVER_SOFTWARE'];
        $serverinfo[L('server_domain_ip')]				= $_SERVER['SERVER_NAME'].' [ '.gethostbyname($_SERVER['SERVER_NAME']).' ]';
        $serverinfo[L('upload_max_filesize')]     		= ini_get('file_uploads') ? ini_get('upload_max_filesize') : '<font color="red">no</font>';
        $serverinfo[L('server_disk_space')]				= round((disk_free_space(".")/(1024*1024)),2).'M';
        $mysqlinfo = M()->query("SELECT VERSION() as version");
        $serverinfo[L('database_version')]				= $mysqlinfo[0]['version'];
        $t = M()->query("SHOW TABLE STATUS LIKE '" . C('DB_PREFIX') . "%'");
        $dbsize = 0;
        foreach ($t as $k){
                $dbsize += $k['Data_length'] + $k['Index_length'];
        }
        $serverinfo[L('database_using')]				= byte_format($dbsize);
        $systeminfo[L('server_info')] 					= $serverinfo;
        unset($serverinfo);
        return $systeminfo;
    }

}

?>