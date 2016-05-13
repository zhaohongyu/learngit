<?php

/**
 * Description of NoticeAction
 *
 * @author zhaohyg
 */
class NoticeAction extends CommonAction {

    /**
     * 发布通知
     */
    public function releasenotice() {
        $editorValue = $_POST["editorValue"];
        if (!empty($editorValue)) {
            $title = I("param.title");
            if (empty($title)) {
                $this->error(L("notcie_releasenotice_not_input_title"));
            }
            //封装数据存储到in_notice
            $map["release_uid"] = I("param.id");
            $map["release_real_name"] = I("param.real_name");
            $map["release_username"] = I("param.username");
            $map["title"] = I("param.title");
            $map["content"] = I("param.editorValue");
            $map["release_time"] = time();
            $rst = D("Notice")->add($map);
            if ($rst) {
                $this->success(L("notcie_releasenotice_success"), getUrl("Notice/noticelist"));
            } else {
                $this->error(L("notcie_releasenotice_failed"));
            }
        } else {
            $this->assign("user_info", $_SESSION['user_info']);
            $this->display("releasenotice");
        }
    }

    /**
     * 分页显示通知列表
     */
    public function noticelist() {
        //搜索的时间
        $search_time = I("param.search_time", 0);
        //拼装搜索条件
        $condition = array();
        if ($search_time != 0) {
            $condition["_string"] = "FROM_UNIXTIME(release_time,'%Y-%m-%d')='$search_time'";
        }
        $result = D("Notice")->getNoticeListByCondition($condition, $search_time);
        $this->assign("notice_list", $result["notice_list"]);
        $this->assign("page", $result["page"]);
        $this->assign("search_time", $search_time);
        $page_now = isset($_GET["page"]) ? $_GET["page"] : 1;
        $this->assign("page_now", $page_now);
        $this->display("noticelist");
    }

    /**
     * 查看公告详情
     */
    public function noticeDetail() {
        $id = I("param.id", 0);
        if ($id != 0) {
            $notice_info = D("Notice")->getById($id);
            //将实体转换成html
            $str = htmlspecialchars_decode($notice_info['content']);
            $notice_info["content"] = $str;
            //去详情页面
            $this->assign("notice_info", $notice_info);
            $page_now = isset($_GET["page_now"]) ? $_GET["page_now"] : 1;
            $this->assign("page_now", $page_now);
            $this->display("noticedetail");
        }
    }

    /**
     * 去编辑公告页面
     */
    public function noticeEdit() {
        $id = I("param.id", 0);
        if ($id != 0) {
            $notice_info = D("Notice")->getById($id);
            //将实体转换成html
            $str = htmlspecialchars_decode($notice_info['content']);
            $notice_info["content"] = $str;
            //去编辑页面
            //去详情页面
            $this->assign("notice_info", $notice_info);
            $page_now = isset($_GET["page_now"]) ? $_GET["page_now"] : 1;
            $this->assign("page_now", $page_now);
            $this->display("noticeedit");
        }
    }

    /**
     * 编辑公告
     */
    public function doNoticeEdit() {
        $id = I("param.id", 0); //要更新的通知信息id
        $editorValue = I("param.editorValue", '');
        $page_now = I("param.page", 1);
        if (!empty($editorValue) && $id != 0) {
            $title = I("param.title");
            if (empty($title)) {
                $this->error(L("notcie_releasenotice_not_input_title"));
            }
            if (empty($editorValue)) {
                $this->error(L("notcie_releasenotice_not_input_content"));
            }
            //封装数据存储到in_notice
            $map["id"] = $id;
            $map["title"] = I("param.title");
            $map["content"] = I("param.editorValue");
            $map["release_time"] = time();
            $rst = D("Notice")->save($map);
            if ($rst) {
                $this->success(L("notcie_releasenotice_edit_success"), getUrl("Notice/noticelist",array("page"=>$page_now)));
            } else {
                $this->error(L("notcie_releasenotice_edit_failed"));
            }
        }
    }

    /**
     * 删除公告
     */
    public function noticeDel() {
        $id = I("param.id", 0);
        $page_now = I("param.page_now", 1);
        if ($id != 0) {
            $rst = D("Notice")->where(array("id" => $id))->delete();
            //删除
            if ($rst > 0) {
                //删除公告成功
                $this->success(L("notcie_releasenotice_del_success"), getUrl("Notice/noticelist",array("page"=>$page_now)));
            } else {
                //删除公告失败,请稍后再试!
                $this->error(L("notcie_releasenotice_del_failed"));
            }
        } else {
            //删除公告失败,请稍后再试!
            $this->error(L("notcie_releasenotice_del_failed"));
        }
    }

}
