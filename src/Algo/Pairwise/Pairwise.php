<?php declare(strict_types=1);
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

namespace CondorcetPHP\Condorcet\Algo\Pairwise;

use CondorcetPHP\Condorcet\{Candidate, Condorcet, CondorcetVersion, Election, Vote};
use CondorcetPHP\Condorcet\Timer\Chrono as Timer_Chrono;
use CondorcetPHP\Condorcet\Relations\HasElection;
use CondorcetPHP\Condorcet\Throwable\{CandidateExistsException};

/**
 * @implements \ArrayAccess<int,array<string,array<int,int>>>
 * @implements \Iterator<int,array<string,array<int,int>>>
 */
class Pairwise implements \ArrayAccess, \Iterator
{
    use HasElection;
    use CondorcetVersion;

    // Implement ArrayAccess
    public function offsetSet(mixed $offset, mixed $value): void {}

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->Pairwise[$offset]);
    }

    public function offsetUnset(mixed $offset): void {}

    public function offsetGet(mixed $offset): ?array
    {
        return $this->Pairwise[$offset] ?? null;
    }


    // Implement Iterator
    private bool $valid = true;

    public function rewind(): void
    {
        reset($this->Pairwise);
        $this->valid = true;
    }

    public function current(): array
    {
        return $this->Pairwise[$this->key()];
    }

    public function key(): ?int
    {
        return key($this->Pairwise);
    }

    public function next(): void
    {
        if (next($this->Pairwise) === false) {
            $this->valid = false;
        }
    }

    public function valid(): bool
    {
        return $this->valid;
    }


    // Pairwise

    /** @var array<int,array<string,array<int,int>>> */
    protected readonly array $Pairwise_Model;
    /** @var array<int,array<string,array<int,int>>> */
    protected array $Pairwise;

    protected ?array $explicitPairwise = null;

    /**
     * @internal
     */
    public function __construct(
        Election $link
    ) {
        $this->setElection($link);

        $this->formatNewpairwise();
        $this->doPairwise();
    }

    protected function getVotesManagerGenerator(): \Generator
    {
        return $this->getElection()->getVotesManager()->getVotesValidUnderConstraintGenerator();
    }

    public function __serialize(): array
    {
        $s = get_object_vars($this);
        unset($s['valid'], $s['selfElection']);

        return $s;
    }

    /**
     * @internal
     */
    public function addNewVote(int $key): void
    {
        if (Condorcet::$UseTimer) {
            new Timer_Chrono($this->getElection()->getTimerManager(), 'Add Vote To Pairwise');
        }

        $this->clearExplicitPairwiseCache();

        $this->computeOneVote($this->Pairwise, $this->getElection()->getVotesManager()[$key]);
    }

    /**
     * @internal
     */
    public function removeVote(int $key): void
    {
        if (Condorcet::$UseTimer) {
            new Timer_Chrono($this->getElection()->getTimerManager(), 'Remove Vote To Pairwise');
        }

        $this->clearExplicitPairwiseCache();

        $diff = $this->Pairwise_Model;

        $this->computeOneVote($diff, $this->getElection()->getVotesManager()[$key]);

        foreach ($diff as $candidate_key => $candidate_details) {
            foreach ($candidate_details as $type => $opponent) {
                foreach ($opponent as $opponent_key => $score) {
                    $this->Pairwise[$candidate_key][$type][$opponent_key] -= $score;
                }
            }
        }
    }
    /**
     * Return the Pairwise.
     * @api
     * @return mixed Pairwise as an explicit array .
     * @see Election::getPairwise, Election::getResult
     */
    public function getExplicitPairwise(): array
    {
        if ($this->explicitPairwise === null) {
            $this->explicitPairwise = [];

            foreach ($this->Pairwise as $candidate_key => $candidate_value) {
                $candidate_name = $this->getCandidateNameFromKey($candidate_key);

                foreach ($candidate_value as $mode => $mode_value) {
                    foreach ($mode_value as $candidate_list_key => $candidate_list_value) {
                        $this->explicitPairwise[$candidate_name][$mode][$this->getCandidateNameFromKey($candidate_list_key)] = $candidate_list_value;
                    }
                }
            }
        }

        return $this->explicitPairwise;
    }

    protected function prepareComparaison(
        Candidate|string $a,
        Candidate|string $b
    ): array {
        $election = $this->getElection();

        if (\is_string($a)) {
            $a = $election->getCandidateObjectFromName($a);
        }

        if (\is_string($b)) {
            $b = $election->getCandidateObjectFromName($b);
        }

        if ($a === null || $b === null) {
            throw new CandidateExistsException('Candidate not linked to this election');
        }

        return [$election->getCandidateKey($a), $election->getCandidateKey($b)];
    }

    /**
     * Compare Candidate pairwise to another Candidate.
     * @param $a first candidate
     * @param $b candidate to be compared with $a
     * @throws CandidateExistsException
     * @return int $a wins - $b wins. Negative if a lose, positive if he win or 0 in case of a tie.
     * @api
     */
    public function compareCandidates(Candidate|string $a, Candidate|string $b): int
    {
        return $this->compareCandidatesKeys(...$this->prepareComparaison($a, $b));
    }

    /**
     * Compare Candidate pairwise to another Candidate.
     * @param $candidate the candidate to be compared
     * @param $opponent the candidate to be compared with $candidate
     * @throws CandidateExistsException
     * @return bool true if $a win, false if it lose or tie
     * @api
     */
    public function candidateWinVersus(Candidate|string $candidate, Candidate|string $opponent): bool
    {
        return $this->candidateKeyWinVersus(...$this->prepareComparaison($candidate, $opponent));
    }

    /** @internal */
    public function compareCandidatesKeys(int $aKey, int $bKey): int
    {
        return $this->Pairwise[$aKey]['win'][$bKey] - $this->Pairwise[$bKey]['win'][$aKey];
    }

    /** @internal */
    public function candidateKeyWinVersus(int $candidateKey, int $opponentKey): bool
    {
        return $this->compareCandidatesKeys($candidateKey, $opponentKey) > 0;
    }

    protected function getCandidateNameFromKey(int $candidateKey): string
    {
        return $this->getElection()->getCandidateObjectFromKey($candidateKey)->name;
    }

    protected function clearExplicitPairwiseCache(): void
    {
        $this->explicitPairwise = null;
    }

    protected function formatNewpairwise(): void
    {
        $election = $this->getElection();
        $pairwiseModel = [];

        foreach ($election->getCandidatesList() as $candidate_key => $candidate_id) {
            $pairwiseModel[$candidate_key] = ['win' => [], 'null' => [], 'lose' => []];

            foreach ($election->getCandidatesList() as $candidate_key_r => $candidate_id_r) {
                if ($candidate_key_r !== $candidate_key) {
                    $pairwiseModel[$candidate_key]['win'][$candidate_key_r]   = 0;
                    $pairwiseModel[$candidate_key]['null'][$candidate_key_r]  = 0;
                    $pairwiseModel[$candidate_key]['lose'][$candidate_key_r]  = 0;
                }
            }
        }

        $this->Pairwise_Model = $pairwiseModel; // @phpstan-ignore assign.readOnlyProperty
    }

    protected function doPairwise(): void
    {
        // Chrono
        if (Condorcet::$UseTimer) {
            new Timer_Chrono($this->getElection()->getTimerManager(), 'Do Pairwise');
        }

        $this->clearExplicitPairwiseCache();
        $this->Pairwise = $this->Pairwise_Model;

        foreach ($this->getVotesManagerGenerator() as $oneVote) {
            $this->computeOneVote($this->Pairwise, $oneVote);
        }
    }

    protected function computeOneVote(array &$pairwise, Vote $oneVote): void
    {
        $election = $this->getElection();

        $vote_ranking = $oneVote->getContextualRankingWithoutSort($election);
        $voteWeight = $oneVote->getWeight($election);

        $vote_candidate_list = [];

        foreach ($vote_ranking as $rank) {
            foreach ($rank as $oneCandidate) {
                $vote_candidate_list[] = $election->getCandidateKey($oneCandidate);
            }
        }

        $done_Candidates = [];

        foreach ($vote_ranking as $candidates_in_rank) {
            $candidates_in_rank_keys = [];

            foreach ($candidates_in_rank as $candidate) {
                $candidates_in_rank_keys[] = $election->getCandidateKey($candidate);
            }

            foreach ($candidates_in_rank as $candidate) {
                $candidate_key = $election->getCandidateKey($candidate);

                // Process
                foreach ($vote_candidate_list as $opponent_candidate_key) {
                    if ($candidate_key !== $opponent_candidate_key) {
                        $opponent_in_rank = null;

                        // Win & Lose
                        if (!\in_array(needle: $opponent_candidate_key, haystack: $done_Candidates, strict: true) &&
                                !($opponent_in_rank = \in_array(needle: $opponent_candidate_key, haystack: $candidates_in_rank_keys, strict: true))) {
                            $pairwise[$candidate_key]['win'][$opponent_candidate_key] += $voteWeight;
                            $pairwise[$opponent_candidate_key]['lose'][$candidate_key] += $voteWeight;

                            // Null
                        } elseif ($opponent_in_rank ?? \in_array(needle: $opponent_candidate_key, haystack: $candidates_in_rank_keys, strict: true)) {
                            $pairwise[$candidate_key]['null'][$opponent_candidate_key] += $voteWeight;
                        }
                    }
                }

                $done_Candidates[] = $candidate_key;
            }
        }
    }
}
