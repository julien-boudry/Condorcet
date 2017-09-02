<?php
declare(strict_types=1);
namespace Condorcet;

use Condorcet\CondorcetException;
use Condorcet\Election;
use Condorcet\Vote;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Condorcet\Candidate
 */
class CandidateTest extends TestCase
{
    /**
     * @var candidate1
     */
    private $candidate1;

    public function setUp()
    {
        $this->candidate1 = new Candidate ('candidate1.n1');
    }

    public function testCreateTimestamp ()
    {
        $this->assertEquals($this->candidate1->getCreateTimestamp(), $this->candidate1->getTimestamp());
    }

    public function testChangeName ()
    {
        $this->assertTrue($this->candidate1->setName('candidate1.n2'));

        $this->assertEquals('candidate1.n2',$this->candidate1->getName());

        $this->assertLessThan($this->candidate1->getTimestamp(), $this->candidate1->getCreateTimestamp());
        $this->assertCount(2,$this->candidate1->getHistory());
    }

    /**
      * @expectedException Condorcet\CondorcetException
      * @expectedExceptionCode 19
      */
    public function testSameCandidateToMultipleElection ()
    {
        $election1 = new Election ();
        $election2 = new Election ();
        $election3 = new Election ();

        // Add candidate to election
        $this->assertEquals($this->candidate1,$election1->addCandidate($this->candidate1));
        $this->assertEquals($this->candidate1,$election2->addCandidate($this->candidate1));
        $this->assertEquals($this->candidate1,$election3->addCandidate($this->candidate1));

        // Check Candidate Link
        $this->assertEquals($election1,$this->candidate1->getLinks()[0]);
        $this->assertEquals($election2,$this->candidate1->getLinks()[1]);
        $this->assertEquals($election3,$this->candidate1->getLinks()[2]);
        $this->assertCount(3,$this->candidate1->getLinks());

        $election3->removeCandidate('candidate1.n1');

        $this->assertCount(2,$this->candidate1->getLinks());

        // Add some conflicts
        $this->assertTrue($this->candidate1->setName('candidate1.n2'));
        $this->assertEquals('candidate1.n2',$this->candidate1->getName());
        $this->assertNotEquals($this->candidate1, $election1->addCandidate('candidate1.n1'));

        $election2->addCandidate('Debussy');
        $this->candidate1->setName('Debussy'); // Throw an Exception. Code 20.
    }
}