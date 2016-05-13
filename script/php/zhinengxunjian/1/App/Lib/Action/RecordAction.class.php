<?php

/**
 * @Description of RecordAction 巡检记录管理
 * @author Zhao-Hong-Yu <ZhaoHongYu, zhao_hong_yu@sina.cn> 
 * @copyright Copyright (C) 2014 赵洪禹 
 * @datetime 2014-6-29 23:07:10
 * @version 1.0
 */
class RecordAction extends CommonAction {

    /**
     * 巡检记录管理
     * 部门—专业—巡检员—巡检点位—设备—检查内容—参考内容—描述（正常/数值）
     * （巡检记录，为巡检的核心内容，是检验巡检工作的关键所在，
     * 通过手机端的巡检采集、信息录入，同时经过服务器的记忆、
     * 筛选以达到监督巡检人员的是否到位、设备室否正常的目的。
     * 巡检记录为全部的巡检信息，包括正常和异常的信息。并支持打印和信息导出功能）
     */
    /*
    public function checkrecord() {
        //查询所有的巡检记录 不指定条件默认查询所有
        $result = D("Record")->getRecordListByCondition();
        //去查询界面 该界面指定出 部门—专业—职位—姓名
        //查询所有启用状态下的部门
        $dept_list = D("Dept")->where(array("status" => 1))->select();
        //查询出所启用状态下的专业
        $specialty_list = D("Specialty")->where(array("status" => 1))->select();
        //查询所有启用状态下的职位
        $organization_list = D("Organization")->where(array("status" => 1))->select();
        //给模板分配查询到的信息
        $page_now = isset($_GET["page"]) ? $_GET["page"] : 1;
        $this->assign("page_now", $page_now);
        $this->assign("record_list", $result["record_list"]);
        $this->assign("organization_list", $organization_list);
        $this->assign("specialty_list", $specialty_list);
        $this->assign("dept_list", $dept_list);
        $this->assign("page", $result["page"]);
        $this->display("recordlist");
    }*/
    
    public function checkrecord() {
        $url = getUrl("Record/checkrecordbycondition");
        header("location:{$url}");
    }

    /**
     * 根据条件搜索巡检记录
     */
    /*
      public function checkrecordbycondition() {
      //搜索的时间
      $search_time = I("param.search_time", 0);
      //指定专业
      $specialty_id = I("param.specialty_id", 0);
      //2014-7-15 10:59:49 改 增加搜索条件
      $search_type = I("param.search_type", '');
      $search_type_value = I("param.search_type_value", '');
      //拼装搜索条件
      $condition = array();
      if (!empty($search_type_value)) {
      $condition[$search_type] = array('like', "%" . $search_type_value . "%");
      }
      if ($search_time != 0) {
      $condition["_string"] = "FROM_UNIXTIME(submit_time,'%Y-%m-%d')='$search_time'";
      }
      if ($specialty_id != 0) {
      $condition["specialty_id"] = $specialty_id;
      }
      //查询所有的巡检记录
      $result = D("Record")->getRecordListByCondition($condition, $search_time, $search_type, $search_type_value);
      //去查询界面
      //查询出所启用状态下的专业
      $specialty_list = D("Specialty")->where(array("status" => 1))->select();
      //给模板分配查询到的信息
      //获取当前页
      $page_now = isset($_GET["page"]) ? $_GET["page"] : 1;
      $this->assign("page_now", $page_now);
      $this->assign("record_list", $result["record_list"]);
      $this->assign("specialty_list", $specialty_list);
      $this->assign("page", $result["page"]);
      //回传数据为了回显
      $this->assign("search_time", $search_time);
      $this->assign("specialty_id", $specialty_id);
      $this->assign("search_type", $search_type);
      $this->assign("search_type_value", $search_type_value);
      $this->display("recordlist");
      }
     */

    /**
     * 根据条件搜索巡检记录
     */
    public function checkrecordbycondition() {
        //搜索的时间
        $user_start_time = I("param.start_time", ''); //查询起始时间
        $user_end_time = I("param.end_time", ''); //查询结束时间
        $today = date("Y-m-d 00:00:00");
        if ($user_start_time != '') {
            //开始时间 
            $start_time = strtotime($user_start_time);
        } else {
            //没有开始时间按照当天零点开始
            $start_time = strtotime($today);
        }
        if ($user_end_time != '') {
            //结束时间
            $user_end_time=$user_end_time." 23:59:59";
            $end_time = strtotime($user_end_time);
        } else {
            //没有结束时间按照当天24点计算
            $end_today = date("Y-m-d 23:59:59");
            $end_time = strtotime($end_today);
        }
        //指定专业
        $specialty_id = I("param.specialty_id", 0);
        //2014-7-15 10:59:49 改 增加搜索条件
        $search_type = I("param.search_type", '');
        $search_type_value = I("param.search_type_value", '');
        //拼装搜索条件
        $condition = array();
        if (!empty($search_type_value)) {
            $condition[$search_type] = array('like', "%" . $search_type_value . "%");
        }
        //搜索时间区间
        $condition['submit_time'] = array('between', array($start_time, $end_time));
        if ($specialty_id != 0) {
            $condition["specialty_id"] = $specialty_id;
        }
        //查询所有的巡检记录
        $u_start_time=date("Y-m-d",$start_time);
        $u_end_time=date("Y-m-d",$end_time);
        $result = D("Record")->getRecordListByCondition2($condition, $u_start_time,$u_end_time, $search_type, $search_type_value);
        //去查询界面
        //查询出所启用状态下的专业
        $specialty_list = D("Specialty")->where(array("status" => 1))->select();
        //给模板分配查询到的信息
        //获取当前页
        $page_now = isset($_GET["page"]) ? $_GET["page"] : 1;
        $this->assign("page_now", $page_now);
        $this->assign("record_list", $result["record_list"]);
        $this->assign("specialty_list", $specialty_list);
        $this->assign("page", $result["page"]);
        //回传数据为了回显
        $this->assign("start_time", $u_start_time);
        $this->assign("end_time", $u_end_time);
        $this->assign("specialty_id", $specialty_id);
        $this->assign("search_type", $search_type);
        $this->assign("search_type_value", $search_type_value);
        $this->display("recordlist");
    }

    /*
     * [uid] => 3
     * [device_ids] => 7,8
     * [position_id] => 1
     * [position_name] => 巡检点1号
     * [username] => test
     * [real_name] => 巡检人员
     * [specialty_id] => 1
     * [dept_id] => 1
     * [organization_id] => 1
     * [dept_name] => 发电部
     * [specialty_name] => 电气专业
     * [device_names] => 设备01,设备02
     */

    public function addrecord() {
        //模拟手机端录入信息
        $task_info = I("param.");
        $device_ids = $task_info['device_ids'];
        $device_list = array();
        foreach (explode(",", $device_ids) as $k => $v) {
            $device_list[] = D("Device")->where(array("id" => $v))->find();
        }
        //循环查询设备的信息
        $this->assign("task_info", $task_info);
        $this->assign("device_list", $device_list);
        //去指定设备界面
        $this->display("selectdevice");
    }

    /**
     * 去填写设备信息界面
     */
    public function writeinfo() {
        $task_info = I("param.");
        $device_info = D("Device")->where(array("id" => $task_info['device_id']))->find();
        $this->assign("task_info", $task_info);
        $this->assign("device_info", $device_info);
        //去录入设备情况界面
        $this->display("writeinfo");
    }

    /**
     * 记录用户提交的数据
     */
    public function doaddrecord() {
        //用户id
        $uid = I("post.uid", 0);
        $input_value = I("post.input_value", '');
        $hidden_trouble = I("post.hidden_trouble", ''); //是否存在隐患
        $hidden_trouble_describes_exception = I("post.hidden_trouble_describes_exception", ''); //隐患信息描述
        if ($hidden_trouble == "yes") {
            if (empty($hidden_trouble_describes_exception)) {
                //您还没有描述设备的隐患信息
                $this->error(L("record_doaddrecord_no_hidden_trouble_describes_exception"));
            }
        }
        $status = I("post.status", ''); //设备的状态 正常为1 异常为0
        $describes_exception = I("post.describes_exception", '');
        //提交上来的设备状态 正常/异常
        //正常或异常描述
        $need_input_value = I("post.need_input_value", 0);
        if ($need_input_value == 1) {
            if (empty($input_value)) {
                //该设备要求输入值,您还没有输入值
                $this->error(L("record_doaddrecord_no_input_value"));
            }
        }
        if (empty($status)) {
            //还没有选择设备的状态
            $this->error(L("record_doaddrecord_no_select_status"));
        }
        if (empty($describes_exception)) {
            //还没有填写描述
            $this->error(L("record_doaddrecord_no_describes_exception"));
        }
        if ($status == "yes") {
            $status = 1;
        } else {
            $status = 0;
        }
        $avatar_src = I("post.avatar", ''); //隐患的设备图片地址
        $video_src = I("post.video", 0); //隐患的设备视频地址
        $device_id = I("post.device_id", 0);
        $device_ids = I("post.device_ids", '');
        $start_time = I("post.start_time", 0);
        $end_time = I("post.end_time", 0);
        $device_name = I("post.device_name", '');
        $position_id = I("post.position_id", 0);
        $position_name = I("post.position_name", '');
        $username = I("post.username", '');
        $real_name = I("post.real_name", '');
        $specialty_id = I("post.specialty_id", 0);
        $specialty_name = I("post.specialty_name", '');
        $dept_id = I("post.dept_id", 0);
        $dept_name = I("post.dept_name", '');
        $organization_id = I("post.organization_id", 0);
        //查询到要更新的更新in_circuit的 信息
        $where['uid'] = $uid;
        $where['device_id'] = $device_id;
        $where['device_ids'] = $device_ids;
        $where['start_time'] = $start_time;
        $where['end_time'] = $end_time;
        $where['position_id'] = $position_id;
        //SELECT * FROM `in_circuit` WHERE ( `uid` = 3 ) AND ( `device_ids` = '4,5,10' ) AND ( `start_time` = 1404061599 ) AND ( `end_time` = 1404065199 ) AND ( `position_id` = 2 ) LIMIT 1  
        $circuit_info = D("Circuit")->where($where)->find();
        //将device_ids与checked_device_ids做差集
        $checked_device_ids = $circuit_info["checked_device_ids"];
        $device_ids_array = explode(",", $device_ids);
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
            $map_device["input_value"] = $input_value;
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
            D("Circuit")->where($where)->save($map_circuit);
        } else {
            //没有未检查的设备,或者该记录已经提交,该设备已经检查过 返回
            $this->error(L("record_doaddrecord_no_device_to_check_or_checked"));
        }
        //再重新查询一遍数据库 获取最新的checked_device_ids记录
        //如果与device_ids 相等则说明巡检任务已完成 更新本条记录的巡检状态 status=1;
        $circuit_info_new = D("Circuit")->where($where)->find();
        if ($circuit_info_new["device_ids"] == $circuit_info_new["checked_device_ids"]) {
            //两个字符串相等了,说明都已经检查过
            //继续更新此条记录的status 字段
            $map_circuit_new["status"] = 1;
            D("Circuit")->where($where)->save($map_circuit_new);
        }
        $device_info = D("Device")->where(array("id" => $device_id))->find();
        $organization_info = D("Organization")->where(array("id" => $organization_id))->find();
        //录入记录到 巡检记录表中
        $map_record["start_time"] = $start_time;
        $map_record["end_time"] = $end_time;
        $map_record["submit_time"] = time(); //记录提交时间
        $map_record["uid"] = $uid;
        $map_record["username"] = $username;
        $map_record["real_name"] = $real_name;
        $map_record["dept_id"] = $dept_id;
        $map_record["dept_name"] = $dept_name;
        $map_record["specialty_id"] = $specialty_id;
        $map_record["specialty_name"] = $specialty_name;
        $map_record["position_id"] = $position_id;
        $map_record["position_name"] = $position_name;
        $map_record["device_id"] = $device_id;
        $map_record["device_name"] = $device_name;
        $map_record["device_status"] = $status;
        $map_record["device_describes_exception"] = $describes_exception;
        $map_record["device_check_standard"] = $device_info["check_standard"];
        $map_record["device_input_value"] = $device_info["input_value"];
        $map_record["device_reference_value"] = $device_info["reference_value"];
        $map_record["organization_id"] = $organization_id;
        $map_record["organization_name"] = $organization_info["name"];
        $rst = D("Record")->add($map_record);
        if ($hidden_trouble == "yes") {
            //存在隐患将图片地址或者视频地址存入到隐患信息数据表中
            if ($avatar_src != '') {
                $map_hidden_trouble["device_image_src"] = $avatar_src;
            }
            if ($video_src != '') {
                $map_hidden_trouble["device_video_src"] = $video_src;
            }
            $map_hidden_trouble["status"] = 0; //将此条隐患信息标为未处理
            $map_hidden_trouble["hidden_trouble_describes_exception"] = $hidden_trouble_describes_exception;
            $map_hidden_trouble["position_id"] = $position_id;
            $map_hidden_trouble["position_name"] = $position_name;
            $map_hidden_trouble["device_id"] = $device_id;
            $map_hidden_trouble["device_name"] = $device_name;
            $map_hidden_trouble["uid"] = $uid;
            $map_hidden_trouble["real_name"] = $real_name;
            $map_hidden_trouble["submit_time"] = time();
            $map_hidden_trouble["dept_id"] = $dept_id;
            $map_hidden_trouble["dept_name"] = $dept_name;
            $map_hidden_trouble["specialty_id"] = $specialty_id;
            $map_hidden_trouble["specialty_name"] = $specialty_name;
            $map_hidden_trouble["organization_id"] = $organization_id;
            $map_hidden_trouble["organization_name"] = $organization_info["name"];
        }
        $rst2 = D("HiddenTrouble")->add($map_hidden_trouble);
        if ($rst || $rst2) {
            //添加记录成功
            $this->success(L("record_doaddrecord_success"), getUrl("Check/myTask"));
        } else {
            //添加记录失败
            $this->error(L("record_doaddrecord_failed"));
        }
    }

    /**
     * 上传文件
     * @param type $savePath 上传路径 视频和图片请区分放置
     * @return type
     */
    protected function _upload($savePath) {
        //监测文件夹是否存在,不存在则创建
        if (!is_dir($savePath)) {
            mkdir($savePath);
        }
        import('ORG.Net.UploadFile');
        $config['savePath'] = $savePath; // 文件路径;
        $config['maxSize'] = 20971520; //20M最大
        $config['thumb'] = true;
        $config['thumbPrefix'] = 'm_';
        $config['thumbMaxWidth'] = '300';
        $config['thumbMaxHeight'] = '300';
        $config['allowExts'] = array('jpg', 'gif', 'png', 'jpeg', "mov", "mp4", "flv", 'JPG', 'GIF', 'PNG', 'JPEG', "MOV", "MP4", "FLV");
        $config['autoSub'] = true; // 开启子目录保存
        $config['subType'] = 'date'; //// 时间保存
        $config['dateFormat'] = 'Y-m-d';
        $upload = new UploadFile($config); // 实例化上传类并传入参数
        if ($upload->upload()) {
            return $upload->getUploadFileInfo();
        } else {
            // 捕获上传异常
            return $upload->getErrorMsg();
        }
    }

    //上传图片
    public function uploadImages() {
        if (!empty($_FILES)) {
            $result = $this->_upload(C("UPLOADS_PATH_IMAGES"));
            if (is_array($result)) {
                foreach ($result as $k => $v) {
                    if (!IS_SAE) {
                        //获得图片存储路径返回给客户端 取出缩略图
                        //获取文件名
                        $temp = explode("/", $v['savename']);
                        $findme = ".";
                        //去掉./Public/upload/images/中的点 ===>/Public/upload/images/
                        $v['savepath'] = substr($v['savepath'], strpos($v['savepath'], $findme) + 1);
                        $image_path = $v['savepath'] . $temp[0] . "/" . "m_" . $temp[1];
                    } else {
                        //兼容sae图片地址
                        $temp = C("TMPL_PARSE_STRING");
                        $image_path = $temp["./Public/upload/"] . "images/" . $v['savename'];
                    }
                }
                $image = getimagesize($image_path);
                $data['img'] = $image_path;
                $data['width'] = $image[0];
                $data['height'] = $image[1];
                $this->ajaxReturn($data, '上传成功', 1);
            } else {
                $this->ajaxReturn('', $result, 0);
            }
        }
    }

    //上传视频 20M最大
    public function uploadvideos() {
        if (!empty($_FILES)) {
            $result = $this->_upload(C("UPLOADS_PATH_VIDEOS"));
            if (is_array($result)) {
                foreach ($result as $k => $v) {
                    if (!IS_SAE) {
                        //获得视频存储路径返回给客户端 取出视频
                        //获取文件名
                        $temp = explode("/", $v['savename']);
                        $findme = ".";
                        //去掉./Public/upload/images/中的点 ===>/Public/upload/images/
                        $v['savepath'] = substr($v['savepath'], strpos($v['savepath'], $findme) + 1);
                        $video_path = $v['savepath'] . $temp[0] . "/" . $temp[1];
                    } else {
                        //兼容sae视频地址
                        $temp = C("TMPL_PARSE_STRING");
                        $video_path = $temp["./Public/upload/"] . "videos/" . $v['savename'];
                    }
                }
                $data['video'] = $video_path;
                $this->ajaxReturn($data, '上传视频成功', 1);
            } else {
                $this->ajaxReturn('', $result, 0);
            }
        }
    }

    /*
     * 导出巡检记录到excel
     * 2014年8月1日 12:18:24 去掉分页
     */

    public function exportExcel() {
        //搜索的时间
        $user_start_time = I("param.start_time", ''); //查询起始时间
        $user_end_time = I("param.end_time", ''); //查询结束时间
        $today = date("Y-m-d 00:00:00");
        if ($user_start_time != '') {
            //开始时间 
            $start_time = strtotime($user_start_time);
        } else {
            //没有开始时间按照当天零点开始
            $start_time = strtotime($today);
        }
        if ($user_end_time != '') {
            //结束时间
            $user_end_time=$user_end_time." 23:59:59";
            $end_time = strtotime($user_end_time);
        } else {
            //没有结束时间按照当天24点计算
            $end_today = date("Y-m-d 23:59:59");
            $end_time = strtotime($end_today);
        }
        //指定专业
        $specialty_id = I("param.specialty_id", 0);
        //2014-7-15 10:59:49 改 增加搜索条件
        $search_type = I("param.search_type", '');
        $search_type_value = I("param.search_type_value", '');
        //拼装搜索条件
        $file_name = "";
        $condition = array();
        //搜索时间区间
        $condition['submit_time'] = array('between', array($start_time, $end_time));
        $u_start_time=date("Y-m-d",$start_time);
        $u_end_time=date("Y-m-d",$end_time);
        $file_name.=$u_start_time."--".$u_end_time. " ";
        if ($specialty_id != 0) {
            $condition["specialty_id"] = $specialty_id;
            $specialty_info = D("Specialty")->getById($specialty_id);
            $file_name.=$specialty_info["name"] . "-";
        }
        if (!empty($search_type_value)) {
            $condition[$search_type] = array('like', "%" . $search_type_value . "%");
            if ($search_type == "real_name") {
                $file_name.="巡检人员-" . $search_type_value . "-";
            } else {
                $file_name.="巡检点位-" . $search_type_value . "-";
            }
        }
        if ($file_name == "") {
            $file_name = "巡检记录";
        } else {
            $file_name.="巡检记录";
        }
        //查询所有的巡检记录
        $result = D("Record")->getRecordListByCondition4Export($condition);
        $record_list = $result["record_list"];
        header("content-type:text/html;charset=utf-8");
        /** Error reporting */
        error_reporting(E_ALL);
        /** PHPExcel */
        import("@.Components.PHPExcel.PHPExcel", "", ".php");
        /** PHPExcel_Writer_Excel2003用于创建xls文件 */
        import("@.Components.PHPExcel.PHPExcel.Writer.Excel5", "", ".php");
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();
        // Set properties
        $objPHPExcel->getProperties()->setCreator($_SESSION["user_info"]["username"]);
        $objPHPExcel->getProperties()->setLastModifiedBy($_SESSION["user_info"]["username"]);
        //因为乱码,所以还是注销掉了!!!2014-7-10 22:27:20
        //$objPHPExcel->getProperties()->setTitle("巡检记录");
        //$objPHPExcel->getProperties()->setSubject("巡检记录");
        //$objPHPExcel->getProperties()->setDescription("巡检记录");
        // Add some data
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', '部门');
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', '专业');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', '巡检员');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', '巡检点位');
        $objPHPExcel->getActiveSheet()->SetCellValue('E1', '设备');
        $objPHPExcel->getActiveSheet()->SetCellValue('F1', '检查内容');
        $objPHPExcel->getActiveSheet()->SetCellValue('G1', '参考内容');
        $objPHPExcel->getActiveSheet()->SetCellValue('H1', '描述');
        $objPHPExcel->getActiveSheet()->SetCellValue('I1', '记录时间');
        // Set column widths
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(35);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(35);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(35);
        foreach ($record_list as $k => $v) {
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . ($k + 2), $v["dept_name"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . ($k + 2), $v["specialty_name"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . ($k + 2), $v["real_name"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . ($k + 2), $v["position_name"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . ($k + 2), $v["device_name"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . ($k + 2), $v["device_check_standard"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . ($k + 2), $v["device_reference_value"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('H' . ($k + 2), $v["device_describes_exception"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('I' . ($k + 2), formatTime($v['submit_time'], 'Y-m-d H:i', 1));
        }
        $objPHPExcel->getActiveSheet()->setTitle('recordList');
        // Save Excel 2007 file
        //$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
        //$objWriter->save(str_replace('.php', '.xls', __FILE__));
        //ob_end_clean(); //清除缓冲区,避免乱码
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate,post-check=0,pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header("Content-Disposition:attachment;filename=" . $file_name . ".xls");
        header("Content-Transfer-Encoding:binary");
        $objWriter->save("php://output");
    }
    
    /*
     * 导出巡检记录到excel
     */
    
//    public function exportExcel() {
//        //搜索的时间
//        $search_time = I("param.search_time", 0);
//        //指定专业
//        $specialty_id = I("param.specialty_id", 0);
//        //2014-7-15 10:59:49 改 增加搜索条件
//        $search_type = I("param.search_type", '');
//        $search_type_value = I("param.search_type_value", '');
//        //拼装搜索条件
//        $file_name = "";
//        $condition = array();
//        if ($search_time != 0) {
//            $condition["_string"] = "FROM_UNIXTIME(submit_time,'%Y-%m-%d')='$search_time'";
//            $file_name.=$search_time . "-";
//        }
//        if ($specialty_id != 0) {
//            $condition["specialty_id"] = $specialty_id;
//            $specialty_info = D("Specialty")->getById($specialty_id);
//            $file_name.=$specialty_info["name"] . "-";
//        }
//        if (!empty($search_type_value)) {
//            $condition[$search_type] = array('like', "%" . $search_type_value . "%");
//            if ($search_type == "real_name") {
//                $file_name.="巡检人员-" . $search_type_value . "-";
//            } else {
//                $file_name.="巡检点位-" . $search_type_value . "-";
//            }
//        }
//        if ($file_name == "") {
//            $file_name = "巡检记录";
//        } else {
//            $file_name.="巡检记录";
//        }
//        //查询所有的巡检记录
//        $result = D("Record")->getRecordListByCondition($condition, $search_time, $search_type, $search_type_value);
//        $record_list = $result["record_list"];
//        header("content-type:text/html;charset=utf-8");
//        /** Error reporting */
//        error_reporting(E_ALL);
//        /** PHPExcel */
//        import("@.Components.PHPExcel.PHPExcel", "", ".php");
//        /** PHPExcel_Writer_Excel2003用于创建xls文件 */
//        import("@.Components.PHPExcel.PHPExcel.Writer.Excel5", "", ".php");
//        // Create new PHPExcel object
//        $objPHPExcel = new PHPExcel();
//        // Set properties
//        $objPHPExcel->getProperties()->setCreator($_SESSION["user_info"]["username"]);
//        $objPHPExcel->getProperties()->setLastModifiedBy($_SESSION["user_info"]["username"]);
//        //因为乱码,所以还是注销掉了!!!2014-7-10 22:27:20
//        //$objPHPExcel->getProperties()->setTitle("巡检记录");
//        //$objPHPExcel->getProperties()->setSubject("巡检记录");
//        //$objPHPExcel->getProperties()->setDescription("巡检记录");
//        // Add some data
//        $objPHPExcel->setActiveSheetIndex(0);
//        $objPHPExcel->getActiveSheet()->SetCellValue('A1', '部门');
//        $objPHPExcel->getActiveSheet()->SetCellValue('B1', '专业');
//        $objPHPExcel->getActiveSheet()->SetCellValue('C1', '巡检员');
//        $objPHPExcel->getActiveSheet()->SetCellValue('D1', '巡检点位');
//        $objPHPExcel->getActiveSheet()->SetCellValue('E1', '设备');
//        $objPHPExcel->getActiveSheet()->SetCellValue('F1', '检查内容');
//        $objPHPExcel->getActiveSheet()->SetCellValue('G1', '参考内容');
//        $objPHPExcel->getActiveSheet()->SetCellValue('H1', '描述');
//        $objPHPExcel->getActiveSheet()->SetCellValue('I1', '记录时间');
//        // Set column widths
//        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
//        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
//        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
//        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
//        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
//        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(35);
//        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(35);
//        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(35);
//        foreach ($record_list as $k => $v) {
//            $objPHPExcel->getActiveSheet()->SetCellValue('A' . ($k + 2), $v["dept_name"]);
//            $objPHPExcel->getActiveSheet()->SetCellValue('B' . ($k + 2), $v["specialty_name"]);
//            $objPHPExcel->getActiveSheet()->SetCellValue('C' . ($k + 2), $v["real_name"]);
//            $objPHPExcel->getActiveSheet()->SetCellValue('D' . ($k + 2), $v["position_name"]);
//            $objPHPExcel->getActiveSheet()->SetCellValue('E' . ($k + 2), $v["device_name"]);
//            $objPHPExcel->getActiveSheet()->SetCellValue('F' . ($k + 2), $v["device_check_standard"]);
//            $objPHPExcel->getActiveSheet()->SetCellValue('G' . ($k + 2), $v["device_reference_value"]);
//            $objPHPExcel->getActiveSheet()->SetCellValue('H' . ($k + 2), $v["device_describes_exception"]);
//            $objPHPExcel->getActiveSheet()->SetCellValue('I' . ($k + 2), formatTime($v['submit_time'], 'Y-m-d H:i', 1));
//        }
//        $objPHPExcel->getActiveSheet()->setTitle('recordList');
//        // Save Excel 2007 file
//        //$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
//        $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
//        //$objWriter->save(str_replace('.php', '.xls', __FILE__));
//        //ob_end_clean(); //清除缓冲区,避免乱码
//        header("Pragma: public");
//        header("Expires: 0");
//        header("Cache-Control:must-revalidate,post-check=0,pre-check=0");
//        header("Content-Type:application/force-download");
//        header("Content-Type:application/vnd.ms-execl");
//        header("Content-Type:application/octet-stream");
//        header("Content-Type:application/download");
//        header("Content-Disposition:attachment;filename=" . $file_name . ".xls");
//        header("Content-Transfer-Encoding:binary");
//        $objWriter->save("php://output");
//    }

}
