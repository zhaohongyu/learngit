<?php

/**
 * @Description of UserAction  人员管理
 * @author Zhao-Hong-Yu <ZhaoHongYu, zhao_hong_yu@sina.cn> 
 * @copyright Copyright (C) 2014 赵洪禹 
 * @datetime 2014-6-17  15:24:25
 * @version 1.0
 */
class UserAction extends CommonAction {
    /*
     * 列出所有用户
     */

    public function index() {
        //去查询界面 该界面指定出 部门—专业—职位—姓名
        //查询所有启用状态下的部门
        $dept_list = D("Dept")->where(array("status" => 1))->select();
        //查询出所启用状态下的专业
        $specialty_list = D("Specialty")->where(array("status" => 1))->select();
        //查询所有启用状态下的职位
        $organization_list = D("Organization")->where(array("status" => 1))->select();
        //分页查询员工信息
        $result = D("User")->get_user_list();
        //给模板分配查询到的用户信息
        $this->assign("user_list", $result["user_list"]);
        $this->assign("organization_list", $organization_list);
        $this->assign("specialty_list", $specialty_list);
        $this->assign("dept_list", $dept_list);
        $this->assign("page", $result["page"]);
        //从session中读取用户信息
        $this->assign("user_info", $_SESSION['user_info']);
        $this->display("show");
    }

    /**
     * 根据条件搜索要设定任务的员工
     *  [dept_id] => 18
      [specialty_id] => 1
      [organization_id] => 70
      [real_name] => 赵洪禹
     */
    public function serach() {
        //接受用户提交的搜索条件
        $dept_id = I("param.dept_id", 0);
        $specialty_id = I("param.specialty_id", 0);
        $organization_id = I("param.organization_id", 0);
        $real_name = I("param.real_name", '');
        //拼装搜索条件
        $condition = array();
        if ($dept_id != 0) {
            $condition["user.dept_id"] = $dept_id;
        }
        if ($specialty_id != 0) {
            $condition["user.specialty_id"] = $specialty_id;
        }
        if ($organization_id != 0) {
            $condition["user.organization_id"] = $organization_id;
        }
        if (!empty($real_name)) {
            $condition["user.real_name"] = array('like', "%" . $real_name . "%");
        }
        $result = D("User")->get_user_list_by_condition($condition, $real_name);
        $user_list = $result["user_list"];
        //去查询界面 该界面指定出 部门—专业—职位—姓名
        //查询所有启用状态下的部门
        $dept_list = D("Dept")->where(array("status" => 1))->select();
        //查询出所启用状态下的专业
        $specialty_list = D("Specialty")->where(array("status" => 1))->select();
        //查询所有启用状态下的职位
        $organization_list = D("Organization")->where(array("status" => 1))->select();
        $this->assign("user_list", $user_list);
        $this->assign("organization_list", $organization_list);
        $this->assign("dept_list", $dept_list);
        $this->assign("specialty_list", $specialty_list);
        $this->assign("dept_id", $dept_id);
        $this->assign("specialty_id", $specialty_id);
        $this->assign("organization_id", $organization_id);
        $this->assign("real_name", $real_name);
        $this->assign("page", $result["page"]);
        $this->display("show");
    }

    /*
     * 增加用户
     */

    public function add() {
        if (!empty($_POST)) {
            //$username_status  0==>用户未填写 1==>可以使用 2==>已经被注册
            $username_status = isset($_POST["username_status"]) ? intval($_POST["username_status"]) : 0;
            if (1 != $username_status) {
                //您的用户账号填写有误
                $this->error(L("user_add_error_0"));
            } else {
                $user_model = new UserModel();
                $formdata = $user_model->create();
                if ($formdata) {
                    $rst = $user_model->add();
                    //获取刚刚插入用户的id
                    $user_id = $user_model->getLastInsID();
                    $role_id = $_POST['role_id'];
                    //操作用户角色表 插入数据
                    $role_user_model = M("RoleUser");
                    $role_user_data = array(
                        "role_id" => $role_id,
                        "user_id" => $user_id,
                    );
                    if ($rst > 0) {
                        $rst2 = $role_user_model->add($role_user_data);
                        if ($rst2 == 1) {
                            //添加用户成功
                            $this->success(L("user_add_success"), getUrl("User/index"));
                        } else {
                            //添加用户失败,请稍后重试！
                            $this->error(L("user_add_failed"));
                        }
                    } else {
                        //添加用户失败,请稍后重试！
                        $this->error(L("user_add_failed"));
                    }
                } else {
                    $this->error($user_model->getError());
                }
            }
        } else {
            // 查询所有角色
            $role_list = M("Role")->where(array("status" => 1))->select();
            // 查询所有部门
            $dept_list = M("Dept")->where(array("status" => 1))->select();
            // 查询职位
            $organization_list = M("Organization")->where(array("status" => 1))->select();
            // 查询所有启用的专业
            $specialty_list = M("Specialty")->where(array("status" => 1))->select();
            $this->assign("role_list", $role_list);
            $this->assign("dept_list", $dept_list);
            $this->assign("organization_list", $organization_list);
            $this->assign("specialty_list", $specialty_list);
            $this->display();
        }
    }

    /*
     * 删除用户
     */

    public function del() {
        $user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $User = M("User"); // 实例化User对象
        //删除user表中的用户记录
        $rst = $User->where(array("id" => $user_id))->delete();
        if ($rst > 0) {
            //删除role_user表中的记录
            $role_user_model = M("RoleUser");
            $rst2 = $role_user_model->where(array("user_id" => $user_id))->delete();
            if ($rst2 > 0) {
                //删除用户成功
                $this->success(L("user_del_success"), getUrl("User/index"));
            } else {
                //删除用户失败,请稍后重试！
                $this->error(L("user_del_failed"));
            }
        } else {
            //删除用户失败,请稍后重试！
            $this->error(L("user_del_failed"));
        }
    }

    /*
     * 去更新用户界面
     */

    public function update() {
        if (!empty($_POST)) {
            $user_model = new UserModel();
            //收集用户提交的数据
            $formdata = $user_model->create();
            if ($formdata) {
                $rst = $user_model->save($formdata);
                if ($rst) {
                    //用户更新成功！
                    //变更用户所属角色id在in_role_user中的数据
                    $map["role_id"]=$formdata["role_id"];
                    M("RoleUser")->where(array("user_id"=>$formdata["id"]))->save($map);
                    $this->success(L("user_update_success"), getUrl("User/index"));
                } else {
                    //用户更新失败,请稍后重试
                    $this->error(L("user_update_failed"));
                }
            } else {
                //表单数据填写错误 给出相应提示
                $this->error($user_model->getError());
            }
        } else {
            $id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
            $user_info = D("User")->where(array("id" => $id))->find();
            // 查询所有已经启用角色
            $role_list = M("Role")->where(array("status" => 1))->select();
            // 查询所有已经启用部门
            $dept_list = M("Dept")->where(array("status" => 1))->select();
            // 查询已经启用职位
            $organization_list = M("Organization")->where(array("status" => 1))->select();
            // 查询所有启用的专业
            $specialty_list = M("Specialty")->where(array("status" => 1))->select();
            $this->assign("user_info", $user_info);
            $this->assign("role_list", $role_list);
            $this->assign("dept_list", $dept_list);
            $this->assign("organization_list", $organization_list);
            $this->assign("specialty_list", $specialty_list);
            $this->display();
        }
    }

    /*
     * 检查用户名是否存在 
     */

    public function check_username() {
        $username = isset($_POST["username"]) ? $_POST["username"] : '';
        if ($this->isAjax()) {
            if (empty($username)) {
                //提示用户不能为空
                $this->ajaxReturn("", "账号不能为空", 0);
            } else {
                //去数据库查询用户是否已经存在
                $user_info = D("User")->where(array("username" => $username))->find();
                if (empty($user_info)) {
                    //可以使用
                    $this->ajaxReturn("", "恭喜您,该账号可以使用!", 1);
                } else {
                    //已经被占用
                    $this->ajaxReturn("", "该账号已经被注册!", 2);
                }
            }
        }
    }

    /*
     * 修改用户密码
     */

    public function modifypwd() {
        if (!empty($_POST)) {
            $oldpassword = I("param.oldpassword", '');
            $password = I("param.password", '');
            $repassword = I("param.repassword", '');
            $user_info = $_SESSION['user_info'];
            if ($oldpassword == '' || $password == '' || $repassword == '') {
                $this->error(L("user_modifypwd_empty"));
            }
            if (md5($oldpassword) != $user_info['password']) {
                $this->error(L("user_modifypwd_not_equal_oldpassword"));
            }
            if ($password != $repassword) {
                $this->error(L("user_modifypwd_not_equal"));
            }
            if ($password == $oldpassword) {
                $this->error(L("user_modifypwd_not_old_new_equal"));
            }
            //都符合条件去更新记录
            $map_user['password'] = md5($password);
            $map_user["id"] = $user_info['id'];
            $rst = D("User")->save($map_user);
            if ($rst) {
                $this->success(L("user_modifypwd_success"), getUrl("Index/right"));
            } else {
                $this->error(L("user_modifypwd_failed"));
            }
        } else {
            $this->display("modifypwd");
        }
    }

    /*
     * 计算用户流量
     * 2014-7-19 17:33:11
     */

    public function count() {
        $uid = I("param.id", 0); //用户id
        $user_start_time = I("param.start_time", ''); //查询起始时间
        $user_end_time = I("param.end_time", ''); //查询结束时间
        $today = date("Y-m-d");
        //取出当月第一天 2014-07-01 00:00:00
        $firstday = date("Y-m-01 00:00:00", strtotime($today));
        //取出当月最后天 2014-07-31
        $lastday = date("Y-m-d 00:00:00", strtotime("$firstday +1 month -1 day"));
        if ($user_start_time!='') {
            //开始时间 
            $start_time = strtotime($user_start_time);
        } else {
            //没有开始时间按照每个月1号开始计算 2014-07-01 00:00:00
            $start_time = strtotime($firstday);
        }
        if ($user_end_time!=''){
            //结束时间
            $end_time = strtotime($user_end_time);
        } else {
            //没有结束时间按照当天日期计算 2014-7-19 00:33:50
            $end_time = time();
        }
        if ($uid != 0) {
            //拼装搜索条件
            $condition["uid"] = $uid;
            $condition['time'] = array('between', array($start_time, $end_time));
            $countUpstreamFlow = M("FlowStatistics")->where($condition)->sum('upstream_flow'); //上行流量总和
            $countDownstreamFlow = M("FlowStatistics")->where($condition)->sum('downstream_flow'); //下行流量总和
            $flowStatistics = array(
                "uid" => $uid,
                "section" => formatTime($start_time, "Y-m-d H:i:s", 1) . "----" . formatTime($end_time, "Y-m-d H:i:s", 1),
                "countUpstreamFlow" => round($countUpstreamFlow/1024/1024,2), //上行流量总和m
                "countDownstreamFlow" => round($countDownstreamFlow/1024/1024,2), //下行流量总和m
                "total" => round((($countUpstreamFlow + $countDownstreamFlow)/1024/1024),2), //流量总和m
            );
        } else {
            $this->error(L("user_count_no_id"));
        }
        $user_info=D("User")->getUserInfo($uid);
        $this->assign("user_info", $user_info);
        $this->assign("flowStatistics", $flowStatistics);
        $this->assign("firstday", $firstday);
        $this->assign("lastday", $lastday);
        $this->assign("start_time", date("Y-m-d H:i:s", $start_time));
        $this->assign("end_time", date("Y-m-d H:i:s", $end_time));
        $this->display("count");
    }
    
    /*
     * 获取在线人数
     * 2014-9-3 10:42:29 新增登录类型
     */
    public function online() {
        $client="iPhone";//获取手机登录类型
        $result=D('User')->getOnline($client);
        //从缓存中读取,减少操作数据库次数
        $Cache = Cache::getInstance(C('DATA_CACHE_TYPE'));
        $user_all = $Cache->get('user_all');
        $temp1=$Cache->get('user_all_ids');
        if(empty($user_all)){
            $user_all=D('User')->get_user_list_for_count();
            $Cache->set('user_all',$user_all);
            $temp1=array();
            foreach ($user_all as $value) {
                $temp1[]=$value['id'];
            }
            $Cache->set('user_all_ids',$temp1);
        }
        $temp2=array();
        foreach ($result['onlne_user_list'] as $value) {
            $temp2[]=$value['id'];
        }
        //离线人员id集合 2014-9-22 23:59:18
        $offline=  array_diff($temp1, $temp2);
        $this->assign("offline",$offline);
        $this->assign("user_all",$user_all);
        //离线人数分配给模板
        $this->assign("countOffline",  count($offline));
        $this->assign("onlne_user_list",$result['onlne_user_list']);
        $this->assign("count",$result['count']);
        $this->assign("page",$result['page']);
        //在线人数分配给模板
        $this->assign("countOnline",  $result['count']);
        $this->display("online");
    }
    
    /**
     * 统计所有人流量
     * 2014-9-11 16:12:03
     * fuck fuck  改需求 啊啊啊啊啊啊啊 疯了!!!!!
     */
    public function liuliangtongji(){
        $search_type = I("param.search_type", ''); //根据条件检索姓名或者工号  real_name为姓名 job_number为工号
        $search_type_value = I("param.search_type_value", ''); 
        $user_start_time = I("param.start_time", ''); //查询起始时间
        $user_end_time = I("param.end_time", ''); //查询结束时间
        $today = date("Y-m-d");
        //取出当月第一天 2014-07-01 00:00:00
        $firstday = date("Y-m-01 00:00:00", strtotime($today));
        //取出当月最后天 2014-07-31
        $lastday = date("Y-m-d 00:00:00", strtotime("$firstday +1 month -1 day"));
        if ($user_start_time!='') {
            //开始时间 
            $start_time = strtotime($user_start_time);
        } else {
            //没有开始时间按照每个月1号开始计算 2014-07-01 00:00:00
            $start_time = strtotime($firstday);
        }
        if ($user_end_time!=''){
            //结束时间
            $end_time = strtotime($user_end_time);
        } else {
            //没有结束时间按照当天日期计算 2014-7-19 00:33:50
            $end_time = time();
        }
        $sql0=$sql='';
        if($search_type=="real_name"&&$search_type_value!=''){
            $sql0="SELECT u.id
            FROM in_flow_statistics AS flow
            LEFT JOIN in_user AS u ON u.id=flow.uid
            LEFT JOIN in_organization AS organization ON organization.id=u.organization_id
            LEFT JOIN in_specialty AS specialty ON specialty.id=u.specialty_id
            WHERE u.id IS NOT NULL 
              AND u.real_name like '{$search_type_value}%'
              AND flow.time between {$start_time} AND {$end_time}
            GROUP BY flow.uid";
            $sql="SELECT u.id,
            u.real_name,
            u.job_number,
            organization.name AS organization_name,
            specialty.name AS specialty_name,
            sum(flow.upstream_flow) AS countUpstreamFlow,
            sum(flow.downstream_flow) AS countDownstreamFlow,
            sum(flow.upstream_flow+flow.downstream_flow) AS total
            FROM in_flow_statistics AS flow
            LEFT JOIN in_user AS u ON u.id=flow.uid
            LEFT JOIN in_organization AS organization ON organization.id=u.organization_id
            LEFT JOIN in_specialty AS specialty ON specialty.id=u.specialty_id
            WHERE u.id IS NOT NULL
              AND u.real_name like '{$search_type_value}%'
              AND flow.time between {$start_time} AND {$end_time}
            GROUP BY flow.uid
            ORDER BY total DESC {$limit}"; 
        }else if($search_type=="job_number"&&$search_type_value!=''){
            $sql0="SELECT u.id
            FROM in_flow_statistics AS flow
            LEFT JOIN in_user AS u ON u.id=flow.uid
            LEFT JOIN in_organization AS organization ON organization.id=u.organization_id
            LEFT JOIN in_specialty AS specialty ON specialty.id=u.specialty_id
            WHERE u.id IS NOT NULL
              AND u.job_number like '{$search_type_value}%'
              AND flow.time between {$start_time} AND {$end_time}
            GROUP BY flow.uid";
            $sql="SELECT u.id,
            u.real_name,
            u.job_number,
            organization.name AS organization_name,
            specialty.name AS specialty_name,
            sum(flow.upstream_flow) AS countUpstreamFlow,
            sum(flow.downstream_flow) AS countDownstreamFlow,
            sum(flow.upstream_flow+flow.downstream_flow) AS total
            FROM in_flow_statistics AS flow
            LEFT JOIN in_user AS u ON u.id=flow.uid
            LEFT JOIN in_organization AS organization ON organization.id=u.organization_id
            LEFT JOIN in_specialty AS specialty ON specialty.id=u.specialty_id
            WHERE u.id IS NOT NULL
              AND u.job_number like '{$search_type_value}%'
              AND flow.time between {$start_time} AND {$end_time}
            GROUP BY flow.uid
            ORDER BY total DESC {$limit}";  
        }else{
            $sql0="SELECT u.id
            FROM in_flow_statistics AS flow
            LEFT JOIN in_user AS u ON u.id=flow.uid
            LEFT JOIN in_organization AS organization ON organization.id=u.organization_id
            LEFT JOIN in_specialty AS specialty ON specialty.id=u.specialty_id
            WHERE u.id IS NOT NULL
              AND flow.time between {$start_time} AND {$end_time}
            GROUP BY flow.uid";
            $sql="SELECT u.id,
            u.real_name,
            u.job_number,
            organization.name AS organization_name,
            specialty.name AS specialty_name,
            sum(flow.upstream_flow) AS countUpstreamFlow,
            sum(flow.downstream_flow) AS countDownstreamFlow,
            sum(flow.upstream_flow+flow.downstream_flow) AS total
            FROM in_flow_statistics AS flow
            LEFT JOIN in_user AS u ON u.id=flow.uid
            LEFT JOIN in_organization AS organization ON organization.id=u.organization_id
            LEFT JOIN in_specialty AS specialty ON specialty.id=u.specialty_id
            WHERE u.id IS NOT NULL
              AND flow.time between {$start_time} AND {$end_time}
            GROUP BY flow.uid
            ORDER BY total DESC {$limit}";
        }
        //show_msg_not_exit($sql);
        //计算总记录数
        $t=M()->query($sql0);
        $total = count($t);
        //读取配置文件的每页分页记录数
        $listRows = C("PAGE_LISTROWS");
        import('@.Components.Page');
        $page = new Page($total, $listRows,"start_time=".$user_start_time."&user_end_time=".$user_end_time."&search_type=".$search_type."&search_type_value=".$search_type_value);
        $limit = $page->limit;
        $res=M()->query($sql);
        $this->assign("liuliangtongji", $res);
        $this->assign("firstday", $firstday);
        $this->assign("lastday", $lastday);
        $section=formatTime($start_time, "Y-m-d H:i:s", 1) . " 至 " . formatTime($end_time, "Y-m-d H:i:s", 1);
        $this->assign("section", $section);
        $this->assign("start_time", date("Y-m-d H:i:s", $start_time));
        $this->assign("end_time", date("Y-m-d H:i:s", $end_time));
        $this->assign("page", $page->fpage());
        $this->assign("search_type",$search_type);
        $this->assign("search_type_value", $search_type_value);
        $this->display("liuliangtongji");
    }
     /**
     * 统计所有人流量
     * 2014-9-1 21:46:29
     * fuck fuck  改需求 啊啊啊啊啊啊啊 疯了!!!!!
     */
    /*
    public function liuliangtongji(){
        $user_start_time = I("param.start_time", ''); //查询起始时间
        $user_end_time = I("param.end_time", ''); //查询结束时间
        $today = date("Y-m-d");
        //取出当月第一天 2014-07-01 00:00:00
        $firstday = date("Y-m-01 00:00:00", strtotime($today));
        //取出当月最后天 2014-07-31
        $lastday = date("Y-m-d 00:00:00", strtotime("$firstday +1 month -1 day"));
        if ($user_start_time!='') {
            //开始时间 
            $start_time = strtotime($user_start_time);
        } else {
            //没有开始时间按照每个月1号开始计算 2014-07-01 00:00:00
            $start_time = strtotime($firstday);
        }
        if ($user_end_time!=''){
            //结束时间
            $end_time = strtotime($user_end_time);
        } else {
            //没有结束时间按照当天日期计算 2014-7-19 00:33:50
            $end_time = time();
        }
        //计算总记录数
        $t=M("FlowStatistics")->field("uid")->group("uid")->select();
        $total = count($t);
        //读取配置文件的每页分页记录数
        $listRows = C("PAGE_LISTROWS");
        import('@.Components.Page');
        $page = new Page($total, $listRows,"start_time=".$user_start_time."&user_end_time=".$user_end_time);
        //Limit 2, 1  ===>2,1  进行转换
        $limit = $page->limit;
        //去除空格 ====>2,1
        $limit = ltrim(str_replace("Limit", "", $limit));
        //取出所有用户id
        $uid_array=M("FlowStatistics")->field("uid")->group("uid")->limit($limit)->select();
        //show_msg_not_exit(M("FlowStatistics")->getLastSql());
        foreach ($uid_array as $v){
            //取出单个uid查询指定时间段内的流量
            $uid = $v['uid']; //用户id
            if($uid!=1){
                //拼装搜索条件
                $condition["uid"] = $uid;
                $condition['time'] = array('between', array($start_time, $end_time));
                $countUpstreamFlow = M("FlowStatistics")->where($condition)->sum('upstream_flow'); //上行流量总和
                $countDownstreamFlow = M("FlowStatistics")->where($condition)->sum('downstream_flow'); //下行流量总和
                $flowStatistics = array(
                    "countUpstreamFlow" => round($countUpstreamFlow/1024/1024,2), //上行流量总和m
                    "countDownstreamFlow" => round($countDownstreamFlow/1024/1024,2), //下行流量总和m
                    "total" => round((($countUpstreamFlow + $countDownstreamFlow)/1024/1024),2), //流量总和m
                );
                $user_info=D("User")->getUserInfo($uid);
                if($user_info['real_name']!=null&&$user_info['real_name']!="赵洪禹"){
                    $temp[$uid]=array(
                    "flowStatistics"=>$flowStatistics,
                    "user_info"=>$user_info,
                    );
                }
            }
        }
        $this->assign("liuliangtongji", $temp);
        $this->assign("firstday", $firstday);
        $this->assign("lastday", $lastday);
        $section=formatTime($start_time, "Y-m-d H:i:s", 1) . " 至 " . formatTime($end_time, "Y-m-d H:i:s", 1);
        $this->assign("section", $section);
        $this->assign("start_time", date("Y-m-d H:i:s", $start_time));
        $this->assign("end_time", date("Y-m-d H:i:s", $end_time));
        $this->assign("page", $page->fpage());
        $this->display("liuliangtongji");
    }
    */
}
