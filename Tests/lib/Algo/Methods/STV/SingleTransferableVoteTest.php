<?php
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\Algo\STV;

use CondorcetPHP\Condorcet\{Candidate, Condorcet, CondorcetUtil, Election, Result, Vote, VoteConstraint};
use \CondorcetPHP\Condorcet\Algo\Methods\STV\SingleTransferableVote;
use PHPUnit\Framework\TestCase;

class SingleTransferableVoteTest extends TestCase
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
        # From https://fr.wikipedia.org/wiki/Vote_alternatif

        $this->election->addCandidate('D');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');
        $this->election->addCandidate('A');

        $this->election->parseVotes('
            A>B>C>D * 28
            A>C>D>B * 14
            B>C>A>D * 15
            C>A>B>D * 17
            D>B>C>A * 26
        ');

        SingleTransferableVote::$seats = 2;

        var_dump($this->election->getResult('STV')->getStats());

        self::assertSame( [
                1 => 'A',
                2 => 'B'
             ],
            $this->election->getResult('STV')->getResultAsArray(true)
        );
    }

    public function testResult_2 () : void
    {
        # From https://fr.wikipedia.org/wiki/Vote_alternatif

        $this->election->addCandidate('Orange');
        $this->election->addCandidate('Pear');
        $this->election->addCandidate('Chocolate');
        $this->election->addCandidate('Strawberry');
        $this->election->addCandidate('Hamburger');

        $this->election->setImplicitRanking(false);

        SingleTransferableVote::$seats = 3;

        $this->election->parseVotes('
            Orange * 4
            Pear > Orange * 2
            Chocolate > Strawberry * 8
            Chocolate > Hamburger * 4
            Strawberry
            Hamburger
        ');

        var_dump($this->election->getResult('STV')->getStats());

        self::assertSame( [
                1 => 'Chocolate',
                2 => 'Orange',
                3 => 'Strawberrie'
             ],
            $this->election->getResult('STV')->getResultAsArray(true)
        );
    }

}