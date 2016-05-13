<?php

/**
 * @Description of TaskModel
 * @author Zhao-Hong-Yu <ZhaoHongYu, zhao_hong_yu@sina.cn> 
 * @copyright Copyright (C) 2014 赵洪禹 
 * @datetime 2014-7-9  15:20:10
 * @version 1.0
 */
class TaskModel extends Model {

    protected $_validate = array(
        // 自动验证定义
        //array(验证字段,验证规则,错误提示,[验证条件,附加规则,验证时间])
        array('task_name', 'require', '{%check_model_task_name_empty}'), //任务名称不能为空
        array('task_code', 'require', '{%check_model_task_code_empty}'), //任务编号不能为空
        array('start_time', 'require', '{%check_model_start_time_empty}'), // 还没有选择开始时间段
        array('end_time', 'require', '{%check_model_end_time_empty}'), // 还没有选择结束时间段
        array('specialty_id', 'require', '{%check_model_specialty_id_empty}'), // 还没有选择专业
    );

    /*
     * 根据条件分页查询已经设定的任务
     * @param type $condition 搜索条件
     */

    public function getTaskListByCondition($condition = array(), $task_name = "") {
        //计算总记录数
        $total = $this->where($condition)->count();
        //读取配置文件的每页分页记录数
        $listRows = C("PAGE_LISTROWS");
        import('@.Components.Page');
        $page = new Page($total, $listRows, "specialty_id=" . $condition["specialty_id"] . "&task_name=" . $task_name);
        //Limit 2, 1  ===>2,1  进行转换
        $limit = $page->limit;
        //去除空格 ====>2,1
        $limit = ltrim(str_replace("Limit", "", $limit));
        //查询任务
        //封装结果集
        $task_list = $this->where($condition)->limit($limit)->order("task_code")->select();
        foreach ($task_list as $kk => $vv) {
            $position_ids = explode(",", $vv["position_ids"]);
            $temp = array();
            foreach ($position_ids as $k => $v) {
                $position_info = D("Position")->getById($v);
                $temp[] = $position_info["name"];
            }
            $position_names = implode(",", $temp);
            $position_names_short = formatStr($position_names, "30");
            //将点位名字数组添加进结果集
            $task_list[$kk]["position_names_arr"] = $temp;
            $task_list[$kk]["position_names"] = $position_names;
            $task_list[$kk]["position_names_short"] = $position_names_short;
        }
        $result["task_list"] = $task_list;
        $result["page"] = $page->fpage();
        return $result;
    }
    
    /*
     * 根据专业id查询该专业下的所有任务
     * 2014-7-31 20:15:16
     * @param type $condition 搜索条件
     */

    public function getTaskListBySpecialtyID($condition = array()) {
        //查询任务
        //封装结果集
        $condition["task_name"] = array("neq", '');
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
            //将点位名字数组添加进结果集
            $task_list[$kk]["position_names_arr"] = $temp;
            $task_list[$kk]["position_names"] = $position_names;
            $task_list[$kk]["position_names_short"] = $position_names_short;
        }
        $result["task_list"] = $task_list;
        return $result;
    }

}
