<?php

/**
 * @Description of DeptAction  部门管理
 * @author Zhao-Hong-Yu <ZhaoHongYu, zhao_hong_yu@sina.cn> 
 * @copyright Copyright (C) 2014 赵洪禹 
 * @datetime 2014年6月25日 21:55:07
 * @version 1.0
 */
class DeptAction extends CommonAction {
    /*
     * 列出所有部门
     */

    public function index() {
        $result = D("Dept")->get_dept_list();
        $this->assign("dept_list", $result["dept_list"]);
        $this->assign("page", $result["page"]);
        $this->display("show");
    }

    /*
     * 增加部门
     */

    public function add() {
        if (!empty($_POST)) {
            $dept_model = D("Dept");
            // 上级部门 如果为空则为顶级部门 即pid为 0 level为1
            $pid = isset($_POST["pid"]) ? intval($_POST["pid"]) : 0;
            //计算所选部门等级
            if ($pid == 0) {
                //顶级部门
                $level = 1;
            } else {
                // 根据所选择的上级部门id 查询出上级部门等级,在此之上level+1
                $dept_info_level = $dept_model->field(array("level"))->where(array("id" => $pid))->find();
                $level = $dept_info_level["level"] + 1;
            }
            //收集表单数据
            $formdata = $dept_model->create();
            $dept_model->pid = $pid;
            $dept_model->level = $level;
            if ($formdata) {
                $rst = $dept_model->add();
                if ($rst > 0) {
                    //添加部门成功
                    $this->success(L("dept_add_success"), getUrl("Dept/index"));
                } else {
                    //'添加部门失败,请稍后重试！'
                    $this->error(L("dept_add_failed"));
                }
            } else {
                //表达数据填写不完整,提示对应信息
                $this->error($dept_model->getError());
            }
        } else {
            $dept_list = M("Dept")->select();
            $this->assign("dept_list", $dept_list);
            $this->display();
        }
    }

    /*
     * 删除部门
     */

    public function del() {
        $dept_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $dept_model = D("Dept"); // 实例化Dept对象
        //删除Dept表中的用户记录  是不是要清空user表中对应的记录
        $rst = $dept_model->where(array("id" => $dept_id))->delete();
        if ($rst > 0) {
            // 清空用户dept_id为$dept_id 的 用户的dept_id
            $data = array('dept_id' => 0);
            D("User")->where(array('dept_id' => $dept_id))->save($data);
            //删除部门成功！
            $this->success(L("dept_del_success"), getUrl("Dept/index"));
        } else {
            //删除部门失败,请稍后重试！
            $this->error(L("dept_del_failed"));
        }
    }

    /*
     * 去更新更新部门
     */

    public function update() {
        if (!empty($_POST)) {
            $dept_model = D("Dept");
            //收集表单数据
            $formdata = $dept_model->create();
            if ($formdata) {
                $rst = $dept_model->save();
                if ($rst > 0) {
                    //更新部门成功！
                    $this->success(L("dept_update_success"), getUrl("Dept/index"));
                } else {
                    //没有更改数据！
                    $this->success(L("dept_update_not_change"), getUrl("Dept/index"));
                }
            } else {
                //表单填写信息项不完整  提示对应信息
                $this->error($dept_model->getError());
            }
        } else {
            //去更新页面
            $id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
            $dept_info = D("Dept")->where(array("id" => $id))->find();
            $dept_list = D("Dept")->select();
            $this->assign("dept_info", $dept_info);
            $this->assign("dept_list", $dept_list);
            $this->display();
        }
    }

}
