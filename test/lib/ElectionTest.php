<?php
declare(strict_types=1);
namespace Condorcet;

use Condorcet\CondorcetException;
use Condorcet\Candidate;
use Condorcet\Election;
use Condorcet\Vote;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Condorcet\Election
 */
class ElectionTest extends TestCase
{
    /**
     * @var election1
     */
    private $election1;

    public function setUp()
    {
        $this->election1 = new Election;

        $this->candidate1 = $this->election1->addCandidate('candidate1');
        $this->candidate2 = $this->election1->addCandidate('candidate2');
        $this->candidate3 = $this->election1->addCandidate('candidate3');

        $this->election1->addVote($this->vote1 = new Vote ([$this->candidate1,$this->candidate2,$this->candidate3]));
        $this->election1->addVote($this->vote2 = new Vote ([$this->candidate2,$this->candidate3,$this->candidate1]));
        $this->election1->addVote($this->vote3 = new Vote ([$this->candidate3,$this->candidate1,$this->candidate2]));
        $this->election1->addVote($this->vote4 = new Vote ([$this->candidate1,$this->candidate2,$this->candidate3]));

    }

    public function testRemoveVote ()
    {
        self::assertSame([$this->vote2],$this->election1->removeVote($this->vote2));

        self::assertCount(3,$this->election1->getVotesList());
    }

    public function testTagsFilter ()
    {
        $this->vote1->addtags('tag1,tag2,tag3');
        $this->vote2->addtags('tag3,tag4');
        $this->vote3->addtags('tag3,tag4,tag5');
        $this->vote4->addtags('tag1,tag4');

        self::assertSame($this->election1->getVotesList('tag1,tag2',true),[0=>$this->vote1,3=>$this->vote4]);
        self::assertSame($this->election1->countVotes('tag1,tag2',true),2);

        self::assertSame($this->election1->getVotesList('tag1,tag2',false),[1=>$this->vote2,2=>$this->vote3]);
        self::assertSame($this->election1->countVotes('tag1,tag2',false),2);

        $resultGlobal = $this->election1->getResult('Schulze');
        $resultFilter1 = $this->election1->getResult('Schulze',['tags' => 'tag1','withTag' => true]);
        $resultFilter2 = $this->election1->getResult('Schulze',['tags' => 'tag1','withTag' => false]);

        self::assertNotSame($resultGlobal,$resultFilter1);
        self::assertNotSame($resultGlobal,$resultFilter2);
        self::assertNotSame($resultFilter1,$resultFilter2);
    }

    /**
      * @expectedException Condorcet\CondorcetException
      * @expectedExceptionCode 12
      */
    public function testMaxParseIteration ()
    {
        self::assertSame(42,Election::setMaxParseIteration(42));

        self::assertCount(42,$this->election1->parseVotes('candidate1>candidate2 * 42'));

        self::assertCount(42,$this->election1->parseVotes('candidate1>candidate2 * 42'));

        self::assertSame(null,Election::setMaxParseIteration(null));

        self::assertCount(43,$this->election1->parseVotes('candidate1>candidate2 * 43'));

        self::assertSame(42,Election::setMaxParseIteration(42));

        $this->election1->parseVotes('candidate1>candidate2 * 43');
    }

    /**
      * @expectedException Condorcet\CondorcetException
      * @expectedExceptionCode 16
      */
    public function testMaxVoteNumber ()
    {
        self::assertSame(42,Election::setMaxVoteNumber(42));

        self::assertCount(17,$this->election1->parseVotes('candidate1>candidate2 * 17'));

        self::assertCount(21,$this->election1->parseVotes('candidate1>candidate2 * 42'));

        $this->election1->addVote('candidate3');
    }




}