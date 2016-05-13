<?php

/**
 * Description of NoticeModel
 *
 * @author Administrator
 */
class NoticeModel extends Model {

    /**
     * 获取通知
     */
    public function getNotice($data) {
        $search_time = $data['SearchTime'] ? $data['SearchTime'] : 0;
        $page = $data['Page'] ? $data['Page'] : 1;
        //拼装搜索条件
        $condition = array();
        if ($search_time != 0) {
            $condition["_string"] = "FROM_UNIXTIME(release_time,'%Y-%m-%d')='$search_time'";
        }
        $result2 = $this->getNoticeListByCondition($condition, $page, $search_time);
        $notice_list = $result2["notice_list"];
        my_array_filter($notice_list);
        if (!empty($notice_list)) {
            foreach ($notice_list as $k => $v) {
                $result["noticeList"][] = array(
                    'id' => $v['id'],
                    'title' => $v['title'],
                    'content' => preg_replace("/<(.*?)>/", '', htmlspecialchars_decode($v['content'])),
                    //preg_replace("/<(.*?)>/",'',htmlspecialchars_decode($v['content']))  
                    //'content' => htmlspecialchars_decode($v['content']), //带html标签
                    'releaseUid' => $v['release_uid'],
                    'releaseTime' => formatTime($v["release_time"], "Y-m-d H:i:s", 1),
                    'releaseRealName' => $v['release_real_name'],
                    'releaseUsername' => $v['release_username'],
                );
            }
            $result["status"] = array(
                'result' => 1,
                'errorCode' => '',
                'errorMessage' => '',
            );
            $result["pageInfo"] = $result2["page_info"]; //返回分页信息
        } else {
            //没有查到符合条件的通知!
            $this->error = 4213;
        }
        if (!$result) {
            $error_code = C('ERROR_CODE');
            $result["status"] = array(
                'result' => 0,
                'errorCode' => $this->error,
                'errorMessage' => $error_code[$this->error],
            );
        }
        //为统计流量做准备
        $uid = intval($data['UserId']) ? intval($data['UserId']) : 0;
        $flow_statistics_id = intval($data['FlowStatisticsId']) ? intval($data['FlowStatisticsId']) : 0;
        if($uid!=0&&$flow_statistics_id!=0){
            $result["UserId"]=$uid;
            $result["FlowStatisticsId"]=$flow_statistics_id;
        }
        return $result;
    }

    /**
     * 根据条件搜索通知消息
     * @return typ 通知消息列表,带分页
     */
    public function getNoticeListByCondition($condition = array(), $page = 1, $search_time = "") {
        //分页查询
        //计算总记录数
        $total = $this->where($condition)->count();
        //读取配置文件的每页分页记录数
        $listRows = C("PAGE_LISTROWS");
        import('@.Components.Page');
        $Page = new Page($total, $listRows, $page, "search_time=" . $search_time);
        //Limit 2, 1  ===>2,1  进行转换
        $limit = $Page->limit;
        //去除空格 ====>2,1
        $limit = ltrim(str_replace("Limit", "", $limit));
        $notice_list = $this->where($condition)->order("release_time desc")->limit($limit)->select();
        $result["notice_list"] = $notice_list;
        $result["page_info"] = array(
            "total" => $total, //总记录数
            "listRows" => $listRows, //每页显示条数
            "page" => $page, //当前页
            "pageNum" => ceil($total / $listRows), //总页数
        );
        //为统计流量做准备
        $uid = intval($data['UserId']) ? intval($data['UserId']) : 0;
        $flow_statistics_id = intval($data['FlowStatisticsId']) ? intval($data['FlowStatisticsId']) : 0;
        if($uid!=0&&$flow_statistics_id!=0){
            $result["UserId"]=$uid;
            $result["FlowStatisticsId"]=$flow_statistics_id;
        }
        return $result;
    }

    /*
     * 根据公告id获取通知公告详情
     */

    public function getNoticeDetail($data) {
        $notice_id = intval($data['NoticeId']) ? intval($data['NoticeId']) : 0;
        if ($notice_id != 0) {
            $notice_info = D("Notice")->getById($notice_id);
            //将实体转换成html
            $str = htmlspecialchars_decode($notice_info['content']);
            $notice_info["content"] = $str;
            $result["noticeInfo"] = array(
                'noticeId`'         => $notice_info['id'],
                'title'             => $notice_info['title'],
                'content'           => $notice_info['content'],
                'releaseUid'         => $notice_info['release_uid'],
                'releaseTime'        => $notice_info['release_time'],
                'releaseRealName'   => $notice_info['release_real_name'],
                'releaseUsername'   => $notice_info['release_username'],
            );
            $result["status"] = array(
                'result' => 1,
                'errorCode' => '',
                'errorMessage' => '',
            );
        } else {
            //请求数据错误
            $this->error = 100;
        }
        if (!$result) {
            $error_code = C('ERROR_CODE');
            $result["status"] = array(
                'result' => 0,
                'errorCode' => $this->error,
                'errorMessage' => $error_code[$this->error],
            );
        }
        //为统计流量做准备
        $uid = intval($data['UserId']) ? intval($data['UserId']) : 0;
        $flow_statistics_id = intval($data['FlowStatisticsId']) ? intval($data['FlowStatisticsId']) : 0;
        if($uid!=0&&$flow_statistics_id!=0){
            $result["UserId"]=$uid;
            $result["FlowStatisticsId"]=$flow_statistics_id;
        }
        return $result;
    }

}
