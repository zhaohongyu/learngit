<?php

/**
 * Description of HiddenTroubleModel
 *
 * @author Administrator
 */
class HiddenTroubleModel extends Model {

    /**
     * 分页获取隐患信息列表
     * 2014-8-24 15:15:05
     * @param type $condition
     */
    public function getHiddenTroubleListByCondition($condition = array(), $start_time='',$end_time='',$status='',$real_name='') {
        //计算总记录数
        $total = D("HiddenTrouble")->where($condition)->count();
        //读取配置文件的每页分页记录数
        $listRows = C("PAGE_LISTROWS");
        import('@.Components.Page');
        $page = new Page($total, $listRows, "start_time=" . $start_time."&end_time=" . $end_time . "&specialty_id=" . $condition["specialty_id"] . "&status=" .$status."&real_name=".$real_name);
        //Limit 2, 1  ===>2,1  进行转换
        $limit = $page->limit;
        //去除空格 ====>2,1
        $limit = ltrim(str_replace("Limit", "", $limit));
        //封装结果集
        $hidden_trouble_list = D("HiddenTrouble")->where($condition)->limit($limit)->order("submit_time desc")->select();
        $result["hidden_trouble_list"] = $hidden_trouble_list;
        $result["page"] = $page->fpage();
        //show_msg_not_exit(D("HiddenTrouble")->getLastSql());
        return $result;
    }
    
    /**
     * 分页获取隐患信息列表 为导出excel做准备  去掉分页
     * 2014-8-4 19:50:56
     * @param type $condition
     */
    public function getHiddenTroubleListByCondition4ExportExcel($condition = array()) {
        //封装结果集
        $hidden_trouble_list = D("HiddenTrouble")->where($condition)->order("submit_time desc")->select();
        $result["hidden_trouble_list"] = $hidden_trouble_list;
        return $result;
    }

}
