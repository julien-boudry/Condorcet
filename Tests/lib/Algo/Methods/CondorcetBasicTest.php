<?php
declare(strict_types=1);
namespace CondorcetPHP\Condorcet;


use PHPUnit\Framework\TestCase;


class CondorcetBasicTest extends TestCase
{
    private $election;

    public function setUp() : void
    {
        $this->election = new Election;
    }

    public function testResult_1 ()
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

        self::assertEquals('c', $this->election->getWinner());
    }

    public function testResult_2 ()
    {
        $this->election->addCandidate('X');
        $this->election->addCandidate('Y');
        $this->election->addCandidate('Z');

        $this->election->parseVotes('
            X > Y > Z * 41
            Y > Z > X * 33
            Z > X > Y * 22
        ');

        self::assertSame(null, $this->election->getWinner());

        // Schulze Substitution
        self::assertEquals('X', $this->election->getWinner('Schulze'));
    }

    public function testResult_3 ()
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

        self::assertEquals('Nashville', $this->election->getWinner());
        self::assertEquals('Memphis', $this->election->getLoser());
    }

    public function testResult_4 ()
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

        self::assertEquals('Chattanooga', $this->election->getWinner());
    }

    public function testResult_5 ()
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

        self::assertEquals('L', $this->election->getLoser());
        self::assertSame(null, $this->election->getWinner());
    }

    public function testResult_6 ()
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

        self::assertEquals('L', $this->election->getLoser());
    }

    public function testNoResultObject ()
    {
        $this->expectException(\CondorcetPHP\Condorcet\Throwable\CondorcetException::class);
        $this->expectExceptionCode(102);

        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');
        $this->election->addCandidate('L');

        $this->election->parseVotes('
            A > B > L
            B > C > L
            A > C > L
        ');

        $this->election->getResult(Algo\Methods\CondorcetBasic::class);
    }

}