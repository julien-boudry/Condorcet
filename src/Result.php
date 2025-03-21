<?php

/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

declare(strict_types=1);

namespace CondorcetPHP\Condorcet;

use CondorcetPHP\Condorcet\Algo\{StatsVerbosity};
use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\BookLibrary;
use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{Book, Description, FunctionParameter, FunctionReturn, InternalModulesAPI, PublicAPI, Related, Throws};
use CondorcetPHP\Condorcet\Utils\{CondorcetUtil, VoteUtil};
use CondorcetPHP\Condorcet\Throwable\ResultException;

class Result implements \ArrayAccess, \Countable, \Iterator
{
    use CondorcetVersion;

    // Implement Iterator

    public function rewind(): void
    {
        reset($this->ResultIterator);
    }

    public function current(): array|Candidate
    {
        return current($this->ResultIterator);
    }

    public function key(): int
    {
        return key($this->ResultIterator);
    }

    public function next(): void
    {
        next($this->ResultIterator);
    }

    public function valid(): bool
    {
        return key($this->ResultIterator) !== null;
    }

    // Implement ArrayAccess
/**
 * @throws ResultException
 */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new ResultException;
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->ranking[$offset]);
    }
/**
 * @throws ResultException
 */
    public function offsetUnset(mixed $offset): void
    {
        throw new ResultException;
    }

    public function offsetGet(mixed $offset): array|Candidate|null
    {
        return $this->ranking[$offset] ?? null;
    }

    // Implement Countable

    public function count(): int
    {
        return \count($this->ranking);
    }


    /////////// CONSTRUCTOR ///////////

    protected readonly array $Result;
    protected array $ResultIterator;
    public readonly mixed $Stats;
    protected array $warning = [];

    /**
     * @api
     */
    public private(set) readonly array $ranking;

    /**
     * @api
     */
    public array $rankingAsString {
        get => $this->getResultAsArray(true);
    }

    /**
     * @api
     */
    public private(set) readonly ?int $seats;

    /**
     * @api
     */
    public private(set) readonly array $methodOptions;

    /**
     * @api
     */
    public private(set) readonly ?Candidate $CondorcetWinner;

    /**
     * @api
     */
    public private(set) readonly ?Candidate $CondorcetLoser;

    /**
     * @api
     */
    public private(set) readonly array $pairwise;

    /**
     * @api
     */
    public private(set) readonly float $buildTimestamp;

    /**
     * @api
     */
    public private(set) readonly string $fromMethod;

    /**
     * @api
     */
    public private(set) readonly string $byClass;

    /**
     * @api
     */
    public private(set) readonly StatsVerbosity $statsVerbosity;

    /**
     * @api
     */
    public private(set) readonly string $electionCondorcetVersion;
/**
 * @internal 
 */
    public function __construct(
        string $fromMethod,
        string $byClass,
        Election $election,
        array $result,
        $stats,
        ?int $seats = null,
        array $methodOptions = []
    ) {
        ksort($result, \SORT_NUMERIC);

        $this->Result = $result;
        $this->ResultIterator = $this->ranking = $this->makeUserResult($election);
        $this->Stats = $stats;
        $this->seats = $seats;
        $this->fromMethod = $fromMethod;
        $this->byClass = $byClass;
        $this->statsVerbosity = $election->StatsVerbosity;
        $this->electionCondorcetVersion = $election->getObjectVersion();
        $this->CondorcetWinner = $election->getWinner();
        $this->CondorcetLoser = $election->getLoser();
        $this->pairwise = $election->getExplicitPairwise();
        $this->buildTimestamp = microtime(true);
        $this->methodOptions = $methodOptions;
    }


    /////////// Get Result ///////////
/**
 * Get result as an array
 * @api 
 * @return mixed An ordered multidimensionnal array by rank.
 * @see Election::getResult, Result::getResultAsString
 * @param $convertToString Convert Candidate object to string.
 */
    public function getResultAsArray(
        #[FunctionParameter('Convert Candidate object to string')]
        bool $convertToString = false
    ): array {
        $r = $this->ranking;

        foreach ($r as &$rank) {
            if (\count($rank) === 1) {
                $rank = $convertToString ? (string) $rank[0] : $rank[0];
            } elseif ($convertToString) {
                foreach ($rank as &$subRank) {
                    $subRank = (string) $subRank;
                }
            }
        }

        return $r;
    }
/**
 * Get result as string
 * @api 
 * @return mixed Result ranking as string.
 * @see Election::getResult, Result::getResultAsArray
 */
    public function getResultAsString(): string
    {
        return VoteUtil::getRankingAsString($this->getResultAsArray(true));
    }
/**
 * Get immutable result as an array
 * @api 
 * @return mixed Unlike other methods to recover the result. This is frozen as soon as the original creation of the Result object is created.
 *               Candidate objects are therefore protected from any change of candidateName, since the candidate objects are converted into a string when the results are promulgated.
 *               
 *               This control method can therefore be useful if you undertake suspicious operations on candidate objects after the results have been promulgated.
 * @see Result::getResultAsArray, Result::getResultAsString, Result::getOriginalResultAsString
 */
    public function getOriginalResultArrayWithString(): array
    {
        return $this->rankingAsString;
    }
/**
 * Get immutable result as a string
 * @api 
 * @return mixed Unlike other methods to recover the result. This is frozen as soon as the original creation of the Result object is created.
 *               Candidate objects are therefore protected from any change of candidateName, since the candidate objects are converted into a string when the results are promulgated.
 *               
 *               This control method can therefore be useful if you undertake suspicious operations on candidate objects after the results have been promulgated.
 * @see Result::getResultAsArray, Result::getResultAsString, Result::getOriginalResultArrayWithString
 */
    public function getOriginalResultAsString(): string
    {
        return VoteUtil::getRankingAsString($this->getOriginalResultArrayWithString());
    }
/**
 * @internal 
 */
    public function getResultAsInternalKey(): array
    {
        return $this->Result;
    }
/**
 * Get advanced computing data from used algorithm. Like Strongest paths for Schulze method.
 * @api 
 * @return mixed Varying according to the algorithm used.
 * @book \CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\BookLibrary::Results
 * @see Election::getResult
 */
    public function getStats(): mixed
    {
        return $this->Stats;
    }
/**
 * ('Get the election winner if any')
 * @api 
 * @return mixed Candidate object given. Null if there are no available winner.
 *               You can get an array with multiples winners.
 * @see Result::getLoser, Election::getWinner
 */
    public function getWinner(): array|Candidate|null
    {
        return CondorcetUtil::format($this[1], false);
    }
/**
 * ('Get the election loser if any')
 * @api 
 * @return mixed Candidate object given. Null if there are no available loser.
 *               You can get an array with multiples losers.
 * @see Result::getWinner, Election::getLoser
 */
    public function getLoser(): array|Candidate|null
    {
        return CondorcetUtil::format($this[\count($this)], false);
    }
/**
 * Get the Condorcet winner, if exist, at the result time.
 * @api 
 * @return mixed CondorcetPHP\Condorcet\Candidate object if there is a Condorcet winner or NULL instead.
 * @see Result::getCondorcetLoser, Election::getWinner
 */
    public function getCondorcetWinner(): ?Candidate
    {
        return $this->CondorcetWinner;
    }
/**
 * Get the Condorcet loser, if exist, at the result time.
 * @api 
 * @return mixed Condorcet/Candidate object if there is a Condorcet loser or NULL instead.
 * @see Result::getCondorcetWinner, Election::getLoser
 */
    public function getCondorcetLoser(): ?Candidate
    {
        return $this->CondorcetLoser;
    }

    protected function makeUserResult(Election $election): array
    {
        $userResult = [];

        foreach ($this->Result as $key => $value) {
            if (\is_array($value)) {
                foreach ($value as $candidate_key) {
                    $userResult[$key][] = $election->getCandidateObjectFromKey($candidate_key);
                }

                sort($userResult[$key], \SORT_STRING);
            } elseif ($value === null) {
                $userResult[$key] = null;
            } else {
                $userResult[$key][] = $election->getCandidateObjectFromKey($value);
            }
        }

        foreach ($userResult as $key => $value) {
            if ($value === null) {
                $userResult[$key] = null;
            }
        }

        return $userResult;
    }


    /////////// Get & Set MetaData ///////////
/**
 * @internal 
 */
    public function addWarning(int $type, ?string $msg = null): true
    {
        $this->warning[] = ['type' => $type, 'msg' => $msg];

        return true;
    }
/**
 * From native methods: only Kemeny-Young use it to inform about a conflict during the computation process.
 * @api 
 * @return mixed Warnings provided by the by the method that generated the warning. Empty array if there is not.
 * @param $type Filter on a specific warning type code.
 */
    public function getWarning(
        #[FunctionParameter('Filter on a specific warning type code')]
        ?int $type = null
    ): array {
        if ($type === null) {
            return $this->warning;
        } else {
            $r = [];

            foreach ($this->warning as $oneWarning) {
                if ($oneWarning['type'] === $type) {
                    $r[] = $oneWarning;
                }
            }

            return $r;
        }
    }
/**
 * Get the The algorithmic method used for this result.
 * @api 
 * @return mixed Method class path like CondorcetPHP\Condorcet\Algo\Methods\Copeland
 * @see Result::getMethod
 */
    public function getClassGenerator(): string
    {
        return $this->byClass;
    }
/**
 * Get the The algorithmic method used for this result.
 * @api 
 * @return mixed Method name.
 * @see Result::getClassGenerator
 */
    public function getMethod(): string
    {
        return $this->fromMethod;
    }
/**
 * Return the method options.
 * @api 
 * @return mixed Array of options. Can be empty for most of the methods.
 * @see Result::getClassGenerator
 */
    public function getMethodOptions(): array
    {
        $r = $this->methodOptions;

        if ($this->isProportional()) {
            $r['Seats'] = $this->getNumberOfSeats();
        }

        return $r;
    }
/**
 * Get the timestamp of this result.
 * @api 
 * @return mixed Microsecond timestamp.
 */
    public function getBuildTimeStamp(): float
    {
        return (float) $this->buildTimestamp;
    }
/**
 * Get the Condorcet PHP version that build this Result.
 * @api 
 * @return mixed Condorcet PHP version string format.
 */
    public function getCondorcetElectionGeneratorVersion(): string
    {
        return $this->electionCondorcetVersion;
    }
/**
 * Get number of Seats for STV methods result.
 * @api 
 * @return mixed Number of seats if this result is a STV method. Else NULL.
 * @see Election::setNumberOfSeats, Election::getNumberOfSeats
 */
    public function getNumberOfSeats(): ?int
    {
        return $this->seats;
    }
/**
 * Does the result come from a proportional method
 * @api 
 * @see Result::getNumberOfSeats
 */
    public function isProportional(): bool
    {
        return $this->seats !== null;
    }
}
