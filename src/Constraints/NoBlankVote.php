<?php declare(strict_types=1);
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

namespace CondorcetPHP\Condorcet\Constraints;

use CondorcetPHP\Condorcet\{Election, Vote, VoteConstraintInterface};

/**
 * Vote constraint that rejects blank votes (a vote ranking no candidate at all).
 *
 * The check is performed on the raw ranking actually expressed by the voter,
 * not on the contextual one: an empty ballot stays blank even when the
 * election uses implicit ranking (which would otherwise complete it).
 */
class NoBlankVote implements VoteConstraintInterface
{
    public static function isVoteAllowed(Election $election, Vote $vote): bool
    {
        return $vote->countRanks() > 0;
    }
}
