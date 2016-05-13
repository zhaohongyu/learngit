<?php

class NodeModel extends Model {

    protected $_validate = array(
        // 自动验证定义
        //array(验证字段,验证规则,错误提示,[验证条件,附加规则,验证时间])
        array('name', 'require', '{%node_model_name}'),
        //array('name','','{%node_model_exist}',0,'unique',1), //该节点已经存在
        array('title', 'require', '{%node_model_title}'),
        array('remark', 'require', '{%node_model_remark}'),
    );
    // 自动完成定义
    protected $_auto = array(
        //array(填充字段,填充内容,[填充条件,附加规则])
        array('status', '1'), // 新增的时候把status字段设置为1 即 启用节点
    );

    /**
     * 分页查询节点信息
     * 2014-6-23 10:14:45
     */
    public function get_node_list() {
        //计算总记录数
        $total = D("Node")->count();
        //读取配置文件的每页分页记录数
        $listRows = C("PAGE_LISTROWS");
        import('@.Components.Page');
        $page = new Page($total, $listRows);
        //Limit 2, 1  ===>2,1  进行转换
        $limit = $page->limit;
        //去除空格 ====>2,1
        $limit = ltrim(str_replace("Limit", "", $limit));
        //查询节点列表  按照sort排序  
        //过滤掉项目节点 防止误操作  过滤掉节点操作ACTION 防止误删除
        $where = array("id" => array("neq", 1), "name" => array("neq","App"), "pid" => array("neq", 0), "_logic" => "OR");
        $node_list = D("Node")->where($where)->order("sort")->limit($limit)->select();
        foreach ($node_list as $k => $v) {
            //给子权限添加-- 以区分父权限
            $node_list[$k]['name'] = str_repeat('--/', $node_list[$k]['level'] - 2) . $node_list[$k]['name'];
        }
        //封装结果
        $result["node_list"] = $node_list;
        $result["page"] = $page->fpage();
        return $result;
    }

    /**
     * 增加节点
     * @param array $formdata 表单收集过来的数据
     * 2014-6-23 10:25:30
     */
    public function add_node($pid) {
        // 上级节点 如果为空则为顶级节点 即pid为 0 level为1
        //计算所选节点等级
        if ($pid == 0) {
            //顶级节点
            $level = 1;
        } else {
            // 根据所选择的上级节点id 查询出上级节点等级,在此之上level+1
            $node_info_level = $this->field(array("level"))->where(array("id" => $pid))->find();
            $level = $node_info_level["level"] + 1;
        }
        //收集表单数据
        $formdata = $this->create();
        if ($formdata) {
            $formdata['pid'] = $pid;
            $formdata['level'] = $level;
            $rst = $this->add($formdata);
            if ($rst > 0) {
                //获取刚刚插入节点的id
                $node_id = $this->getLastInsID();
                //更新sort值 
                //第一种情况 level=1的时候sort 为1
                //第二种情况 level=2的时候sort 为刚刚插入的id
                //第三种情况 level=3的时候sort 为 pid-id  父级节点id---新插入节点id
                if ($level == 3) {
                    $sort = $pid . "-" . $node_id;
                } else if ($level == 2) {
                    $sort = $node_id;
                } elseif ($level == 1) {
                    $sort = "1";
                }
                $data["sort"] = $sort;
                $data["id"] = $node_id;
                return $this->save($data);
            } else {
                //添加节点失败,请稍后重试
                $this->error = L("node_add_failed");
                return null;
            }
        } else {
            $this->error = $this->getError();
            return null;
        }
    }

    /**
     * 删除一个节点
     * @param int $node_id 要删除的节点id
     */
    public function del_node($node_id) {
        //查询node_info信息
        $node_info_arr = $this->where(array("pid" => $node_id))->select();
        //如果要删除的节点下面含有子节点不可以删除,只有删除子节点过后才可以删除
        my_array_filter($node_info_arr);
        if (!empty($node_info_arr)) {
            //不可以删除  
            //该节点下含有子节点,不可以删除,请删除子节点后再进行删除操作！
            $this->error = L("node_del_has_child_nodes");
            return null;
        } else {
            //可以删除  返回影响记录数
            return $this->where(array("id" => $node_id))->delete();
        }
    }

}
