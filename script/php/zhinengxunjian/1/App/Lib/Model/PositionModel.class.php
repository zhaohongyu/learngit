<?php

class PositionModel extends Model {

    protected $_validate = array(
        //自动验证定义
        //array(验证字段,验证规则,错误提示,[验证条件,附加规则,验证时间])
        array('number', 'require', '{%position_model_number}'), //点位编码必须填写
        array('number', '', '{%position_model_number_exits}', 0, 'unique', 3), // 验证点编号number字段是否唯一
        array('code', 'require', '{%position_model_code}'), //点位编码必须填写
        array('code', '', '{%position_model_code_exits}', 0, 'unique', 3), // 验证点位编码code字段是否唯一
        //array('name', 'require', '{%position_model_name}'), //点位名称必须填写
        //array('position', 'require', '{%position_model_position}'), //点位位置必须填写
        //array('remark', 'require', '{%position_model_remark}'), // 点位备注必须填写
    );

    /**
     * 根据条件分页查询列出点位信息
     * 2014-6-26 00:30:09
     */
    public function get_position_list($where = array(), $search_type = "", $search_type_value = "", $id = "") {
        //计算总记录数
        $total = $this->where($where)->count();
        //读取配置文件的每页分页记录数
        $listRows = C("PAGE_LISTROWS");
        import('@.Components.Page');
        $page = new Page($total, $listRows, "search_type=" . $search_type . "&search_type_value=" . $search_type_value . "&id=" . $id);
        //Limit 2, 1  ===>2,1  进行转换
        $limit = $page->limit;
        //去除空格 ====>2,1
        $limit = ltrim(str_replace("Limit", "", $limit));
        //查询点位
        //封装结果集
        $position_list = $this->where($where)->limit($limit)->order("number")->select();
        $result["position_list"] = $position_list;
        $result["page"] = $page->fpage();
        return $result;
    }
    /**
     * 分页查询列出所有点位信息
     * 2014-7-8 10:33:59
     */
    public function getPositionList() {
        //计算总记录数
        $total = $this->count();
        //读取配置文件的每页分页记录数
        $listRows = C("PAGE_LISTROWS");
        import('@.Components.Page');
        $page = new Page($total, $listRows);
        //Limit 2, 1  ===>2,1  进行转换
        $limit = $page->limit;
        //去除空格 ====>2,1
        $limit = ltrim(str_replace("Limit", "", $limit));
        //查询点位
        //封装结果集
        $position_list = $this->limit($limit)->order("number")->select();
        $result["position_list"] = $position_list;
        $result["page"] = $page->fpage();
        return $result;
    }
    

}
