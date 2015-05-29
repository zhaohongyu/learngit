<?php
require_once "./function.php";
require_once './PHPExcel/PHPExcel.php'; //修改为自己的目录
//echo '<p>TEST PHPExcel 1.8.0: read xlsx file</p>';
$filename = "translate.xls";
$objReader = PHPExcel_IOFactory::createReaderForFile($filename);
$objPHPExcel = $objReader->load($filename);
$objPHPExcel->setActiveSheetIndex(0);
//$date = $objPHPExcel->getActiveSheet()->getCell('A1')->getValue();
?>

<table id="table_id" border="1px;">
    <?php
    $objWorksheet = $objPHPExcel->getActiveSheet();
    $i = 0;
    foreach ($objWorksheet->getRowIterator() as $row) {
        ?>
        <tr>
            <?php
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
            if ($i == 100) {
                break;
            }
            if ($i == 0) {
                echo '<thead>';
            }
            $j = 0;
            foreach ($cellIterator as $cell) {
                //限制读取两列
                if ($j == 0 || $j == 1 || $j == 3) {
                    echo '<td>' . $cell->getValue() . '</td>';
                }
                $j++;
            }
            if ($i == 0) {
                echo '</thead>';
            }
            $i++;
            ?>
        </tr>
        <?php
    }
    ?>
</table>