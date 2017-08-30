<?php

// 生成mysql数据字典
header("Content-type: text/html; charset=utf-8");

// 配置数据库
$host     = "192.168.100.60";
$username = "root";
$password = "";
$database = isset($_GET['database']) ? $_GET['database'] : "event_dove_admin";

$tables = [];
$title  = "$database-数据字典";

// PDO
$pdo       = new PDO("mysql:host={$host};dbname={$database}", $username, $password);
$statement = $pdo->query("show tables");

// 组装表名数组
while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
    $tables [] ['TABLE_NAME'] = $row["Tables_in_{$database}"];
}

// 循环取得所有表的备注及表中列消息
foreach ($tables as $k => $v) {
    $sql1         = 'SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE ';
    $sql1         .= "table_name = '{$v['TABLE_NAME']}'  AND table_schema = '{$database}'";
    $table_result = $pdo->query($sql1);
    while ($t = $table_result->fetch(PDO::FETCH_ASSOC)) {
        $tables [$k] ['TABLE_COMMENT'] = $t ['TABLE_COMMENT'];
    }

    $sql2         = 'SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE ';
    $sql2         .= "table_name = '{$v['TABLE_NAME']}' AND table_schema = '{$database}'";
    $fields       = array();
    $field_result = $pdo->query($sql2);
    while ($t = $field_result->fetch(PDO::FETCH_ASSOC)) {
        $fields[] = $t;
    }
    $tables [$k] ['COLUMN'] = $fields;
}

$dictionary_html_1
    = <<<html_1
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>{$title}</title>
        <link rel="stylesheet" href="http://apps.bdimg.com/libs/bootstrap/3.3.4/css/bootstrap.min.css">
        <script src="http://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
        <script src="http://apps.bdimg.com/libs/bootstrap/3.3.4/js/bootstrap.min.js"></script>
        <style type="text/css">
            ul.nav-tabs{
                width: 280px;
                margin-top: 20px;
                border-radius: 4px;
                border: 1px solid #ddd;
                box-shadow: 0 1px 4px rgba(0, 0, 0, 0.067);
            }
            ul.nav-tabs li{
                margin: 0;
                border-top: 1px solid #ddd;
            }
            ul.nav-tabs li:first-child{
                border-top: none;
            }
            ul.nav-tabs li a{
                margin: 0;
                padding: 8px 16px;
                border-radius: 0;
            }
            ul.nav-tabs li.active a, ul.nav-tabs li.active a:hover{
                color: #fff;
                background: #0088cc;
                border: 1px solid #0088cc;
            }
            ul.nav-tabs li:first-child a{
                border-radius: 4px 4px 0 0;
            }
            ul.nav-tabs li:last-child a{
                border-radius: 0 0 4px 4px;
            }
            ul.nav-tabs.affix{
                top: 30px; /* Set the top position of pinned element */
            }
        </style>
    </head>
    <body data-spy="scroll" data-target="#myScrollspy">
    <div class="container">
    <div class="row">
        <div class="col-xs-3" id="myScrollspy">
            <ul class="nav nav-tabs nav-stacked affix" data-spy="affix" data-offset-top="125">
html_1;

foreach ($tables as $k => $v) {
    $section_title     = $v['TABLE_NAME'];
    $dictionary_html_1 .= "<li><a href='#{$section_title}'>{$section_title}</a></li>";
}

$dictionary_html_2
    = <<<html_2
            </ul>
       </div>
       <div class='col-xs-9'>
html_2;

foreach ($tables as $k => $v) {
    $section_title = $v['TABLE_NAME'];
    $table_comment = $v['TABLE_COMMENT'];

    $html = "";
    $html .= "<table class='table table-responsive table-bordered table-hover table-condensed table-striped'>";
    $html .= "<caption>{$table_comment}</caption>";
    $html .= "<tbody><tr><th>字段名</th><th>数据类型</th><th>默认值</th> <th>允许非空</th> <th>自动递增</th><th>备注</th></tr>";

    foreach ($v ['COLUMN'] as $f) {
        $is_auto_increment = ($f ['EXTRA'] === 'auto_increment') ? '是' : '';
        $html              .= "<tr>";
        $html              .= "    <td>{$f ['COLUMN_NAME']}</td>";
        $html              .= "    <td>{$f ['COLUMN_TYPE']}</td>";
        $html              .= "    <td>{$f ['COLUMN_DEFAULT']}</td>";
        $html              .= "    <td>{$f ['IS_NULLABLE']}</td>";
        $html              .= "    <td>{$is_auto_increment}</td>";
        $html              .= "    <td>{$f ['COLUMN_COMMENT']}</td>";
        $html              .= '</tr>';
    }
    $html .= '</tbody></table>';

    $dictionary_html_2
        .= <<<html_2
            <h2 id="{$section_title}">{$section_title}</h2>
            {$html}
            <hr>
html_2;

}

$dictionary_html_3
    = <<<html_3
                </div>
            </div>
        </div>
    </body>
</html>
html_3;

echo $dictionary_html_1 . $dictionary_html_2 . $dictionary_html_3;
