<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet;

use CondorcetPHP\Condorcet\CondorcetDocAttributes\{Description, Examples, FunctionReturn, PublicAPI, Related};
use CondorcetPHP\Condorcet\ElectionProcess\VoteUtil;
use CondorcetPHP\Condorcet\Throwable\CondorcetException;

class Result implements \ArrayAccess, \Countable, \Iterator
{
    use CondorcetVersion;

    // Implement Iterator

    public function rewind() :void {
        \reset($this->_UserResult);
    }

    public function current () {
        return \current($this->_UserResult);
    }

    public function key () : int {
        return \key($this->_UserResult);
    }

    public function next () : void {
        \next($this->_UserResult);
    }

    public function valid () : bool {
        return \key($this->_UserResult) !== null;
    }

    // Implement ArrayAccess

    public function offsetSet ($offset, $value) : void {
        throw new CondorcetException (0,"Can't change a result");
    }

    public function offsetExists ($offset) : bool {
        return isset($this->_UserResult[$offset]);
    }

    public function offsetUnset ($offset) : void {
        throw new CondorcetException (0,"Can't change a result");
    }

    public function offsetGet ($offset) : array|Candidate|null {
        return $this->_UserResult[$offset] ?? null;
    }

    // Implement Countable

    public function count () : int {
        return \count($this->_UserResult);
    }


/////////// CONSTRUCTOR ///////////

    protected array $_Result;
    protected array $_UserResult;
    protected array $_stringResult;
    protected ?Candidate $_CondorcetWinner;
    protected ?Candidate $_CondorcetLoser;

    protected $_Stats;

    protected array $_warning = [];

    protected float $_BuildTimeStamp;
    protected string $_fromMethod;
    protected string $_byClass;
    protected string $_ElectionCondorcetVersion;


    public function __construct (string $fromMethod, string $byClass, Election $election, array $result, $stats)
    {
        \ksort($result, \SORT_NUMERIC);

        $this->_Result = $result;
        $this->_UserResult = $this->makeUserResult($election);
        $this->_stringResult = $this->getResultAsArray(true);
        $this->_Stats = $stats;
        $this->_fromMethod = $fromMethod;
        $this->_byClass = $byClass;
        $this->_ElectionCondorcetVersion = $election->getObjectVersion();
        $this->_CondorcetWinner = $election->getWinner();
        $this->_CondorcetLoser = $election->getLoser();
        $this->_BuildTimeStamp = \microtime(true);
    }

    public function __destruct ()
    {
        unset($this->_Result, $this->_UserResult, $this->_Stats, $this->_CondorcetWinner, $this->_CondorcetLoser);
    }


/////////// Get Result ///////////

    #[PublicAPI]
    public function getResultAsArray (bool $convertToString = false) : array
    {
        $r = $this->_UserResult;

        foreach ($r as &$rank) :
            if (\count($rank) === 1) :
                $rank = $convertToString ? (string) $rank[0] : $rank[0];
            elseif ($convertToString) :
                foreach ($rank as &$subRank) :
                    $subRank = (string) $subRank;
                endforeach;
            endif;
        endforeach;

        return $r;
    }

    #[PublicAPI]
    public function getResultAsString () : string
    {
        return VoteUtil::getRankingAsString($this->getResultAsArray(true));
    }

    #[PublicAPI]
    public function getOriginalResultArrayWithString () : array
    {
        return $this->_stringResult;
    }

    public function getResultAsInternalKey () : array
    {
        return $this->_Result;
    }

    #[PublicAPI]
    public function getStats () : mixed {
        return $this->_Stats;
    }

    #[PublicAPI]
    public function getWinner () : array|Candidate|null {
        return CondorcetUtil::format($this[1],false);
    }

    #[PublicAPI]
    public function getLoser () : array|Candidate|null {
        return CondorcetUtil::format($this[count($this)],false);
    }

    #[PublicAPI]
    public function getCondorcetWinner () : ?Candidate {
        return $this->_CondorcetWinner;
    }

    #[PublicAPI]
    public function getCondorcetLoser () : ?Candidate {
        return $this->_CondorcetLoser;
    }

    protected function makeUserResult (Election $election) : array
    {
        $userResult = [];

        foreach ( $this->_Result as $key => $value ) :
            if (\is_array($value)) :
                foreach ($value as $candidate_key) :
                    $userResult[$key][] = $election->getCandidateObjectFromKey($candidate_key);
                endforeach;

                sort($userResult[$key], \SORT_STRING);
            elseif (\is_null($value)) :
                $userResult[$key] = null;
            else :
                $userResult[$key][] = $election->getCandidateObjectFromKey($value);
            endif;
        endforeach;

        foreach ( $userResult as $key => $value ) :
            if (\is_null($value)) :
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

    #[PublicAPI]
    public function getWarning (?int $type = null) : array
    {
        if ($type === null) :
            return $this->_warning;
        else :
            $r = [];

            foreach ($this->_warning as $oneWarning) :
                if ($oneWarning['type'] === $type) :
                    $r[] = $oneWarning;
                endif;
            endforeach;

            return $r;
        endif;
    }

    #[PublicAPI]
    public function getClassGenerator () : string {
        return $this->_byClass;
    }

    #[PublicAPI]
    public function getMethod () : string {
        return $this->_fromMethod;
    }

    #[PublicAPI]
    public function getBuildTimeStamp () : float {
        return (float) $this->_BuildTimeStamp;
    }

    #[PublicAPI]
    public function getCondorcetElectionGeneratorVersion () : string {
        return $this->_ElectionCondorcetVersion;
    }
}
