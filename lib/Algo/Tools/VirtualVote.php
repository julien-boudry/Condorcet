<?php
/*
    Condorcet PHP Class, with Schulze Methods and others !

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
namespace Condorcet\Algo\Tools;

use Condorcet\Vote;

class VirtualVote
{
    public static function removeCandidates (Vote $vote, array $candidatesList)
    {
        ($virtualVote = clone $vote)->removeCandidates($candidatesList);

        return $virtualVote;
    }
}
