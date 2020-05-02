<?php
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Methods\Tests;

use CondorcetPHP\Condorcet\{Candidate, Condorcet, CondorcetUtil, Election, Result, Vote, VoteConstraint};
use CondorcetPHP\Condorcet\Algo\Methods\Majority\Majority_Core;
use PHPUnit\Framework\TestCase;

class MajorityCoreTest extends TestCase
{
    /**
     * @var election
     */
    private  Election $election;

    public function setUp() : void
    {
        $this->election = new Election;
    }

    public function testResult_MajorityTest_systematic_triangular () : void
    {
        self::assertTrue(Condorcet::addMethod(MajorityTest_systematic_triangular::class));

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
                2 => 'D',
                3 => 'B',
                4 => 'C' ],
            $this->election->getResult('MajorityTest_systematic_triangular')->getResultAsArray(true)
        );

        self::assertSame([  1=> [
                                    'A' => 42,
                                    'B' => 26,
                                    'D' => 17,
                                    'C' => 15
                                ],
                            2=> [
                                'A' => 42,
                                'D' => 32,
                                'B' => 26,
                            ]
                        ],
                        $this->election->getResult('MajorityTest_systematic_triangular')->getStats()
        );
    }


    public function testResult_MajorityTest_three_round () : void
    {
        self::assertTrue(Condorcet::addMethod(MajorityTest_three_round::class));
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

        // self::assertSame( [ 1 => 'B', 2 => 'A', 3 => 'C', 4=> 'D', 5=> 'E' ],
        //     $this->election->getResult('MajorityTest_three_round')->getResultAsArray(true)
        // );

        self::assertSame([  1=> [
                                    'B' => 12,
                                    'A' => 10,
                                    'C' => 10,
                                    'D' => 9,
                                    'E' => 5
                                ],
                            2=> [
                                    'A' => 19,
                                    'B' => 17,
                                    'C' => 10
                                ],
                            3=> [
                                'B' => 27,
                                'A' => 19
                            ]
                        ],
            $this->election->getResult('MajorityTest_three_round')->getStats()
        );

    }

}

class MajorityTest_systematic_triangular extends Majority_Core {
    // Method Name
    public const METHOD_NAME = ['MajorityTest_systematic_triangular'];

    // Mod
    protected const MAX_ROUND = 2;
    protected const TARGET_NUMBER_OF_CANDIDATES_FOR_THE_NEXT_ROUND = 3;
}

class MajorityTest_three_round extends Majority_Core {
    // Method Name
    public const METHOD_NAME = ['MajorityTest_three_round'];

    // Mod
    protected const MAX_ROUND = 3;
    protected const TARGET_NUMBER_OF_CANDIDATES_FOR_THE_NEXT_ROUND = 2;
}