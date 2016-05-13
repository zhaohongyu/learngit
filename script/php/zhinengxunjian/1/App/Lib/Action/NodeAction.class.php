<?php

/**
 * @Description of NodeAction  节点管理
 * @author Zhao-Hong-Yu <ZhaoHongYu, zhao_hong_yu@sina.cn> 
 * @copyright Copyright (C) 2014 赵洪禹 
 * @datetime 2014年6月21日 20:42:40
 * @version 1.0
 */
class NodeAction extends CommonAction {
    /*
     * 列出所有节点
     */

    public function index() {
        $result = D("Node")->get_node_list();
        $this->assign("node_list", $result["node_list"]);
        $this->assign("page", $result["page"]);
        //获取当前页
        $page_now = isset($_GET["page"]) ? $_GET["page"] : 1;
        $this->assign("page_now", $page_now);
        $this->display("show");
    }

    /*
     * 增加节点
     */

    public function add() {
        if (!empty($_POST)) {
            $node_model = D("Node");
            $pid = isset($_POST["pid"]) ? intval($_POST["pid"]) : 0;
            $rst = $node_model->add_node($pid);
            if ($rst > 0) {
                //添加节点成功！
                //获取当前页
                $page_now = I("param.page",1);
                $this->success(L("node_add_success"), getUrl("Node/index",array("page"=>$page_now)));
            } else {
                // 表单数据填写不完成 返回对应信息
                $this->error($node_model->getError());
            }
        } else {
            //查询节点列表  按照sort排序  列出level等于1-2级的菜单
            $node_list = M("Node")->where(array("level" => array("neq", 3)))->order("sort")->select();
            foreach ($node_list as $k => $v) {
                //给子权限添加-- 以区分父权限
                $node_list[$k]['title'] = str_repeat('--/', $node_list[$k]['level'] - 1) . $node_list[$k]['title'];
            }
            $this->assign("node_list", $node_list);
            //获取当前页
            $page_now = isset($_GET["page"]) ? $_GET["page"] : 1;
            $this->assign("page_now", $page_now);
            $this->display();
        }
    }

    /*
     * 删除节点
     */

    public function del() {
        //获取当前页
        $page_now = isset($_GET["page"]) ? $_GET["page"] : 1;
        $node_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $node_model = D("Node"); // 实例化Node对象
        $rst = $node_model->del_node($node_id);
        if ($rst > 0) {
            //删除节点成功！
            //获取当前页
            $page_now = I("param.page",1);
            $this->success(L("node_del_success"), getUrl("Node/index",array("page"=>$page_now)));
        } else {
            //删除节点失败,请稍后重试！
            $this->error($node_model->getError());
        }
    }

    /*
     * 更新节点
     */

    public function update() {
        if (!empty($_POST)) {
            $node_model = D("Node"); // 实例化Node对象
            //收集用户提交的数据
            $formdata = $node_model->create();
            if ($formdata) {
                $rst = $node_model->save($formdata);
                if ($rst) {
                    $node_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
                    $status = isset($_POST['status']) ? intval($_POST['status']) : 0;
                    //查询node_info信息
                    $node_info_arr = $node_model->where(array("pid" => $node_id))->select();
                    my_array_filter($node_info_arr);
                    if ($status == 1) {
                        //如果$staus 为1 启用 先检查其父节点是否开启,如果为关闭,则不能启用
                        $node_info = $node_model->field("pid")->where(array("id" => $node_id))->find();
                        $pid = $node_info["pid"];
                        //查询出父级节点的启用状态
                        $node_praent_info = $node_model->field("status")->where(array("id" => $pid))->find();
                        $status2 = $node_praent_info["status"]; //父节点的启用状态
                        if ($status2 == 0) {
                            //不可以启用进行数据更新 将状态再该回0 这里操作了2次数据库
                            $data = array('status' => $status2);
                            $node_model->where(array("id" => $node_id))->save($data);
                            //提示给用户
                            //启用父级节点后,本节点将自动开启,目前您暂时不能开启此节点！
                            $this->error(L("node_update_failed_parrent_node_not_open"));
                        }
                    }
                    //如果要停用或启用的节点下面含有子节点 则其子节点也全部停用或启用
                    if (!empty($node_info_arr)) {
                        $data = array('status' => $status);
                        $node_model->where(array("pid" => $node_id))->save($data);
                    }
                    //节点更新成功！
                    //获取当前页
                    $page_now = I("param.page",1);
                    $this->success(L("node_update_success"), getUrl("Node/index",array("page"=>$page_now)));
                } else {
                    //没有更改数据！
                    $this->success(L("node_update_not_change"), getUrl("Node/index"));
                }
            } else {
                //更新失败,返回表单未填写相关信息
                $this->error($node_model->getError());
            }
        } else {
            //去更新节点界面
            $id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
            $node_info = D("Node")->where(array("id" => $id))->find();
            $this->assign("node_info", $node_info);
            //查询节点列表  按照sort排序  列出level等于1-2级的菜单
            $node_list = M("Node")->where(array("level" => array("neq", 3)))->order("sort")->select();
            foreach ($node_list as $k => $v) {
                //给子权限添加-- 以区分父权限
                $node_list[$k]['title'] = str_repeat('--/', $node_list[$k]['level'] - 1) . $node_list[$k]['title'];
            }
            $this->assign("node_list", $node_list);
            //获取当前页
            $page_now = isset($_GET["page"]) ? $_GET["page"] : 1;
            $this->assign("page_now", $page_now);
            $this->display();
        }
    }

}
