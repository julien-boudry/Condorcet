<?php

declare(strict_types=1);
use CondorcetPHP\Condorcet\{Candidate, Election, Vote};
use CondorcetPHP\Condorcet\Throwable\{CandidateDoesNotExistException, VoteException, VoteInvalidFormatException, VoteNotLinkedException};
use CondorcetPHP\Condorcet\Tools\Converters\CEF\CondorcetElectionFormat;
use CondorcetPHP\Condorcet\Utils\CondorcetUtil;

beforeEach(function (): void {
    $this->election1 = new Election;

    $this->candidate1 = $this->election1->addCandidate('candidate1');
    $this->candidate2 = $this->election1->addCandidate('candidate2');
    $this->candidate3 = $this->election1->addCandidate('candidate3');
    $this->candidate4 = new Candidate('candidate4');
    $this->candidate5 = new Candidate('candidate5');
    $this->candidate6 = new Candidate('candidate6');
});

test('timestamp', function (): void {
    $vote1 = new Vote([$this->candidate1, $this->candidate2, $this->candidate3]);

    expect($vote1->updatedAt)->toBe($vote1->createdAt);

    $vote1->setRanking([$this->candidate1, $this->candidate2, $this->candidate3]);

    expect($vote1->createdAt)->toBeLessThan($vote1->updatedAt);
});

test('different ranking', function (): void {
    // Ranking 1
    $vote1 = new Vote([$this->candidate1, $this->candidate2, $this->candidate3]);

    $newRanking1 = $vote1->getRanking();

    // Ranking 2
    expect($vote1->setRanking(
        [$this->candidate1, $this->candidate2, $this->candidate3]
    ))->toBe($vote1);

    expect($vote1->getRanking())->toBe($newRanking1);

    // Ranking 3
    expect($vote1->setRanking(
        [4 => $this->candidate1, 6 => $this->candidate2, 14 => $this->candidate3]
    ))->toBe($vote1);

    expect($vote1->getRanking())->toBe($newRanking1);

    // Add vote into an election
    expect($vote1)->toBe($this->election1->addVote($vote1));

    // Ranking 4
    $vote1->setRanking(
        [$this->candidate1, $this->candidate2]
    );

    expect($vote1->getContextualRanking($this->election1))->toBe($newRanking1);

    expect($vote1->getRanking())->toHaveCount(2);

    // Ranking 5
    $vote1->setRanking(
        ['candidate1', 'candidate2']
    );

    expect($vote1->getContextualRanking($this->election1))->toBe($newRanking1);

    // Ranking 6
    $vote1->setRanking(
        [42 => 'candidate2', 142 => 'candidate1']
    );

    expect($vote1->getContextualRanking($this->election1))->not()->toBe($newRanking1);

    // Ranking 7
    $vote1->setRanking(
        'candidate1>Kim Jong>candidate2>Trump'
    );

    expect($vote1->getContextualRanking($this->election1))->toBe($newRanking1);

    // Ranking 8
    $vote1->setRanking([
        2 => $this->candidate2,
        1 => $this->candidate1,
        3 => $this->candidate3,
    ]);

    expect($vote1->getContextualRanking($this->election1))->toBe($newRanking1);

    // Ranking 9
    $vote = new Vote('candidate4 > candidate3 = candidate1 > candidate2');

    expect([
        1 => 'candidate4',
        2 => ['candidate1', 'candidate3'],
        3 => 'candidate2',
    ])->toBe(CondorcetUtil::format($vote->getRanking()));

    $election = new Election;
    $election->parseCandidates('candidate2;candidate3;candidate4;candidate1');
    $election->addVote($vote);

    expect([
        1 => 'candidate4',
        2 => ['candidate1', 'candidate3'],
        3 => 'candidate2',
    ])->toBe(CondorcetUtil::format($vote->getContextualRanking($election)));

    expect([
        1 => 'candidate4',
        2 => ['candidate1', 'candidate3'],
        3 => 'candidate2',
    ])->toBe($election->getResult()->rankingAsArrayString);

    // Contextual Ranking Fail
    $this->expectException(VoteNotLinkedException::class);
    $this->expectExceptionMessage('The vote is not linked to an election');

    $unexpectedElection = new Election;
    $vote1->getContextualRanking($unexpectedElection);
});

test('array access', function (): void {
    // Ranking 1
    $vote = new Vote('candidate1 > candidate3 = candidate2 > candidate4');

    expect($vote[1][0]->name)->toBe('candidate1');
    expect($vote[3][0]->name)->toBe('candidate4');
});

test('array access set exception', function (): void {
    $this->expectException(VoteException::class);

    // Ranking 1
    $vote = new Vote('candidate1 > candidate3 = candidate2 > candidate4');

    $vote[1] = 'candidateX';
});

test('array access unset exception', function (): void {
    $this->expectException(VoteException::class);

    // Ranking 1
    $vote = new Vote('candidate1 > candidate3 = candidate2 > candidate4');

    unset($vote[1]);
});

test('simple ranking', function (): void {
    // Ranking 1
    $vote1 = new Vote('candidate1 > candidate3 = candidate2 > candidate4');

    expect('candidate1 > candidate2 = candidate3 > candidate4')->toBe($vote1->getSimpleRanking());

    $this->election1->addVote($vote1);

    expect('candidate1 > candidate2 = candidate3')->toBe($vote1->getSimpleRanking($this->election1));
});

test('provisional candidate object', function (): void {
    // Ranking 1
    $vote1 = new Vote([$this->candidate1, $this->candidate2, $this->candidate3]);
    $newRanking1 = $vote1->getRanking();
    $this->election1->addVote($vote1);

    // I
    expect($vote1->setRanking([
        new Candidate('candidate1'),
        $this->candidate2,
        $this->candidate3,
    ]))->toBe($vote1);

    expect($vote1->getContextualRanking($this->election1))->not()->toBe($newRanking1);

    expect($vote1->getContextualRanking($this->election1))->toBe([1 => [$this->candidate2],
        2 => [$this->candidate3],
        3 => [$this->candidate1], ]);

    expect($vote1->getContextualRankingAsString($this->election1))->toBe([1 => 'candidate2',
        2 => 'candidate3',
        3 => 'candidate1', ]);

    // II
    $vote2 = new Vote('candidate1>candidate2');

    expect($vote2->getRanking()[1][0]->provisionalState)->toBeTrue();
    $vote2_firstRanking = $vote2->getRanking();

    $this->election1->addVote($vote2);

    expect($vote2->getRanking()[1][0]->provisionalState)->toBeFalse();

    expect($vote2->getContextualRanking($this->election1))->toBe([1 => [$this->candidate1],
        2 => [$this->candidate2],
        3 => [$this->candidate3], ]);

    expect($vote2->getRanking())->not()->toBe($vote2_firstRanking);

    // III
    $otherCandidate1 = new Candidate('candidate1');
    $otherCandidate2 = new Candidate('candidate2');

    $vote3 = new Vote([$otherCandidate1, $otherCandidate2, $this->candidate3]);

    expect($vote3->getRanking()[1][0]->provisionalState)->toBeFalse();
    $vote3_firstRanking = $vote3->getRanking();

    $this->election1->addVote($vote3);

    expect($vote2->getRanking()[1][0]->provisionalState)->toBeFalse();

    expect($vote3->getContextualRanking($this->election1))->toBe([1 => [$this->candidate3],
        2 => [$this->candidate1, $this->candidate2], ]);

    expect($vote3->getContextualRankingAsString($this->election1))->toBe([1 => 'candidate3',
        2 => ['candidate1', 'candidate2'], ]);

    expect($vote3->getRanking())->toBe($vote3_firstRanking);
});

test('different election', function (): void {
    $election1 = $this->election1;

    $election2 = new Election;
    $election2->addCandidate($this->candidate1);
    $election2->addCandidate($this->candidate2);
    $election2->addCandidate($this->candidate4);

    $vote1 = new Vote([
        $this->candidate1,
        $this->candidate2,
        $this->candidate3,
        $this->candidate4,
    ]);
    $vote1_originalRanking = $vote1->getRanking();

    $election1->addVote($vote1);
    $election2->addVote($vote1);

    expect($vote1->getRanking())->toBe($vote1_originalRanking);
    expect($vote1->getContextualRanking($election1))->toBe([1 => [$this->candidate1], 2 => [$this->candidate2], 3 => [$this->candidate3]]);
    expect($vote1->getContextualRanking($election2))->toBe([1 => [$this->candidate1], 2 => [$this->candidate2], 3 => [$this->candidate4]]);
    expect($vote1->getContextualRanking($election2))->not()->toBe($vote1->getRanking());

    expect($vote1->setRanking([
        [$this->candidate5, $this->candidate2],
        $this->candidate3,
    ]))->toBe($vote1);

    expect($vote1->getContextualRanking($election1))->toBe([1 => [$this->candidate2], 2 => [$this->candidate3], 3 => [$this->candidate1]]);
    expect($vote1->getContextualRanking($election2))->toBe([1 => [$this->candidate2], 2 => [$this->candidate1, $this->candidate4]]);
});

test('valid tags', function (): void {
    $vote1 = new Vote([$this->candidate1, $this->candidate2, $this->candidate3]);

    $targetTags = ['tag1', 'tag2', 'tag3'];

    expect($vote1->addTags('tag1,tag2,tag3'))->toBeTrue();

    expect(array_values($vote1->tags))->toBe($targetTags);

    expect($vote1->removeAllTags())->toBeTrue();

    expect($vote1->tags)->toBeArray()->toBeEmpty();

    expect($vote1->addTags(['tag1', 'tag2', 'tag3']))->toBeTrue();

    expect(array_values($vote1->tags))->toBe($targetTags);

    expect($vote1->removeTags('tag2'))->toBe(['tag2']);

    expect(array_values($vote1->tags))->toBe(['tag1', 'tag3']);

    expect($vote1->removeAllTags())->toBeTrue();

    expect($vote1->tags)->toBeArray()->toBeEmpty();
});

test('bad tag input1', function (): void {
    $this->expectException(VoteInvalidFormatException::class);
    $this->expectExceptionMessage('The format of the vote is invalid: every tag must be of type string, integer given');

    $vote = new Vote('A');
    $vote->addTags(['tag1', 42]);
});

test('bad tag input2', function (): void {
    $vote = new Vote('A');

    try {
        $vote->addTags(
            ['tag1 ', ' tag2', ' tag3 ', ' ']
        );
    } catch (Throwable $e) {
    }

    expect($vote->tags)->toBeArray()->toBeEmpty();

    expect($vote->removeAllTags())->toBeTrue();

    $this->expectException(VoteInvalidFormatException::class);
    $this->expectExceptionMessage('The format of the vote is invalid: found empty tag');

    throw $e;
});

test('bad tag input3', function (): void {
    $vote = new Vote('A');

    try {
        $vote->addTags(
            ' tag1,tag2 , tag3 ,'
        );
    } catch (Throwable $e) {
    }

    expect($vote->tags)->toBeArray()->toBeEmpty();

    expect($vote->removeAllTags())->toBeTrue();

    $this->expectException(VoteInvalidFormatException::class);
    $this->expectExceptionMessage('The format of the vote is invalid: found empty tag');

    throw $e;
});

test('bad tag input4', function (): void {
    $vote = new Vote('A');

    try {
        $vote->addTags(
            [null]
        );
    } catch (Throwable $e) {
    }

    expect($vote->tags)->toBeArray()->toBeEmpty();

    $this->expectException(VoteInvalidFormatException::class);
    $this->expectExceptionMessage('The format of the vote is invalid: every tag must be of type string, NULL given');

    throw $e;
});

test('bad tag input5', function (): void {
    $vote = new Vote('A');
    $vote->addTags(
        []
    );

    expect($vote->tags)->toBeArray()->toBeEmpty();
});

test('add remove tags', function (): void {
    $vote1 = new Vote([$this->candidate1, $this->candidate2, $this->candidate3]);

    $vote1->addTags('tag1');
    $vote1->addTags(['tag2', 'tag3']);
    expect($vote1->addTags('tag4,tag5'))->toBeTrue();

    expect($vote1->tags)->toBe(['tag1', 'tag2', 'tag3', 'tag4', 'tag5']);

    expect($vote1->removeTags(''))->toBeArray()->toBeEmpty();

    $vote1->removeTags('tag1');
    $vote1->removeTags(['tag2', 'tag3']);
    expect(['tag4', 'tag5'])->toBe($vote1->removeTags('tag4,tag5,tag42'));

    expect($vote1->tags)->toBeArray()->toBeEmpty();

    expect($vote1->addTags('tag4,tag5'))->toBeTrue();
    expect($vote1->removeAllTags())->toBeTrue();

    expect($vote1->tags)->toBeArray()->toBeEmpty();
});

test('tags on constructor by string input', function (): void {
    $vote1 = new Vote('tag1,tag2 ||A > B >C', 'tag3,tag4');

    expect($vote1->tags)->tobe(['tag3', 'tag4', 'tag1', 'tag2']);

    expect($vote1->getSimpleRanking())->toBe('A > B > C');

    $vote2 = new Vote((string) $vote1);

    expect((string) $vote2)->toBe((string) $vote1);
});

test('clone vote', function (): void {
    // Ranking 1
    $vote1 = new Vote('candidate1 > candidate3 = candidate2 > candidate4');

    $this->election1->addVote($vote1);

    $vote2 = clone $vote1;

    expect($vote2->countLinks())->toBe(0);
    expect($vote1->countLinks())->toBe(1);
});

test('iterator', function (): void {
    $vote = new Vote('C > B > A');

    foreach ($vote as $key => $value) {
        expect($value)->toBe($vote->getRanking()[$key]);
    }
});

test('weight', function (): void {
    $vote = new Vote('A>B>C^42');

    expect($vote->getWeight())->toBe(42);
    expect($vote->setWeight(2))->toBe(2);
    expect($vote->getWeight())->toBe(2);
    expect($vote->getWeight($this->election1))->toBe(1);

    $this->expectException(VoteInvalidFormatException::class);
    $this->expectExceptionMessage("The format of the vote is invalid: the value 'a' is not an integer.");

    $vote = new Vote('A>B>C^a');
});

test('custom timestamp', function (): void {
    $vote = new Vote(
        'A>B>C',
        null,
        $createTimestamp = microtime(true) - (3600 * 1000)
    );

    expect($vote->updatedAt)->toBe($createTimestamp);

    $vote->setRanking('B>C>A', $ranking2Timestamp = microtime(true) - (60 * 1000));

    expect($vote->updatedAt)->toBe($ranking2Timestamp);

    expect($vote->createdAt)->toBe($createTimestamp);

    expect($vote->rankingHistory[0]['timestamp'])->toBe($createTimestamp);

    expect($vote->rankingHistory[1]['timestamp'])->toBe($ranking2Timestamp);

    $this->expectException(VoteInvalidFormatException::class);
    $this->expectExceptionMessage('The format of the vote is invalid: Timestamp format of vote is not correct');

    $vote->setRanking('A', 1);
});

test('hash code', function (): void {
    $vote = new Vote('A>B>C');

    $hashVote[1] = $vote->hash;

    $vote->addTags('tag1');

    $hashVote[2] = $vote->hash;

    $vote->setRanking('C>A>B');

    $hashVote[3] = $vote->hash;

    $vote->setRanking('C>A>B');

    $hashVote[4] = $vote->hash;

    expect($hashVote[1])->not()->tobe($hashVote[2]);
    expect($hashVote[2])->not()->tobe($hashVote[3]);
    expect($hashVote[3])->not()->toBe($hashVote[4]);
});

test('count ranks', function (): void {
    $vote = new Vote('A>B=C>D');

    expect($vote->countRanks())->toBe(3);
    expect($vote->countCandidates)->toBe(4);
});

test('count ranking candidates', function (): void {
    $vote = new Vote('A>B>C');

    expect($vote->countCandidates)->toBe(3);
});

test('invalid weight', function (): void {
    $this->expectException(VoteInvalidFormatException::class);
    $this->expectExceptionMessage('The format of the vote is invalid: the vote weight can not be less than 1');

    $vote = new Vote('A>B>C');

    $vote->setWeight(0);
});

test('invalid tag1', function (): void {
    $this->expectException(TypeError::class);

    $vote = new Vote('A>B>C');

    $vote->addTags(true);
});

test('invalid tag2', function (): void {
    $this->expectException(TypeError::class);

    $vote = new Vote('A>B>C');

    $vote->addTags(42);
});

test('remove candidate', function (): void {
    $vote1 = new Vote('candidate1 > candidate2 > candidate3 ^ 42');

    $this->election1->addVote($vote1);

    expect($this->election1->getResult()->rankingAsString)->toBe('candidate1 > candidate2 > candidate3');

    $vote1->removeCandidate('candidate2');

    expect($vote1->getSimpleRanking())->toBe('candidate1 > candidate3 ^42');

    expect($this->election1->getResult()->rankingAsString)->toBe('candidate1 > candidate3 > candidate2');

    $vote1->removeCandidate($this->candidate3);

    expect($this->election1->getResult()->rankingAsString)->toBe('candidate1 > candidate2 = candidate3');

    $this->expectException(CandidateDoesNotExistException::class);
    $this->expectExceptionMessage('This candidate does not exist: candidate4');

    $vote1->removeCandidate($this->candidate4);
});

test('remove candidate invalid input', function (): void {
    $vote1 = new Vote('candidate1 > candidate2 > candidate3 ^ 42');

    $this->expectException(TypeError::class);

    $vote1->removeCandidate([]);
});

test('vote history', function (): void {
    $this->election1->addCandidate($this->candidate4);
    $this->election1->addCandidate($this->candidate5);
    $this->election1->addCandidate($this->candidate6);

    $vote1 = $this->election1->addVote(['candidate1', 'candidate2']);

    expect($vote1->rankingHistory)->toHaveCount(1);

    // -------
    $vote2 = $this->election1->addVote('candidate1 > candidate2');

    expect($vote2->rankingHistory)->toHaveCount(1);

    // -------
    $vote3 = new Vote(['candidate1', 'candidate2']);

    $this->election1->addVote($vote3);

    expect($vote3)->toHaveCount(2);

    // -------
    $this->election1->parseVotes('voterParsed || candidate1 > candidate2');

    $votes_lists = $this->election1->getVotesListGenerator('voterParsed', true);
    $vote4 = $votes_lists->current();

    expect($vote4->rankingHistory)->toHaveCount(1);

    // -------
    $vote5 = new Vote([$this->candidate5, $this->candidate6]);

    $this->election1->addVote($vote5);

    expect($vote5->rankingHistory)->toHaveCount(1);

    // -------
    $vote6 = new Vote([$this->candidate5, 'candidate6']);

    $this->election1->addVote($vote6);

    expect($vote6->rankingHistory)->toHaveCount(2);

    // -------
    $vote7 = new Vote([$this->candidate6, 'candidate8']);

    $candidate8 = $vote7->getAllCandidates()[1];

    expect($candidate8->name)->toBe('candidate8');

    expect($candidate8->provisionalState)->toBeTrue();

    $this->election1->addVote($vote7);

    expect($candidate8->provisionalState)->toBeTrue();

    expect($vote7->rankingHistory)->toHaveCount(1);
});

test('bad ranking input1', function (): void {
    $this->expectException(TypeError::class);

    $vote = new Vote(42);
});

test('bad ranking input2', function (): void {
    $this->expectException(VoteInvalidFormatException::class);
    $this->expectExceptionMessage('The format of the vote is invalid');

    $candidate = new Candidate('A');

    $vote = new Vote([$candidate, $candidate]);
});

test('empty vote contextual in ranking', function (): void {
    $vote = $this->election1->addVote('candidate4 > candidate5');

    expect($vote->getContextualRanking($this->election1))->toBe([1 => [$this->candidate1, $this->candidate2, $this->candidate3]]);

    $cr = $vote->getContextualRankingAsString($this->election1);

    expect($cr)->toBe([1 => ['candidate1', 'candidate2', 'candidate3']]);
});

test('non empty vote contextual in ranking', function (): void {
    $vote = $this->election1->addVote('candidate1 = candidate2 = candidate3');

    expect($vote->getContextualRanking($this->election1))->toBe([1 => [$this->candidate1, $this->candidate2, $this->candidate3]]);

    $cr = $vote->getContextualRankingAsString($this->election1);

    expect($cr)->toBe([1 => ['candidate1', 'candidate2', 'candidate3']]);
});

test('duplicate candidates1', function (): void {
    $this->expectException(VoteInvalidFormatException::class);
    $this->expectExceptionMessage('The format of the vote is invalid');

    new Vote('Spain>Japan>France>Netherlands>Australia>France');
});

test('duplicate candidates2', function (): void {
    $election = new Election;
    $election->parseCandidates('Spain;Japan;France;Netherlands;Australia');

    $vote = $election->addVote('Spain>Japan>France>Netherlands>Australia>france');

    expect($vote->getSimpleRanking($election))->toBe('Spain > Japan > France > Netherlands > Australia');
});

test('empty special key word', function (): void {
    $vote1 = new Vote(CondorcetElectionFormat::SPECIAL_KEYWORD_EMPTY_RANKING);
    $vote2 = new Vote('  ' . CondorcetElectionFormat::SPECIAL_KEYWORD_EMPTY_RANKING . '  ');

    expect($vote1->getRanking())->toBe([]);
    expect($vote2->getRanking())->toBe([]);
});

test('get all candidates', function (): void {
    $vote = new Vote('candidate1>candidate2>candidate8>candidate3=candidate4=candidate6>candidate5');
    $election = new Election;

    $election->addCandidate($this->candidate1);
    $election->addCandidate($this->candidate2);
    $election->addCandidate($this->candidate3);
    $election->addCandidate($this->candidate4);
    $election->addCandidate($this->candidate5);
    $election->addCandidate($this->candidate6);

    $election->addVote($vote);

    expect($vote->getAllCandidates())->toBe([
        $this->candidate1,
        $this->candidate2,
        $vote[3][0],
        $this->candidate3,
        $this->candidate4,
        $this->candidate6,
        $this->candidate5,
    ]);

    expect($vote->getAllCandidates($election))->tobe([
        $this->candidate1,
        $this->candidate2,
        $this->candidate3,
        $this->candidate4,
        $this->candidate6,
        $this->candidate5,
    ]);
});
