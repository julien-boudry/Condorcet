<?php declare(strict_types=1);
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

namespace CondorcetPHP\Condorcet\Algo\Tools;

use CondorcetPHP\Condorcet\Vote;

abstract class VirtualVote
{
    public static function removeCandidates(Vote $vote, array $candidatesList): Vote
    {
        $virtualVote = clone $vote;

        foreach ($candidatesList as $oneCandidate) {
            $virtualVote->removeCandidate($oneCandidate);
        }

        return $virtualVote;
    }
}
