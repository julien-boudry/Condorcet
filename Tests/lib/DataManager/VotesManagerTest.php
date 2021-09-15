<?php
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\DataManager;

use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Throwable\VoteNotLinkedException;
use CondorcetPHP\Condorcet\Vote;
use CondorcetPHP\Condorcet\DataManager\VotesManager;

use PHPUnit\Framework\TestCase;


class VotesManagerTest extends TestCase
{
    private readonly Election $election;
    private readonly VotesManager $votes_manager;

    protected function setUp(): void
    {
        $this->election = new Election();
        $this->election->parseCandidates('A;B;C');

        $this->votes_manager = $this->election->getVotesManager();
    }

    public function testOffsetSet(): never
    {
        $this->expectException(VoteNotLinkedException::class);
        $this->expectExceptionMessage("The vote is not linked to an election");

        $vote = new Vote([]);

        // add valid vote
        $this->votes_manager[] = $vote;
        self::assertSame($vote, $this->votes_manager->getVotesList()[0]);

        // add invalid vote
        $this->votes_manager[] = null;
    }

    public function testOffsetUnset(): void
    {
        $before_list = $this->votes_manager->getVotesList();

        // unset non existent vote
        unset($this->votes_manager[0]);
        self::assertSame($before_list, $this->votes_manager->getVotesList());

        // unset existing vote
        $vote = new Vote ([]);
        $vote->registerLink($this->election);
        $this->votes_manager[] = $vote;
        unset($this->votes_manager[0]);
        self::assertEmpty($this->votes_manager->getVotesList());
    }

    public function testGetVoteKey(): void
    {
        self::assertNull($this->votes_manager->getVoteKey(new Vote([])));
    }

    public function testGetVotesList(): void
    {
        // With Election
        self::assertEmpty($this->votes_manager->getVotesList());

        $this->election->addCandidate('candidate');
        $this->election->addVote(new Vote(['candidate']));

        self::assertNotEmpty($this->votes_manager->getVotesList());
    }

    public function testGetVotesListGenerator(): void
    {
        $this->election->parseVotes('A>B>C * 10;tag42 || C>B>A * 42');

        $votesListGenerator = [];

        foreach ($this->election->getVotesListGenerator() as $key => $value) :
            $votesListGenerator[$key] = $value;
        endforeach;

        self::assertEquals($this->election->getVotesList(),$votesListGenerator);
        self::assertSame(52,\count($votesListGenerator));


        $votesListGenerator = [];

        foreach ($this->election->getVotesListGenerator('tag42') as $key => $value) :
            $votesListGenerator[$key] = $value;
        endforeach;

        self::assertEquals($this->election->getVotesList('tag42'),$votesListGenerator);
        self::assertSame(42,\count($votesListGenerator));
    }
}
