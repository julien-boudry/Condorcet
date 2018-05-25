<?php
declare(strict_types=1);
namespace Condorcet;


use PHPUnit\Framework\TestCase;


class FtptTest extends TestCase
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
            $this->election->getResult('Ftpt')->getResultAsArray(true)
        );

        self::assertSame( [
            'A' => 42,
            'B' => 26,
            'D' => 17,
            'C' => 15 ],
            $this->election->getResult('Ftpt')->getStats()
        );
    }

    public function testResult_2 ()
    {
        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');
        $this->election->addCandidate('D');

        $this->election->allowVoteWeight(true);

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
            $this->election->getResult('Ftpt')->getResultAsArray(true)
        );

        self::assertSame( [
            'A' => 42,
            'D' => 42,
            'B' => 26,
            'C' => 15 ],
            $this->election->getResult('Ftpt')->getStats()
        );
    }

    public function testResult_3 ()
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
            $this->election->getResult('Ftpt')->getResultAsArray(true)
        );

        self::assertSame( [
            'A' => 1 + 1/2,
            'C' => 1/2,
            'B' => 0 ],
            $this->election->getResult('Ftpt')->getStats()
        );
    }
}