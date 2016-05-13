<?php

/**
 * Description of PositionModel
 *
 * @author zhaohyg
 */
class PositionModel extends Model {

    protected $_validate = array(
        //自动验证定义
        //array(验证字段,验证规则,错误提示,[验证条件,附加规则,验证时间])
        array('number', 'require', '点位编号必须填写!'), //点位编码必须填写
        array('number', '', '点位编号已经存在,请不要重复添加!', 0, 'unique', 1), // 在新增的时候验证点编号number字段是否唯一
        array('code', 'require', '点位编码必须填写!'), //点位编码必须填写
        array('code', '', '点位编码已经存在,请不要重复添加!', 0, 'unique', 1), // 在新增的时候验证点位编码code字段是否唯一
    );

    /**
     * 添加点位 提供给手机端录入数据
     * @param type $data
     */
    public function addPosition($data_array) {
        foreach ($data_array["Position"] as $k => $data) {
            $number = intval($data['PositionNumber']) ? intval($data['PositionNumber']) : 0;
            $code = $data['PositionCode'];
            $name = $data['PositionName'];
            if ($number != 0 && isset($code)) {
                $name = $data["PositionName"];
                $map["number"] = $number;
                $map["code"] = $code;
                $map["name"] = $name;
                $position_model = D("Position");
                $formdata = $position_model->create($map);
                if ($formdata) {
                    $rst = $position_model->add();
                    if ($rst > 0) {
                        //添加点位成功
                        $result[] = array(
                            'result' => 1,
                            'message' => "添加点位成功!",
                        );
                    } else {
                        //添加点位失败,请稍后重试！
                        $this->error = "添加点位失败,请稍后重试！";
                    }
                } else {
                    $this->error = $this->getError();
                }
            } else {
                $this->error = "请求数据错误!";
            }
            if (!$result) {
                $result[] = array(
                    'result' => 0,
                    'errorMessage' => $this->getError(),
                );
            }
        }
        return $result;
    }

    /*
     * 获取所有巡检点以及巡检点下的所有设备信息 
     */

    public function getPositionAndDevice() {
        //查询所有点位
        $position_list = D("Position")->select();
        foreach ($position_list as $k => $v) {
            //查询所有position_id 是 $v['id'] 的设备信息
            $device_info = D("Device")->where(array("position_id" => $v['id']))->select();
            $position_list[$k]['deviceList'] = $device_info;
        }
        $result["positionList"] = $position_list;
        $result["status"] = array(
            'result' => 1,
            'errorCode' => '',
            'errorMessage' => '',
        );
        //为统计流量做准备
        $uid = intval($data['UserId']) ? intval($data['UserId']) : 0;
        $flow_statistics_id = intval($data['FlowStatisticsId']) ? intval($data['FlowStatisticsId']) : 0;
        if ($uid != 0 && $flow_statistics_id != 0) {
            $result["UserId"] = $uid;
            $result["FlowStatisticsId"] = $flow_statistics_id;
        }
        return $result;
    }

    /**
     * 点位巡检之--上传正常/异常巡检信息
     * 
     */
    public function addRecord($data) {
        //为统计流量做准备
        //用户id
        $uid = intval($data['UserId']) ? intval($data['UserId']) : 0;
        $flow_statistics_id = intval($data['FlowStatisticsId']) ? intval($data['FlowStatisticsId']) : 0;
        if ($uid != 0 && $flow_statistics_id != 0) {
            $result["UserId"] = $uid;
            $result["FlowStatisticsId"] = $flow_statistics_id;
        }
        $error_code = C('ERROR_CODE');
        if ($uid == 0) {
            $result["status"] = array(
                'result' => 0,
                'errorCode' => 100,
                'errorMessage' => $error_code["100"],
            );
            return $result;
        }
        $task_id = intval($data['TaskId']) ? intval($data['TaskId']) : 0; //要完成的任务id
        $status = intval($data['Status']) ? intval($data['Status']) : 0; //设备的状态 正常为1 异常为0
        $describes_exception = isset($data['DescribesException']) ? $data['DescribesException'] : ''; //设备的异常描述或正常描述
        if ($describes_exception == "") {
            $result["status"] = array(
                'result' => 0,
                'errorCode' => 4214,
                'errorMessage' => $error_code["4214"],
            );
            return $result;
        }
        $need_input_value = intval($data['NeedInputValue']) ? intval($data['NeedInputValue']) : 0; //是否需要输入值
        $input_value = isset($data["InputValue"]) ? $data["InputValue"] : ''; //如果$need_input_value==1 则此值必须输入
        if ($need_input_value == 1) {
            if ($input_value == "") {
                $result["status"] = array(
                    'result' => 0,
                    'errorCode' => 4215,
                    'errorMessage' => $error_code["4215"],
                );
                return $result;
            }
        }
        $device_ids = isset($data["DeviceIds"]) ? $data["DeviceIds"] : ''; //这个点位下要巡检的设备id集合
        $device_id = intval($data['DeviceId']) ? intval($data['DeviceId']) : 0; //要检查的设备id
        if ($device_ids == "" || $device_id == 0 || $task_id == 0) {
            $result["status"] = array(
                'result' => 0,
                'errorCode' => 4216,
                'errorMessage' => $error_code["4216"],
            );
            return $result;
        }
        //根据task_id查询任务信息
        $circuit_info = D("Circuit")->where(array("task_id" => $task_id))->find();
        //将device_ids与checked_device_ids做差集
        $checked_device_ids = $circuit_info["checked_device_ids"];
        $device_ids_array = explode(",", $circuit_info["device_ids"]);
        /* //暂时放开验证
          if(!in_array($device_id, $device_ids_array)){
          //不在此任务中
          $result["status"] = array(
          'result' => 0,
          'errorCode' => 4225,
          'errorMessage' => $error_code["4225"],
          );
          return $result;
          }
         */
        //检查过的设备
        $checked_device_ids_array = explode(",", $checked_device_ids);
        //过滤空数组
        my_array_filter($checked_device_ids_array);
        $diff = array_diff($device_ids_array, $checked_device_ids_array);
        if (!empty($diff) && !in_array($device_id, $checked_device_ids_array)) {
            //未检查过的设备,做记录
            //更改in_device 表中的字段 status ,input_value, describes_exception
            $map_device["id"] = $device_id;
            $map_device["status"] = $status;
            if ($need_input_value == 1) {
                $map_device["input_value"] = $input_value;
            }
            $map_device["describes_exception"] = $describes_exception;
            D("Device")->save($map_device);
            //更新in_circuit 表中字段  checked_device_ids
            //添加新检查的设备id到已经检查的ids的数组中
            if (empty($checked_device_ids_array)) {
                $map_circuit["checked_device_ids"] = $device_id;
            } else {
                $checked_device_ids_array[] = $device_id;
                $map_circuit["checked_device_ids"] = implode(",", $checked_device_ids_array);
            }
            D("Circuit")->where(array("task_id" => $task_id))->save($map_circuit);
        } else {
            //没有未检查的设备,或者该记录已经提交,该设备已经检查过 返回
            $result["status"] = array(
                'result' => 0,
                'errorCode' => 4217,
                'errorMessage' => $error_code["4217"],
            );
            return $result;
        }
        //再重新查询一遍数据库 获取最新的checked_device_ids记录
        //如果与device_ids 相等则说明巡检任务已完成 更新本条记录的巡检状态 status=1;
        $circuit_info_new = D("Circuit")->where(array("task_id" => $task_id))->find();
        if ($circuit_info_new["device_ids"] == $circuit_info_new["checked_device_ids"]) {
            //两个字符串相等了,说明都已经检查过
            //继续更新此条记录的status 字段
            $map_circuit_new["status"] = 1;
            //计算是否超时完成,超时多长时间 用当前时间减去end_time 如果为负数则为未超时 为正数则为超时
            $end_time = $circuit_info_new["end_time"];
            $real_finish_time = time(); //实际完成的时间
            if (($real_finish_time - $end_time) < 0) {
                $map_circuit_new["is_overtime"] = 1; //标记为未超时
            } else {
                $map_circuit_new["is_overtime"] = 0; //标记为超时
                //计算超时时间
                $overtime = $real_finish_time - $end_time;
                $map_circuit_new["overtime"] = $overtime;
            }
            $map_circuit_new["submit_time"] = $real_finish_time; //记录任务真正的完成时间
            D("Circuit")->where(array("task_id" => $task_id))->save($map_circuit_new);
        }
        $device_info = D("Device")->where(array("id" => $device_id))->find();
        $position_info = D("Position")->where(array("id" => $circuit_info["position_id"]))->find();
        //去查询用户信息
        $user_info = D('App://User')->getUserInfo($uid);
        //录入记录到 巡检记录表中
        $map_record["start_time"] = $circuit_info["start_time"];
        $map_record["end_time"] = $circuit_info["end_time"];
        $map_record["submit_time"] = time(); //记录提交时间
        $map_record["uid"] = $circuit_info["uid"];
        $map_record["username"] = $user_info["username"];
        $map_record["real_name"] = $user_info["real_name"];
        $map_record["dept_id"] = $user_info["dept_id"];
        $map_record["dept_name"] = $user_info["dept_name"];
        $map_record["specialty_id"] = $user_info["specialty_id"];
        $map_record["specialty_name"] = $user_info["specialty_name"];
        $map_record["position_id"] = $circuit_info["position_id"];
        $map_record["position_name"] = $position_info["name"];
        $map_record["device_id"] = $device_id;
        $map_record["device_name"] = $device_info["name"];
        $map_record["device_status"] = $status;
        $map_record["device_describes_exception"] = $describes_exception;
        $map_record["device_check_standard"] = $device_info["check_standard"];
        $map_record["device_input_value"] = $device_info["input_value"];
        $map_record["device_reference_value"] = $device_info["reference_value"];
        $map_record["organization_id"] = $user_info["organization_id"];
        $map_record["organization_name"] = $user_info["organization_name"];
        $rst = D("Record")->add($map_record);
        if ($rst) {
            //添加记录成功
            $result["record"] = array(
                'message' => "下面返回的是巡检记录中的id,如果有需要可以保留!",
                'recordId' => $rst,
            );
            $result["status"] = array(
                'result' => 1,
                'errorCode' => '',
                'errorMessage' => '',
            );
            return $result;
        } else {
            //添加记录失败
            $result["status"] = array(
                'result' => 0,
                'errorCode' => 4218,
                'errorMessage' => $error_code["4218"],
            );
            return $result;
        }
    }

    /*
     * 根据隐患信息id修改隐患信息图片路径
     */

    public function updateHiddenTrouble($data) {
        $error_code = C('ERROR_CODE');
        //为统计流量做准备
        $uid = intval($data['UserId']) ? intval($data['UserId']) : 0; //上报隐患的人员id
        $flow_statistics_id = intval($data['FlowStatisticsId']) ? intval($data['FlowStatisticsId']) : 0;
        if ($uid != 0 && $flow_statistics_id != 0) {
            $result["UserId"] = $uid;
            $result["FlowStatisticsId"] = $flow_statistics_id;
        }
        $hiddenttroublelist = isset($data["HiddenTtroubleList"]) ? $data["HiddenTtroubleList"] : ''; //隐患信息集合
        if ($hiddenttroublelist == '') {
            $result["status"] = array(
                'result' => 0,
                'errorCode' => 4228,
                'errorMessage' => $error_code["4228"],
            );
            return $result;
        }
        //循环操作
        if (is_array($hiddenttroublelist)) {
            foreach ($hiddenttroublelist as $k => $v) {
                $hiddentroubleid = isset($v["HiddenTroubleId"]) ? $v["HiddenTroubleId"] : 0; //隐患信息id
                $deviceimagesrc = isset($v["DeviceImageSrc"]) ? $v["DeviceImageSrc"] : ''; //隐患信息图片路径
                if ($hiddentroubleid != 0 && $deviceimagesrc != '') {
                    //根据隐患信息id查询隐患信息记录
                    $hidden_trouble_info = D("HiddenTrouble")->getById($hiddentroubleid);
                    if (!empty($hidden_trouble_info)) {
                        //取出原有隐患信息图片路径
                        $old_deviceimagesrc = $hidden_trouble_info["device_image_src"];
                        if ($old_deviceimagesrc == '') {
                            $map["device_image_src"] = $deviceimagesrc;
                        } else {
                            //判断新纪录时候在路径中存在,不存在的才会存储 存在,会抛弃
                            $old_deviceimagesrc_arr = explode(",", $old_deviceimagesrc);
                            if (!in_array($deviceimagesrc, $old_deviceimagesrc_arr)) {
                                $map["device_image_src"] = $old_deviceimagesrc . "," . $deviceimagesrc;
                            }
                        }
                        $map["id"] = $hiddentroubleid;
                        D("HiddenTrouble")->save($map);
                    }
                }
            }
        }
        $result["status"] = array(
            'result' => 1,
            'errorCode' => '',
            'errorMessage' => '隐患信息更新成功!',
        );
        return $result;
    }

    /**
     * 
     * 添加设备隐患信息
     */
    public function addHiddenTrouble($data) {
        $error_code = C('ERROR_CODE');
        //为统计流量做准备
        $uid = intval($data['UserId']) ? intval($data['UserId']) : 0; //上报隐患的人员id
        $flow_statistics_id = intval($data['FlowStatisticsId']) ? intval($data['FlowStatisticsId']) : 0;
        if ($uid != 0 && $flow_statistics_id != 0) {
            $result["UserId"] = $uid;
            $result["FlowStatisticsId"] = $flow_statistics_id;
        }
        $hidden_trouble_describes_exception = isset($data["HiddenTroubleDescribesException"]) ? $data["HiddenTroubleDescribesException"] : ''; //隐患异常信息描述
        $device_id = intval($data["DeviceId"]) ? intval($data['DeviceId']) : 0; //隐患设备id
        $device_image_src = $data["DeviceImageSrc"]; //设备隐患图片地址,多个图片用逗号隔开
        $device_video_src = $data["DeviceVideoSrc"]; //设备隐患视频地址
        if ($uid == 0) {
            $result["status"] = array(
                'result' => 0,
                'errorCode' => 4219,
                'errorMessage' => $error_code["4219"],
            );
            return $result;
        }
        if ($hidden_trouble_describes_exception == '') {
            $result["status"] = array(
                'result' => 0,
                'errorCode' => 4220,
                'errorMessage' => $error_code["4220"],
            );
            return $result;
        }
        if ($device_id == 0) {
            $result["status"] = array(
                'result' => 0,
                'errorCode' => 4221,
                'errorMessage' => $error_code["4221"],
            );
            return $result;
        }
        //存在隐患将图片地址或者视频地址存入到隐患信息数据表中
        if ($device_image_src != '') {
            $map_hidden_trouble["device_image_src"] = $device_image_src;
        }
        if ($device_video_src != '') {
            $map_hidden_trouble["device_video_src"] = $device_video_src;
        }
        //根据设备id查询设备信息
        $device_info = D("Device")->where(array("id" => $device_id))->find();
        //根据设备id查询所在的点位信息
        $position_info = D("Position")->where(array("id" => $device_info["position_id"]))->find();
        //根据用户id查询用户详细信息
        $user_info = D('App://User')->getUserInfo($uid);
        $map_hidden_trouble["status"] = 0; //将此条隐患信息标为未处理
        $map_hidden_trouble["hidden_trouble_describes_exception"] = $hidden_trouble_describes_exception;
        $map_hidden_trouble["position_id"] = $position_info["id"];
        $map_hidden_trouble["position_name"] = $position_info["name"];
        $map_hidden_trouble["device_id"] = $device_id;
        $map_hidden_trouble["device_name"] = $device_info["name"];
        $map_hidden_trouble["uid"] = $uid;
        $map_hidden_trouble["real_name"] = $user_info["real_name"]; //提交隐患的人员名字
        $map_hidden_trouble["submit_time"] = time();
        $map_hidden_trouble["dept_id"] = $user_info["dept_id"];
        $map_hidden_trouble["dept_name"] = $user_info["dept_name"];
        $map_hidden_trouble["specialty_id"] = $user_info["specialty_id"];
        $map_hidden_trouble["specialty_name"] = $user_info["specialty_name"];
        $map_hidden_trouble["organization_id"] = $user_info["organization_id"];
        $map_hidden_trouble["organization_name"] = $user_info["organization_name"];
        $rst = D("HiddenTrouble")->add($map_hidden_trouble);
        if ($rst) {
            //发送邮件
            $body=  $this->hiddenTemplate($map_hidden_trouble);
            $rst_email=$this->_email($body);
            if($rst_email!=1){
                //发送失败记录到本地
                //本地log文件记录
                Log::$format = '[ Y-m-d H:i:s ]';
                $rst_email="发送隐患信息邮件失败,原因是:".$rst_email;
                Log::write($message=$rst_email,$level="INFO",$type='3',$destination='',$extra='');
            }
            //添加隐患记录成功
            $result["hiddenTrouble"] = array(
                'message' => "下面返回的是隐患记录中的id,如果有需要可以保留!",
                'hiddenTroubleId' => $rst,
            );
            $result["status"] = array(
                'result' => 1,
                'errorCode' => '',
                'errorMessage' => '',
            );
            return $result;
        } else {
            //添加隐患记录失败
            $result["status"] = array(
                'result' => 0,
                'errorCode' => 4222,
                'errorMessage' => $error_code["4222"],
            );
            return $result;
        }
    }

    /**
     * 点位巡检之--上传异常/正常信息 新版
     */
    public function submitRecord($data) {
        //为统计流量做准备
        $uid = intval($data['UserId']) ? intval($data['UserId']) : 0; //用户id
        $flow_statistics_id = intval($data['FlowStatisticsId']) ? intval($data['FlowStatisticsId']) : 0;
        if ($uid != 0 && $flow_statistics_id != 0) {
            $result["UserId"] = $uid;
            $result["FlowStatisticsId"] = $flow_statistics_id;
        }
        $error_code = C('ERROR_CODE');
        //完成任务的id 在任务状态表
        $finishTaskId = intval($data['FinishTaskId']) ? intval($data['FinishTaskId']) : 0;
        //记录提交时间 如果提供此字段 则就是用户提交的时间,没传递此参数,时间记录为系统时间
        //注意用户提交的为时间戳
        $submit_time = isset($data['SubmitTime']) ? $data['SubmitTime'] : time();
        if ($uid == 0) {
            $result["status"] = array(
                'result' => 0,
                'errorCode' => 100,
                'errorMessage' => $error_code["100"],
            );
            return $result;
        }
        if ($finishTaskId == 0) {
            $result["status"] = array(
                'result' => 0,
                'errorCode' => 4224,
                'errorMessage' => $error_code["4224"],
            );
            return $result;
        }
        $status = intval($data['Status']) ? intval($data['Status']) : 0; //设备的状态 正常为1 异常为0
        $describes_exception = isset($data['DescribesException']) ? $data['DescribesException'] : ''; //设备的异常描述或正常描述
        /* //去掉异常信息描述是必填项
          if ($describes_exception == "") {
          $result["status"] = array(
          'result' => 0,
          'errorCode' => 4214,
          'errorMessage' => $error_code["4214"],
          );
          return $result;
          }
         */
        $need_input_value = intval($data['NeedInputValue']) ? intval($data['NeedInputValue']) : 0; //是否需要输入值
        $input_value = isset($data["InputValue"]) ? $data["InputValue"] : ''; //如果$need_input_value==1 则此值必须输入
        if ($need_input_value == 1) {
            if ($input_value == "") {
                $result["status"] = array(
                    'result' => 0,
                    'errorCode' => 4215,
                    'errorMessage' => $error_code["4215"],
                );
                return $result;
            }
        }
        $device_id = intval($data['DeviceId']) ? intval($data['DeviceId']) : 0; //要检查的设备id
        if ($device_id == 0) {
            $result["status"] = array(
                'result' => 0,
                'errorCode' => 4221,
                'errorMessage' => $error_code["4221"],
            );
            return $result;
        }
        $device_info = D("Device")->where(array("id" => $device_id))->find();
        //根据finishTaskId查询任务信息 in_finish_task
        $finish_task_info = D("FinishTask")->where(array("id" => $finishTaskId))->find();
        //查询device_ids信息
        //将device_ids与checked_device_ids做差集
        $checked_device_ids = $finish_task_info["checked_device_ids"];
        $device_ids = $finish_task_info["device_ids"];
        $device_ids_array = explode(",", $device_ids);
        if (!in_array($device_id, $device_ids_array)) {
            //不在此任务中
            $result["status"] = array(
                'result' => 0,
                'errorCode' => 4225,
                'errorMessage' => $error_code["4225"],
            );
            return $result;
        }
        //检查过的设备
        $checked_device_ids_array = explode(",", $checked_device_ids);
        //过滤空数组
        my_array_filter($checked_device_ids_array);
        $diff = array_diff($device_ids_array, $checked_device_ids_array);
        if (!empty($diff) && !in_array($device_id, $checked_device_ids_array)) {
            //未检查过的设备,做记录
            //更改in_device 表中的字段 status ,input_value, describes_exception
            $map_device["id"] = $device_id;
            $map_device["status"] = $status;
            if ($need_input_value == 1) {
                $map_device["input_value"] = $input_value;
            }
            $map_device["describes_exception"] = $describes_exception;
            D("Device")->save($map_device);
            //更新in_finish_task 表中字段  checked_device_ids
            //添加新检查的设备id到已经检查的ids的数组中
            if (empty($checked_device_ids_array)) {
                $map_finish_task["checked_device_ids"] = $device_id;
            } else {
                $checked_device_ids_array[] = $device_id;
                $map_finish_task["checked_device_ids"] = implode(",", $checked_device_ids_array);
            }
            D("FinishTask")->where(array("id" => $finishTaskId))->save($map_finish_task);
        } else {
            //没有未检查的设备,或者该记录已经提交,该设备已经检查过 返回
            $result["status"] = array(
                'result' => 0,
                'errorCode' => 4217,
                'errorMessage' => $error_code["4217"],
            );
            return $result;
        }
        //再重新查询一遍数据库 获取最新的checked_device_ids记录
        //如果与device_ids 相等则说明巡检任务已完成 更新本条记录的巡检状态 status=1;
        $finish_task_info_new = D("FinishTask")->where(array("id" => $finishTaskId))->find();
        //查询任务信息
        $task_info = D("Task")->where(array("id" => $finish_task_info_new["task_id"]))->find();
        $device_ids_arr_new = explode(",", $finish_task_info_new["device_ids"]);
        $checked_device_ids_arr_new = explode(",", $finish_task_info_new["checked_device_ids"]);
        $diff_new = array_diff($device_ids_arr_new, $checked_device_ids_arr_new);
        if (empty($diff_new)) {
            //两个字符串相等了,说明都已经检查过
            //继续更新此条记录的status 字段
            $map_finish_task_new["status"] = 1;
            //计算是否超时完成,超时多长时间 用当前时间减去end_time 如果为负数则为未超时 为正数则为超时
            $end_time = strtotime($task_info["end_time"]);
            $start_time = strtotime($task_info["start_time"]);
            $real_finish_time = formatTime($submit_time, "H:i"); //实际完成的时间
            $real_finish_time = strtotime($real_finish_time);
            //取出日期 和领取时间进行比对 完成时间必须和领取时间的日期是同一天
            $submit_time_date = formatTime($submit_time, "Y-m-d"); //提交任务的时间的日期部分
            $receive_time_date = formatTime($finish_task_info_new["receive_time"], "Y-m-d"); //领取任务的时间的日期部分
            if ($real_finish_time < $end_time && $real_finish_time > $start_time && $submit_time_date == $receive_time_date) {
                $map_finish_task_new["is_overtime"] = 1; //标记为未超时
            } else {
                $map_finish_task_new["is_overtime"] = 0; //标记为超时
                //计算超时时间
                //日期部分 超时多少天
                $date = floor(($submit_time - strtotime($receive_time_date . " " . $task_info["end_time"])) / 86400);
                $hour = floor(($submit_time - strtotime($receive_time_date . " " . $task_info["end_time"])) % 86400 / 3600);
                $minute = floor((floor(($submit_time - strtotime($receive_time_date . " " . $task_info["end_time"])) % 86400) % 3600) / 60);
                $overtime = $date . "天" . $hour . "小时" . $minute . "分";
                $map_finish_task_new["overtime"] = $overtime;
            }
            $map_finish_task_new["real_submit_time"] = time(); //记录任务真正的完成时间--服务器时间
            $map_finish_task_new["finish_time"] = $submit_time; //记录任务真正的完成时间
            D("FinishTask")->where(array("id" => $finishTaskId))->save($map_finish_task_new);
        }
        $position_info = D("Position")->where(array("id" => $device_info["position_id"]))->find();
        //去查询用户信息
        $user_info = D('App://User')->getUserInfo($uid);
        //录入记录到 巡检记录表中
        $map_record["start_time"] = $task_info["start_time"];
        $map_record["end_time"] = $task_info["end_time"];
        $map_record["submit_time"] = $submit_time; //记录提交时间
        $map_record["real_submit_time"] = time(); //服务器记录的提交时间 2014-8-8 11:33:25 更新
        $map_record["uid"] = $finish_task_info["uid"];
        $map_record["username"] = $user_info["username"];
        $map_record["real_name"] = $user_info["real_name"];
        $map_record["dept_id"] = $user_info["dept_id"];
        $map_record["dept_name"] = $user_info["dept_name"];
        $map_record["specialty_id"] = $user_info["specialty_id"];
        $map_record["specialty_name"] = $user_info["specialty_name"];
        $map_record["position_id"] = $device_info["position_id"];
        $map_record["position_name"] = $position_info["name"];
        $map_record["device_id"] = $device_id;
        $map_record["device_name"] = $device_info["name"];
        $map_record["device_status"] = $status;
        $map_record["device_describes_exception"] = $describes_exception;
        $map_record["device_check_standard"] = $device_info["check_standard"];
        $map_record["device_input_value"] = $input_value;
        $map_record["device_reference_value"] = $device_info["reference_value"];
        $map_record["organization_id"] = $user_info["organization_id"];
        $map_record["organization_name"] = $user_info["organization_name"];
        $rst = D("Record")->add($map_record);
        if ($rst) {
            //添加记录成功
            $result["record"] = array(
                'message' => "下面返回的是巡检记录中的id,如果有需要可以保留!",
                'recordId' => $rst,
            );
            $result["status"] = array(
                'result' => 1,
                'errorCode' => '',
                'errorMessage' => '',
            );
            return $result;
        } else {
            //添加记录失败
            $result["status"] = array(
                'result' => 0,
                'errorCode' => 4218,
                'errorMessage' => $error_code["4218"],
            );
            return $result;
        }
    }

    /**
     * 点位巡检之--上传异常/正常信息 新版
     * 2014-8-8 12:28:47 支持多条记录同时上传
     */
    public function submitRecordMultiple($data) {
        //为统计流量做准备
        $uid = intval($data['UserId']) ? intval($data['UserId']) : 0; //用户id
        $flow_statistics_id = intval($data['FlowStatisticsId']) ? intval($data['FlowStatisticsId']) : 0;
        if ($uid != 0 && $flow_statistics_id != 0) {
            $result["UserId"] = $uid;
            $result["FlowStatisticsId"] = $flow_statistics_id;
        }
        $error_code = C('ERROR_CODE');
        if ($uid == 0) {
            $result["status"] = array(
                'result' => 0,
                'errorCode' => 100,
                'errorMessage' => $error_code["100"],
            );
            return $result;
        }
        $finishtasklist = isset($data['FinishTaskList']) ? $data['FinishTaskList'] : '';
        if (empty($finishtasklist)) {
            $result["status"] = array(
                'result' => 0,
                'errorCode' => 100,
                'errorMessage' => $error_code["100"],
            );
            return $result;
        }
        $count = 0;
        foreach ($finishtasklist as $k => $v) {
            //完成任务的id 在任务状态表
            $finishTaskId = intval($v['FinishTaskId']) ? intval($v['FinishTaskId']) : 0;
            //要检查的设备id
            $device_id = intval($v['DeviceId']) ? intval($v['DeviceId']) : 0;
            if ($finishTaskId != 0 && $device_id != 0) {
                $rst = $this->_process($v, $uid);
                if ($rst != 0) {
                    $count+=1;
                }
            }
        }
        $result["status"] = array(
            'result' => 1,
            'errorCode' => '',
            'errorMessage' => '成功提交了' . $count . '条任务记录!',
        );
        return $result;
    }

    /*
     * 处理用户提交的任务记录
     * 2014-8-8 12:43:31
     */

    private function _process($data, $uid) {
        //完成任务的id 在任务状态表
        $finishTaskId = intval($data['FinishTaskId']) ? intval($data['FinishTaskId']) : 0;
        //记录提交时间 如果提供此字段 则就是用户提交的时间,没传递此参数,时间记录为系统时间
        //注意用户提交的为时间戳
        $submit_time = isset($data['SubmitTime']) ? $data['SubmitTime'] : time();
        $status = intval($data['Status']) ? intval($data['Status']) : 0; //设备的状态 正常为1 异常为0
        $describes_exception = isset($data['DescribesException']) ? $data['DescribesException'] : ''; //设备的异常描述或正常描述
        $need_input_value = intval($data['NeedInputValue']) ? intval($data['NeedInputValue']) : 0; //是否需要输入值
        $input_value = isset($data["InputValue"]) ? $data["InputValue"] : ''; //如果$need_input_value==1 则此值必须输入
        $device_id = intval($data['DeviceId']) ? intval($data['DeviceId']) : 0; //要检查的设备id
        $device_info = D("Device")->where(array("id" => $device_id))->find();
        //根据finishTaskId查询任务信息 in_finish_task
        $finish_task_info = D("FinishTask")->where(array("id" => $finishTaskId))->find();
        //查询device_ids信息
        //将device_ids与checked_device_ids做差集
        $checked_device_ids = $finish_task_info["checked_device_ids"];
        $device_ids = $finish_task_info["device_ids"];
        $device_ids_array = explode(",", $device_ids);
        if (in_array($device_id, $device_ids_array)) {
            //检查过的设备
            $checked_device_ids_array = explode(",", $checked_device_ids);
            //过滤空数组
            my_array_filter($checked_device_ids_array);
            $diff = array_diff($device_ids_array, $checked_device_ids_array);
            if (!empty($diff) && !in_array($device_id, $checked_device_ids_array)) {
                //未检查过的设备,做记录
                //更改in_device 表中的字段 status ,input_value, describes_exception
                $map_device["id"] = $device_id;
                $map_device["status"] = $status;
                if ($need_input_value == 1) {
                    $map_device["input_value"] = $input_value;
                }
                $map_device["describes_exception"] = $describes_exception;
                D("Device")->save($map_device);
                //更新in_finish_task 表中字段  checked_device_ids
                //添加新检查的设备id到已经检查的ids的数组中
                if (empty($checked_device_ids_array)) {
                    $map_finish_task["checked_device_ids"] = $device_id;
                } else {
                    $checked_device_ids_array[] = $device_id;
                    $map_finish_task["checked_device_ids"] = implode(",", $checked_device_ids_array);
                }
                D("FinishTask")->where(array("id" => $finishTaskId))->save($map_finish_task);

                $position_info = D("Position")->where(array("id" => $device_info["position_id"]))->find();
                //查询任务信息
                $task_info2 = D("Task")->where(array("id" => $finish_task_info["task_id"]))->find();
                //去查询用户信息
                $user_info = D('App://User')->getUserInfo($uid);
                //录入记录到 巡检记录表中
                $map_record["start_time"] = $task_info2["start_time"];
                $map_record["end_time"] = $task_info2["end_time"];
                $map_record["submit_time"] = $submit_time; //记录提交时间
                $map_record["real_submit_time"] = time(); //服务器记录的提交时间 2014-8-8 11:33:25 更新
                $map_record["uid"] = $finish_task_info["uid"];
                $map_record["username"] = $user_info["username"];
                $map_record["real_name"] = $user_info["real_name"];
                $map_record["dept_id"] = $user_info["dept_id"];
                $map_record["dept_name"] = $user_info["dept_name"];
                $map_record["specialty_id"] = $user_info["specialty_id"];
                $map_record["specialty_name"] = $user_info["specialty_name"];
                $map_record["position_id"] = $device_info["position_id"];
                $map_record["position_name"] = $position_info["name"];
                $map_record["device_id"] = $device_id;
                $map_record["device_name"] = $device_info["name"];
                $map_record["device_status"] = $status;
                $map_record["device_describes_exception"] = $describes_exception;
                $map_record["device_check_standard"] = $device_info["check_standard"];
                $map_record["device_input_value"] = $input_value;
                $map_record["device_reference_value"] = $device_info["reference_value"];
                $map_record["organization_id"] = $user_info["organization_id"];
                $map_record["organization_name"] = $user_info["organization_name"];
                if($status==0){
                    //2014年8月21日 16:22:11
                    //记录异常信息到异常记录表中
                    D("ExceptionRecord")->add($map_record);
                    //发送邮件
                    $body=  $this->exceptionTemplate($map_record);
                    $rst_email=$this->_email($body);
                    if($rst_email!=1){
                        //发送失败记录到本地
                        //本地log文件记录
                        Log::$format = '[ Y-m-d H:i:s ]';
                        $rst_email="发送异常信息邮件失败,原因是:".$rst_email;
                        Log::write($message=$rst_email,$level="INFO",$type='3',$destination='',$extra='');
                    }
                }
                //记录巡检记录到记录表中包含正常信息和异常信息
                $rst = D("Record")->add($map_record);
            }
            //再重新查询一遍数据库 获取最新的checked_device_ids记录
            //如果与device_ids 相等则说明巡检任务已完成 更新本条记录的巡检状态 status=1;
            $finish_task_info_new = D("FinishTask")->where(array("id" => $finishTaskId))->find();
            //查询任务信息
            $task_info = D("Task")->where(array("id" => $finish_task_info_new["task_id"]))->find();
            $device_ids_arr_new = explode(",", $finish_task_info_new["device_ids"]);
            $checked_device_ids_arr_new = explode(",", $finish_task_info_new["checked_device_ids"]);
            $diff_new = array_diff($device_ids_arr_new, $checked_device_ids_arr_new);
            if (empty($diff_new)) {
                //两个字符串相等了,说明都已经检查过
                //继续更新此条记录的status 字段
                $map_finish_task_new["status"] = 1;
                //计算是否超时完成,超时多长时间 用当前时间减去end_time 如果为负数则为未超时 为正数则为超时
                $end_time = strtotime($task_info["end_time"]);
                $start_time = strtotime($task_info["start_time"]);
                $real_finish_time = formatTime($submit_time, "H:i"); //实际完成的时间
                $real_finish_time = strtotime($real_finish_time);
                //取出日期 和领取时间进行比对 完成时间必须和领取时间的日期是同一天
                $submit_time_date = formatTime($submit_time, "Y-m-d"); //提交任务的时间的日期部分
                $receive_time_date = formatTime($finish_task_info_new["receive_time"], "Y-m-d"); //领取任务的时间的日期部分
                if ($real_finish_time < $end_time && $real_finish_time > $start_time && $submit_time_date == $receive_time_date) {
                    $map_finish_task_new["is_overtime"] = 1; //标记为未超时
                } else {
                    $map_finish_task_new["is_overtime"] = 0; //标记为超时
                    //计算超时时间
                    //日期部分 超时多少天
                    $date = floor(($submit_time - strtotime($receive_time_date . " " . $task_info["end_time"])) / 86400);
                    $hour = floor(($submit_time - strtotime($receive_time_date . " " . $task_info["end_time"])) % 86400 / 3600);
                    $minute = floor((floor(($submit_time - strtotime($receive_time_date . " " . $task_info["end_time"])) % 86400) % 3600) / 60);
                    $overtime = $date . "天" . $hour . "小时" . $minute . "分";
                    $map_finish_task_new["overtime"] = $overtime;
                }
                $map_finish_task_new["real_submit_time"] = time(); //记录任务真正的完成时间--服务器时间
                $map_finish_task_new["finish_time"] = $submit_time; //记录任务真正的完成时间--用户提交
                D("FinishTask")->where(array("id" => $finishTaskId))->save($map_finish_task_new);
                //2014年9月1日 22:09:39 新增一个任务可以多个人领取，但是当这个任务无论是谁完成的，
                //只要完成了，就只显示那个完成的任务，
                //其他人领取的未完成就自动删除或隐藏。要是都是未完成的就都显示出来。
                //删除其他人误操作领取的任务 即 id不等于$finishTaskId task_id=$task_info['id'] 时间 receive_time=当天
                $today=date("Y-m-d");
                $del_map["_string"] = "FROM_UNIXTIME(receive_time,'%Y-%m-%d')='$today'";
                $del_map["id"]=array('neq',$finishTaskId);
                $del_map["task_id"]=$task_info['id'];
                //DELETE FROM `in_finish_task` WHERE ( FROM_UNIXTIME(receive_time,'%Y-%m-%d')='2014-09-01' ) AND ( `id` <> 956 ) AND ( `task_id` = 215 )
                D("FinishTask")->where($del_map)->delete();
            }
            if ($rst) {
                //添加记录成功
                return $rst;
            } else {
                //添加记录失败
                return 0;
            }
        }
    }
    
    /*
     *发送邮件
     */
    private function _email($body) {
        //引入PHPMailer
        import("App.Components.PHPMailer.class#phpmailer", "", ".php");
        # Create object of PHPMailer
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->Host = C("HOST");//发件服务器地址
        $mail->Port = C("PORT");//端口
        $mail->Username = C("USER_NAME");//发件人邮箱用户名
        $mail->Password = C("PASSWORD");//发件人邮箱密码
        $mail->SetFrom(C("USER_NAME"), C("FA_JIAN_REN"));
        $mail->Subject = C("SUBJECT");
        $mail->CharSet = "UTF-8";   // 这里指定字符集！    
        $mail->Encoding = "base64";
        $mail->MsgHTML($body);
        $to = C("SENT_TO");
        $mail->AddAddress($to, C("SHOU_JIAN_REN"));
        if (!$mail->Send()) {
            return $mail->ErrorInfo;
        } else {
            return 1;
        }
    }
    
    /*
     * 异常信息模板
     */
    private function exceptionTemplate($data){
        $submit_time=date("Y-m-d H:i:s",$data['real_submit_time']);
        $exceptionTemplate=<<<EXCEPTIONTEMPLATE
<meta charset="UTF-8">
<div>
    <div style="font-size:14px;font-family:Verdana,&quot;宋体&quot;,Helvetica,sans-serif;line-height:1.66;padding:8px 10px;margin:0;overflow:auto">
        <table width="610" border="0" align="center" cellspacing="0">
            <tbody>
                <tr>
                    <td bgcolor="#f0f0f0" style="height:5px"></td>
                </tr>
                <tr>
                    <td bgcolor="#f0f0f0">
                        <table width="600" border="0" align="center" cellpadding="0" cellspacing="0" style=" border:1px solid #a1afb8">
                            <tbody>
                                <tr>
                                    <td bgcolor="#FFFFFF" style="font-size:14px; line-height:24px;">
                                        <table border="0" cellspacing="0" cellpadding="0" width="520" align="center">
                                            <tbody>
                                                <tr>
                                                    <td width="520" height="50" style="font-size:14px"> <strong>尊敬的
                                                            <span style="color:#E75C00">系统管理员</span>
                                                            ，您好！</strong> 
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style=" text-indent:2em;font-size:14px; line-height:30px">
                                                        您好,以下是异常信息详情,请您及时处理。
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style=" text-indent:2em;font-size:14px; line-height:30px">
                                                        专&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;业:{$data['specialty_name']}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style=" text-indent:2em;font-size:14px; line-height:30px">
                                                        巡&nbsp;&nbsp;检&nbsp;员:{$data['real_name']}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style=" text-indent:2em;font-size:14px; line-height:30px">
                                                        巡检点位:{$data['position_name']}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style=" text-indent:2em;font-size:14px; line-height:30px">
                                                        设&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;备:{$data['device_name']}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style=" text-indent:2em;font-size:14px; line-height:30px">
                                                        检查内容:{$data['device_check_standard']}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style=" text-indent:2em;font-size:14px; line-height:30px">
                                                        异常描述:{$data['device_describes_exception']}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style=" text-indent:2em;font-size:14px; line-height:30px">
                                                        上报时间:{$submit_time}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td height="20"></td>
                                                </tr>
                                                <tr>
                                                    <td height="10"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td bgcolor="#FFFFFF" style=" font-size:12px; padding:10px 25px;text-align:right;  "></td>
                                </tr>
                                <tr>
                                    <td bgcolor="#FFFFFF" style=" font-size:12px; padding:5px 20px; text-align:right; color:#999;  ">(注：此为系统邮件，请勿直接回复。)</td>
                                </tr>
                                <tr>
                                    <td bgcolor="#FFFFFF" style="font-size:12px; padding:20px; text-align:center; line-height:22px; color:#999">
                                        Copyright © 2000-2014 智能巡检系统 All Rights Reserved
                                        <br>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td bgcolor="#f0f0f0" style=" height:5px"></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
EXCEPTIONTEMPLATE;
        return $exceptionTemplate;
    }
    /*
     * 隐患信息模板
     */
    private function hiddenTemplate($data){
        $submit_time=date("Y-m-d H:i:s",$data['submit_time']);
        $hiddenTemplate=<<<HIDDENTEMPLATE
<meta charset="UTF-8">
<div>
    <div style="font-size:14px;font-family:Verdana,&quot;宋体&quot;,Helvetica,sans-serif;line-height:1.66;padding:8px 10px;margin:0;overflow:auto">
        <table width="610" border="0" align="center" cellspacing="0">
            <tbody>
                <tr>
                    <td bgcolor="#f0f0f0" style="height:5px"></td>
                </tr>
                <tr>
                    <td bgcolor="#f0f0f0">
                        <table width="600" border="0" align="center" cellpadding="0" cellspacing="0" style=" border:1px solid #a1afb8">
                            <tbody>
                                <tr>
                                    <td bgcolor="#FFFFFF" style="font-size:14px; line-height:24px;">
                                        <table border="0" cellspacing="0" cellpadding="0" width="520" align="center">
                                            <tbody>
                                                <tr>
                                                    <td width="520" height="50" style="font-size:14px"> <strong>尊敬的
                                                            <span style="color:#E75C00">系统管理员</span>
                                                            ，您好！</strong> 
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style=" text-indent:2em;font-size:14px; line-height:30px">
                                                        您好,以下是隐患信息详情,请您及时处理。
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style=" text-indent:2em;font-size:14px; line-height:30px">
                                                        巡检点位:{$data['position_name']}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style=" text-indent:2em;font-size:14px; line-height:30px">
                                                        设备名称:{$data['device_name']}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style=" text-indent:2em;font-size:14px; line-height:30px">
                                                        故障描述:{$data['hidden_trouble_describes_exception']}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style=" text-indent:2em;font-size:14px; line-height:30px">
                                                        上报人员:{$data['real_name']}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style=" text-indent:2em;font-size:14px; line-height:30px">
                                                        上报时间:{$submit_time}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td height="20"></td>
                                                </tr>
                                                <tr>
                                                    <td height="10"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td bgcolor="#FFFFFF" style=" font-size:12px; padding:10px 25px;text-align:right;  "></td>
                                </tr>
                                <tr>
                                    <td bgcolor="#FFFFFF" style=" font-size:12px; padding:5px 20px; text-align:right; color:#999;  ">(注：此为系统邮件，请勿直接回复。)</td>
                                </tr>
                                <tr>
                                    <td bgcolor="#FFFFFF" style="font-size:12px; padding:20px; text-align:center; line-height:22px; color:#999">
                                        Copyright © 2000-2014 智能巡检系统 All Rights Reserved
                                        <br>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td bgcolor="#f0f0f0" style=" height:5px"></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
HIDDENTEMPLATE;
        return $hiddenTemplate;
    }

}
