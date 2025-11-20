<?php declare(strict_types=1);
/*
    Part of INSTANT-RUNOFF method Module - From the original Condorcet PHP

    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

namespace CondorcetPHP\Condorcet\Algo\Methods\Lotteries;

use CondorcetPHP\Condorcet\Algo\{Method, MethodInterface};
use CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats;
use CondorcetPHP\Condorcet\Tools\Randomizers\VoteRandomizer;
use CondorcetPHP\Condorcet\Utils\VoteUtil;
use Random\Randomizer;

/**
 * Implements the Random Candidates lottery voting method.
 *
 * @internal
 */
class RandomCandidates extends Method implements MethodInterface
{
    // Method Name
    public const array METHOD_NAME = ['Random Candidates', 'Random Candidate'];

    // Non deterministic method
    public const bool IS_DETERMINISTIC = false;

    // Options
    public static ?Randomizer $optionRandomizer = null;
    public static float|int $optionTiesProbability = 0;

    public function compute(): void
    {
        $election = $this->getElectionOrFail();

        $vr = new VoteRandomizer($election->getCandidatesList(), self::$optionRandomizer);
        $vr->tiesProbability = self::$optionTiesProbability;

        $ranking = $vr->getNewVote()->getRanking();

        VoteUtil::convertRankingFromCandidateObjectToInternalKeys($election, $ranking);

        $this->Result = $this->createResult($ranking);
    }

    protected function getStats(): BaseMethodStats
    {
        return new BaseMethodStats(['Ties Probability' => self::$optionTiesProbability]);
    }
}
