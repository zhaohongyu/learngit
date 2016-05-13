<?php

class PublicAction extends Action {

    public function index() {
        $this->login();
    }

    public function login() {
        $this->display();
    }

    /**
     * 生成验证码
     */
    public function verifyImg() {
        import('ORG.Util.Image');
        echo Image::buildImageVerify(4, 1, 'png', 75, 28, 'verify');
    }

    public function checkLogin() {
        if (md5(strtoupper($_POST['verify'])) != $_SESSION['verify']) {
            //验证码错误
            $this->error(L("public_checkLogin_verify_error_error"));
        }
        if (empty($_POST['username'])) {
            //验证码错误
            $this->error(L("public_checkLogin_username_error"));
        } elseif (empty($_POST['password'])) {
            //密码必须填写
            $this->error(L("public_checkLogin_password_error"));
        }

        //生成认证条件
        $map = array();
        // 支持使用绑定帐号登录
        $map['username'] = $_POST['username'];
        import('ORG.Util.RBAC');
        $authInfo = RBAC::authenticate($map);
        //使用用户名、密码和状态的方式进行认证
        if (false === $authInfo) {
            //帐号不存在或已禁用
            $this->error(L("public_checkLogin_error"));
        } else {
            if ($authInfo['password'] != md5($_POST['password'])) {
                $this->assign("error", '密码错误！');
                //密码错误  提示给用户  用户名或密码不正确
                $this->error(L("public_checkLogin_password_not_correct"));
            }
            $_SESSION[C('USER_AUTH_KEY')] = $authInfo['id'];
            if ($authInfo['username'] == 'admin') {
                //开启超级管理员认证标识
                $_SESSION['administrator'] = true;
            }
            // 缓存访问权限
            RBAC::saveAccessList();
            $time = time();
            //保存用户信息到session  这里缓存的都是上次登录的ip 登录时间
            $_SESSION['user_info'] = $authInfo;
            //更新最后登录时间
            $authInfo["login_time"] = $time;
            //更新记录最后更新时间
            $authInfo["update_time"] = $time;
            //更新最后登录IP
            $authInfo["login_ip"] = get_client_ip();
            //更新登录次数
            $authInfo["login_count"] = $authInfo["login_count"] + 1;
            D("User")->save($authInfo);
            //封装数据存储到UserActionLog表
            $data['uid'] = $authInfo['id'];
            $data['action'] = 'login';
            //获得用户设备信息
            $shebei = getUserOS() . "/" . getUserBrower();
            $data['client'] = $shebei;
            $data['ctime'] = $time;
            $data['ip'] = get_client_ip();
            $_SESSION['UserId'] = $authInfo['id'];
            $_SESSION['UserName'] = $authInfo['username'];
            $_SESSION['UserToken'] = md5($_SESSION['UserId'] . session_id());
            $data['token'] = $_SESSION['UserToken'];
            $data['username'] = $_SESSION['UserName'];
            ////保存数据存储到UserActionLog表
            M('UserActionLog')->add($data);
            $this->assign("jumpUrl", getUrl("Index/index"));
            //登录成功
            $this->success(L("public_checkLogin_login_success"));
        }
    }

    public function loginout() {
        if (isset($_SESSION[C('USER_AUTH_KEY')])) {
            //销毁token
            $map['token'] = $_SESSION['UserToken'];
            //清空token值,不进行删除纪录
            $new_map['token']='';
            M('UserActionLog')->where($map)->save($new_map);
            unset($_SESSION[C('USER_AUTH_KEY')]);
            unset($_SESSION);
            session_destroy();
            
            //2014-8-22 13:17:00
            $today = date("Y-m-d");
            $two_day_before=date("Y-m-d", strtotime("$today-1 day"));
            $ctime=  strtotime($two_day_before);
            //删除小于等于前天日期的登录记录,防止数据库数据过多
            M('UserActionLog')->where(array("ctime"=>array('elt',$ctime)))->delete();
            
            
            //登出成功
            $this->success(L("public_checkLogin_loginout_success"), getUrl("Public/login"));
        } else {
            //已经登出
            $this->success(L("public_checkLogin_loginout_already"));
        }
    }

    public function noauth() {
        $this->display("deny");
    }
    /*
     * 根据用户输入的关键字实时反馈给用户提示
     * 2014-8-29 11:33:49
     */
    public function searchUser(){
        if(IS_AJAX){
            //搜索关键字
            $real_name=I("param.search-text",'');
            if(!empty($real_name)){
                $condition["real_name"] = array('like', "%" . $real_name . "%");
                //最多取5条
                $res=D("User")->where($condition)->limit("5")->select();
                //循环取出数组中每条记录
                $temp = array();
                foreach ($res as $v) {
                    //取出被搜索的字符串在数组中出现的次数,如果大于0 说明符合候选条件
                    $res_count = substr_count($v['real_name'], $real_name);
                    if ($res_count >= 1) {
                        $temp[] = $v['real_name'];
                    }
                }
                echo json_encode($temp,true);
            }
        }
    }

}

?>