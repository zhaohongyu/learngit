<?php

require_once "./function.php";

/*
 * 测试 ord 函数  返回第一个字符串的ASCII值
 */

function testord() {
    $str = "\n";
    var_dump(ord($str));
    if (ord($str) == 10) {
        echo "The first character of \$str is a line feed.\n";
    }
}

//testord();
//D:\wnmp\www\orbeus\learngit\test.txt
//echo realpath("test.txt");
//echo "<br/>";
//echo PHP_INT_SIZE . ";";//4;
//echo PHP_INT_MAX;//2147483647
//die();
require_once "./config.php";
require_once "./cass_helper.php";

$cass = new CassandraIO;

//测试查询数据
$query = "SELECT * FROM rekome_wordnet.concept_map WHERE word='papaya';";
//测试insert
//$query = "INSERT INTO rekome_wordnet.concept_map (word, mapped_word) values('test3','测试3');";
if (!$cass->query($query, $error, $res)) {
    die($error);
}
show_msg($res);
