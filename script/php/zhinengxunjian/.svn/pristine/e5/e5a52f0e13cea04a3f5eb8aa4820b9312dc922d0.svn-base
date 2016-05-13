<?php

/**
 * @Description of RoleAction  角色管理
 * @nodeor Zhao-Hong-Yu <ZhaoHongYu, zhao_hong_yu@sina.cn> 
 * @copyright Copyright (C) 2014 赵洪禹 
 * @datetime 2014年6月17日 20:46:56
 * @version 1.0
 */
class RoleAction extends CommonAction {
    /*
     * 列出所有角色
     */

    public function index() {
        $result = D("Role")->get_role_list();
        $this->assign("role_list", $result["role_list"]);
        $this->assign("page", $result["page"]);
        $this->display("show");
    }

    /*
     * 增加角色
     */

    public function add() {
        if (!empty($_POST)) {
            $role_model = D("Role");
            $formdata = $role_model->create();
            if ($formdata) {
                $rst = $role_model->add();
                if ($rst > 0) {
                    //添加角色成功
                    $this->success(L("role_add_success"), getUrl("Role/index"));
                } else {
                    //添加角色失败,请稍后重试！
                    $this->error(L("role_add_failed"));
                }
            } else {
                //表单信息填写不完整
                $this->error($role_model->getError());
            }
        } else {
            $this->display();
        }
    }

    /*
     * 删除角色
     */

    public function del() {
        $role_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $role_model = D("Role"); // 实例化Role对象
        $rst = $role_model->where(array("id" => $role_id))->delete();
        if ($rst > 0) {
            // 清空用户role_id为$role_id 的 用户的role_id
            $data = array('role_id' => 0);
            D("User")->where(array('role_id' => $role_id))->save($data);
            //删除角色成功！
            $this->success(L("role_del_success"), getUrl("Role/index"));
        } else {
            //删除角色失败,请稍后重试！
            $this->error(L("role_del_failed"));
        }
    }

    /*
     * 更新角色
     */

    public function update() {
        if (!empty($_POST)) {
            $role_model = D("Role"); // 实例化Role对象
            //收集用户提交的数据
            $formdata = $role_model->create();
            if ($formdata) {
                $rst = $role_model->save($formdata);
                if ($rst) {
                    //角色更新成功
                    $this->success(L("role_update_success"), getUrl("Role/index"));
                } else {
                    //数据未更改
                    $this->success(L("role_update_not_change"), getUrl("Role/index"));
                }
            } else {
                //表单信息不完整 提示对应信息
                $this->error($role_model->getError());
            }
        } else {
            //去更新角色界面
            $id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
            $role_info = D("Role")->where(array("id" => $id))->find();
            $this->assign("role_info", $role_info);
            $this->display();
        }
    }

    /**
     * 给角色分配权限,即可访问的节点列表
     */
    public function distribute() {
        if (!empty($_POST)) {
            //接收提交过来的参数
            $rst = D('Role')->distributeNode($_POST);
            if ($rst) {
                //权限分配成功
                $this->success(L("role_distribute_success"), getUrl("Role/index"));
            } else {
                //权限分配失败,请稍后重试!
                $this->error(L("role_distribute_failed"));
            }
        } else {
            $role_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            //分配权限查询出所有权限 以层级样式显示
            $node_model = D('Node');
            //查询所有父级权限 level 2 级别的权限(节点)
            $p_node_info = $node_model->where(array("level" => 2, "status" => 1))->order("sort")->select();
            //查询所有子级权限
            $c_node_info = $node_model->where(array("level" => 3, "status" => 1))->order("sort")->select();
            //根据角色ID查询出对应的角色 信息
            $role_info = D('Role')->getById($role_id);
            //查询出该角色拥有的权限
            $role_node_ids = D("Access")->field("node_id")->where(array("role_id" => $role_id))->select();
            $temp_arr = array();
            foreach ($role_node_ids as $k => $v) {
                $temp_arr[] = $v["node_id"];
            }
            $this->assign('p_node_info', $p_node_info);
            $this->assign('count', count($p_node_info));
            $this->assign('c_node_info', $c_node_info);
            $this->assign('role_node_ids', $temp_arr);
            $this->assign('role_info', $role_info);
            //从session中读取用户信息
            $this->assign("user_info", $_SESSION['user_info']);
            $this->display();
        }
    }

}
