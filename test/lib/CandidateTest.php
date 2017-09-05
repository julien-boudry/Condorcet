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
        self::assertEquals($this->candidate1->getCreateTimestamp(), $this->candidate1->getTimestamp());
    }

    public function testChangeName ()
    {
        self::assertTrue($this->candidate1->setName('candidate1.n2'));

        self::assertEquals('candidate1.n2',$this->candidate1->getName());

        self::assertLessThan($this->candidate1->getTimestamp(), $this->candidate1->getCreateTimestamp());
        self::assertCount(2,$this->candidate1->getHistory());
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
        self::assertSame($this->candidate1,$election1->addCandidate($this->candidate1));
        self::assertSame($this->candidate1,$election2->addCandidate($this->candidate1));
        self::assertSame($this->candidate1,$election3->addCandidate($this->candidate1));

        // Check Candidate Link
        self::assertSame($election1,$this->candidate1->getLinks()[0]);
        self::assertSame($election2,$this->candidate1->getLinks()[1]);
        self::assertSame($election3,$this->candidate1->getLinks()[2]);
        self::assertCount(3,$this->candidate1->getLinks());

        $election3->removeCandidate('candidate1.n1');

        self::assertCount(2,$this->candidate1->getLinks());

        // Add some conflicts
        self::assertTrue($this->candidate1->setName('candidate1.n2'));
        self::assertEquals('candidate1.n2',$this->candidate1->getName());
        self::assertNotSame($this->candidate1, $election1->addCandidate('candidate1.n1'));

        $election2->addCandidate('Debussy');
        $this->candidate1->setName('Debussy'); // Throw an Exception. Code 20.
    }
}