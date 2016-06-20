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


class Result implements \ArrayAccess, \Countable, \Iterator 
{
    use CondorcetVersion;

    // Implement Iterator

    function rewind() {
        reset($this->_UserResult);
    }

    function current () {
        return current($this->_UserResult);
    }

    function key () : int {
        return key($this->_UserResult);
    }

    function next () {
        next($this->_UserResult);
    }

    function valid () : bool {
        return (key($this->_UserResult) === null) ? false : true;
    }

    // Implement ArrayAccess

    public function offsetSet ($offset, $value) {
        throw new CondorcetException (0,"Can't change a result");
    }

    public function offsetExists ($offset) : bool {
        return isset($this->_UserResult[$offset]);
    }

    public function offsetUnset ($offset) {
        throw new CondorcetException (0,"Can't change a result");
    }

    public function offsetGet ($offset) {
        return isset($this->_UserResult[$offset]) ? $this->_UserResult[$offset] : null;
    }

    // Implement Countable

    public function count () : int {
        return count($this->_UserResult);
    }


/////////// CONSTRUCTOR ///////////

    protected $_Election;

    protected $_Result;
    protected $_UserResult;

    protected $_Stats;

    protected $_warning = [];

    public function __construct (Election $election, array $result, $stats)
    {
        $this->_Election = $election;
        $this->_Result = $result;
        $this->_UserResult = $this->makeUserResult();
        $this->_Stats = $stats;
    }


/////////// Get Result ///////////

    public function getResultAsArray (bool $convertToString = false) : array
    {
        $r = $this->_UserResult;

        foreach ($r as &$rank) :
            if (count($rank) === 1) :
                $rank = ($convertToString) ? (string) $rank[0] : $rank[0];
            elseif ($convertToString) :
                foreach ($rank as &$subRank) :
                    $subRank = (string) $subRank;
                endforeach;
            endif;
        endforeach;

        return $r;
    }

    public function getResultAsInternalKey () : array
    {
        return $this->_Result;
    }

    public function getStats () {
        return $this->_Stats;
    }

    protected function makeUserResult () : array
    {
        $userResult = [];

        foreach ( $this->_Result as $key => $value ) :
            if (is_array($value)) :
                foreach ($value as $candidate_key) :
                    $userResult[$key][] = $this->_Election->getCandidateId($candidate_key);
                endforeach;
            elseif (is_null($value)) :
                $userResult[$key] = null;
            else :
                $userResult[$key][] = $this->_Election->getCandidateId($value);
            endif;
        endforeach;

        foreach ( $userResult as $key => $value ) :
            if (is_null($value)) :
                $userResult[$key] = null;
            endif;
        endforeach;

        return $userResult;
    }


/////////// Get & Set MetaData ///////////

    public function addWarning (int $type, string $msg = null) : bool
    {
        $this->_warning[] = ['type' => $type, 'msg' => $msg];

        return true;
    }

    public function getWarning ($type = null) : array
    {
        if ($type === null) :
            return $this->_warning;
        else :
            $r = [];

            foreach ($this->_warning as $oneWarning) :
                if ($oneWarning['type'] === (int) $type) :
                    $r[] = $oneWarning;
                endif;
            endforeach;

            return $r;
        endif;
    }

}
