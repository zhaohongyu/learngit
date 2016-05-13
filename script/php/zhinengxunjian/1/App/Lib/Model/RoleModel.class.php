<?php

class RoleModel extends Model {

    protected $_validate = array(
        // 自动验证定义
        //array(验证字段,验证规则,错误提示,[验证条件,附加规则,验证时间])
        array('name', 'require', '{%role_model_name}'),
        array('name','','{%role_model_exist}',0,'unique',1), //该角色已经存在
        array('remark', 'require', '{%role_model_remark}'),
    );
    // 自动完成定义
    protected $_auto = array(
        //array(填充字段,填充内容,[填充条件,附加规则])
        array('status', '1'), // 新增的时候把status字段设置为1 即 启用职位
    );

    /**
     * 分页查询角色信息
     * 2014年6月23日 10:06:23
     */
    public function get_role_list() {
        //计算总记录数
        $total = M("Role")->count();
        //读取配置文件的每页分页记录数
        $listRows = C("PAGE_LISTROWS");
        import('@.Components.Page');
        $page = new Page($total, $listRows);
        //Limit 2, 1  ===>2,1  进行转换
        $limit = $page->limit;
        //去除空格 ====>2,1
        $limit = ltrim(str_replace("Limit", "", $limit));
        //查询角色列表
        //封装结果
        $role_list = M("Role")->limit($limit)->select();
        $result["role_list"] = $role_list;
        $result["page"] = $page->fpage();
        return $result;
    }

    /**
     * 分配权限
     */
    public function distributeNode($formdata) {
        //先删除在access表中 role_id为$role_id 的数据,不然会无限制增多
        $role_id = isset($_POST['role_id']) ? intval($_POST['role_id']) : 0;
        $node_id_arr = $_POST['node_id'];
        M("Access")->where(array("role_id" => $role_id))->delete();
        //如果$node_id_arr 为空 说明用户做的是取消权限
        //否则是添加权限
        if (!empty($node_id_arr)) {
            // 要记得给该id 增加项目访问权限,即id=$role_id  node_id=1 pid=0 level=
            $node_id_arr[] = 1;
        }
        //统计要更新的数据记录
        $count = count($node_id_arr);
        $count2 = 0;
        foreach ($node_id_arr as $k => $v) {
            //循环去node 表 查询 id 为 $v 的 level pid
            $temp_info = D("Node")->field("level,pid")->where(array("id" => $v))->find();
            //封装数据写入access表
            $data["role_id"] = $role_id;
            $data["node_id"] = $v;
            $data["level"] = $temp_info["level"];
            $data["pid"] = $temp_info["pid"];
            $rst = M("Access")->add($data);
            if ($rst) {
                $count2 = $count2 + 1;
            }
        }
        if ($count == $count2) {
            return true;
        } else {
            return false;
        }
    }

}
