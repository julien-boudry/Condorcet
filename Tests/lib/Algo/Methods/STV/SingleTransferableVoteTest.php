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
        # From https://fr.wikipedia.org/wiki/Scrutin_%C3%A0_vote_unique_transf%C3%A9rable

        $this->election->addCandidate('D');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');
        $this->election->addCandidate('A');

        $this->election->allowsVoteWeight();

        $this->election->parseVotes('
            A>B>C>D ^ 28
            A>C>D>B ^ 14
            B>C>A>D ^ 15
            C>A>B>D ^ 17
            D>B>C>A ^ 26
        ');

        SingleTransferableVote::$seats = 2;

        self::assertSame(
            [
                1 =>
                    [
                    'A' => 42.0,
                    'D' => 26.0,
                    'C' => 17.0,
                    'B' => 15.0,
                    ],
                2 =>
                    [
                    'D' => 26.0,
                    'B' => 20.333333333333,
                    'C' => 19.666666666667,
                    ],
                3 =>
                    [
                    'B' => 37.333333333333,
                    'D' => 28.666666666667,
                    ],
            ],
            $this->election->getResult('STV')->getStats()
        );

        self::assertSame( [
                1 => 'A',
                2 => 'B'
             ],
            $this->election->getResult('STV')->getResultAsArray(true)
        );
    }

    public function testResult_2 () : void
    {
        # From https://en.wikipedia.org/wiki/Single_transferable_vote

        $this->election->addCandidate('Orange');
        $this->election->addCandidate('Pear');
        $this->election->addCandidate('Chocolate');
        $this->election->addCandidate('Strawberry');
        $this->election->addCandidate('Hamburger');

        $this->election->setImplicitRanking(false);
        $this->election->allowsVoteWeight();

        SingleTransferableVote::$seats = 3;

        $this->election->parseVotes('
            Orange ^ 4
            Pear > Orange * 2
            Chocolate > Strawberry * 8
            Chocolate > Hamburger * 4
            Strawberry
            Hamburger
        ');

        self::assertSame(
            [
                1 =>
                [
                'Chocolate' => 12.0,
                'Orange' => 4.0,
                'Pear' => 2.0,
                'Strawberry' => 1.0,
                'Hamburger' => 1.0,
                ],
                2 =>
                [
                'Strawberry' => 5.0,
                'Orange' => 4.0,
                'Hamburger' => 3.0,
                'Pear' => 2.0,
                ],
                3 =>
                [
                'Orange' => 6.0,
                'Strawberry' => 5.0,
                'Hamburger' => 3.0,
                ],
                4 =>
                [
                'Strawberry' => 5.0,
                'Hamburger' => 3.0,
                ],
                5 =>
                [
                'Strawberry' => 5.0,
                ],
            ]
        , $this->election->getResult('STV')->getStats());

        self::assertSame( [
                1 => 'Chocolate',
                2 => 'Orange',
                3 => 'Strawberry'
             ],
            $this->election->getResult('STV')->getResultAsArray(true)
        );
    }

    public function testResult_3 () : void
    {
        # From https://en.wikipedia.org/wiki/Schulze_STV

        $this->election->addCandidate('Andrea');
        $this->election->addCandidate('Brad');
        $this->election->addCandidate('Carter');

        $this->election->setImplicitRanking(false);
        $this->election->allowsVoteWeight();

        SingleTransferableVote::$seats = 2;

        $this->election->parseVotes('
            Andrea > Brad > Carter ^ 12
            Andrea > Carter > Brad ^ 26
            Andrea > Carter > Brad ^ 12
            Carter > Andrea > Brad ^ 13
            Brad ^ 27
        ');

        self::assertSame(
            [
                1 =>
                    [
                    'Andrea' => 50.0,
                    'Brad' => 27.0,
                    'Carter' => 13.0
                    ],
                2 =>
                    [
                    'Brad' => 31.56,
                    'Carter' => 27.44
                    ]
            ],
            $this->election->getResult('STV')->getStats()
        );

        self::assertSame( [
                1 => 'Andrea',
                2 => 'Brad'
             ],
            $this->election->getResult('STV')->getResultAsArray(true)
        );
    }

}