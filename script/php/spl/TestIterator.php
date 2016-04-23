<?php

/**
 * Created by PhpStorm.
 * User: zhaohongyu
 * Date: 16/4/22
 * Time: 下午5:14
 */
class TestIterator implements Iterator {

    private $arr = array();

    public function __construct($arr) {
        $this->arr = $arr;
    }

    private $valid = false;

    /**
     * 返回当前元素
     */
    public function current() {
        return current($this->arr);
    }

    /**
     * 移动到下一个元素
     */
    public function next() {
        return $this->valid = (false !== next($this->arr));
    }

    /**
     * 返回当前元素数组的key
     */
    public function key() {
        return key($this->arr);
    }

    /**
     * 检查当前元素位置是否有效
     */
    public function valid() {
        return $this->valid;
    }

    /**
     * 重置指针到数组的第一个元素
     */
    public function rewind() {
        return $this->valid = (false !== reset($this->arr));
    }
}

$arr1 = array(
    'a' => 'aaa11',
    'b' => 'bbb22',
);
$test = new TestIterator($arr1);

foreach ($test as $k => $v) {
    echo $k . '---' . $v . '<br/>';
}

$test->rewind();
while ($test->valid()) {

    echo $test->key() . ": " . $test->current() . "
";
    $test->next();

}




