<?php

/**
 * Created by PhpStorm.
 * User: zhaohongyu
 * Date: 16/4/22
 * Time: 下午6:30
 */
class TestArrayAccess implements ArrayAccess, IteratorAggregate {

    public $title;
    public $author;
    public $category;

    /**
     * TestArrayAccess constructor.
     *
     * @param $title
     * @param $author
     * @param $category
     */
    public function __construct($title, $author, $category) {
        $this->title    = $title;
        $this->author   = $author;
        $this->category = $category;
    }

    /**
     * 当前下标对应的数组元素是否存在
     */
    public function offsetExists($offset) {
        return array_key_exists($offset, get_object_vars($this));
    }

    /**
     * 获取当前下标对应的数组元素
     */
    public function offsetGet($offset) {
        if ($this->offsetExists($offset)) {
            return $this->{$offset};
        }
    }

    /**
     * 设置对应下标文件的数组元素
     */
    public function offsetSet($offset, $value) {
        if ($this->offsetExists($offset)) {
            $this->{$offset} = $value;
        }
    }

    /**
     * 删除对应下标文件的数组元素
     */
    public function offsetUnset($offset) {
        if ($this->offsetExists($offset)) {
            unset($this->{$offset});
        }
    }

    public function getIterator() {
        return new ArrayIterator($this);
    }

}


$A = new TestArrayAccess('SPL Rocks', 'Joe Bloggs', 'PHP');

// Check what it looks like
echo 'Initial State:<pre>';
print_r($A);
echo '</pre>';

// Change the title using array syntax
$A['title'] = 'SPL_really_ rocks';

// Try setting a non existent property (ignored)
$A['not found'] = 1;

// Unset the author field
unset($A['author']);

// Check what it looks like again
echo 'Final State:<pre>';
print_r($A);
echo '</pre>';


$B = new TestArrayAccess('SPL Rocks', 'Joe Bloggs', 'PHP');

// Loop (getIterator will be called automatically)
echo 'Looping with foreach:<pre>';
foreach ($B as $field => $value) {
    echo "$field : $value<br>";
}
echo '</pre>';

// Get the size of the iterator (see how many properties are left)
echo "Object has " . sizeof($B->getIterator()) . " elements";
