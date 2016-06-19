<?php
/*
    Condorcet PHP Class, with Schulze Methods and others !

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace Condorcet;

use Condorcet\CondorcetException;
use Condorcet\CondorcetVersion;
use Condorcet\Candidate;
use Condorcet\Election;
use Condorcet\Linkable;


class Result implements \Iterator, \ArrayAccess
{
    use CondorcetVersion;

    // Implement Iterator

    function rewind() {
        reset($this->_Result);
    }

    function current () {
        return current($this->_Result);
    }

    function key () : int {
        return key($this->_Result);
    }

    function next () {
        next($this->_Result);
    }

    function valid () : bool {
        return (key($this->_Result) === null) ? false : true;
    }

    // Implement ArrayAccess

    public function offsetSet ($offset, $value) {
        throw new CondorcetException (0,"Can't change a result");
    }

    public function offsetExists ($offset) : bool {
        return isset($this->_Result[$offset]);
    }

    public function offsetUnset ($offset) {
        throw new CondorcetException (0,"Can't change a result");
    }

    public function offsetGet ($offset) {
        return isset($this->_Result[$offset]) ? $this->_Result[$offset] : null;
    }


    // Result

    protected $_Result;
    protected $_Election;

    public function __construct (Election $election, array $result)
    {
        $this->_Election = $election;
        $this->_Result = $result;
    }

    public function getResultAsArray () : array
    {
        $r = $this->_Result;

        foreach ($r as &$rank) :
            if (count($rank) === 1) :
                $rank = $rank[0];
                var_dump($rank);
            endif;
        endforeach;

        return $r;
    }

}
