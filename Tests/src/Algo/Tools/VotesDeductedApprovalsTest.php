<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\Algo\Tools;

use CondorcetPHP\Condorcet\Algo\Tools\Combinations;
use CondorcetPHP\Condorcet\Algo\Tools\VotesDeductedApprovals;
use CondorcetPHP\Condorcet\Election;
use PHPUnit\Framework\TestCase;

class VotesDeductedApprovalsTest extends TestCase
{
    public function testGetVotesCountWithCandidatesAllCombination(): void
    {
        $election = new Election;

        $election->addCandidate('A'); // 0
        $election->addCandidate('B'); // 1
        $election->addCandidate('C'); // 2
        $election->addCandidate('D'); // 3
        $election->addCandidate('E'); // 4
        $election->addCandidate('F'); // 5
        $election->addCandidate('G'); // 6
        $election->addCandidate('H'); // 7
        $election->addCandidate('I'); // 8
        $election->addCandidate('J'); // 9

        $election->parseVotes('A>B>C=D>E ^42');

        // Test with 2 and implicit
        $votesStats = new VotesDeductedApprovals(2, $election);
        expect($votesStats)->toHaveCount(Combinations::getPossibleCountOfCombinations($election->countCandidates(), 2));

        expect($votesStats->sumWeightIfVotesIncludeCandidates([0, 1]))->toBe(1);
        expect($votesStats->sumWeightIfVotesIncludeCandidates([1, 0]))->toBe(1);

        expect($votesStats->sumWeightIfVotesIncludeCandidates([2, 3]))->toBe(1);
        expect($votesStats->sumWeightIfVotesIncludeCandidates([4, 0]))->toBe(1);
        expect($votesStats->sumWeightIfVotesIncludeCandidates([1, 8]))->toBe(1);
        expect($votesStats->sumWeightIfVotesIncludeCandidates([8, 9]))->toBe(1);

        // Test with 2 and explicit
        $election->setImplicitRanking(false);

        $votesStats = $votesStats = new VotesDeductedApprovals(2, $election);
        expect($votesStats)->toHaveCount(Combinations::getPossibleCountOfCombinations($election->countCandidates(), 2));

        expect($votesStats->sumWeightIfVotesIncludeCandidates([0, 1]))->toBe(1);
        expect($votesStats->sumWeightIfVotesIncludeCandidates([3, 2]))->toBe(1);
        expect($votesStats->sumWeightIfVotesIncludeCandidates([0, 4]))->toBe(1);
        expect($votesStats->sumWeightIfVotesIncludeCandidates([1, 8]))->toBe(0);
        expect($votesStats->sumWeightIfVotesIncludeCandidates([8, 9]))->toBe(0);

        // Test with 5 and explicit
        $votesStats = $votesStats = new VotesDeductedApprovals(5, $election);
        expect($votesStats)->toHaveCount(Combinations::getPossibleCountOfCombinations($election->countCandidates(), 5));

        expect($votesStats->sumWeightIfVotesIncludeCandidates([0, 1, 2, 3, 4]))->toBe(1);
        expect($votesStats->sumWeightIfVotesIncludeCandidates([0, 1, 2, 3, 5]))->toBe(0);
        expect($votesStats->sumWeightIfVotesIncludeCandidates([3, 8, 6, 9, 2]))->toBe(0);
        expect($votesStats->sumWeightIfVotesIncludeCandidates([5, 6, 8, 7, 9]))->toBe(0);

        // With 2 and weight activated
        $election->allowsVoteWeight(true);

        $votesStats = $votesStats = new VotesDeductedApprovals(2, $election);
        expect($votesStats)->toHaveCount(Combinations::getPossibleCountOfCombinations($election->countCandidates(), 2));

        expect($votesStats->sumWeightIfVotesIncludeCandidates([0, 1]))->toBe(42);
        expect($votesStats->sumWeightIfVotesIncludeCandidates([3, 2]))->toBe(42);
        expect($votesStats->sumWeightIfVotesIncludeCandidates([0, 4]))->toBe(42);
        expect($votesStats->sumWeightIfVotesIncludeCandidates([1, 8]))->toBe(0);
        expect($votesStats->sumWeightIfVotesIncludeCandidates([8, 9]))->toBe(0);

        // Add a vote
        $election->parseVotes('A>B');
        $votesStats = $votesStats = new VotesDeductedApprovals(2, $election);

        expect($votesStats->sumWeightIfVotesIncludeCandidates([0, 1]))->toBe(43);
        expect($votesStats->sumWeightIfVotesIncludeCandidates([0, 2]))->toBe(42);
        expect($votesStats->sumWeightIfVotesIncludeCandidates([1, 8]))->toBe(0);
    }
}
