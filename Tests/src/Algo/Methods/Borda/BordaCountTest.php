<?php
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\Algo\Methods\Borda;

use CondorcetPHP\Condorcet\{Candidate, Condorcet, CondorcetUtil, Election, Result, Vote, VoteConstraint};
use PHPUnit\Framework\TestCase;

class BordaCountTest extends TestCase
{
    private readonly Election $election;

    public function setUp(): void
    {
        $this->election = new Election;
    }

    public function tearDown(): void
    {
        $this->election->setMethodOption('Borda Count', 'Starting', 1);
    }

    public function testResult_1 (): void
    {
        # From https://fr.wikipedia.org/wiki/M%C3%A9thode_Borda

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

        self::assertSame( [
                1 => 'B',
                2 => 'C',
                3 => 'A',
                4 => 'D' ],
            $this->election->getResult('Borda Count')->getResultAsArray(true)
        );

        self::assertEquals( [
            'B' => 294,
            'C' => 273,
            'A' => 226,
            'D' => 207 ],
            $this->election->getResult('Borda Count')->getStats()
        );
    }

    public function testResult_2 (): void
    {
        # From https://fr.wikipedia.org/wiki/M%C3%A9thode_Borda

        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');
        $this->election->addCandidate('D');

        $this->election->allowsVoteWeight(true);

        $this->election->parseVotes('
            B>A>C>D * 30
            B>A>D>C * 30
            A>C>D>B * 25
            A>D>C>B ^ 15
        ');

        self::assertSame( [
            1 => 'A',
            2 => 'B',
            3 => 'C',
            4 => 'D' ],
            $this->election->getResult('Borda Count')->getResultAsArray(true)
        );

        self::assertEquals( [
            'A' => 340,
            'B' => 280,
            'C' => 195,
            'D' => 185 ],
            $this->election->getResult('Borda Count')->getStats()
        );
    }

    public function testResult_3 (): void
    {
        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');

        $this->election->parseVotes('
            A
        ');

        self::assertSame( [
            1 => 'A',
            2 => ['B','C'] ],
            $this->election->getResult('Borda Count')->getResultAsArray(true)
        );

        self::assertEquals( [
            'A' => 3,
            'B' => 1.5,
            'C' => 1.5 ],
            $this->election->getResult('Borda Count')->getStats()
        );

        $this->election->setImplicitRanking(false);

        self::assertSame( [
            1 => 'A',
            2 => ['B','C'] ],
            $this->election->getResult('Borda Count')->getResultAsArray(true)
        );

        self::assertEquals( [
            'A' => 3,
            'B' => 0,
            'C' => 0 ],
            $this->election->getResult('Borda Count')->getStats()
        );
    }

    public function testResult_4 (): void
    {
        # From https://fr.wikipedia.org/wiki/M%C3%A9thode_Borda

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

        self::assertSame( [
                1 => 'B',
                2 => 'C',
                3 => 'A',
                4 => 'D' ],
            $this->election->getResult('Borda Count')->getResultAsArray(true)
        );

        self::assertEquals( [
            'B' => 294,
            'C' => 273,
            'A' => 226,
            'D' => 207 ],
            $this->election->getResult('Borda Count')->getStats()
        );
    }

    public function testResult_variant (): void
    {
        # From https://fr.wikipedia.org/wiki/M%C3%A9thode_Borda

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

        $this->election->setMethodOption('Borda Count', 'Starting', 0);

        self::assertSame( [
                1 => 'B',
                2 => 'C',
                3 => 'A',
                4 => 'D' ],
            $this->election->getResult('Borda Count')->getResultAsArray(true)
        );

        self::assertEquals( [
            'B' => 294 - 100,
            'C' => 273 - 100,
            'A' => 226 - 100,
            'D' => 207 - 100 ],
            $this->election->getResult('Borda Count')->getStats()
        );
    }

    public function testVeryHighVoteWeightAndPerformances (): void
    {
        $this->election->allowsVoteWeight(true);
        $this->election->parseCandidates('0;1');

        $this->election->parseVotes('1 > 0 ^6973568802');

        self::assertSame('1', $this->election->getResult('Borda Count')->getResultAsString());
    }
}