<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet;

use CondorcetPHP\Condorcet\Candidate;
use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{Description, Example, FunctionParameter, FunctionReturn, PublicAPI, Related, Throws};
use CondorcetPHP\Condorcet\ElectionProcess\VoteUtil;
use CondorcetPHP\Condorcet\Throwable\ResultException;

class Result implements \ArrayAccess, \Countable, \Iterator
{
    use CondorcetVersion;

    // Implement Iterator

    public function rewind(): void {
        \reset($this->_ResultIterator);
    }

    public function current (): array|Candidate {
        return \current($this->_ResultIterator);
    }

    public function key (): int {
        return \key($this->_ResultIterator);
    }

    public function next (): void {
        \next($this->_ResultIterator);
    }

    public function valid (): bool {
        return \key($this->_ResultIterator) !== null;
    }

    // Implement ArrayAccess

    #[Throws(ResultException::class)]
    public function offsetSet ($offset, $value): void {
        throw new ResultException();
    }

    public function offsetExists ($offset): bool {
        return isset($this->ranking[$offset]);
    }

    #[Throws(ResultException::class)]
    public function offsetUnset ($offset): void {
        throw new ResultException();
    }

    public function offsetGet ($offset): array|Candidate|null {
        return $this->ranking[$offset] ?? null;
    }

    // Implement Countable

    public function count (): int {
        return \count($this->ranking);
    }


/////////// CONSTRUCTOR ///////////

    protected readonly array $_Result;
    protected array $_ResultIterator;
    protected $_Stats;
    protected array $_warning = [];

    #[PublicAPI]
    public readonly array $ranking;
    #[PublicAPI]
    public readonly array $rankingAsString;
    #[PublicAPI]
    public readonly ?int $seats;
    #[PublicAPI]
    public readonly array $methodOptions;
    #[PublicAPI]
    public readonly ?Candidate $CondorcetWinner;
    #[PublicAPI]
    public readonly ?Candidate $CondorcetLoser;
    #[PublicAPI]
    public readonly float $buildTimestamp;
    #[PublicAPI]
    public readonly string $fromMethod;
    #[PublicAPI]
    public readonly string $byClass;
    #[PublicAPI]
    public readonly string $electionCondorcetVersion;


    public function __construct (string $fromMethod, string $byClass, Election $election, array $result, $stats, ?int $seats = null, array $methodOptions = [])
    {
        \ksort($result, \SORT_NUMERIC);

        $this->_Result = $result;
        $this->_ResultIterator = $this->ranking = $this->makeUserResult($election);
        $this->rankingAsString = $this->getResultAsArray(true);
        $this->_Stats = $stats;
        $this->seats = $seats;
        $this->fromMethod = $fromMethod;
        $this->byClass = $byClass;
        $this->electionCondorcetVersion = $election->getObjectVersion();
        $this->CondorcetWinner = $election->getWinner();
        $this->CondorcetLoser = $election->getLoser();
        $this->buildTimestamp = \microtime(true);
        $this->methodOptions = $methodOptions;
    }


/////////// Get Result ///////////

    #[PublicAPI]
    #[Description("Get result as an array")]
    #[FunctionReturn("An ordered multidimensionnal array by rank.")]
    #[Related("Election::getResult", "Result::getResultAsString")]
    public function getResultAsArray (
        #[FunctionParameter('Convert Candidate object to string')]
        bool $convertToString = false
    ): array
    {
        $r = $this->ranking;

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
    #[Description("Get result as string")]
    #[FunctionReturn("Result ranking as string.")]
    #[Related("Election::getResult", "Result::getResultAsArray")]
    public function getResultAsString (): string
    {
        return VoteUtil::getRankingAsString($this->getResultAsArray(true));
    }

    #[PublicAPI]
    #[Description("Get result as an array")]
    #[FunctionReturn("Unlike other methods to recover the result. This is frozen as soon as the original creation of the Result object is created.\nCandidate objects are therefore protected from any change of candidateName, since the candidate objects are converted into a string when the results are promulgated.\n\nThis control method can therefore be useful if you undertake suspicious operations on candidate objects after the results have been promulgated.")]
    #[Related("Result::getResultAsArray", "Result::getResultAsString")]
    public function getOriginalResultArrayWithString (): array
    {
        return $this->rankingAsString;
    }

    public function getResultAsInternalKey (): array
    {
        return $this->_Result;
    }

    #[PublicAPI]
    #[Description("Get advanced computing data from used algorithm. Like Strongest paths for Schulze method.")]
    #[FunctionReturn("Varying according to the algorithm used.")]
    #[Example("Advanced Result Management","https://github.com/julien-boudry/Condorcet/wiki/II-%23-C.-Result-%23-3.-Advanced-Results-Management")]
    #[Related("Election::getResult")]
    public function getStats (): mixed {
        return $this->_Stats;
    }

    #[PublicAPI]
    #[Description("Equivalent to [Condorcet/Election::getWinner(\$method)](../Election Class/public Election--getWinner.md).")]
    #[FunctionReturn("Candidate object given. Null if there are no available winner.\nYou can get an array with multiples winners.")]
    #[Related("Result::getLoser", "Election::getWinner")]
    public function getWinner (): array|Candidate|null {
        return CondorcetUtil::format($this[1],false);
    }

    #[PublicAPI]
    #[Description("Equivalent to [Condorcet/Election::getWinner(\$method)](../Election Class/public Election--getWinner.md).")]
    #[FunctionReturn("Candidate object given. Null if there are no available loser.\nYou can get an array with multiples losers.")]
    #[Related("Result::getWinner", "Election::getLoser")]
    public function getLoser (): array|Candidate|null {
        return CondorcetUtil::format($this[count($this)],false);
    }

    #[PublicAPI]
    #[Description("Get the Condorcet winner, if exist, at the result time.")]
    #[FunctionReturn("CondorcetPHP\Condorcet\Candidate object if there is a Condorcet winner or NULL instead.")]
    #[Related("Result::getCondorcetLoser", "Election::getWinner")]
    public function getCondorcetWinner (): ?Candidate {
        return $this->CondorcetWinner;
    }

    #[PublicAPI]
    #[Description("Get the Condorcet loser, if exist, at the result time.")]
    #[FunctionReturn("Condorcet/Candidate object if there is a Condorcet loser or NULL instead.")]
    #[Related("Result::getCondorcetWinner", "Election::getLoser")]
    public function getCondorcetLoser (): ?Candidate {
        return $this->CondorcetLoser;
    }

    protected function makeUserResult (Election $election): array
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

    public function addWarning (int $type, string $msg = null): bool
    {
        $this->_warning[] = ['type' => $type, 'msg' => $msg];

        return true;
    }

    #[PublicAPI]
    #[Description("From native methods: only Kemeny-Young use it to inform about a conflict during the computation process.")]
    #[FunctionReturn("Warnings provided by the by the method that generated the warning. Empty array if there is not.")]
    public function getWarning (
        #[FunctionParameter('Filter on a specific warning type code')]
        ?int $type = null
    ): array
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
    #[Description("Get the The algorithmic method used for this result.")]
    #[FunctionReturn("Method class path like CondorcetPHP\Condorcet\Algo\Methods\Copeland")]
    #[Related("Result::getMethod")]
    public function getClassGenerator (): string {
        return $this->byClass;
    }

    #[PublicAPI]
    #[Description("Get the The algorithmic method used for this result.")]
    #[FunctionReturn("Method name.")]
    #[Related("Result::getClassGenerator")]
    public function getMethod (): string {
        return $this->fromMethod;
    }

    #[PublicAPI]
    #[Description("Return the method options.")]
    #[FunctionReturn("Array of options. Can be empty for most of the methods.")]
    #[Related("Result::getClassGenerator")]
    public function getMethodOptions (): array {
        $r = $this->methodOptions;

        if($this->isProportional()) :
            $r['Seats'] = $this->getNumberOfSeats();
        endif;

        return $r;
    }

    #[PublicAPI]
    #[Description("Get the timestamp of this result.")]
    #[FunctionReturn("Microsecond timestamp.")]
    public function getBuildTimeStamp (): float {
        return (float) $this->buildTimestamp;
    }

    #[PublicAPI]
    #[Description("Get the Condorcet PHP version that build this Result.")]
    #[FunctionReturn("Condorcet PHP version string format.")]
    public function getCondorcetElectionGeneratorVersion (): string {
        return $this->electionCondorcetVersion;
    }

    #[PublicAPI]
    #[Description("Get number of Seats for STV methods result.")]
    #[FunctionReturn("Number of seats if this result is a STV method. Else NULL.")]
    #[Related("Election::setNumberOfSeats", "Election::getNumberOfSeats")]
    public function getNumberOfSeats (): ?int {
        return $this->seats;
    }

    #[PublicAPI]
    #[Description("Does the result come from a proportional method")]
    #[Related("Result::getNumberOfSeats")]
    public function isProportional (): bool {
        return $this->seats !== null;
    }
}
