<?php

set_time_limit(0);
//引入PHPMailer
require_once "./PHPMailer/class.phpmailer.php";

/**
 * 发送邮件
 * @param $sendTo 收件人邮箱
 * @param $subject 发件主题
 * @param $body 发送内容
 * @param $attachment 发送附件
 * @param $attachment_name 发送附件的名字,注意后缀
 */
function _email($sendTo, $subject, $body, $attachment = '', $attachment_name = '') {
    //Create object of PHPMailer
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->SMTPAuth = true;
    $mail->Host = "smtp.exmail.qq.com"; //发件服务器地址
    $mail->Port = "25"; //端口
    $mail->Username = "hyzhao@phototime.com"; //发件人邮箱用户名
    $mail->Password = "123@Exmail.qq.com"; //发件人邮箱密码
    $mail->SetFrom($mail->Username, "赵洪禹");
    $mail->Subject = $subject;
    $mail->CharSet = "UTF-8";   // 这里指定字符集！    
    $mail->Encoding = "base64";
    $mail->MsgHTML($body);
    $mail->AddAttachment($attachment, $attachment_name); //附件的路径和附件名称
    if (is_string($sendTo)) {
        $mail->AddAddress($sendTo);
    } else if (is_array($sendTo)) {
        foreach ($sendTo as $receive) {
            $mail->AddAddress($receive[0], $receive[1]);
        }
    }
    if (!$mail->Send()) {
        return $mail->ErrorInfo;
    } else {
        return 1;
    }
}

/*
 * 邮件模板
 * 2015年6月6日 02:45:41
 */

function _template($msg_arr) {
    $company = 'Orbeus';
    $year = date('Y');
    $end = $company . ' ' . date('Y-m-d');
    $template = <<<TEMPLATE
<meta charset="UTF-8">
<div>
    <div style="font-size:14px;font-family:Verdana,&quot;宋体&quot;,Helvetica,sans-serif;line-height:1.66;padding:8px 10px;margin:0;overflow:auto">
        <table width="610" border="0" align="center" cellspacing="0">
            <tbody>
                <tr>
                    <td bgcolor="#f0f0f0" style="height:5px"></td>
                </tr>
                <tr>
                    <td bgcolor="#f0f0f0">
                        <table width="600" border="0" align="center" cellpadding="0" cellspacing="0" style=" border:1px solid #a1afb8">
                            <tbody>
                                <tr>
                                    <td bgcolor="#FFFFFF" style="font-size:14px; line-height:24px;">
                                        <table border="0" cellspacing="0" cellpadding="0" width="520" align="center">
                                            <tbody>
                                                <tr>
                                                    <td style=" text-indent:2em;font-size:14px; line-height:30px">
                                                        {$msg_arr[0]}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style=" text-indent:2em;font-size:14px; line-height:30px">
                                                        {$msg_arr[1]}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style=" text-indent:2em;font-size:14px; line-height:30px">
                                                        {$end}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td height="20"></td>
                                                </tr>
                                                <tr>
                                                    <td height="10"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td bgcolor="#FFFFFF" style=" font-size:12px; padding:10px 25px;text-align:right;  "></td>
                                </tr>
                                <tr>
                                    <td bgcolor="#FFFFFF" style=" font-size:12px; padding:5px 20px; text-align:right; color:#999;  ">(注：系统邮件，请勿回复。)</td>
                                </tr>
                                <tr>
                                    <td bgcolor="#FFFFFF" style="font-size:12px; padding:20px; text-align:center; line-height:22px; color:#999">
                                        Copyright © 1970-{$year} {$company} All Rights Reserved
                                        <br>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td bgcolor="#f0f0f0" style=" height:5px"></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
TEMPLATE;
    return $template;
}

/**
 * 发送邮件
 * @param type $msg_arr 错误消息
 * $msg_arr[0] 错误消息提示
 * $msg_arr[1] 错误消息内容
 */
function real_send_mail($msg_arr) {
    //收件人
    $sendToArr = array(
        array("khan@orbe.us", 'Kuang Han'),
        array("tqliu@orbe.us", 'Tianqiang Liu'),
        array("jwang@phototime.com", 'silen'),
        array("hyzhao@phototime.com", '赵洪禹'),
    );
    $subject = "Cassandra报警邮件--" . $GLOBALS['CASSANDRA_HOST'];
    $body = _template($msg_arr);
    $res = _email($sendToArr, $subject, $body);
    if ($res != 1) {
        die("发送邮件失败!");
    }
}

/*
 * 监测cassandra是否能正常提供服务
 * 2015年6月6日 01:14:28
 */
require_once "./config.php";
require_once "./cass_helper.php";

try {
    $cass = new CassandraIO;
} catch (Exception $exc) {
    $msg_arr[] = $GLOBALS['CASSANDRA_HOST'] . "服务器上的Cassandra出现故障,无法连接,错误信息是";
    $msg = iconv('GBK', 'UTF-8', $exc->getMessage());
    $msg_arr[] = $msg;
    real_send_mail($msg_arr);
    die($msg);
}

//查询中英文词库映射表
$query = "SELECT * FROM rekome_wordnet.concept_map where word='dance';";
$error = '';
if (!$cass->query($query, $error, $result)) {
    $msg_arr[] = $GLOBALS['CASSANDRA_HOST'] . "服务器上的Cassandra出现故障,错误信息是";
    $msg_arr[] = $error;
    real_send_mail($msg_arr);
    die($error);
}