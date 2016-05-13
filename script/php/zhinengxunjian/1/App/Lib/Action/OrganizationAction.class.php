<?php

/**
 * @Description of OrganizationAction  组织机构管理
 * @author Zhao-Hong-Yu <ZhaoHongYu, zhao_hong_yu@sina.cn>
* @copyright Copyright (C) 2014 赵洪禹 
 * @datetime 2014年6月17日 20:46:56
 * @version 1.0
 */
class OrganizationAction extends CommonAction {
    /*
     * 列出所有职位
     */

    public function index() {
        $result = D("Organization")->get_organization_list();
        $this->assign("job_list", $result["job_list"]);
        $this->assign("page", $result["page"]);
        $this->display("show");
    }

    /*
     * 增加职位
     */

    public function add() {
        if (!empty($_POST)) {
            $dept_id = isset($_POST["dept_id"]) ? intval($_POST["dept_id"]) : 0;
            if ($dept_id == 0) {
                //'您还没有选择该职位的所属部门!'
                $this->error(L("organization_add_not_select_dept_id"));
            }
            $organization_model = D("Organization");
            // 上级职位 如果为空则为顶级职位 即pid为 0 level为1
            $pid = isset($_POST["pid"]) ? intval($_POST["pid"]) : 0;
            //计算所选职位等级
            if ($pid == 0) {
                //顶级职位
                $level = 1;
            } else {
                // 根据所选择的上级职位id 查询出上级职位等级,在此之上level+1
                $organization_info_level = $organization_model->field(array("level"))->where(array("id" => $pid))->find();
                $level = $organization_info_level["level"] + 1;
            }
            //收集表单数据
            $formdata = $organization_model->create();
            $organization_model->pid = $pid;
            $organization_model->level = $level;
            if ($formdata) {
                $rst = $organization_model->add();
                if ($rst > 0) {
                    //添加职位成功
                    $this->success(L("organization_add_success"), getUrl("Organization/index"));
                } else {
                    //'添加职位失败,请稍后重试！'
                    $this->error(L("organization_add_failed"));
                }
            } else {
                //表达数据填写不完整,提示对应信息
                $this->error($organization_model->getError());
            }
        } else {
            $job_list = M("Organization")->where(array("status" => 1))->select();
            // 查询所有部门
            $dept_list = M("Dept")->where(array("status" => 1))->select();
            $this->assign("job_list", $job_list);
            $this->assign("dept_list", $dept_list);
            $this->display();
        }
    }

    /*
     * 删除职位
     */

    public function del() {
        $organization_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $organization_model = D("Organization"); // 实例化Organization对象
        //删除Organization表中的用户记录  是不是要清空user表中对应的记录
        $rst = $organization_model->where(array("id" => $organization_id))->delete();
        if ($rst > 0) {
            // 清空用户organization_id为$organization_id 的 用户的organization_id
            $data = array('organization_id' => 0);
            D("User")->where(array('organization_id' => $organization_id))->save($data);
            //删除职位成功！
            $this->success(L("organization_del_success"), getUrl("Organization/index"));
        } else {
            //删除职位失败,请稍后重试！
            $this->error(L("organization_del_failed"));
        }
    }

    /*
     * 去更新更新职位
     */

    public function update() {
        if (!empty($_POST)) {
            $organization_model = D("Organization");
            //收集表单数据
            $formdata = $organization_model->create();
            if ($formdata) {
                $rst = $organization_model->save();
                if ($rst > 0) {
                    //更新职位成功！
                    $this->success(L("organization_update_success"), getUrl("Organization/index"));
                } else {
                    //没有更改数据！
                    $this->success(L("organization_update_not_change"), getUrl("Organization/index"));
                }
            } else {
                //表单填写信息项不完整  提示对应信息
                $this->error($organization_model->getError());
            }
        } else {
            //去更新页面
            $id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
            $organization_info = D("Organization")->where(array("id" => $id))->find();
            $organization_list = D("Organization")->select();
            //部门列表
            $dept_list = D("Dept")->select();
            $this->assign("dept_list", $dept_list);
            //查询部门信息
            $dept_info = D("Dept")->where(array("id" => $organization_info['dept_id']))->find();
            $this->assign("organization_info", $organization_info);
            $this->assign("organization_list", $organization_list);
            //show_msg($organization_list);
            $this->assign("dept_info", $dept_info);
            $this->assign("parent_level", (($organization_info["level"])-1));//该职位的上级职位级别
            $this->display();
        }
    }

    /*
        生成组织结构图

    */
    public function jiegoutu(){
        $organization_list=D("Organization")->select();
        //获得根节点
        $root_organization=D("Organization")->where(array("level"=>1))->find();
        //获得level=2
        $organization_level2_list=D("Organization")->where(array("level"=>2))->select();
        //获得level=3
        $organization_level3_list=D("Organization")->where(array("level"=>3))->select();
        //获得level=4
        $organization_level4_list=D("Organization")->where(array("level"=>4))->select();
        //获得level=5
        $organization_level5_list=D("Organization")->where(array("level"=>5))->select();
        //获得level=6
        $organization_level6_list=D("Organization")->where(array("level"=>6))->select();
        //获得level=7
        $organization_level7_list=D("Organization")->where(array("level"=>7))->select();
        //获得level=8
        $organization_level8_list=D("Organization")->where(array("level"=>8))->select();
        //获得level=9
        $organization_level9_list=D("Organization")->where(array("level"=>9))->select();
        //获得level=10
        $organization_level10_list=D("Organization")->where(array("level"=>10))->select();
        $this->assign("organization_list", $organization_list);
        $this->assign("root_organization", $root_organization);
        $this->assign("organization_level2_list", $organization_level2_list);
        $this->assign("organization_level3_list", $organization_level3_list);
        $this->assign("organization_level4_list", $organization_level4_list);
        $this->assign("organization_level5_list", $organization_level5_list);
        $this->assign("organization_level6_list", $organization_level6_list);
        $this->assign("organization_level7_list", $organization_level7_list);
        $this->assign("organization_level8_list", $organization_level8_list);
        $this->assign("organization_level9_list", $organization_level9_list);
        $this->assign("organization_level10_list", $organization_level10_list);
        $this->display();
    }
    /*
     * 根据职位id查询该职位下的员工信息
     * 2014年7月27日 15:31:43
     */
    public function getUserByOid() {
        $oid=I("param.oid",0);
        if ($this->isAjax()) {
            if ($oid) {
                //查询职位信息
                $organization_info = D("Organization")->where(array("id"=>$oid))->find();
                //根据职位id去查询该职位下的所有员工信息
                //搜索条件
                $condition["user.organization_id"] = $oid;
                $user_list = D("User")->get_user_list_by_organization_id($condition);
                if($user_list){
                    $html = $this->_showUserList($user_list, $organization_info["name"]);
                    $this->ajaxReturn($html, "", 1);
                }else{
                    $html = "<p class='text-center text-danger'>职位 {$organization_info["name"]} 下暂无员工信息</p><br/>";
                    //该职位暂无员工信息
                    $this->ajaxReturn($html, "", 0);
                }
            }
        }
    }
    /**
     * 用于拼装html字符串返回给客户端
     * @param type $user_list 职位下的员工信息
     * @param type $organization_name 职位名称
     */
    private function _showUserList($user_list, $organization_name) {
        $html_1 = <<<USERLIST_1
            <p class="text-center text-primary">职位 {$organization_name} 下的员工信息为</p><br/>
            <table class="table table-condensed table-hover table-bordered">
                <thead>
                  <tr>
                    <th>姓名</th>
                    <th>工号</th>
                    <th>专业</th>
                    <th>电话</th>
                    <th>操作时间</th>
                  </tr>
                </thead>
                <tbody>
USERLIST_1;
        $temp = '';
        foreach ($user_list as $k => $v) {
            $update_time=formatTime($v['update_time'],'',2);
            $temp.="<tr>
                    <th>{$v['real_name']}</th>
                    <th>{$v['job_number']}</th>
                    <th>{$v['specialty_name']}</th>
                    <th>{$v['mobile']}</th>
                    <th>{$update_time}</th>
                  </tr>";
        }
        $html_2 = <<<USERLIST_2
                </tbody>
            </table>
USERLIST_2;
        return $html_1 . $temp . $html_2;
    }
}