<?php

include_once('common.php');

/**
 * 导出数据库数据到excel
 */
function exportExcel() {
    //查询所有记录
    $sql = "select * from t_goods_amazon";
    /* check connection */
    $record_list = M()->select($sql);
    //show_msg($record_list);
    header("content-type:text/html;charset=utf-8");
    /** Error reporting */
    error_reporting(E_ALL);
    /** PHPExcel */
    require_once './PHPExcel/PHPExcel.php';
    /** PHPExcel_Writer_Excel2003用于创建xls文件 */
    require_once './PHPExcel/PHPExcel/Writer/Excel5.php';
    // Create new PHPExcel object
    $objPHPExcel = new PHPExcel();
    // Set properties
    $objPHPExcel->getProperties()->setCreator('hyzhao');
    $objPHPExcel->getProperties()->setLastModifiedBy('hyzhao');
    //因为乱码,所以还是注销掉了!!!2014-7-10 22:27:20
    //$objPHPExcel->getProperties()->setTitle("记录");
    //$objPHPExcel->getProperties()->setSubject("记录");
    //$objPHPExcel->getProperties()->setDescription("记录");
    // Add some data
    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->SetCellValue('A1', '商品id');
    $objPHPExcel->getActiveSheet()->SetCellValue('B1', '商品名称');
    $objPHPExcel->getActiveSheet()->SetCellValue('C1', '商品价格');
    $objPHPExcel->getActiveSheet()->SetCellValue('D1', '商品价格-美元');
    $objPHPExcel->getActiveSheet()->SetCellValue('E1', '品牌');
    $objPHPExcel->getActiveSheet()->SetCellValue('F1', '国家');
    $objPHPExcel->getActiveSheet()->SetCellValue('G1', '商品链接');
    // Set column widths
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(35);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(35);
    foreach ($record_list as $k => $v) {
        $objPHPExcel->getActiveSheet()->SetCellValue('A' . ($k + 2), $v["id"]);
        $objPHPExcel->getActiveSheet()->SetCellValue('B' . ($k + 2), html_entity_decode($v["goods_name"]));
        $objPHPExcel->getActiveSheet()->SetCellValue('C' . ($k + 2), $v["goods_price"]);
        $objPHPExcel->getActiveSheet()->SetCellValue('D' . ($k + 2), $v["goods_price_usd"]);
        $objPHPExcel->getActiveSheet()->SetCellValue('E' . ($k + 2), $v["band"]);
        $objPHPExcel->getActiveSheet()->SetCellValue('F' . ($k + 2), $v["country"]);
        $objPHPExcel->getActiveSheet()->SetCellValue('G' . ($k + 2), $v["href"]);
    }
    $objPHPExcel->getActiveSheet()->setTitle('recordList');
    // Save Excel 2007 file
    //$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
    //$objWriter->save(str_replace('.php', '.xls', __FILE__));
    //ob_end_clean(); //清除缓冲区,避免乱码
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control:must-revalidate,post-check=0,pre-check=0");
    header("Content-Type:application/force-download");
    header("Content-Type:application/vnd.ms-execl");
    header("Content-Type:application/octet-stream");
    header("Content-Type:application/download");
    header("Content-Disposition:attachment;filename=amazon.xls");
    header("Content-Transfer-Encoding:binary");
    $objWriter->save("php://output");
}
exportExcel();
