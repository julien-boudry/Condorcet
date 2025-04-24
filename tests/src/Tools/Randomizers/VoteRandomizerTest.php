<?php declare(strict_types=1);


use CondorcetPHP\Condorcet\Tools\Randomizers\{ArrayRandomizer, VoteRandomizer};
use CondorcetPHP\Condorcet\Utils\CondorcetUtil;

test('equivalence', function (): void {
    $arrayRandomizer = new ArrayRandomizer(self::CANDIDATE_SET_1, self::SEED);
    $votesRandomizer = new VoteRandomizer(self::CANDIDATE_SET_1, self::SEED);

    for ($i = 0; $i < 5; $i++) {
        expect(
            array_values(
                CondorcetUtil::format(
                    $votesRandomizer->getNewVote()->getRanking(sortCandidatesInRank: false)
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
                    $votesRandomizer->getNewVote()->getRanking(sortCandidatesInRank: false)
                )
            )
        )->tobe($arrayRandomizer->shuffle());
    }
});
