<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests;

use CondorcetPHP\Condorcet\Candidate;
use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Throwable\CandidateExistsException;
use CondorcetPHP\Condorcet\Throwable\CandidateInvalidNameException;
use PHPUnit\Framework\TestCase;

class CandidateTest extends TestCase
{
    private readonly Candidate $candidate1;

    protected function setUp(): void
    {
        $this->candidate1 = new Candidate('candidate1.n1');
    }

    public function testCreateTimestamp(): void
    {
        self::assertEquals($this->candidate1->getCreateTimestamp(), $this->candidate1->getTimestamp());
    }

    public function testChangeName(): void
    {
        self::assertTrue($this->candidate1->setName('candidate1.n2'));

        self::assertEquals('candidate1.n2', $this->candidate1->getName());

        self::assertLessThan($this->candidate1->getTimestamp(), $this->candidate1->getCreateTimestamp());
        self::assertCount(2, $this->candidate1->getHistory());
    }

    public function testTrimName(): void
    {
        $candidate = new Candidate(' candidateName ');
        self::assertSame('candidateName', (string) $candidate);
    }

    public function testMatchingAndTooLongName(): never
    {
        $name = '';
        while (mb_strlen($name) < Election::MAX_CANDIDATE_NAME_LENGTH) {
            $name .= uniqid();
        }
        $name = mb_substr($name, 0, Election::MAX_CANDIDATE_NAME_LENGTH);

        // The name is exactly as long as allowed.
        $candidate = new Candidate($name);
        $this->assertEquals($name, (string) $candidate);

        // Now the name is one character too long.
        $name .= 'A';

        $this->expectException(CandidateInvalidNameException::class);
        $this->expectExceptionMessage("This name is not valid: {$name}");

        new Candidate($name);
    }

    public function testBadName(): never
    {
        $this->expectException(CandidateInvalidNameException::class);
        $this->expectExceptionMessage('This name is not valid');

        new Candidate('<$"');
    }

    public function testBadNameWithNewline(): never
    {
        $this->expectException(CandidateInvalidNameException::class);
        $this->expectExceptionMessage('This name is not valid');

        new Candidate("A name with\n a newline");
    }

    public function testCandidateBadClass(): never
    {
        $this->expectException(\TypeError::class);

        (new Election)->addCandidate(new \stdClass);
    }

    public function testAddSameCandidate1(): never
    {
        $election1 = new Election;

        $candidate = new Candidate('Schizophrenic');

        $election1->addCandidate($candidate);

        $this->expectException(CandidateExistsException::class);
        $this->expectExceptionMessage('This candidate already exists: Schizophrenic');

        $election1->addCandidate($candidate);
    }

    public function testAddSameCandidate2(): never
    {
        $this->expectException(CandidateExistsException::class);
        $this->expectExceptionMessage('This candidate already exists: candidate1');

        $election1 = new Election;

        $election1->parseCandidates('candidate1;candidate2;candidate1');
    }

    public function testAddSameCandidate3(): never
    {
        $election1 = new Election;

        $election1->addCandidate('candidate1');

        $this->expectException(CandidateExistsException::class);
        $this->expectExceptionMessage('This candidate already exists: candidate1');

        $election1->parseCandidates('candidate2;candidate1');
    }

    public function testAddSameCandidate4(): void
    {
        $election1 = new Election;

        $candidate1= $election1->addCandidate('candidate1');

        try {
            $election1->parseCandidates('candidate2;candidate1');
        } catch (\Exception) {
        }

        self::assertsame([$candidate1], $election1->getCandidatesList());
    }

    public function testSameCandidateToMultipleElection(): void
    {
        $election1 = new Election;
        $election2 = new Election;
        $election3 = new Election;

        // Add candidate to election
        self::assertSame($this->candidate1, $election1->addCandidate($this->candidate1));
        self::assertSame($this->candidate1, $election2->addCandidate($this->candidate1));
        self::assertSame($this->candidate1, $election3->addCandidate($this->candidate1));

        // Check Candidate Link
        self::assertTrue($this->candidate1->haveLink($election1));
        self::assertTrue($this->candidate1->haveLink($election2));
        self::assertTrue($this->candidate1->haveLink($election3));
        self::assertCount(3, $this->candidate1->getLinks());

        $election3->removeCandidates('candidate1.n1');

        self::assertCount(2, $this->candidate1->getLinks());

        // Add some conflicts
        self::assertTrue($this->candidate1->setName('candidate1.n2'));
        self::assertSame('candidate1.n2', $this->candidate1->getName());
        self::assertNotSame($this->candidate1, $election1->addCandidate('candidate1.n1'));

        $election2->addCandidate('Debussy');

        $this->expectException(CandidateExistsException::class);
        $this->expectExceptionMessage("This candidate already exists: the name 'Debussy' is taken by another candidate");

        $this->candidate1->setName('Debussy');
    }

    public function testCloneCandidate(): void
    {
        ($election = new Election)->addCandidate($this->candidate1);

        self::assertsame(1, $this->candidate1->countLinks());

        $cloneCandidate = clone $this->candidate1;

        self::assertsame(0, $cloneCandidate->countLinks());

        self::assertSame(1, $election->countCandidates());
    }
}
