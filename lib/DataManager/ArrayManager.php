<?php
/*
    Condorcet PHP Class, with Schulze Methods and others !

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);


namespace Condorcet\DataManager;

use Condorcet\DataManager\DataContextInterface;
use Condorcet\DataManager\DataHandlerDrivers\DataHandlerDriverInterface;
use Condorcet\CondorcetException;
use Condorcet\CondorcetVersion;

abstract class ArrayManager implements \ArrayAccess, \Countable, \Iterator
{
    use CondorcetVersion;

        //////

    public static $CacheSize = 2000;
    public static $MaxContainerLength = 2000;

    protected $_Container = [];
    protected $_DataHandler = null;
    protected $_link = [];

    protected $_Cache = [];
    protected $_CacheMaxKey = 0;
    protected $_CacheMinKey = 0;

    protected $_cursor = null;
    protected $_counter = 0;
    protected $_maxKey = -1;

    public function __construct () {}

    public function __destruct ()
    {
        $this->regularize();
    }

    public function __clone ()
    {
        $this->_link = [];
    }

    public function __sleep () : array
    {
        $this->regularize();
        $this->clearCache();
        $this->rewind();

        return ['_Container','_DataHandler','_link'];
    }

    public function __wakeup ()
    {
        $this->resetMaxKey();
        $this->resetCounter();
    }


/////////// Implement ArrayAccess ///////////

    public function offsetSet($offset, $value) : void
    {
        if ($offset === null) :
            $this->_Container[++$this->_maxKey] = $value;
            ++$this->_counter;
        else :
            $state = !$this->keyExist($offset);
            $this->_Container[$offset] = $value;

            if ($state) :
                ++$this->_counter;

                if ($offset > $this->_maxKey) :
                    $this->_maxKey = $offset;
                endif;

                ksort($this->_Container,SORT_NUMERIC);
            elseif ($this->_DataHandler !== null) :
                $this->_DataHandler->deleteOneEntity($offset, true);
            endif;

            $this->clearCache();
        endif;

        $this->checkRegularize();
    }

    // Use by isset() function, must return false if offset value is null.
    public function offsetExists($offset) : bool
    {
        return ( isset($this->_Container[$offset]) || ($this->_DataHandler !== null && $this->_DataHandler->selectOneEntity($offset) !== false) ) ? true : false ;
    }

    public function offsetUnset($offset) : void
    {
        if ($this->keyExist($offset)) :
            if (array_key_exists($offset, $this->_Container)) :
                unset($this->_Container[$offset]);
            else :
                unset($this->_Cache[$offset]);

                $this->_DataHandler->deleteOneEntity($offset, false);
            endif;

            --$this->_counter;
        endif;
    }

    public function offsetGet($offset)
    {
        if (isset($this->_Container[$offset])) :
            return $this->_Container[$offset];
        elseif ($this->_DataHandler !== null) :
            if (array_key_exists($offset, $this->_Cache)) :
                return $this->_Cache[$offset];
            else :
                $query = $this->_DataHandler->selectOneEntity($offset);
                return ($query === false) ? null : $query;
            endif;
        else :
            return null;
        endif;
    }


/////////// Implement Iterator ///////////

    protected $valid = true;

    public function rewind() : void {
        $this->_cursor = null;
        $this->valid = true;

        reset($this->_Cache);
        reset($this->_Container);
    }

    public function current() {
        return $this->offsetGet($this->key());
    }

    public function key() : ?int
    {
        if ($this->_counter === 0) :
            return null;
        else :
            return ($this->_cursor === null) ? $this->getFirstKey() : $this->_cursor;
        endif;
    }

    public function next() : void
    {
        $oldCursor = $this->_cursor;

        if ($this->_cursor >= $this->_maxKey) :
            // Do nothing
        elseif (!$this->isUsingHandler()) :
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
            next($array);
            $arrayKey = key($array);

            if ($arrayKey > $this->key()) :
                return $this->_cursor = $arrayKey;
            endif;
        }

    public function valid() : bool {
        return $this->valid;
    }


/////////// Implement Countable ///////////

    public function count () : int {
        return $this->_counter;
    }

/////////// Array Methods ///////////

    public function getFullDataSet () : array
    {
        $this->regularize();

        return (!$this->isUsingHandler()) ? $this->_Container : $this->_DataHandler->selectRangeEntitys(0,$this->_maxKey + 1);
    }

    public function keyExist ($offset) : bool
    {
        if ( array_key_exists($offset, $this->_Container) || ($this->_DataHandler !== null && $this->_DataHandler->selectOneEntity($offset) !== false) ) :
            return true;
        else  :
            return false;
        endif;
    }

    public function getFirstKey () : int
    {
        $r = array_keys($this->_Container);

        if ($this->_DataHandler !== null) :
            $r[] = $this->_DataHandler->selectMinKey();
        endif;

        return (int) min($r);
    }

    public function getContainerSize () : int
    {
        return count($this->_Container);
    }

    public function getCacheSize () : int
    {
        return count($this->_Cache);
    }

    public function debugGetCache () : array
    {
        return $this->_Cache;
    }


/////////// HANDLER API ///////////

    public function regularize () : bool
    {
        if (!$this->isUsingHandler() || empty($this->_Container)) :
            return false;
        else :
            $this->_DataHandler->insertEntitys($this->_Container);
            $this->_Container = [];
            return true;
        endif;
    }

    public function checkRegularize () : bool
    {
        if ( $this->_DataHandler !== null && self::$MaxContainerLength <= $this->getContainerSize() ) :
            $this->regularize();
            return true;
        else :
            return false;
        endif;
    }

    protected function populateCache () : void
    {
        $this->regularize();

        $currentKey = $this->key();

        if ( empty($this->_Cache) || $currentKey >= $this->_CacheMaxKey || $currentKey < $this->_CacheMinKey ) :
            $this->_Cache = $this->_DataHandler->selectRangeEntitys($currentKey, self::$CacheSize);

            $keys = array_keys($this->_Cache);
            $this->_CacheMaxKey = max($keys);
            $this->_CacheMinKey = min($keys);
        endif;
    }

    public function clearCache () : void
    {
        $this->_Cache = [];
        $this->_CacheMaxKey = 0;
        $this->_CacheMinKey = 0;
    }

    public function isUsingHandler ()
    {
        return $this->_DataHandler !== null;
    }

/////////// HANDLER INTERRACTION ///////////

    public function resetCounter () : int
    {
        return $this->_counter = $this->getContainerSize() + ( ($this->isUsingHandler()) ? $this->_DataHandler->countEntitys() : 0 );
    }

    public function resetMaxKey () : ?int
    {
        $this->resetCounter();

        if ($this->count() < 1) :
            $this->_maxKey = -1;
            return null;
        else :
            $maxContainerKey = (empty($this->_Container)) ? null : max(array_keys($this->_Container));
            $maxHandlerKey = ($this->_DataHandler !== null) ? $this->_DataHandler->selectMaxKey() : null;

            return $this->_maxKey = max( $maxContainerKey,$maxHandlerKey );
        endif;
    }

    public function importHandler (DataHandlerDriverInterface $handler) : bool
    {
        if ($handler->countEntitys() === 0) :
            $this->_DataHandler = $handler;
            $this->_DataHandler->_dataContextObject = $this->getDataContextObject();

            try {
                $this->regularize();
            } catch (\Exception $e) {
                $this->_DataHandler = null;
                $this->resetCounter();
                $this->resetMaxKey();
                throw $e;
            }

            $this->resetCounter();
            $this->resetMaxKey();

            return true;
        else :
            throw new CondorcetException;
        endif;
    }

    public function closeHandler () : void
    {
        if ($this->_DataHandler !== null) :
            $this->regularize();
            $this->clearCache();

            $this->_Container = $this->_DataHandler->selectRangeEntitys(0,$this->_maxKey + 1);

            $this->_DataHandler = null;

            $this->resetCounter();
            $this->resetMaxKey();
        endif;
    }

    public function getDataContextObject () : DataContextInterface
    {
        return new Class implements DataContextInterface {
            public function dataCallBack ($data)
            {
                return $data;
            }

            public function dataPrepareStoringAndFormat ($data)
            {
                return $data;
            }
        };
    }
}
