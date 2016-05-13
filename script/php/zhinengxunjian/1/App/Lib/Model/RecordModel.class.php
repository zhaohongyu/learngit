<?php

class RecordModel extends Model {

    /**
     * 根据条件搜索巡检记录 只要操作 in_record 表
     * 2014-6-30 23:42:30
     * @param type $where 搜索条件
     */
    public function getRecordListByCondition($where = array(), $search_time = '',$search_type='', $search_type_value='',$real_name='') {
        //读取配置文件的每页分页记录数
        $listRows = C("PAGE_LISTROWS");
        import('@.Components.Page');
        //要查询的字段
        $field = "";
        if (empty($where) && empty($search_time)) {
            //分页查询
            //计算总记录数
            $total = M("Record")->count();
            $page = new Page($total, $listRows);
            //Limit 2, 1  ===>2,1  进行转换
            $limit = $page->limit;
            //去除空格 ====>2,1
            $limit = ltrim(str_replace("Limit", "", $limit));
            $record_list = $this->field($field)->where($where)->order("submit_time desc")->limit($limit)->select();
            $result["record_list"] = $record_list;
            $result["page"] = $page->fpage();
        } else {
            //分页查询
            //计算总记录数
            $total = $this->field($field)->where($where)->count();
            $page = new Page($total, $listRows, "search_type=" . $search_type . "&search_type_value=" . $search_type_value ."&search_time=" . $search_time."&specialty_id=" . $where["specialty_id"].'&real_name='.$real_name);
            //Limit 2, 1  ===>2,1  进行转换
            $limit = $page->limit;
            //去除空格 ====>2,1
            $limit = ltrim(str_replace("Limit", "", $limit));
            //封装结果集
            $record_list = $this->field($field)->where($where)->order("submit_time desc")->limit($limit)->select();
            $result["record_list"] = $record_list;
            $result["page"] = $page->fpage();
        }
        return $result;
    }
    
    /**
     * 根据条件搜索巡检记录 只要操作 in_record 表  
     * 2014-8-1 11:12:51  新修改增加时间区间查询
     * @param type $where 搜索条件
     */
    public function getRecordListByCondition2($where = array(), $start_time='',$end_time='', $search_type='', $search_type_value='',$real_name='') {
        //读取配置文件的每页分页记录数
        $listRows = C("PAGE_LISTROWS");
        import('@.Components.Page');
        //要查询的字段
        $field = "";
        //分页查询
        //计算总记录数
        $total = $this->field($field)->where($where)->count();
        $page = new Page($total, $listRows, "search_type=" . $search_type . "&search_type_value=" . $search_type_value ."&start_time=" . $start_time."&end_time=" . $end_time."&specialty_id=" . $where["specialty_id"].'&real_name='.$real_name);
        //Limit 2, 1  ===>2,1  进行转换
        $limit = $page->limit;
        //去除空格 ====>2,1
        $limit = ltrim(str_replace("Limit", "", $limit));
        //封装结果集
        $record_list = $this->field($field)->where($where)->order("submit_time desc")->limit($limit)->select();
        $result["record_list"] = $record_list;
        $result["page"] = $page->fpage();
        return $result;
    }
    
    /**
     * 根据条件搜索巡检记录 只要操作 in_record 表  为导出excel表格的函数,去掉分页  
     * 2014-8-1 11:12:51  新修改增加时间区间查询 
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
