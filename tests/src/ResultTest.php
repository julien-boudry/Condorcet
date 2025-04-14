<?php

declare(strict_types=1);
use CondorcetPHP\Condorcet\Algo\Methods\KemenyYoung\KemenyYoung;
use CondorcetPHP\Condorcet\Algo\StatsVerbosity;
use CondorcetPHP\Condorcet\{Condorcet, Election};
use CondorcetPHP\Condorcet\Algo\Methods\RankedPairs\RankedPairsMargin;
use CondorcetPHP\Condorcet\Throwable\{VotingMethodIsNotImplemented, ResultException};

beforeEach(function (): void {
    $this->election1 = new Election;
});

test('get result as string', function (): void {
    $this->election1->addCandidate('B');
    $this->election1->addCandidate('A');
    $this->election1->addCandidate('C');

    $this->election1->parseVotes('
            B > A > C * 7
            A > B > C * 7
            C > A > B * 2
            C > B > A * 2
        ');

    expect($this->election1->getResult('Ranked Pairs')->rankingAsString)->toBe('A = B > C');
});

test('get result as internal key', function (): void {
    $this->election1->addCandidate('B');
    $this->election1->addCandidate('A');
    $this->election1->addCandidate('C');

    $this->election1->parseVotes('
            B > A > C * 7
            A > B > C * 7
            C > A > B * 2
            C > B > A * 2
        ');

    expect($this->election1->getResult('Ranked Pairs')->rawRanking)->toBe([1 => [0, 1], 2 => [2]]);
});

test('get condorcet election generator version', function (): void {
    $this->election1->addCandidate('B');
    $this->election1->addCandidate('A');
    $this->election1->addCandidate('C');

    $this->election1->addVote('C > B > A');

    expect($this->election1->getResult('Ranked Pairs')->electionCondorcetVersion)->toBe(Condorcet::getVersion());
});

test('result classgenerator', function (): void {
    $this->election1->addCandidate('B');
    $this->election1->addCandidate('A');
    $this->election1->addCandidate('C');

    $this->election1->addVote('C > B > A');

    expect($this->election1->getResult('Ranked Pairs')->byClass)->toBe(RankedPairsMargin::class);
});

test('method', function (): void {
    $this->election1->addCandidate('B');
    $this->election1->addCandidate('A');
    $this->election1->addCandidate('C');

    $this->election1->addVote('C > B > A');

    expect($this->election1->getResult('Ranked Pairs')->fromMethod)->toBeString()->toBe(RankedPairsMargin::METHOD_NAME[0]);
});

test('get build time stamp', function (): void {
    $this->election1->addCandidate('B');
    $this->election1->addCandidate('A');
    $this->election1->addCandidate('C');

    $this->election1->addVote('C > B > A');

    expect($this->election1->getResult('Ranked Pairs')->buildTimestamp)->toBeFloat();
});

test('get winner', function (): void {
    $this->election1->addCandidate('a');
    $this->election1->addCandidate('b');
    $this->election1->addCandidate('c');

    $this->election1->parseVotes('
            a > c > b * 23
            b > c > a * 19
            c > b > a * 16
            c > a > b * 2
        ');

    expect($this->election1->getResult()->Winner)->toEqual('c');
    expect($this->election1->getResult()->CondorcetWinner)->toEqual('c');
});

test('get loser', function (): void {
    $this->election1->addCandidate('Memphis');
    $this->election1->addCandidate('Nashville');
    $this->election1->addCandidate('Knoxville');
    $this->election1->addCandidate('Chattanooga');

    $this->election1->parseVotes('
            Memphis > Nashville > Chattanooga * 42
            Nashville > Chattanooga > Knoxville * 26
            Chattanooga > Knoxville > Nashville * 15
            Knoxville > Chattanooga > Nashville * 17
        ');

    expect($this->election1->getResult()->Loser)->toEqual('Memphis');
    expect($this->election1->getResult()->CondorcetLoser)->toEqual('Memphis');
});

test('get original result', function (bool $changeCandidateName): void {
    $candidateA = $this->election1->addCandidate('a');
    $this->election1->addCandidate('b');
    $this->election1->addCandidate('c');

    $this->election1->addVote('a > b > c');

    $result = $this->election1->getResult();

    if ($changeCandidateName) {
        $candidateA->name = 'z';
    }

    expect($result->originalRankingAsArrayString)->toBe([
        1 => 'a',
        2 => 'b',
        3 => 'c',
    ]);

    expect($result->originalRankingAsString)->toBe('a > b > c');
})->with([false, true]);

test('offset set', function (): void {
    $this->election1->addCandidate('B');
    $this->election1->addCandidate('A');
    $this->election1->addCandidate('C');

    $this->election1->addVote('C > B > A');

    $result = $this->election1->getResult('Schulze');

    $this->expectException(ResultException::class);
    $this->expectExceptionMessage('Result cannot be changed');

    $result[] = 42;
});

test('off unset', function (): void {
    $this->election1->addCandidate('B');
    $this->election1->addCandidate('A');
    $this->election1->addCandidate('C');

    $this->election1->addVote('C > B > A');

    $result = $this->election1->getResult('Schulze');

    expect(isset($result[1]))->toBeTrue();

    $this->expectException(ResultException::class);
    $this->expectExceptionMessage('Result cannot be changed');

    unset($result[1]);
});

test('iterator', function (): void {
    $this->election1->addCandidate('B');
    $this->election1->addCandidate('A');
    $this->election1->addCandidate('C');

    $vote = $this->election1->addVote('C > B > A');

    $result = $this->election1->getResult('Schulze');

    foreach ($result as $key => $value) {
        expect($value)->toBe($vote->getRanking()[$key]);
    }
});

test('bad method name', function (): void {
    $this->election1->addCandidate('B');
    $this->election1->addCandidate('A');
    $this->election1->addCandidate('C');

    $this->election1->parseVotes('A>B>C');

    $this->expectException(VotingMethodIsNotImplemented::class);
    $this->expectExceptionMessage('The voting algorithm is not available: bad method');

    $this->election1->getResult('bad method');
});

test('result rank ordering', function (): void {
    $this->election1->addCandidate('B');
    $this->election1->addCandidate('C');
    $this->election1->addCandidate('A');

    $this->election1->addVote('C = A = B');

    expect($this->election1->getResult()->originalRankingAsArrayString)->toBe([1 => ['A', 'B', 'C']]);
});

test('proportional', function (): void {
    $this->election1->addCandidate('A');
    $this->election1->addCandidate('B');
    $this->election1->addCandidate('C');

    $this->election1->addVote('A');

    $this->election1->setNumberOfSeats(2);

    $result = $this->election1->getResult('STV');

    expect($result->seats)->toBe(2);

    expect($result->byClass::IS_PROPORTIONAL)->toBeTrue();

    expect($result->isProportional)->toBeTrue();

    $result = $this->election1->getResult('Schulze');

    expect($result->seats)->toBeNull();

    expect($result->byClass::IS_PROPORTIONAL)->toBeFalse();

    expect($result->isProportional)->toBeFalse();
});

test('method option', function (): void {
    $this->election1->addCandidate('A');
    $this->election1->addCandidate('B');
    $this->election1->addCandidate('C');

    $this->election1->addVote('A>B>C');

    $class = Condorcet::getMethodClass('Borda');

    expect($class::$optionStarting)->toBe(1);

    $b1 = $this->election1->getResult('Borda');
    $c1 = $this->election1->getResult('Copeland');

    expect($b1->methodOptions['Starting'])->toBe(1);

    expect($this->election1->setMethodOption('Borda Count', 'Starting', 0));
    expect($class::$optionStarting)->toBe(0);

    expect($b1->methodOptions['Starting'])->toBe(1);

    $b2 = $this->election1->getResult('Borda');
    $c2 = $this->election1->getResult('Copeland');

    expect($b2)->not()->toBe($b1);
    expect($c2)->toBe($c1);

    expect($b2->methodOptions['Starting'])->toBe(0);

    expect(fn(): never => $this->election1->setMethodOption('Unregistered method', 'Starting', 0))->toThrow(VotingMethodIsNotImplemented::class);
});

test('verbosity level', function (): void {
    $this->election1->addCandidate('A');
    $this->election1->addCandidate('B');
    $this->election1->addCandidate('C');

    $this->election1->addVote('A>B>C');

    $r1 = $this->election1->getResult(KemenyYoung::class);
    expect($r1->statsVerbosity)->toBe(StatsVerbosity::STD);

    expect($this->election1->setStatsVerbosity(StatsVerbosity::STD))->toBe($this->election1);
    $r2 = $this->election1->getResult(KemenyYoung::class);
    expect($r2)->toBe($r1);

    $this->election1->setStatsVerbosity(StatsVerbosity::FULL);
    $r3 = $this->election1->getResult(KemenyYoung::class);

    expect($r1->statsVerbosity)->toBe(StatsVerbosity::STD);
    expect($r3->statsVerbosity)->toBe(StatsVerbosity::FULL);

    expect($r3)->not()->toBe($r1);
    expect($r1->stats->asArray)->not()->toHaveKey('Ranking Scores');
    expect($r3->stats->asArray)->toHaveKey('Ranking Scores');
});

test('immutable pairwise', function (string $method): void {
    $election = new Election;
    $election->parseCandidates('A;B');
    $election->parseVotes('
            tag1 || A > B
            tag2 || B > A
        ');

    $resultGlobal = $election->getResult($method);

    $resultTag1 = $election->getResult(method: $method, methodOptions: ['%tagFilter' => true, 'tags' => 'tag1']);
    $resultTag2 = $election->getResult(method: $method, methodOptions: ['%tagFilter' => true, 'tags' => 'tag2']);

    $testSuite = static function () use ($resultGlobal, $resultTag1, $resultTag2): void {
        expect($resultGlobal->pairwise)->toBe([
            'A' => [
                'win' => [
                    'B' => 1,
                ],
                'null' => [
                    'B' => 0,
                ],
                'lose' => [
                    'B' => 1,
                ],
            ],
            'B' => [
                'win' => [
                    'A' => 1,
                ],
                'null' => [
                    'A' => 0,
                ],
                'lose' => [
                    'A' => 1,
                ],
            ],
        ]);

        expect($resultTag1->pairwise)->toBe([
            'A' => [
                'win' => [
                    'B' => 1,
                ],
                'null' => [
                    'B' => 0,
                ],
                'lose' => [
                    'B' => 0,
                ],
            ],
            'B' => [
                'win' => [
                    'A' => 0,
                ],
                'null' => [
                    'A' => 0,
                ],
                'lose' => [
                    'A' => 1,
                ],
            ],
        ]);

        expect($resultTag2->pairwise)->toBe([
            'A' => [
                'win' => [
                    'B' => 0,
                ],
                'null' => [
                    'B' => 0,
                ],
                'lose' => [
                    'B' => 1,
                ],
            ],
            'B' => [
                'win' => [
                    'A' => 1,
                ],
                'null' => [
                    'A' => 0,
                ],
                'lose' => [
                    'A' => 0,
                ],
            ],
        ]);
    };

    // Run first
    $testSuite();

    // Add a vote, nothing move
    $election->parseVotes('tag1 || A > B');

    $testSuite();
})->with(static fn(): array => getMethodList());
