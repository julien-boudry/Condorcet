<?php declare(strict_types=1);

use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Throwable\CandidateExistsException;

beforeEach(function (): void {
    $this->election1 = new Election;

    $this->election1->addCandidate('A');
    $this->election1->addCandidate('B');
    $this->election1->addCandidate('C');

    $this->election1->addVote('A>B>C');
});

test('pairwise offset get', function (): void {
    $pairwise = $this->election1->getPairwise();

    expect($pairwise[1])->toBeArray();

    expect($pairwise[42])->toBeNull();
});

test('explicit pairwise', function (): void {
    expect($this->election1->getPairwise()->getExplicitPairwise())->toBe([
        'A' => [
            'win' => [
                'B' => 1,
                'C' => 1,
            ],
            'null' => [
                'B' => 0,
                'C' => 0,
            ],
            'lose' => [
                'B' => 0,
                'C' => 0,
            ],
        ],
        'B' => [
            'win' => [
                'A' => 0,
                'C' => 1,
            ],
            'null' => [
                'A' => 0,
                'C' => 0,
            ],
            'lose' => [
                'A' => 1,
                'C' => 0,
            ],
        ],
        'C' => [
            'win' => [
                'A' => 0,
                'B' => 0,
            ],
            'null' => [
                'A' => 0,
                'B' => 0,
            ],
            'lose' => [
                'A' => 1,
                'B' => 1,
            ],
        ],
    ]);
});

test('votes weight', function (): void {
    $electionOff = new Election;

    $electionOff->addCandidate('A');
    $electionOff->addCandidate('B');
    $electionOff->addCandidate('C');
    $electionOff->addCandidate('D');

    $electionOff->addVote('A>B>C=D ^3');
    $electionOff->addVote('A>B>C=D ^4');

    $electionOn = clone $electionOff;
    $electionOn->authorizeVoteWeight = true;

    expect($electionOn->getExplicitPairwise())->not()->toBe($electionOff->getExplicitPairwise());

    expect($electionOn->getPairwise()->getExplicitPairwise())->toBe([
        'A' => [
            'win' => [
                'B' => 7,
                'C' => 7,
                'D' => 7,
            ],
            'null' => [
                'B' => 0,
                'C' => 0,
                'D' => 0,
            ],
            'lose' => [
                'B' => 0,
                'C' => 0,
                'D' => 0,
            ],
        ],
        'B' => [
            'win' => [
                'A' => 0,
                'C' => 7,
                'D' => 7,
            ],
            'null' => [
                'A' => 0,
                'C' => 0,
                'D' => 0,
            ],
            'lose' => [
                'A' => 7,
                'C' => 0,
                'D' => 0,
            ],
        ],
        'C' => [
            'win' => [
                'A' => 0,
                'B' => 0,
                'D' => 0,
            ],
            'null' => [
                'A' => 0,
                'B' => 0,
                'D' => 7,
            ],
            'lose' => [
                'A' => 7,
                'B' => 7,
                'D' => 0,
            ],
        ],
        'D' => [
            'win' => [
                'A' => 0,
                'B' => 0,
                'C' => 0,
            ],
            'null' => [
                'A' => 0,
                'B' => 0,
                'C' => 7,
            ],
            'lose' => [
                'A' => 7,
                'B' => 7,
                'C' => 0,
            ],
        ],
    ]);
});

test('remove vote 1', function (): void {
    $this->election1->authorizeVoteWeight = true;
    $this->election1->removeAllVotes();

    // removeAllVotes process a loop on each vote
    $this->election1->parseVotes('A>B>C ^2');

    expect($this->election1->getExplicitPairwise())->toBe([
        'A' => [
            'win' => [
                'B' => 2,
                'C' => 2,
            ],
            'null' => [
                'B' => 0,
                'C' => 0,
            ],
            'lose' => [
                'B' => 0,
                'C' => 0,
            ],
        ],
        'B' => [
            'win' => [
                'A' => 0,
                'C' => 2,
            ],
            'null' => [
                'A' => 0,
                'C' => 0,
            ],
            'lose' => [
                'A' => 2,
                'C' => 0,
            ],
        ],
        'C' => [
            'win' => [
                'A' => 0,
                'B' => 0,
            ],
            'null' => [
                'A' => 0,
                'B' => 0,
            ],
            'lose' => [
                'A' => 2,
                'B' => 2,
            ],
        ],
    ]);
});

test('remove vote bug with weight', function (): void {
    $this->election1->removeAllVotes();
    // removeAllVotes process a loop on each vote
    $this->election1->authorizeVoteWeight = true;

    // Bug was occured when they were not any votes left, then setting pairwise to null without rebuild a new one.
    $this->election1->parseVotes('A>B>C ^2');

    expect($this->election1->getExplicitPairwise())->toBe([
        'A' => [
            'win' => [
                'B' => 2,
                'C' => 2,
            ],
            'null' => [
                'B' => 0,
                'C' => 0,
            ],
            'lose' => [
                'B' => 0,
                'C' => 0,
            ],
        ],
        'B' => [
            'win' => [
                'A' => 0,
                'C' => 2,
            ],
            'null' => [
                'A' => 0,
                'C' => 0,
            ],
            'lose' => [
                'A' => 2,
                'C' => 0,
            ],
        ],
        'C' => [
            'win' => [
                'A' => 0,
                'B' => 0,
            ],
            'null' => [
                'A' => 0,
                'B' => 0,
            ],
            'lose' => [
                'A' => 2,
                'B' => 2,
            ],
        ],
    ]);
});

test('compare Candidates', function (): void {
    $this->election1->removeAllVotes();
    $this->election1->addVote('A>B>C');
    $this->election1->addVote('A>C>B');

    $pairwise = $this->election1->getPairwise();

    expect($pairwise->compareCandidates('A', 'B'))->toBe(2);
    expect($pairwise->candidateWinVersus('A', 'B'))->toBeTrue();
    expect($pairwise->compareCandidates('A', 'C'))->toBe(2);
    expect($pairwise->candidateWinVersus('A', 'C'))->toBeTrue();
    expect($pairwise->compareCandidates('B', 'C'))->toBe(0);
    expect($pairwise->candidateWinVersus('B', 'C'))->toBeFalse();
    expect($pairwise->compareCandidates('B', 'A'))->toBe(-2);
    expect($pairwise->candidateWinVersus('B', 'A'))->toBeFalse();
    expect($pairwise->compareCandidates('C', 'A'))->toBe(-2);
    expect($pairwise->candidateWinVersus('C', 'A'))->toBeFalse();
    expect($pairwise->compareCandidates('C', 'B'))->toBe(0);
    expect($pairwise->candidateWinVersus('C', 'B'))->toBeFalse();

    $candidateA = $this->election1->getCandidateObjectFromName('A');
    $candidateB = $this->election1->getCandidateObjectFromName('B');
    $candidateC = $this->election1->getCandidateObjectFromName('C');

    expect($pairwise->compareCandidates($candidateA, $candidateB))->toBe(2);
    expect($pairwise->candidateWinVersus($candidateA, $candidateB))->toBeTrue();
    expect($pairwise->compareCandidates($candidateA, 'C'))->toBe(2);
    expect($pairwise->candidateWinVersus($candidateA, 'C'))->toBeTrue();
    expect($pairwise->compareCandidates('B', $candidateC))->toBe(0);
    expect($pairwise->candidateWinVersus('B', $candidateC))->toBeFalse();

    expect(fn(): never => $pairwise->compareCandidates($candidateA, 'D'))->toThrow(CandidateExistsException::class);
});
