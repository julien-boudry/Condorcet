<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\Algo\Methods\RankedPairs;

use CondorcetPHP\Condorcet\Election;
use PHPUnit\Framework\TestCase;

class FilteredPairwiseTest extends TestCase
{
    private ?Election $election1;

    protected function setUp(): void
    {
        $this->election1 = new Election;

        $this->election1->addCandidate('A');
        $this->election1->addCandidate('B');
        $this->election1->addCandidate('C');

        $this->election1->addVote('A>B>C');
        $this->election1->parseVotes('tag1 || C>B>A');

    }

    public function testFilteredPairwiseResults_1(): void
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

    public function testSerializeFilteredPairwise(): void
    {
        $filteredPairwise = $this->election1->getFilteredPairwiseByTags('tag1');

        self::assertSame(1, $filteredPairwise[2]['win'][0]);

        $explicitOriginalFromFilteredPairwise = $filteredPairwise->getExplicitPairwise();

        $serializedFilteredPairwise = serialize($filteredPairwise);
        unset($filteredPairwise);

        $filteredPairwise = unserialize($serializedFilteredPairwise);

        self::assertSame($explicitOriginalFromFilteredPairwise, $filteredPairwise->getExplicitPairwise());
        self::assertSame(['tag1'], $filteredPairwise->tags);
        self::assertTrue($filteredPairwise->withTags);
    }

    public function testModifyFilteredPairwise(): void
    {
        $this->election1->parseVotes('tag1 || C>B>A');

        $filteredPairwise = $this->election1->getFilteredPairwiseByTags('tag1');

        self::assertSame(2, $filteredPairwise[2]['win'][0]);

        $filteredPairwise->removeVote(1);
        self::assertSame(1, $filteredPairwise[2]['win'][0]);

        $newVote = $this->election1->addVote('A>B>C');
        $newVote->addTags('tag1');

        self::assertSame(0, $filteredPairwise[0]['win'][2]);
        $filteredPairwise->addNewVote($this->election1->getVoteKey($newVote));
        self::assertSame(1, $filteredPairwise[0]['win'][2]);

        $this->election1 = null; // destroy reference and weak reference

        // Explicit pairwise must continue to find original candidates names
        self::assertArrayHasKey('A', $filteredPairwise->getExplicitPairwise());
        self::assertArrayHasKey('B', $filteredPairwise->getExplicitPairwise());
        self::assertArrayHasKey('C', $filteredPairwise->getExplicitPairwise());
    }

    public function testFilteredPairwiseResults_2(): void
    {
        $this->election1->removeAllVotes();
        $this->election1->allowsVoteWeight(true);

        $this->election1->parseVotes('
            A > B > C
            tag1 || B > C > A
            tag2 || A > B > C
            tag1, tag2 || C > B > A *2
        ');


        $filteredWithBothTags = $this->election1->getExplicitFilteredPairwiseByTags(['tag1', 'tag2'], 2);


        // Test $filteredwithBothTags
        self::assertSame(
            expected: [
                'A' => [
                    'win' => [
                        'B' => 0,
                        'C' => 0,
                    ],
                    'null' => [
                        'B' => 0,
                        'C' => 0,
                    ],
                    'lose' => [
                        'B' => 2,
                        'C' => 2,
                    ],
                ],
                'B' => [
                    'win' => [
                        'A' => 2,
                        'C' => 0,
                    ],
                    'null' => [
                        'A' => 0,
                        'C' => 0,
                    ],
                    'lose' => [
                        'A' => 0,
                        'C' => 2,
                    ],
                ],
                'C' => [
                    'win' => [
                        'A' => 2,
                        'B' => 2,
                    ],
                    'null' => [
                        'A' => 0,
                        'B' => 0,
                    ],
                    'lose' => [
                        'A' => 0,
                        'B' => 0,
                    ],
                ]],
            actual: $filteredWithBothTags
        );
    }
}
