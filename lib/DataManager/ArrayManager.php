<?php
/*
    Condorcet PHP Class, with Schulze Methods and others !

    Version: 0.95

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

namespace Condorcet\DataManager;


abstract class ArrayManager implements \Iterator,\ArrayAccess
{
    // Implement ArrayAccess
    public function offsetSet($offset, $value) {}

    public function offsetExists($offset) {
        return isset($this->_Data[$offset]) ? true : false;
    }

    public function offsetUnset($offset) {}

    public function offsetGet($offset) {
        return isset($this->_Data[$offset]) ? $this->_Data[$offset] : null;
    }


    // Implement Iterator
    private $valid = true;

    public function rewind() {
        reset($this->_Data);
        $this->valid = true;
    }

    public function current() {
        return $this->_Data[$this->key()];
    }

    public function key() {
        return key($this->_Data);
    }

    public function next() {
        if (next($this->_Data) === false) :
            $this->valid = false;
        endif;
    }

    public function valid() {
        return $this->valid;
    }   


        //////

    protected $_Data = [];
}
