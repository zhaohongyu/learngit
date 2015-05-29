<?php

/**
 * 插入数据
 * rekome_wordnet.concept_map
 */
include_once "./config.php";
require_once "./cass_helper.php";
require_once './PHPExcel/PHPExcel.php'; //修改为自己的目录
set_time_limit(0);
ini_set('memory_limit', '256M');
$t1 = microtime(true);
$filename = "translate.xls";
$objReader = PHPExcel_IOFactory::createReaderForFile($filename);
$objPHPExcel = $objReader->load($filename);
$objPHPExcel->setActiveSheetIndex(0);
$objWorksheet = $objPHPExcel->getActiveSheet();
$i = 0;
$query_arr_tmp = array();
foreach ($objWorksheet->getRowIterator() as $row) {
    $cellIterator = $row->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(false);
    $j = 0;
    $ins_value = '';
    $tmp_arr = array();
    foreach ($cellIterator as $cell) {
        //限制只读取A列和B列
        if ($j == 0) {
            //转换小写去除空格
            $tmp_arr[0] = trim(strtolower($cell->getValue()));
        } else if ($j == 1) {
            //去除空格
            $tmp_arr[1] = trim($cell->getValue());
        }
        $j++;
    }

    if (empty($tmp_arr[0])) {
        break;
    }
    //去除最后一个逗号
    $ins_tmp_value = "'" . $tmp_arr[0] . "','" . $tmp_arr[1] . "'";
    $query = "insert into rekome_wordnet.concept_map (word,mapped_word) values ($ins_tmp_value)\r\n";
    $query_arr_tmp[] = $query;
    $i++;
    unset($j);
    unset($ins_tmp_value);
    unset($tmp_arr);
    unset($query);
}

$batch_query = make_batch_query($query_arr_tmp);
echo "=============================================================\r\n";
echo "===========it will insert  " . count($query_arr_tmp) . " records.....\r\n";
echo "=============================================================\r\n";
//echo $batch_query;
$cass = new CassandraIO;
$error = '';
if (!$cass->query($batch_query, $error)) {
    echo $error . "\r\n";
} else {
    echo "done!.\r\n";
}
$t2 = microtime(true);
$td = ($t2 - $t1) * 1000;
echo "use t = " . $td . " ms \r\n";
