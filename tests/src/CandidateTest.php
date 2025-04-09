<?php

declare(strict_types=1);
use CondorcetPHP\Condorcet\{Candidate, Election};
use CondorcetPHP\Condorcet\Throwable\{CandidateExistsException, CandidateInvalidNameException};

beforeEach(function (): void {
    $this->candidate1 = new Candidate('candidate1.n1');
});

test('create timestamp', function (): void {
    expect($this->candidate1->updatedAt)->toEqual($this->candidate1->createdAt);
});

test('change name', function (): void {
    expect($this->candidate1->setName('candidate1.n2'))->toBe($this->candidate1);

    expect($this->candidate1->name)->toEqual('candidate1.n2');

    expect($this->candidate1->createdAt)->toBeLessThan($this->candidate1->updatedAt);
    expect($this->candidate1->nameHistory)->toHaveCount(2);
});

test('trim name', function (): void {
    $candidate = new Candidate(' candidateName ');
    expect((string) $candidate)->toBe('candidateName');
});

test('matching and too long name', function (): void {
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
});

test('bad name', function (): void {
    $this->expectException(CandidateInvalidNameException::class);
    $this->expectExceptionMessage('This name is not valid');

    new Candidate('<$"');
});

test('bad name with newline', function (): void {
    $this->expectException(CandidateInvalidNameException::class);
    $this->expectExceptionMessage('This name is not valid');

    new Candidate("A name with\n a newline");
});

test('candidate bad class', function (): void {
    $this->expectException(TypeError::class);

    (new Election)->addCandidate(new stdClass);
});

test('add same candidate1', function (): void {
    $election1 = new Election;

    $candidate = new Candidate('Schizophrenic');

    $election1->addCandidate($candidate);

    $this->expectException(CandidateExistsException::class);
    $this->expectExceptionMessage('This candidate already exists: Schizophrenic');

    $election1->addCandidate($candidate);
});

test('add same candidate2', function (): void {
    $this->expectException(CandidateExistsException::class);
    $this->expectExceptionMessage('This candidate already exists: candidate1');

    $election1 = new Election;

    $election1->parseCandidates('candidate1;candidate2;candidate1');
});

test('add same candidate3', function (): void {
    $election1 = new Election;

    $election1->addCandidate('candidate1');

    $this->expectException(CandidateExistsException::class);
    $this->expectExceptionMessage('This candidate already exists: candidate1');

    $election1->parseCandidates('candidate2;candidate1');
});

test('add same candidate4', function (): void {
    $election1 = new Election;

    $candidate1 = $election1->addCandidate('candidate1');

    try {
        $election1->parseCandidates('candidate2;candidate1');
    } catch (Exception) {
    }

    expect($election1->getCandidatesList())->toBe([$candidate1]);
});

test('same candidate to multiple election', function (): void {
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
    expect($this->candidate1->setName('candidate1.n2'))->toBe($this->candidate1);
    expect($this->candidate1->name)->toBe('candidate1.n2');
    expect($election1->addCandidate('candidate1.n1'))->not()->toBe($this->candidate1);

    $election2->addCandidate('Debussy');

    $this->expectException(CandidateExistsException::class);
    $this->expectExceptionMessage("This candidate already exists: the name 'Debussy' is taken by another candidate");

    $this->candidate1->name = 'Debussy';
});

test('clone candidate', function (): void {
    ($election = new Election)->addCandidate($this->candidate1);

    expect($this->candidate1->countLinks())->toBe(1);

    $cloneCandidate = clone $this->candidate1;

    expect($cloneCandidate->countLinks())->toBe(0);

    expect($election->countCandidates())->toBe(1);
});
