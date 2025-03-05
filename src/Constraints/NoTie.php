<?php

/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Constraints;

use CondorcetPHP\Condorcet\{Election, Vote, VoteConstraintInterface};

class NoTie implements VoteConstraintInterface
{
    public static function isVoteAllow(Election $election, Vote $vote): bool
    {
        $voteRanking = $vote->getContextualRankingWithoutSort($election);

        foreach ($voteRanking as $oneRank) {
            if (\count($oneRank) > 1) {
                return false;
            }
        }

        return true;
    }
}
