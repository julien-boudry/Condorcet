<?php declare(strict_types=1);

use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Algo\Methods\KemenyYoung\KemenyYoung;
use CondorcetPHP\Condorcet\Algo\StatsVerbosity;
use CondorcetPHP\Condorcet\Throwable\CandidatesMaxNumberReachedException;

beforeEach(function (): void {
    $this->election = new Election;
});

test('result 1', function (): void {
    $this->election->addCandidate('Memphis');
    $this->election->addCandidate('Nashville');
    $this->election->addCandidate('Knoxville');
    $this->election->addCandidate('Chattanooga');

    $this->election->parseVotes('
            Memphis > Nashville > Chattanooga * 42
            Nashville > Chattanooga > Knoxville * 26
            Chattanooga > Knoxville > Nashville * 15
            Knoxville > Chattanooga > Nashville * 17
        ');

    expect($this->election->getResult('KemenyYoung')->rankingAsArrayString)->toBe([
        1 => 'Nashville',
        2 => 'Chattanooga',
        3 => 'Knoxville',
        4 => 'Memphis',
    ]);

    expect($this->election->getResult('KemenyYoung')->stats['Best Score'])->toBe(393);

    expect($this->election->getWinner('KemenyYoung'))->toBe($this->election->getWinner());
});

test('result2', function (): void {
    $this->election->parseCandidates('Elliot;Roland;Meredith;Selden');

    $this->election->parseVotes('
            Elliot > Roland ^30
            Elliot > Meredith ^60
            Elliot > Selden ^60
            Roland > Meredith ^70
            Roland > Selden ^60
            Meredith > Selden ^40
        ');

    $this->election->setImplicitRanking(false);

    expect($this->election->getResult('KemenyYoung')->rankingAsArrayString)->toBe([
        1 => 'Elliot',
        2 => 'Roland',
        3 => 'Meredith',
        4 => 'Selden',
    ]);
});

test('stats 1', function (): void {
    $this->election->setStatsVerbosity(StatsVerbosity::FULL);

    $this->election->addCandidate('A');
    $this->election->addCandidate('B');

    $this->election->parseVotes($r = 'A > B');

    expect($this->election->getResult('KemenyYoung')->rankingAsString)->toBe($r);

    expect($this->election->getResult('KemenyYoung')->stats->asArray)->toBe([
        'Best Score' => 1,
        'Ranking In Conflicts' => 0,
        'Ranking Scores' => [
            [1 => 'A', 2 => 'B', 'score' => 1],
            [1 => 'B', 2 => 'A', 'score' => 0],
        ],
    ]);

    $this->election->setStatsVerbosity(StatsVerbosity::STD);

    expect($this->election->getResult('KemenyYoung')->stats)->not()->toHaveKey('rankingScores');
});

test('max candidates', function (): void {
    for ($i = 0; $i < (KemenyYoung::$MaxCandidates + 1); $i++) {
        $this->election->addCandidate();
    }

    $this->election->parseVotes('A');

    $this->expectException(CandidatesMaxNumberReachedException::class);
    $this->expectExceptionMessage("Maximum number of candidates reached: The method 'Kemeny–Young' is configured to accept only " . KemenyYoung::$MaxCandidates . ' candidates');

    $this->election->getWinner('KemenyYoung');
});

test('conflicts', function (): void {
    $this->election->parseCandidates('A;B;C');

    $this->election->parseVotes('
            A>B>C;
            B>C>A;
            C>A>B');

    $result = $this->election->getResult('KemenyYoung');

    expect($result->getWarning(KemenyYoung::CONFLICT_WARNING_CODE))->toEqual([0 => [
        'type' => 42,
        'msg' => '3;5',
    ],
    ]);

    expect($result->getWarning())->toEqual([0 => [
        'type' => 42,
        'msg' => '3;5',
    ],
    ]);

    expect($result->stats->getEntry('Ranking In Conflicts'))->toBe(3);

    $this->election->addVote('A>B>C');

    $result = $this->election->getResult('KemenyYoung');

    expect($result->getWarning(KemenyYoung::CONFLICT_WARNING_CODE))->toBe([]);

    expect($this->election->getWinner('KemenyYoung'))->toEqual('A');
});

test('kemeny with only1 candidate', function (): void {
    $candidate[] = $this->election->addCandidate();

    $this->election->addVote($candidate);

    expect($this->election->getWinner('KemenyYoung'))->toBe($candidate[0]);
});
dataset('ManyCandidatesProvider', fn(): array => [
    9  => [9],
    10  => [10],
]);

test('kemeny with many candidates', function (int $candidatesCount): void {
    $original = KemenyYoung::$MaxCandidates;
    KemenyYoung::$MaxCandidates = null;

    for ($i = 0; $i < $candidatesCount; $i++) {
        $candidates[] = $this->election->addCandidate();
    }

    $this->election->addVote($candidates);

    expect($this->election->getWinner('KemenyYoung'))->toBe($candidates[0]);

    KemenyYoung::$MaxCandidates = $original;
})->with('ManyCandidatesProvider')->group('slow');
