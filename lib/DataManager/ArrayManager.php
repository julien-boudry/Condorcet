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
    protected $_Cache = [];

    protected $_cursor = null;
    protected $_counter = 0;
    protected $_maxKey = -1;


/////////// Magic ///////////


/////////// Implement ArrayAccess ///////////

    public function offsetSet($offset, $value)
    {
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

        $this->checkRegularize();
    }

    // Use by isset() function, must return false if offset value is null.
    public function offsetExists($offset)
    {
        if (isset($this->_Container[$offset]) || ($this->_Bdd !== null && $this->_Bdd->selectOneEntity($offset) !== false) ) :
            return true;
        else :
            return false;
        endif;
    }

    public function offsetUnset($offset)
    {
        if ($this->keyExist($offset)) :
            if (array_key_exists($offset, $this->_Container)) :
                unset($this->_Container[$offset]);
            else :
                $this->_Bdd->deleteOneEntity($offset);
            endif;

            --$this->_counter;
        endif;
    }

    public function offsetGet($offset)
    {
        if (isset($this->_Container[$offset])) :
            return $this->_Container[$offset];
        elseif ($this->_Bdd !== null) :
            $query = $this->_Bdd->selectOneEntity($offset);
            return ($query === false) ? null : $query;
        else :
            return null;
        endif;
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

    public function getFullDataSet ()
    {
        $this->regularize();

        return ($this->_Bdd === null) ? $this->_Container : $this->_Bdd->selectRangeEntitys(0,$this->_maxKey);
    }

    public function keyExist ($offset)
    {
        if ( array_key_exists($offset, $this->_Container) || ($this->_Bdd !== null && $this->_Bdd->selectOneEntity($offset) !== false) ) :
            return true;
        else  :
            return false;
        endif;
    }


/////////// BDD API ///////////

    public function setBdd ($bdd, $struct = null)
    {
        $this->_Bdd = new BddHandler ($bdd);

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

    public function checkRegularize ()
    {

    }

/////////// BDD INTERRACTION ///////////

    public function resetCounter ()
    {
        $this->_counter = count($this->_Container) + ( ($this->_Bdd !== null) ? $this->_Bdd->countEntitys() : 0 );
    }

    public function resetMaxKey ()
    {
        $this->_counter = max( max(array_keys($this->_Container)),( ($this->_Bdd !== null) ? $this->_Bdd->selectMaxKey() : 0 ) );
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
