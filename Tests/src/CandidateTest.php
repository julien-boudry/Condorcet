<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests;

use CondorcetPHP\Condorcet\{Candidate, Election};
use CondorcetPHP\Condorcet\Throwable\{CandidateExistsException, CandidateInvalidNameException};
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
        expect($this->candidate1->getTimestamp())->toEqual($this->candidate1->getCreateTimestamp());
    }

    public function testChangeName(): void
    {
        expect($this->candidate1->setName('candidate1.n2'))->toBeTrue();

        expect($this->candidate1->getName())->toEqual('candidate1.n2');

        expect($this->candidate1->getCreateTimestamp())->toBeLessThan($this->candidate1->getTimestamp());
        expect($this->candidate1->getHistory())->toHaveCount(2);
    }

    public function testTrimName(): void
    {
        $candidate = new Candidate(' candidateName ');
        expect((string) $candidate)->toBe('candidateName');
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
        expect((string) $candidate)->toEqual($name);

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

        $candidate1 = $election1->addCandidate('candidate1');

        try {
            $election1->parseCandidates('candidate2;candidate1');
        } catch (\Exception) {
        }

        expect($election1->getCandidatesList())->toBe([$candidate1]);
    }

    public function testSameCandidateToMultipleElection(): void
    {
        $election1 = new Election;
        $election2 = new Election;
        $election3 = new Election;

        // Add candidate to election
        expect($election1->addCandidate($this->candidate1))->toBe($this->candidate1);
        expect($election2->addCandidate($this->candidate1))->toBe($this->candidate1);
        expect($election3->addCandidate($this->candidate1))->toBe($this->candidate1);

        // Check Candidate Link
        expect($this->candidate1->haveLink($election1))->toBeTrue();
        expect($this->candidate1->haveLink($election2))->toBeTrue();
        expect($this->candidate1->haveLink($election3))->toBeTrue();
        expect($this->candidate1->getLinks())->toHaveCount(3);

        $election3->removeCandidates('candidate1.n1');

        expect($this->candidate1->getLinks())->toHaveCount(2);

        // Add some conflicts
        expect($this->candidate1->setName('candidate1.n2'))->toBeTrue();
        expect($this->candidate1->getName())->toBe('candidate1.n2');
        expect($election1->addCandidate('candidate1.n1'))->not()->toBe($this->candidate1);

        $election2->addCandidate('Debussy');

        $this->expectException(CandidateExistsException::class);
        $this->expectExceptionMessage("This candidate already exists: the name 'Debussy' is taken by another candidate");

        $this->candidate1->setName('Debussy');
    }

    public function testCloneCandidate(): void
    {
        ($election = new Election)->addCandidate($this->candidate1);

        expect($this->candidate1->countLinks())->toBe(1);

        $cloneCandidate = clone $this->candidate1;

        expect($cloneCandidate->countLinks())->toBe(0);

        expect($election->countCandidates())->toBe(1);
    }
}
