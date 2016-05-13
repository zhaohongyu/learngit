<?php

/**
 * @Description of PosintionAction  点位管理
 * @author Zhao-Hong-Yu <ZhaoHongYu, zhao_hong_yu@sina.cn> 
 * @copyright Copyright (C) 2014 赵洪禹 
 * @datetime 2014-6-26 00:14:50
 * @version 1.0
 */
class PositionAction extends CommonAction {
    /*
     * 列出所有点位
     */

    public function index() {
        $result = D("Position")->get_position_list();
        //给模板分配查询到的点位信息
        //获取当前页
        $page_now = isset($_GET["page"]) ? $_GET["page"] : 1;
        $this->assign("position_list", $result["position_list"]);
        $this->assign("page", $result["page"]);
        $this->assign("page_now", $page_now);
        $this->display("show");
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
        $this->display("show");
    }

    /*
     * 增加点位
     */

    public function add() {
        if (!empty($_POST)) {
            //$code_status  0==>点位编码必须填写 1==>点位编码可以使用 2==>点位编码已经存在
            $code_status = isset($_POST["code_status"]) ? intval($_POST["code_status"]) : 0;
            if (1 != $code_status) {
                //您填写的点位编码有误,请检查后再提交!
                $this->error(L("position_add_error_0"));
            } else {
                $position_model = D("Position");
                $formdata = $position_model->create();
                if ($formdata) {
                    $rst = $position_model->add();
                    if ($rst > 0) {
                        //添加点位成功
                        $this->success(L("position_add_success"), getUrl("Position/index"));
                    } else {
                        //添加点位失败,请稍后重试！
                        $this->error(L("position_add_failed"));
                    }
                } else {
                    $this->error($position_model->getError());
                }
            }
        } else {
            $this->display();
        }
    }

    /*
     * 删除点位
     */

    public function del() {
        $position_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $Position = M("Position"); // 实例化Position对象
        //删除position表中的点位记录
        $rst = $Position->where(array("id" => $position_id))->delete();
        if ($rst > 0) {
            //删除点位成功
            //获取当前页 方便回显
            $page = isset($_GET["page_now"]) ? $_GET["page_now"]:1;
            $this->success(L("position_del_success"), getUrl("Position/index",array("page" => $page)));
        } else {
            //删除点位失败,请稍后重试！
            $this->error(L("position_del_failed"));
        }
    }

    /*
     * 去更新点位界面
     */

    public function update() {
        if (!empty($_POST)) {
            $position_model = D("Position");
            //收集点位提交的数据
            $formdata = $position_model->create();
            if ($formdata) {
                $rst = $position_model->save($formdata);
                //获取当前页 方便回显
                $page = isset($_POST["page"]) ? $_POST["page"] : 1;
                if ($rst) {
                    //点位更新成功！
                    $this->success(L("position_update_success"), getUrl("Position/index",array("page" => $page)));
                } else {
                    //数据没有改变
                    $this->success(L("position_update_not_change"), getUrl("Position/index",array("page" => $page)));
                }
            } else {
                //表单数据填写错误 给出相应提示
                $this->error($position_model->getError());
            }
        } else {
            //获取当前页
            $page_now = isset($_GET["page_now"]) ? $_GET["page_now"] : 1;
            $id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
            $position_info = D("Position")->where(array("id" => $id))->find();
            $this->assign("position_info", $position_info);
            $this->assign("page_now", $page_now);
            $this->display();
        }
    }

    /*
     * 检查点位编码是否存在 
     */

    public function check_code() {
        $code = isset($_POST["code"]) ? $_POST["code"] : '';
        if ($this->isAjax()) {
            if (empty($code)) {
                //点位编码必须填写
                $this->ajaxReturn("", L("position_model_code"), 0);
            } else {
                //去数据库查询点位编码是否已经存在
                $position_info = D("Position")->where(array("code" => $code))->find();
                if (empty($position_info)) {
                    //点位编码可以使用
                    $this->ajaxReturn("", L("position_model_code_yes"), 1);
                } else {
                    //点位编码已经存在
                    $this->ajaxReturn("", L("position_model_code_exits"), 2);
                }
            }
        }
    }

}
