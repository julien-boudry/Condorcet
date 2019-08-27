<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet;

use CondorcetPHP\Condorcet\DataManager\VotesManager;
use CondorcetPHP\Condorcet\DataManager\DataHandlerDrivers\DataHandlerDriverInterface;
use CondorcetPHP\Condorcet\ElectionProcess\{CandidatesProcess, ResultsProcess, VotesProcess};
use CondorcetPHP\Condorcet\Throwable\CondorcetException;
use CondorcetPHP\Condorcet\Timer\Manager as Timer_Manager;

// Base Condorcet class
class Election
{

/////////// PROPERTIES ///////////

    public const MAX_LENGTH_CANDIDATE_ID = 30; // Max length for candidate identifiant string

    protected static $_maxParseIteration = null;
    protected static $_maxVoteNumber = null;
    protected static $_checksumMode = false;

/////////// STATICS METHODS ///////////

    // Change max parse iteration
    public static function setMaxParseIteration (?int $maxParseIterations) : ?int
    {
        self::$_maxParseIteration = $maxParseIterations;
        return self::$_maxParseIteration;
    }

    // Change max vote number
    public static function setMaxVoteNumber (?int $maxVotesNumber) : ?int
    {
        self::$_maxVoteNumber = $maxVotesNumber;
        return self::$_maxVoteNumber;
    }


/////////// CONSTRUCTOR ///////////

    use CondorcetVersion;

    // Mechanics
    protected $_State = 1; // 1 = Add Candidates / 2 = Voting / 3 = Some result have been computing
    protected $_timer;

    // Params
    protected $_ImplicitRanking = true;
    protected $_VoteWeightRule = false;
    protected $_Constraints = [];

        //////

    public function __construct ()
    {
        $this->_Candidates = [];
        $this->_Votes = new VotesManager ($this);
        $this->_timer = new Timer_Manager;
    }

    public function __destruct ()
    {
        $this->destroyAllLink();
    }

    public function __sleep () : array
    {
        // Don't include others data
        $include = [
            '_Candidates',
            '_Votes',

            '_AutomaticNewCandidateName',
            '_State',
            '_objectVersion',

            '_ImplicitRanking',
            '_VoteWeightRule',
            '_Constraints',

            '_Pairwise',
            '_Calculator',
        ];

        !self::$_checksumMode && array_push($include, '_timer');

        return $include;
    }

    public function __wakeup ()
    {
        if ( version_compare($this->getObjectVersion(true),Condorcet::getVersion(true),'!=') ) :
            throw new CondorcetException(11, 'Your object version is '.$this->getObjectVersion().' but the class engine version is '.Condorcet::getVersion());
        endif;
    }

    public function __clone ()
    {
        $this->_Votes = clone $this->_Votes;
        $this->_Votes->setElection($this);      
        $this->registerAllLinks();

        $this->_timer = clone $this->_timer;

        if ($this->_Pairwise !== null) :
            $this->_Pairwise = clone $this->_Pairwise;
            $this->_Pairwise->setElection($this);
        endif;
    }


/////////// TIMER & CHECKSUM ///////////

    public function getGlobalTimer () : float {
        return $this->_timer->getGlobalTimer();
    }

    public function getLastTimer () : float {
        return $this->_timer->getLastTimer();
    }

    public function getTimerManager () : Timer_Manager {
        return $this->_timer;
    }

    public function getChecksum () : string
    {
        self::$_checksumMode = true;

        $r = hash_init('sha256');

        foreach ($this->_Candidates as $value) :
            hash_update($r, (string) $value);
        endforeach;

        foreach ($this->_Votes as $value) :
            hash_update($r, (string) $value);
        endforeach;

        $this->_Pairwise !== null
            && hash_update($r,serialize($this->_Pairwise->getExplicitPairwise()));

        hash_update($r, $this->getObjectVersion(true));

        self::$_checksumMode = false;

        return hash_final($r);
    }


/////////// LINKS REGULATION ///////////

    protected function registerAllLinks () : void
    {
        foreach ($this->_Candidates as $value) :
            $value->registerLink($this);
        endforeach;

        if ($this->_State > 1) :
            foreach ($this->_Votes as $value) :
                $value->registerLink($this);
            endforeach;
        endif;
    }

    protected function destroyAllLink () : void
    {
        foreach ($this->_Candidates as $value) :
            $value->destroyLink($this);
        endforeach;

        if ($this->_State > 1) :
            foreach ($this->_Votes as $value) :
                $value->destroyLink($this);
            endforeach;
        endif;
    }


  /////////// IMPLICIT RANKING & VOTE WEIGHT ///////////

    public function getImplicitRankingRule () : bool
    {
        return $this->_ImplicitRanking;
    }

    public function setImplicitRanking (bool $rule = true) : bool
    {
        $this->_ImplicitRanking = $rule;
        $this->cleanupCompute();
        return $this->getImplicitRankingRule();
    }

    public function isVoteWeightAllowed () : bool
    {
        return $this->_VoteWeightRule;
    }

    public function allowVoteWeight (bool $rule = true) : bool
    {
        $this->_VoteWeightRule = $rule;
        $this->cleanupCompute();
        return $this->isVoteWeightAllowed();
    }


    /////////// VOTE CONSTRAINT ///////////

    public function addConstraint (string $constraintClass) : bool
    {
        if ( !class_exists($constraintClass) ) :
            throw new CondorcetException(27);
        elseif ( !is_subclass_of($constraintClass, VoteConstraint::class) ) :
            throw new CondorcetException(28);
        elseif (in_array($constraintClass,$this->getConstraints(), true)) :
            throw new CondorcetException(29);
        endif;

        if ( $this->_State > 2) :
            $this->cleanupCompute();;
        endif;

        $this->_Constraints[] = $constraintClass;

        return true;
    }

    public function getConstraints () : array
    {
        return $this->_Constraints;
    }

    public function clearConstraints () : bool
    {
        $this->_Constraints = [];

        if ( $this->_State > 2) :
            $this->cleanupCompute();;
        endif;

        return true;
    }

    public function testIfVoteIsValidUnderElectionConstraints (Vote $vote) : bool
    {
        foreach ($this->_Constraints as $oneConstraint) :
            if ($oneConstraint::isVoteAllow($this,$vote) === false) :
                return false;
            endif;
        endforeach;

        return true;
    }


/////////// LARGE ELECTION MODE ///////////

    public function setExternalDataHandler (DataHandlerDriverInterface $driver) : bool
    {
        if (!$this->_Votes->isUsingHandler()) :
            $this->_Votes->importHandler($driver);
            return true;
        else :
            throw new CondorcetException(24);
        endif;
    }

    public function removeExternalDataHandler () : bool
    {
        if ($this->_Votes->isUsingHandler()) :
            $this->_Votes->closeHandler();
            return true;
        else :
            throw new CondorcetException(23);
        endif;
    }


/////////// STATE ///////////

    public function getState () : int
    {
        return $this->_State;
    }

    // Close the candidate config, be ready for voting (optional)
    public function setStateToVote () : bool
    {
        if ( $this->_State === 1 ) :
                if (empty($this->_Candidates)) :
                    throw new CondorcetException(20);
                endif;

                $this->_State = 2;

        // If voting continues after a first set of results
        elseif ( $this->_State > 2 ) :
                $this->cleanupCompute();
        endif;

        return true;
    }

    // Prepare to compute results & caching system
    protected function prepareResult () : bool
    {
        if ($this->_State > 2) :
            return false;
        elseif ($this->_State === 2) :
            $this->cleanupCompute();

            // Do Pairewise
            $this->makePairwise();

            // Change state to result
            $this->_State = 3;

            // Return
            return true;
        else :
            throw new CondorcetException(6);
        endif;
    }


/////////// CANDIDATES ///////////

    use CandidatesProcess;


/////////// VOTING ///////////

    use VotesProcess;


/////////// RESULTS ///////////

    use ResultsProcess;
}
