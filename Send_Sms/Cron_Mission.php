<?php

class Cron_Mission {

    public function __construct() {
        
    }

    /**
     * 进程数
     * @var int
     */
    private $thread_counts = 2;

    /**
     * 调用任务
     * # crontab –e
     * # 1 * * * * /usr/local/php/bin/php /home/www/test/testci/index.php Cron_Mission send_sms
     */
    public function send_sms() {
        for ($i = 0; $i < $this->thread_counts; $i++) {
            $cmd = PHP_PATH . " " . INDEX_PATH . " Send_Msg send_sms &";
            exec($cmd);
        }
    }

}
