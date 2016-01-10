##短信发送

###目录
Send_Sms/

├── Curl/  
├── data/               短信接收人和短信接口地址
├── Cron_Mission.php    定时任务调用脚本
├── Send_Msg.php        发送短信文件
├── Socketlog.php       DEBUG
├── define.php          常量定义
├── index.php           入口文件

#使用注意
1.使用前请修改define.php中的PHP_PATH改成环境对应的路径，日志路径默认使用当前文件夹
2.data目录中的数据可以自定义添加，按照规定格式即可

#定时发送，添加到定时任务中的脚本，请求改对应的路径
D:/wamp/bin/php/php5.5.12/php D:/wnmp/www/Send_Sms/index.php Cron_Mission send_sms &


#测试接口
带参数测试接口 手机号使用%s代替 要使用get方式请求时在最后面加上method=get否则默认使用post请求
D:/wamp/bin/php/php5.5.12/php D:/wnmp/www/Send_Sms/index.php Send_Msg test "http://linking.baidu.com/finance/msg/sendVerifyCode?phoneNum=%s&method=get" &

#浏览器请求方式 c 控制器 m 方法 param 请求参数
http://mywww.com/Send_Sms/index.php?c=Send_Msg&m=test&param=http://linking.baidu.com/finance/msg/sendVerifyCode?phoneNum=%s&method=get


#测试群发
D:/wamp/bin/php/php5.5.12/php D:/wnmp/www/Send_Sms/index.php Send_Msg send_sms &