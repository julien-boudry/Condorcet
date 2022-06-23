<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\Algo\Methods\RankedPairs;

use CondorcetPHP\Condorcet\{Candidate, Condorcet, CondorcetUtil, Election, Result, Vote, VoteConstraint};
use PHPUnit\Framework\TestCase;

class PairwiseTest extends TestCase
{
    private readonly Election $election;

    public function setUp(): void
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
                'A' =>
                [
                  'win' =>
                  [
                    'B' => 1,
                    'C' => 1,
                  ],
                  'null' =>
                  [
                    'B' => 0,
                    'C' => 0,
                  ],
                  'lose' =>
                  [
                    'B' => 0,
                    'C' => 0,
                  ],
                ],
                'B' =>
                [
                  'win' =>
                  [
                    'A' => 0,
                    'C' => 1,
                  ],
                  'null' =>
                  [
                    'A' => 0,
                    'C' => 0,
                  ],
                  'lose' =>
                  [
                    'A' => 1,
                    'C' => 0,
                  ],
                ],
                'C' =>
                [
                  'win' =>
                  [
                    'A' => 0,
                    'B' => 0,
                  ],
                  'null' =>
                  [
                    'A' => 0,
                    'B' => 0,
                  ],
                  'lose' =>
                  [
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
                'A' =>
                [
                  'win' =>
                  [
                    'B' => 7,
                    'C' => 7,
                    'D' => 7,
                  ],
                  'null' =>
                  [
                    'B' => 0,
                    'C' => 0,
                    'D' => 0,
                  ],
                  'lose' =>
                  [
                    'B' => 0,
                    'C' => 0,
                    'D' => 0,
                  ],
                ],
                'B' =>
                [
                  'win' =>
                  [
                    'A' => 0,
                    'C' => 7,
                    'D' => 7,
                  ],
                  'null' =>
                  [
                    'A' => 0,
                    'C' => 0,
                    'D' => 0,
                  ],
                  'lose' =>
                  [
                    'A' => 7,
                    'C' => 0,
                    'D' => 0,
                  ],
                ],
                'C' =>
                [
                  'win' =>
                  [
                    'A' => 0,
                    'B' => 0,
                    'D' => 0,
                  ],
                  'null' =>
                  [
                    'A' => 0,
                    'B' => 0,
                    'D' => 7,
                  ],
                  'lose' =>
                  [
                    'A' => 7,
                    'B' => 7,
                    'D' => 0,
                  ],
                ],
                'D' =>
                [
                  'win' =>
                  [
                    'A' => 0,
                    'B' => 0,
                    'C' => 0,
                  ],
                  'null' =>
                  [
                    'A' => 0,
                    'B' => 0,
                    'C' => 7,
                  ],
                  'lose' =>
                  [
                    'A' => 7,
                    'B' => 7,
                    'C' => 0,
                  ],
                ],
              ],
            $electionOn->getPairwise()->getExplicitPairwise()
        );
    }
}
