<?php

/**
 * Description of WarningAction
 *
 * @author zhaohyg
 * 2014-7-2 21:35:06
 */
class WarningAction extends CommonAction {
    
    /*
     * 异步获取隐患信息记录数
     */
    public function countHiddentrouble() {
        echo $_SESSION['countHiddentrouble'];
    }
    /*
     * 异步获取异常信息记录数
     */
    public function countException() {
        echo $_SESSION['countException'];
    }
    /**
     * 隐患信息处理
     * 加载隐患信息,这里默认先加载所有隐患信息
     */
    public function hiddentrouble() {
        //搜索的时间
        $user_start_time = I("param.start_time", ''); //查询起始时间
        $user_end_time = I("param.end_time", ''); //查询结束时间
        $today = date("Y-m-d 00:00:00");
        if ($user_start_time != '') {
            //开始时间 
            $start_time = strtotime($user_start_time);
        } else {
            //没有开始时间查询7天内
            //2014-9-22 22:57:35
            $start_time =  strtotime("$today -7 day");
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
        //搜索时间区间
        $condition['submit_time'] = array('between', array($start_time, $end_time));
        //查询所有的巡检记录
        $u_start_time=date("Y-m-d",$start_time);
        $u_end_time=date("Y-m-d",$end_time);
        //指定专业
        $specialty_id = I("param.specialty_id", 0);
        $real_name = I("param.real_name", '');
        $status = I("param.status", 2);
        if ($real_name != '') {
            $condition["real_name"] = array('like', "%" . $real_name . "%");
        }
        if ($specialty_id != 0) {
            $condition["specialty_id"] = $specialty_id;
        }
        if ($status!=2&&$status!='') {
            $condition["status"] = $status;
        }
        $result = D("HiddenTrouble")->getHiddenTroubleListByCondition($condition, $u_start_time,$u_end_time,$status,$real_name);
        
        //2014-8-21 15:21:59
        //从session中取出隐患信息数量与新取出的进行比对如果不相等进行更新
        $old_countHiddentrouble=$_SESSION['countHiddentrouble'];
        //新取出的隐患信息记录
        $new_countHiddentrouble=  D("HiddenTrouble")->where(array("status"=>0))->count();
        if(empty($old_countHiddentrouble)||$old_countHiddentrouble!=$new_countHiddentrouble){
            //将隐患信息数量存储到session
            $_SESSION['countHiddentrouble']=$new_countHiddentrouble;
        }
        
        //去查询界面 该界面指定出 部门—专业—职位—姓名
        //查询出所启用状态下的专业
        $specialty_list = D("Specialty")->where(array("status" => 1))->select();
        //给模板分配查询到的信息
        $page_now = isset($_GET["page"]) ? $_GET["page"] : 1;
        $this->assign("page_now", $page_now);
        $this->assign("hidden_trouble_list", $result["hidden_trouble_list"]);
        $this->assign("specialty_list", $specialty_list);
        $this->assign("page", $result["page"]);
        
        //回传数据为了回显
        $this->assign("start_time", $u_start_time);
        $this->assign("end_time", $u_end_time);
        $this->assign("specialty_id", $specialty_id);
        $this->assign("real_name", $real_name);
        $this->assign("status", $status);
        
        $this->display("hiddentroublelist");
    }

    /**
     * 根据条件来检索隐患信息
     */
    public function searchHiddenTroubleListByCondition() {
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
        //搜索时间区间
        $condition['submit_time'] = array('between', array($start_time, $end_time));
        //查询所有的巡检记录
        $u_start_time=date("Y-m-d",$start_time);
        $u_end_time=date("Y-m-d",$end_time);
        
        //指定专业
        $specialty_id = I("param.specialty_id", 0);
        $real_name = I("param.real_name", '');
        $status = I("param.status", 2);
        if ($real_name != '') {
            $condition["real_name"] = array('like', "%" . $real_name . "%");
        }
        if ($specialty_id != 0) {
            $condition["specialty_id"] = $specialty_id;
        }
        if ($status!=2&&$status!='') {
            $condition["status"] = $status;
        }
        $result = D("HiddenTrouble")->getHiddenTroubleListByCondition($condition, $u_start_time,$u_end_time, $status, $real_name);
        //去查询界面
        //查询出所启用状态下的专业
        $specialty_list = D("Specialty")->where(array("status" => 1))->select();
        //给模板分配查询到的信息
        //获取当前页
        $page_now = isset($_GET["page"]) ? $_GET["page"] : 1;
        $this->assign("page_now", $page_now);
        $this->assign("hidden_trouble_list", $result["hidden_trouble_list"]);
        $this->assign("specialty_list", $specialty_list);
        $this->assign("page", $result["page"]);
        //回传数据为了回显
        $this->assign("start_time", $u_start_time);
        $this->assign("end_time", $u_end_time);
        $this->assign("specialty_id", $specialty_id);
        $this->assign("real_name", $real_name);
        $this->assign("status", $status);
        $this->display("hiddentroublelist");
    }

    /**
     * 对已经处理完毕的隐患信息查看详情
     * 2014-7-3 14:21:54
     */
    public function catHiddenTroubleInfo() {
        $hidden_trouble_info = D("HiddenTrouble")->getById(I("param.id", 0));
        $device_image_src_array = explode(",", $hidden_trouble_info['device_image_src']);
        if (!IS_SAE) {
            $this->assign("device_image_src_array", $device_image_src_array);
        } else {
            //    /Public/upload/images/2014-07-02/m_53b424df2947f.JPG
            //    [dirname] => /Public/upload/images/2014-07-02
            //    [basename] => m_53b424df2947f.JPG
            //    [extension] => JPG
            //    [filename] => m_53b424df2947f
            //兼容sae图片地址
            $findme = "images";
            $temp = C("TMPL_PARSE_STRING");
            $temp2 = array();
            foreach ($device_image_src_array as $k => $v) {
                $path = $v;
                $path_parts = pathinfo($path);
                $date_dir = substr($path, strpos($path, $findme) + 7, 10); //2014-07-02
                $image_path = $temp["./Public/upload/"] . "images/" . $date_dir . "/" . $path_parts["basename"];
                $temp2[] = $image_path;
            }
            $this->assign("device_image_src_array", $temp2);
        }
        $page_now = isset($_GET["page_now"]) ? $_GET["page_now"] : 1;
        $this->assign("page_now", $page_now);
        $this->assign("hidden_trouble_info", $hidden_trouble_info);
        
        $this->assign("start_time", I("param.start_time",''));
        $this->assign("end_time", I("param.end_time",''));
        $this->assign("specialty_id", I("param.specialty_id",0));
        $this->assign("real_name", I("param.real_name",''));
        $this->assign("status", I("param.status",2));
        
        $this->display("cathiddentrobuleinfo");
    }

    /**
     * 去处理隐患信息
     * 2014-7-3 14:23:02
     */
    public function processHiddenTroubleInfo() {
        $hidden_trouble_info = D("HiddenTrouble")->getById(I("param.id", 0));
        $hidden_trouble_id = I("param.id", 0);
        $device_image_src_array = explode(",", $hidden_trouble_info['device_image_src']);
        if (!IS_SAE) {
            $this->assign("device_image_src_array", $device_image_src_array);
        } else {
            //    /Public/upload/images/2014-07-02/m_53b424df2947f.JPG
            //    [dirname] => /Public/upload/images/2014-07-02
            //    [basename] => m_53b424df2947f.JPG
            //    [extension] => JPG
            //    [filename] => m_53b424df2947f
            //兼容sae图片地址
            $findme = "images";
            $temp = C("TMPL_PARSE_STRING");
            $temp2 = array();
            foreach ($device_image_src_array as $k => $v) {
                $path = $v;
                $path_parts = pathinfo($path);
                $date_dir = substr($path, strpos($path, $findme) + 7, 10); //2014-07-02
                $image_path = $temp["./Public/upload/"] . "images/" . $date_dir . "/" . $path_parts["basename"];
                $temp2[] = $image_path;
            }
            $this->assign("device_image_src_array", $temp2);
        }
        $page_now = isset($_GET["page_now"]) ? $_GET["page_now"] : 1;
        $this->assign("page_now", $page_now);
        $this->assign("hidden_trouble_info", $hidden_trouble_info);
        $this->assign("hidden_trouble_id", $hidden_trouble_id);
        
        $this->assign("start_time", I("param.start_time",''));
        $this->assign("end_time", I("param.end_time",''));
        $this->assign("specialty_id", I("param.specialty_id",0));
        $this->assign("real_name", I("param.real_name",''));
        $this->assign("status", I("param.status",2));
        
        $this->display("processhiddentrouble");
    }

    /**
     * 处理隐患信息
     * 2014-7-3 14:23:02
     */
    public function doProcessHiddenTroubleInfo() {
        $process_result = I("param.process_result", '');
        $hidden_trouble_id = I("param.hidden_trouble_id", 0);
        $hidden_trouble_status = I("param.hidden_trouble_status");
        if (!empty($process_result)) {
            if ($hidden_trouble_status == 1) {
                //说明已经处理过了,不用再重复处理
                $this->success(L("warning_doProcessHiddenTroubleInfo_processed"), getUrl("Warning/hiddentrouble",array("page"=>$page)));
            }
            //更新in_hidden_trouble 表中的status的状态为1  代表已处理
            //  `process_uid` '处理人员id',
            //  `process_real_name` varchar(32) '处理人员真实姓名',
            //  `process_time`  '处理人员处理的时间',
            //  `process_result` '处理人员的处理结果',
            // 更新in_device
            //待处理成功 in_device 表中status字段 将状态更新为正常,这个有待考究


            $map["id"] = $hidden_trouble_id;
            $map["status"] = 1;
            $map["process_uid"] = $_SESSION['user_info']["id"];
            $map["process_real_name"] = $_SESSION['user_info']["real_name"];
            $map["process_time"] = time();
            $map["process_result"] = $process_result;
            $rst = D("HiddenTrouble")->save($map);
            if ($rst == 1) {
                //处理成功
                $arr=I("param.");
                $this->success(L("warning_doProcessHiddenTroubleInfo_success"), getUrl("Warning/hiddentrouble",$arr));
            } else {
                //处理失败稍后再试
                $this->error(L("warning_doProcessHiddenTroubleInfo_failed"));
            }
        } else {
            //还没有填写处理意见
            $this->error(L("warning_doProcessHiddenTroubleInfo_not_input_process_result"));
        }
    }

    /**
     * 异常信息处理
     * 加载异常信息,这里默认先加载所有异常信息
     * 2014-7-3 23:14:35
     */
    public function exception() {
        //搜索的时间
        $user_start_time = I("param.start_time", ''); //查询起始时间
        $user_end_time = I("param.end_time", ''); //查询结束时间
        $today = date("Y-m-d 00:00:00");
        if ($user_start_time != '') {
            //开始时间 
            $start_time = strtotime($user_start_time);
        } else {
            //没有开始时间查询7天内
            //2014-9-22 22:57:35
            $start_time =  strtotime("$today -7 day");
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
        //搜索时间区间
        $condition['submit_time'] = array('between', array($start_time, $end_time));
        //查询所有的巡检记录
        $u_start_time=date("Y-m-d",$start_time);
        $u_end_time=date("Y-m-d",$end_time);
        //指定专业
        $specialty_id = I("param.specialty_id", 0);
        //拼装搜索条件
        $real_name = I("param.real_name", '');
        if ($real_name != '') {
            $condition["real_name"] = array('like', "%" . $real_name . "%");
        }
        if ($specialty_id != 0) {
            $condition["specialty_id"] = $specialty_id;
        }
        $status = I("param.status", 2);
        if ($status!=2&&$status!='') {
            $condition["device_status"] = $status;
        }
        
        //查询有的异常巡检记录 查询设备状态为0的记录 即 有异常的设备
        $result = D("ExceptionRecord")->getRecordListByCondition($condition, $u_start_time,$u_end_time,$real_name,$status);
        
        //2014-8-21 15:21:59
        //从session中取出异常信息数量与新取出的进行比对如果不相等进行更新
        $old_countException=$_SESSION['countException'];
        //新取出的异常信息记录
        $new_countException=  D("ExceptionRecord")->where(array("device_status"=>0))->count();
        if(empty($old_countException)||$old_countException!=$new_countException){
            //将异常信息数量存储到session
            $_SESSION['countException']=$new_countException;
        }
        
        //去查询界面
        //查询出所启用状态下的专业
        $specialty_list = D("Specialty")->where(array("status" => 1))->select();
        //给模板分配查询到的信息
        $page_now = isset($_GET["page"]) ? $_GET["page"] : 1;
        $this->assign("page_now", $page_now);
        $this->assign("record_list", $result["record_list"]);
        $this->assign("specialty_list", $specialty_list);
        $this->assign("page", $result["page"]);
        
        //回传数据为了回显
        $this->assign("start_time", $u_start_time);
        $this->assign("end_time", $u_end_time);
        $this->assign("specialty_id", $specialty_id);
        $this->assign("real_name", $real_name);
        $this->assign("status", $status);
        
        $this->display("exceptionrecordlist");
    }

    /**
     * 根据条件搜索异常信息记录
     * 2014-7-3 23:14:38
     */
    public function checkExceptionRecordByCondition() {
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
        //搜索时间区间
        $condition['submit_time'] = array('between', array($start_time, $end_time));
        //查询所有的巡检记录
        $u_start_time=date("Y-m-d",$start_time);
        $u_end_time=date("Y-m-d",$end_time);
        
        //指定专业
        $specialty_id = I("param.specialty_id", 0);
        //拼装搜索条件
        $real_name = I("param.real_name", '');
        if ($real_name != '') {
            $condition["real_name"] = array('like', "%" . $real_name . "%");
        }
        if ($specialty_id != 0) {
            $condition["specialty_id"] = $specialty_id;
        }
        $status = I("param.status", 2);
        if ($status!=2&&$status!='') {
            $condition["device_status"] = $status;
        }
        //查询有的异常巡检记录 查询设备状态为0的记录 即 有异常的设备
        $result = D("ExceptionRecord")->getRecordListByCondition($condition, $u_start_time,$u_end_time, $real_name,$status);
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
        $this->assign("real_name", $real_name);
        $this->assign("status", $status);
        $this->display("exceptionrecordlist");
    }

    /*
     * 导出隐患信息记录到excel
     */

    public function exportHiddenTroubleToExcel() {
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
        //搜索时间区间
        $condition['submit_time'] = array('between', array($start_time, $end_time));
        $u_start_time=date("Y-m-d",$start_time);
        $u_end_time=date("Y-m-d",$end_time);
        
        //指定专业
        $specialty_id = I("param.specialty_id", 0);
        $real_name = I("param.real_name", '');
        $status = I("param.status", 2);
        //拼装搜索条件
        $file_name = "";
        $file_name.=$u_start_time."--".$u_end_time. " ";
        if ($real_name != '') {
            $condition["real_name"] = array('like', "%" . $real_name . "%");
            $file_name.=$real_name . "-";
        }
        if ($specialty_id != 0) {
            $condition["specialty_id"] = $specialty_id;
            $specialty_info = D("Specialty")->getById($specialty_id);
            $file_name.=$specialty_info["name"] . "-";
        }
        if ($status == 0) {
            //未处理
            $file_name.="未处理" . "-";
        }
        if ($status == 1) {
            //已处理
            $file_name.="已处理" . "-";
        }
        if ($status == 2) {
            //全部
            $file_name.="全部" . "-";
        }
        if ($file_name == "") {
            $file_name = "隐患信息";
        } else {
            $file_name.="隐患信息";
        }
        if ($status!=2&&$status!='') {
            $condition["status"] = $status;
        }
        $result = D("HiddenTrouble")->getHiddenTroubleListByCondition4ExportExcel($condition);
        $hidden_trouble_list = $result["hidden_trouble_list"];
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
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', '巡检点位');
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', '设备名称');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', '故障描述');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', '上报人员');
        $objPHPExcel->getActiveSheet()->SetCellValue('E1', '上报时间');
        $objPHPExcel->getActiveSheet()->SetCellValue('F1', '处理状态');
        $objPHPExcel->getActiveSheet()->SetCellValue('G1', '处理人员');
        $objPHPExcel->getActiveSheet()->SetCellValue('H1', '处理时间');
        $objPHPExcel->getActiveSheet()->SetCellValue('I1', '处理结果');
        // Set column widths
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(35);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(35);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(35);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(35);
        foreach ($hidden_trouble_list as $k => $v) {
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . ($k + 2), $v["position_name"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . ($k + 2), $v["device_name"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . ($k + 2), $v["hidden_trouble_describes_exception"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . ($k + 2), $v["real_name"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . ($k + 2), formatTime($v["submit_time"], "Y-m-d H:i:s", 1));
            if ($v["status"] == 1) {
                $rst = "已处理";
            } else {
                $rst = "未处理";
            }
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . ($k + 2), $rst);
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . ($k + 2), $v["process_real_name"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('H' . ($k + 2), formatTime($v["process_time"], "Y-m-d H:i:s", 1));
            $objPHPExcel->getActiveSheet()->SetCellValue('I' . ($k + 2), $v["process_result"]);
        }
        $objPHPExcel->getActiveSheet()->setTitle('hiddenTroubleList');
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
     * 导出异常信息记录到excel
     */

    public function exportExceptionToExcel() {
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
        //搜索时间区间
        $condition['submit_time'] = array('between', array($start_time, $end_time));
        $u_start_time=date("Y-m-d",$start_time);
        $u_end_time=date("Y-m-d",$end_time);
        
        $file_name=$u_start_time."--".$u_end_time. " ";
        //指定专业
        $specialty_id = I("param.specialty_id", 0);
        //拼装搜索条件
        $real_name = I("param.real_name", '');
        if ($real_name != '') {
            $condition["real_name"] = array('like', "%" . $real_name . "%");
            $file_name.=$real_name . "-";
        }
        if ($specialty_id != 0) {
            $condition["specialty_id"] = $specialty_id;
            $specialty_info = D("Specialty")->getById($specialty_id);
            $file_name.=$specialty_info["name"] . "-";
        }
        $status = I("param.status", 2);
        if ($status!=2&&$status!='') {
            $condition["device_status"] = $status;
        }
        if ($status == 0) {
            //未处理
            $file_name.="未处理" . "-";
        }
        if ($status == 1) {
            //已处理
            $file_name.="已处理" . "-";
        }
        if ($status == 2) {
            //全部
            $file_name.="全部" . "-";
        }
        if ($file_name == "") {
            $file_name = "异常信息";
        } else {
            $file_name.="异常信息";
        }
        //查询有的异常巡检记录 查询设备状态为0的记录 即 有异常的设备
        $result = D("ExceptionRecord")->getRecordListByCondition4Export($condition);
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
        $objPHPExcel->getActiveSheet()->SetCellValue('I1', '处理人员');
        $objPHPExcel->getActiveSheet()->SetCellValue('J1', '处理结果');
        $objPHPExcel->getActiveSheet()->SetCellValue('K1', '处理时间');
        $objPHPExcel->getActiveSheet()->SetCellValue('L1', '处理状态');
        // Set column widths
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(35);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(35);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(35);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(35);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(35);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(35);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(35);
        foreach ($record_list as $k => $v) {
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . ($k + 2), $v["dept_name"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . ($k + 2), $v["specialty_name"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . ($k + 2), $v["real_name"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . ($k + 2), $v["position_name"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . ($k + 2), $v["device_name"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . ($k + 2), $v["device_check_standard"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . ($k + 2), $v["device_reference_value"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('H' . ($k + 2), $v["device_describes_exception"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('I' . ($k + 2), $v["process_real_name"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('J' . ($k + 2), $v["process_result"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('K' . ($k + 2), formatTime($v["process_time"], "Y-m-d H:i:s", 1));
            if ($v["device_status"] == 1) {
                $rst = "已处理";
            } else {
                $rst = "未处理";
            }
            $objPHPExcel->getActiveSheet()->SetCellValue('L' . ($k + 2), $rst);
        }
        $objPHPExcel->getActiveSheet()->setTitle('exceptionList');
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
     * 查看异常信息
     * 2014-8-22 00:17:13
     */
    public function catException() {
        $exception_info = D("ExceptionRecord")->getById(I("param.id", 0));
        $page_now = isset($_GET["page_now"]) ? $_GET["page_now"] : 1;
        $this->assign("page_now", $page_now);
        $this->assign("exception_info", $exception_info);
        
        $this->assign("start_time", I("param.start_time",''));
        $this->assign("end_time", I("param.end_time",''));
        $this->assign("specialty_id", I("param.specialty_id",0));
        $this->assign("real_name", I("param.real_name",''));
        $this->assign("status", I("param.status",2));
        
        $this->display("catexception");
    }
    /*
     * 处理异常信息
     * 2014-8-22 00:17:16
     */
    public function processException() {
        $exception_info = D("ExceptionRecord")->getById(I("param.id", 0));
        $exception_id = I("param.id", 0);
        $page_now = isset($_GET["page_now"]) ? $_GET["page_now"] : 1;
        $this->assign("page_now", $page_now);
        $this->assign("exception_info", $exception_info);
        $this->assign("exception_id", $exception_id);
        
        $this->assign("start_time", I("param.start_time",''));
        $this->assign("end_time", I("param.end_time",''));
        $this->assign("specialty_id", I("param.specialty_id",0));
        $this->assign("real_name", I("param.real_name",''));
        $this->assign("status", I("param.status",2));
        
        $this->display("processexception");
    }
    /**
     * 处理异常记录信息
     * 2014-7-3 14:23:02
     */
    public function doProcessException() {
        $process_result = I("param.process_result", '');
        $exception_id = I("param.exception_id", 0);
        $exception_status = I("param.exception_status");
        if (!empty($process_result)) {
            if ($exception_status == 1) {
                //说明已经处理过了,不用再重复处理
                $this->success(L("warning_doProcessHiddenTroubleInfo_processed"), getUrl("Warning/exception",array("page"=>$page)));
            }
            //更新in_exception_record 表中的device_status的状态为1  代表已处理
            //  `process_uid` '处理人员id',
            //  `process_real_name` varchar(32) '处理人员真实姓名',
            //  `process_time`  '处理人员处理的时间',
            //  `process_result` '处理人员的处理结果',
            // 更新in_device
            //待处理成功 in_device 表中status字段 将状态更新为正常,这个有待考究


            $map["id"] = $exception_id;
            $map["device_status"] = 1;
            $map["process_uid"] = $_SESSION['user_info']["id"];
            $map["process_real_name"] = $_SESSION['user_info']["real_name"];
            $map["process_time"] = time();
            $map["process_result"] = $process_result;
            $rst = D("ExceptionRecord")->save($map);
            if ($rst == 1) {
                //处理成功
                $arr=I("param.");
                $this->success(L("warning_doProcessHiddenTroubleInfo_success"), getUrl("Warning/exception",$arr));
            } else {
                //处理失败稍后再试
                $this->error(L("warning_doProcessHiddenTroubleInfo_failed"));
            }
        } else {
            //还没有填写处理意见
            $this->error(L("warning_doProcessHiddenTroubleInfo_not_input_process_result"));
        }
    }
}
