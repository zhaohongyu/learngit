<?php

class UserModel extends Model {

    protected $_validate = array(
        // 自动验证定义
        //array(验证字段,验证规则,错误提示,[验证条件,附加规则,验证时间])
        array('username', 'require', '{%user_model_username}'), //用户账号必须填写
        array('username', '', '{%user_model_exist_username}', 0, 'unique', 1), //该用户已经存在
        array('password', 'require', '{%user_model_password}'), // 密码必须填写
        array('repassword', 'require', '{%user_model_repassword}'), // 确认密码必须填写
        //比较两次密码必须相等
        array('password', 'repassword', '{%user_model_password_equal}', 0, confirm), // 两次输入密码必须相等
        array('real_name', 'require', '{%user_model_real_name}'), // 用户真实必须填写
        array('job_number', '/^\d+$/', '{%user_model_job_number}'), // 工号输入格式有误,只能是数字格式
        array('job_number', '', '{%user_model_exist_job_number}', 0, 'unique', 1), //该用户已经存在
        //验证手机号
        array('mobile', '/^[1][358][0-9]{9}$/', '{%user_model_mobile}', 2), // 手机号式输入有误
        array('dept_id', "require", '{%user_model_dept_id}'), // 请分配所属部门
        array('user_id', "require", '{%user_model_role_id}'), // 请分配所属角色
        array('organization_id', "require", '{%user_model_organization_id}'), // 请分配所属职位
        array('specialty_id', "require", '{%user_model_specialty_id}'), // 请选择所在专业
    );
    // 自动完成定义
    protected $_auto = array(
        //array(填充字段,填充内容,[填充条件,附加规则])
        array('password', 'md5', 1, 'function'), // 对password字段在新增的时候使md5函数处理
        array('register_time', 'time', 1, 'function'), // 对register_time字段在新增的时候写入当前时间戳
        array('update_time', 'time', 3, 'function'), // 对update_time字段在所有情况都进行处理写入当前时间戳
    );

    /**
     * 根据角色id获取用户对应的节点菜单
     * $level 菜单等级 
     * level =2 level=3
     */
    public function getMenu($user_id, $level) {
        //这里根据角色id去数据库直接查询role_id 防止权限更新不及时
        $user_info = M("User")->getById($user_id);
        //取出role_id
        $role_id = $user_info["role_id"];
        //去access 表取出当前 role_id对应的权限
        //表名
        $table_access = C("DB_PREFIX") . 'access';
        $table_node = C("DB_PREFIX") . 'node';
        //连接名
        $join_node = $table_node . ' as node ';
        $join_access = $table_access . ' as access ON access.node_id = node.id';
        //要查询的字段
        $field = "node.*";
        //level=2 的时候 过滤掉Index模块 
        if ($level == 2) {
            $hide_model = array("Index", "Node"); //要隐藏的2级菜单
            $where = array("access.role_id" => $role_id, "node.level" => $level, "node.status" => "1", "name" => array("not in", $hide_model));
        } else {
            //去掉,更新和删除显示 因为没有指定id是无法操作成功的所以还是不显示给用户了
            //要隐藏的3级菜单
            $hide_action = C("HIDE_ACTION");
            $where = array("access.role_id" => $role_id, "node.level" => $level, "node.status" => "1", "name" => array('not in', $hide_action));
        }
        $menu_list = M()->table($join_node)->join($join_access)->field($field)->where($where)->order("sort_menu desc,sort")->select();
        return $menu_list;
    }

    /**
     * 连接查询4张表  分页查询列出用户信息
     * 2014-6-23 09:56:56
     */
    public function get_user_list() {
        //计算总记录数
        $total = M("User")->count();
        //读取配置文件的每页分页记录数
        $listRows = C("PAGE_LISTROWS");
        import('@.Components.Page');
        $page = new Page($total, $listRows);
        //Limit 2, 1  ===>2,1  进行转换
        $limit = $page->limit;
        //去除空格 ====>2,1
        $limit = ltrim(str_replace("Limit", "", $limit));
        //查询用户
        //表名
        $table_user = C("DB_PREFIX") . 'user';
        $table_role = C("DB_PREFIX") . 'role';
        $table_dept = C("DB_PREFIX") . 'dept';
        $table_organization = C("DB_PREFIX") . 'organization';
        $table_specialty = C("DB_PREFIX") . 'specialty';
        //连接名
        $join_user = $table_user . ' as user ';
        $join_role = $table_role . ' as role ON user.role_id = role.id';
        $join_dept = $table_dept . ' as dept ON user.dept_id = dept.id';
        $join_organization = $table_organization . ' as organization ON user.organization_id = organization.id';
        $join_specialty = $table_specialty . ' as specialty ON user.specialty_id = specialty.id';
        //要查询的字段
        $field = "user.*,role.remark as role_remark,dept.name as dept_name,organization.name as organization_name,specialty.name as specialty_name";
        //取出超级管理员在列表中显示 防止用户更改
        //WHERE ( user.username <> 'admin' ) OR ( user.id <> '1' )
        $where = array("user.username" => array("neq", "admin"), "user.id" => array("neq", "1"), "_logic" => "OR");
        //增加排序 按照职位等级排序 2014-7-17 09:23:04
        $order="organization.level,organization.id";
        //封装结果集
        $user_list = M()->table($join_user)->join($join_role)->join($join_dept)->join($join_organization)->join($join_specialty)->field($field)->where($where)->order($order)->limit($limit)->select();
        $result["user_list"] = $user_list;
        $result["page"] = $page->fpage();
        return $result;
    }
    /**
     * 连接查询4张表  不带分页 统计离线人数做准备
     * 2014-9-22 23:42:53
     */
    public function get_user_list_for_count() {
        //查询用户
        //表名
        $table_user = C("DB_PREFIX") . 'user';
        $table_role = C("DB_PREFIX") . 'role';
        $table_dept = C("DB_PREFIX") . 'dept';
        $table_organization = C("DB_PREFIX") . 'organization';
        $table_specialty = C("DB_PREFIX") . 'specialty';
        //连接名
        $join_user = $table_user . ' as user ';
        $join_role = $table_role . ' as role ON user.role_id = role.id';
        $join_dept = $table_dept . ' as dept ON user.dept_id = dept.id';
        $join_organization = $table_organization . ' as organization ON user.organization_id = organization.id';
        $join_specialty = $table_specialty . ' as specialty ON user.specialty_id = specialty.id';
        //要查询的字段
        $field = "user.*,role.remark as role_remark,dept.name as dept_name,organization.name as organization_name,specialty.name as specialty_name";
        //取出超级管理员在列表中显示 防止用户更改
        //WHERE ( user.username <> 'admin' ) OR ( user.id <> '1' )
        $where = array("user.username" => array("neq", "admin"), "user.id" => array("neq", "1"), "_logic" => "OR");
        //增加排序 按照职位等级排序 2014-7-17 09:23:04
        $order="organization.level,organization.id";
        //封装结果集
        $user_list = M()->table($join_user)->join($join_role)->join($join_dept)->join($join_organization)->join($join_specialty)->field($field)->where($where)->order($order)->select();
        return $user_list;
    }

    /**
     * 根据条件查询用户信息
     * 2014-6-27 20:45:21
     */
    public function get_user_list_by_condition($where = array(), $real_name = "") {
        //查询用户
        //表名
        $table_user = C("DB_PREFIX") . 'user';
        $table_role = C("DB_PREFIX") . 'role';
        $table_dept = C("DB_PREFIX") . 'dept';
        $table_organization = C("DB_PREFIX") . 'organization';
        $table_specialty = C("DB_PREFIX") . 'specialty';
        //连接名
        $join_user = $table_user . ' as user ';
        $join_role = $table_role . ' as role ON user.role_id = role.id';
        $join_dept = $table_dept . ' as dept ON user.dept_id = dept.id';
        $join_organization = $table_organization . ' as organization ON user.organization_id = organization.id';
        $join_specialty = $table_specialty . ' as specialty ON user.specialty_id = specialty.id';
        //要查询的字段
        $field = "user.*,role.remark as role_remark,dept.name as dept_name,organization.name as organization_name,specialty.name as specialty_name";
        //计算总记录数
        $total = D("User")->table($join_user)->join($join_role)->join($join_dept)->join($join_organization)->join($join_specialty)->where($where)->count();
        //读取配置文件的每页分页记录数
        $listRows = C("PAGE_LISTROWS");
        import('@.Components.Page');
        $page = new Page($total, $listRows, "real_name=" . $real_name . "&dept_id=" . $where["user.dept_id"] . "&specialty_id=" . $where["user.specialty_id"] . "&organization_id=" . $where["user.organization_id"]);
        //Limit 2, 1  ===>2,1  进行转换
        $limit = $page->limit;
        //去除空格 ====>2,1
        $limit = ltrim(str_replace("Limit", "", $limit));
        //增加排序 按照职位等级排序 2014-7-17 09:23:04
        $order="organization.level,organization.id";
        //封装结果集
        $user_list = D("User")->table($join_user)->join($join_role)->join($join_dept)->join($join_organization)->join($join_specialty)->field($field)->where($where)->order($order)->limit($limit)->select();
        $result["user_list"] = $user_list;
        $result["page"] = $page->fpage();
        return $result;
    }

    //获取员工信息
    public function getUserInfo($uid) {
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
            $where = array("user.id" => $uid);
            //封装结果集
            $user = M()->table($join_user)->join($join_role)->join($join_dept)->join($join_organization)->join($join_user_action_log)->join($join_specialty)->field($field)->where($where)->find();
            if ($user) {
                return $user;
            } else {
                //用户不存在
                $this->error = L("user_model_getUserInfo_user_not_exit");
                return null;
            }
        } else {
            //请求参数错误
            $this->error = L("user_model_getUserInfo_request_error");
            return null;
        }
    }
    
    /**
     * 根据职位id查询该职位下的所有员工信息
     * 2014-7-27 16:05:09
     */
    public function get_user_list_by_organization_id($where = array()) {
        //查询用户
        //表名
        $table_user = C("DB_PREFIX") . 'user';
        $table_role = C("DB_PREFIX") . 'role';
        $table_dept = C("DB_PREFIX") . 'dept';
        $table_organization = C("DB_PREFIX") . 'organization';
        $table_specialty = C("DB_PREFIX") . 'specialty';
        //连接名
        $join_user = $table_user . ' as user ';
        $join_role = $table_role . ' as role ON user.role_id = role.id';
        $join_dept = $table_dept . ' as dept ON user.dept_id = dept.id';
        $join_organization = $table_organization . ' as organization ON user.organization_id = organization.id';
        $join_specialty = $table_specialty . ' as specialty ON user.specialty_id = specialty.id';
        //要查询的字段
        $field = "user.*,role.remark as role_remark,dept.name as dept_name,organization.name as organization_name,specialty.name as specialty_name";
        //增加排序 按照职位等级排序 2014-7-17 09:23:04
        $order="organization.level,organization.id";
        //封装结果集
        $user_list = D("User")->table($join_user)->join($join_role)->join($join_dept)->join($join_organization)->join($join_specialty)->field($field)->where($where)->order($order)->select();
        return $user_list;
    }
    
    /*
     * 获取在线会员 带分页
     * param $client 登录客户端类型
     */
    public function getOnline($client=''){
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
        $where ["ual.token"]= array('neq','');
        $where ["ual.client"]= array('eq',$client);//只查询手机端在线人数
        //计算总记录数
        $distinct_uids=D("User")->Distinct(true)->field('ual.uid')->table($join_user)->join($join_role)->join($join_dept)->join($join_organization)->join($join_user_action_log)->join($join_specialty)->where($where)->select();
        $total = count($distinct_uids);
        //读取配置文件的每页分页记录数
        $listRows = C("PAGE_LISTROWS");
        import('@.Components.Page');
        $page = new Page($total, $listRows);
        //Limit 2, 1  ===>2,1  进行转换
        $limit = $page->limit;
        //去除空格 ====>2,1
        $limit = ltrim(str_replace("Limit", "", $limit));
        //增加排序 按照职位等级排序 2014-7-17 09:23:04
        $order="organization.level,organization.id";
        //封装结果集 
        $ids='';
        foreach ($distinct_uids as $v){
            $ids.=$v['uid'].',';
        }
        $ids=rtrim($ids,',');
        $field2 = "user.*,role.remark as role_remark,dept.name as dept_name,organization.name as organization_name,specialty.name as specialty_name";
        $where2['user.id']=array('in',$ids);
        $onlne_user_list=D("User")->table($join_user)->join($join_role)->join($join_dept)->join($join_organization)->join($join_specialty)->field($field2)->where($where2)->order($order)->limit($limit)->select();
        $result["onlne_user_list"] = $onlne_user_list;
        $result["page"] = $page->fpage();
        $result["count"] = $total;//在线会员数
        return $result;
    }

}
