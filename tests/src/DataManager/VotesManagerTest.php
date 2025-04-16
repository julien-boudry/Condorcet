<?php declare(strict_types=1);

use CondorcetPHP\Condorcet\{Election, Vote};
use CondorcetPHP\Condorcet\Throwable\{TagsFilterException, VoteException, VoteManagerException};

beforeEach(function (): void {
    $this->election = new Election;
    $this->election->parseCandidates('A;B;C');

    $this->votes_manager = $this->election->getVotesManager();
});

test('offset set', function (): void {
    $vote = new Vote([]);

    // add valid vote
    $this->votes_manager[] = $vote;
    expect($this->votes_manager->getVotesList()[0])->toBe($vote);
    expect($vote->getLinks()[0])->toBe($this->election);

    $this->expectException(VoteException::class);
    $this->expectExceptionMessage('This vote is already linked to the election');

    // add invalid vote
    $this->votes_manager[] = $vote;
});

test('offset set argument type1', function (): void {
    $this->expectException(VoteManagerException::class);

    // add invalid vote
    try {
        $this->votes_manager[] = new stdClass;
    } catch (VoteManagerException $e) {
        expect($this->votes_manager)->toHaveCount(0);
        throw $e;
    }
});

test('offset set argument type2', function (): void {
    $this->expectException(VoteManagerException::class);

    // add invalid vote
    try {
        $this->votes_manager[] = null;
    } catch (VoteManagerException $e) {
        expect($this->votes_manager)->toHaveCount(0);
        throw $e;
    }
});

test('offset unset', function (): void {
    $before_list = $this->votes_manager->getVotesList();

    // unset non existent vote
    unset($this->votes_manager[0]);
    expect($this->votes_manager->getVotesList())->toBe($before_list);

    // unset existing vote
    $vote = new Vote([]);
    $this->votes_manager[] = $vote;
    unset($this->votes_manager[0]);
    expect($this->votes_manager->getVotesList())->toBeEmpty();
});

test('get vote key', function (): void {
    expect($this->votes_manager->getVoteKey(new Vote([])))->toBeNull();
});

test('get votes list', function (): void {
    // With Election
    expect($this->votes_manager->getVotesList())->toBeEmpty();

    $this->election->addCandidate('candidate');
    $this->election->addVote(new Vote(['candidate']));

    expect($this->votes_manager->getVotesList())->not()->toBeEmpty();
});

test('get votes list generator', function (): void {
    $this->election->parseVotes('A>B>C * 10;tag42 || C>B>A * 42');

    $votesListGenerator = [];

    foreach ($this->election->getVotesListGenerator() as $key => $value) {
        $votesListGenerator[$key] = $value;
    }

    expect($votesListGenerator)->toEqual($this->election->getVotesList());
    expect($votesListGenerator)->toHaveCount(52);

    $votesListGenerator = [];

    foreach ($this->election->getVotesListGenerator('tag42') as $key => $value) {
        $votesListGenerator[$key] = $value;
    }

    expect($votesListGenerator)->toEqual($this->election->getVotesList('tag42'));
    expect($votesListGenerator)->toHaveCount(42);
});

test('count votes', function (): void {
    expect($this->votes_manager->getVotesList())->toBeEmpty();

    $this->election->parseVotes('
            A>B>C * 10;
            tag42 || C>B>A * 42
            tag44 || B>C>A * 26
            tag42, tag44 || A>C>B * 18
        ');

    expect($this->votes_manager->countVotes(['tag42'], 1))->toEqual(60);
    expect($this->votes_manager->countVotes(['tag44'], 1))->toEqual(44);
    expect($this->votes_manager->countVotes(['tag44'], true))->toEqual(44);
    expect($this->votes_manager->countVotes(['tag42', 'tag44'], 2))->toEqual(18);
    expect($this->votes_manager->countVotes(['tag44'], 0))->toEqual(52);
    expect($this->votes_manager->countVotes(['tag44'], false))->toEqual(52);

    $with = -1;

    $this->expectException(TagsFilterException::class);
    $this->expectExceptionMessage('Value of $with cannot be less than 0. Actual value is ' . $with);

    $this->election->countVotes('tag44', $with);
});
