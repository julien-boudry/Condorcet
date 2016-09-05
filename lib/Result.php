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

    protected $_Result;
    protected $_UserResult;
    protected $_CondorcetWinner;
    protected $_CondorcetLoser;

    protected $_Stats;

    protected $_warning = [];

    protected $_BuildTimeStamp;
    protected $_fromMethod;
    protected $_byClass;
    protected $_ElectionCondorcetVersion;


    public function __construct (string $fromMethod, string $byClass, Election $election, array $result, $stats)
    {
        $this->_Result = $result;
        $this->_UserResult = $this->makeUserResult($election);
        $this->_Stats = $stats;
        $this->_fromMethod = $fromMethod;
        $this->_byClass = $byClass;
        $this->_ElectionCondorcetVersion = $election->getObjectVersion();
        $this->_CondorcetWinner = $election->getWinner();
        $this->_CondorcetLoser = $election->getLoser();
        $this->_BuildTimeStamp = microtime(true);
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

    public function getWinner () {
        Condorcet::format($this[1],false,false);
    }

    public function getLoser () {
        Condorcet::format($this[count($this)],false,false);
    }

    public function getCondorcetWinner () {
        return $this->_CondorcetWinner;
    }

    public function getCondorcetLoser () {
        return $this->_CondorcetLoser;
    }

    protected function makeUserResult (Election $election) : array
    {
        $userResult = [];

        foreach ( $this->_Result as $key => $value ) :
            if (is_array($value)) :
                foreach ($value as $candidate_key) :
                    $userResult[$key][] = $election->getCandidateId($candidate_key);
                endforeach;
            elseif (is_null($value)) :
                $userResult[$key] = null;
            else :
                $userResult[$key][] = $election->getCandidateId($value);
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

    public function getClassGenerator () : string {
        return $this->_byClass;
    }

    public function getMethod () : string {
        return $this->_fromMethod;
    }

    public function getBuildTimeStamp () : float {
        return (float) $this->_BuildTimeStamp;
    }

    public function getCondorcetElectionGeneratorVersion () : string {
        return $this->_ElectionCondorcetVersion;
    }

}
