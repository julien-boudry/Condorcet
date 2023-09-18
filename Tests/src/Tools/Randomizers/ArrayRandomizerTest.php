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

        for ($i = 0; $i < 10; $i++) {
            $nv = $votesRandomizer->shuffle();

            expect($nv)->not()->toEqual(self::CANDIDATE_SET_1);
            expect($nv)->toHaveCount(\count(self::CANDIDATE_SET_1));
        }
    }

    public function testMaxCandidatesRanked(): void
    {
        $votesRandomizer = new ArrayRandomizer(self::CANDIDATE_SET_1, self::SEED);
        $votesRandomizer->maxCandidatesRanked = 3;

        $original = \array_slice(self::CANDIDATE_SET_1, 0, 3);

        for ($i = 0; $i < 10; $i++) {
            $nv = $votesRandomizer->shuffle();

            expect($nv)->not()->toEqual($original);
            expect($nv)->toHaveCount($votesRandomizer->maxCandidatesRanked);
        }
    }

    public function testMinCandidatesRanked(): void
    {
        $votesRandomizer = new ArrayRandomizer(self::CANDIDATE_SET_1, self::SEED);
        $votesRandomizer->minCandidatesRanked = 3;

        $variations = [];

        for ($i = 0; $i < 10; $i++) {
            $nv = $votesRandomizer->shuffle();

            expect($nv)->not()->toEqual(self::CANDIDATE_SET_1);

            $countNv = \count($nv);
            $variations[] = $countNv;

            expect($countNv)->toBeGreaterThanOrEqual($votesRandomizer->minCandidatesRanked);
        }

        $variationsCount = \count(array_unique($variations));
        expect($variationsCount)->toBe(\count(self::CANDIDATE_SET_1) - $votesRandomizer->minCandidatesRanked + 1);
    }

    public function testMinAndMaxCandidatesRanked(): void
    {
        $votesRandomizer = new ArrayRandomizer(self::CANDIDATE_SET_1, self::SEED);
        $votesRandomizer->minCandidatesRanked = 3;
        $votesRandomizer->maxCandidatesRanked = 6;

        $variations = [];

        for ($i = 0; $i < 10; $i++) {
            $nv = $votesRandomizer->shuffle();

            $countNv = \count($nv);
            $variations[] = $countNv;

            expect($countNv)->toBeGreaterThanOrEqual($votesRandomizer->minCandidatesRanked);
            expect($countNv)->toBeLessThanOrEqual($votesRandomizer->maxCandidatesRanked);
        }

        $variationsCount = \count(array_unique($variations));
        expect($variationsCount)->toBe($votesRandomizer->maxCandidatesRanked - $votesRandomizer->minCandidatesRanked + 1);
    }

    public function testAddedTies(): void
    {
        $votesRandomizer = new ArrayRandomizer(self::CANDIDATE_SET_2, self::SEED);

        $votesRandomizer->tiesProbability = 100;
        $nv = $votesRandomizer->shuffle();
        expect($nv)->toHaveCount(2);
        expect($nv[0])->toBeString();
        expect($nv[1])->toHaveCount(2);

        $votesRandomizer->tiesProbability = 90;
        $nv = $votesRandomizer->shuffle();
        expect($nv)->toHaveCount(2);
        expect($nv[0])->toBeString();
        expect($nv[1])->toHaveCount(2);

        $votesRandomizer->tiesProbability = 70;
        $nv = $votesRandomizer->shuffle();
        expect($nv)->toHaveCount(3);

        $votesRandomizer->tiesProbability = 500;
        $nv = $votesRandomizer->shuffle();
        expect($nv)->toHaveCount(1);
        expect($nv[0])->toHaveCount(3);
    }

    public function testAddedTiesWithArray1(): void
    {
        $votesRandomizer = new ArrayRandomizer(self::CANDIDATE_SET_3, self::SEED);

        $votesRandomizer->tiesProbability = 100;
        $nv = $votesRandomizer->shuffle();

        expect($nv)->toHaveCount(3);
        expect($nv[2])->toHaveCount(2);
    }

    public function testAddedTiesWithArray2(): void
    {
        $votesRandomizer = new ArrayRandomizer(self::CANDIDATE_SET_1, self::SEED);

        $votesRandomizer->tiesProbability = 500;
        $nv = $votesRandomizer->shuffle();

        expect($nv)->toHaveCount(4);
        expect($nv[2])->toHaveCount(6);
    }

    public function testSeeds(): void
    {
        // Test low seed
        $votesRandomizer = new ArrayRandomizer(self::CANDIDATE_SET_1, '42');
        expect($votesRandomizer->shuffle()[0])->toBe(self::CANDIDATE_SET_1[5]);

        // Test 32 bytes seed
        $s = 'abcdefghijklmnopqrstuvwxyz123456';
        expect(\strlen($s))->toBe(32);
        $votesRandomizer = new ArrayRandomizer(self::CANDIDATE_SET_1, $s);
        expect($votesRandomizer->shuffle()[0])->toBe(self::CANDIDATE_SET_1[6]);

        // Test custom Randomizer
        $r = new \Random\Randomizer(new \Random\Engine\PcgOneseq128XslRr64('abcdefghijklmnop'));
        $votesRandomizer = new ArrayRandomizer(self::CANDIDATE_SET_1, $r);
        expect($votesRandomizer->shuffle()[0])->toBe(self::CANDIDATE_SET_1[4]);

        // Test secure engine
        $votesRandomizer = new ArrayRandomizer([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]);

        for ($i = 0; $i < 3; $i++) {
            expect($votesRandomizer->shuffle())->not()->toBe(self::CANDIDATE_SET_1);
        }
    }
}
