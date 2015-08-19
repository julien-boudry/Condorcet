<?php
/*
    Condorcet PHP Class, with Schulze Methods and others !

    Version: 0.95

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

namespace Condorcet;

use Condorcet\Election;
use Condorcet\Vote;

class VotesManager implements \Iterator,\ArrayAccess
{
    // Implement ArrayAccess
    public function offsetSet($offset, $value) {}

    public function offsetExists($offset) {
        return isset($this->_Votes[$offset]) ? true : false;
    }

    public function offsetUnset($offset) {}

    public function offsetGet($offset) {
        return isset($this->_Votes[$offset]) ? $this->_Votes[$offset] : null;
    }


    // Implement Iterator
    private $valid = true;

    public function rewind() {
        reset($this->_Votes);
        $this->valid = true;
    }

    public function current() {
        return $this->_Votes[$this->key()];
    }

    public function key() {
        return key($this->_Votes);
    }

    public function next() {
        if (next($this->_Votes) === false) :
            $this->valid = false;
        endif;
    }

    public function valid() {
        return $this->valid;
    }   


        //////

    protected $_Votes = [];

    public function __construct (Election &$link)
    {
        $this->_Election = $link;
    }