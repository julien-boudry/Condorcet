<?php
/*
    Condorcet PHP Class, with Schulze Methods and others !

    Version: 0.97

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

namespace Condorcet\DataManager;

abstract class ArrayManager implements \ArrayAccess,\Countable,\Iterator
{
        //////

    protected $_Container = [];
    protected $_counter = 0;


    // Implement ArrayAccess
    public function offsetSet($offset, $value) {
        if ($offset === null) :
            $this->_Container[] = $value;
            ++$this->_counter;
        else :
            if (!$this->keyExist($offset)) :
                ++$this->_counter;
            endif;

            $this->_Container[$offset] = $value;
        endif;
    }

    public function offsetExists($offset) {
        return isset($this->_Container[$offset]);
    }

    public function offsetUnset($offset) {
        if ($this->keyExist($offset)) :
            unset($this->_Container[$offset]);
            --$this->_counter;
        endif;
    }

    public function offsetGet($offset) {
        return isset($this->_Container[$offset]) ? $this->_Container[$offset] : null;
    }


    // Implement Iterator
    protected $valid = true;

    public function rewind() {
        reset($this->_Container);
        $this->valid = true;
    }

    public function current() {
        return $this->_Container[$this->key()];
    }

    public function key() {
        return key($this->_Container);
    }

    public function next() {
        if (next($this->_Container) === false) :
            $this->valid = false;
        endif;
    }

    public function valid() {
        return $this->valid;
    }


    // Implement Countable
    public function count () {
        return $this->_counter;
    }

    // Array Methods

    public function getArray () {
        return $this->_Container;
    }

    public function keyExist ($offset) {
        return array_key_exists($offset, $this->_Container);
    }

    public function isIn ($needle, $strict = true) {
        return in_array($needle, $this->_Container, $strict);
    }

}
