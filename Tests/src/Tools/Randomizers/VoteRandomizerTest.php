<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests;

use CondorcetPHP\Condorcet\Tools\Randomizers\{ArrayRandomizer, VoteRandomizer};
use CondorcetPHP\Condorcet\Utils\CondorcetUtil;
use PHPUnit\Framework\TestCase;

class VoteRandomizerTest extends TestCase
{
    public function testEquivalence(): void
    {
        $arrayRandomizer = new ArrayRandomizer(ArrayRandomizerTest::CANDIDATE_SET_1, ArrayRandomizerTest::SEED);
        $votesRandomizer = new VoteRandomizer(ArrayRandomizerTest::CANDIDATE_SET_1, ArrayRandomizerTest::SEED);

        for ($i = 0; $i < 5; $i++) {
            expect(
                array_values(
                    CondorcetUtil::format(
                        $votesRandomizer->getNewVote()->getRanking(false)
                    )
                )
            )->toBe($arrayRandomizer->shuffle());
        }

        $arrayRandomizer->tiesProbability = 100;
        $votesRandomizer->tiesProbability = 100;

        for ($i = 0; $i < 5; $i++) {
            expect(
                array_values(
                    CondorcetUtil::format(
                        $votesRandomizer->getNewVote()->getRanking(false)
                    )
                )
            )->tobe($arrayRandomizer->shuffle());
        }
    }
}
