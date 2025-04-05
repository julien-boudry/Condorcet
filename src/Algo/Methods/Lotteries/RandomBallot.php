<?php

/*
    Part of INSTANT-RUNOFF method Module - From the original Condorcet PHP

    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Algo\Methods\Lotteries;

use CondorcetPHP\Condorcet\Algo\{Method, MethodInterface};
use CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats;
use CondorcetPHP\Condorcet\Algo\Stats\StatsInterface;
use Random\Randomizer;

class RandomBallot extends Method implements MethodInterface
{
    // Method Name
    public const array METHOD_NAME = ['Random Ballot', 'single stochastic vote', 'lottery voting'];

    // Non deterministic method
    public const bool IS_DETERMINISTIC = false;

    // Options
    public static ?Randomizer $optionRandomizer = null;

    protected readonly int $totalElectionWeight;
    protected readonly int $electedWeightLevel;
    protected readonly int $electedBallotKey;

    public function compute(): void
    {
        $election = $this->getElection();

        $this->totalElectionWeight = $election->sumValidVotesWeightWithConstraints();

        $randomizer = self::$optionRandomizer ?? new Randomizer(new \Random\Engine\Secure);

        $this->electedWeightLevel = $randomizer->getInt(1, $this->totalElectionWeight);

        $currentWeightLevel = 0;

        foreach ($election->getVotesValidUnderConstraintGenerator() as $voteKey => $oneVote) {
            $currentWeightLevel += $oneVote->getWeight($election);

            if ($currentWeightLevel >= $this->electedWeightLevel) {
                $this->electedBallotKey = $voteKey;
                break;
            }
        }

        $electedVote = $election->getVotesManager()[$this->electedBallotKey];

        $this->Result = $this->createResult($electedVote->getContextualRankingWithCandidateKeys($election));
    }

    protected function getStats(): BaseMethodStats
    {
        return new BaseMethodStats([
            'Elected Weight Level' => $this->electedWeightLevel,
            'Elected Ballot Key' => $this->electedBallotKey,
        ]);
    }
}
