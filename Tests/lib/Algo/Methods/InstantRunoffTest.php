<?php
declare(strict_types=1);
namespace Condorcet;


use PHPUnit\Framework\TestCase;


class InstantRunoffTest extends TestCase
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

        self::assertEquals( [
                1 => 'A',
                2 => 'B',
                3 => 'C',
                4 => 'D' ],
            $this->election->getResult('Borda Count')->getResultAsArray(true)
        );

    }

    public function testResult_2 ()
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

        self::assertEquals( [
            1 => 'Sue',
            2 => 'Bob',
            3 => 'Bill' ],
            $this->election->getResult('Borda Count')->getResultAsArray(true)
        );

    }

}