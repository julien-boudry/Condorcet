<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\DataManager;

use CondorcetPHP\Condorcet\{Election, Vote};
use CondorcetPHP\Condorcet\Throwable\{TagsFilterException, VoteException, VoteManagerException, VoteNotLinkedException};
use CondorcetPHP\Condorcet\DataManager\VotesManager;

use PHPUnit\Framework\TestCase;

class VotesManagerTest extends TestCase
{
    private readonly Election $election;
    private readonly VotesManager $votes_manager;

    protected function setUp(): void
    {
        $this->election = new Election;
        $this->election->parseCandidates('A;B;C');

        $this->votes_manager = $this->election->getVotesManager();
    }

    public function testOffsetSet(): never
    {
        $vote = new Vote([]);

        // add valid vote
        $this->votes_manager[] = $vote;
        $this->assertSame($vote, $this->votes_manager->getVotesList()[0]);
        $this->assertSame($this->election, $vote->getLinks()[0]);

        $this->expectException(VoteException::class);
        $this->expectExceptionMessage('This vote is already linked to the election');

        // add invalid vote
        $this->votes_manager[] = $vote;
    }

    public function testOffsetSetArgumentType1(): never
    {
        $this->expectException(VoteManagerException::class);

        // add invalid vote
        try {
            $this->votes_manager[] = new \stdClass;
        } catch (VoteManagerException $e) {
            $this->assertCount(0, $this->votes_manager);
            throw $e;
        }
    }

    public function testOffsetSetArgumentType2(): never
    {
        $this->expectException(VoteManagerException::class);

        // add invalid vote
        try {
            $this->votes_manager[] = null;
        } catch (VoteManagerException $e) {
            $this->assertCount(0, $this->votes_manager);
            throw $e;
        }
    }

    public function testOffsetUnset(): void
    {
        $before_list = $this->votes_manager->getVotesList();

        // unset non existent vote
        unset($this->votes_manager[0]);
        $this->assertSame($before_list, $this->votes_manager->getVotesList());

        // unset existing vote
        $vote = new Vote([]);
        $this->votes_manager[] = $vote;
        unset($this->votes_manager[0]);
        $this->assertEmpty($this->votes_manager->getVotesList());
    }

    public function testGetVoteKey(): void
    {
        $this->assertNull($this->votes_manager->getVoteKey(new Vote([])));
    }

    public function testGetVotesList(): void
    {
        // With Election
        $this->assertEmpty($this->votes_manager->getVotesList());

        $this->election->addCandidate('candidate');
        $this->election->addVote(new Vote(['candidate']));

        $this->assertNotEmpty($this->votes_manager->getVotesList());
    }

    public function testGetVotesListGenerator(): void
    {
        $this->election->parseVotes('A>B>C * 10;tag42 || C>B>A * 42');

        $votesListGenerator = [];

        foreach ($this->election->getVotesListGenerator() as $key => $value) {
            $votesListGenerator[$key] = $value;
        }

        $this->assertEquals($this->election->getVotesList(), $votesListGenerator);
        $this->assertCount(52, $votesListGenerator);


        $votesListGenerator = [];

        foreach ($this->election->getVotesListGenerator('tag42') as $key => $value) {
            $votesListGenerator[$key] = $value;
        }

        $this->assertEquals($this->election->getVotesList('tag42'), $votesListGenerator);
        $this->assertCount(42, $votesListGenerator);
    }

    public function testCountVotes(): never
    {
        $this->assertEmpty($this->votes_manager->getVotesList());

        $this->election->parseVotes('
            A>B>C * 10;
            tag42 || C>B>A * 42
            tag44 || B>C>A * 26
            tag42, tag44 || A>C>B * 18
        ');

        $this->assertEquals(60, $this->votes_manager->countVotes(['tag42'], 1));
        $this->assertEquals(44, $this->votes_manager->countVotes(['tag44'], 1));
        $this->assertEquals(44, $this->votes_manager->countVotes(['tag44'], true));
        $this->assertEquals(18, $this->votes_manager->countVotes(['tag42', 'tag44'], 2));
        $this->assertEquals(52, $this->votes_manager->countVotes(['tag44'], 0));
        $this->assertEquals(52, $this->votes_manager->countVotes(['tag44'], false));

        $with = -1;

        $this->expectException(TagsFilterException::class);
        $this->expectExceptionMessage('Value of $with cannot be less than 0. Actual value is '.$with);

        $this->election->countVotes('tag44', $with);
    }
}
