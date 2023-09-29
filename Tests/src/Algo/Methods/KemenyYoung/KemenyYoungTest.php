<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\Algo\Methods\KemenyYoung;

use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Algo\Methods\KemenyYoung\KemenyYoung;
use CondorcetPHP\Condorcet\Algo\StatsVerbosity;
use CondorcetPHP\Condorcet\Throwable\CandidatesMaxNumberReachedException;
use PHPUnit\Framework\Attributes\{DataProvider, Group};
use PHPUnit\Framework\TestCase;

class KemenyYoungTest extends TestCase
{
    private readonly Election $election;

    protected function setUp(): void
    {
        $this->election = new Election;
    }

    public function testResult_1(): void
    {
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


        expect($this->election->getResult('KemenyYoung')->getResultAsArray(true))->toBe([
            1 => 'Nashville',
            2 => 'Chattanooga',
            3 => 'Knoxville',
            4 => 'Memphis',
        ]);

        expect($this->election->getResult('KemenyYoung')->getStats()['Best Score'])->toBe(393);

        expect($this->election->getWinner('KemenyYoung'))->toBe($this->election->getWinner());
    }

    public function testResult2(): void
    {
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

        expect($this->election->getResult('KemenyYoung')->getResultAsArray(true))->toBe([
            1 => 'Elliot',
            2 => 'Roland',
            3 => 'Meredith',
            4 => 'Selden',
        ]);
    }

    public function testStats_1(): void
    {
        $this->election->setStatsVerbosity(StatsVerbosity::FULL);

        $this->election->addCandidate('A');
        $this->election->addCandidate('B');

        $this->election->parseVotes($r = 'A > B');

        expect($this->election->getResult('KemenyYoung')->getResultAsString())->toBe($r);

        expect($this->election->getResult('KemenyYoung')->getStats())->toBe([
            'Best Score' => 1,
            'Ranking In Conflicts' => 0,
            'Ranking Scores' => [
                [1 => 'A', 2 => 'B', 'score' => 1],
                [1 => 'B', 2 => 'A', 'score' => 0],
            ],
        ]);

        $this->election->setStatsVerbosity(StatsVerbosity::STD);

        expect($this->election->getResult('KemenyYoung')->getStats())->not()->toHaveKey('rankingScores');
    }

    public function testMaxCandidates(): never
    {
        for ($i = 0; $i < (KemenyYoung::$MaxCandidates + 1); $i++) {
            $this->election->addCandidate();
        }

        $this->election->parseVotes('A');

        $this->expectException(CandidatesMaxNumberReachedException::class);
        $this->expectExceptionMessage("Maximum number of candidates reached: The method 'Kemeny–Young' is configured to accept only ".KemenyYoung::$MaxCandidates.' candidates');

        $this->election->getWinner('KemenyYoung');
    }

    public function testConflicts(): void
    {
        $this->election->parseCandidates('A;B;C');

        $this->election->parseVotes('
            A>B>C;
            B>C>A;
            C>A>B');

        $result = $this->election->getResult('KemenyYoung');

        expect($result->getWarning(\CondorcetPHP\Condorcet\Algo\Methods\KemenyYoung\KemenyYoung::CONFLICT_WARNING_CODE))->toEqual([0 => [
            'type' => 42,
            'msg' => '3;5',
        ],
        ]);

        expect($result->getWarning())->toEqual([0 => [
            'type' => 42,
            'msg' => '3;5',
        ],
        ]);

        expect($result->getStats()['Ranking In Conflicts'])->toBe(3);

        $this->election->addVote('A>B>C');

        $result = $this->election->getResult('KemenyYoung');

        expect($result->getWarning(\CondorcetPHP\Condorcet\Algo\Methods\KemenyYoung\KemenyYoung::CONFLICT_WARNING_CODE))->toBe([]);

        expect($this->election->getWinner('KemenyYoung'))->toEqual('A');
    }

    public function testKemenyWithOnly1Candidate(): void
    {
        $candidate[] = $this->election->addCandidate();

        $this->election->addVote($candidate);

        expect($this->election->getWinner('KemenyYoung'))->toBe($candidate[0]);
    }

    public static function ManyCandidatesProvider(): array
    {
        return [
            9  => [9],
            10  => [10],
        ];
    }

    #[Group('large')]
    #[DataProvider('ManyCandidatesProvider')]
    public function testKemenyWithManyCandidates(int $candidatesCount): void
    {
        $original = KemenyYoung::$MaxCandidates;
        KemenyYoung::$MaxCandidates = null;

        for ($i = 0; $i < $candidatesCount; $i++) {
            $candidates[] = $this->election->addCandidate();
        }

        $this->election->addVote($candidates);

        expect($this->election->getWinner('KemenyYoung'))->toBe($candidates[0]);

        KemenyYoung::$MaxCandidates = $original;
    }
}
