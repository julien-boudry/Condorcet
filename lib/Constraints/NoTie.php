<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Constraints;

use CondorcetPHP\Condorcet\CondorcetDocAttributes\{Description, Example, FunctionReturn, PublicAPI, Related};
use CondorcetPHP\Condorcet\VoteConstraint;

class NoTie extends VoteConstraint
{
    protected static function evaluateVote (array $vote) : bool
    {
        foreach ($vote as $oneRank) :
            if (\count($oneRank) > 1) :
                return false;
            endif;
        endforeach;

        return true;
    }
}