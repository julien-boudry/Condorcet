<?php declare(strict_types=1);
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

namespace CondorcetPHP\Condorcet\ElectionProcess;

use CondorcetPHP\Condorcet\{Candidate, Condorcet, Result};
use CondorcetPHP\Condorcet\Algo\{MethodInterface, StatsVerbosity};
use CondorcetPHP\Condorcet\Algo\Pairwise\{FilteredPairwise, Pairwise};
use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{Book, Throws};
use CondorcetPHP\Condorcet\Throwable\VotingMethodIsNotImplemented;
use CondorcetPHP\Condorcet\Timer\Chrono as Timer_Chrono;
use Random\Randomizer;

/**
 * Manage Results for an Election class
 */
trait ResultsProcess
{
    /////////// CONSTRUCTOR ///////////

    // Result
    protected ?Pairwise $Pairwise = null;
    /** @var ?array<MethodInterface> */
    protected ?array $MethodsComputation = null;

    /**
     * The current level of stats verbosity for this election object. Look at Election->setStatsVerbosity method for more informations.
     * @api
     * @return StatsVerbosity The current verbosity level for this election object.
     */
    public protected(set) StatsVerbosity $statsVerbosity = StatsVerbosity::STD;

    /////////// GET RESULTS ///////////

    // Generic function for default result with ability to change default object method
    /**
     * Get a full ranking from an advanced Condorcet method.
     * @api
     * @book \CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\BookLibrary::ResultsRanking
     * @see Election::getWinner, Election::getResult, Condorcet::getDefaultMethod
     * @param $method Not required for use election default method. Set the string name of the algorithm for use of a specific one.
     * @param $methodOptions Array of option for some methods. Look at each method documentation.
     * @throws VotingMethodIsNotImplemented
     * @return mixed An Condorcet/Result Object (implementing ArrayAccess and Iterator, can be use like an array ordered by rank)
     */
    public function getResult(
        ?string $method = null,
        array $methodOptions = []
    ): Result {
        $methodOptions = self::formatResultOptions($methodOptions);

        // Filter if tag is provided & return
        if ($methodOptions['%tagFilter']) {
            $chrono = (Condorcet::$UseTimer) ? new Timer_Chrono($this->timer, 'GetResult with filter') : null;

            $filter = new self;

            foreach ($this->getCandidatesList() as $candidate) {
                $filter->addCandidate($candidate);
            }

            foreach ($this->getVotesList(tags: $methodOptions['tags'], with: $methodOptions['withTag']) as $vote) {
                $filter->addVote($vote);
            }

            unset($chrono);

            return $filter->getResult($method);
        }

        ////// Start //////

        // Prepare
        $this->preparePairwiseAndCleanCompute();

        // -------

        $chrono = (Condorcet::$UseTimer) ? new Timer_Chrono($this->timer) : null;

        if ($method === null) {
            $this->initResult(Condorcet::getDefaultMethod());
            $result = $this->MethodsComputation[Condorcet::getDefaultMethod()]->getResult();
        } elseif ($wanted_method = Condorcet::getMethodClass($method)) {
            $this->initResult($wanted_method);
            $result = $this->MethodsComputation[$wanted_method]->getResult();
        } else {
            throw new VotingMethodIsNotImplemented($method);
        }

        if ($chrono !== null) {
            $chrono->setRole('GetResult for ' . $method);
        }

        return $result;
    }
    /**
     * Get the natural Condorcet winner if there is one. Alternatively you can get the winner(s) from an advanced Condorcet algorithm.
     * @api
     * @book \CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\BookLibrary::ResultsWinner
     * @see Election::getCondorcetWinner, Election::getLoser, Election::getResult
     * @param $method Only if not null the winner will be provided by an advanced algorithm of an available advanced Condorcet method. For most of them, it will be the same as the Condorcet Marquis there. But if it does not exist, it may be different; and in some cases they may be multiple. If null, Natural Condorcet algorithm will be use.
     * @return mixed Candidate object given. Null if there are no available winner or loser.
     *
     *               If you use an advanced method instead of Natural, you can get an array with multiples winners.
     *
     *               Throw an exception on error.
     */
    public function getWinner(
        ?string $method = null
    ): array|Candidate|null {
        $algo = Condorcet::validateAlternativeWinnerLoserMethod($method);

        // -------

        if ($algo === Condorcet::CONDORCET_BASIC_CLASS) {
            if (Condorcet::$UseTimer) {
                new Timer_Chrono($this->timer, 'GetWinner for CondorcetBasic');
            }
            $this->initResult($algo);
            $result = $this->MethodsComputation[$algo]->getWinner(); // @phpstan-ignore method.notFound

            return ($result === null) ? null : $this->getCandidateObjectFromKey($result);
        } else {
            return $this->getResult($algo)->Winner;
        }
    }
    /**
     * Get the natural Condorcet loser if there is one. Alternatively you can get the loser(s) from an advanced Condorcet algorithm.
     * @api
     * @book \CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\BookLibrary::ResultsWinner
     * @see Election::getWinner, Election::getResult
     * @param $method Only if not null the loser will be provided by an advanced algorithm of an available advanced Condorcet method. For most of them, it will be the same as the Condorcet Marquis there. But if it does not exist, it may be different; and in some cases they may be multiple. If null, Natural Condorcet algorithm will be use.
     * @return mixed Candidate object given. Null if there are no available winner or loser.
     *
     *               If you use an advanced method instead of Natural, you can get an array with multiples losers.
     *
     *               Throw an exception on error.
     */
    public function getLoser(
        ?string $method = null
    ): array|Candidate|null {
        $algo = Condorcet::validateAlternativeWinnerLoserMethod($method);

        // -------

        if ($algo === Condorcet::CONDORCET_BASIC_CLASS) {
            if (Condorcet::$UseTimer) {
                new Timer_Chrono($this->timer, 'GetLoser for CondorcetBasic');
            }
            $this->initResult($algo);
            $result = $this->MethodsComputation[$algo]->getLoser(); // @phpstan-ignore method.notFound

            return ($result === null) ? null : $this->getCandidateObjectFromKey($result);
        } else {
            return $this->getResult($algo)->Loser;
        }
    }
    /**
     * Get the natural Condorcet winner if there is one.
     * @api
     * @return mixed Candidate object given. Null if there are no available winner.
     * @book \CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\BookLibrary::ResultsWinner
     * @see Election::getCondorcetLoser, Election::getWinner, Election::getResult
     */
    public function getCondorcetWinner(): ?Candidate
    {
        return $this->getWinner(null); // @phpstan-ignore return.type
    }
    /**
     * Get the natural Condorcet loser if there is one.
     * @api
     * @return mixed Candidate object given. Null if there are no available loser.
     * @book \CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\BookLibrary::ResultsWinner
     * @see Election::getCondorcetWinner, Election::getLoser, Election::getResult
     */
    public function getCondorcetLoser(): ?Candidate
    {
        return $this->getLoser(null); // @phpstan-ignore return.type
    }
    /**
     * Return the Pairwise.
     * @api
     * @return mixed Pairwise object.
     * @book \CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\BookLibrary::Results
     * @see Election::getExplicitPairwise, Election::getResult
     */
    public function getPairwise(): Pairwise
    {
        if ($this->Pairwise === null) {
            $this->preparePairwiseAndCleanCompute();
        }

        return $this->Pairwise;
    }
    /**
     * Get a pairwise object filtered by tags. Not any votes updates are provided to the object.
     * @internal
     * @see Election::getPairwise
     * @param $tags Tags as string separated by commas or array.
     * @param $with Votes with these tags or without.
     * @return mixed Return a Pairwise filtered by tags
     */
    public function getFilteredPairwiseByTags(
        array|string $tags,
        bool|int $with = true
    ): FilteredPairwise {
        return new FilteredPairwise($this, $tags, $with);
    }
    /**
     * Return the Pairwise.
     * @api
     * @return mixed Pairwise as an explicit array .
     * @see Election::getPairwise, Election::getResult
     */
    public function getExplicitPairwise(): array
    {
        return $this->getPairwise()->getExplicitPairwise();
    }
    /**
     * Get a pairwise filtered by tags.
     * @api
     * @see Election::getPairwise
     * @param $tags Tags as string separated by commas or array.
     * @param $with Minimum number of specified tags that votes must include, or 0 for only votes without any specified tags.
     * @return mixed Return a Pairwise filtered by tags
     */
    public function getExplicitFilteredPairwiseByTags(
        array|string $tags,
        bool|int $with = 1
    ): array {
        return $this->getFilteredPairwiseByTags($tags, $with)->getExplicitPairwise();
    }

    /**
     * Set an option to a method module and reset his cache for this election object. Be aware that this option applies to all election objects and remains in memory.
     * @api
     * @see Result::methodOptions
     * @param $method Method name or class path.
     * @param $optionName Option name.
     * @param $optionValue Option Value.
     */
    public function setMethodOption(
        string $method,
        string $optionName,
        array|\BackedEnum|int|float|string|Randomizer $optionValue
    ): static {
        if ($method = Condorcet::getMethodClass($method)) {
            $method::setOption($optionName, $optionValue);
            unset($this->MethodsComputation[$method]);

            return $this;
        } else {
            throw new VotingMethodIsNotImplemented(
                'Method ' . $method . ' not found. Please check the name or the class path.'
            );
        }
    }
    /**
     * Set a verbosity level for Result->statsVerbosity on returning Result objects. High level can slow down processing and use more memory (many more) than LOW and STD (default) level on somes methods.
     * @api
     * @see Election::statsVerbosity, Result::statsVerbosity
     * @param $StatsVerbosity A verbosity level.
     */
    public function setStatsVerbosity(
        StatsVerbosity $StatsVerbosity
    ): static {
        if ($StatsVerbosity !== $this->statsVerbosity) {
            $this->resetMethodsComputation();
        }

        $this->statsVerbosity = $StatsVerbosity;

        return $this;
    }



    /////////// MAKE RESULTS ///////////
    /**
     * Really similar to Election::getResult() but not return anything. Just calculates silently and fill the cache.
     * @api
     * @see Election::getWinner, Election::getResult, Condorcet::getDefaultMethod
     * @param $method Not requiered for use object default method. Set the string name of the algorithm for use an specific one.
     */
    public function computeResult(
        ?string $method = null
    ): void {
        $this->getResult($method);
    }

    protected function computePairwise(): void
    {
        $this->resetMethodsComputation();
        $this->Pairwise = new Pairwise($this);
    }

    protected function initResult(string $class): void
    {
        if (!isset($this->MethodsComputation[$class])) {
            $this->MethodsComputation[$class] = new $class($this); // @phpstan-ignore assign.propertyType
        }
    }

    public function debugGetCalculator(): ?array
    {
        return $this->MethodsComputation;
    }

    // Cleanup results to compute again with new votes
    protected function resetComputation(): void
    {
        // Algos
        $this->resetMethodsComputation();

        // Clean pairwise
        if ($this->state === ElectionState::VOTES_REGISTRATION) {
            $this->computePairwise();
        } else {
            $this->Pairwise = null;
        }
    }

    /** @internal */
    public function resetMethodsComputation(): void
    {
        $this->MethodsComputation = null;
    }


    /////////// UTILS ///////////

    protected static function formatResultOptions(array $arg): array
    {
        // About tag filter
        if (isset($arg['tags'])) {
            $arg['%tagFilter'] = true;

            if (!isset($arg['withTag']) || !\is_bool($arg['withTag'])) {
                $arg['withTag'] = true;
            }
        } else {
            $arg['%tagFilter'] = false;
        }

        return $arg;
    }
}
