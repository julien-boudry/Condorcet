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
 * Vote constraint that rejects votes which do not rank every candidate of the
 * election (partial rankings).
 *
 * The check is performed on the raw ranking actually expressed by the voter,
 * not on the contextual one: with implicit ranking enabled every vote would
 * otherwise look complete, so this constraint deliberately ignores the
 * implicit completion and only accepts a ballot that explicitly ranks all
 * candidates. Ties are allowed; only completeness is enforced.
 */
class CompleteRanking implements VoteConstraintInterface
{
    public static function isVoteAllowed(Election $election, Vote $vote): bool
    {
        return \count($vote->getAllCandidates()) === $election->countCandidates();
    }
}
