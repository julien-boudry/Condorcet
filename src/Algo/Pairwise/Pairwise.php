<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Algo\Pairwise;

use CondorcetPHP\Condorcet\{Condorcet, CondorcetVersion, Election, Vote};
use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{Description, FunctionReturn, PublicAPI, Related};
use CondorcetPHP\Condorcet\Timer\Chrono as Timer_Chrono;
use CondorcetPHP\Condorcet\Relations\HasElection;

class Pairwise implements \ArrayAccess, \Iterator
{
    use HasElection;
    use CondorcetVersion;

    // Implement ArrayAccess
    public function offsetSet(mixed $offset, mixed $value): void
    {
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->Pairwise[$offset]);
    }

    public function offsetUnset(mixed $offset): void
    {
    }

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

    protected readonly array $Pairwise_Model;
    protected array $Pairwise;
    protected ?array $explicitPairwise = null;

    public function __construct(Election $link)
    {
        $this->setElection($link);
        $this->formatNewpairwise();
        $this->doPairwise();
    }

    public function __serialize(): array
    {
        return [
            'Pairwise_Model' => $this->Pairwise_Model,
            'Pairwise' => $this->Pairwise,
        ];
    }

    public function addNewVote(int $key): void
    {
        (Condorcet::$UseTimer === true) && new Timer_Chrono($this->getElection()->getTimerManager(), 'Add Vote To Pairwise');

        $this->clearExplicitPairwiseCache();

        $this->computeOneVote($this->Pairwise, $this->getElection()->getVotesManager()[$key]);
    }

    public function removeVote(int $key): void
    {
        (Condorcet::$UseTimer === true) && new Timer_Chrono($this->getElection()->getTimerManager(), 'Remove Vote To Pairwise');

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

    #[PublicAPI]
    #[Description('Return the Pairwise.')]
    #[FunctionReturn('Pairwise as an explicit array .')]
    #[Related('Election::getPairwise', 'Election::getResult')]
    public function getExplicitPairwise(): array
    {
        if ($this->explicitPairwise === null) {
            $election = $this->getElection();
            $this->explicitPairwise = [];

            foreach ($this->Pairwise as $candidate_key => $candidate_value) {
                $candidate_name = $election->getCandidateObjectFromKey($candidate_key)->getName();

                foreach ($candidate_value as $mode => $mode_value) {
                    foreach ($mode_value as $candidate_list_key => $candidate_list_value) {
                        $this->explicitPairwise[$candidate_name][$mode][$election->getCandidateObjectFromKey($candidate_list_key)->getName()] = $candidate_list_value;
                    }
                }
            }
        }

        return $this->explicitPairwise;
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

        $this->Pairwise_Model ??= $pairwiseModel;
    }

    protected function doPairwise(): void
    {
        $election = $this->getElection();

        // Chrono
        (Condorcet::$UseTimer === true) && new Timer_Chrono($election->getTimerManager(), 'Do Pairwise');

        $this->clearExplicitPairwiseCache();
        $this->Pairwise = $this->Pairwise_Model;

        foreach ($election->getVotesManager()->getVotesValidUnderConstraintGenerator() as $oneVote) {
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
