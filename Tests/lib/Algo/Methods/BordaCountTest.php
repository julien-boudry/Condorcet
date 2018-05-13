<?php
declare(strict_types=1);
namespace Condorcet;


use PHPUnit\Framework\TestCase;


class BordaCountTest extends TestCase
{
    /**
     * @var election
     */
    private $election;

    public function setUp()
    {
        $this->election = new Election;
    }

    public function testResult_1 ()
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

        self::assertEquals( [
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

    public function testResult_2 ()
    {
        # From https://fr.wikipedia.org/wiki/M%C3%A9thode_Borda

        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');
        $this->election->addCandidate('D');

        $this->election->parseVotes('
            B>A>C>D * 30
            B>A>D>C * 30
            A>C>D>B * 25
            A>D>C>B * 15
        ');

        self::assertEquals( [
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
}