<?php

/**
 * @Description of TestAction
 * @author Zhao-Hong-Yu <ZhaoHongYu, zhao_hong_yu@sina.cn> 
 * @copyright Copyright (C) 2014 赵洪禹 
 * @datetime 2014-6-17  14:11:16
 * @version 1.0
 */
class TestAction extends CommonAction {

    
    /*
     * 自定义测试发送邮件
     */

    public function testSendEmail() {
//        echo date("Y-m-d H:i:s",time());
//        Log::$format = '[ Y-m-d H:i:s ]';
//        Log::write($message="测试邮件日志记录",$level="INFO",$type='3',$destination='',$extra='');
//        die();
        //引入PHPMailer
        //alias_import('PHPMailer', VENDOR_PATH . "PHPMailer/class.phpmailer.php");
        import("@.Components.PHPMailer.class#phpmailer", "", ".php");
        # Create object of PHPMailer
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->Host = 'mail.yonyou.com';
        $mail->Port = 25;
        $mail->Username = "zhaohyg@yonyou.com";
        $mail->Password = "123@diancaonima";
        $mail->SetFrom("zhaohyg@yonyou.com", "智能巡检系统");
        $mail->Subject = "主题:智能巡检系统邮件测试";
        $mail->CharSet = "UTF-8";   // 这里指定字符集！    
        $mail->Encoding = "base64";  
        $body = "内容:智能巡检系统邮件测试";
        $mail->MsgHTML($body);
        $to = "zhao_hong_yu@sina.cn";
        $mail->AddAddress($to, "测试账号");
        $mail->Send();
        if (!$mail->Send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
            echo "Message sent Successfully!";
        }
    }
    
    
    public function liNing() {
       
        $a=~~520.1314;
        echo '~~520.1314='.$a;
        die();
        if(empty($_POST["Pc_content"])){
            $this->display("show");
        }else{
            //根据条件进行模糊查询 Pc_content 
            $Pc_content=I("param.Pc_content",'');
            $where["Pc_content"]=array('like', "%" . $Pc_content . "%");
            //SELECT * FROM `in_putdown_content` WHERE ( `Pc_content` LIKE '%中华%' ) ORDER BY pc_createTime asc LIMIT 20  
            $putdown_content_list=M("PutdownContent")->where($where)->order("pc_createTime asc")->limit("20")->select();
            foreach ($putdown_content_list as $k=>$v){
                //获得问题ID
                $pc_Id=$v["pc_Id"];
                //获得答案ID
                $answer_pc_ID=$pc_Id+1;
                //去数据库查询对应的答案
                //SELECT * FROM `in_putdown_content` WHERE ( `pc_Id` = 18 ) LIMIT 1  
                $putdown_content_list[$k]['answer']=M("PutdownContent")->where(array("pc_Id"=>$answer_pc_ID))->find();
            }
            $this->assign("putdown_content_list",$putdown_content_list);
            $this->display("show");
        }
    }

    
    

    /**
     *某用户的城市照片列表（每个城市取3张，城市顺序无要求）
     */
    public function meiTu() {
        //查出该用户拥有照片的城市id集合
        $user_id=1;
        $where=array();
        $where["user_id"]=$user_id;
        $field="city_id";
        $limit=3;//取出相片张数
        //SELECT `city_id` FROM `m_photo` WHERE ( `user_id` = 1 ) GROUP BY city_id 
        $city_id_list=M("Photo")->field($field)->where($where)->group("city_id")->select();
        $photo_list=array();
        //TODO 考虑将照片列表存入缓存....避免每次直接操作数据库循环消耗性能
        foreach ($city_id_list as $v){
            foreach ($v as $vv){
                //根据城市id 和user_id 去查询前三张照片
                //SELECT * FROM `m_photo`
                //WHERE (`user_id` = 1) AND (`city_id` = 4) ORDER BY release_time DESC LIMIT 3
                $photo_list[$vv]=M("Photo")
                    ->where(array("user_id"=>$user_id,"city_id"=>$vv))
                    ->order("release_time desc")
                    ->limit($limit)
                    ->select();
            }
        }
        // do something...
    }
    /**
     * 测试bootstrap
     */
    public function testbt(){
        $this->display();
    }
    /**
     * 到处excel
     */
    public function exp() {
        header("content-type:text/html;charset=utf-8");
        /** Error reporting */
        error_reporting(E_ALL);
        /** PHPExcel */
        //include_once 'PHPExcel.php';
        import("@.Components.PHPExcel.PHPExcel", "", ".php");

        /** PHPExcel_Writer_Excel2003用于创建xls文件 */
//        include_once 'PHPExcel/Writer/Excel5.php';
        import("@.Components.PHPExcel.PHPExcel.Writer.Excel5", "", ".php");

// Create new PHPExcel object
        $objPHPExcel = new PHPExcel();

// Set properties
        $objPHPExcel->getProperties()->setCreator("李汉团");
        $objPHPExcel->getProperties()->setLastModifiedBy("李汉团");
        $objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");
        $objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Test Document");
        $objPHPExcel->getProperties()->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.");

// Add some data
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Date');
//合并单元格：
        $objPHPExcel->getActiveSheet()->mergeCells('B1:F1');
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'CSAT Score');
        $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Grand Total');
        $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'CSAT');
        $objPHPExcel->getActiveSheet()->SetCellValue('A2', '08/01/11');
        $objPHPExcel->getActiveSheet()->SetCellValue('B2', '0');
        $objPHPExcel->getActiveSheet()->SetCellValue('C2', '0');
        $objPHPExcel->getActiveSheet()->SetCellValue('D2', '0');
        $objPHPExcel->getActiveSheet()->SetCellValue('E2', '0');
        $objPHPExcel->getActiveSheet()->SetCellValue('F2', '0');
        $objPHPExcel->getActiveSheet()->SetCellValue('G2', '0');
        $objPHPExcel->getActiveSheet()->SetCellValue('H2', '0%');
        $objPHPExcel->getActiveSheet()->SetCellValue('A3', '08/01/11');
        $objPHPExcel->getActiveSheet()->SetCellValue('B3', '0');
        $objPHPExcel->getActiveSheet()->SetCellValue('C3', '0');
        $objPHPExcel->getActiveSheet()->SetCellValue('D3', '0');
        $objPHPExcel->getActiveSheet()->SetCellValue('E3', '0');
        $objPHPExcel->getActiveSheet()->SetCellValue('F3', '0');
        $objPHPExcel->getActiveSheet()->SetCellValue('G3', '0');
        $objPHPExcel->getActiveSheet()->SetCellValue('H3', '0%');
        $objPHPExcel->getActiveSheet()->SetCellValue('A4', '08/01/11');
        $objPHPExcel->getActiveSheet()->SetCellValue('B4', '0');
        $objPHPExcel->getActiveSheet()->SetCellValue('C4', '0');
        $objPHPExcel->getActiveSheet()->SetCellValue('D4', '0');
        $objPHPExcel->getActiveSheet()->SetCellValue('E4', '0');
        $objPHPExcel->getActiveSheet()->SetCellValue('F4', '0');
        $objPHPExcel->getActiveSheet()->SetCellValue('G4', '0');
        $objPHPExcel->getActiveSheet()->SetCellValue('H4', '0%');

// Rename sheet
        $objPHPExcel->getActiveSheet()->setTitle('Csat');


// Save Excel 2007 file
//$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

        $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
        $objWriter->save(str_replace('.php', '.xls', __FILE__));
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate,post-check=0,pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header("Content-Disposition:attachment;filename=csat.xls");
        header("Content-Transfer-Encoding:binary");
        $objWriter->save("php://output");
    }

    /**
     * 测试导出excel
     */
    public function exportExcel() {
//        header("Content-type:application/vnd.ms-excel;charset=utf-8;");
//        header("Content-Disposition:attachment;filename=test_data.xls");
//        echo "姓名" . "\t";
//        echo "年龄" . "\t";
//        echo "学历" . "\t";
//        echo "\n";
//        echo "张三" . "\t";
//        echo "25" . "\t";
//        echo "本科" . "\t";
        $this->display("export");
    }

    /**
     * 测试API接口模拟手机发送数据
     * 
     */
    public function testAPI() {
//        echo date("Y-m-d", time())."-".md5(time());
        import("ORG.Util.Curl.CurlUtil");
        $curlutil = new CurlUtil();
        $url = C("API_URL_UPLOAD");
        $img = file_get_contents("D:/1.jpg");
        $result = $curlutil->getInfoByPost($url, $img);
        show_msg($result);
    }

    /**
     * 测试播放器jwplayer
     */
    public function testplayer() {
        $this->display("jwplayer");
    }

    /**
     * 测试播放器ckplayer
     */
    public function testckplayer() {
        $this->display("ckplayer");
    }

    /**
     * 测试文件上传
      [image] => Array
      (
      [name] => IMG_2037.JPG
      [type] => image/jpeg
      [tmp_name] => D:\Program Files (x86)\EasyPHP-DevServer-14.1VC11\binaries\tmp\php88C3.tmp
      [error] => 0
      [size] => 275347
      )
     */
    public function testFileUpload() {
        if (!empty($_FILES)) {
            /*
              import('ORG.Net.UploadFile');
              $config['savePath'] = './Uploads/';
              $config['thumb'] = true;
              $config['thumbPrefix'] = 'm_,s_';
              $config['thumbMaxWidth'] = '200,50';
              $config['thumbMaxHeight'] = '200,50';
              $upload = new UploadFile($config); // 实例化上传类并传入参数
             */
            import('ORG.Net.UploadFile');
            $upload = new UploadFile(); // 实例化上传类
//$upload->maxSize = 3145728; // 设置附件上传大小
            $upload->allowExts = array('jpg', 'gif', 'png', 'jpeg'); // 设置附件上传类型
            $upload->savePath = './Public/upload/images/'; // 设置附件上传目录
//设置需要生成缩略图，仅对图像文件有效
            $upload->thumb = true;
//设置需要生成缩略图的文件后缀
            $upload->thumbPrefix = 'm_';  //生产1张缩略图
//设置缩略图最大宽度
            $upload->thumbMaxWidth = '200';
//设置缩略图最大高度
            $upload->thumbMaxHeight = '200';
            $upload->autoSub = true; //使用子目录上传
            $upload->subType = 'date';
            $upload->dateFormat = 'Y-m-d';
            if (!$upload->upload()) {// 上传错误提示错误信息
                $this->error($upload->getErrorMsg());
            } else {// 上传成功
                /**
                  [0] => Array
                  (
                  [name] => IMG_2037.JPG
                  [type] => image/jpeg
                  [size] => 275347
                  [key] => image
                  [extension] => JPG
                  [savepath] => ./Public/upload/images/
                  [savename] => 2014-07-01/53b298076d7a1.JPG
                  [hash] => cfdab95fd0d8da16a9e60554b8b493d8
                  )
                 */
                $this->success('上传成功！');
            }
        } else {
            $this->display("fileupload");
        }
    }

    public function testFileUpload2() {
        if (!empty($_FILES)) {
//根据上文文件类型不同存储到不同文件夹
            if (!empty($_FILES["image"]["name"])) {
                $result = $this->_upload(C("UPLOADS_PATH_IMAGES"));
            }
            if (!empty($_FILES["video"]["name"])) {
                $result = $this->_upload(C("UPLOADS_PATH_VIDEOS"));
            }
            if (is_array($result)) {
//                show_msg($result);
//                $this->success('上传成功！');
            } else {
                $this->error($result);
            }
        } else {
            $this->display("fileupload");
        }
    }

    /**
     * 测试公共函数
     * @datetime 2014-6-17  14:11:16
     */
    public function testCommonFnc() {
        $str = "/warning/processhiddentroubleinfo/id/1.html";
        $findme = "/id";
//        echo strpos($str,$findme);
        $str2 = substr($str, strpos($str, $findme) + 4);

//if (IS_SAE) {
//    echo "this is sae platform";
//} else {
//    echo "this is local";
//}
//echo getUserBrower();
//user/del/id/100/name/liming
//echo getUrl("user/del",array("id"=>"100","name"=>"liming"));
//echo getUrl("Public/login",array("id"=>"100","name"=>"liming"));
//echo C("PAGE_LISTROWS");
//$limit = "Limit 2, 1";
//echo $limit;
//echo ltrim(str_replace("Limit", "", $limit));
//$str = "djskf到时候电话健康的生活就大客户";
//echo $str;
//echo "<br/>";
//echo formatStr($str, 16);
    }

    public function test_show() {
        header("Content-type:text/html;charset=utf-8;");
        echo 3 % 2;
    }

    /**
     * 测试系统自带分页
     * 
     */
    public function testFenye1() {
        $User = M('User'); // 实例化User对象
        import('ORG.Util.Page'); // 导入分页类
        $count = $User->count(); // 查询满足要求的总记录数
        $Page = new Page($count, 3); // 实例化分页类 传入总记录数和每页显示的记录数
        $Page->setConfig('header', '个会员');
        $show = $Page->show(); // 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $User->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->display("test"); // 输出模板
    }

    /**
     * 测试系统自带分页
     * 
     */
    public function testFenye() {
        $User = M('User'); // 实例化User对象
        import('@.Components.PageBootstrap'); // 导入分页类
        $count = $User->count(); // 查询满足要求的总记录数
        //$Page = new PageBootstrap($count, 1,array("p1"=>"a","p2"=>"b")); // 实例化分页类 传入总记录数和每页显示的记录数
        $Page = new PageBootstrap($count, 1, array("p1" => "a", "p2" => "b")); // 实例化分页类 传入总记录数和每页显示的记录数  带参数
        $Page->setConfig("prev", "上一页");
        $Page->setConfig("next", "下一页");
        $show = $Page->show(); // 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $user_list = $User->limit($Page->firstRow . ',' . $Page->listRows)->select();
        //show_msg_not_exit($list);
        $this->assign('user_list', $user_list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        //show_msg($Page);
        $this->display("test"); // 输出模板
    }

    /**
     * 测试获用户个人信息
     */
    public function testGetUserInfo() {
        D("User")->getUserInfo(1);
    }

    /**
     * 测试获得用户设备信息
     */
    public function testShebei() {
        echo getUserOS();
        echo "<br/>";
        echo getUserBrower();
    }

    /**
     * 连接查询3张表列出用户信息
     */
    public function testDuobiaochaxun() {
//表名
        $table_user = C("DB_PREFIX") . 'user';
        $table_role = C("DB_PREFIX") . 'role';
        $table_dept = C("DB_PREFIX") . 'dept';
        $table_organization = C("DB_PREFIX") . 'organization';
//连接名
        $join_user = $table_user . ' as user ';
        $join_role = $table_role . ' as role ON user.role_id = role.id';
        $join_dept = $table_dept . ' as dept ON user.dept_id = dept.id';
        $join_organization = $table_organization . ' as organization ON user.organization_id = organization.id';
//要查询的字段
        $field = "user.*,role.remark as role_remark,dept.name as dept_name,organization.name as organization_name";
        $user = M()->table($join_user)->join($join_role)->join($join_dept)->join($join_organization)->field($field)->select();
        show_msg($user);
    }

    /**
     * 生成验证码
     */
    public function testVerifyImg() {
        import('ORG.Util.Image');
        echo Image::buildImageVerify(4, 1, 'png', 75, 28, 'verify');
    }

    public function test() {
        $firstday = date("Y-m-d 00:00:00");
        $lastday = date("Y-m-d 00:00:00", strtotime("$firstday -7 day"));
        echo $firstday;
        echo $lastday;
        die();
        //echo strtotime("00:00:00");
        
//        $today = date("Y-m-d H:i:s");
//        $yestoday=date("Y-m-d H:i:s", strtotime("$today-2 day"));
//        echo $today.'---';
//        echo $yestoday;
//        echo strtotime($yestoday);
        die();
//        $today = date("Y-m-d");
//        $firstday = date("Y-m-01 00:00:00", strtotime($today));
//        echo $firstday;
//        echo "<br/>";
//        echo strtotime($firstday);
//        echo "<br/>";
//        exit();
        $date = date("Y-m-d");
        echo "<br/>";
        echo $date;
        echo "<br/>";
        $firstday = date("Y-m-01 00:00:00", strtotime($date));
        $lastday = date("Y-m-d", strtotime("$firstday +1 month -1 day"));
        echo $firstday;
        echo "<br/>222";
        echo $lastday;
        echo "<br/>11";
        echo formatTime(time(), "Y-m-d H:i:s", 1);
//        $str="{'resultRespond':{'result':'1','errorCode':'','errorMessage':'','userName':'test','userId':'3','userToken':'ed8b8b13729a4c99ea9b11d969a0dcec'},'size':'156'} ";
//        $size = mb_strlen($str, 'UTF-8');
//        echo $size;
//        echo "<br/>";
//$img = file_get_contents("D:/1.jpg");
//file_put_contents("D:/2.jpg", $img);
        echo $t1 = strtotime("2014-07-16 22:40");
        echo "</br>";
        echo $t2 = strtotime("2014-07-12 22:30");
        echo "</br>";
        echo "</br>";
        $date = floor(($t1 - $t2) / 86400);
        $hour = floor(($t1 - $t2) % 86400 / 3600);
        $minute = floor((floor(($t1 - $t2) % 86400) % 3600) / 60);
        $overtime = $date . "天" . $hour . "小时" . $minute . "分";
        echo $overtime;
//        echo formatTime($t1-$t2,"H:i",2);
        echo "</br>";
//        echo strtotime("2014-07-12 13:45");
//        echo "</br>";
//        echo strtotime("12:09");
//        echo "</br>";
//        echo strtotime("14:00");
//        echo "</br>";
//        echo strtotime("02:11");
//        echo "</br>";
//        $real_finish_time="1405102260";
//        $end_time="1405123200";
//        echo formatTime($real_finish_time, "H:i");
//        echo "</br>";
//        echo formatTime($end_time, "H:i");
    }

}
