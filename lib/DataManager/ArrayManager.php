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
    protected static $MaxContainerLength =  13;

    protected $_Container = [];
    protected $_Bdd = null;
    protected $_Cache = [];

    protected $_cursor = null;
    protected $_counter = 0;
    protected $_maxKey = -1;


/////////// Implement ArrayAccess ///////////

    public function offsetSet($offset, $value)
    {
        if ($offset === null) :
            $this->_Container[++$this->_maxKey] = $value;
            ++$this->_counter;
        else :
            if (!$this->keyExist($offset)) :
                ++$this->_counter;
                if ($offset > $this->_maxKey) :
                    $this->_maxKey = $offset;
                endif;
            endif;

            unset($this->_Cache[$offset]);
            $this->_Container[$offset] = $value;
        endif;

        $this->checkRegularize();
    }

    // Use by isset() function, must return false if offset value is null.
    public function offsetExists($offset)
    {
        return ( isset($this->_Container[$offset]) || ($this->_Bdd !== null && $this->_Bdd->selectOneEntity($offset) !== false) ) ? true : false ;
    }

    public function offsetUnset($offset)
    {
        if ($this->keyExist($offset)) :
            if (array_key_exists($offset, $this->_Container)) :
                unset($this->_Container[$offset]);
            else :
                unset($this->_Cache[$offset]);

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
            if (array_key_exists($offset, $this->_Cache)) :
                return $this->_Cache[$offset];
            endif;

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
        return  $this->offsetGet($this->key());
    }

    public function key()
    {
        if ($this->_counter === 0) :
            return null;
        else :
            return ($this->_cursor === null) ? $this->getFirstKey() : $this->_cursor;
        endif;
    }

    public function next()
    {
        $oldCursor = $this->_cursor;

        if ($this->_cursor >= $this->_maxKey) :
            // Do nothing
        elseif ($this->_Bdd === null) :
            $this->setCursorOnNextKeyInArray($this->_Container);
        else :
            $this->populateCache();
            $this->setCursorOnNextKeyInArray($this->_Cache);
        endif;

        if ($this->_cursor === $oldCursor) :
            $this->valid = false;
        endif;
    }

        protected function setCursorOnNextKeyInArray (array &$array)
        {
            $match = $this->key();
            ksort($array,SORT_NUMERIC);

            foreach (array_slice($array,array_search($match, array_keys($array),true),2,true) as $key => $value) :
                if ($key > $match) :
                    $this->_cursor = $key;
                    return;
                endif;
            endforeach;
        }

    public function valid() {
        return $this->valid;
    }


/////////// Implement Countable ///////////

    public function count () {
        return $this->_counter;
    }

/////////// Array Methods ///////////

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

    public function getFirstKey ()
    {
        $r = array_keys($this->_Container);

        if ($this->_Bdd !== null) :
            $r[] = $this->_Bdd->selectMinKey();
        endif;

        return (int) min($r);
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
        if ($this->_Bdd === null || empty($this->_Container)) :
            return false;
        else :
            $this->_Bdd->insertEntitys($this->_Container);
            $this->_Container = [];
            return true;
        endif;
    }

    public function checkRegularize ()
    {
        if ( $this->_Bdd !== null && self::$MaxContainerLength < count($this->_Container) ) :
            $this->regularize();
            return true;
        else :
            return false;
        endif;
    }

    protected function populateCache ()
    {
        if (    $this->regularize() &&
                (   empty($this->_Cache) ||
                    (
                        count(
                            array_slice(
                                $this->_Cache,
                                array_search($this->key(),
                                    array_keys($this->_Cache), true
                                ),
                                null,true
                            )
                        ) < (self::$CacheSize / 3)
                    )
                )
            ) :

            $this->_Cache = $this->_Bdd->selectRangeEntitys($this->key(), self::$CacheSize);

        endif;
    }

    public function clearCache () {
        $this->_Cache = [];
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
