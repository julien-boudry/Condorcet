<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet;

use CondorcetPHP\Condorcet\CondorcetDocAttributes\{Description, Example, FunctionReturn, PublicAPI, Related};
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

    protected static ?int $_maxParseIteration = null;
    protected static ?int $_maxVoteNumber = null;
    protected static bool $_checksumMode = false;

/////////// STATICS METHODS ///////////

    // Change max parse iteration
    #[PublicAPI]
    #[Description("Maximum input for each use of Election::parseCandidate && Election::parseVote. Will throw an exception if exceeded.")]
    #[FunctionReturn("*(int or null)* The new limit.")]
    #[Related("static Election::setMaxVoteNumber")]
    public static function setMaxParseIteration (?int $maxParseIterations) : ?int
    {
        self::$_maxParseIteration = $maxParseIterations;
        return self::$_maxParseIteration;
    }

    // Change max vote number
    #[PublicAPI]
    #[Description("Add a limitation on Election::addVote and related methods. You can't add new vote y the number of registered vote is equall ou superior of this limit.")]
    #[FunctionReturn("*(int or null)* The new limit.")]
    #[Related("static Election::setMaxParseIteration")]
    public static function setMaxVoteNumber (?int $maxVotesNumber) : ?int
    {
        self::$_maxVoteNumber = $maxVotesNumber;
        return self::$_maxVoteNumber;
    }


/////////// CONSTRUCTOR ///////////

    use CondorcetVersion;

    // Mechanics
    protected int $_State = 1; // 1 = Add Candidates / 2 = Voting & Result
    protected Timer_Manager $_timer;

    // Params
    protected bool $_ImplicitRanking = true;
    protected bool $_VoteWeightRule = false;
    protected array $_Constraints = [];
    protected int $_Seats = 100;

        //////

    #[PublicAPI]
    #[Description("Build a new Election.")]
    public function __construct ()
    {
        $this->_Candidates = [];
        $this->_Votes = new VotesManager ($this);
        $this->_timer = new Timer_Manager;
    }

    public function __destruct ()
    {
        $this->destroyAllLink();
        unset($this->_Pairwise);
        unset($this->_Calculator);
    }

    public function __serialize () : array
    {
        // Don't include others data
        $include = [
            '_Candidates' => $this->_Candidates,
            '_Votes' => $this->_Votes,

            '_State' => $this->_State,
            '_objectVersion' => $this->_objectVersion,
            '_AutomaticNewCandidateName' => $this->_AutomaticNewCandidateName,

            '_ImplicitRanking' => $this->_ImplicitRanking,
            '_VoteWeightRule' => $this->_VoteWeightRule,
            '_Constraints' => $this->_Constraints,

            '_Pairwise' => $this->_Pairwise,
            '_Calculator' => $this->_Calculator
        ];

        !self::$_checksumMode && ($include += ['_timer' => $this->_timer]);

        return $include;
    }

    public function __unserialize (array $data) : void
    {
        if ( \version_compare($this->getObjectVersion(true),Condorcet::getVersion(true),'!=') ) :
            throw new CondorcetException(11, 'Your object version is '.$this->getObjectVersion().' but the class engine version is '.Condorcet::getVersion());
        endif;

        $this->_Candidates = $data['_Candidates'];
        $this->_Votes = $data['_Votes'];

        $this->_AutomaticNewCandidateName = $data['_AutomaticNewCandidateName'];
        $this->_State = $data['_State'];
        $this->_objectVersion = $data['_objectVersion'];

        $this->_ImplicitRanking = $data['_ImplicitRanking'];
        $this->_VoteWeightRule = $data['_VoteWeightRule'];
        $this->_Constraints = $data['_Constraints'];

        $this->_Pairwise = $data['_Pairwise'];
        $this->_Calculator = $data['_Calculator'];

        $this->_timer ??= $data['_timer'];
    }

    public function __clone () : void
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

    #[PublicAPI]
    #[Description("Returns the cumulated computation runtime of this object. Include only computation related methods.")]
    #[FunctionReturn("(Float) Timer")]
    #[Example("Manual - Timber benchmarking","https://github.com/julien-boudry/Condorcet/wiki/III-%23-A.-Avanced-features---Configuration-%23-1.-Timer-Benchmarking")]
    #[Related("Election::getLastTimer")]
    public function getGlobalTimer () : float {
        return $this->_timer->getGlobalTimer();
    }

    #[PublicAPI]
    #[Description("Return the last computation runtime (typically after a getResult() call.). Include only computation related methods.")]
    #[FunctionReturn("(Float) Timer")]
    #[Example("Manual - Timber benchmarking","https://github.com/julien-boudry/Condorcet/wiki/III-%23-A.-Avanced-features---Configuration-%23-1.-Timer-Benchmarking")]
    #[Related("Election::getGlobalTimer")]
    public function getLastTimer () : float {
        return $this->_timer->getLastTimer();
    }

    #[PublicAPI]
    #[Description("Get the Timer manager object.")]
    #[FunctionReturn("An CondorcetPHP\Condorcet\Timer\Manager object using by this election.")]
    #[Related("Election::getGlobalTimer", "Election::getLastTimer")]
    public function getTimerManager () : Timer_Manager {
        return $this->_timer;
    }

    #[PublicAPI]
    #[Description("SHA-2 256 checksum of following internal data:\n* Candidates\n* Votes list & tags\n* Computed data (pairwise, algorithm cache, stats)\n* Class version (major version like 0.14)\n\nCan be powerfull to check integrity and security of an election. Or working with serialized object.")]
    #[FunctionReturn("SHA-2 256 bits Hexadecimal")]
    #[Example("Manual - Cryptographic Checksum","https://github.com/julien-boudry/Condorcet/wiki/III-%23-A.-Avanced-features---Configuration-%23-2.-Cryptographic-Checksum")]
    public function getChecksum () : string
    {
        self::$_checksumMode = true;

        $r = \hash_init('sha256');

        foreach ($this->_Candidates as $value) :
            \hash_update($r, (string) $value);
        endforeach;

        foreach ($this->_Votes as $value) :
            \hash_update($r, (string) $value);
        endforeach;

        $this->_Pairwise !== null
            && \hash_update($r,\serialize($this->_Pairwise->getExplicitPairwise()));

        \hash_update($r, $this->getObjectVersion(true));

        self::$_checksumMode = false;

        return \hash_final($r);
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

        $this->_Votes->destroyElection();
    }


  /////////// IMPLICIT RANKING & VOTE WEIGHT ///////////

    #[PublicAPI]
    #[Description("Returns the corresponding setting as currently set (True by default).\nIf it is True then all votes expressing a partial ranking are understood as implicitly placing all the non-mentioned candidates exequos on a last rank.\nIf it is false, then the candidates not ranked, are not taken into account at all.")]
    #[FunctionReturn("True / False")]
    #[Related("Election::setImplicitRanking")]
    public function getImplicitRankingRule () : bool
    {
        return $this->_ImplicitRanking;
    }

    #[PublicAPI]
    #[Description("Set the setting and reset all result data.\nIf it is True then all votes expressing a partial ranking are understood as implicitly placing all the non-mentioned candidates exequos on a last rank.\nIf it is false, then the candidates not ranked, are not taken into account at all.")]
    #[FunctionReturn("Return True")]
    #[Related("Election::getImplicitRankingRule")]
    public function setImplicitRanking (bool $rule = true) : bool
    {
        $this->_ImplicitRanking = $rule;
        $this->cleanupCompute();
        return $this->getImplicitRankingRule();
    }

    #[PublicAPI]
    #[Description("Returns the corresponding setting as currently set (False by default).\nIf it is True then votes vote optionally can use weight otherwise (if false) all votes will be evaluated as equal for this election.")]
    #[FunctionReturn("True / False")]
    #[Related("Election::allowsVoteWeight")]
    public function isVoteWeightAllowed () : bool
    {
        return $this->_VoteWeightRule;
    }

    #[PublicAPI]
    #[Description("Set the setting and reset all result data.\nThen the weight of votes (if specified) will be taken into account when calculating the results. Otherwise all votes will be considered equal.\nBy default, the voting weight is not activated and all votes are considered equal.")]
    #[FunctionReturn("Return True")]
    #[Related("Election::isVoteWeightAllowed")]
    public function allowsVoteWeight (bool $rule = true) : bool
    {
        $this->_VoteWeightRule = $rule;
        $this->cleanupCompute();
        return $this->isVoteWeightAllowed();
    }


    /////////// VOTE CONSTRAINT ///////////

    #[PublicAPI]
    #[Description("Add a constraint rules as a valid class path.")]
    #[FunctionReturn("True on success. Throw Throwable\CondorcetException code 27/28/29 on error.")]
    #[Example("Manual - Vote Constraints","https://github.com/julien-boudry/Condorcet/wiki/II-%23-C.-Result-%23-5.-Vote-Constraints")]
    #[Related("Election::getConstraints", "Election::clearConstraints", "Election::testIfVoteIsValidUnderElectionConstraints")]
    public function addConstraint (string $constraintClass) : bool
    {
        if ( !\class_exists($constraintClass) ) :
            throw new CondorcetException(27);
        elseif ( !\is_subclass_of($constraintClass, VoteConstraint::class) ) :
            throw new CondorcetException(28);
        elseif (\in_array(needle: $constraintClass, haystack: $this->getConstraints(), strict: true)) :
            throw new CondorcetException(29);
        endif;

        $this->cleanupCompute();;

        $this->_Constraints[] = $constraintClass;

        return true;
    }

    #[PublicAPI]
    #[Description("Get active constraints list.")]
    #[FunctionReturn("Array with class name of each active constraint. Empty array if there is not.")]
    #[Example("Manual - Vote Constraints","https://github.com/julien-boudry/Condorcet/wiki/II-%23-C.-Result-%23-5.-Vote-Constraints")]
    #[Related("Election::clearConstraints", "Election::addConstraints", "Election::testIfVoteIsValidUnderElectionConstraints")]
    public function getConstraints () : array
    {
        return $this->_Constraints;
    }

    #[PublicAPI]
    #[Description("Clear all constraints rules and clear previous results.")]
    #[FunctionReturn("Return True.")]
    #[Example("Manual - Vote Constraints","https://github.com/julien-boudry/Condorcet/wiki/II-%23-C.-Result-%23-5.-Vote-Constraints")]
    #[Related("Election::getConstraints", "Election::addConstraints", "Election::testIfVoteIsValidUnderElectionConstraints")]
    public function clearConstraints () : bool
    {
        $this->_Constraints = [];

        $this->cleanupCompute();;
        return true;
    }

    #[PublicAPI]
    #[Description("Test if a vote is valid with these election constraints.")]
    #[FunctionReturn("Return True if vote will pass the constraints rules, else False.")]
    #[Example("Manual - Vote Constraints","https://github.com/julien-boudry/Condorcet/wiki/II-%23-C.-Result-%23-5.-Vote-Constraints")]
    #[Related("Election::getConstraints", "Election::addConstraints", "Election::clearConstraints")]
    public function testIfVoteIsValidUnderElectionConstraints (Vote $vote) : bool
    {
        foreach ($this->_Constraints as $oneConstraint) :
            if ($oneConstraint::isVoteAllow($this,$vote) === false) :
                return false;
            endif;
        endforeach;

        return true;
    }


    /////////// STV SEATS ///////////

    #[PublicAPI]
    #[Description("Get number of Seats for STV methods.")]
    #[FunctionReturn("Number of seats.")]
    #[Related("Election::setNumberOfSeats", "Result::getNumberOfSeats")]
    public function getNumberOfSeats () : int
    {
        return $this->_Seats;
    }

    #[PublicAPI]
    #[Description("Set number of Seats for STV methods.")]
    #[FunctionReturn("Number of seats.")]
    #[Related("Election::getNumberOfSeats")]
    public function setNumberOfSeats (int $seats) : int
    {
        if ($seats > 0) :
            $this->cleanupCompute();

            $this->_Seats = $seats;
        else :
            throw new CondorcetException(30);
        endif;

        return $this->_Seats;
    }


/////////// LARGE ELECTION MODE ///////////

    #[PublicAPI]
    #[Description("Import and enable an external driver to store vote on very large election.")]
    #[FunctionReturn("True if success. Else throw an Exception.")]
    #[Example("[Manual - DataHandler]","https://github.com/julien-boudry/Condorcet/blob/master/examples/specifics_examples/use_large_election_external_database_drivers.php")]
    #[Related("Election::removeExternalDataHandler")]
    public function setExternalDataHandler (DataHandlerDriverInterface $driver) : bool
    {
        if (!$this->_Votes->isUsingHandler()) :
            $this->_Votes->importHandler($driver);
            return true;
        else :
            throw new CondorcetException(24);
        endif;
    }

    #[PublicAPI]
    #[Description("Remove an external driver to store vote on very large election. And import his data into classical memory.")]
    #[FunctionReturn("True if success. Else throw an Exception.")]
    #[Related("Election::setExternalDataHandler")]
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

    #[PublicAPI]
    #[Description("Get the election process level.")]
    #[FunctionReturn("1: Candidate registered state. No votes, no result, no cache.\n2: Voting registration phase. Pairwise cache can exist thanks to dynamic computation if voting phase continue after the first get result. But method result never exist.\n3: Result phase: Some method result may exist, pairwise exist. An election will return to Phase 2 if votes are added or modified dynamically.")]
    #[Related("Election::setStateToVote")]
    public function getState () : int
    {
        return $this->_State;
    }

    // Close the candidate config, be ready for voting (optional)
    #[PublicAPI]
    #[Description("Force the election to get back to state 2. See Election::getState.\nIt is not necessary to use this method. The election knows how to manage its phase changes on its own. But it is a way to clear the cache containing the results of the methods.\n\nIf you are on state 1 (candidate registering), it's will close this state and prepare election to get firsts votes.\nIf you are on state 3. The method result cache will be clear, but not the pairwise. Which will continue to be updated dynamically.")]
    #[FunctionReturn("Always True.")]
    #[Related("Election::getState")]
    public function setStateToVote () : bool
    {
        if ( $this->_State === 1 ) :
            if (empty($this->_Candidates)) :
                throw new CondorcetException(20);
            endif;

            $this->_State = 2;
            $this->preparePairwiseAndCleanCompute();
        endif;

        return true;
    }

    // Prepare to compute results & caching system
    protected function preparePairwiseAndCleanCompute () : bool
    {
        if ($this->_Pairwise === null && $this->_State === 2) :
            $this->cleanupCompute();

            // Do Pairewise
            $this->makePairwise();

            // Return
            return true;
        elseif ($this->_State === 1 || $this->countVotes() === 0) :
            throw new CondorcetException(6);
        else :
            return false;
        endif;
    }


/////////// CANDIDATES ///////////

    use CandidatesProcess;


/////////// VOTING ///////////

    use VotesProcess;


/////////// RESULTS ///////////

    use ResultsProcess;
}
