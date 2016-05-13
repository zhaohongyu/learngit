<?php

/**
 * Description of NoticeModel
 *
 * @author Administrator
 */
class NoticeModel extends Model {

    public function getNoticeListByCondition($condition = array(), $search_time = "") {
        //读取配置文件的每页分页记录数
        $listRows = C("PAGE_LISTROWS");
        import('@.Components.Page');
        //分页查询
        //计算总记录数
        $total = $this->where($condition)->count();
        $page = new Page($total, $listRows, "search_time=" . $search_time);
        //Limit 2, 1  ===>2,1  进行转换
        $limit = $page->limit;
        //去除空格 ====>2,1
        $limit = ltrim(str_replace("Limit", "", $limit));
        $notice_list = $this->where($condition)->order("release_time desc")->limit($limit)->select();
        $result["notice_list"] = $notice_list;
        $result["page"] = $page->fpage();
        return $result;
    }

}
