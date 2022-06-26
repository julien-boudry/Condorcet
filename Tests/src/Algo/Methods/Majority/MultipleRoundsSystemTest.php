<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Methods\Tests;

use CondorcetPHP\Condorcet\{Candidate, Condorcet, CondorcetUtil, Election, Result, Vote, VoteConstraint};
use PHPUnit\Framework\TestCase;

class MultipleRoundsSystemTest extends TestCase
{
    private readonly Election $election;

    protected function setUp(): void
    {
        $this->election = new Election;
    }

    protected function tearDown(): void
    {
        $this->resetOptions();
    }

    protected function resetOptions(): void
    {
        $methodClass = Condorcet::getMethodClass('runoff voting');

        $this->election->setMethodOption($methodClass, 'MAX_ROUND', 2);
        $this->election->setMethodOption($methodClass, 'TARGET_NUMBER_OF_CANDIDATES_FOR_THE_NEXT_ROUND', 2);
        $this->election->setMethodOption($methodClass, 'NUMBER_OF_TARGETED_CANDIDATES_AFTER_EACH_ROUND', 0);
    }

    public function testResult_MajorityTest_systematic_triangular(): void
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

        $methodClass = Condorcet::getMethodClass('Multiple Rounds System');

        $this->election->setMethodOption($methodClass, 'MAX_ROUND', 2);
        $this->election->setMethodOption($methodClass, 'TARGET_NUMBER_OF_CANDIDATES_FOR_THE_NEXT_ROUND', 3);
        $this->election->setMethodOption($methodClass, 'NUMBER_OF_TARGETED_CANDIDATES_AFTER_EACH_ROUND', 0);

        self::assertSame(
            [
                1 => 'A',
                2 => 'D',
                3 => 'B',
                4 => 'C', ],
            $this->election->getResult('Multiple Rounds System')->getResultAsArray(true)
        );

        self::assertEquals(
            [1=> [
                'A' => 42,
                'B' => 26,
                'D' => 17,
                'C' => 15,
            ],
                2=> [
                    'A' => 42,
                    'D' => 32,
                    'B' => 26,
                ],
            ],
            $this->election->getResult('Multiple Rounds System')->getStats()
        );
    }


    public function testResult_MajorityTest_three_round(): void
    {
        $this->election->allowsVoteWeight(true);

        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');
        $this->election->addCandidate('D');
        $this->election->addCandidate('E');

        $this->election->parseVotes('
            A>B ^10
            B ^12
            C>B ^10
            D>E>A>B ^9
            E>B ^5
        ');

        $methodClass = Condorcet::getMethodClass('runoff voting');

        $this->election->setMethodOption($methodClass, 'MAX_ROUND', 3);
        $this->election->setMethodOption($methodClass, 'TARGET_NUMBER_OF_CANDIDATES_FOR_THE_NEXT_ROUND', 2);
        $this->election->setMethodOption($methodClass, 'NUMBER_OF_TARGETED_CANDIDATES_AFTER_EACH_ROUND', 0);


        self::assertSame(
            [1 => 'B', 2 => 'A', 3 => 'C', 4=> 'D', 5=> 'E'],
            $this->election->getResult('Multiple Rounds System')->getResultAsArray(true)
        );

        self::assertEquals(
            [1=> [
                'B' => 12,
                'A' => 10,
                'C' => 10,
                'D' => 9,
                'E' => 5,
            ],
                2=> [
                    'A' => 19,
                    'B' => 17,
                    'C' => 10,
                ],
                3=> [
                    'B' => 27,
                    'A' => 19,
                ],
            ],
            $this->election->getResult('runoff voting')->getStats()
        );
    }

    public function testResult_MajorityTest_Many_Round(): void
    {
        $this->election->allowsVoteWeight(true);

        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');
        $this->election->addCandidate('D');
        $this->election->addCandidate('E');
        $this->election->addCandidate('F');

        $this->election->parseVotes('
            A>B>C>D>E>F ^100
            B>A>C>D>E>F ^99
            C>A>B>D>E>F ^98
            D>A=B>C>E>F ^97
            E>B>A>C>D>F ^96
            F>A>B>C>D>E ^95
        ');

        $methodClass = Condorcet::getMethodClass('Multiple Rounds System');

        $this->election->setMethodOption($methodClass, 'MAX_ROUND', 100);
        $this->election->setMethodOption($methodClass, 'TARGET_NUMBER_OF_CANDIDATES_FOR_THE_NEXT_ROUND', 5);
        $this->election->setMethodOption($methodClass, 'NUMBER_OF_TARGETED_CANDIDATES_AFTER_EACH_ROUND', -1);

        self::assertSame(
            [1 => 'A', 2 => 'B', 3 => 'C', 4=> 'D', 5=> 'E', 6 => 'F'],
            $this->election->getResult('Multiple Rounds System')->getResultAsArray(true)
        );

        self::assertEquals(
            [1=> [
                'A' => 100,
                'B' => 99,
                'C' => 98,
                'D' => 97,
                'E' => 96,
                'F' => 95,
            ],
                2=> [
                    'A' => 100 + 95,
                    'B' => 99,
                    'C' => 98,
                    'D' => 97,
                    'E' => 96,
                ],
                3=> [
                    'A' => 100 + 95,
                    'B' => 99 + 96,
                    'C' => 98,
                    'D' => 97,
                ],
                4=> [
                    'A' => 100 + 95 + (97/2),
                    'B' => 99 + 96 + (97/2),
                    'C' => 98,
                ],
                5=> [
                    'A' => 100 + 95 + (97/2) + 98,
                    'B' => 99 + 96 + (97/2),
                ],
            ],
            $this->election->getResult('runoff voting')->getStats()
        );
    }
}
