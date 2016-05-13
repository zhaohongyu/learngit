<?php

/**
 * Description of FinishTaskModel
 *
 * @author zhaohyg
 */
class FinishTaskModel extends Model {

    /**
     * 24小时内的巡检任务情况 管理员版 根据条件检索
     * 2014-6-29 17:37:23
     * @return array 巡检任务情况结果集
     */
    public function getTaskStatusListByCondition($where = array(), $search_time = "", $task_name = "") {
        //表名
        $table_finish_task = C("DB_PREFIX") . 'finish_task';
        $table_task = C("DB_PREFIX") . 'task';
        $table_user = C("DB_PREFIX") . 'user';
        $table_dept = C("DB_PREFIX") . 'dept';
        $table_specialty = C("DB_PREFIX") . 'specialty';
        //连接名
        $join_finish_task = $table_finish_task . ' as finish_task ON finish_task.uid=user.id ';
        $join_task = $table_task . ' as task ON finish_task.task_id=task.id ';
        $join_user = $table_user . ' as user ';
        $join_dept = $table_dept . ' as dept ON user.dept_id = dept.id';
        $join_specialty = $table_specialty . ' as specialty ON user.specialty_id = specialty.id';
        $where["finish_task.task_id"] = array("neq", '');
        $where["task.task_name"] = array("neq", '');
        //要查询的字段
        $field = "finish_task.*,finish_task.id as finish_task_id,task.*,user.*,dept.name as dept_name,specialty.name as specialty_name";
        //分页查询
        //计算总记录数
        $total = M()->join($join_finish_task)->join($join_task)->table($join_user)->join($join_dept)->join($join_specialty)->where($where)->count();
        //读取配置文件的每页分页记录数
        $listRows = C("PAGE_LISTROWS");
        import('@.Components.Page');
        $page = new Page($total, $listRows, "search_time=" . $search_time . "&specialty_id=" . $where["specialty.id"] . "&task_name=" . $task_name."&status=".$where["finish_task.status"]);
        //Limit 2, 1  ===>2,1  进行转换
        $limit = $page->limit;
        //去除空格 ====>2,1
        $limit = ltrim(str_replace("Limit", "", $limit));
        $task_status_list = M()->join($join_finish_task)->join($join_task)->table($join_user)->join($join_dept)->join($join_specialty)->field($field)->where($where)->order("task.task_code asc,finish_task.id desc")->limit($limit)->select();
        //show_msg_not_exit(M()->getLastSql());
        foreach ($task_status_list as $kk => $vv) {
            $position_ids = explode(",", $vv["position_ids"]);
            $temp = array();
            foreach ($position_ids as $k => $v) {
                $position_info = D("Position")->getById($v);
                $temp[] = $position_info["name"];
            }
            $position_names = implode(",", $temp);
            $position_names_short = formatStr($position_names, "8");
            //将点位名字数组添加进结果集
            $task_status_list[$kk]["position_names_arr"] = $temp;
            $task_status_list[$kk]["position_names"] = $position_names;
            $task_status_list[$kk]["position_names_short"] = $position_names_short;
        }
        $result["task_status_list"] = $task_status_list;
        $result["page"] = $page->fpage();
        return $result;
    }
    
    /**
     * 24小时内的巡检任务情况 管理员版 根据条件检索 为导出excel表格 不带分页
     * 2014-8-1 20:21:55
     * @return array 巡检任务情况结果集
     */
    public function getTaskStatusListByCondition4ExportExcel($where = array()) {
        //表名
        $table_finish_task = C("DB_PREFIX") . 'finish_task';
        $table_task = C("DB_PREFIX") . 'task';
        $table_user = C("DB_PREFIX") . 'user';
        $table_dept = C("DB_PREFIX") . 'dept';
        $table_specialty = C("DB_PREFIX") . 'specialty';
        //连接名
        $join_finish_task = $table_finish_task . ' as finish_task ON finish_task.uid=user.id ';
        $join_task = $table_task . ' as task ON finish_task.task_id=task.id ';
        $join_user = $table_user . ' as user ';
        $join_dept = $table_dept . ' as dept ON user.dept_id = dept.id';
        $join_specialty = $table_specialty . ' as specialty ON user.specialty_id = specialty.id';
        $where["finish_task.task_id"] = array("neq", '');
        $where["task.task_name"] = array("neq", '');
        //要查询的字段
        $field = "finish_task.*,finish_task.id as finish_task_id,task.*,user.*,dept.name as dept_name,specialty.name as specialty_name";
        $task_status_list = M()->join($join_finish_task)->join($join_task)->table($join_user)->join($join_dept)->join($join_specialty)->field($field)->where($where)->order("task.task_code asc,finish_task.id desc")->select();
        //show_msg_not_exit(M()->getLastSql());
        foreach ($task_status_list as $kk => $vv) {
            $position_ids = explode(",", $vv["position_ids"]);
            $temp = array();
            foreach ($position_ids as $k => $v) {
                $position_info = D("Position")->getById($v);
                $temp[] = $position_info["name"];
            }
            $position_names = implode(",", $temp);
            $position_names_short = formatStr($position_names, "8");
            //将点位名字数组添加进结果集
            $task_status_list[$kk]["position_names_arr"] = $temp;
            $task_status_list[$kk]["position_names"] = $position_names;
            $task_status_list[$kk]["position_names_short"] = $position_names_short;
        }
        $result["task_status_list"] = $task_status_list;
        return $result;
    }
    
    /**
     * 24小时内的巡检任务情况 管理员版 根据条件检索 为导出excel表格 带分页
     * 2014-6-29 17:37:23
     * @return array 巡检任务情况结果集
     */
    /*
    public function getTaskStatusListByCondition4ExportExcel($where = array(), $search_time = "", $task_name = "") {
        //表名
        $table_finish_task = C("DB_PREFIX") . 'finish_task';
        $table_task = C("DB_PREFIX") . 'task';
        $table_user = C("DB_PREFIX") . 'user';
        $table_dept = C("DB_PREFIX") . 'dept';
        $table_specialty = C("DB_PREFIX") . 'specialty';
        //连接名
        $join_finish_task = $table_finish_task . ' as finish_task ON finish_task.uid=user.id ';
        $join_task = $table_task . ' as task ON finish_task.task_id=task.id ';
        $join_user = $table_user . ' as user ';
        $join_dept = $table_dept . ' as dept ON user.dept_id = dept.id';
        $join_specialty = $table_specialty . ' as specialty ON user.specialty_id = specialty.id';
        $where["finish_task.task_id"] = array("neq", '');
        $where["task.task_name"] = array("neq", '');
        //要查询的字段
        $field = "finish_task.*,finish_task.id as finish_task_id,task.*,user.*,dept.name as dept_name,specialty.name as specialty_name";
        //分页查询
        //计算总记录数
        $total = M()->join($join_finish_task)->join($join_task)->table($join_user)->join($join_dept)->join($join_specialty)->where($where)->count();
        //读取配置文件的每页分页记录数
        $listRows = C("PAGE_LISTROWS");
        import('@.Components.Page');
        $page = new Page($total, $listRows, "search_time=" . $search_time . "&specialty_id=" . $where["specialty.id"] . "&task_name=" . $task_name."&status=".$where["finish_task.status"]);
        //Limit 2, 1  ===>2,1  进行转换
        $limit = $page->limit;
        //去除空格 ====>2,1
        $limit = ltrim(str_replace("Limit", "", $limit));
        $task_status_list = M()->join($join_finish_task)->join($join_task)->table($join_user)->join($join_dept)->join($join_specialty)->field($field)->where($where)->order("task.task_code asc,finish_task.id desc")->limit($limit)->select();
        //show_msg_not_exit(M()->getLastSql());
        foreach ($task_status_list as $kk => $vv) {
            $position_ids = explode(",", $vv["position_ids"]);
            $temp = array();
            foreach ($position_ids as $k => $v) {
                $position_info = D("Position")->getById($v);
                $temp[] = $position_info["name"];
            }
            $position_names = implode(",", $temp);
            $position_names_short = formatStr($position_names, "8");
            //将点位名字数组添加进结果集
            $task_status_list[$kk]["position_names_arr"] = $temp;
            $task_status_list[$kk]["position_names"] = $position_names;
            $task_status_list[$kk]["position_names_short"] = $position_names_short;
        }
        $result["task_status_list"] = $task_status_list;
        $result["page"] = $page->fpage();
        return $result;
    }
     */
    
    /**
     * 根据finish_task_id 查询任务完成情况
     * 2014-7-17 20:09:18
     * @return array 巡检任务情况结果集
     */
    public function getTaskStatusListByFinishTaskId($where = array()) {
        //表名
        $table_finish_task = C("DB_PREFIX") . 'finish_task';
        $table_task = C("DB_PREFIX") . 'task';
        $table_user = C("DB_PREFIX") . 'user';
        $table_dept = C("DB_PREFIX") . 'dept';
        $table_specialty = C("DB_PREFIX") . 'specialty';
        //连接名
        $join_finish_task = $table_finish_task . ' as finish_task ON finish_task.uid=user.id ';
        $join_task = $table_task . ' as task ON finish_task.task_id=task.id ';
        $join_user = $table_user . ' as user ';
        $join_dept = $table_dept . ' as dept ON user.dept_id = dept.id';
        $join_specialty = $table_specialty . ' as specialty ON user.specialty_id = specialty.id';
        $where["finish_task.task_id"] = array("neq", '');
        $where["task.task_name"] = array("neq", '');
        //要查询的字段
        $field = "finish_task.*,finish_task.id as finish_task_id,task.*,user.*,dept.name as dept_name,specialty.name as specialty_name";
        $task_status_info = M()->join($join_finish_task)->join($join_task)->table($join_user)->join($join_dept)->join($join_specialty)->field($field)->where($where)->order("finish_task.id desc")->find();
        //show_msg_not_exit(M()->getLastSql());
        $position_ids = explode(",", $task_status_info["position_ids"]);
        $temp = array();
        foreach ($position_ids as $k => $v) {
            $position_info = D("Position")->getById($v);
            $temp[] = $position_info["name"];
        }
        $position_names = implode(",", $temp);
        $position_names_short = formatStr($position_names, "8");
        $task_status_info["position_names"] = $position_names;
        $task_status_info["position_names_short"] = $position_names_short;
        return $task_status_info;
    }

}
