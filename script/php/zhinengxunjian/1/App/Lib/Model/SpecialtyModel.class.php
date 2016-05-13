<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * @Description of SpecialtyModel
 * @author Zhao-Hong-Yu <ZhaoHongYu, zhao_hong_yu@sina.cn> 
 * @copyright Copyright (C) 2014 赵洪禹 
 * @datetime 2014-6-27  12:13:42
 * @version 1.0
 */
class SpecialtyModel extends Model {

    protected $_validate = array(
        // 自动验证定义
        //array(验证字段,验证规则,错误提示,[验证条件,附加规则,验证时间])
        array('name', 'require', '{%specialty_model_name}'),
        array('name', '', '{%specialty_model_exist}', 0, 'unique', 1), //该专业已经存在
        array('remark', 'require', '{%specialty_model_remark}'),
    );
    // 自动完成定义
    protected $_auto = array(
        //array(填充字段,填充内容,[填充条件,附加规则])
        array('status', '1'), // 新增的时候把status字段设置为1 即 启用专业
    );

    /**
     * 分页查询专业信息
     * 2014-6-27 12:15:58
     */
    public function get_specialty_list() {
        //计算总记录数
        $total = M("Specialty")->count();
        //读取配置文件的每页分页记录数
        $listRows = C("PAGE_LISTROWS");
        import('@.Components.Page');
        $page = new Page($total, $listRows);
        //Limit 2, 1  ===>2,1  进行转换
        $limit = $page->limit;
        //去除空格 ====>2,1
        $limit = ltrim(str_replace("Limit", "", $limit));
        //查询专业列表
        //封装结果
        $specialty_list = M("Specialty")->limit($limit)->select();
        $result["specialty_list"] = $specialty_list;
        $result["page"] = $page->fpage();
        return $result;
    }

}
