<?php

/**
 * @Description of DeviceAction
 * @author Zhao-Hong-Yu <ZhaoHongYu, zhao_hong_yu@sina.cn> 
 * @copyright Copyright (C) 2014 赵洪禹 
 * @datetime 2014-6-26  16:37:45
 * @version 1.0
 */
class DeviceAction extends Action {
    /*
     * 列出所有设备
     */

    public function index() {
        $position_id = isset($_GET['position_id']) ? intval($_GET['position_id']) : 0;
        if ($this->isAjax()) {
            if ($position_id) {
                //根据点位id去查询该点位下的所有设备信息
                $result = D("Device")->get_device_list($position_id);
                $position_info = D("Position")->getById($position_id);
                $position_number = $position_info["number"];
                if ($result["device_list"]) {
                    //查询到点位下的设备信息
                    //拼接成html格式输出
                    if (!empty($_GET['id'])) {
                        $html = $this->_showDeviceList4Workset($result, $position_number, $position_id, I("get.id", 0));
                    } else {
                        $page_now = isset($_GET["page_now"]) ? $_GET["page_now"]:1;
                        $html = $this->_showDeviceList($result, $position_number, $position_id,$page_now);
                    }
                    $this->ajaxReturn($html, "", 1);
                } else {
                    $html = "<p class='text-center text-danger'>编号 {$position_number} 点位暂无设备信息</p>";
                    //该点位下暂无设备信息
                    $this->ajaxReturn($html, "", 0);
                }
            }
        }
    }

    /*
     * 增加设备
     */

    public function add() {
        if (!empty($_POST)) {
            $device_model = D("Device");
            //点位id
            //$position_id= isset($_POST["position_id"]) ? intval($_POST["position_id"]) : 0;
            //收集表单数据
            $formdata = $device_model->create();
            if ($formdata) {
                $rst = $device_model->add();
                if ($rst > 0) {
                    //添加设备成功
                    //获取当前页 方便回显
                    $page = isset($_POST["page"]) ? $_POST["page"] : 1;
                    $this->success(L("device_add_success"), getUrl("Position/index", array("page" => $page)));
                } else {
                    //'添加设备失败,请稍后重试！'
                    $this->error(L("device_add_failed"));
                }
            } else {
                //表达数据填写不完整,提示对应信息
                $this->error($device_model->getError());
            }
        } else {
            //点位id
            $position_id = isset($_GET['position_id']) ? intval($_GET['position_id']) : 0;
            //点位编码
            $position_code = isset($_GET['position_code']) ? $_GET['position_code'] : 0;
            //获取当前页
            $page_now = isset($_GET["page_now"]) ? $_GET["page_now"] : 1;
            $this->assign("page_now", $page_now);
            $this->assign("position_id", $position_id);
            $this->assign("position_code", $position_code);
            $this->display();
        }
    }

    /*
     * 删除设备
     */

    public function del() {
        $device_id = isset($_GET['device_id']) ? intval($_GET['device_id']) : 0;
        $device_model = D("Device"); // 实例化Device对象
        //删除Device表中的用户记录
        $rst = $device_model->where(array("id" => $device_id))->delete();
        if ($rst > 0) {
            //删除设备成功！
            //获取当前页
            $page_now = isset($_GET["page_now"]) ? $_GET["page_now"] : 1;
            $this->success(L("device_del_success"), getUrl("Position/index", array("page" => $page_now)));
        } else {
            //删除设备失败,请稍后重试！
            $this->error(L("device_del_failed"));
        }
    }

    /*
     * 去更新更新设备
     */

    public function update() {
        if (!empty($_POST)) {
            $device_model = D("Device");
            //收集表单数据
            $formdata = $device_model->create();
            if ($formdata) {
                $rst = $device_model->save();
                //获取当前页 方便回显
                $page = isset($_POST["page"]) ? $_POST["page"] : 1;
                if ($rst > 0) {
                    //更新设备成功！
                    $this->success(L("device_update_success"), getUrl("Position/index", array("page" => $page)));
                } else {
                    //没有更改数据！
                    $this->success(L("device_update_not_change"), getUrl("Position/index", array("page" => $page)));
                }
            } else {
                //表单填写信息项不完整  提示对应信息
                $this->error($device_model->getError());
            }
        } else {
            //去更新页面
            $device_id = isset($_GET['device_id']) ? intval($_GET['device_id']) : 0;
            $device_info = D("Device")->where(array("id" => $device_id))->find();
            $this->assign("device_info", $device_info);
            //获取当前页
            $page_now = isset($_GET["page_now"]) ? $_GET["page_now"] : 1;
            $this->assign("page_now", $page_now);
            $this->display();
        }
    }

    /*
     * 查看设备详情
     */

    public function detail() {
        $device_id = isset($_GET['device_id']) ? intval($_GET['device_id']) : 0;
        $device_info = D("Device")->where(array("id" => $device_id))->find();
        $this->assign("device_info", $device_info);
        //获取当前页
        $page_now = isset($_GET["page_now"]) ? $_GET["page_now"] : 1;
        $this->assign("page_now", $page_now);
        $this->display();
    }

    /**
     * 用于拼装html字符串返回给客户端
     * @param type $result 点位下的设备信息结果集
     * @param type $position_id 点位ID
     */
    private function _showDeviceList($result, $position_number, $position_id,$page_now) {
        $html_1 = <<<DEVICELIST_1
            <p class="text-center text-warning">编号 {$position_number} 点位下的设备信息为</p>
            <table class="table table-condensed table-hover table-bordered">
                <thead>
                  <tr>
                    <th>设备编码</th>
                    <th>设备名称</th>
                    <th>参考值</th>
                    <th>巡检标准</th>
                    <th>设备状态</th>
                    <th>是否需要输入值</th>
                    <th>输入数值</th>
                    <th>异常描述</th>
                    
                    
                    <th>设备备注</th>
                    <th>操作</th>
                  </tr>
                </thead>
                <tbody>
DEVICELIST_1;
        $temp = '';
        foreach ($result['device_list'] as $k => $v) {
            $url_update = getUrl('Device/update', array("device_id" => $v['id'],"page_now" => $page_now));
            $url_del = getUrl('Device/del', array("device_id" => $v['id'],"page_now" => $page_now));
            $url_detail = getUrl('Device/detail', array("device_id" => $v['id'],"page_now" => $page_now));
            $url = "<div class='btn-group-xs'><a class='btn btn-success' href='{$url_update}'>编辑</a>&nbsp;<a class='btn btn-danger' href='{$url_del}'>删除</a>&nbsp;<a class='btn btn-primary' href='{$url_detail}'>详情</a></div>";
            if ($v['need_input_value'] == 1) {
                $need_input_value = "需要";
            } else {
                $need_input_value = "不需要";
            }
            //截取巡检标准 不要显示过多
            $check_standard = formatStr($v['check_standard'], 8);
            //异常描述
            $describes_exception = formatStr($v['describes_exception'], 8);
            $reference_value = formatStr($v['reference_value'], 8);
            $device_name = formatStr($v['name'], 8);
            $remark = formatStr($v['remark'], 8);
            $onclick2="onclick='show_detail(this)'";
            if ($v['status'] == 1) {
                $status = "正常";
                $temp.="<tr class='info'>
                    <th>{$v['code']}</th>
                    <th><abbr title='{$v['name']}' {$onclick2}>{$device_name}</abbr></th>
                    <th><abbr title='{$v['reference_value']}' {$onclick2}>{$reference_value}</abbr></th>
                    <th><abbr title='{$v['check_standard']}' {$onclick2}>{$check_standard}</abbr></th>
                    <th>{$status}</th>
                    <th>{$need_input_value}</th>
                    <th>{$v['input_value']}</th>
                    <th><abbr title='{$v['describes_exception']}' {$onclick2}>{$describes_exception}</abbr></th>
                    <th><abbr title='{$v['remark']}' {$onclick2}>{$remark}</abbr></th>
                    <th>{$url}</th>
                  </tr>";
            } else {
                $status = "异常";
                $temp.="<tr class='danger'>
                    <th>{$v['code']}</th>
                    <th><abbr title='{$v['name']}' {$onclick2}>{$device_name}</abbr></th>
                    <th><abbr title='{$v['reference_value']}' {$onclick2}>{$reference_value}</abbr></th>
                    <th><abbr title='{$v['check_standard']}' {$onclick2}>{$check_standard}</abbr></th>
                    <th>{$status}</th>
                    <th>{$need_input_value}</th>
                    <th>{$v['input_value']}</th>
                    <th><abbr title='{$v['describes_exception']}' {$onclick2}>{$describes_exception}</abbr></th>
                    <th><abbr title='{$v['remark']}' {$onclick2}>{$remark}</abbr></th>
                    <th>{$url}</th>
                  </tr>";
            }
        }
        $html_2 = <<<DEVICELIST_2
                </tbody>
            </table>
DEVICELIST_2;
        return $html_1 . $temp . $html_2;
    }

    /**
     * 用于拼装html字符串返回给客户端
     * @param type $result 点位下的设备信息结果集
     * @param type $position_id 点位ID
     */
    private function _showDeviceList4Workset($result, $position_number, $position_id, $id) {
        $html_1 = <<<DEVICELIST_1
            <p class="text-center text-warning">编号 {$position_number} 点位下的设备信息为</p>
            <table class="table table-condensed table-hover table-bordered">
                <thead>
                  <tr>
                    <th>设备编码</th>
                    <th>设备名称</th>
                    <th>参考值</th>
                    <th>巡检标准</th>
                    <th>设备状态</th>
                    <th>是否需要输入值</th>
                    <th>输入数值</th>
                    <th>异常描述</th>
                    <th>巡检频率</th>
                    <th>巡检次数</th>
                    <th>设备备注</th>
                    <th>操作</th>
                  </tr>
                </thead>
                <tbody>
DEVICELIST_1;
        $temp = '';
        foreach ($result['device_list'] as $k => $v) {
            $url_distributePosition = getUrl('Check/distributePosition', array("position_id" => $position_id, "id" => $id, "device_id" => $v['id'], "random" => mt_rand()));
            $url = "<div class='btn-group-xs'><a class='btn btn-primary' href='{$url_distributePosition}'>指定此设备</a></div>";
            if ($v['need_input_value'] == 1) {
                $need_input_value = "需要";
            } else {
                $need_input_value = "不需要";
            }
            //截取巡检标准 不要显示过多
            $check_standard = formatStr($v['check_standard'], 8);
            //异常描述
            $describes_exception = formatStr($v['describes_exception'], 8);
            if ($v['status'] == 1) {
                $status = "正常";
                $temp.="<tr class='info'>
                    <th>{$v['code']}</th>
                    <th>{$v['name']}</th>
                    <th>{$v['reference_value']}</th>
                    <th><abbr title='{$v['check_standard']}'>{$check_standard}</abbr></th>
                    <th>{$status}</th>
                    <th>{$need_input_value}</th>
                    <th>{$v['input_value']}</th>
                    <th><abbr title='{$v['describes_exception']}'>{$describes_exception}</abbr></th>
                    <th>{$v['frequency']}</th>
                    <th>{$v['times']}</th>
                    <th>{$v['remark']}</th>
                    <th>{$url}</th>
                  </tr>";
            } else {
                $status = "异常";
                $temp.="<tr class='danger'>
                    <th>{$v['code']}</th>
                    <th>{$v['name']}</th>
                    <th>{$v['reference_value']}</th>
                    <th><abbr title='{$v['check_standard']}'>{$check_standard}</abbr></th>
                    <th>{$status}</th>
                    <th>{$need_input_value}</th>
                    <th>{$v['input_value']}</th>
                    <th><abbr title='{$v['describes_exception']}'>{$describes_exception}</abbr></th>
                    <th>{$v['frequency']}</th>
                    <th>{$v['times']}</th>
                    <th>{$v['remark']}</th>
                    <th>{$url}</th>
                  </tr>";
            }
        }
        $html_2 = <<<DEVICELIST_2
                </tbody>
            </table>
DEVICELIST_2;
        return $html_1 . $temp . $html_2;
    }

}
