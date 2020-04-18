<?php
declare(strict_types=1);
namespace CondorcetPHP\Condorcet;


use PHPUnit\Framework\TestCase;


class FirstPastThePostTest extends TestCase
{
    /**
     * @var election
     */
    private  Election $election;

    public function setUp() : void
    {
        $this->election = new Election;
    }

    public function testResult_1 () : void
    {
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
                1 => 'A',
                2 => 'B',
                3 => 'D',
                4 => 'C' ],
            $this->election->getResult('Fptp')->getResultAsArray(true)
        );

        self::assertSame( [
            'A' => 42,
            'B' => 26,
            'D' => 17,
            'C' => 15 ],
            $this->election->getResult('Fptp')->getStats()
        );
    }

    public function testResult_2 () : void
    {
        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');
        $this->election->addCandidate('D');

        $this->election->allowsVoteWeight(true);

        $this->election->parseVotes('
            A>B>C>D ^ 42
            B>C>D>A * 26
            C>D>B>A ^ 15
            D>C>B>A * 17
            D>B=C=A ^ 25
        ');

        self::assertSame( [
            1 => ['A','D'],
            2 => 'B',
            3 => 'C' ],
            $this->election->getResult('Fptp')->getResultAsArray(true)
        );

        self::assertSame( [
            'A' => 42,
            'D' => 42,
            'B' => 26,
            'C' => 15 ],
            $this->election->getResult('Fptp')->getStats()
        );
    }

    public function testResult_3 () : void
    {
        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');

        $this->election->parseVotes('
            A>B>C
            A=C>B
        ');

        self::assertSame( [
            1 => 'A',
            2 => 'C',
            3 => 'B' ],
            $this->election->getResult('Fptp')->getResultAsArray(true)
        );

        self::assertSame( [
            'A' => 1 + 1/2,
            'C' => 1/2,
            'B' => 0 ],
            $this->election->getResult('Fptp')->getStats()
        );
    }
}