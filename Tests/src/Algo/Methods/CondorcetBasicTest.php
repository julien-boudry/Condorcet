<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\Algo\Methods;

use CondorcetPHP\Condorcet\{Condorcet, Election, Vote};
use CondorcetPHP\Condorcet\Algo\Methods\CondorcetBasic;
use CondorcetPHP\Condorcet\Throwable\AlgorithmWithoutRankingFeatureException;
use PHPUnit\Framework\TestCase;

class CondorcetBasicTest extends TestCase
{
    private readonly Election $election;

    protected function setUp(): void
    {
        $this->election = new Election;
    }

    public function testResult_Basic(): void
    {
        $this->election->addCandidate('a');
        $this->election->addCandidate('b');
        $this->election->addCandidate('c');

        $vote = new Vote('a');

        $this->election->addVote($vote);

        self::assertEquals('a', $this->election->getCondorcetWinner());
    }

    public function testResult_1(): void
    {
        $this->election->addCandidate('a');
        $this->election->addCandidate('b');
        $this->election->addCandidate('c');

        $this->election->parseVotes('
            a > c > b * 23
            b > c > a * 19
            c > b > a * 16
            c > a > b * 2
        ');

        self::assertEquals('c', $this->election->getCondorcetWinner());
    }

    public function testResult_2(): void
    {
        $this->election->addCandidate('X');
        $this->election->addCandidate('Y');
        $this->election->addCandidate('Z');

        $this->election->parseVotes('
            X > Y > Z * 41
            Y > Z > X * 33
            Z > X > Y * 22
        ');

        self::assertNull($this->election->getWinner());

        // Schulze Substitution
        self::assertEquals('X', $this->election->getWinner('Schulze'));
    }

    public function testResult_3(): void
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

        self::assertEquals('Nashville', $this->election->getCondorcetWinner());
        self::assertEquals('Memphis', $this->election->getCondorcetLoser());
    }

    public function testResult_4(): void
    {
        $this->election->addCandidate('Memphis');
        $this->election->addCandidate('Nashville');
        $this->election->addCandidate('Knoxville');
        $this->election->addCandidate('Chattanooga');

        $this->election->parseVotes('
            Memphis > Chattanooga > Nashville * 42
            Nashville > Chattanooga > Knoxville * 26
            Chattanooga > Knoxville > Nashville * 15
            Knoxville > Chattanooga > Nashville * 17
        ');

        self::assertEquals('Chattanooga', $this->election->getCondorcetWinner());
    }

    public function testResult_5(): void
    {
        # From https://en.wikipedia.org/wiki/Condorcet_loser_criterion

        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');
        $this->election->addCandidate('L');

        $this->election->parseVotes('
            A > B > C * 1
            A > B > L * 1
            B > C > A * 3
            C > L > A * 1
            L > A > B * 1
            L > C > A * 2
        ');

        self::assertEquals('L', $this->election->getCondorcetLoser());
        self::assertNull($this->election->getCondorcetWinner());
    }

    public function testResult_6(): void
    {
        # From https://en.wikipedia.org/wiki/Condorcet_loser_criterion

        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');
        $this->election->addCandidate('L');

        $this->election->parseVotes('
            A > B > L
            B > C > L
            A > C > L
        ');

        self::assertEquals('L', $this->election->getCondorcetLoser());
    }

    public function testNoResultObject(): never
    {
        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');
        $this->election->addCandidate('L');

        $this->election->parseVotes('
            A > B > L
            B > C > L
            A > C > L
        ');

        $this->expectException(AlgorithmWithoutRankingFeatureException::class);
        $this->expectExceptionMessage("This algortihm can't provide a full ranking (but only Winner and Loser): ".CondorcetBasic::METHOD_NAME[0]);

        $this->election->getResult(CondorcetBasic::class);
    }
}
