<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\DataManager;

use CondorcetPHP\Condorcet\{Election, Vote};
use CondorcetPHP\Condorcet\Throwable\{VoteException, VoteManagerException, VoteNotLinkedException};
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
        $this->expectException(VoteException::class);
        $this->expectExceptionMessage('This vote is already linked to the election');

        $vote = new Vote([]);

        // add valid vote
        $this->votes_manager[] = $vote;
        self::assertSame($vote, $this->votes_manager->getVotesList()[0]);
        self::assertSame($this->election, $vote->getLinks()[0]);

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
            self::assertCount(0, $this->votes_manager);
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
            self::assertCount(0, $this->votes_manager);
            throw $e;
        }
    }

    public function testOffsetUnset(): void
    {
        $before_list = $this->votes_manager->getVotesList();

        // unset non existent vote
        unset($this->votes_manager[0]);
        self::assertSame($before_list, $this->votes_manager->getVotesList());

        // unset existing vote
        $vote = new Vote([]);
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

        foreach ($this->election->getVotesListGenerator() as $key => $value) {
            $votesListGenerator[$key] = $value;
        }

        self::assertEquals($this->election->getVotesList(), $votesListGenerator);
        self::assertCount(52, $votesListGenerator);


        $votesListGenerator = [];

        foreach ($this->election->getVotesListGenerator('tag42') as $key => $value) {
            $votesListGenerator[$key] = $value;
        }

        self::assertEquals($this->election->getVotesList('tag42'), $votesListGenerator);
        self::assertCount(42, $votesListGenerator);
    }

    public function testCountVotes(): void
    {
        self::assertEmpty($this->votes_manager->getVotesList());

        $this->election->parseVotes('
            A>B>C * 10;
            tag42 || C>B>A * 42
            tag44 || B>C>A * 26
            tag42, tag44 || A>C>B * 18
        ');

        self::assertEquals(60, $this->votes_manager->countVotes(['tag42'], 1));
        self::assertEquals(44, $this->votes_manager->countVotes(['tag44'], 1));
        self::assertEquals(18, $this->votes_manager->countVotes(['tag42', 'tag44'], 2));
        self::assertEquals(52, $this->votes_manager->countVotes(['tag44'], 0));
    }
}
