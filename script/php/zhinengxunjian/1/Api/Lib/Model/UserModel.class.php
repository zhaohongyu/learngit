<?php

/**
 * in_in_sys 用户类
 * $Author: liushr $
 * $Id: UserModel.class.php,v 1.6 2014-7-6 00:11:20 zhaohyg
 */
class UserModel extends Model 
{

    //登录验证
    public function login($data) 
    {
        //用户名和密码判断是否输入
        if (!$data['UserName']) {
            //'4201' => '请填写用户名！',
            $this->error = 4201;
        } else if (!$data['Password']) {
            //'4202' => '请填写密码！',
            $this->error = 4202;
        } else {
            //生成认证条件
            $map = array();
            // 支持使用绑定帐号登录
            $map['username'] = $data['UserName'];
            import('ORG.Util.RBAC');
            $authInfo = RBAC::authenticate($map);
            //使用用户名、密码和状态的方式进行认证
            if (false == $authInfo) {
                //4203 邮箱/姓名不存在或已禁用！
                $this->error = 4203;
            } else {
                if ($authInfo['password'] != md5($data['Password'])) {
                    //4204 密码错误
                    $this->error = 4204;
                } else {
                    //登录成功 保存登录信息
                    $_SESSION[C('USER_AUTH_KEY')] = $authInfo['id'];
                    if ($authInfo['username'] == 'admin') {
                        //开启超级管理员认证标识
                        $_SESSION['administrator'] = true;
                    }
                    // 缓存访问权限
                    RBAC::saveAccessList();
                    //保存用户信息到session  这里缓存的都是上次登录的ip 登录时间
                    $time = time();
                    $_SESSION['user_info'] = $authInfo;
                    //更新最后登录时间
                    $authInfo["login_time"] = $time;
                    //更新记录最后更新时间
                    $authInfo["update_time"] = $time;
                    //更新最后登录IP
                    $authInfo["login_ip"] = get_client_ip();
                    //更新登录次数
                    $authInfo["login_count"] = $authInfo["login_count"] + 1;
                    //保存数据到user表
                    M("User")->save($authInfo);
                    //封装数据存储到UserActionLog表
                    $data['uid'] = $authInfo['id'];
                    $data['action'] = 'login';
                    $data['client']  = $data['Client'];
                    $data['ctime'] = $time;
                    $data['ip'] = get_client_ip();
                    $_SESSION['UserId']     = $authInfo['id'];
                    $_SESSION['UserName']   = $authInfo['username'];
                    $_SESSION['UserToken']  = md5($_SESSION['UserId'] . session_id());
                    $data['token'] = $_SESSION['UserToken'];
                    $data['username'] = $_SESSION['UserName'];
                    ////保存数据存储到UserActionLog表
                    M('UserActionLog')->add($data);
                    $result['resultRespond'] = array(
                        'result'        => 1,
                        'errorCode'     => '',
                        'errorMessage'  => '',
                        'userName'      => $_SESSION['UserName'],
                        'userId'        => $_SESSION['UserId'],
                        'userToken'     => $_SESSION['UserToken'],
                    );
                }
            }
        }
        if (!$result) {
            $error_code = C('ERROR_CODE');
            $result['resultRespond'] = array(
                'result'        => 0,
                'errorCode'     => $this->error,
                'errorMessage'  => $error_code[$this->error],
                'userName'      => '',
                'userId'        => '',
                'userToken'     => '',
            );
        }
        return $result;
    }
    // 用户登出
    public function logout($data)
    {
        //为统计流量做准备
        $uid = intval($data['UserId']) ? intval($data['UserId']) : 0;//用户id
        $flow_statistics_id = intval($data['FlowStatisticsId']) ? intval($data['FlowStatisticsId']) : 0;
        if($uid!=0&&$flow_statistics_id!=0){
            $result["UserId"]=$uid;
            $result["FlowStatisticsId"]=$flow_statistics_id;
        }
        if(isset($data['UserId'])) {
            //销毁token
            $map['token'] = $_SESSION['UserToken'];
            //清空token值,不进行删除纪录
            $new_map['token']='';
            M('UserActionLog')->where($map)->save($new_map);
            unset($_SESSION['UserName']);
            unset($_SESSION['UserToken']);
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
            $result['resultRespond'] = array(
                'result'        => 1,
                'errorCode'     => '',    
                'errorMessage'  => '',
            );
        }else {
            $error_code = C('ERROR_CODE');
            $result['resultRespond'] = array(
                'result'        => 0,
                'errorCode'     => 4208,//已经登出    
                'errorMessage'  => $error_code[4208],
            );
        }
        return $result;
    }

    //获取员工信息
    public function getUserInfo($data)
    {
        //为统计流量做准备
        $uid = intval($data['UserId']) ? intval($data['UserId']) : 0;//用户id
        $flow_statistics_id = intval($data['FlowStatisticsId']) ? intval($data['FlowStatisticsId']) : 0;
        if($uid!=0&&$flow_statistics_id!=0){
            $result["UserId"]=$uid;
            $result["FlowStatisticsId"]=$flow_statistics_id;
        }
        if ($uid) {
            //查询用户
            //表名
            $table_user = C("DB_PREFIX") . 'user';
            $table_role = C("DB_PREFIX") . 'role';
            $table_dept = C("DB_PREFIX") . 'dept';
            $table_organization = C("DB_PREFIX") . 'organization';
            $table_user_action_log = C("DB_PREFIX") . 'user_action_log';
            $table_specialty = C("DB_PREFIX") . 'specialty';
            //连接名
            $join_user = $table_user . ' as user ';
            $join_role = $table_role . ' as role ON user.role_id = role.id';
            $join_dept = $table_dept . ' as dept ON user.dept_id = dept.id';
            $join_organization = $table_organization . ' as organization ON user.organization_id = organization.id';
            $join_user_action_log = $table_user_action_log . ' as ual ON user.id = ual.uid';
            $join_specialty = $table_specialty . ' as specialty ON user.specialty_id = specialty.id';
            //要查询的字段
            $field = "user.*,ual.*,role.remark as role_remark,dept.name as dept_name,organization.name as organization_name,specialty.name as specialty_name";
            $where = array("user.id"=>$uid);
            //封装结果集
            $user = M()->table($join_user)->join($join_role)->join($join_dept)->join($join_organization)->join($join_user_action_log)->join($join_specialty)->field($field)->where($where)->find();
            if ($user) {
                $result['userInfo'] = array(
                    'userName'          => $user['username'],
                    'userId'            => $user['id'],
                    'userToken'         => $data['UserToken'],
                    'userRealName'      => $user['real_name'],
                    'userJobNumber'     => $user['job_number'],
                    'userMobile'        => $user['mobile'],
                    'userRoleId'        => $user['role_id'],
                    'userDeptId'        => $user['dept_id'],
                    'userOrganizationId'=> $user['organization_id'],
                    'userRegisterTime'  => $user['register_time'],
                    'userLoginTime'     => $user['login_time'],
                    'userUpdateTime'    => $user['update_time'],
                    'userLoginIp'       => $user['login_ip'],
                    'userLoginCount'    => $user['login_count'],
                    'userClient'        => $user['client'],
                    'userRoleRemark'    => $user['role_remark'],
                    'userDeptName'      => $user['dept_name'],
                    'userOrganizationName' =>$user['organization_name'],
                    'userSpecialtyName'    =>$user['specialty_name'],
                    'result'                => 1,
                    'errorCode'             => '',
                    'errorMessage'          => '',
                );
            } else {
                //用户不存在
                $this->error = 4211;
            }
        } else {
            //请求数据错误
            $this->error = 100;
        }
        if (!$result) {
            $error_code = C('ERROR_CODE');
            $result['userInfo'] = array(
                'userName'          =>'',
                'userId'            => '',
                'userToken'         => '',
                'userRealName'      => '',
                'userJobNumber'     =>'',
                'userMobile'        => '',
                'userRoleId'        => '',
                'userDeptId'        => '',
                'userOrganizationId'=>'',
                'userRegisterTime'  => '',
                'userLoginTime'     => '',
                'userUpdateTime'    => '',
                'userLoginIp'       => '',
                'userLoginCount'    => '',
                'userClient'        => '',
                'userRoleRemark'    => '',
                'userDeptName'      => '',
                'userOrganizationName' =>'',
                'result'            => 0,
                'errorCode'         => $this->error,
                'errorMessage'      => $error_code[$this->error],
            );
        }
        return $result;
    }

    /**
     * 获取我的巡检任务
     * @param type $data
     * @return string
     */
    public function getMyTask($data) {
        //为统计流量做准备
        $uid = intval($data['UserId']) ? intval($data['UserId']) : 0;//用户id
        $flow_statistics_id = intval($data['FlowStatisticsId']) ? intval($data['FlowStatisticsId']) : 0;
        if($uid!=0&&$flow_statistics_id!=0){
            $result["UserId"]=$uid;
            $result["FlowStatisticsId"]=$flow_statistics_id;
        }
        //搜索的时间
        $search_time = $data['SearchTime'] ? $data['SearchTime'] : 0;
        $page = $data['Page'] ? $data['Page'] : 1;
        if ($uid) {
            //拼装搜索条件
            $condition = array();
            if ($search_time != 0) {
                $condition["_string"] = "FROM_UNIXTIME(circuit.start_time,'%Y-%m-%d')='$search_time'";
            }
            $condition["circuit.uid"] = $uid;
            //根据条件查询巡检任务完成情况
            $result2 = $this->getTaskStatusListByCondition($condition, $search_time,$page);
            $task_status_list = $result2["task_status_list"];
            foreach ($task_status_list as $k => $v) {
                $tmep = explode(",", $v['device_ids']);
                $str = "";
                foreach ($tmep as $kk => $vv) {
                    $device_info = D("Device")->where(array("id" => $vv))->find();
                    $str.=$device_info['name'] . ",";
                }
                $str = rtrim($str, ",");
                $task_status_list[$k]["device_names"] = $str;
                $task_status_list[$k]["device_names_short"] = formatStr($str, 14);
            }
            my_array_filter($task_status_list);
            if (!empty($task_status_list)) {
                foreach ($task_status_list as $k => $v) {
                    $result[$k] = array(
                        'taskId'         =>$v['task_id'],
                        'startTime'         =>$v['start_time'],
                        'endTime'           =>$v['end_time'],
                        'deviceIds'         =>$v['device_ids'],
                        'positionId'        =>$v['position_id'],
                        'status'            =>$v['status'],
                        'submitTime'        =>$v['submit_time'],
                        'overTime'          =>$v['overtime'],//超时完成的时间
                        'isOverTime'        =>$v['is_overtime'],//是否超时
                        'checkedDeviceIds'  =>$v['checked_device_ids'],
                        'positionName'      =>$v['position_name'],
                        'positionCode'      =>$v['position_code'],
                        'deviceNames'       =>$v['device_names'],
                        'deviceNamesShort'  =>$v['device_names_short'],
                        'userName'          => $data['UserName'],
                        'userId'            => $data['UserId'],
                        'userToken'         => $data['UserToken'],
                        'userRealName'      => $v['real_name'],
                        'userJobNumber'     => $v['job_number'],
                        'userMobile'        => $v['mobile'],
                        'userRoleId'        => $v['role_id'],
                        'userDeptId'        => $v['dept_id'],
                        'userOrganizationId'=> $v['organization_id'],
                        'userRegisterTime'  => $v['register_time'],
                        'userLoginTime'     => $v['login_time'],
                        'userUpdateTime'    => $v['update_time'],
                        'userLoginIp'       => $v['login_ip'],
                        'userLoginCount'    => $v['login_count'],
                        'userDeptName'      => $v['dept_name'],
                        'userSpecialtyName' =>$v['specialty_name'],
                    );
                }
                $result["status"] = array(
                        'result' => 1,
                        'errorCode' => '',
                        'errorMessage' => '',
                );
                $result["pageInfo"]=$result2["page_info"];//返回分页信息
            } else {
                //没有查到符合条件的任务!
                $this->error = 4212;
            }
        } else {
            //请求数据错误
            $this->error = 100;
        }
        if (!$result) {
            $error_code = C('ERROR_CODE');
            $result["status"]= array(
                'result' => 0,
                'errorCode' => $this->error,
                'errorMessage' => $error_code[$this->error],
            );
        }
        return $result;
    }

    /**
     * 24小时内的巡检任务情况 管理员版 根据条件检索  旧版
     * 2014-6-29 17:37:23
     * @return array 巡检任务情况结果集
     */
    public function getTaskStatusListByCondition($where = array(), $search_time = "",$page=1) {
        //表名
        $table_user = C("DB_PREFIX") . 'user';
        $table_circuit = C("DB_PREFIX") . 'circuit';
        $table_dept = C("DB_PREFIX") . 'dept';
        $table_specialty = C("DB_PREFIX") . 'specialty';
        $table_position = C("DB_PREFIX") . 'position';
        //连接名
        $join_user = $table_user . ' as user ';
        $join_circuit = $table_circuit . ' as circuit ON user.id = circuit.uid';
        $join_dept = $table_dept . ' as dept ON user.dept_id = dept.id';
        $join_specialty = $table_specialty . ' as specialty ON user.specialty_id = specialty.id';
        $join_position = $table_position . ' as position ON circuit.position_id = position.id';
        //要查询的字段
        $field = "circuit.*,position.name as position_name ,position.code as position_code,user.*,dept.name as dept_name,specialty.name as specialty_name";
        $where["circuit.start_time"] = array("neq", '');
        $where["circuit.end_time"] = array("neq", '');
        $where["circuit.device_ids"] = array("neq", '');
        //分页查询
        //计算总记录数
        $total = M()->join($join_circuit)->join($join_position)->table($join_user)->join($join_dept)->join($join_specialty)->where($where)->count();
        //读取配置文件的每页分页记录数
        $listRows = C("PAGE_LISTROWS");
        import('@.Components.Page');
        $Page = new Page($total, $listRows,$page, "search_time=" . $search_time);
        //Limit 2, 1  ===>2,1  进行转换
        $limit = $Page->limit;
        //去除空格 ====>2,1
        $limit = ltrim(str_replace("Limit", "", $limit));
        $task_status_list = M()->join($join_circuit)->join($join_position)->table($join_user)->join($join_dept)->join($join_specialty)->field($field)->where($where)->order("end_time desc")->limit($limit)->select();
        $result["task_status_list"] = $task_status_list;
        $result["page_info"]=array(
            "total"=>$total,//总记录数
            "listRows"=>$listRows,//每页显示条数
            "page"=>$page,//当前页
            "pageNum"=>ceil($total / $listRows),//总页数
            
        );
        return $result;
    }
    
    
    
    /**
     * 根据专业获取任务列表--去掉分页
     * 2014-7-12 14:59:31
     * @param type $data
     * @return string
     */
    public function getTaskListBySpecialty($data) {
        //为统计流量做准备
        $uid = intval($data['UserId']) ? intval($data['UserId']) : 0;//用户id
        $flow_statistics_id = intval($data['FlowStatisticsId']) ? intval($data['FlowStatisticsId']) : 0;
        if($uid!=0&&$flow_statistics_id!=0){
            $result["UserId"]=$uid;
            $result["FlowStatisticsId"]=$flow_statistics_id;
        }
        if ($uid) {
            //根据用户id查询用户信息
            $user_info = D('User')->getById($uid);
            //拼装搜索条件
            $condition = array();
            $condition["specialty_id"] = $user_info["specialty_id"];
            //根据条件查询巡检任务完成情况
            $result2 = $this->getTaskListByCondition($condition);
            $task_list = $result2["task_list"];
            my_array_filter($task_list);
            if (!empty($task_list)) {
                foreach ($task_list as $k => $v) {
                    $result["taskList"][$k] = array(
                        'taskId'         =>$v['id'],
                        'taskCode'       =>$v['task_code'],
                        'startTime'      =>$v['start_time'],
                        'endTime'        =>$v['end_time'],
                        'taskName'       =>$v['task_name'],
                        'positionIds'    =>$v['position_ids'],
                        'deviceIds'      =>$v['device_ids'],
                        'specialtyId'    =>$v['specialty_id'],//超时完成的时间
                        'specialtyName'  =>$v['specialty_name'],//是否超时
                        'positionNames'  =>$v['position_names'],
                        'positionNamesShort'=>$v['position_names_short'],
                    );
                }
                $result["status"] = array(
                        'result' => 1,
                        'errorCode' => '',
                        'errorMessage' => '',
                );
            } else {
                //没有查到符合条件的任务!
                $this->error = 4212;
            }
        } else {
            //请求数据错误
            $this->error = 100;
        }
        if (!$result) {
            $error_code = C('ERROR_CODE');
            $result["status"]= array(
                'result' => 0,
                'errorCode' => $this->error,
                'errorMessage' => $error_code[$this->error],
            );
        }
        return $result;
    }
    
    /*
     * 根据条件分页查询已经设定的任务 新版-- 去掉分页
     * 2014-7-12 14:57:18
     * @param type $condition 搜索条件
     */

    public function getTaskListByCondition($condition = array()) {
        //查询任务
        //封装结果集
        $task_list = M("Task")->where($condition)->order("task_code")->select();
        foreach ($task_list as $kk => $vv) {
            $position_ids = explode(",", $vv["position_ids"]);
            $temp = array();
            foreach ($position_ids as $k => $v) {
                $position_info = D("Position")->getById($v);
                $temp[] = $position_info["name"];
            }
            $position_names = implode(",", $temp);
            $position_names_short = formatStr($position_names, "8");
            $task_list[$kk]["position_names"] = $position_names;
            $task_list[$kk]["position_names_short"] = $position_names_short;
        }
        $result["task_list"] = $task_list;
        return $result;
    }
    
    
    
    /**
     * 根据专业获取任务列表--带分页
     * 2014-7-10 14:59:00
     * @param type $data
     * @return string
     */
    public function getTaskListBySpecialtyByPage($data) {
        //为统计流量做准备
        $uid = intval($data['UserId']) ? intval($data['UserId']) : 0;//用户id
        $flow_statistics_id = intval($data['FlowStatisticsId']) ? intval($data['FlowStatisticsId']) : 0;
        if($uid!=0&&$flow_statistics_id!=0){
            $result["UserId"]=$uid;
            $result["FlowStatisticsId"]=$flow_statistics_id;
        }
        $page = $data['Page'] ? $data['Page'] : 1;
        if ($uid) {
            //根据用户id查询用户信息
            $user_info = D('User')->getById($uid);
            //拼装搜索条件
            $condition = array();
            $condition["specialty_id"] = $user_info["specialty_id"];
            //根据条件查询巡检任务完成情况
            $result2 = $this->getTaskListByCondition($condition,$page);
            $task_list = $result2["task_list"];
            my_array_filter($task_list);
            if (!empty($task_list)) {
                foreach ($task_list as $k => $v) {
                    $result["taskList"][$k] = array(
                        'taskId'         =>$v['id'],
                        'taskCode'       =>$v['task_code'],
                        'startTime'      =>$v['start_time'],
                        'endTime'        =>$v['end_time'],
                        'taskName'       =>$v['task_name'],
                        'positionIds'    =>$v['position_ids'],
                        'deviceIds'      =>$v['device_ids'],
                        'specialtyId'    =>$v['specialty_id'],
                        'specialtyName'  =>$v['specialty_name'],
                        'positionNames'  =>$v['position_names'],
                        'positionNamesShort'=>$v['position_names_short'],
                    );
                }
                $result["status"] = array(
                        'result' => 1,
                        'errorCode' => '',
                        'errorMessage' => '',
                );
                $result["pageInfo"]=$result2["page_info"];//返回分页信息
            } else {
                //没有查到符合条件的任务!
                $this->error = 4212;
            }
        } else {
            //请求数据错误
            $this->error = 100;
        }
        if (!$result) {
            $error_code = C('ERROR_CODE');
            $result["status"]= array(
                'result' => 0,
                'errorCode' => $this->error,
                'errorMessage' => $error_code[$this->error],
            );
        }
        return $result;
    }
    
    /*
     * 根据条件分页查询已经设定的任务 新版-- 带分页
     * @param type $condition 搜索条件
     */

    public function getTaskListByConditionByPage($condition = array(),$page=1) {
        //计算总记录数
        $total = M("Task")->where($condition)->count();
        //读取配置文件的每页分页记录数
        $listRows = C("PAGE_LISTROWS");
        import('@.Components.Page');
        $Page = new Page($total, $listRows,$page ,"specialty_id=" . $condition["specialty_id"]);
        //Limit 2, 1  ===>2,1  进行转换
        $limit = $Page->limit;
        //去除空格 ====>2,1
        $limit = ltrim(str_replace("Limit", "", $limit));
        //查询任务
        //封装结果集
        $task_list = M("Task")->where($condition)->limit($limit)->order("task_code")->select();
        foreach ($task_list as $kk => $vv) {
            $position_ids = explode(",", $vv["position_ids"]);
            $temp = array();
            foreach ($position_ids as $k => $v) {
                $position_info = D("Position")->getById($v);
                $temp[] = $position_info["name"];
            }
            $position_names = implode(",", $temp);
            $position_names_short = formatStr($position_names, "8");
            $task_list[$kk]["position_names"] = $position_names;
            $task_list[$kk]["position_names_short"] = $position_names_short;
        }
        $result["task_list"] = $task_list;
        $result["page_info"]=array(
            "total"=>$total,//总记录数
            "listRows"=>$listRows,//每页显示条数
            "page"=>$page,//当前页
            "pageNum"=>ceil($total / $listRows),//总页数
        );
        return $result;
    }
    /*
     * 领取任务
     */
    public function receiveTask($data){
        //为统计流量做准备
        $uid = intval($data['UserId']) ? intval($data['UserId']) : 0;//用户id
        $flow_statistics_id = intval($data['FlowStatisticsId']) ? intval($data['FlowStatisticsId']) : 0;
        if($uid!=0&&$flow_statistics_id!=0){
            $result["UserId"]=$uid;
            $result["FlowStatisticsId"]=$flow_statistics_id;
        }
        $error_code = C('ERROR_CODE');
        $task_id = intval($data['TaskId']) ? intval($data['TaskId']) : 0;
        if($uid&&$task_id){
            //获取当前时间的日期2014-07-12 这样的形式
            $time=formatTime(time(),"Y-m-d",1);
            //拼接搜索条件
            $condition["uid"]=$uid;
            $condition["task_id"]=$task_id;
            $condition["_string"] = "FROM_UNIXTIME(receive_time,'%Y-%m-%d')='$time'";
            //去数据库查询当天是否领取过
            $finish_task_received=D("FinishTask")->where($condition)->find();
            //如果已经领取过了,并且status等于1 即已经完成 提示给用户
            if(!empty($finish_task_received)&&$finish_task_received["status"]==1){
                $result["status"] = array(
                    'result' => 0,
                    'errorCode' => 4226,
                    'errorMessage' =>$error_code["4226"],
                );
                return $result;
            }
            if(!empty($finish_task_received)&&$finish_task_received["status"]==0){
                $result["finishTaskInfo"] = array(
                        'message' => "下面返回的是要完成任务记录id信息,一定要保留!",
                        'finishTaskId' => $finish_task_received["id"],
                );
                $result["status"] = array(
                    'result' => 1,
                    'errorCode' => 4227,
                    'errorMessage' =>$error_code["4227"],
                );
                return $result;
            }
            //如果为空,则说明没有领取过
            if(empty($finish_task_received)){
                $task_info = D("Task")->where(array("id" => $task_id))->find();
                $map_finish_task["task_id"]=$task_id;
                $map_finish_task["status"]=0;//初始化任务状态为未完成
                $map_finish_task["receive_time"]=  time();//任务的领取时间
                $map_finish_task["uid"]=$uid;//领取任务的员工id
                $map_finish_task["device_ids"]=$task_info["device_ids"];//设备id集合
                //查询该员工的所在专业id
                $user_info=D("User")->getById($uid);
                $map_finish_task["specialty_id"]=$user_info["specialty_id"];
                $rst=D("FinishTask")->add($map_finish_task);
                if($rst){
                    $result["finishTaskInfo"] = array(
                        'message' => "下面返回的是要完成任务记录id信息,一定要保留!",
                        'finishTaskId' => $rst,
                    );
                    $result["status"] = array(
                        'result' => 1,
                        'errorCode' => '',
                        'errorMessage' => '',
                    );
                }else{
                    //领取任务失败
                    $result["status"] = array(
                        'result' => 0,
                        'errorCode' => 4223,
                        'errorMessage' => $error_code["4223"],
                    );
                }
            }
        }else{
            //领取任务失败 请求数据错误
            $result["status"] = array(
                'result' => 0,
                'errorCode' => 100,
                'errorMessage' => $error_code["100"],
            );
        }
        return $result;
    }
    /*
     * 获取我的任务列表---已经领取的任务
     * 2014-7-14 21:33:08
     */
    public function getMyTaskList($data) {
        //为统计流量做准备
        $uid = intval($data['UserId']) ? intval($data['UserId']) : 0;//用户id
        $flow_statistics_id = intval($data['FlowStatisticsId']) ? intval($data['FlowStatisticsId']) : 0;
        if($uid!=0&&$flow_statistics_id!=0){
            $result["UserId"]=$uid;
            $result["FlowStatisticsId"]=$flow_statistics_id;
        }
        if($uid){
            //查询in_finish_task表中 status 为0 的即未完成的任务 uid等于登录用户的id
            //$where=array("status"=>0,"uid"=>$uid);
            //获取已完成和未完成任务即--所有任务 按照领取时间 倒序排序
            $where=array("uid"=>$uid);
            $finishTaskList=D("FinishTask")->where($where)->order("receive_time desc")->select();
            foreach ($finishTaskList as $k => $v) {
                $task_info=D("Task")->getById($v["task_id"]);
                $position_ids = explode(",", $task_info["position_ids"]);
                $temp = array();
                foreach ($position_ids as $kk => $vv) {
                    $position_info = D("Position")->getById($vv);
                    $temp[] = $position_info["name"];
                }
                $position_names = implode(",", $temp);
                $position_names_short = formatStr($position_names, "8");
                $result["myTaskList"][$k] = array(
                    'FinishTaskId'         =>$v['id'],
                    'taskId'               =>$v['task_id'],
                    'status'               =>$v['status'],
                    'receiveTime'          =>$v['receive_time'],
                    'finishTime'           =>$v['finish_time'],
                    'isOverTime'           =>$v['is_overtime'],//是否超时
                    'overTime'             =>$v['overtime'],//超时的时间
                    'deviceIds'            =>$v['device_ids'],
                    'checkedDeviceIds'     =>$v['checked_device_ids'],
                    'uid'                  =>$v['uid'],
                    'position_names'       =>$position_names,
                    'position_names_short' =>$position_names_short,
                    'taskName'            =>$task_info['task_name'],
                    'taskCode'            =>$task_info['task_code'],
                    'positionIds'         =>$task_info['position_ids'],
                    'startTime'           =>$task_info['start_time'],
                    'endTime'             =>$task_info['end_time'],
                    'specialtyId'         =>$task_info['specialty_id'],
                    'specialtyName'       =>$task_info['specialty_name'],
                );
                
            }
            $result["status"] = array(
                    'result' => 1,
                    'errorCode' => '',
                    'errorMessage' => '',
            );
        }else{
            //请求数据错误
            $this->error = 100;
        }
        if (!$result) {
            $error_code = C('ERROR_CODE');
            $result["status"]= array(
                'result' => 0,
                'errorCode' => $this->error,
                'errorMessage' => $error_code[$this->error],
            );
        }
        return $result;
    }
    /*
     * 计算用户流量
     * 2014-7-19 01:55:01
     */
    public function count($data) {
        //为统计流量做准备
        $uid = intval($data['UserId']) ? intval($data['UserId']) : 0;//用户id
        $flow_statistics_id = intval($data['FlowStatisticsId']) ? intval($data['FlowStatisticsId']) : 0;
        if($uid!=0&&$flow_statistics_id!=0){
            $result["UserId"]=$uid;
            $result["FlowStatisticsId"]=$flow_statistics_id;
        }
        $today = date("Y-m-d");
        //取出当月第一天 2014-07-01 00:00:00
        $firstday = date("Y-m-01 00:00:00", strtotime($today));
        //取出当月最后天 2014-07-31
        //$lastday = date("Y-m-d 00:00:00", strtotime("$firstday +1 month -1 day"));
        if(isset($data['StartTime'])){
            //开始时间 
            $start_time = strtotime($data['StartTime']);
        }else{
            //没有开始时间按照每个月1号开始计算 2014-07-01 00:00:00
            $start_time = strtotime($firstday);
        }
        if(isset($data['EndTime'])){
            //结束时间
            $end_time = strtotime($data['EndTime']);
        }else{
            //没有结束时间按照当天日期计算 2014-7-19 00:33:50
            $end_time = time();
        }
        if($uid!=0){
            //拼装搜索条件
            $condition["uid"]=$uid;
            $condition['time']  = array('between',array($start_time,$end_time));
            $countUpstreamFlow=M("FlowStatistics")->where($condition)->sum('upstream_flow');//上行流量总和
            $countDownstreamFlow=M("FlowStatistics")->where($condition)->sum('downstream_flow');//下行流量总和
            $result["flowStatistics"]=array(
                "uid"                   =>$uid,
                "section"               =>formatTime($start_time,"Y-m-d H:i:s",1)."-".formatTime($end_time,"Y-m-d H:i:s",1),
                "countUpstreamFlow"     =>$countUpstreamFlow,//上行流量总和
                "countDownstreamFlow"   =>$countDownstreamFlow,//下行流量总和
                "total"   =>$countUpstreamFlow+$countDownstreamFlow,//流量总和
            );
            $result["status"] = array(
                    'result' => 1,
                    'errorCode' => '',
                    'errorMessage' => '',
            );
        }else{
            //请求数据错误
            $this->error = 100;
        }
        if (!$result) {
            $error_code = C('ERROR_CODE');
            $result["status"]= array(
                'result' => 0,
                'errorCode' => $this->error,
                'errorMessage' => $error_code[$this->error],
            );
        }
        return $result;
    }
}
