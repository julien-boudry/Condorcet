<?php
declare(strict_types=1);
namespace Condorcet;


use PHPUnit\Framework\TestCase;


class CandidateTest extends TestCase
{
    /**
     * @var candidate1
     */
    private $candidate1;

    public function setUp() : void
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

    public function testTrimName ()
    {
        $candidate = new Candidate (' candidateName ');
        self::assertSame('candidateName',(string) $candidate);
    }

    public function testToLongName ()
    {
        $this->expectException(\Condorcet\CondorcetException::class);
        $this->expectExceptionCode(1);

        new Candidate (
            bin2hex(random_bytes(Election::MAX_LENGTH_CANDIDATE_ID + 42))
        );
    }

    function testSameCandidateToMultipleElection ()
    {
        $this->expectException(\Condorcet\CondorcetException::class);
        $this->expectExceptionCode(19);

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
        self::assertSame('candidate1.n2',$this->candidate1->getName());
        self::assertNotSame($this->candidate1, $election1->addCandidate('candidate1.n1'));

        $election2->addCandidate('Debussy');
        $this->candidate1->setName('Debussy'); // Throw an Exception. Code 19.
    }

    public function testCloneCandidate()
    {
        ($election = new Election)->addCandidate($this->candidate1);

        self::assertsame(1,$this->candidate1->countLinks());

        $cloneCandidate = clone $this->candidate1;

        self::assertsame(0,$cloneCandidate->countLinks());
    }
}