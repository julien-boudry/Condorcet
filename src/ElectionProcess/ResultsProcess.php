<?php

/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\ElectionProcess;

use CondorcetPHP\Condorcet\{Candidate, Condorcet, Result};
use CondorcetPHP\Condorcet\Algo\Pairwise\{FilteredPairwise, Pairwise};
use CondorcetPHP\Condorcet\Algo\StatsVerbosity;
use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\BookLibrary;
use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{Book, Description, FunctionParameter, FunctionReturn, InternalModulesAPI, PublicAPI, Related, Throws};
use CondorcetPHP\Condorcet\Throwable\AlgorithmException;
use CondorcetPHP\Condorcet\Timer\Chrono as Timer_Chrono;
use Random\Randomizer;

// Manage Results for Election class
trait ResultsProcess
{
    /////////// CONSTRUCTOR ///////////

    // Result
    protected ?Pairwise $Pairwise = null;
    protected ?array $Calculator = null;

    #[PublicAPI]
    #[Description('The current level of stats verbosity for this election object. Look at Election->setStatsVerbosity method for more informations.')]
    #[FunctionReturn('The current verbosity level for this election object.')]
    public protected(set) StatsVerbosity $StatsVerbosity = StatsVerbosity::STD;


    /////////// GET RESULTS ///////////

    // Generic function for default result with ability to change default object method
    #[PublicAPI]
    #[Description("Get a full ranking from an advanced Condorcet method.\n*Have a look on the [supported method](https://github.com/julien-boudry/Condorcet/wiki/I-%23-Installation-%26-Basic-Configuration-%23-2.-Condorcet-Methods), or create [your own algorithm](https://github.com/julien-boudry/Condorcet/wiki/III-%23-C.-Extending-Condorcet-%23-1.-Add-your-own-ranking-algorithm).*")]
    #[FunctionReturn('An Condorcet/Result Object (implementing ArrayAccess and Iterator, can be use like an array ordered by rank)')]
    #[Throws(AlgorithmException::class)]
    #[Book(BookLibrary::ResultsRanking)]
    #[Related('Election::getWinner', 'Election::getResult', 'Condorcet::getDefaultMethod')]
    public function getResult(
        #[FunctionParameter('Not required for use election default method. Set the string name of the algorithm for use of a specific one.')]
        ?string $method = null,
        #[FunctionParameter('Array of option for some methods. Look at each method documentation.')]
        array $methodOptions = []
    ): Result {
        $methodOptions = self::formatResultOptions($methodOptions);

        // Filter if tag is provided & return
        if ($methodOptions['%tagFilter']) {
            $chrono = (Condorcet::$UseTimer === true) ? new Timer_Chrono($this->timer, 'GetResult with filter') : null;

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

        $chrono = (Condorcet::$UseTimer === true) ? new Timer_Chrono($this->timer) : null;

        if ($method === null) {
            $this->initResult(Condorcet::getDefaultMethod());
            $result = $this->Calculator[Condorcet::getDefaultMethod()]->getResult();
        } elseif ($wanted_method = Condorcet::getMethodClass($method)) {
            $this->initResult($wanted_method);
            $result = $this->Calculator[$wanted_method]->getResult();
        } else {
            throw new AlgorithmException($method);
        }

        ($chrono !== null) && $chrono->setRole('GetResult for ' . $method);

        return $result;
    }


    #[PublicAPI]
    #[Description('Get the natural Condorcet winner if there is one. Alternatively you can get the winner(s) from an advanced Condorcet algorithm.')]
    #[FunctionReturn("Candidate object given. Null if there are no available winner or loser.\n\nIf you use an advanced method instead of Natural, you can get an array with multiples winners.\n\nThrow an exception on error.")]
    #[Book(BookLibrary::ResultsWinner)]
    #[Related('Election::getCondorcetWinner', 'Election::getLoser', 'Election::getResult')]
    public function getWinner(
        #[FunctionParameter("*Only if not null:    *\n\nThe winner will be provided by an advanced algorithm of an available advanced Condorcet method. For most of them, it will be the same as the Condorcet Marquis there. But if it does not exist, it may be different; and in some cases they may be multiple.    \n\nIf null, Natural Condorcet algorithm will be use.")]
        ?string $method = null
    ): array|Candidate|null {
        $algo = Condorcet::condorcetBasicSubstitution($method);

        // -------

        if ($algo === Condorcet::CONDORCET_BASIC_CLASS) {
            (Condorcet::$UseTimer === true) && new Timer_Chrono($this->timer, 'GetWinner for CondorcetBasic');
            $this->initResult($algo);
            $result = $this->Calculator[$algo]->getWinner();

            return ($result === null) ? null : $this->getCandidateObjectFromKey($result);
        } else {
            return $this->getResult($algo)->getWinner();
        }
    }


    #[PublicAPI]
    #[Description('Get the natural Condorcet loser if there is one. Alternatively you can get the loser(s) from an advanced Condorcet algorithm.')]
    #[FunctionReturn("Candidate object given. Null if there are no available winner or loser.\n\nIf you use an advanced method instead of Natural, you can get an array with multiples losers.\n\nThrow an exception on error.")]
    #[Book(BookLibrary::ResultsWinner)]
    #[Related('Election::getWinner', 'Election::getResult')]
    public function getLoser(
        #[FunctionParameter("*Only if not nulle:*    \n\nThe loser will be provided by an advanced algorithm of an available advanced Condorcet method. For most of them, it will be the same as the Condorcet Marquis there. But if it does not exist, it may be different; and in some cases they may be multiple.    \n\n        If null, Natural Condorcet algorithm will be use.")]
        ?string $method = null
    ): array|Candidate|null {
        $algo = Condorcet::condorcetBasicSubstitution($method);

        // -------

        if ($algo === Condorcet::CONDORCET_BASIC_CLASS) {
            (Condorcet::$UseTimer === true) && new Timer_Chrono($this->timer, 'GetLoser for CondorcetBasic');
            $this->initResult($algo);
            $result = $this->Calculator[$algo]->getLoser();

            return ($result === null) ? null : $this->getCandidateObjectFromKey($result);
        } else {
            return $this->getResult($algo)->getLoser();
        }
    }

    #[PublicAPI]
    #[Description('Get the natural Condorcet winner if there is one.')]
    #[FunctionReturn('Candidate object given. Null if there are no available winner.')]
    #[Book(BookLibrary::ResultsWinner)]
    #[Related('Election::getCondorcetLoser', 'Election::getWiner', 'Election::getResult')]
    public function getCondorcetWinner(): ?Candidate
    {
        return $this->getWinner(null);
    }

    #[PublicAPI]
    #[Description('Get the natural Condorcet loser if there is one.')]
    #[FunctionReturn('Candidate object given. Null if there are no available loser.')]
    #[Book(BookLibrary::ResultsWinner)]
    #[Related('Election::getCondorcetWinner', 'Election::getLoser', 'Election::getResult')]
    public function getCondorcetLoser(): ?Candidate
    {
        return $this->getLoser(null);
    }

    #[PublicAPI]
    #[Description('Return the Pairwise.')]
    #[FunctionReturn('Pairwise object.')]
    #[Book(BookLibrary::Results)]
    #[Related('Election::getExplicitPairwise', 'Election::getResult')]
    public function getPairwise(): Pairwise
    {
        if ($this->Pairwise === null) {
            $this->preparePairwiseAndCleanCompute();
        }

        return $this->Pairwise;
    }

    #[InternalModulesAPI]
    #[Description('Get a pairwise object filtered by tags. Not any votes updates are provided to the object.')]
    #[FunctionReturn('Return a Pairwise filtered by tags')]
    #[Related('Election::getPairwise')]
    public function getFilteredPairwiseByTags(
        #[FunctionParameter('Tags as string separated by commas or array')]
        array|string $tags,
        #[FunctionParameter('Votes with these tags or without')]
        bool|int $with = true
    ): FilteredPairwise {
        return new FilteredPairwise($this, $tags, $with);
    }

    #[PublicAPI]
    #[Description('Return the Pairwise.')]
    #[FunctionReturn('Pairwise as an explicit array .')]
    #[Related('Election::getPairwise', 'Election::getResult')]
    public function getExplicitPairwise(): array
    {
        return $this->getPairwise()->getExplicitPairwise();
    }

    #[PublicAPI]
    #[Description('Get a pairwise filtered by tags.')]
    #[FunctionReturn('Return a Pairwise filtered by tags')]
    #[Related('Election::getPairwise')]
    public function getExplicitFilteredPairwiseByTags(
        #[FunctionParameter('Tags as string separated by commas or array')]
        array|string $tags,
        #[FunctionParameter('Minimum number of specified tags that votes must include, or 0 for only votes without any specified tags')]
        bool|int $with = 1
    ): array {
        return $this->getFilteredPairwiseByTags($tags, $with)->getExplicitPairwise();
    }

    // Generic function for default result with ability to change default object method
    #[PublicAPI]
    #[Description('Set an option to a method module and reset his cache for this election object. Be aware that this option applies to all election objects and remains in memory.')]
    #[FunctionReturn('True on success. Else False.')]
    #[Related('Result::getMethodOptions')]
    public function setMethodOption(
        #[FunctionParameter('Method name or class path')]
        string $method,
        #[FunctionParameter('Option name')]
        string $optionName,
        #[FunctionParameter('Option Value')]
        array|\BackedEnum|int|float|string|Randomizer $optionValue
    ): bool {
        if ($method = Condorcet::getMethodClass($method)) {
            $method::setOption($optionName, $optionValue);
            unset($this->Calculator[$method]);

            return true;
        } else {
            return false;
        }
    }

    #[PublicAPI]
    #[Description('Set a verbosity level for Result->statsVerbosity on returning Result objects. High level can slow down processing and use more memory (many more) than LOW and STD (default) level on somes methods.')]
    #[Related('Election::getVerbosity', 'Result::getVerbosity')]
    public function setStatsVerbosity(
        #[FunctionParameter('A verbosity level')]
        StatsVerbosity $StatsVerbosity
    ): void {
        if ($StatsVerbosity !== $this->StatsVerbosity) {
            $this->cleanupCalculator();
        }

        $this->StatsVerbosity = $StatsVerbosity;
    }



    /////////// MAKE RESULTS ///////////

    #[PublicAPI]
    #[Description('Really similar to Election::getResult() but not return anything. Just calculates silently and fill the cache.')]
    #[Related('Election::getWinner', 'Election::getResult', 'Condorcet::getDefaultMethod')]
    public function computeResult(
        #[FunctionParameter('Not requiered for use object default method. Set the string name of the algorithm for use an specific one')]
        ?string $method = null
    ): void {
        $this->getResult($method);
    }

    protected function makePairwise(): void
    {
        $this->cleanupCalculator();
        $this->Pairwise = new Pairwise($this);
    }

    protected function initResult(string $class): void
    {
        if (!isset($this->Calculator[$class])) {
            $this->Calculator[$class] = new $class($this);
        }
    }

    public function debugGetCalculator(): ?array
    {
        return $this->Calculator;
    }

    // Cleanup results to compute again with new votes
    protected function cleanupCompute(): void
    {
        // Algos
        $this->cleanupCalculator();

        // Clean pairwise
        if ($this->state === ElectionState::VOTES_REGISTRATION) {
            $this->makePairwise();
        } else {
            $this->Pairwise = null;
        }
    }

    public function cleanupCalculator(): void
    {
        $this->Calculator = null;
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
