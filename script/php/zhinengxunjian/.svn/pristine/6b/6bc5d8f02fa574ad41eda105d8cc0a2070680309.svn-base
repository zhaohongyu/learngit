<?php

class IndexAction extends CommonAction {
    
    /*
     * 主框架部分
     */
    public function index() {
        //从session中读取用户信息
        $this->assign("user_info", $_SESSION['user_info']);
        $this->display();
    }

    /*
     * 生成head部分
     */
    public function head() {
        //从session中读取用户信息
        $this->assign("user_info", $_SESSION['user_info']);
        $this->display();
    }

    /*
     *生成left部分 缓存
     */
    public function left() {
        if (!empty($_SESSION['action_list']) && !empty($_SESSION['method_list'])) {
            //存储到session当中不用每次都去数据库查询
            //更新角色权限后需退出登录后才能看到新的菜单
            $action_list = $_SESSION['action_list'];
            $method_list = $_SESSION['method_list'];
        } else {
            $user_id = $_SESSION['user_info']["id"];
            if ($_SESSION['administrator']) {
                //超级管理员不做限制
                //过滤掉Index模块
                $hide_model = array("Index"); //要隐藏的2级菜单
                $action_list = D("Node")->where(array("level" => 2, "status" => 1, "name" => array("not in", $hide_model)))->order("sort_menu desc")->select();
                //去掉,更新和删除显示 因为没有指定id是无法操作成功的所以还是不显示给用户了
                //要隐藏的3级菜单
                $hide_action = C("HIDE_ACTION");
                $method_list = D("Node")->where(array("level" => 3, "status" => 1, "name" => array('not in', $hide_action)))->select();
                $_SESSION['action_list'] = $action_list;
                $_SESSION['method_list'] = $method_list;
            } else {
                //过滤掉Index模块
                $action_list = D("User")->getMenu($user_id, 2);
                $method_list = D("User")->getMenu($user_id, 3);
                $_SESSION['action_list'] = $action_list;
                $_SESSION['method_list'] = $method_list;
            }
        }
        $this->assign("action_list", $action_list);
        $this->assign("method_list", $method_list);
        $this->display();
    }

    /*
     * 生成right部分
     */
    public function right() {
        if (!empty($_SESSION['ip_info'])) {
            $ip_info = $_SESSION['ip_info'];
        } else {
            //http://ip.taobao.com/service/getIpInfo.php?ip=223.203.193.253
            $url = C("URL_IP_SEARCH") . $_SESSION['user_info']['login_ip'];
            import("ORG.Util.Curl.CurlUtil");
            $curlutil = new CurlUtil();
            $result = $curlutil->getInfoByPost($url, null);
            if ($result['code'] == 0) {
                $ip_info = $result['data'];
                $_SESSION['ip_info'] = $ip_info;
            }
        }
        
        $countHiddentrouble=D("HiddenTrouble")->where(array("status"=>0))->count();
        $countException=D("ExceptionRecord")->where(array("device_status"=>0))->count();
        $content="";
        $title="";
        $url=getUrl("Warning/exception");
        if($countHiddentrouble!=0&&$countHiddentrouble!=''){
            $content.="<span style='color:red;font-size:21px;'>".$countHiddentrouble."</span>个隐患信息未处理 ";
        }
        if($countException!=0&&$countException!=''){
            $content.="<span style='color:red;font-size:21px;'>".$countException."</span>个异常信息未处理。";
        }
        //分配隐患信息记录数和异常信息记录数生成的提示信息到模板
        $this->assign("content",  $content);
        $this->assign("title",  $title);
        $this->assign("url",  $url);
        $this->assign("countHiddentrouble",  $countHiddentrouble);
        $this->assign("countException",  $countException);

        //在线人数分配给模板
        $result=D('User')->getOnline($client="iPhone");
        $this->assign("countOnline",  $result['count']);
        //分配系统信息到界面
        $this->assign("system_info",  $this->getServerInfo());
        $this->assign("ip_info", $ip_info);
        //从session中读取用户信息
        $this->assign("user_info", $_SESSION['user_info']);
        $this->display();
    }

}
