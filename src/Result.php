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

    #[Throws(ResultException::class)]
    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new ResultException;
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->ranking[$offset]);
    }

    #[Throws(ResultException::class)]
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

    #[PublicAPI]
    public private(set) readonly array $ranking;
    #[PublicAPI]
    public array $rankingAsString {
        get => $this->getResultAsArray(true);
    }
    #[PublicAPI]
    public private(set) readonly ?int $seats;
    #[PublicAPI]
    public private(set) readonly array $methodOptions;
    #[PublicAPI]
    public private(set) readonly ?Candidate $CondorcetWinner;
    #[PublicAPI]
    public private(set) readonly ?Candidate $CondorcetLoser;
    #[PublicAPI]
    public private(set) readonly array $pairwise;
    #[PublicAPI]
    public private(set) readonly float $buildTimestamp;
    #[PublicAPI]
    public private(set) readonly string $fromMethod;
    #[PublicAPI]
    public private(set) readonly string $byClass;
    #[PublicAPI]
    public private(set) readonly StatsVerbosity $statsVerbosity;
    #[PublicAPI]
    public private(set) readonly string $electionCondorcetVersion;


    #[InternalModulesAPI]
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

    #[PublicAPI]
    #[Description('Get result as an array')]
    #[FunctionReturn('An ordered multidimensionnal array by rank.')]
    #[Related('Election::getResult', 'Result::getResultAsString')]
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

    #[PublicAPI]
    #[Description('Get result as string')]
    #[FunctionReturn('Result ranking as string.')]
    #[Related('Election::getResult', 'Result::getResultAsArray')]
    public function getResultAsString(): string
    {
        return VoteUtil::getRankingAsString($this->getResultAsArray(true));
    }

    #[PublicAPI]
    #[Description('Get immutable result as an array')]
    #[FunctionReturn("Unlike other methods to recover the result. This is frozen as soon as the original creation of the Result object is created.\nCandidate objects are therefore protected from any change of candidateName, since the candidate objects are converted into a string when the results are promulgated.\n\nThis control method can therefore be useful if you undertake suspicious operations on candidate objects after the results have been promulgated.")]
    #[Related('Result::getResultAsArray', 'Result::getResultAsString', 'Result::getOriginalResultAsString')]
    public function getOriginalResultArrayWithString(): array
    {
        return $this->rankingAsString;
    }

    #[PublicAPI]
    #[Description('Get immutable result as a string')]
    #[FunctionReturn("Unlike other methods to recover the result. This is frozen as soon as the original creation of the Result object is created.\nCandidate objects are therefore protected from any change of candidateName, since the candidate objects are converted into a string when the results are promulgated.\n\nThis control method can therefore be useful if you undertake suspicious operations on candidate objects after the results have been promulgated.")]
    #[Related('Result::getResultAsArray', 'Result::getResultAsString', 'Result::getOriginalResultArrayWithString')]
    public function getOriginalResultAsString(): string
    {
        return VoteUtil::getRankingAsString($this->getOriginalResultArrayWithString());
    }

    #[InternalModulesAPI]
    public function getResultAsInternalKey(): array
    {
        return $this->Result;
    }

    #[PublicAPI]
    #[Description('Get advanced computing data from used algorithm. Like Strongest paths for Schulze method.')]
    #[FunctionReturn('Varying according to the algorithm used.')]
    #[Book(BookLibrary::Results)]
    #[Related('Election::getResult')]
    public function getStats(): mixed
    {
        return $this->Stats;
    }

    #[PublicAPI]
    #[Description('Equivalent to [Condorcet/Election::getWinner($method)](../Election Class/public Election--getWinner.md).')]
    #[FunctionReturn("Candidate object given. Null if there are no available winner.\nYou can get an array with multiples winners.")]
    #[Related('Result::getLoser', 'Election::getWinner')]
    public function getWinner(): array|Candidate|null
    {
        return CondorcetUtil::format($this[1], false);
    }

    #[PublicAPI]
    #[Description('Equivalent to [Condorcet/Election::getWinner($method)](../Election Class/public Election--getWinner.md).')]
    #[FunctionReturn("Candidate object given. Null if there are no available loser.\nYou can get an array with multiples losers.")]
    #[Related('Result::getWinner', 'Election::getLoser')]
    public function getLoser(): array|Candidate|null
    {
        return CondorcetUtil::format($this[\count($this)], false);
    }

    #[PublicAPI]
    #[Description('Get the Condorcet winner, if exist, at the result time.')]
    #[FunctionReturn("CondorcetPHP\Condorcet\Candidate object if there is a Condorcet winner or NULL instead.")]
    #[Related('Result::getCondorcetLoser', 'Election::getWinner')]
    public function getCondorcetWinner(): ?Candidate
    {
        return $this->CondorcetWinner;
    }

    #[PublicAPI]
    #[Description('Get the Condorcet loser, if exist, at the result time.')]
    #[FunctionReturn('Condorcet/Candidate object if there is a Condorcet loser or NULL instead.')]
    #[Related('Result::getCondorcetWinner', 'Election::getLoser')]
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

    #[InternalModulesAPI]
    public function addWarning(int $type, ?string $msg = null): true
    {
        $this->warning[] = ['type' => $type, 'msg' => $msg];

        return true;
    }

    #[PublicAPI]
    #[Description('From native methods: only Kemeny-Young use it to inform about a conflict during the computation process.')]
    #[FunctionReturn('Warnings provided by the by the method that generated the warning. Empty array if there is not.')]
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

    #[PublicAPI]
    #[Description('Get the The algorithmic method used for this result.')]
    #[FunctionReturn("Method class path like CondorcetPHP\Condorcet\Algo\Methods\Copeland")]
    #[Related('Result::getMethod')]
    public function getClassGenerator(): string
    {
        return $this->byClass;
    }

    #[PublicAPI]
    #[Description('Get the The algorithmic method used for this result.')]
    #[FunctionReturn('Method name.')]
    #[Related('Result::getClassGenerator')]
    public function getMethod(): string
    {
        return $this->fromMethod;
    }

    #[PublicAPI]
    #[Description('Return the method options.')]
    #[FunctionReturn('Array of options. Can be empty for most of the methods.')]
    #[Related('Result::getClassGenerator')]
    public function getMethodOptions(): array
    {
        $r = $this->methodOptions;

        if ($this->isProportional()) {
            $r['Seats'] = $this->getNumberOfSeats();
        }

        return $r;
    }

    #[PublicAPI]
    #[Description('Get the timestamp of this result.')]
    #[FunctionReturn('Microsecond timestamp.')]
    public function getBuildTimeStamp(): float
    {
        return (float) $this->buildTimestamp;
    }

    #[PublicAPI]
    #[Description('Get the Condorcet PHP version that build this Result.')]
    #[FunctionReturn('Condorcet PHP version string format.')]
    public function getCondorcetElectionGeneratorVersion(): string
    {
        return $this->electionCondorcetVersion;
    }

    #[PublicAPI]
    #[Description('Get number of Seats for STV methods result.')]
    #[FunctionReturn('Number of seats if this result is a STV method. Else NULL.')]
    #[Related('Election::setNumberOfSeats', 'Election::getNumberOfSeats')]
    public function getNumberOfSeats(): ?int
    {
        return $this->seats;
    }

    #[PublicAPI]
    #[Description('Does the result come from a proportional method')]
    #[Related('Result::getNumberOfSeats')]
    public function isProportional(): bool
    {
        return $this->seats !== null;
    }
}
