<?php

class DeptModel extends Model {

    protected $_validate = array(
        // 自动验证定义
        //array(验证字段,验证规则,错误提示,[验证条件,附加规则,验证时间])
        array('name', 'require', '{%dept_model_name}'),
        array('name','','{%dept_model_exist}',0,'unique',1), //该部门已经存在
        array('title', 'require', '{%dept_model_title}'),
        array('remark', 'require', '{%dept_model_remark}'),
    );
    // 自动完成定义
    protected $_auto = array(
        //array(填充字段,填充内容,[填充条件,附加规则])
        array('status', '1'), // 新增的时候把status字段设置为1 即 启用部门
    );

    /*
     * 分页查询部门列表 
     */

    public function get_dept_list() {
        //计算总记录数
        $total = M("Dept")->count();
        //读取配置文件的每页分页记录数
        $listRows = C("PAGE_LISTROWS");
        import('@.Components.Page');
        $page = new Page($total, $listRows);
        //Limit 2, 1  ===>2,1  进行转换
        $limit = $page->limit;
        //去除空格 ====>2,1
        $limit = ltrim(str_replace("Limit", "", $limit));
        //查询部门列表
        $table_dept = C("DB_PREFIX") . 'dept';
        $join_dept = $table_dept . ' as dept ON d1.pid=dept.id';
        $field = "d1.*,dept.name as shangjibumen";
        $dept_list = M()->table($table_dept . " as d1 ")->join($join_dept)->field($field)->order("level")->limit($limit)->select();
        $result["dept_list"] = $dept_list;
        $result["page"] = $page->fpage();
        return $result;
    }

}
