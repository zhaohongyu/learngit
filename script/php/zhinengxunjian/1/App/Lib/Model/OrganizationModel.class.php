<?php

class OrganizationModel extends Model {

    protected $_validate = array(
        // 自动验证定义
        //array(验证字段,验证规则,错误提示,[验证条件,附加规则,验证时间])
        array('name', 'require', '{%organization_model_name}'),
        //array('name','','{%organization_model_exist}',0,'unique',1), //该职位已经存在
        array('dept_id','require','{%organization_add_not_select_dept_id}'), //还没有选择dept_id
        array('title', 'require', '{%organization_model_title}'),
    );
    // 自动完成定义
    protected $_auto = array(
        //array(填充字段,填充内容,[填充条件,附加规则])
        array('status', '1'), // 新增的时候把status字段设置为1 即 启用职位
    );

    /*
     * 分页查询组织结构列表 
     */

    public function get_organization_list() {
        //计算总记录数
        $total = M("Organization")->count();
        //读取配置文件的每页分页记录数
        $listRows = C("PAGE_LISTROWS");
        import('@.Components.Page');
        $page = new Page($total, $listRows);
        //Limit 2, 1  ===>2,1  进行转换
        $limit = $page->limit;
        //去除空格 ====>2,1
        $limit = ltrim(str_replace("Limit", "", $limit));
        //查询职位列表
        $table_organization = C("DB_PREFIX") . 'organization';
        $table_dept = C("DB_PREFIX") . 'dept';
        $join_organization = $table_organization . ' as organization ON o1.pid=organization.id';
        $join_dept = $table_dept . ' as dept ON o1.dept_id=dept.id';
        $field = "o1.*,organization.name as shangjilingdao,dept.name as suozaibumen";
        $job_list = M()->table($table_organization . " as o1 ")->join($join_organization)->join($join_dept)->field($field)->order("id")->limit($limit)->select();
        $result["job_list"] = $job_list;
        $result["page"] = $page->fpage();
        return $result;
    }

}
