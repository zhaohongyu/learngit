<?php

/**
 * @Description of SpecialtyAction
 * @author Zhao-Hong-Yu <ZhaoHongYu, zhao_hong_yu@sina.cn> 
 * @copyright Copyright (C) 2014 赵洪禹 
 * @datetime 2014-6-27  12:11:17
 * @version 1.0
 */
class SpecialtyAction extends CommonAction {
    /*
     * 列出所有专业
     */

    public function index() {
        $result = D("Specialty")->get_specialty_list();
        $this->assign("specialty_list", $result["specialty_list"]);
        $this->assign("page", $result["page"]);
        $this->display("show");
    }

    /*
     * 增加专业
     */

    public function add() {
        if (!empty($_POST)) {
            $specialty_model = D("Specialty");
            $formdata = $specialty_model->create();
            if ($formdata) {
                $rst = $specialty_model->add();
                if ($rst > 0) {
                    //添加专业成功
                    $this->success(L("specialty_add_success"), getUrl("Specialty/index"));
                } else {
                    //添加专业失败,请稍后重试！
                    $this->error(L("specialty_add_failed"));
                }
            } else {
                //表单信息填写不完整
                $this->error($specialty_model->getError());
            }
        } else {
            $this->display();
        }
    }

    /*
     * 删除专业
     */

    public function del() {
        $specialty_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $specialty_model = D("Specialty"); // 实例化Specialty对象
        $rst = $specialty_model->where(array("id" => $specialty_id))->delete();
        if ($rst > 0) {
            //删除专业成功！
            $this->success(L("specialty_del_success"), getUrl("Specialty/index"));
        } else {
            //删除专业失败,请稍后重试！
            $this->error(L("specialty_del_failed"));
        }
    }

    /*
     * 更新专业
     */

    public function update() {
        if (!empty($_POST)) {
            $specialty_model = D("Specialty"); // 实例化Specialty对象
            //收集用户提交的数据
            $formdata = $specialty_model->create();
            if ($formdata) {
                $rst = $specialty_model->save($formdata);
                if ($rst) {
                    //专业更新成功
                    $this->success(L("specialty_update_success"), getUrl("Specialty/index"));
                } else {
                    //数据没有改变
                    $this->success(L("specialty_update_not_change"), getUrl("Specialty/index"));
                }
            } else {
                //表单信息不完整 提示对应信息
                $this->error($specialty_model->getError());
            }
        } else {
            //去更新专业界面
            $id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
            $specialty_info = D("Specialty")->where(array("id" => $id))->find();
            $this->assign("specialty_info", $specialty_info);
            $this->display();
        }
    }

}
