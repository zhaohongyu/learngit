<?php

/**
 * Description of CheckModel
 *
 * @author zhaohy
 * 2014-6-29 17:10:37
 */
class CheckModel extends Model {

    /**
     * 24小时内的巡检任务情况 管理员版 根据条件检索
     * 2014-6-29 17:37:23
     * @return array 巡检任务情况结果集
     */
    public function getTaskStatusListByCondition($where = array(), $search_time = "") {
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
        $page = new Page($total, $listRows, "search_time=" . $search_time . "&specialty_id=" . $where["specialty.id"] . "&dept_id=" . $where["dept.id"]);
        //Limit 2, 1  ===>2,1  进行转换
        $limit = $page->limit;
        //去除空格 ====>2,1
        $limit = ltrim(str_replace("Limit", "", $limit));
        $task_status_list = M()->join($join_circuit)->join($join_position)->table($join_user)->join($join_dept)->join($join_specialty)->field($field)->where($where)->order("end_time desc")->limit($limit)->select();
        $result["task_status_list"] = $task_status_list;
        $result["page"] = $page->fpage();
        return $result;
    }

}
