<?php
/*
    Condorcet PHP Class, with Schulze Methods and others !

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

namespace Condorcet\DataManager;

use Condorcet\DataManager\BDD\BddHandler;
use Condorcet\CondorcetVersion;

abstract class ArrayManager implements \ArrayAccess,\Countable,\Iterator
{
    use CondorcetVersion;

        //////

    protected static $CacheSize =  13;

    protected $_Container = [];
    protected $_Bdd = null;

    protected $_cursor = null;
    protected $_counter = 0;
    protected $_maxKey = -1;


/////////// Magic ///////////


/////////// Implement ArrayAccess ///////////

    public function offsetSet($offset, $value) {
        if ($offset === null) :
            $this->_Container[$this->_cursor = ++$this->_maxKey] = $value;
            ++$this->_counter;
        else :
            if (!$this->keyExist($offset)) :
                ++$this->_counter;
                if ($offset > $this->_maxKey) :
                    $this->_maxKey = $offset;
                endif;
            endif;

            $this->_Container[$offset] = $value;
        endif;
    }

    public function offsetExists($offset) {
        // Use by isset() function, must return false if offset value is null.
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


/////////// Implement Iterator ///////////

    protected $valid = true;

    public function rewind() {
        $this->_cursor = null;
        $this->valid = true;
    }

    public function current() {
        return $this->_Container[$this->key()];
    }

    public function key() {
        if ($this->_cursor === null) :
            reset($this->_Container);
            $this->_cursor = key($this->_Container);
        endif;

        return $this->_cursor;
    }

    public function next() {
        foreach (array_slice($this->_Container,$this->_cursor,null,true) as $key => $value) {
            if ($key > $this->_cursor) {
                $this->_cursor = $key;
                return;
            }
        };

        $this->valid = false;
    }

    public function valid() {
        return $this->valid;
    }


/////////// Implement Countable ///////////

    public function count () {
        return $this->_counter;
    }

    // Array Methods

    public function getFullDataSet () {
        return $this->_Container;
    }

    public function keyExist ($offset) {
        return array_key_exists($offset, $this->_Container);
    }

    public function isIn ($needle, $strict = true) {
        return in_array($needle, $this->_Container, $strict);
    }


/////////// BDD API ///////////

    public function setBdd ($bdd, $struct = null)
    {
        $this->_Bdd = new Bddhandler ($bdd);

        $this->regularize();
    }

    public function unsetBdd ()
    {

    }

    public function regularize ()
    {
        if ($this->_Bdd === null) :
            return false;
        endif;
    }

/////////// BDD INTERRACTION ///////////

    public function resetCounter ()
    {

    }

    public function resetMaxKey ()
    {

    }

    protected function writeData (array $data)
    {
        // return int / false
    }

    protected function removeData (array $keys)
    {
        // return int / false

    }
}
