<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\Algo\Methods\InstantRunoff;

use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Vote;
use CondorcetPHP\Condorcet\Tools\Converters\DavidHillFormat;
use PHPUnit\Framework\TestCase;

class InstantRunoffTest extends TestCase
{
    private readonly Election $election;

    protected function setUp(): void
    {
        $this->election = new Election;
    }

    public function testResult_1(): void
    {
        # From https://fr.wikipedia.org/wiki/Vote_alternatif

        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');
        $this->election->addCandidate('D');

        $this->election->parseVotes('
            A>B>C>D * 42
            B>C>D>A * 26
            C>D>B>A * 15
            D>C>B>A * 17
        ');

        $this->assertSame(
            [
                1 => 'D',
                2 => 'A',
                3 => 'B',
                4 => 'C', ],
            $this->election->getResult('InstantRunoff')->getResultAsArray(true)
        );

        $this->assertSame(
            [
                'majority' => 50.0,
                'rounds' => [
                    1 => [
                        'A' => 42,
                        'B' => 26,
                        'C' => 15,
                        'D' => 17,
                    ],
                    2 => [
                        'A' => 42,
                        'B' => 26,
                        'D' => 32,
                    ],
                    3 => [
                        'A' => 42,
                        'D' => 58,
                    ],
                ],
            ],
            $this->election->getResult('InstantRunoff')->getStats()
        );
    }

    public function testResult_2(): void
    {
        # From https://en.wikipedia.org/wiki/Instant-runoff_voting#Examples

        $this->election->addCandidate('bob');
        $this->election->addCandidate('sue');
        $this->election->addCandidate('bill');

        $this->election->parseVotes('
            bob > bill > sue
            sue > bob > bill
            bill > sue > bob
            bob > bill > sue
            sue > bob > bill
        ');

        $this->assertSame(
            [
                1 => 'sue',
                2 => 'bob',
                3 => 'bill', ],
            $this->election->getResult('InstantRunoff')->getResultAsArray(true)
        );
    }

    public function testResult_3(): void
    {
        $this->election->addCandidate('bob');
        $this->election->addCandidate('sue');
        $this->election->addCandidate('bill');

        $this->election->parseVotes('
            bob > bill > sue
            sue > bob > bill
            bill > sue > bob
            bob > bill > sue
            sue > bob > bill
            bill > bob > sue
        ');

        $this->assertSame(
            [
                1 => 'bob',
                2 => 'bill',
                3 => 'sue', ],
            $this->election->getResult('InstantRunoff')->getResultAsArray(true)
        );
    }

    public function testResult_4(): void
    {
        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');

        $this->election->parseVotes('
            A=B=C
        ');

        $this->assertSame(
            [
                1 => ['A', 'B', 'C'], ],
            $this->election->getResult('InstantRunoff')->getResultAsArray(true)
        );
    }

    public function testResult_Equality(): void
    {
        $this->election->addCandidate('A');
        $this->election->addCandidate('B');

        $this->election->parseVotes('
            A
            B
        ');

        $this->assertSame(
            [
                1 => ['A', 'B'], ],
            $this->election->getResult('InstantRunoff')->getResultAsArray(true)
        );
    }

    public function testResult_TieBreaking(): void
    {
        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');
        $this->election->addCandidate('D');

        $this->election->parseVotes('
            A * 4
            B * 4
            C>A * 2
            D>C>B * 2
        ');

        $this->assertSame(
            [
                1 => ['A', 'B'],
                3 => 'C',
                4 => 'D',
            ],
            $this->election->getResult('InstantRunoff')->getResultAsArray(true)
        );
    }

    public function testInfiniteLoopOnTidemanDataset3IfExplicitRanking(): void
    {
        $election = (new DavidHillFormat(__DIR__.'/../../../Tools/Converters/TidemanData/A3.HIL'))->setDataToAnElection();

        $election->setImplicitRanking(false);

        expect($election->getResult('InstantRunoff')->getResultAsString())->toBe('6 > 8 > 4 > 11 > 2 > 5 > 14 > 1 = 7 > 12 > 3 > 9 > 10 > 15 > 13');
    }
}
