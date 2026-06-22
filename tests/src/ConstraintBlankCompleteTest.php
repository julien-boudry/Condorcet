<?php declare(strict_types=1);

use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Constraints\{CompleteRanking, NoBlankVote};

beforeEach(function (): void {
    $this->election = new Election;
    $this->election->parseCandidates('A;B;C');
});

test('NoBlankVote turns blank ballots into invalid votes', function (): void {
    $this->election->parseVotes('
        A > B > C
        A > B > C
        /EMPTY_RANKING/
    ');

    expect($this->election->countVotes())->toBe(3);
    expect($this->election->countValidVoteWithConstraints())->toBe(3);
    expect($this->election->countInvalidVoteWithConstraints())->toBe(0);

    $this->election->addConstraint(NoBlankVote::class);

    expect($this->election->countVotes())->toBe(3);
    expect($this->election->countValidVoteWithConstraints())->toBe(2);
    expect($this->election->countInvalidVoteWithConstraints())->toBe(1);
});

test('NoBlankVote is insensitive to implicit ranking', function (): void {
    $this->election->parseVotes('/EMPTY_RANKING/');
    $this->election->addConstraint(NoBlankVote::class);

    $this->election->implicitRankingRule(true);
    expect($this->election->countInvalidVoteWithConstraints())->toBe(1);

    $this->election->implicitRankingRule(false);
    expect($this->election->countInvalidVoteWithConstraints())->toBe(1);
});

test('CompleteRanking rejects partial and blank ballots, allows ties', function (): void {
    $this->election->parseVotes('
        A > B > C
        A = B = C
        A > B
        /EMPTY_RANKING/
    ');

    $this->election->addConstraint(CompleteRanking::class);

    // Complete: "A > B > C" and "A = B = C" (ties allowed, only completeness matters)
    // Incomplete: "A > B" (partial) and "/EMPTY_RANKING/" (blank)
    expect($this->election->countVotes())->toBe(4);
    expect($this->election->countValidVoteWithConstraints())->toBe(2);
    expect($this->election->countInvalidVoteWithConstraints())->toBe(2);
});

test('CompleteRanking ignores implicit ranking completion', function (): void {
    $this->election->parseVotes('A > B'); // partial: C omitted
    $this->election->addConstraint(CompleteRanking::class);

    // Even with implicit ranking ON (which would contextually complete the ballot),
    // the raw ranking is partial, so the vote is invalid.
    $this->election->implicitRankingRule(true);
    expect($this->election->countValidVoteWithConstraints())->toBe(0);
    expect($this->election->countInvalidVoteWithConstraints())->toBe(1);

    $this->election->implicitRankingRule(false);
    expect($this->election->countValidVoteWithConstraints())->toBe(0);
});
