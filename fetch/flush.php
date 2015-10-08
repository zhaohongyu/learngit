<?php
/**
 * 关闭nginx的gzip off;
 * 循环输出
 */
set_time_limit(0);
ob_end_clean();
ob_implicit_flush(1);
echo '<div style="background-color:green;overflow:scroll;width:800px;height:400px;margin:0 auto;">';
for ($i = 0; $i < 350; $i++) {
    echo "<h2 style='color:red;'>" . $i . "</h2><br />\n";
    echo str_repeat(' ', 1024 * 4);
    sleep(1);
}