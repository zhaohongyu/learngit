<?php
error_reporting(0);
//ajax调用实时刷新
if ($_GET['act'] == "rt") {
    $arr = array(
        "data1" => "data1",
        "data2" => "data2",
        "data3" => "data3",
        "data4" => "data4",
    );
    $jarr = json_encode($arr);
    echo $_GET['callback'], '(', $jarr, ')';
    exit;
}
?>
<script language="JavaScript" type="text/javascript" src="http://lib.sinaapp.com/js/jquery/1.7/jquery.min.js"></script>
<script type="text/javascript">
<!--
    $(document).ready(function() {
        getJSONData();
    });
    function getJSONData()
    {
        setTimeout("getJSONData()", 1000);
        $.getJSON('?act=rt&callback=?', displayData);
    }
    function displayData(dataJSON)
    {
        console.log(dataJSON);
        //document.write(dataJSON);
    }
-->
</script>