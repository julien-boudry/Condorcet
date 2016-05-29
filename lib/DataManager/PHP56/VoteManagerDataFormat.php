<?php
/*
    Condorcet PHP Class, with Schulze Methods and others !

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
//declare(strict_types=1);

namespace Condorcet\DataManager\PHP56;

use Condorcet\Vote;

class VoteManagerDataFormat
{
    public $election;

    public function dataCallBack ($data)
    {
        $vote = new Vote ($data);
        $this->election->checkVoteCandidate($vote);
        $vote->registerLink($this->election);

        return $vote;
    }
}
