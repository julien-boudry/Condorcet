<?php
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests;

use CondorcetPHP\Condorcet\{Candidate, Condorcet, CondorcetUtil, Election, Result, Vote, VoteConstraint};
use PHPUnit\Framework\TestCase;

class CandidateTest extends TestCase
{
    /**
     * @var candidate1
     */
    private Candidate $candidate1;

    public function setUp(): void
    {
        $this->candidate1 = new Candidate ('candidate1.n1');
    }

    public function testCreateTimestamp (): void
    {
        self::assertEquals($this->candidate1->getCreateTimestamp(), $this->candidate1->getTimestamp());
    }

    public function testChangeName (): void
    {
        self::assertTrue($this->candidate1->setName('candidate1.n2'));

        self::assertEquals('candidate1.n2',$this->candidate1->getName());

        self::assertLessThan($this->candidate1->getTimestamp(), $this->candidate1->getCreateTimestamp());
        self::assertCount(2,$this->candidate1->getHistory());
    }

    public function testTrimName (): void
    {
        $candidate = new Candidate (' candidateName ');
        self::assertSame('candidateName',(string) $candidate);
    }

    public function testToLongName (): never
    {
        $this->expectException(\CondorcetPHP\Condorcet\Throwable\CondorcetException::class);
        $this->expectExceptionCode(1);

        new Candidate (
            bin2hex(random_bytes(Election::MAX_LENGTH_CANDIDATE_ID + 42))
        );
    }

    public function testBadName (): never
    {
        $this->expectException(\CondorcetPHP\Condorcet\Throwable\CondorcetException::class);
        $this->expectExceptionCode(1);

        new Candidate ('<$"');
    }

    public function testCandidateBadClass (): never
    {
        $this->expectException(\TypeError::class);

        (new Election)->addCandidate(new \stdClass );
    }

    public function testAddSameCandidate1 (): never
    {
        $this->expectException(\CondorcetPHP\Condorcet\Throwable\CondorcetException::class);
        $this->expectExceptionCode(3);

        $election1 = new Election ();

        $candidate = new Candidate ('Schizophrenic');

        $election1->addCandidate($candidate);
        $election1->addCandidate($candidate);
    }

    public function testAddSameCandidate2 (): never
    {
        $this->expectException(\CondorcetPHP\Condorcet\Throwable\CondorcetException::class);
        $this->expectExceptionCode(3);

        $election1 = new Election ();

        $election1->parseCandidates('candidate1;candidate2;candidate1');
    }

    public function testAddSameCandidate3 (): never
    {
        $this->expectException(\CondorcetPHP\Condorcet\Throwable\CondorcetException::class);
        $this->expectExceptionCode(3);

        $election1 = new Election ();

        $election1->addCandidate('candidate1');
        $election1->parseCandidates('candidate2;candidate1');
    }

    public function testAddSameCandidate4 (): void
    {
        $election1 = new Election ();

        $candidate1= $election1->addCandidate('candidate1');

        try {
            $election1->parseCandidates('candidate2;candidate1');
        } catch (\Exception) {}

        self::assertsame([$candidate1],$election1->getCandidatesList());
    }

    function testSameCandidateToMultipleElection ()
    {
        $this->expectException(\CondorcetPHP\Condorcet\Throwable\CondorcetException::class);
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

        $election3->removeCandidates('candidate1.n1');

        self::assertCount(2,$this->candidate1->getLinks());

        // Add some conflicts
        self::assertTrue($this->candidate1->setName('candidate1.n2'));
        self::assertSame('candidate1.n2',$this->candidate1->getName());
        self::assertNotSame($this->candidate1, $election1->addCandidate('candidate1.n1'));

        $election2->addCandidate('Debussy');
        $this->candidate1->setName('Debussy'); // Throw an Exception. Code 19.
    }

    public function testCloneCandidate(): void
    {
        ($election = new Election)->addCandidate($this->candidate1);

        self::assertsame(1,$this->candidate1->countLinks());

        $cloneCandidate = clone $this->candidate1;

        self::assertsame(0,$cloneCandidate->countLinks());
    }
}