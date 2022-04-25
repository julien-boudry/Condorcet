<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Algo;

use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{Description, Example, FunctionReturn, PublicAPI, Related};
use CondorcetPHP\Condorcet\{Condorcet, CondorcetVersion, Election, Vote};
use CondorcetPHP\Condorcet\Timer\Chrono as Timer_Chrono;

class Pairwise implements \ArrayAccess, \Iterator
{
    use CondorcetVersion;

    // Implement ArrayAccess
    public function offsetSet(mixed $offset, mixed $value): void {}

    public function offsetExists(mixed $offset): bool {
        return isset($this->_Pairwise[$offset]);
    }

    public function offsetUnset(mixed $offset): void {}

    public function offsetGet(mixed $offset): ?array {
        return $this->_Pairwise[$offset] ?? null;
    }


    // Implement Iterator
    private bool $valid = true;

    public function rewind(): void {
        \reset($this->_Pairwise);
        $this->valid = true;
    }

    public function current(): array {
        return $this->_Pairwise[$this->key()];
    }

    public function key(): ?int {
        return \key($this->_Pairwise);
    }

    public function next(): void {
        if (\next($this->_Pairwise) === false) :
            $this->valid = false;
        endif;
    }

    public function valid(): bool {
        return $this->valid;
    }


    // Pairwise

    protected ?\WeakReference $_Election;
    protected array $_Pairwise_Model;
    protected array $_Pairwise;

    public function __construct (Election $link)
    {
        $this->setElection($link);
        $this->formatNewpairwise();
        $this->doPairwise();
    }

    public function __destruct ()
    {
        unset($this->_Election);
    }

    public function __clone (): void
    {
        $this->_Election = null;
    }

    public function __serialize (): array
    {
        return [
            '_Election' => null,
            '_Pairwise_Model' => $this->_Pairwise_Model,
            '_Pairwise' => $this->_Pairwise,
        ];
    }

    public function __unserialize (array $data): void
    {
        $this->_Pairwise_Model = $data['_Pairwise_Model'];
        $this->_Pairwise = $data['_Pairwise'];
    }

    public function getElection (): Election
    {
        return $this->_Election->get();
    }

    public function setElection (Election $election): void
    {
        $this->_Election = \WeakReference::create($election);
    }

    public function addNewVote (int $key): void
    {
        (Condorcet::$UseTimer === true) && new Timer_Chrono ( $this->getElection()->getTimerManager(), 'Add Vote To Pairwise' );

        $this->computeOneVote($this->_Pairwise,$this->getElection()->getVotesManager()[$key]);
    }

    public function removeVote (int $key): void
    {
        (Condorcet::$UseTimer === true) && new Timer_Chrono ( $this->getElection()->getTimerManager(), 'Remove Vote To Pairwise' );

        $diff = $this->_Pairwise_Model;

        $this->computeOneVote($diff,$this->getElection()->getVotesManager()[$key]);

        foreach ($diff as $candidate_key => $candidate_details) :
            foreach ($candidate_details as $type => $opponent) :
                foreach ($opponent as $opponent_key => $score) :
                    $this->_Pairwise[$candidate_key][$type][$opponent_key] -= $score;
                endforeach;
            endforeach;
        endforeach;
    }

    #[PublicAPI]
    #[Description("Return the Pairwise.")]
    #[FunctionReturn("Pairwise as an explicit array .")]
    #[Related("Election::getPairwise", "Election::getResult")]
    public function getExplicitPairwise (): array
    {
        $explicit_pairwise = [];

        foreach ($this->_Pairwise as $candidate_key => $candidate_value) :

            $candidate_name = $this->getElection()->getCandidateObjectFromKey($candidate_key)->getName();

            foreach ($candidate_value as $mode => $mode_value) :

                foreach ($mode_value as $candidate_list_key => $candidate_list_value) :
                    $explicit_pairwise[$candidate_name][$mode][$this->getElection()->getCandidateObjectFromKey($candidate_list_key)->getName()] = $candidate_list_value;
                endforeach;

            endforeach;

        endforeach;

        return $explicit_pairwise;
    }

    protected function formatNewpairwise (): void
    {
        $this->_Pairwise_Model = [];

        foreach ( $this->getElection()->getCandidatesList() as $candidate_key => $candidate_id ) :

            $this->_Pairwise_Model[$candidate_key] = [ 'win' => [], 'null' => [], 'lose' => [] ];

            foreach ( $this->getElection()->getCandidatesList() as $candidate_key_r => $candidate_id_r ) :

                if ($candidate_key_r !== $candidate_key) :
                    $this->_Pairwise_Model[$candidate_key]['win'][$candidate_key_r]   = 0;
                    $this->_Pairwise_Model[$candidate_key]['null'][$candidate_key_r]  = 0;
                    $this->_Pairwise_Model[$candidate_key]['lose'][$candidate_key_r]  = 0;
                endif;

            endforeach;

        endforeach;
    }

    protected function doPairwise (): void
    {
        // Chrono
        (Condorcet::$UseTimer === true) && new Timer_Chrono ( $this->getElection()->getTimerManager(), 'Do Pairwise' );

        $this->_Pairwise = $this->_Pairwise_Model;

        foreach ( $this->getElection()->getVotesManager()->getVotesValidUnderConstraintGenerator() as $oneVote ) :
            $this->computeOneVote($this->_Pairwise, $oneVote);
        endforeach;
    }

    protected function computeOneVote (array &$pairwise, Vote $oneVote): void
    {
        $vote_ranking = $oneVote->getContextualRanking($this->getElection());

        $voteWeight = $oneVote->getWeight($this->getElection());

        $vote_candidate_list = [];

        foreach ($vote_ranking as $rank) :
            foreach ($rank as $oneCandidate) :
                $vote_candidate_list[] = $oneCandidate;
            endforeach;
        endforeach;

        $done_Candidates = [];

        foreach ($vote_ranking as $candidates_in_rank) :

            $candidates_in_rank_keys = [];

            foreach ($candidates_in_rank as $candidate) :
                $candidates_in_rank_keys[] = $this->getElection()->getCandidateKey($candidate);
            endforeach;

            foreach ($candidates_in_rank as $candidate) :

                $candidate_key = $this->getElection()->getCandidateKey($candidate);

                // Process
                foreach ( $vote_candidate_list as $g_Candidate ) :

                    $g_candidate_key = $this->getElection()->getCandidateKey($g_Candidate);

                    if ($candidate_key === $g_candidate_key) :
                        continue;
                    endif;

                    // Win & Lose
                    if (    !\in_array(needle: $g_candidate_key, haystack: $done_Candidates, strict: true) &&
                            !\in_array(needle: $g_candidate_key, haystack: $candidates_in_rank_keys, strict: true) ) :

                        $pairwise[$candidate_key]['win'][$g_candidate_key] += $voteWeight;
                        $pairwise[$g_candidate_key]['lose'][$candidate_key] += $voteWeight;

                        $done_Candidates[] = $candidate_key;

                    // Null
                    elseif (\in_array(needle: $g_candidate_key, haystack: $candidates_in_rank_keys, strict: true)) :
                        $pairwise[$candidate_key]['null'][$g_candidate_key] += $voteWeight;
                    endif;

                endforeach;

            endforeach;

        endforeach;
    }

}
