<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);
namespace Condorcet\DataManager;

use Condorcet\CondorcetException;
use Condorcet\Election;
use Condorcet\Vote;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Condorcet\DataManager\VotesManager
 * @covers \Condorcet\DataManager\ArrayManager
 */
class VotesManagerTest extends TestCase
{
    private $election;

    /**
     * @var VotesManager
     */
    private $empty_votes_manager;

    /**
     * @var VotesManager
     */
    private $votes_manager;

    protected function setUp()
    {
        $this->election = new Election();

        $this->votes_manager = new VotesManager($this->election);
        $this->empty_votes_manager = new VotesManager();
    }

    public function testGetDataContextObject()
    {
        // Testing the class type is not possible, since it is an
        // anonymous class not implementing any interface.
        self::assertNull($this->empty_votes_manager->getDataContextObject()->election);
        self::assertSame($this->election, $this->votes_manager->getDataContextObject()->election);
    }

    public function testOffsetSet()
    {
        $vote = new Vote([]);

        // add valid vote
        $this->empty_votes_manager[] = $vote;
        self::assertSame($vote, $this->empty_votes_manager->getVotesList()[0]);

        // add invalid vote
        self::expectException(CondorcetException::class);
        $this->empty_votes_manager[] = null;
    }

    public function testOffsetUnset()
    {
        $before_list = $this->empty_votes_manager->getVotesList();

        // unset non existent vote
        unset($this->empty_votes_manager[0]);
        self::assertSame($before_list, $this->empty_votes_manager->getVotesList());

        // unset existing vote
        $this->empty_votes_manager[] = new Vote([]);
        unset($this->empty_votes_manager[0]);
        self::assertEmpty($this->empty_votes_manager->getVotesList());
    }

    public function testGetVoteKey()
    {
        self::assertFalse($this->empty_votes_manager->getVoteKey(new Vote([])));
    }

    public function testGetVotesList()
    {
        // Without Election
        self::assertEmpty($this->empty_votes_manager->getVotesList());

        $this->empty_votes_manager[] = new Vote([]);
        self::assertNotEmpty($this->empty_votes_manager->getVotesList());

        // With Election
        self::assertEmpty($this->votes_manager->getVotesList());

        $this->election->addCandidate('candidate');
        $this->election->addVote(new Vote(['candidate']));

        $this->votes_manager[] = new Vote([]);
        self::assertNotEmpty($this->votes_manager->getVotesList());
    }
}
