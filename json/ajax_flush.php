<?php
error_reporting(0);
//ajax调用实时刷新
if ($_GET['act'] == "rt") {
    $arr = array(
        "time" => time(),
        "rand" => rand(1, 1000),
    );
    $jarr = json_encode($arr);
    echo $_GET['callback'] . '(' . $jarr . ')';
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
        var res = "time=" + dataJSON.time + " rand=" + dataJSON.rand+"<br/><div style='height:10px;'></div>";
        var scrollHeight=window.document.body.scrollHeight;
        $('body').scrollTop(scrollHeight);
        document.write(res);
    }
-->
</script>