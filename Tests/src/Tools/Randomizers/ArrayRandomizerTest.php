<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests;

use CondorcetPHP\Condorcet\Tools\Randomizers\ArrayRandomizer;
use PHPUnit\Framework\TestCase;

class ArrayRandomizerTest extends TestCase
{
    public const SEED = 'CondorcetSeed';

    public const CANDIDATE_SET_1 = [
        'Candidate1', 'Candidate2', 'Candidate3', 'Candidate4', 'Candidate5', 'Candidate6', 'Candidate7', 'Candidate8', 'Candidate9',
    ];

    public const CANDIDATE_SET_2 = [
        'Candidate1', 'Candidate2', 'Candidate3',
    ];

    public const CANDIDATE_SET_3 = [
        'Candidate1', 'Candidate2', 'Candidate3', ['Candidate4'],
    ];


    public function testDefaultRandomVotes(): void
    {
        $votesRandomizer = new ArrayRandomizer(self::CANDIDATE_SET_1, self::SEED);

        for ($i=0; $i<10; $i++) {
            $nv = $votesRandomizer->shuffle();

            self::assertNotEquals(self::CANDIDATE_SET_1, $nv);
            self::assertCount(\count(self::CANDIDATE_SET_1), $nv);
        }
    }

    public function testMaxCandidatesRanked(): void
    {
        $votesRandomizer = new ArrayRandomizer(self::CANDIDATE_SET_1, self::SEED);
        $votesRandomizer->maxCandidatesRanked = 3;

        $original = \array_slice(self::CANDIDATE_SET_1, 0, 3);

        for ($i=0; $i<10; $i++) {
            $nv = $votesRandomizer->shuffle();

            self::assertNotEquals($original, $nv);
            self::assertCount($votesRandomizer->maxCandidatesRanked, $nv);
        }
    }

    public function testMinCandidatesRanked(): void
    {
        $votesRandomizer = new ArrayRandomizer(self::CANDIDATE_SET_1, self::SEED);
        $votesRandomizer->minCandidatesRanked = 3;

        $variations = [];

        for ($i=0; $i<10; $i++) {
            $nv = $votesRandomizer->shuffle();

            self::assertNotEquals(self::CANDIDATE_SET_1, $nv);

            $countNv = \count($nv);
            $variations[] = $countNv;

            self::assertGreaterThanOrEqual($votesRandomizer->minCandidatesRanked, $countNv);
        }

        $variationsCount = \count(array_unique($variations));
        self::assertSame(\count(self::CANDIDATE_SET_1) - $votesRandomizer->minCandidatesRanked + 1, $variationsCount);
    }

    public function testMinAndMaxCandidatesRanked(): void
    {
        $votesRandomizer = new ArrayRandomizer(self::CANDIDATE_SET_1, self::SEED);
        $votesRandomizer->minCandidatesRanked = 3;
        $votesRandomizer->maxCandidatesRanked = 6;

        $variations = [];

        for ($i=0; $i<10; $i++) {
            $nv = $votesRandomizer->shuffle();

            $countNv = \count($nv);
            $variations[] = $countNv;

            self::assertGreaterThanOrEqual($votesRandomizer->minCandidatesRanked, $countNv);
            self::assertLessThanOrEqual($votesRandomizer->maxCandidatesRanked, $countNv);
        }

        $variationsCount = \count(array_unique($variations));
        self::assertSame($votesRandomizer->maxCandidatesRanked - $votesRandomizer->minCandidatesRanked + 1, $variationsCount);
    }

    public function testAddedTies(): void
    {
        $votesRandomizer = new ArrayRandomizer(self::CANDIDATE_SET_2, self::SEED);

        $votesRandomizer->tiesProbability = 100;
        $nv = $votesRandomizer->shuffle();
        self::assertCount(2, $nv);
        self::assertIsString($nv[0]);
        self::assertCount(2, $nv[1]);

        $votesRandomizer->tiesProbability = 90;
        $nv = $votesRandomizer->shuffle();
        self::assertCount(2, $nv);
        self::assertIsString($nv[0]);
        self::assertCount(2, $nv[1]);

        $votesRandomizer->tiesProbability = 70;
        $nv = $votesRandomizer->shuffle();
        self::assertCount(3, $nv);

        $votesRandomizer->tiesProbability = 500;
        $nv = $votesRandomizer->shuffle();
        self::assertCount(1, $nv);
        self::assertCount(3, $nv[0]);
    }

    public function testAddedTiesWithArray1(): void
    {
        $votesRandomizer = new ArrayRandomizer(self::CANDIDATE_SET_3, self::SEED);

        $votesRandomizer->tiesProbability = 100;
        $nv = $votesRandomizer->shuffle();

        self::assertCount(3, $nv);
        self::assertCount(2, $nv[2]);
    }

    public function testAddedTiesWithArray2(): void
    {
        $votesRandomizer = new ArrayRandomizer(self::CANDIDATE_SET_1, self::SEED);

        $votesRandomizer->tiesProbability = 500;
        $nv = $votesRandomizer->shuffle();

        self::assertCount(4, $nv);
        self::assertCount(6, $nv[2]);
    }

    public function testSeeds(): void
    {
        // Test low seed
        $votesRandomizer = new ArrayRandomizer(self::CANDIDATE_SET_1, '42');
        self::assertSame(self::CANDIDATE_SET_1[5], $votesRandomizer->shuffle()[0]);

        // Test 32 bytes seed
        $s = 'abcdefghijklmnopqrstuvwxyz123456';
        self::assertSame(32, \strlen($s));
        $votesRandomizer = new ArrayRandomizer(self::CANDIDATE_SET_1, $s);
        self::assertSame(self::CANDIDATE_SET_1[6], $votesRandomizer->shuffle()[0]);

        // Test custom Randomizer
        $r = new \Random\Randomizer(new \Random\Engine\PcgOneseq128XslRr64('abcdefghijklmnop'));
        $votesRandomizer = new ArrayRandomizer(self::CANDIDATE_SET_1, $r);
        self::assertSame(self::CANDIDATE_SET_1[4], $votesRandomizer->shuffle()[0]);

        // Test secure engine
        $votesRandomizer = new ArrayRandomizer([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]);

        for ($i=0; $i<3; $i++) {
            self::assertNotSame(self::CANDIDATE_SET_1, $votesRandomizer->shuffle());
        }
    }
}
