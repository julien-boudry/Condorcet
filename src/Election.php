<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

declare(strict_types=1);

namespace CondorcetPHP\Condorcet;

use CondorcetPHP\Condorcet\Throwable\{DataHandlerException, ElectionObjectVersionMismatchException, NoCandidatesException, NoSeatsException, ResultRequestedWithoutVotesException, VoteConstraintException};
use CondorcetPHP\Condorcet\ElectionProcess\{CandidatesProcess, ElectionState, ResultsProcess, VotesProcess};
use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{Description, Example, FunctionParameter, FunctionReturn, PublicAPI, Related, Throws};
use CondorcetPHP\Condorcet\DataManager\VotesManager;
use CondorcetPHP\Condorcet\DataManager\DataHandlerDrivers\DataHandlerDriverInterface;
use CondorcetPHP\Condorcet\Timer\Manager as Timer_Manager;

// Base Condorcet class
class Election
{
    /////////// CONSTRUCTOR ///////////

    use CondorcetVersion;


    /////////// CANDIDATES ///////////

    use CandidatesProcess;


    /////////// VOTING ///////////

    use VotesProcess;


    /////////// RESULTS ///////////

    use ResultsProcess;

    /////////// PROPERTIES ///////////

    #[PublicAPI]
    public const MAX_CANDIDATE_NAME_LENGTH = 100; // Max length for candidate name string (UTF-8)

    protected static ?int $maxParseIteration = null;
    protected static ?int $maxVoteNumber = null;
    protected static bool $checksumMode = false;

    /////////// STATICS METHODS ///////////

    // Change max parse iteration
    #[PublicAPI]
    #[Description('Maximum input for each use of Election::parseCandidate && Election::parseVote. Will throw an exception if exceeded.')]
    #[FunctionReturn('*(int or null)* The new limit.')]
    #[Related('static Election::setMaxVoteNumber')]
    public static function setMaxParseIteration(
        #[FunctionParameter('Null will deactivate this functionality. Else, enter an integer.')]
        ?int $maxParseIterations
    ): ?int {
        self::$maxParseIteration = $maxParseIterations;
        return self::$maxParseIteration;
    }

    // Change max vote number
    #[PublicAPI]
    #[Description("Add a limitation on Election::addVote and related methods. You can't add new vote y the number of registered vote is equall ou superior of this limit.")]
    #[FunctionReturn('*(int or null)* The new limit.')]
    #[Related('static Election::setMaxParseIteration')]
    public static function setMaxVoteNumber(
        #[FunctionParameter('Null will deactivate this functionality. An integer will fix the limit.')]
        ?int $maxVotesNumber
    ): ?int {
        self::$maxVoteNumber = $maxVotesNumber;
        return self::$maxVoteNumber;
    }

    // Mechanics
    protected ElectionState $State = ElectionState::CANDIDATES_REGISTRATION;
    protected Timer_Manager $timer;

    // Params
    protected bool $ImplicitRanking = true;
    protected bool $VoteWeightRule = false;
    protected array $Constraints = [];
    protected int $Seats = 100;

    // -------

    #[PublicAPI]
    #[Description('Build a new Election.')]
    public function __construct()
    {
        $this->Candidates = [];
        $this->Votes = new VotesManager($this);
        $this->timer = new Timer_Manager;
    }

    public function __serialize(): array
    {
        // Don't include others data
        $include = [
            'Candidates' => $this->Candidates,
            'Votes' => $this->Votes,

            'State' => $this->State,
            'objectVersion' => $this->objectVersion,
            'AutomaticNewCandidateName' => $this->AutomaticNewCandidateName,

            'ImplicitRanking' => $this->ImplicitRanking,
            'VoteWeightRule' => $this->VoteWeightRule,
            'Constraints' => $this->Constraints,

            'Pairwise' => $this->Pairwise,
            'Calculator' => $this->Calculator,
        ];

        !self::$checksumMode && ($include += ['timer' => $this->timer]);

        return $include;
    }

    public function __unserialize(array $data): void
    {
        // Only compare major and minor version numbers, not patch level
        // e.g. 2.0 and 3.2
        $objectVersion = explode('.', $data['objectVersion']);
        $objectVersion = $objectVersion[0] . '.' . $objectVersion[1];
        if (version_compare($objectVersion, Condorcet::getVersion(true), '!=')) {
            throw new ElectionObjectVersionMismatchException($objectVersion);
        }

        $this->Candidates = $data['Candidates'];
        $this->Votes = $data['Votes'];
        $this->Votes->setElection($this);
        $this->registerAllLinks();

        $this->AutomaticNewCandidateName = $data['AutomaticNewCandidateName'];
        $this->State = $data['State'];
        $this->objectVersion = $data['objectVersion'];

        $this->ImplicitRanking = $data['ImplicitRanking'];
        $this->VoteWeightRule = $data['VoteWeightRule'];
        $this->Constraints = $data['Constraints'];

        $this->Pairwise = $data['Pairwise'];
        $this->Pairwise->setElection($this);

        $this->Calculator = $data['Calculator'];
        foreach ($this->Calculator as $methodObject) {
            $methodObject->setElection($this);
        }

        $this->timer ??= $data['timer'];
    }

    public function __clone(): void
    {
        $this->Votes = clone $this->Votes;
        $this->Votes->setElection($this);
        $this->registerAllLinks();

        $this->timer = clone $this->timer;

        if ($this->Pairwise !== null) {
            $this->Pairwise = clone $this->Pairwise;
            $this->Pairwise->setElection($this);
        }
    }


    /////////// TIMER & CHECKSUM ///////////

    #[PublicAPI]
    #[Description('Returns the cumulated computation runtime of this object. Include only computation related methods.')]
    #[FunctionReturn('(Float) Timer')]
    #[Example('Documentation Book - Timber benchmarking', 'https://github.com/julien-boudry/Condorcet/wiki/III-%23-A.-Avanced-features---Configuration-%23-1.-Timer-Benchmarking')]
    #[Related('Election::getLastTimer')]
    public function getGlobalTimer(): float
    {
        return $this->timer->getGlobalTimer();
    }

    #[PublicAPI]
    #[Description('Return the last computation runtime (typically after a getResult() call.). Include only computation related methods.')]
    #[FunctionReturn('(Float) Timer')]
    #[Example('Documentation Book - Timber benchmarking', 'https://github.com/julien-boudry/Condorcet/wiki/III-%23-A.-Avanced-features---Configuration-%23-1.-Timer-Benchmarking')]
    #[Related('Election::getGlobalTimer')]
    public function getLastTimer(): float
    {
        return $this->timer->getLastTimer();
    }

    #[PublicAPI]
    #[Description('Get the Timer manager object.')]
    #[FunctionReturn("An CondorcetPHP\Condorcet\Timer\Manager object using by this election.")]
    #[Related('Election::getGlobalTimer', 'Election::getLastTimer')]
    public function getTimerManager(): Timer_Manager
    {
        return $this->timer;
    }

    #[PublicAPI]
    #[Description("SHA-2 256 checksum of following internal data:\n* Candidates\n* Votes list & tags\n* Computed data (pairwise, algorithm cache, stats)\n* Class version (major version like 0.14)\n\nCan be powerfull to check integrity and security of an election. Or working with serialized object.")]
    #[FunctionReturn('SHA-2 256 bits Hexadecimal')]
    #[Example('Documentation Book - Cryptographic Checksum', 'https://github.com/julien-boudry/Condorcet/wiki/III-%23-A.-Avanced-features---Configuration-%23-2.-Cryptographic-Checksum')]
    public function getChecksum(): string
    {
        self::$checksumMode = true;

        $r = hash_init('sha256');

        foreach ($this->Candidates as $value) {
            hash_update($r, (string) $value);
        }

        foreach ($this->Votes as $value) {
            hash_update($r, (string) $value);
        }

        $this->Pairwise !== null
            && hash_update($r, serialize($this->Pairwise->getExplicitPairwise()));

        hash_update($r, $this->getObjectVersion(true));

        self::$checksumMode = false;

        return hash_final($r);
    }


    /////////// LINKS REGULATION ///////////

    protected function registerAllLinks(): void
    {
        foreach ($this->Candidates as $value) {
            $value->registerLink($this);
        }

        foreach ($this->Votes as $value) {
            $value->registerLink($this);
        }
    }


    /////////// IMPLICIT RANKING & VOTE WEIGHT ///////////

    #[PublicAPI]
    #[Description("Returns the corresponding setting as currently set (True by default).\nIf it is True then all votes expressing a partial ranking are understood as implicitly placing all the non-mentioned candidates exequos on a last rank.\nIf it is false, then the candidates not ranked, are not taken into account at all.")]
    #[FunctionReturn('True / False')]
    #[Related('Election::setImplicitRanking')]
    public function getImplicitRankingRule(): bool
    {
        return $this->ImplicitRanking;
    }

    #[PublicAPI]
    #[Description("Set the setting and reset all result data.\nIf it is True then all votes expressing a partial ranking are understood as implicitly placing all the non-mentioned candidates exequos on a last rank.\nIf it is false, then the candidates not ranked, are not taken into account at all.")]
    #[FunctionReturn('Return True')]
    #[Related('Election::getImplicitRankingRule')]
    public function setImplicitRanking(
        #[FunctionParameter('New rule')]
        bool $rule = true
    ): bool {
        $this->ImplicitRanking = $rule;
        $this->cleanupCompute();
        return $this->getImplicitRankingRule();
    }

    #[PublicAPI]
    #[Description("Returns the corresponding setting as currently set (False by default).\nIf it is True then votes vote optionally can use weight otherwise (if false) all votes will be evaluated as equal for this election.")]
    #[FunctionReturn('True / False')]
    #[Related('Election::allowsVoteWeight')]
    public function isVoteWeightAllowed(): bool
    {
        return $this->VoteWeightRule;
    }

    #[PublicAPI]
    #[Description("Set the setting and reset all result data.\nThen the weight of votes (if specified) will be taken into account when calculating the results. Otherwise all votes will be considered equal.\nBy default, the voting weight is not activated and all votes are considered equal.")]
    #[FunctionReturn('Return True')]
    #[Related('Election::isVoteWeightAllowed')]
    public function allowsVoteWeight(
        #[FunctionParameter('New rule')]
        bool $rule = true
    ): bool {
        $this->VoteWeightRule = $rule;
        $this->cleanupCompute();
        return $this->isVoteWeightAllowed();
    }


    /////////// VOTE CONSTRAINT ///////////

    #[PublicAPI]
    #[Description('Add a constraint rules as a valid class path.')]
    #[FunctionReturn('True on success.')]
    #[Throws(VoteConstraintException::class)]
    #[Example('Documentation Book - Vote Constraints', 'https://github.com/julien-boudry/Condorcet/wiki/II-%23-C.-Result-%23-5.-Vote-Constraints')]
    #[Related('Election::getConstraints', 'Election::clearConstraints', 'Election::testIfVoteIsValidUnderElectionConstraints')]
    public function addConstraint(
        #[FunctionParameter('A valid class path. Class must extend VoteConstraint class')]
        string $constraintClass
    ): bool {
        if (!class_exists($constraintClass)) {
            throw new VoteConstraintException('class is not defined');
        } elseif (!is_subclass_of($constraintClass, VoteConstraintInterface::class)) {
            throw new VoteConstraintException('class is not a valid subclass');
        } elseif (\in_array(needle: $constraintClass, haystack: $this->getConstraints(), strict: true)) {
            throw new VoteConstraintException('class is already registered');
        }

        $this->cleanupCompute();


        $this->Constraints[] = $constraintClass;

        return true;
    }

    #[PublicAPI]
    #[Description('Get active constraints list.')]
    #[FunctionReturn('Array with class name of each active constraint. Empty array if there is not.')]
    #[Example('Documentation Book - Vote Constraints', 'https://github.com/julien-boudry/Condorcet/wiki/II-%23-C.-Result-%23-5.-Vote-Constraints')]
    #[Related('Election::clearConstraints', 'Election::addConstraints', 'Election::testIfVoteIsValidUnderElectionConstraints')]
    public function getConstraints(): array
    {
        return $this->Constraints;
    }

    #[PublicAPI]
    #[Description('Clear all constraints rules and clear previous results.')]
    #[FunctionReturn('Return True.')]
    #[Example('Documentation Book - Vote Constraints', 'https://github.com/julien-boudry/Condorcet/wiki/II-%23-C.-Result-%23-5.-Vote-Constraints')]
    #[Related('Election::getConstraints', 'Election::addConstraints', 'Election::testIfVoteIsValidUnderElectionConstraints')]
    public function clearConstraints(): bool
    {
        $this->Constraints = [];

        $this->cleanupCompute();

        return true;
    }

    #[PublicAPI]
    #[Description('Test if a vote is valid with these election constraints.')]
    #[FunctionReturn('Return True if vote will pass the constraints rules, else False.')]
    #[Example('Documentation Book - Vote Constraints', 'https://github.com/julien-boudry/Condorcet/wiki/II-%23-C.-Result-%23-5.-Vote-Constraints')]
    #[Related('Election::getConstraints', 'Election::addConstraints', 'Election::clearConstraints')]
    public function testIfVoteIsValidUnderElectionConstraints(
        #[FunctionParameter('A vote. Not necessarily registered in this election')]
        Vote $vote
    ): bool {
        foreach ($this->Constraints as $oneConstraint) {
            if ($oneConstraint::isVoteAllow($this, $vote) === false) {
                return false;
            }
        }

        return true;
    }


    /////////// STV SEATS ///////////

    #[PublicAPI]
    #[Description('Get number of Seats for STV methods.')]
    #[FunctionReturn('Number of seats.')]
    #[Related('Election::setNumberOfSeats', 'Result::getNumberOfSeats')]
    public function getNumberOfSeats(): int
    {
        return $this->Seats;
    }

    #[PublicAPI]
    #[Description('Set number of Seats for STV methods.')]
    #[FunctionReturn('Number of seats.')]
    #[Throws(NoSeatsException::class)]
    #[Related('Election::getNumberOfSeats')]
    public function setNumberOfSeats(
        #[FunctionParameter('The number of seats for proportional methods.')]
        int $seats
    ): int {
        if ($seats > 0) {
            $this->cleanupCompute();

            $this->Seats = $seats;
        } else {
            throw new NoSeatsException;
        }

        return $this->Seats;
    }


    /////////// LARGE ELECTION MODE ///////////

    #[PublicAPI]
    #[Description('Import and enable an external driver to store vote on very large election.')]
    #[FunctionReturn('True if success. Else throw an Exception.')]
    #[Throws(DataHandlerException::class)]
    #[Example('[Documentation Book - DataHandler]', 'https://github.com/julien-boudry/Condorcet/blob/master/examples/specifics_examples/use_large_election_external_database_drivers.php')]
    #[Related('Election::removeExternalDataHandler')]
    public function setExternalDataHandler(
        #[FunctionParameter('Driver object')]
        DataHandlerDriverInterface $driver
    ): bool {
        if (!$this->Votes->isUsingHandler()) {
            $this->Votes->importHandler($driver);
            return true;
        } else {
            throw new DataHandlerException('external data handler cannot be imported');
        }
    }

    #[PublicAPI]
    #[Description('Remove an external driver to store vote on very large election. And import his data into classical memory.')]
    #[FunctionReturn('True if success. Else throw an Exception.')]
    #[Throws(DataHandlerException::class)]
    #[Related('Election::setExternalDataHandler')]
    public function removeExternalDataHandler(): bool
    {
        if ($this->Votes->isUsingHandler()) {
            $this->Votes->closeHandler();
            return true;
        } else {
            throw new DataHandlerException('external data handler cannot be removed, is already in use');
        }
    }


    /////////// STATE ///////////

    #[PublicAPI]
    #[Description('Get the election process level.')]
    #[FunctionReturn("ElectionState::CANDIDATES_REGISTRATION: Candidate registered state. No votes, no result, no cache.\nElectionState::VOTES_REGISTRATION: Voting registration phase. Pairwise cache can exist thanks to dynamic computation if voting phase continue after the first get result. But method result never exist.\n3: Result phase: Some method result may exist, pairwise exist. An election will return to Phase 2 if votes are added or modified dynamically.")]
    #[Related('Election::setStateToVote')]
    public function getState(): ElectionState
    {
        return $this->State;
    }

    // Close the candidate config, be ready for voting (optional)
    #[PublicAPI]
    #[Description("Force the election to get back to state 2. See Election::getState.\nIt is not necessary to use this method. The election knows how to manage its phase changes on its own. But it is a way to clear the cache containing the results of the methods.\n\nIf you are on state 1 (candidate registering), it's will close this state and prepare election to get firsts votes.\nIf you are on state 3. The method result cache will be clear, but not the pairwise. Which will continue to be updated dynamically.")]
    #[FunctionReturn('Always True.')]
    #[Throws(NoCandidatesException::class, ResultRequestedWithoutVotesException::class)]
    #[Related('Election::getState')]
    public function setStateToVote(): bool
    {
        if ($this->State === ElectionState::CANDIDATES_REGISTRATION) {
            if (empty($this->Candidates)) {
                throw new NoCandidatesException;
            }

            $this->State = ElectionState::VOTES_REGISTRATION;
            $this->preparePairwiseAndCleanCompute();
        }

        return true;
    }

    // Prepare to compute results & caching system
    protected function preparePairwiseAndCleanCompute(): bool
    {
        if ($this->Pairwise === null && $this->State === ElectionState::VOTES_REGISTRATION) {
            $this->cleanupCompute();

            // Do Pairwise
            $this->makePairwise();

            // Return
            return true;
        } elseif ($this->State === ElectionState::CANDIDATES_REGISTRATION || $this->countVotes() === 0) {
            throw new ResultRequestedWithoutVotesException;
        } else {
            return false;
        }
    }
}
