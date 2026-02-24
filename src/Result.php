<?php declare(strict_types=1);
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

namespace CondorcetPHP\Condorcet;

use CondorcetPHP\Condorcet\Algo\StatsVerbosity;
use CondorcetPHP\Condorcet\Algo\Stats\StatsInterface;
use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{Book, Throws};
use CondorcetPHP\Condorcet\Utils\{CondorcetUtil, VoteUtil};
use CondorcetPHP\Condorcet\Throwable\ResultException;

/**
 * Contains election results for a specific voting method.
 *
 * @implements \ArrayAccess<int,null|Candidate|array<int,Candidate>>
 * @implements \Iterator<int,null|Candidate|array<int,Candidate>>
 */
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
        return key($this->ResultIterator); // @phpstan-ignore return.type
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

    /**
     * @var array<int,array<int,int>>
     *
     * @internal
     */
    public private(set) readonly array $rawRanking;

    protected array $ResultIterator;

    protected array $warning = [];

    /**
     * @api
     */
    public private(set) readonly StatsVerbosity $statsVerbosity;

    /**
     * @internal
     */
    public function __construct(
        /**
         * Get the The algorithmic method used for this result.
         *
         * @api
         * */
        public private(set) readonly string $fromMethod,
        /**
         * Get the The algorithmic method used for this result.
         *
         * @api
         */
        public private(set) readonly string $byClass,
        Election $election,
        array $rawRanking,
        /**
         * Get advanced computing data from used algorithm. Like Strongest paths for Schulze method.
         *
         * @api
         *
         * @book \CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\BookLibrary::Results
         */
        public readonly StatsInterface $stats,
        /**
         * Get count of seats to elects for STV methods result.
         *
         * @api
         * */
        public private(set) readonly ?int $seats = null,
        /**
         * Return the method options. Can be empty for most of the methods.
         *
         * @api
         */
        public private(set) array $methodOptions = [] {
            get {
                $r = $this->methodOptions;

                if ($this->isProportional) {
                    $r['Seats'] = $this->seats;
                }

                return $r;
            }
        },
    ) {
        ksort($rawRanking, \SORT_NUMERIC);
        $this->rawRanking = $rawRanking;

        $this->ResultIterator = $this->ranking = $this->makeUserRanking($election);
        $this->originalRankingAsArrayString = $this->rankingAsArrayString;
        $this->statsVerbosity = $election->statsVerbosity;
        $this->electionCondorcetVersion = $election->getCondorcetBuilderVersion();
        $this->CondorcetWinner = $election->getCondorcetWinner();
        $this->CondorcetLoser = $election->getCondorcetLoser();
        $this->pairwise = $election->getExplicitPairwise();
        $this->buildTimestamp = microtime(true);
    }

    protected function makeUserRanking(Election $election): array
    {
        $userResult = [];

        foreach ($this->rawRanking as $key => $value) {
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


    /////////// Get Ranking ///////////

    /**
     * @var array<int,array<int,Candidate>>
     *
     * @api
     */
    public private(set) readonly array $ranking;

    /**
     * Get result as an array populated by Candidate objects.
     *
     * @api
     *
     * @var array<int,Candidate|array<Candidate>>
     */
    public array $rankingAsArray {
        get {
            return array_map(function ($rank): array|Candidate {
                if (\count($rank) === 1) {
                    return $rank[0];
                } else {
                    return $rank;
                }
            }, $this->ranking);
        }
    }

    /**
     * Get result as an array populated by string.
     *
     * @api
     *
     * @var array<int,string|array<string>>
     */
    public array $rankingAsArrayString {
        get {
            return array_map(function ($rank): array|string {
                if ($rank instanceof Candidate) {
                    return $rank->name;
                } else {
                    $newRankCandidates = [];

                    foreach ($rank as $rankCandidates) {
                        $newRankCandidates[] = $rankCandidates->name;
                    }

                    return $newRankCandidates;
                }
            }, $this->rankingAsArray);
        }
    }

    /**
     * Result ranking as string.
     *
     *  @api
     */
    public string $rankingAsString {
        get => VoteUtil::getRankingAsString($this->rankingAsArrayString);
    }

    /**
     * Get the Condorcet winner, if exist, at the result time.
     *
     * @api
     */
    public private(set) readonly ?Candidate $CondorcetWinner;

    /**
     * Get the Condorcet loser, if exist, at the result time.
     *
     * @api
     */
    public private(set) readonly ?Candidate $CondorcetLoser;

    /**
     * Get immutable result as an array
     * Unlike other methods to recover the result. This is frozen as soon as the original creation of the Result object is created.
     * Candidate objects are therefore protected from any change of candidateName, since the candidate objects are converted into a string when the results are promulgated.
     * This control method can therefore be useful if you undertake suspicious operations on candidate objects after the results have been promulgated.
     *
     * @api
     *
     * @var array<int,string|array<string>>
     */
    public readonly array $originalRankingAsArrayString;

    /**
     * Get immutable result as a string
     * Unlike other methods to recover the result. This is frozen as soon as the original creation of the Result object is created.
     * Candidate objects are therefore protected from any change of candidateName, since the candidate objects are converted into a string when the results are promulgated.
     * This control method can therefore be useful if you undertake suspicious operations on candidate objects after the results have been promulgated.
     *
     * @api
     */
    public string $originalRankingAsString {
        get => VoteUtil::getRankingAsString($this->originalRankingAsArrayString);
    }

    /**
     * @api
     */
    public private(set) readonly array $pairwise;

    /**
     * ('Get the election winner if any')
     * Contain Candidate object. Null if there are no available winner. Or an array with multiples winners.
     *
     * @api
     *
     * @see Result::Loser
     * @see Election::getWinner()
     */
    public array|Candidate|null $Winner {
        get => CondorcetUtil::format($this[1], false);
    }

    /**
     * ('Get the election loser if any')
     * Contain Candidate object. Null if there are no available loser. Or an array with multiples losers.
     *
     * @api
     *
     * @see Result::Winner
     * @see Election::getWinner()
     */
    public array|Candidate|null $Loser {
        get => CondorcetUtil::format($this[\count($this)], false);
    }


    /////////// Get & Set MetaData ///////////

    /**
     * The timestamp of this result at build time.
     *
     * @api
     */
    public private(set) readonly float $buildTimestamp;

    /**
     * Get the Condorcet PHP version that build this Result.
     *
     * @api
     */
    public private(set) readonly string $electionCondorcetVersion;


    /////////// Warning ///////////

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
     *
     * @api
     *
     * @param $type Filter on a specific warning type code.
     *
     * @return array<int, string> Warnings provided by the by the method that generated the warning. Empty array if there is not.
     */
    public function getWarning(
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
     * Does the result come from a proportional method.
     *
     * @api
     *
     * @see Result::seats
     */
    public bool $isProportional {
        get => $this->seats !== null;
    }
}
