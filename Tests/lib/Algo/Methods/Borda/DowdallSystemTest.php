<?php
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\Algo\Methods\Borda;

use CondorcetPHP\Condorcet\{Candidate, Condorcet, CondorcetUtil, Election, Result, Vote, VoteConstraint};
use PHPUnit\Framework\TestCase;

class DowdallSystemTest extends TestCase
{
    /**
     * @var election
     */
    private Election $election;

    public function setUp() : void
    {
        $this->election = new Election;
    }

    public function testResult_1 () : void
    {
        # From https://en.wikipedia.org/wiki/Borda_count

        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');
        $this->election->addCandidate('D');
        $this->election->addCandidate('E');
        $this->election->addCandidate('F');

        $this->election->parseVotes('
            A>B>C>D>E>F
        ');

        self::assertSame( [
                1 => 'A',
                2 => 'B',
                3 => 'C',
                4 => "D",
                5 => "E",
                6 => 'F' ],
            $this->election->getResult('DowdallSystem')->getResultAsArray(true)
        );

        self::assertEquals( [
            'A' => 1/1,
            'B' => 1/2,
            'C' => 1/3,
            'D' => 1/4,
            'E' => 1/5,
            'F' => 1/6 ],
            $this->election->getResult('DowdallSystem')->getStats()
        );
    }
}