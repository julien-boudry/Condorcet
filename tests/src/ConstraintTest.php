<?php

declare(strict_types=1);
use CondorcetPHP\Condorcet\{Election, Vote, VoteConstraintInterface};
use CondorcetPHP\Condorcet\Constraints\NoTie;
use CondorcetPHP\Condorcet\Throwable\VoteConstraintException;

beforeEach(function (): void {
    $this->election = new Election;

    $this->election->addCandidate('A');
    $this->election->addCandidate('B');
    $this->election->addCandidate('C');
});

test('add constraint and clear', function (): void {
    $class = NoTie::class;

    expect($this->election->addConstraint($class))->toBeTrue();

    expect($this->election->getConstraints())->toBe([$class]);

    expect($this->election->clearConstraints())->toBeTrue();

    expect($this->election->getConstraints())
        ->toBeArray()
        ->toBeEmpty()
    ;

    expect($this->election->addConstraint($class))->toBeTrue();

    $this->expectException(VoteConstraintException::class);
    $this->expectExceptionMessage('The vote constraint could not be set up: class is already registered');

    $this->election->addConstraint($class);
});

test('phantom class', function (): void {
    $this->expectException(VoteConstraintException::class);
    $this->expectExceptionMessage('The vote constraint could not be set up: class is not defined');

    $this->election->addConstraint('WrongNamespace\AndWrongClass');
});

test('bad class', function (): void {
    $this->expectException(VoteConstraintException::class);
    $this->expectExceptionMessage('The vote constraint could not be set up: class is not a valid subclass');

    $class = Vote::class;

    $this->election->addConstraint($class);
});

test('constraints on vote', function (string $constraintClass): void {
    $this->election->parseVotes('
            tag1 || A>B>C
            C>B=A * 3
            B^42
        ');

    $this->election->allowsVoteWeight();

    expect($this->election->getWinner())->toEqual('B');

    $this->election->addConstraint($constraintClass);

    expect($this->election->getWinner())->toEqual('A');

    $this->election->clearConstraints();

    expect($this->election->getWinner())->toEqual('B');

    $this->election->addConstraint($constraintClass);

    expect($this->election->getWinner())->toEqual('A');

    expect($this->election->sumValidVotesWeightWithConstraints())->toEqual(1);
    expect($this->election->sumVotesWeight())->toEqual(46);
    expect($this->election->sumVotesWeight('tag1', false))->toEqual(45);
    expect($this->election->sumValidVotesWeightWithConstraints('tag1', false))->toEqual(0);
    expect($this->election->countVotes())->toEqual(5);
    expect($this->election->countValidVoteWithConstraints())->toEqual(1);
    expect($this->election->countValidVoteWithConstraints('tag1', false))->toEqual(0);
    expect($this->election->countInvalidVoteWithConstraints())->toEqual(4);

    expect($this->election->getWinner('FTPT'))->toEqual('A');

    expect($this->election->setImplicitRanking(false))->toBe($this->election);
    expect($this->election->getImplicitRankingRule())->toBeFalse();

    expect($this->election->getWinner('FTPT'))->toEqual('B');
    expect($this->election->getWinner())->toEqual('A');

    expect($this->election->sumValidVotesWeightWithConstraints())->toEqual(43);
    expect($this->election->sumVotesWeight())->toEqual(46);
    expect($this->election->sumVotesWeight('tag1', false))->toEqual(45);
    expect($this->election->sumValidVotesWeightWithConstraints('tag1', false))->toEqual(42);
    expect($this->election->countVotes())->toEqual(5);
    expect($this->election->countValidVoteWithConstraints())->toEqual(2);
    expect($this->election->countValidVoteWithConstraints('tag1', false))->toEqual(1);
    expect($this->election->countInvalidVoteWithConstraints())->toEqual(3);

    $this->election->setImplicitRanking(true);
    expect($this->election->getImplicitRankingRule())->toBeTrue();

    expect($this->election->getWinner())->toEqual('A');
    expect($this->election->getWinner('FTPT'))->toEqual('A');

    expect($this->election->sumValidVotesWeightWithConstraints())->toEqual(1);
    expect($this->election->sumVotesWeight())->toEqual(46);
    expect($this->election->sumVotesWeight('tag1', false))->toEqual(45);
    expect($this->election->sumValidVotesWeightWithConstraints('tag1', false))->toEqual(0);
    expect($this->election->countVotes())->toEqual(5);
    expect($this->election->countValidVoteWithConstraints())->toEqual(1);
    expect($this->election->countValidVoteWithConstraints('tag1', false))->toEqual(0);
    expect($this->election->countInvalidVoteWithConstraints())->toEqual(4);
    expect(iterator_to_array($this->election->getVotesValidUnderConstraintGenerator(['tag1'], true)))->toHaveCount(1);
    expect(iterator_to_array($this->election->getVotesValidUnderConstraintGenerator(['tag1'], false)))->toHaveCount(0);
})->with([NoTie::class, AlternativeNoTieConstraintClass::class]);

class AlternativeNoTieConstraintClass implements VoteConstraintInterface
{
    public static function isVoteAllowed(Election $election, Vote $vote): bool
    {
        foreach ($vote->getContextualRanking($election) as $oneRank) {
            if (\count($oneRank) > 1) {
                return false;
            }
        }

        return true;
    }
}
