<?php
/**
 * @Description of VerifyAction  验证码
 * @author Zhao-Hong-Yu <ZhaoHongYu, zhao_hong_yu@sina.cn> 
 * @copyright Copyright (C) 2014 赵洪禹 
 * @datetime 2014年6月24日 20:53:23
 * @version 1.0
 */
class VerifyAction extends Action
{
    public function index() 
    {
        //验证码
        import("ORG.Util.Verify");
        $verify = new Verify();
        $verify->seKey      = 'yonyou_zhy';
        $verify->fontSize   = 18;
        $verify->imageH     = 50;
        $verify->imageL     = 100;
        $verify->length     = 4;
        $verify->entry();
    }
}
?>