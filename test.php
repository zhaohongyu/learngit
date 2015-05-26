<?php

function testord() {
    $str = "\n";
    var_dump(ord($str));
    if (ord($str) == 10) {
        echo "The first character of \$str is a line feed.\n";
    }
}

testord();

?> 