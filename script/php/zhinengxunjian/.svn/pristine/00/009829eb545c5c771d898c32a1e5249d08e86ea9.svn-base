<?php

/**
 * @Description of CheckAction 巡检工作管理
 * @author Zhao-Hong-Yu <ZhaoHongYu, zhao_hong_yu@sina.cn> 
 * @copyright Copyright (C) 2014 赵洪禹 
 * @datetime 2014-6-27  16:14:42
 * @version 1.0
 */
class CheckAction extends CommonAction {

    /**
     * 跳转到巡检任列表
     */
    public function index() {
        $url = getUrl("Check/taskList");
        header("location:{$url}");
    }

    /**
     * 巡检任务设定
     */
    public function workset() {
        if (!empty($_GET['id'])) {
            $result = D("Position")->getPositionList();
            //给模板分配查询到的点位信息
            $this->assign("position_list", $result["position_list"]);
            $this->assign("page", $result["page"]);
            $this->assign("id", I("param.id", 0));
            $this->display("workset");
        } else {
            //去查询界面 该界面指定出 部门—专业—职位—姓名
            //查询所有启用状态下的部门
            $dept_list = D("Dept")->where(array("status" => 1))->select();
            //查询出所启用状态下的专业
            $specialty_list = D("Specialty")->where(array("status" => 1))->select();
            //查询所有启用状态下的职位
            $organization_list = D("Organization")->where(array("status" => 1))->select();
            //分页查询员工信息
            $result = D("User")->get_user_list();
            //给模板分配查询到的用户信息
            $this->assign("user_list", $result["user_list"]);
            $this->assign("organization_list", $organization_list);
            $this->assign("specialty_list", $specialty_list);
            $this->assign("dept_list", $dept_list);
            $this->assign("page", $result["page"]);
            $this->display("show");
        }
    }

    /**
     * 指定巡检点位
     * 2014-6-29 02:00:02
     */
    public function distributePosition() {
        $device_id = I('param.device_id', 0);
        $position_id = I('param.position_id', 0);
        $device_ids = array();
        $id = I('param.id', 0);
        if ($device_id != 0) {
            //指定指定的设备巡检
            $device_ids[] = $device_id;
        } else {
            //指定点位下的所有设备
            //查询该点位下的所有设备信息
            $result = D("Device")->get_device_list($position_id);
            //循环出device_id 组成数组
            foreach ($result['device_list'] as $k => $v) {
                $device_ids[] = $v['id'];
            }
        }
        //给模板分配设备id集合
        $this->assign("device_ids", implode(",", $device_ids));
        $this->assign("position_id", $position_id);
        $this->assign("id", I("param.id", 0));
        $this->display("timeset");
    }

    /**
     * 提交任务
     */
    public function complete() {
        $start_time = strtotime(I("param.start_time", 0));
        $end_time = strtotime(I("param.end_time", 0));
        if ($end_time - $start_time <= 0) {
            //提示用户选择时间不对
            $this->error(L("check_complete_time_incorrect"));
        }
        //要指定的用户id
        $id = I("param.id", 0);
        //要指定的点位id
        $position_id = I("param.position_id", 0);
        //要指定的设备id集合
        $device_ids = I("param.device_ids", '');
        //封装数据存入到线路巡检时间表 in_circuit
        $map['uid'] = $id;
        $map['start_time'] = $start_time;
        $map['end_time'] = $end_time;
        $map['device_ids'] = $device_ids;
        $map['position_id'] = $position_id;
        $map['status'] = 0; //初始指定任务的时候设为任务未完成
        $rst = M("Circuit")->add($map);
        if ($rst > 0) {
            //设定任务成功
            $this->success(L("check_complete_success"), getUrl("Check/workset"));
        } else {
            //设定任务失败
            $this->error(L("check_complete_failed"));
        }
    }

    /**
     * 按照点位编码,点位名字,点位位置来搜索点位
     * 2014-6-29 00:15:05
     */
    public function searchPosition() {
        $search_type = I("param.search_type", '');
        $search_type_value = I("param.search_type_value", '');
        //拼装搜索条件
        $condition = array();
        //使用模糊匹配 SELECT * FROM `in_position` WHERE ( `name` LIKE '%f7e%' ) 
        if (!empty($search_type_value)) {
            $condition[$search_type] = array('like', "%" . $search_type_value . "%");
        }
        if (!empty($condition[$search_type])) {
            $result = D("Position")->get_position_list($condition, $search_type, $search_type_value, I("param.id", 0));
        }
        //给模板分配查询到的点位信息
        $this->assign("position_list", $result["position_list"]);
        $this->assign("id", I("param.id", 0));
        $this->assign("search_type", $search_type);
        $this->assign("search_type_value", $search_type_value);
        $this->assign("page", $result["page"]);
        $this->display("workset");
    }

    /**
     * 根据条件搜索要设定任务的员工
     *  [dept_id] => 18
      [specialty_id] => 1
      [organization_id] => 70
      [real_name] => 赵洪禹
     */
    public function serach() {
        //接受用户提交的搜索条件
        $dept_id = I("param.dept_id", 0);
        $specialty_id = I("param.specialty_id", 0);
        $organization_id = I("param.organization_id", 0);
        $real_name = I("param.real_name", '');
        //拼装搜索条件
        $condition = array();
        if ($dept_id != 0) {
            $condition["user.dept_id"] = $dept_id;
        }
        if ($specialty_id != 0) {
            $condition["user.specialty_id"] = $specialty_id;
        }
        if ($organization_id != 0) {
            $condition["user.organization_id"] = $organization_id;
        }
        if (!empty($real_name)) {
            $condition["user.real_name"] = array('like', "%" . $real_name . "%");
        }
        $result = D("User")->get_user_list_by_condition($condition, $real_name);
        $user_list = $result["user_list"];
        //去查询界面 该界面指定出 部门—专业—职位—姓名
        //查询所有启用状态下的部门
        $dept_list = D("Dept")->where(array("status" => 1))->select();
        //查询出所启用状态下的专业
        $specialty_list = D("Specialty")->where(array("status" => 1))->select();
        //查询所有启用状态下的职位
        $organization_list = D("Organization")->where(array("status" => 1))->select();
        $this->assign("user_list", $user_list);
        $this->assign("organization_list", $organization_list);
        $this->assign("dept_list", $dept_list);
        $this->assign("specialty_list", $specialty_list);
        $this->assign("dept_id", $dept_id);
        $this->assign("specialty_id", $specialty_id);
        $this->assign("organization_id", $organization_id);
        $this->assign("real_name", $real_name);
        $this->assign("page", $result["page"]);
        $this->display("show");
    }

    /**
     * 巡检任务完成情况
     */
    public function workstatus() {
        //查询当天的巡检任务完成情况
        $result = D("Check")->getTaskStatusListByCondition();
        $task_status_list = $result["task_status_list"];
        foreach ($task_status_list as $k => $v) {
            $tmep = explode(",", $v['device_ids']);
            $str = "";
            foreach ($tmep as $kk => $vv) {
                $device_info = D("Device")->where(array("id" => $vv))->find();
                $str.=$device_info['name'] . ",";
            }
            $str = rtrim($str, ",");
            $task_status_list[$k]["device_names"] = $str;
            $task_status_list[$k]["device_names_short"] = formatStr($str, 14);
        }
        $this->assign("task_status_list", $task_status_list);
        $this->assign("page", $result["page"]);
        //去查询界面 该界面指定出 部门—专业
        //查询所有启用状态下的部门
        $dept_list = D("Dept")->where(array("status" => 1))->select();
        //查询出所启用状态下的专业
        $specialty_list = D("Specialty")->where(array("status" => 1))->select();
        //给模板分配查询到的用户信息
        $this->assign("specialty_list", $specialty_list);
        $this->assign("dept_list", $dept_list);
        $this->display("workstatus");
    }

    /**
     * 根据条件查询任务状况
     */
    public function searchTaskStatusList() {
        //搜索的时间
        $search_time = I("param.search_time", 0);
        //指定专业
        $specialty_id = I("param.specialty_id", 0);
        //指定部门
        $dept_id = I("param.dept_id", 0);
        //拼装搜索条件
        $condition = array();
        if ($search_time != 0) {
            $condition["_string"] = "FROM_UNIXTIME(circuit.start_time,'%Y-%m-%d')='$search_time'";
        }
        if ($specialty_id != 0) {
            $condition["specialty.id"] = $specialty_id;
        }
        if ($dept_id != 0) {
            $condition["dept.id"] = $dept_id;
        }
        //根据条件查询巡检任务完成情况
        $result = D("Check")->getTaskStatusListByCondition($condition, $search_time);
        $task_status_list = $result["task_status_list"];
        foreach ($task_status_list as $k => $v) {
            $tmep = explode(",", $v['device_ids']);
            $str = "";
            foreach ($tmep as $kk => $vv) {
                $device_info = D("Device")->where(array("id" => $vv))->find();
                $str.=$device_info['name'] . ",";
            }
            $str = rtrim($str, ",");
            $task_status_list[$k]["device_names"] = $str;
            $task_status_list[$k]["device_names_short"] = formatStr($str, 14);
        }
        $this->assign("task_status_list", $task_status_list);
        $this->assign("page", $result["page"]);
        //去查询界面 该界面指定出 部门—专业
        //查询所有启用状态下的部门
        $dept_list = D("Dept")->where(array("status" => 1))->select();
        //查询出所启用状态下的专业
        $specialty_list = D("Specialty")->where(array("status" => 1))->select();
        //给模板分配查询到的用户信息
        $this->assign("specialty_list", $specialty_list);
        $this->assign("dept_list", $dept_list);
        $this->assign("search_time", $search_time);
        $this->assign("specialty_id", $specialty_id);
        $this->assign("dept_id", $dept_id);
        $this->display("workstatus");
    }

    /**
     * 我的巡检任务
     */
    public function myTask() {
        $user_id = $_SESSION['user_info']["id"];
        //搜索的时间
        $search_time = I("param.search_time", 0);
        //拼装搜索条件
        $condition = array();
        if ($search_time != 0) {
            $condition["_string"] = "FROM_UNIXTIME(circuit.start_time,'%Y-%m-%d')='$search_time'";
        } else {
            //默认查询24小时任务
            //$search_time = formatTime(time(), "Y-m-d", 1); //获得当前时间2014-06-29
            //$condition["_string"] = "FROM_UNIXTIME(circuit.start_time,'%Y-%m-%d')='$search_time'";
        }
        if ($user_id != 0) {
            $condition["circuit.uid"] = $user_id;
        }
        //根据条件查询巡检任务完成情况
        $result = D("Check")->getTaskStatusListByCondition($condition, $search_time);
        $task_status_list = $result["task_status_list"];
        foreach ($task_status_list as $k => $v) {
            $tmep = explode(",", $v['device_ids']);
            $str = "";
            foreach ($tmep as $kk => $vv) {
                $device_info = D("Device")->where(array("id" => $vv))->find();
                $str.=$device_info['name'] . ",";
            }
            $str = rtrim($str, ",");
            $task_status_list[$k]["device_names"] = $str;
            $task_status_list[$k]["device_names_short"] = formatStr($str, 14);
        }
        $this->assign("task_status_list", $task_status_list);
        $this->assign("page", $result["page"]);
        //给模板分配查询到的用户信息
        $this->assign("search_time", $search_time);
        $this->display("mytask");
    }

    /*
     * 巡检任务设定,第二版
     * 
     */

    public function taskSet() {
        //获取当前页
        $page_now = I("param.page",1);
        if (!empty($_POST)) {
            $specialty_id = intval($_POST["specialty_id"]) ? intval($_POST["specialty_id"]) : 0;
            //根据专业id 查询专业名字
            $specialty_info = D("Specialty")->getById($specialty_id);
            $specialty_name = $specialty_info["name"];
            $task_model = new TaskModel();
            $form_data = $task_model->create();
            $position_ids = $_POST['position_ids'];
            if ($position_ids == "") {
                $this->error(L("check_model_position_ids_empty"));
            }
            $task_model->specialty_name = $specialty_name;
            $task_model->position_ids = implode(",", $position_ids);
            //循环获取点位下的设备信息
            $temp = array();
            foreach ($position_ids as $k => $v) {
                $device_list = D("Device")->where(array("position_id" => $v))->select();
                if (!empty($device_list)) {
                    //取出设备列表中的设备id 拼接成以逗号分隔
                    $temp2 = array();
                    foreach ($device_list as $kk => $vv) {
                        $device_id = $vv['id'];
                        $temp2[] = $device_id;
                    }
                    $device_ids = implode(",", $temp2);
                    $temp[] = $device_ids;
                }
            }
            //2,10,11,12,13,14,15,16,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,44,45,18,19,20,21,22,23,24,25
            $device_ids_array = implode(",", $temp);
            //将$device_ids_array 存入 in_task 表中的 device_ids 字段 代表 要检查的设备id 集合
            $task_model->device_ids = $device_ids_array;
            if ($form_data) {
                $rst = $task_model->add();
                if ($rst) {
                    $this->success(L("check_complete_success"), getUrl("Check/taskList",array("page"=>$page_now)));
                } else {
                    $this->error(L("check_complete_failed"));
                }
            } else {
                $this->error($task_model->getError());
            }
        } else {
            //查询所有专业
            $specialty_list = D("Specialty")->select();
            //将查询到的专业信息分配给模板
            $this->assign("specialty_list", $specialty_list);
            $this->assign("page_now", $page_now);
            $this->display("taskset");
        }
    }

    /**
     * 根据专业名称去点位表中查询点位信息
     */
    public function getPositionList() {
        $specialty_id = intval($_POST["specialty_id"]) ? intval($_POST["specialty_id"]) : 0;
        if ($this->isAjax()) {
            if ($specialty_id != 0) {
                //根据专业id 查询专业名字
                $specialty_info = D("Specialty")->getById($specialty_id);
                //根据专业名称去点位表中查询点位信息
                $specialty_name = formatStr($specialty_info["name"], 2, '');
                $condition["position"] = array('like', "%" . $specialty_name . "%");
                $position_list = D("Position")->where($condition)->select();
                if (!empty($position_list)) {
                    //查询到点位信息
                    $html = $this->_showPositionList($position_list);
                    $this->ajaxReturn($html, "返回的点位信息列表", 1);
                } else {
                    //没查询到点位信息
                    $this->ajaxReturn("", "根据该专业名称没有查询到点位信息!", 2);
                }
            }
        }
    }

    /**
     * 生成回显给用户选择的点位id html
     */
    public function _showPositionList($position_list) {
        $temp = "";
        foreach ($position_list as $k => $v) {
            $temp.="<tr>
                        <th>
                            <input class='' type='checkbox' name='position_ids[]' value='{$v['id']}'/>
                        </th>
                        <th>{$v['number']}</th>
                        <th>{$v['name']}</th>
                        
                    </tr>";
        }
        return $temp;
    }

    /*
     * 列出已经设定的任务 
     */

    public function taskList() {
        //获取当前页
        $page_now = I("param.page",1);
        $specialty_id = I("param.specialty_id", 0);
        $task_name = I("param.task_name", '');
        $condition = array();
        if ($specialty_id != 0) {
            $condition["specialty_id"] = $specialty_id;
        }
        if ($task_name != '') {
            $condition["task_name"] = array('like', "%" . $task_name . "%");
        }
        $result = D("Task")->getTaskListByCondition($condition, $task_name);
        $specialty_list = D("Specialty")->select();
        $this->assign("specialty_list", $specialty_list);
        $this->assign("task_list", $result["task_list"]);
        $this->assign("page", $result["page"]);
        $this->assign("task_name", $task_name);
        $this->assign("specialty_id", $specialty_id);
        $this->assign("page_now", $page_now);
        $this->display("tasklist");
    }

    /*
     * 更新任务
     * 2014-9-3 09:23:13
     */

    public function update() {
        //获取当前页
        $page_now = I("param.page",1);
        if (!empty($_POST)) {
            $specialty_id = intval($_POST["specialty_id"]) ? intval($_POST["specialty_id"]) : 0;
            //根据专业id 查询专业名字
            $specialty_info = D("Specialty")->getById($specialty_id);
            $specialty_name = $specialty_info["name"];
            $task_model = new TaskModel();
            $form_data = $task_model->create();
            $position_ids = $_POST['position_ids'];
            if ($position_ids == "") {
                $this->error(L("check_model_position_ids_empty"));
            }
            $task_model->specialty_name = $specialty_name;
            $task_model->position_ids = implode(",", $position_ids);
            //循环获取点位下的设备信息
            $temp = array();
            foreach ($position_ids as $k => $v) {
                $device_list = D("Device")->where(array("position_id" => $v))->select();
                if (!empty($device_list)) {
                    //取出设备列表中的设备id 拼接成以逗号分隔
                    $temp2 = array();
                    foreach ($device_list as $kk => $vv) {
                        $device_id = $vv['id'];
                        $temp2[] = $device_id;
                    }
                    $device_ids = implode(",", $temp2);
                    $temp[] = $device_ids;
                }
            }
            //2,10,11,12,13,14,15,16,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,44,45,18,19,20,21,22,23,24,25
            $device_ids_array = implode(",", $temp);
            //将$device_ids_array 存入 in_task 表中的 device_ids 字段 代表 要检查的设备id 集合
            $task_model->device_ids = $device_ids_array;
            if ($form_data) {
                $rst = $task_model->save();
                if ($rst) {
                    $this->success(L("check_update_success"), getUrl("Check/taskList",array("page"=>$page_now)));
                } else {
                    $this->success(L("check_update_failed"), getUrl("Check/taskList",array("page"=>$page_now)));
                }
            } else {
                $this->error($task_model->getError());
            }
        } else {
            //获得专业名称  根据专业名称获取专业id
            $specialty_name = I("param.specialty_name", 0);
            //查询所有专业
            $specialty_info = D("Specialty")->where(array("name"=>$specialty_name))->find();
            //将查询到的专业信息分配给模板
            $this->assign("specialty_info", $specialty_info);
            $task_id = I("param.id", 0);
            $task_info = D("Task")->getById($task_id);
            $this->assign("task_info", $task_info);
            $this->assign("page_now", $page_now);
            $specialty_id=$specialty_info["id"];
            $position_ids=$task_info['position_ids'];
            $position_ids_arr=  explode(",", $position_ids);
            if ($specialty_id != 0) {
                //根据专业id 查询专业名字
                $specialty_info = D("Specialty")->getById($specialty_id);
                //根据专业名称去点位表中查询点位信息
                $specialty_name = formatStr($specialty_info["name"], 2, '');
                $condition["position"] = array('like', "%" . $specialty_name . "%");
                $position_list = D("Position")->where($condition)->select();
                if (!empty($position_list)) {
                    //查询到点位信息
                    $html = $this->_showPositionList4update($position_list,$position_ids_arr);
                }
            }
            $this->assign("html", $html);
            $this->display("update");
        }
    }
    
    /**
     * 生成回显给用户选择的点位id html
     * 2014-9-1 23:51:14
     */
    private function _showPositionList4update($position_list,$position_ids_arr) {
        $temp = "";
        foreach ($position_list as $k => $v) {
            $checked="";
            if(in_array($v['id'], $position_ids_arr)){
                $checked="checked";
            }
            $temp.="<tr>
                        <th>
                            <input class='' type='checkbox' name='position_ids[]' value='{$v['id']}' {$checked}/>
                        </th>
                        <th>{$v['number']}</th>
                        <th>{$v['name']}</th>
                    </tr>";
        }
        return $temp;
    }

    /* 删除任务
     * 
     */

    public function del() {
        //获取当前页
        $page_now = I("param.page",1);
        $task_id = I("param.id", 0);
        $rst = D("Task")->where(array("id" => $task_id))->delete();
        if ($rst) {
            $this->success(L("check_del_success"), getUrl("Check/taskList",array("page"=>$page_now)));
        } else {
            $this->error(L("check_del_failed"));
        }
    }


    /*
     * 查看任务的领取以及完成情况 
     2014-7-28 20:19:30 旧版任务完成情况
     */

    /*public function taskstatus() {
        //搜索的时间
        $search_time = I("param.search_time", 0);
        $specialty_id = I("param.specialty_id", 0);
        $task_name = I("param.task_name", '');
        $status = I("param.status", '');
        $condition = array();
        if ($status == 0) {
            //未完成
            $condition["finish_task.status"] = $status;
        }
        if ($status == 1) {
            //已完成
            $condition["finish_task.status"] = $status;
        }
        if ($search_time != 0) {
            $condition["_string"] = "FROM_UNIXTIME(finish_task.receive_time,'%Y-%m-%d')='$search_time'";
        }
        if ($specialty_id != 0) {
            $condition["specialty.id"] = $specialty_id;
        }
        if ($task_name != '') {
            $condition["task.task_name"] = array('like', "%" . $task_name . "%");
        }
        $result = D("FinishTask")->getTaskStatusListByCondition($condition, $search_time, $task_name);
        //查询出所启用状态下的专业
        $specialty_list = D("Specialty")->where(array("status" => 1))->select();
        //给模板分配查询到的用户信息
        $this->assign("specialty_list", $specialty_list);
        $this->assign("task_status_list", $result["task_status_list"]);
        $this->assign("search_time", $search_time);
        $this->assign("specialty_id", $specialty_id);
        //根据专业id查询专业信息
        $specialty_info=D("Specialty")->getById($specialty_id);
        $this->assign("specialty_name", $specialty_info["name"]);
        $this->assign("task_name", $task_name);
        $this->assign("status", $status);
        $this->assign("page", $result["page"]);
        $this->display("taskstatus");
    }*/


    /*
     * 查看任务的领取以及完成情况 
     2014-7-28 20:19:39 新版任务完成情况
     */

    public function taskstatus() {
        if(!empty($_REQUEST["specialty_id"])){
            //搜索的时间
            $time=date("Y-m-d");
            $search_time = I("param.search_time", $time);
            $specialty_id = I("param.specialty_id", 0);
            $task_name = I("param.task_name", '');
            $status = I("param.status", 2);
            $condition = array();
            if ($status!=2&&$status!='') {
                $condition["finish_task.status"] = $status;
            }
            if ($search_time != 0) {
                $condition["_string"] = "FROM_UNIXTIME(finish_task.receive_time,'%Y-%m-%d')='$search_time'";
            }
            if ($specialty_id != 0) {
                $condition["specialty.id"] = $specialty_id;
            }
            if ($task_name != '') {
                $condition["task.task_name"] = array('like', "%" . $task_name . "%");
            }
            $result = D("FinishTask")->getTaskStatusListByCondition($condition, $search_time, $task_name);
            // show_msg($result);
            //查询出所启用状态下的专业
            $specialty_list = D("Specialty")->where(array("status" => 1))->select();
            $this->assign("specialty_list", $specialty_list);
            $this->assign("task_status_list", $result["task_status_list"]);
            $this->assign("search_time", $search_time);
            $this->assign("specialty_id", $specialty_id);
            //根据专业id查询专业信息
            $specialty_info=D("Specialty")->getById($specialty_id);
            $this->assign("specialty_name", $specialty_info["name"]);
            $this->assign("task_name", $task_name);
            if ($status!=2&&$status!='') {
                $this->assign("status", $status);
            }else{
                $this->assign("status", 2);
            }
            $this->assign("page", $result["page"]);
            //获取当前页
            $page_now = isset($_GET["page"]) ? $_GET["page"] : 1;
            $this->assign("page_now", $page_now);
            
            //计算出该专业指定时间已经领取的任务id
            //取出指定时间该专业领取的任务id
            $where["_string"] = "FROM_UNIXTIME(receive_time,'%Y-%m-%d')='$search_time'";
            if ($specialty_id != 0) {
                $where["specialty_id"] = $specialty_id;
            }
            $result3=D("FinishTask")->field("task_id")->where($where)->select();
            $receive_ids=array();//指定时间该专业领取的任务id集合
            foreach ($result3 as $k=>$v){
                $receive_ids[]=$v["task_id"];
            }
            //取出该专业下的所有任务
            if ($specialty_id != 0) {
                $condition2["specialty_id"] = $specialty_id;
            }
            //从缓存中读取,减少操作数据库次数
            $Cache = Cache::getInstance('File');
            $task_list = $Cache->get($specialty_id);  // 根据专业id获得该专业下的任务列表
            $receive_ids_2=$Cache->get($specialty_id."_receive_ids_2");//该专业下的所有任务的任务id集合
            if(empty($task_list)){
                $result2 = D("Task")->getTaskListBySpecialtyID($condition2);
                $task_list=$result2["task_list"];
                $Cache->set($specialty_id,$task_list);// 根据专业id进行缓存该专业下的任务列表
                $receive_ids_2=array();
                foreach ($task_list as $kk=>$vv){
                    $receive_ids_2[]=$vv["id"];
                }
                $Cache->set($specialty_id."_receive_ids_2",$receive_ids_2);
            }
            //计算出该专业指定时间已经领取的任务id
            //做差集
            $not_receive_ids=array_diff($receive_ids_2, $receive_ids);//指定时间段没领取的任务id集合
            $temp=array();//临时存储未领取的任务
            foreach ($task_list as $key => $value) {
                if(in_array($value["id"], $not_receive_ids)){
                    $temp[]=$value;
                }
            }
            $this->assign("task_list", $temp);//未领取的任务
            $this->assign("not_receive_ids", $not_receive_ids);
            $this->assign("received_ids", $receive_ids);
            
            $this->display("taskstatus");
        }else{

            // //搜索的时间
            // // $search_time = I("param.search_time", 0);
            // $time=date("Y-m-d");
            // $search_time = I("param.search_time", $time);
            // if ($search_time != 0) {
            //     $condition["_string"] = "FROM_UNIXTIME(finish_task.receive_time,'%Y-%m-%d')='$search_time'";
            // }
            // $result = D("FinishTask")->getTaskStatusListByCondition($condition, $search_time);
            // $this->assign("task_status_list", $result["task_status_list"]);
            // $this->assign("search_time", $search_time);
            // $this->assign("page", $result["page"]);
            // //获取当前页
            // $page_now = isset($_GET["page"]) ? $_GET["page"] : 1;
            // $this->assign("page_now", $page_now);
            // $this->assign("status", '2');

            //查询出所启用状态下的专业
            $specialty_list=$_SESSION["specialty_list"];
            if(empty($specialty_list)){
                $specialty_list = D("Specialty")->where(array("status" => 1))->select();
                $_SESSION["specialty_list"]=$specialty_list;
            }
            $this->assign("specialty_list", $specialty_list);
            $this->display("listspecialty");
        }
        
    }

    /**
     * 任务完成详情
     */
    public function finishTaskDetail() {
        //根据finish_task_id 查询任务完成详情
        $finish_task_id = I("param.finish_task_id", 0);
        $condition["finish_task.id"] = $finish_task_id;
        $finish_task_info = D("FinishTask")->getTaskStatusListByFinishTaskId($condition);
        $checked_device_ids_arr = explode(",", $finish_task_info["checked_device_ids"]);
        $position_ids_arr = explode(",", $finish_task_info["position_ids"]);
        //循环遍历每个点位下的设备信息
        $temp = array();
        $cut_length = "15"; //截取字符串的长度
        foreach ($position_ids_arr as $kk => $vv) {
            $device_list = D("Device")->where(array("position_id" => $vv))->select();
            $temp2 = array();
            $temp3 = array();
            foreach ($device_list as $k => $v) {
                $temp2[] = $v["id"]; //要检查的设备id集合
                if ($v["name"] != '') {
                    $temp3[] = $v["name"]; //要检查的设备名字集合
                }
            }
            //等于空说明此点位下的设备都已经检查过 做标记 已检查完
            $res = array_diff($temp2, $checked_device_ids_arr);
            $position_info = D("Position")->where(array("id" => $vv))->find();
            $to_check_device_names = implode(",", $temp3);
            $to_check_device_names_short = formatStr($to_check_device_names, $cut_length);
            if (empty($res)) {
                $checked_device_names = implode(",", $temp3);
                $checked_device_names_short = formatStr($checked_device_names, $cut_length);
                $temp[] = array(
                    "position_info" => $position_info,
                    "status" => 1,
                    "to_check_device_ids" => implode(",", $temp2),
                    "to_check_device_names" => $to_check_device_names,
                    "to_check_device_names_short" => $to_check_device_names_short,
                    "checked_device_ids" => implode(",", $temp2),
                    "checked_device_names" => $checked_device_names,
                    "checked_device_names_short" => $checked_device_names_short,
                );
            } else {
                //检查过的设备id集合
                $checked_device_ids = array_diff($temp2, $res);
                //根据检查过的设备id去device表查询device信息
                $temp4 = array(); //检查过的设备名字集合
                foreach ($checked_device_ids as $kkk => $vvv) {
                    $device_info = D("Device")->where(array("id" => $vvv))->find();
                    if ($device_info["name"] != '') {
                        $temp4[] = $device_info["name"]; //检查过的设备名字
                    }
                }
                $checked_device_names = implode(",", $temp4);
                $checked_device_names_short = formatStr($checked_device_names, $cut_length);
                //取出未检查的设备id集合字符串
                $not_check_device_ids = implode(",", $res);
                //根据未检查的设备id去device表查询device信息
                $temp5 = array(); //未检查过的设备名字集合
                foreach ($res as $kkkk => $vvvv) {
                    $device_info = D("Device")->where(array("id" => $vvvv))->find();
                    if ($device_info["name"] != '') {
                        $temp5[] = $device_info["name"]; //检查过的设备名字
                    }
                }
                $not_check_device_names = implode(",", $temp5);
                $not_check_device_names_short = formatStr($not_check_device_names, $cut_length);
                $temp[] = array(
                    "position_info" => $position_info,
                    "status" => 0,
                    "to_check_device_ids" => implode(",", $temp2),
                    "to_check_device_names" => $to_check_device_names,
                    "to_check_device_names_short" => $to_check_device_names_short,
                    "checked_device_ids" => implode(",", $checked_device_ids),
                    "checked_device_names" => $checked_device_names,
                    "checked_device_names_short" => $checked_device_names_short,
                    "not_check_device_ids" => $not_check_device_ids,
                    "not_check_device_names" => $not_check_device_names,
                    "not_check_device_names_short" => $not_check_device_names_short,
                );
            }
        }
        //获取当前页
        $page_now = isset($_GET["page_now"]) ? $_GET["page_now"] : 1;
        $this->assign("page_now", $page_now);
        //专业id  回显
        $specialty_id = I("param.specialty_id", 0);
        $this->assign("specialty_id", $specialty_id);
        $search_time = I("param.search_time",0);
        $task_name = I("param.task_name", '');
        $status = I("param.status", '');
        $this->assign("search_time", $search_time);
        $this->assign("task_name", $task_name);
        $this->assign("status", $status);
        $this->assign("finish_task_info", $finish_task_info);
        $this->assign("postion_finish_task_info_list", $temp);
        $this->display("taskfinishdetail");
    }
    
    /**
     * 任务完成详情
     */
    public function finishTaskDetail4liebiao() {
        //根据finish_task_id 查询任务完成详情
        $finish_task_id = I("param.finish_task_id", 0);
        $condition["finish_task.id"] = $finish_task_id;
        $finish_task_info = D("FinishTask")->getTaskStatusListByFinishTaskId($condition);
        $checked_device_ids_arr = explode(",", $finish_task_info["checked_device_ids"]);
        $position_ids_arr = explode(",", $finish_task_info["position_ids"]);
        //循环遍历每个点位下的设备信息
        $temp = array();
        $cut_length = "15"; //截取字符串的长度
        foreach ($position_ids_arr as $kk => $vv) {
            $device_list = D("Device")->where(array("position_id" => $vv))->select();
            $temp2 = array();
            $temp3 = array();
            foreach ($device_list as $k => $v) {
                $temp2[] = $v["id"]; //要检查的设备id集合
                if ($v["name"] != '') {
                    $temp3[] = $v["name"]; //要检查的设备名字集合
                }
            }
            //等于空说明此点位下的设备都已经检查过 做标记 已检查完
            $res = array_diff($temp2, $checked_device_ids_arr);
            $position_info = D("Position")->where(array("id" => $vv))->find();
            $to_check_device_names = implode(",", $temp3);
            $to_check_device_names_short = formatStr($to_check_device_names, $cut_length);
            if (empty($res)) {
                $checked_device_names = implode(",", $temp3);
                $checked_device_names_short = formatStr($checked_device_names, $cut_length);
                $temp[] = array(
                    "position_info" => $position_info,
                    "status" => 1,
                    "to_check_device_ids" => implode(",", $temp2),
                    "to_check_device_names" => $to_check_device_names,
                    "to_check_device_names_short" => $to_check_device_names_short,
                    "checked_device_ids" => implode(",", $temp2),
                    "checked_device_names" => $checked_device_names,
                    "checked_device_names_short" => $checked_device_names_short,
                );
            } else {
                //检查过的设备id集合
                $checked_device_ids = array_diff($temp2, $res);
                //根据检查过的设备id去device表查询device信息
                $temp4 = array(); //检查过的设备名字集合
                foreach ($checked_device_ids as $kkk => $vvv) {
                    $device_info = D("Device")->where(array("id" => $vvv))->find();
                    if ($device_info["name"] != '') {
                        $temp4[] = $device_info["name"]; //检查过的设备名字
                    }
                }
                $checked_device_names = implode(",", $temp4);
                $checked_device_names_short = formatStr($checked_device_names, $cut_length);
                //取出未检查的设备id集合字符串
                $not_check_device_ids = implode(",", $res);
                //根据未检查的设备id去device表查询device信息
                $temp5 = array(); //未检查过的设备名字集合
                foreach ($res as $kkkk => $vvvv) {
                    $device_info = D("Device")->where(array("id" => $vvvv))->find();
                    if ($device_info["name"] != '') {
                        $temp5[] = $device_info["name"]; //检查过的设备名字
                    }
                }
                $not_check_device_names = implode(",", $temp5);
                $not_check_device_names_short = formatStr($not_check_device_names, $cut_length);
                $temp[] = array(
                    "position_info" => $position_info,
                    "status" => 0,
                    "to_check_device_ids" => implode(",", $temp2),
                    "to_check_device_names" => $to_check_device_names,
                    "to_check_device_names_short" => $to_check_device_names_short,
                    "checked_device_ids" => implode(",", $checked_device_ids),
                    "checked_device_names" => $checked_device_names,
                    "checked_device_names_short" => $checked_device_names_short,
                    "not_check_device_ids" => $not_check_device_ids,
                    "not_check_device_names" => $not_check_device_names,
                    "not_check_device_names_short" => $not_check_device_names_short,
                );
            }
        }
        //获取当前页
        $page_now = isset($_GET["page_now"]) ? $_GET["page_now"] : 1;
        $this->assign("page_now", $page_now);
        //专业id  回显
        $specialty_id = I("param.specialty_id", 0);
        $this->assign("specialty_id", $specialty_id);
        $search_time = I("param.search_time",0);
        $task_name = I("param.task_name", '');
        $status = I("param.status", '');
        $this->assign("search_time", $search_time);
        $this->assign("task_name", $task_name);
        $this->assign("status", $status);
        $this->assign("finish_task_info", $finish_task_info);
        $this->assign("postion_finish_task_info_list", $temp);
        $this->display("taskfinishdetail4liebiao");
    }
    
    /*
     * 列表显示任务完成情况
     */
    public function taskstatusliebiao() {
        //搜索的时间
        $time=date("Y-m-d");
        $search_time = I("param.search_time", $time);
        $specialty_id = I("param.specialty_id", 0);
        $task_name = I("param.task_name", '');
        $status = I("param.status", 2);
        $condition = array();
        if ($status!=2&&$status!='') {
            $condition["finish_task.status"] = $status;
        }
        if ($search_time != 0) {
            $condition["_string"] = "FROM_UNIXTIME(finish_task.receive_time,'%Y-%m-%d')='$search_time'";
        }
        if ($specialty_id != 0) {
            $condition["specialty.id"] = $specialty_id;
        }
        if ($task_name != '') {
            $condition["task.task_name"] = array('like', "%" . $task_name . "%");
        }
        $result = D("FinishTask")->getTaskStatusListByCondition($condition, $search_time, $task_name);
        //查询出所启用状态下的专业
        $specialty_list = D("Specialty")->where(array("status" => 1))->select();
        $this->assign("specialty_list", $specialty_list);
        $this->assign("task_status_list", $result["task_status_list"]);
        $this->assign("search_time", $search_time);
        $this->assign("specialty_id", $specialty_id);
        //根据专业id查询专业信息
        $specialty_info=D("Specialty")->getById($specialty_id);
        $this->assign("specialty_name", $specialty_info["name"]);
        $this->assign("task_name", $task_name);
        if ($status!=2&&$status!='') {
            $this->assign("status", $status);
        }else{
            $this->assign("status", 2);
        }
        $this->assign("page", $result["page"]);
        //获取当前页
        $page_now = isset($_GET["page"]) ? $_GET["page"] : 1;
        $this->assign("page_now", $page_now);

        //计算出该专业指定时间已经领取的任务id
        //取出指定时间该专业领取的任务id
        $where["_string"] = "FROM_UNIXTIME(receive_time,'%Y-%m-%d')='$search_time'";
        if ($specialty_id != 0) {
            $where["specialty_id"] = $specialty_id;
        }
        $result3=D("FinishTask")->field("task_id")->where($where)->select();
        $receive_ids=array();//指定时间该专业领取的任务id集合
        foreach ($result3 as $k=>$v){
            $receive_ids[]=$v["task_id"];
        }
        //取出该专业下的所有任务
        if ($specialty_id != 0) {
            $condition2["specialty_id"] = $specialty_id;
        }
        //从缓存中读取,减少操作数据库次数
        $Cache = Cache::getInstance('File');
        $task_list = $Cache->get($specialty_id);  // 根据专业id获得该专业下的任务列表
        $receive_ids_2=$Cache->get($specialty_id."_receive_ids_2");//该专业下的所有任务的任务id集合
        if(empty($task_list)){
            $result2 = D("Task")->getTaskListBySpecialtyID($condition2);
            $task_list=$result2["task_list"];
            $Cache->set($specialty_id,$task_list);// 根据专业id进行缓存该专业下的任务列表
            $receive_ids_2=array();
            foreach ($task_list as $kk=>$vv){
                $receive_ids_2[]=$vv["id"];
            }
            $Cache->set($specialty_id."_receive_ids_2",$receive_ids_2);
        }
        //计算出该专业指定时间已经领取的任务id
        //做差集
        $not_receive_ids=array_diff($receive_ids_2, $receive_ids);//指定时间段没领取的任务id集合
        $temp=array();//临时存储未领取的任务
        foreach ($task_list as $key => $value) {
            if(in_array($value["id"], $not_receive_ids)){
                $temp[]=$value;
            }
        }
        $this->assign("task_list", $temp);//未领取的任务
        $this->assign("not_receive_ids", $not_receive_ids);
        $this->assign("received_ids", $receive_ids);

        $this->display("taskstatusliebiao");
    }
    
    /*
     * 导出到excel 不带分页  领取过的任务完成情况
     */
    public function exportExcel() {
        $user_start_time = I("param.start_time", ''); //查询起始时间
        $user_end_time = I("param.end_time", ''); //查询结束时间
        if($user_start_time==''&&$user_end_time==''){
            $page_now = I("param.page", 1);
            $specialty_id = I("param.specialty_id", 0);
            $today = date("Y-m-d");
            $search_time = I("param.search_time", $today);
            $task_name = I("param.task_name", '');
            $status = I("param.status", 2);
            //根据专业id查询专业信息
            $specialty_info=D("Specialty")->getById($specialty_id);
            $specialty_name=$specialty_info["name"];
            //回显数据
            $this->assign("page_now", $page_now);
            $this->assign("specialty_id", $specialty_id);
            $this->assign("search_time", $search_time);
            $this->assign("task_name", $task_name);
            $this->assign("status", $status);
            $this->assign("specialty_name", $specialty_name);
            $this->display("exportexcel");
        }else{
            $file_name = "";
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
            $condition = array();
            //搜索时间区间
            $condition['finish_task.receive_time'] = array('between', array($start_time, $end_time));
            $u_start_time=date("Y-m-d",$start_time);
            $u_end_time=date("Y-m-d",$end_time);
            $file_name.=$u_start_time."--".$u_end_time. " ";
            $specialty_id = I("param.specialty_id", 0);
            $task_name = I("param.task_name", '');
            $status = I("param.status", 2);
            if ($task_name != '') {
                $condition["task.task_name"] = array('like', "%" . $task_name . "%");
                $file_name.="任务名:".$task_name. "-";
            }
            if ($specialty_id != 0) {
                $condition["specialty.id"] = $specialty_id;
                //根据专业id查询专业信息
                $specialty_info=D("Specialty")->getById($specialty_id);
                $specialty_name=$specialty_info["name"];
                $file_name.=$specialty_name. "-";
            }
            if ($status!=2&&$status!='') {
                $condition["finish_task.status"] = $status;
                if($status==0){
                    $file_name.="未完成". "-";
                }else{
                    $file_name.="已完成". "-";
                }
            }else{
                $file_name.="全部". "-";
            }
            $file_name.="任务完成情况";
            $result = D("FinishTask")->getTaskStatusListByCondition4ExportExcel($condition);
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
            $objPHPExcel->getActiveSheet()->SetCellValue('A1', '专业');
            $objPHPExcel->getActiveSheet()->SetCellValue('B1', '任务名称');
            $objPHPExcel->getActiveSheet()->SetCellValue('C1', '点位名称');
            $objPHPExcel->getActiveSheet()->SetCellValue('D1', '巡检时间段');
            $objPHPExcel->getActiveSheet()->SetCellValue('E1', '任务领取时间');
            $objPHPExcel->getActiveSheet()->SetCellValue('F1', '完成时间');
            $objPHPExcel->getActiveSheet()->SetCellValue('G1', '领取人');
            $objPHPExcel->getActiveSheet()->SetCellValue('H1', '是否超时');
            $objPHPExcel->getActiveSheet()->SetCellValue('I1', '超时时间');
            $objPHPExcel->getActiveSheet()->SetCellValue('J1', '状态');
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
            //已领取任务
            foreach ($result["task_status_list"] as $k => $v) {
                $objPHPExcel->getActiveSheet()->SetCellValue('A' . ($k + 2), $v["specialty_name"]);
                $objPHPExcel->getActiveSheet()->SetCellValue('B' . ($k + 2), $v["task_name"]);
                $objPHPExcel->getActiveSheet()->SetCellValue('C' . ($k + 2), $v["position_names"]);
                $objPHPExcel->getActiveSheet()->SetCellValue('D' . ($k + 2), $v["start_time"].'--'.$v["end_time"]);
                $objPHPExcel->getActiveSheet()->SetCellValue('E' . ($k + 2), formatTime($v['receive_time'], 'Y-m-d H:i', 1));
                $objPHPExcel->getActiveSheet()->SetCellValue('F' . ($k + 2), formatTime($v['finish_time'], 'Y-m-d H:i', 1));
                $objPHPExcel->getActiveSheet()->SetCellValue('G' . ($k + 2), $v["real_name"]);
                $is_overtime=$v["is_overtime"];
                if(empty($is_overtime)){
                    $is_overtime_val="";
                }
                if(!empty($is_overtime)&&$is_overtime==0){
                    $is_overtime_val="超时完成";
                }
                if (!empty($is_overtime)&&$is_overtime==1) {
                    $is_overtime_val="未超时";
                }
                $objPHPExcel->getActiveSheet()->SetCellValue('H' . ($k + 2), $is_overtime_val);
                $objPHPExcel->getActiveSheet()->SetCellValue('I' . ($k + 2), $v["overtime"]);
                $status=$v["status"];
                if($status==0){
                    $status_val="未完成";
                }else {
                    if($is_overtime==0){
                        $status_val="超时完成";
                    }else{
                        $status_val="已完成";
                    }
                }
                $objPHPExcel->getActiveSheet()->SetCellValue('J' . ($k + 2), $status_val);
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
    }
    
    /*
     * 导出到excel 不带分页  领取过的任务完成情况
     */
//    public function exportExcel() {
//        $file_name = "";
//        //搜索的时间
//        $time=date("Y-m-d");
//        $search_time = I("param.search_time", $time);
//        $specialty_id = I("param.specialty_id", 0);
//        $task_name = I("param.task_name", '');
//        $status = I("param.status", 2);
//        $condition = array();
//        if ($search_time != 0) {
//            $condition["_string"] = "FROM_UNIXTIME(finish_task.receive_time,'%Y-%m-%d')='$search_time'";
//            $file_name.=$search_time. "-";
//        }
//        if ($task_name != '') {
//            $condition["task.task_name"] = array('like', "%" . $task_name . "%");
//            $file_name.="任务名:".$task_name. "-";
//        }
//        if ($specialty_id != 0) {
//            $condition["specialty.id"] = $specialty_id;
//            //根据专业id查询专业信息
//            $specialty_info=D("Specialty")->getById($specialty_id);
//            $specialty_name=$specialty_info["name"];
//            $file_name.=$specialty_name. "-";
//        }
//        if ($status!=2&&$status!='') {
//            $condition["finish_task.status"] = $status;
//            if($status==0){
//                $file_name.="未完成". "-";
//            }else{
//                $file_name.="已完成". "-";
//            }
//        }else{
//            $file_name.="全部". "-";
//        }
//        $file_name.="任务完成情况";
//        $result = D("FinishTask")->getTaskStatusListByCondition4ExportExcel($condition, $search_time, $task_name);
//        //计算出该专业指定时间已经领取的任务id
//        //取出指定时间该专业领取的任务id
//        $where["_string"] = "FROM_UNIXTIME(receive_time,'%Y-%m-%d')='$search_time'";
//        if ($specialty_id != 0) {
//            $where["specialty_id"] = $specialty_id;
//        }
//        $result3=D("FinishTask")->field("task_id")->where($where)->select();
//        $receive_ids=array();//指定时间该专业领取的任务id集合
//        foreach ($result3 as $k=>$v){
//            $receive_ids[]=$v["task_id"];
//        }
//        //取出该专业下的所有任务
//        if ($specialty_id != 0) {
//            $condition2["specialty_id"] = $specialty_id;
//        }
//        //从缓存中读取,减少操作数据库次数
//        $Cache = Cache::getInstance('File');
//        $task_list = $Cache->get($specialty_id);  // 根据专业id获得该专业下的任务列表
//        $receive_ids_2=$Cache->get($specialty_id."_receive_ids_2");//该专业下的所有任务的任务id集合
//        if(empty($task_list)){
//            $result2 = D("Task")->getTaskListBySpecialtyID($condition2);
//            $task_list=$result2["task_list"];
//            $Cache->set($specialty_id,$task_list);// 根据专业id进行缓存该专业下的任务列表
//            $receive_ids_2=array();
//            foreach ($task_list as $kk=>$vv){
//                $receive_ids_2[]=$vv["id"];
//            }
//            $Cache->set($specialty_id."_receive_ids_2",$receive_ids_2);
//        }
//        //计算出该专业指定时间已经领取的任务id
//        //做差集
//        $not_receive_ids=array_diff($receive_ids_2, $receive_ids);//指定时间段没领取的任务id集合
//        $temp=array();//临时存储未领取的任务
//        foreach ($task_list as $key => $value) {
//            if(in_array($value["id"], $not_receive_ids)){
//                $temp[]=$value;
//            }
//        }
//        
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
//        $objPHPExcel->getActiveSheet()->SetCellValue('A1', '专业');
//        $objPHPExcel->getActiveSheet()->SetCellValue('B1', '任务名称');
//        $objPHPExcel->getActiveSheet()->SetCellValue('C1', '点位名称');
//        $objPHPExcel->getActiveSheet()->SetCellValue('D1', '巡检时间段');
//        $objPHPExcel->getActiveSheet()->SetCellValue('E1', '任务领取时间');
//        $objPHPExcel->getActiveSheet()->SetCellValue('F1', '完成时间');
//        $objPHPExcel->getActiveSheet()->SetCellValue('G1', '领取人');
//        $objPHPExcel->getActiveSheet()->SetCellValue('H1', '是否超时');
//        $objPHPExcel->getActiveSheet()->SetCellValue('I1', '超时时间');
//        $objPHPExcel->getActiveSheet()->SetCellValue('J1', '状态');
//        // Set column widths
//        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
//        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
//        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
//        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
//        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
//        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(35);
//        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(35);
//        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(35);
//        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(35);
//        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(35);
//        //已领取任务
//        foreach ($result["task_status_list"] as $k => $v) {
//            $objPHPExcel->getActiveSheet()->SetCellValue('A' . ($k + 2), $v["specialty_name"]);
//            $objPHPExcel->getActiveSheet()->SetCellValue('B' . ($k + 2), $v["task_name"]);
//            $objPHPExcel->getActiveSheet()->SetCellValue('C' . ($k + 2), $v["position_names"]);
//            $objPHPExcel->getActiveSheet()->SetCellValue('D' . ($k + 2), $v["start_time"].'--'.$v["end_time"]);
//            $objPHPExcel->getActiveSheet()->SetCellValue('E' . ($k + 2), formatTime($v['receive_time'], 'Y-m-d H:i', 1));
//            $objPHPExcel->getActiveSheet()->SetCellValue('F' . ($k + 2), formatTime($v['finish_time'], 'Y-m-d H:i', 1));
//            $objPHPExcel->getActiveSheet()->SetCellValue('G' . ($k + 2), $v["real_name"]);
//            $is_overtime=$v["is_overtime"];
//            if(empty($is_overtime)){
//                $is_overtime_val="";
//            }
//            if(!empty($is_overtime)&&$is_overtime==0){
//                $is_overtime_val="超时完成";
//            }
//            if (!empty($is_overtime)&&$is_overtime==1) {
//                $is_overtime_val="未超时";
//            }
//            $objPHPExcel->getActiveSheet()->SetCellValue('H' . ($k + 2), $is_overtime_val);
//            $objPHPExcel->getActiveSheet()->SetCellValue('I' . ($k + 2), $v["overtime"]);
//            $status=$v["status"];
//            if($status==0){
//                $status_val="未完成";
//            }else {
//                if($is_overtime==0){
//                    $status_val="超时完成";
//                }else{
//                    $status_val="已完成";
//                }
//            }
//            $objPHPExcel->getActiveSheet()->SetCellValue('J' . ($k + 2), $status_val);
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
    
    /*
     * 导出到excel 带分页  未领取的任务
     */
    public function exportExcelNotReceive() {
        $file_name = "";
        //搜索的时间
        $time=date("Y-m-d");
        $search_time = I("param.search_time", $time);
        $specialty_id = I("param.specialty_id", 0);
        if ($search_time != 0) {
            $file_name.=$search_time. "-";
        }
        if ($specialty_id != 0) {
            //根据专业id查询专业信息
            $specialty_info=D("Specialty")->getById($specialty_id);
            $specialty_name=$specialty_info["name"];
            $file_name.=$specialty_name. "-";
        }
        $file_name.="未领取任务";
        //计算出该专业指定时间已经领取的任务id
        //取出指定时间该专业领取的任务id
        $where["_string"] = "FROM_UNIXTIME(receive_time,'%Y-%m-%d')='$search_time'";
        if ($specialty_id != 0) {
            $where["specialty_id"] = $specialty_id;
        }
        $result3=D("FinishTask")->field("task_id")->where($where)->select();
        $receive_ids=array();//指定时间该专业领取的任务id集合
        foreach ($result3 as $k=>$v){
            $receive_ids[]=$v["task_id"];
        }
        //取出该专业下的所有任务
        if ($specialty_id != 0) {
            $condition2["specialty_id"] = $specialty_id;
        }
        //从缓存中读取,减少操作数据库次数
        $Cache = Cache::getInstance('File');
        $task_list = $Cache->get($specialty_id);  // 根据专业id获得该专业下的任务列表
        $receive_ids_2=$Cache->get($specialty_id."_receive_ids_2");//该专业下的所有任务的任务id集合
        if(empty($task_list)){
            $result2 = D("Task")->getTaskListBySpecialtyID($condition2);
            $task_list=$result2["task_list"];
            $Cache->set($specialty_id,$task_list);// 根据专业id进行缓存该专业下的任务列表
            $receive_ids_2=array();
            foreach ($task_list as $kk=>$vv){
                $receive_ids_2[]=$vv["id"];
            }
            $Cache->set($specialty_id."_receive_ids_2",$receive_ids_2);
        }
        //计算出该专业指定时间已经领取的任务id
        //做差集
        $not_receive_ids=array_diff($receive_ids_2, $receive_ids);//指定时间段没领取的任务id集合
        $temp=array();//临时存储未领取的任务
        foreach ($task_list as $key => $value) {
            if(in_array($value["id"], $not_receive_ids)){
                $temp[]=$value;
            }
        }
        
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
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', '专业');
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', '任务名称');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', '点位名称');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', '巡检时间段');
        $objPHPExcel->getActiveSheet()->SetCellValue('E1', '状态');
        // Set column widths
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
        //未领取任务
        foreach ($temp as $k => $v) {
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . ($k + 2), $v["specialty_name"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . ($k + 2), $v["task_name"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . ($k + 2), $v["position_names"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . ($k + 2), $v["start_time"].'--'.$v["end_time"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . ($k + 2), "未领取");
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
     * 到处到excel 带分页
     */
    
//    public function exportExcel() {
//        $file_name = "";
//        //搜索的时间
//        $time=date("Y-m-d");
//        $search_time = I("param.search_time", $time);
//        $specialty_id = I("param.specialty_id", 0);
//        $task_name = I("param.task_name", '');
//        $status = I("param.status", 2);
//        $condition = array();
//        if ($search_time != 0) {
//            $condition["_string"] = "FROM_UNIXTIME(finish_task.receive_time,'%Y-%m-%d')='$search_time'";
//            $file_name.=$search_time. "-";
//        }
//        if ($task_name != '') {
//            $condition["task.task_name"] = array('like', "%" . $task_name . "%");
//            $file_name.="任务名:".$task_name. "-";
//        }
//        if ($specialty_id != 0) {
//            $condition["specialty.id"] = $specialty_id;
//            //根据专业id查询专业信息
//            $specialty_info=D("Specialty")->getById($specialty_id);
//            $specialty_name=$specialty_info["name"];
//            $file_name.=$specialty_name. "-";
//        }
//        if ($status!=2&&$status!='') {
//            $condition["finish_task.status"] = $status;
//            if($status==0){
//                $file_name.="未完成". "-";
//            }else{
//                $file_name.="已完成". "-";
//            }
//        }else{
//            $file_name.="全部". "-";
//        }
//        $file_name.="任务完成情况";
//        $result = D("FinishTask")->getTaskStatusListByCondition4ExportExcel($condition, $search_time, $task_name);
//        //计算出该专业指定时间已经领取的任务id
//        //取出指定时间该专业领取的任务id
//        $where["_string"] = "FROM_UNIXTIME(receive_time,'%Y-%m-%d')='$search_time'";
//        if ($specialty_id != 0) {
//            $where["specialty_id"] = $specialty_id;
//        }
//        $result3=D("FinishTask")->field("task_id")->where($where)->select();
//        $receive_ids=array();//指定时间该专业领取的任务id集合
//        foreach ($result3 as $k=>$v){
//            $receive_ids[]=$v["task_id"];
//        }
//        //取出该专业下的所有任务
//        if ($specialty_id != 0) {
//            $condition2["specialty_id"] = $specialty_id;
//        }
//        //从缓存中读取,减少操作数据库次数
//        $Cache = Cache::getInstance('File');
//        $task_list = $Cache->get($specialty_id);  // 根据专业id获得该专业下的任务列表
//        $receive_ids_2=$Cache->get($specialty_id."_receive_ids_2");//该专业下的所有任务的任务id集合
//        if(empty($task_list)){
//            $result2 = D("Task")->getTaskListBySpecialtyID($condition2);
//            $task_list=$result2["task_list"];
//            $Cache->set($specialty_id,$task_list);// 根据专业id进行缓存该专业下的任务列表
//            $receive_ids_2=array();
//            foreach ($task_list as $kk=>$vv){
//                $receive_ids_2[]=$vv["id"];
//            }
//            $Cache->set($specialty_id."_receive_ids_2",$receive_ids_2);
//        }
//        //计算出该专业指定时间已经领取的任务id
//        //做差集
//        $not_receive_ids=array_diff($receive_ids_2, $receive_ids);//指定时间段没领取的任务id集合
//        $temp=array();//临时存储未领取的任务
//        foreach ($task_list as $key => $value) {
//            if(in_array($value["id"], $not_receive_ids)){
//                $temp[]=$value;
//            }
//        }
//        
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
//        $objPHPExcel->getActiveSheet()->SetCellValue('A1', '专业');
//        $objPHPExcel->getActiveSheet()->SetCellValue('B1', '任务名称');
//        $objPHPExcel->getActiveSheet()->SetCellValue('C1', '点位名称');
//        $objPHPExcel->getActiveSheet()->SetCellValue('D1', '巡检时间段');
//        $objPHPExcel->getActiveSheet()->SetCellValue('E1', '任务领取时间');
//        $objPHPExcel->getActiveSheet()->SetCellValue('F1', '完成时间');
//        $objPHPExcel->getActiveSheet()->SetCellValue('G1', '领取人');
//        $objPHPExcel->getActiveSheet()->SetCellValue('H1', '是否超时');
//        $objPHPExcel->getActiveSheet()->SetCellValue('I1', '超时时间');
//        $objPHPExcel->getActiveSheet()->SetCellValue('J1', '状态');
//        // Set column widths
//        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
//        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
//        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
//        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
//        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
//        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(35);
//        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(35);
//        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(35);
//        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(35);
//        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(35);
//        //未领取任务
//        foreach ($temp as $k => $v) {
//            $objPHPExcel->getActiveSheet()->SetCellValue('A' . ($k + 2), $v["specialty_name"]);
//            $objPHPExcel->getActiveSheet()->SetCellValue('B' . ($k + 2), $v["task_name"]);
//            $objPHPExcel->getActiveSheet()->SetCellValue('C' . ($k + 2), $v["position_names"]);
//            $objPHPExcel->getActiveSheet()->SetCellValue('D' . ($k + 2), $v["start_time"].'--'.$v["end_time"]);
//            $objPHPExcel->getActiveSheet()->SetCellValue('E' . ($k + 2), formatTime($v['receive_time'], 'Y-m-d H:i', 1));
//            $objPHPExcel->getActiveSheet()->SetCellValue('F' . ($k + 2), formatTime($v['finish_time'], 'Y-m-d H:i', 1));
//            $objPHPExcel->getActiveSheet()->SetCellValue('G' . ($k + 2), $v["real_name"]);
//            $objPHPExcel->getActiveSheet()->SetCellValue('H' . ($k + 2), '');
//            $objPHPExcel->getActiveSheet()->SetCellValue('I' . ($k + 2), $v["overtime"]);
//            $objPHPExcel->getActiveSheet()->SetCellValue('J' . ($k + 2), "未领取");
//        }
//        //已领取任务
//        foreach ($result["task_status_list"] as $k => $v) {
//            $objPHPExcel->getActiveSheet()->SetCellValue('A' . ($k + 2), $v["specialty_name"]);
//            $objPHPExcel->getActiveSheet()->SetCellValue('B' . ($k + 2), $v["task_name"]);
//            $objPHPExcel->getActiveSheet()->SetCellValue('C' . ($k + 2), $v["position_names"]);
//            $objPHPExcel->getActiveSheet()->SetCellValue('D' . ($k + 2), $v["start_time"].'--'.$v["end_time"]);
//            $objPHPExcel->getActiveSheet()->SetCellValue('E' . ($k + 2), formatTime($v['receive_time'], 'Y-m-d H:i', 1));
//            $objPHPExcel->getActiveSheet()->SetCellValue('F' . ($k + 2), formatTime($v['finish_time'], 'Y-m-d H:i', 1));
//            $objPHPExcel->getActiveSheet()->SetCellValue('G' . ($k + 2), $v["real_name"]);
//            $is_overtime=$v["is_overtime"];
//            if(empty($is_overtime)){
//                $is_overtime_val="";
//            }
//            if(!empty($is_overtime)&&$is_overtime==0){
//                $is_overtime_val="超时完成";
//            }
//            if (!empty($is_overtime)&&$is_overtime==1) {
//                $is_overtime_val="未超时";
//            }
//            $objPHPExcel->getActiveSheet()->SetCellValue('H' . ($k + 2), $is_overtime_val);
//            $objPHPExcel->getActiveSheet()->SetCellValue('I' . ($k + 2), $v["overtime"]);
//            $status=$v["status"];
//            if($status==0){
//                $status_val="未完成";
//            }else {
//                if($is_overtime==0){
//                    $status_val="超时完成";
//                }else{
//                    $status_val="已完成";
//                }
//            }
//            $objPHPExcel->getActiveSheet()->SetCellValue('J' . ($k + 2), $status_val);
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
