<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\Algo\Methods\RankedPairs;

use CondorcetPHP\Condorcet\Election;
use PHPUnit\Framework\TestCase;

class PairwiseTest extends TestCase
{
    private readonly Election $election1;

    protected function setUp(): void
    {
        $this->election1 = new Election;

        $this->election1->addCandidate('A');
        $this->election1->addCandidate('B');
        $this->election1->addCandidate('C');

        $this->election1->addVote('A>B>C');
    }

    public function testPairwiseOffsetGet(): void
    {
        $pairwise = $this->election1->getPairwise();

        self::assertIsArray($pairwise[1]);

        self::assertNull($pairwise[42]);
    }

    public function testExplicitPairwise(): void
    {
        self::assertSame(
            [
                'A' => [
                    'win' => [
                        'B' => 1,
                        'C' => 1,
                    ],
                    'null' => [
                        'B' => 0,
                        'C' => 0,
                    ],
                    'lose' => [
                        'B' => 0,
                        'C' => 0,
                    ],
                ],
                'B' => [
                    'win' => [
                        'A' => 0,
                        'C' => 1,
                    ],
                    'null' => [
                        'A' => 0,
                        'C' => 0,
                    ],
                    'lose' => [
                        'A' => 1,
                        'C' => 0,
                    ],
                ],
                'C' => [
                    'win' => [
                        'A' => 0,
                        'B' => 0,
                    ],
                    'null' => [
                        'A' => 0,
                        'B' => 0,
                    ],
                    'lose' => [
                        'A' => 1,
                        'B' => 1,
                    ],
                ],
            ],
            $this->election1->getPairwise()->getExplicitPairwise()
        );
    }

    public function testVotesWeight(): void
    {
        $electionOff = new Election;

        $electionOff->addCandidate('A');
        $electionOff->addCandidate('B');
        $electionOff->addCandidate('C');
        $electionOff->addCandidate('D');

        $electionOff->addVote('A>B>C=D ^3');
        $electionOff->addVote('A>B>C=D ^4');

        $electionOn = clone $electionOff;
        $electionOn->allowsVoteWeight(true);

        self::assertNotSame($electionOff->getExplicitPairwise(), $electionOn->getExplicitPairwise());

        self::assertSame(
            [
                'A' => [
                    'win' => [
                        'B' => 7,
                        'C' => 7,
                        'D' => 7,
                    ],
                    'null' => [
                        'B' => 0,
                        'C' => 0,
                        'D' => 0,
                    ],
                    'lose' => [
                        'B' => 0,
                        'C' => 0,
                        'D' => 0,
                    ],
                ],
                'B' => [
                    'win' => [
                        'A' => 0,
                        'C' => 7,
                        'D' => 7,
                    ],
                    'null' => [
                        'A' => 0,
                        'C' => 0,
                        'D' => 0,
                    ],
                    'lose' => [
                        'A' => 7,
                        'C' => 0,
                        'D' => 0,
                    ],
                ],
                'C' => [
                    'win' => [
                        'A' => 0,
                        'B' => 0,
                        'D' => 0,
                    ],
                    'null' => [
                        'A' => 0,
                        'B' => 0,
                        'D' => 7,
                    ],
                    'lose' => [
                        'A' => 7,
                        'B' => 7,
                        'D' => 0,
                    ],
                ],
                'D' => [
                    'win' => [
                        'A' => 0,
                        'B' => 0,
                        'C' => 0,
                    ],
                    'null' => [
                        'A' => 0,
                        'B' => 0,
                        'C' => 7,
                    ],
                    'lose' => [
                        'A' => 7,
                        'B' => 7,
                        'C' => 0,
                    ],
                ],
            ],
            $electionOn->getPairwise()->getExplicitPairwise()
        );
    }

    public function testRemoveVote_1(): void
    {
        $this->election1->allowsVoteWeight(true);
        $this->election1->removeAllVotes(); // removeAllVotes process a loop on each vote

        $this->election1->parseVotes('A>B>C ^2');

        self::assertSame(
            expected: [
                'A' => [
                    'win' => [
                        'B' => 2,
                        'C' => 2,
                    ],
                    'null' => [
                        'B' => 0,
                        'C' => 0,
                    ],
                    'lose' => [
                        'B' => 0,
                        'C' => 0,
                    ],
                ],
                'B' => [
                    'win' => [
                        'A' => 0,
                        'C' => 2,
                    ],
                    'null' => [
                        'A' => 0,
                        'C' => 0,
                    ],
                    'lose' => [
                        'A' => 2,
                        'C' => 0,
                    ],
                ],
                'C' => [
                    'win' => [
                        'A' => 0,
                        'B' => 0,
                    ],
                    'null' => [
                        'A' => 0,
                        'B' => 0,
                    ],
                    'lose' => [
                        'A' => 2,
                        'B' => 2,
                    ],
                ],
            ],
            actual: $this->election1->getExplicitPairwise()
        );
    }

    public function testRemoveVote_BugWithWeight(): void
    {
        $this->election1->removeAllVotes(); // removeAllVotes process a loop on each vote
        $this->election1->allowsVoteWeight(true); // Bug was occured when they were not any votes left, then setting pairwise to null without rebuild a new one.

        $this->election1->parseVotes('A>B>C ^2');

        self::assertSame(
            expected: [
                'A' => [
                    'win' => [
                        'B' => 2,
                        'C' => 2,
                    ],
                    'null' => [
                        'B' => 0,
                        'C' => 0,
                    ],
                    'lose' => [
                        'B' => 0,
                        'C' => 0,
                    ],
                ],
                'B' => [
                    'win' => [
                        'A' => 0,
                        'C' => 2,
                    ],
                    'null' => [
                        'A' => 0,
                        'C' => 0,
                    ],
                    'lose' => [
                        'A' => 2,
                        'C' => 0,
                    ],
                ],
                'C' => [
                    'win' => [
                        'A' => 0,
                        'B' => 0,
                    ],
                    'null' => [
                        'A' => 0,
                        'B' => 0,
                    ],
                    'lose' => [
                        'A' => 2,
                        'B' => 2,
                    ],
                ],
            ],
            actual: $this->election1->getExplicitPairwise()
        );
    }

    public function testFilteredPairwise(): void
    {
        $this->election1->removeAllVotes();
        $this->election1->allowsVoteWeight(true);

        $this->election1->parseVotes('
            A > B > C
            tag1 || C > B > A
            tag2 || A > B > C ^2
        ');


        $filteredwithoutTag1 = $this->election1->getExplicitFilteredPairwiseByTags('tag1', false);
        $filteredWithTag2 = $this->election1->getExplicitFilteredPairwiseByTags('tag2');
        $filteredWithTag2AndTag1 = $this->election1->getExplicitFilteredPairwiseByTags('tag2,tag1');
        $normalPairwise = $this->election1->getExplicitPairwise();

        // Test $filteredwithoutTag1
        self::assertSame(
            expected: [
                'A' => [
                    'win' => [
                        'B' => 3,
                        'C' => 3,
                    ],
                    'null' => [
                        'B' => 0,
                        'C' => 0,
                    ],
                    'lose' => [
                        'B' => 0,
                        'C' => 0,
                    ],
                ],
                'B' => [
                    'win' => [
                        'A' => 0,
                        'C' => 3,
                    ],
                    'null' => [
                        'A' => 0,
                        'C' => 0,
                    ],
                    'lose' => [
                        'A' => 3,
                        'C' => 0,
                    ],
                ],
                'C' => [
                    'win' => [
                        'A' => 0,
                        'B' => 0,
                    ],
                    'null' => [
                        'A' => 0,
                        'B' => 0,
                    ],
                    'lose' => [
                        'A' => 3,
                        'B' => 3,
                    ],
                ]],
            actual: $filteredwithoutTag1
        );

        // Test $filteredwithTag2
        self::assertSame(
            expected: [
                'A' => [
                    'win' => [
                        'B' => 2,
                        'C' => 2,
                    ],
                    'null' => [
                        'B' => 0,
                        'C' => 0,
                    ],
                    'lose' => [
                        'B' => 0,
                        'C' => 0,
                    ],
                ],
                'B' => [
                    'win' => [
                        'A' => 0,
                        'C' => 2,
                    ],
                    'null' => [
                        'A' => 0,
                        'C' => 0,
                    ],
                    'lose' => [
                        'A' => 2,
                        'C' => 0,
                    ],
                ],
                'C' => [
                    'win' => [
                        'A' => 0,
                        'B' => 0,
                    ],
                    'null' => [
                        'A' => 0,
                        'B' => 0,
                    ],
                    'lose' => [
                        'A' => 2,
                        'B' => 2,
                    ],
                ]],
            actual: $filteredWithTag2
        );


        // Test $filteredwithTag2AndTag1
        self::assertSame(
            expected: [
                'A' => [
                    'win' => [
                        'B' => 2,
                        'C' => 2,
                    ],
                    'null' => [
                        'B' => 0,
                        'C' => 0,
                    ],
                    'lose' => [
                        'B' => 1,
                        'C' => 1,
                    ],
                ],
                'B' => [
                    'win' => [
                        'A' => 1,
                        'C' => 2,
                    ],
                    'null' => [
                        'A' => 0,
                        'C' => 0,
                    ],
                    'lose' => [
                        'A' => 2,
                        'C' => 1,
                    ],
                ],
                'C' => [
                    'win' => [
                        'A' => 1,
                        'B' => 1,
                    ],
                    'null' => [
                        'A' => 0,
                        'B' => 0,
                    ],
                    'lose' => [
                        'A' => 2,
                        'B' => 2,
                    ],
                ]],
            actual: $filteredWithTag2AndTag1
        );

        // Test NormalPairwise
        self::assertSame(
            expected: [
                'A' => [
                    'win' => [
                        'B' => 3,
                        'C' => 3,
                    ],
                    'null' => [
                        'B' => 0,
                        'C' => 0,
                    ],
                    'lose' => [
                        'B' => 1,
                        'C' => 1,
                    ],
                ],
                'B' => [
                    'win' => [
                        'A' => 1,
                        'C' => 3,
                    ],
                    'null' => [
                        'A' => 0,
                        'C' => 0,
                    ],
                    'lose' => [
                        'A' => 3,
                        'C' => 1,
                    ],
                ],
                'C' => [
                    'win' => [
                        'A' => 1,
                        'B' => 1,
                    ],
                    'null' => [
                        'A' => 0,
                        'B' => 0,
                    ],
                    'lose' => [
                        'A' => 3,
                        'B' => 3,
                    ],
                ]],
            actual: $normalPairwise
        );
    }
}
