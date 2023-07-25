<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\Algo\Tools;

use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Vote;
use CondorcetPHP\Condorcet\Algo\Tools\VirtualVote;
use PHPUnit\Framework\TestCase;

class VirtualVoteTest extends TestCase
{
    public function testVirtualVote(): void
    {
        $election = new Election;
        $election->parseCandidates('A;B;C');

        $vote1 = new Vote('A>B>C');
        $election->addVote($vote1);

        $vote2 = VirtualVote::removeCandidates($vote1, ['B']);

        expect($vote2->getSimpleRanking())->not()->toBe($vote1->getSimpleRanking());
        expect($vote2->getSimpleRanking())->toBe('A > C');

        expect($vote1->countLinks())->toBe(1);
        expect($vote2->countLinks())->toBe(0);
        expect($election->countVotes())->toBe(1);
    }
}
