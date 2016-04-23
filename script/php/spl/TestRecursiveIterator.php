<?php

/**
 * Created by PhpStorm.
 * User: zhaohongyu
 * Date: 16/4/23
 * Time: 下午12:02
 */
class TestRecursiveIterator implements RecursiveIterator, SeekableIterator {

    private $_arr;

    public $valid;

    /**
     * TestRecursiveIterator constructor.
     *
     * @param $arr
     */
    public function __construct($arr) {
        $this->_arr = $arr;
    }

    public function current() {
        return current($this->_arr);
    }

    public function next() {
        return $this->valid = (false !== next($this->_arr));
    }

    public function key() {
        return key($this->_arr);
    }

    public function valid() {
        return $this->valid;
    }

    public function rewind() {
        return $this->valid = (false !== reset($this->_arr));
    }

    public function hasChildren() {
        return is_array($this->current());
    }

    public function getChildren() {
        echo '<pre>';
        $child = $this->current();
        var_dump($child);
        echo '</pre>';
        return $child;
    }

    public function seek($position) {
        $this->rewind();
        $index = 0;

        while ($index < $position && $this->valid()) {
            $this->next();
            $index++;
        }

        if (!$this->valid()) {
            throw new OutOfBoundsException('Invalid position');
        }
        return $this->_arr[$position];
    }

}

$arr = array(0, 88, 2, 3, 4, 5 => array(10, 20, 30), 6, 7, 8, 9 => array(1, 2, 3));
$mri = new TestRecursiveIterator($arr);

//echo $mri->seek(0);

//foreach ($mri as $c => $v) {
//    if ($mri->hasChildren()) {
//        echo "$c has children: <br />";
//        $mri->getChildren();
//    } else {
//        echo "$v <br />";
//    }
//
//}

// a simple foreach() to traverse the SPL class names
//foreach(spl_classes() as $key=>$value)
//{
//    echo '<pre>';
//    echo $key.' -&gt; '.$value.'<br />';
//    echo '</pre>';
//}

//try {
//    echo '<pre>';
//    /*** class create new DirectoryIterator Object ***/
//    foreach (new DirectoryIterator('./') as $Item) {
//        var_dump($Item) . '<br />';
//    }
//} /*** if an exception is thrown, catch it here ***/
//catch (Exception $e) {
//    echo 'No files Found!<br />';
//}

//$it = new DirectoryIterator('./');
//while ($it->valid()) {
//    /*** check if value is a directory ***/
//    if ($it->isDir()) {
//        /*** echo the key and current value ***/
//        echo $it->key() . ' -- ' . $it->current() . '<br />';
//    }
//    /*** move to the next iteration ***/
//    $it->next();
//}

///*** a simple array ***/
//$array = array('koala', 'kangaroo', 'wombat', 'wallaby', 'emu', 'kiwi', 'kookaburra', 'platypus');
//
///*** create the array object ***/
//$arrayObj = new ArrayObject($array);
//
///*** iterate over the array ***/
//for ($iterator = $arrayObj->getIterator();
//    /*** check if valid ***/
//     $iterator->valid();
//    /*** move to the next array member ***/
//     $iterator->next()) {
//    /*** output the key and current array value ***/
//    echo $iterator->key() . ' => ' . $iterator->current() . '<br />';
//}
//
//$arrayObj->append('dingo');
//echo $arrayObj->count();
//$arrayObj->offsetUnset(5);
//if ($arrayObj->offsetExists(3))
//{
//    echo 'Offset Exists<br />';
//}
//$arrayObj->offsetSet(5, "galah");
//echo $arrayObj->offsetGet(4);


//$array = array(
//    array('name' => 'butch', 'sex' => 'm', 'breed' => 'boxer'),
//    array('name' => 'fido', 'sex' => 'm', 'breed' => 'doberman'),
//    array('name' => 'girly', 'sex' => 'f', 'breed' => 'poodle'),
//);
//
//foreach (new RecursiveIteratorIterator(new RecursiveArrayIterator($array)) as $key => $value) {
//    echo $key . ' -- ' . $value . '<br />';
//}


class fibonacci_sequence {


    public function fib1($n) {
        if ($n < 1) {
            return -1;
        }
        if ($n == 1 || $n == 2) {
            return 1;
        }
        return $this->fib1($n - 1) + $this->fib1($n - 2);
    }

    public function fib2($n) {
        $arr[1] = $arr[2] = 1;
        for ($i = 3; $i <= $n; $i++) {
            $arr[$i] = $arr[$i - 1] + $arr[$i - 2];
        }
        return $arr[$n];
    }

}


$test = new fibonacci_sequence();

echo $test->fib1(6);
echo $test->fib2(6);


$arr[1] = 1;
for ($i = 2; $i < 100; $i++) {
    $arr[$i] = $arr[$i - 1] + $arr[$i - 2];
}
echo join(",", $arr);//将数组合并为一个字符串输出