<?php

/**
 * @Description of DeviceModel
 * @author Zhao-Hong-Yu <ZhaoHongYu, zhao_hong_yu@sina.cn> 
 * @copyright Copyright (C) 2014 赵洪禹 
 * @datetime 2014-6-26  16:48:44
 * @version 1.0
 */
class DeviceModel extends Model {

    protected $_validate = array(
        // 自动验证定义
        //array(验证字段,验证规则,错误提示,[验证条件,附加规则,验证时间])
        array('code', 'require', '{%device_model_code}'),
        //array('code', '', '{%device_model_exist}', 0, 'unique', 1), //检查设备编码时候存在
        //array('name', 'require', '{%device_model_name}'),
        //array('reference_value', 'require', '{%device_model_reference_value}'),
        array('position_id', 'require', '{%device_model_position_id}'),
        //array('check_standard', 'require', '{%device_check_standard}'),
        //array('status', 'require', '{%device_model_status}'), //设备状态选项-1-正常/0-异常
        //array('input_value', 'require', '{%device_model_input_value}'), //如果此设备检的need_input_value需要输入数值存入此字段
        //array('need_input_value', 'require ', '{%device_model_need_input_value}'), //是否需要输入数值1-需要/0-不需要
        //array('describes_exception', 'require', '{%device_model_describes_exception}'),//异常描述
        //array('frequency', 'number', '{%device_model_frequency}'), //设备的巡检频率-如1小时一次,2小时一次等
        //array('times', 'number', '{%device_model_times}'), //需要巡检的次数如、1次，2次、等'
        //array('remark', 'require', '{%device_model_remark}'),
    );

    /*
     * 根据点位id查询该点位下的设备列表
     */

    public function get_device_list($position_id) {
        $device_list = $this->order("code")->where(array("position_id" => $position_id))->select();
        $result["device_list"] = $device_list;
        return $result;
    }

}
