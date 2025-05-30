<?php declare(strict_types=1);
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

namespace CondorcetPHP\Condorcet;

use CondorcetPHP\Condorcet\Algo\Pairwise\Pairwise;
use CondorcetPHP\Condorcet\Throwable\{DataHandlerException, ElectionObjectVersionMismatchException, NoCandidatesException, NoSeatsException, ResultRequestedWithoutVotesException, VoteConstraintException};
use CondorcetPHP\Condorcet\ElectionProcess\{CandidatesProcess, ElectionState, ResultsProcess, VotesProcess};
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

    /**
     * @api
     */
    public const int MAX_CANDIDATE_NAME_LENGTH = 100; // Max length for candidate name string (UTF-8)

    /**
     * Maximum input for each use of Election::parseCandidate && Election::parseVote. Will throw an exception if exceeded.
     * Null will deactivate this functionality. An integer will set the limit.
     * @api
     */
    public static ?int $maxParseIteration = null;

    /**
     * Add a limitation on Election::addVote and related methods. You can't add new votes if the number of registered votes is equal or superior to this limit.
     * Null will deactivate this functionality. An integer will set the limit.
     * @api
     * @see Election::maxParseIteration
     */
    public static ?int $maxVotePerElection = null;

    protected static bool $checksumMode = false;

    /////////// STATICS METHODS ///////////


    // Mechanics
    /**
     * Get the election process level.
     * @api
     * @return ElectionState ElectionState::CANDIDATES_REGISTRATION: Candidate registered state. No votes, no result, no cache.
     *                      ElectionState::VOTES_REGISTRATION: Voting registration phase. Pairwise cache can exist thanks to dynamic computation if voting phase continue after the first get result. But method result never exist.
     *                      ElectionState::RESULT_COMPUTING: Result phase: Some method result may exist, pairwise exist. An election will dynamically return to Phase 2 if votes are added or modified.
     * @see Election::setStateToVote()
     */
    public protected(set) ElectionState $state = ElectionState::CANDIDATES_REGISTRATION;
    protected readonly Timer_Manager $timer;

    /**
     * Returns the corresponding setting as currently set (False by default).
     * If it is True then votes vote optionally can use weight otherwise (if false) all votes will be evaluated as equal for this election.
     * @api
     * @see Election::authorizeVoteWeight()
     */
    public bool $authorizeVoteWeight = false {
        set {
            $this->authorizeVoteWeight = $value;
            $this->resetComputation();
        }
    }

    /**
     * Alias as a method for Election->authorizeVoteWeight
     * @param $authorized New rule.
     * @see Election::authorizeVoteWeight
     * @api
     */
    public function authorizeVoteWeight(bool $authorized = true): static
    {
        $this->authorizeVoteWeight = $authorized;

        return $this;
    }


    /**
     * Get or set seats to elect count for proportionals methods like STV.
     * @api
     * @throws NoSeatsException
     * @see Election::setSeatsToElect(), Result::seats
     */
    public int $seatsToElect = 100 {
        set(int $seats) {
            if ($seats > 0) {
                $this->seatsToElect = $seats;

                $this->resetComputation();
            } else {
                throw new NoSeatsException;
            }
        }
    }

    /**
     * @api
     */
    public protected(set) array $votesConstraints = [];

    /**
     * @api
     */
    public string $hash { get => $this->getChecksum(); }

    // -------
    /**
     * Build a new Election.
     * @api
     */
    public function __construct()
    {
        $this->candidates = [];
        $this->Votes = new VotesManager($this);
        $this->timer = new Timer_Manager;
    }

    public function __serialize(): array
    {
        // Don't include others data
        $include = [
            'Candidates' => $this->candidates,
            'Votes' => $this->Votes,

            'State' => $this->state,
            'objectVersion' => $this->buildByCondorcetVersion,
            'nextAutomaticCandidateName' => $this->nextAutomaticCandidateName,

            'ImplicitRanking' => $this->implicitRankingRule,
            'VoteWeightRule' => $this->authorizeVoteWeight,
            'Constraints' => $this->votesConstraints,

            'Pairwise' => $this->Pairwise,
            'Calculator' => $this->MethodsComputation,
        ];

        if (!self::$checksumMode) {
            $include += ['timer' => $this->timer];
        }

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

        $this->candidates = $data['Candidates'];
        $this->Votes = $data['Votes'];
        $this->Votes->setElection($this);
        $this->registerAllLinks();

        $this->nextAutomaticCandidateName = $data['nextAutomaticCandidateName'];
        $this->state = $data['State'];
        $this->buildByCondorcetVersion = $data['objectVersion'];

        $this->implicitRankingRule = $data['ImplicitRanking'];
        $this->authorizeVoteWeight = $data['VoteWeightRule'];
        $this->votesConstraints = $data['Constraints'];

        $this->Pairwise = $data['Pairwise'];
        $this->Pairwise?->setElection($this);

        $this->MethodsComputation = $data['Calculator'] ?? [];
        foreach ($this->MethodsComputation as $methodObject) {
            $methodObject->setElection($this);
        }

        $this->timer = $data['timer'];
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
    /**
     * Returns the cumulated computation runtime of this object. Include only computation related methods.
     * @api
     * @return float Timer
     * @book \CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\BookLibrary::Timer
     * @see Election::getLastTimer()
     */
    public function getGlobalTimer(): float
    {
        return $this->timer->getGlobalTimer();
    }
    /**
     * Return the last computation runtime (typically after a getResult() call.). Include only computation related methods.
     * @api
     * @return ?float Timer
     * @book \CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\BookLibrary::Timer
     * @see Election::getGlobalTimer()
     */
    public function getLastTimer(): ?float
    {
        return $this->timer->getLastTimer();
    }
    /**
     * Get the Timer manager object.
     * @api
     * @return Timer_Manager An CondorcetPHP\Condorcet\Timer\Manager object using by this election.
     * @see Election::getGlobalTimer(), Election::getLastTimer()
     */
    public function getTimerManager(): Timer_Manager
    {
        return $this->timer;
    }
    /**
     * SHA-2 256 checksum of following internal data:
     * * Candidates
     * * Votes list & tags
     * * Computed data (pairwise, algorithm cache, stats)
     * * Class version (major version like 3.4)
     *
     * Can be powerfull to check integrity and security of an election. Or working with serialized object.
     * @api
     * @return string SHA-2 256 bits Hexadecimal
     * @book \CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\BookLibrary::Crypto
     */
    public function getChecksum(): string
    {
        self::$checksumMode = true;

        $r = hash_init('sha256');

        foreach ($this->candidates as $value) {
            hash_update($r, (string) $value);
        }

        foreach ($this->Votes as $value) {
            hash_update($r, (string) $value);
        }

        if ($this->Pairwise !== null) {
            hash_update($r, serialize($this->Pairwise->getExplicitPairwise()));
        }

        hash_update($r, $this->getCondorcetBuilderVersion(true));

        self::$checksumMode = false;

        return hash_final($r);
    }


    /////////// LINKS REGULATION ///////////

    protected function registerAllLinks(): void
    {
        foreach ($this->candidates as $value) {
            $value->registerLink($this);
        }

        foreach ($this->Votes as $value) {
            $value->registerLink($this);
        }
    }


    /////////// IMPLICIT RANKING & VOTE WEIGHT ///////////

    // Params
    /**
     * The corresponding setting as currently set (True by default).
     * If it is True then all votes expressing a partial ranking are understood as implicitly placing all the non-mentioned candidates exequos on a last rank.
     * If it is false, then the candidates not ranked, are not taken into account at all.
     * @api
     * @see Election::implicitRankingRule()
     */
    public bool $implicitRankingRule = true {
        set {
            $this->implicitRankingRule = $value;
            $this->resetComputation();
        }
    }

    /**
     * Act like public property Election->implicitRankingRule, but return the election object.
     * @api
     * @see Election::implicitRankingRule
     * @param $rule New rule.
     */
    public function implicitRankingRule(
        bool $rule
    ): static {
        $this->implicitRankingRule = $rule;

        return $this;
    }

    /////////// VOTE CONSTRAINT ///////////
    /**
     * Add a constraint rules as a valid class path.
     * @param $constraintClass A valid class path. Class must extend VoteConstraint class.
     * @throws VoteConstraintException
     * @api
     * @see Election::getConstraints(), Election::clearConstraints(), Election::isVoteValidUnderConstraints()
     * @book \CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\BookLibrary::VotesConstraints
     */
    public function addConstraint(
        string $constraintClass
    ): static {
        if (!class_exists($constraintClass)) {
            throw new VoteConstraintException('class is not defined');
        } elseif (!is_subclass_of($constraintClass, VoteConstraintInterface::class)) {
            throw new VoteConstraintException('class is not a valid subclass');
        } elseif (\in_array(needle: $constraintClass, haystack: $this->getConstraints(), strict: true)) {
            throw new VoteConstraintException('class is already registered');
        }

        $this->votesConstraints[] = $constraintClass;

        $this->resetComputation();

        return $this;
    }
    /**
     * Get active constraints list.
     * @api
     * @return array Array with class name of each active constraint. Empty array if there is not.
     * @book \CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\BookLibrary::VotesConstraints
     * @see Election::clearConstraints(), Election::addConstraint(), Election::isVoteValidUnderConstraints()
     */
    public function getConstraints(): array
    {
        return $this->votesConstraints;
    }

    /**
     * Clear all constraints rules and clear previous results.
     * @api
     * @book \CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\BookLibrary::VotesConstraints
     * @see Election::getConstraints(), Election::addConstraint(), Election::isVoteValidUnderConstraints()
     */
    public function clearConstraints(): static
    {
        $this->votesConstraints = [];

        $this->resetComputation();

        return $this;
    }

    /**
     * Test if a vote is valid with these election constraints.
     * @api
     * @book \CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\BookLibrary::VotesConstraints
     * @see Election::getConstraints(), Election::addConstraint(), Election::clearConstraints()
     * @param $vote A vote. Not necessarily registered in this election.
     * @return bool Return True if vote will pass the constraints rules, else False.
     */
    public function isVoteValidUnderConstraints(
        Vote $vote
    ): bool {
        foreach ($this->votesConstraints as $oneConstraint) {
            if ($oneConstraint::isVoteAllowed($this, $vote) === false) {
                return false;
            }
        }

        return true;
    }


    /////////// STV SEATS ///////////

    /**
     * Set count of seats to elects for STV methods.
     * @api
     * @see Election::seatsToElect
     * @param $seats The count of seats to elect for proportional methods.
     * @throws NoSeatsException
     */
    public function setSeatsToElect(
        int $seats
    ): static {
        $this->seatsToElect = $seats;

        return $this;
    }


    /////////// LARGE ELECTION MODE ///////////
    /**
     * Import and enable an external driver to store vote on very large election.
     * @api
     * @book \CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\BookLibrary::MassiveElection
     * @see Election::removeExternalDataHandler()
     * @param $driver Driver object.
     * @throws DataHandlerException
     */
    public function setExternalDataHandler(
        DataHandlerDriverInterface $driver
    ): static {
        if (!$this->Votes->hasExternalHandler()) {
            $this->Votes->importHandler($driver);

            return $this;
        } else {
            throw new DataHandlerException('external data handler cannot be imported');
        }
    }

    /**
     * Remove an external driver to store vote on very large election. And import his data into classical memory.
     * @api
     * @throws DataHandlerException
     * @return bool True if success. Else throw an Exception.
     * @see Election::setExternalDataHandler()
     */
    public function removeExternalDataHandler(): bool
    {
        if ($this->Votes->hasExternalHandler()) {
            $this->Votes->closeHandler();
            return true;
        } else {
            throw new DataHandlerException('external data handler cannot be removed, is already in use');
        }
    }


    /////////// STATE ///////////


    // Close the candidate config, be ready for voting (optional)
    /**
     * Force the election to get back to state 2.
     * It is not necessary to use this method. The election knows how to manage its phase changes on its own. But it is a way to clear the cache containing the results of the methods.
     *
     * If you are on state 1 (candidate registering), it's will close this state and prepare election to get firsts votes.
     * If you are on state 3. The method result cache will be clear, but not the pairwise. Which will continue to be updated dynamically.
     * @api
     * @throws NoCandidatesException
     * @throws ResultRequestedWithoutVotesException
     * @return true Always True.
     * @see Election::state
     */
    public function setStateToVote(): true
    {
        if ($this->state === ElectionState::CANDIDATES_REGISTRATION) {
            if (empty($this->candidates)) {
                throw new NoCandidatesException;
            }

            $this->state = ElectionState::VOTES_REGISTRATION;
            $this->preparePairwiseAndCleanCompute();
        }

        return true;
    }

    // Prepare to compute results & caching system
    protected function preparePairwiseAndCleanCompute(): bool
    {
        if ($this->Pairwise === null && $this->state === ElectionState::VOTES_REGISTRATION) {
            $this->resetComputation();

            // Return
            return true;
        } elseif ($this->state === ElectionState::CANDIDATES_REGISTRATION || $this->countVotes() === 0) {
            throw new ResultRequestedWithoutVotesException;
        } else {
            return false;
        }
    }
}
