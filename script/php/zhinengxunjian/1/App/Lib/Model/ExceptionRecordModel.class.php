<?php

/*
 * 异常设备信息异常设备信息记录数据模型
 * 2014-8-21 16:27:36
 */
class ExceptionRecordModel extends Model {

    /**
     * 根据条件搜索巡检异常设备信息记录 只要操作 in_record 表
     * 2014-8-24 16:09:40
     * @param type $where 搜索条件
     */
    public function getRecordListByCondition($where = array(), $start_time='',$end_time='',$real_name='',$status='') {
        //读取配置文件的每页分页异常设备信息记录数
        $listRows = C("PAGE_LISTROWS");
        import('@.Components.Page');
        //要查询的字段
        $field = "";
        //分页查询
        //计算总异常设备信息记录数
        $total = $this->field($field)->where($where)->count();
        $page = new Page($total, $listRows, "start_time=" . $start_time."&end_time=" . $end_time."&specialty_id=" . $where["specialty_id"].'&real_name='.$real_name.'&status='.$status);
        //Limit 2, 1  ===>2,1  进行转换
        $limit = $page->limit;
        //去除空格 ====>2,1
        $limit = ltrim(str_replace("Limit", "", $limit));
        //封装结果集
        $record_list = $this->field($field)->where($where)->order("submit_time desc")->limit($limit)->select();
        $result["record_list"] = $record_list;
        $result["page"] = $page->fpage();
        //show_msg_not_exit($this->getLastSql());
        return $result;
    }
    
    /**
     * 根据条件搜索巡检异常设备信息记录 只要操作 in_record 表  为导出excel表格的函数,去掉分页  
     * 2014-8-21 16:27:46  新修改增加时间区间查询 
     * @param type $where 搜索条件
     */
    public function getRecordListByCondition4Export($where = array()) {
        //要查询的字段
        $field = "";
        //封装结果集
        $record_list = $this->field($field)->where($where)->order("submit_time desc")->select();
        $result["record_list"] = $record_list;
        return $result;
    }

}
