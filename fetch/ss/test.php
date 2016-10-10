<?php

require_once('./myclass/ss_fast.php');

$fetch_url   = "https://www.ss-fast.com/ucenter/?act=free_plan";
$user_cookie = "ss_secret=6bbe%2B%2BSA8RBmuwBNmtdQe1OiuX3Cf7iOOSR3BDgCGzVnhmvvU9%2FDIUhR22rNNUWGbVmIzR57%2BH7O3jJnIjFH6miWR3PB1e2nny7179HQg9%2FRRlHKZE16HRRrexoTdH6oKHb6x3MGaS1gdF%2B8dbqS; PHPSESSID=fd0b2794949b7076a8b0103cbde59454";

$ss_fast = new ss_fast($fetch_url, $user_cookie);
$SsInfo  = $ss_fast->getSsInfo();

var_dump($SsInfo);