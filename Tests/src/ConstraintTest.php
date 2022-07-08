<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests;

use CondorcetPHP\Condorcet\{Election, Vote, VoteConstraintInterface};
use CondorcetPHP\Condorcet\Constraints\NoTie;
use CondorcetPHP\Condorcet\Throwable\VoteConstraintException;
use PHPUnit\Framework\TestCase;

class ConstraintTest extends TestCase
{
    private Election $election;

    protected function setUp(): void
    {
        $this->election = new Election;

        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');
    }

    public function testAddConstraintAndClear(): never
    {
        $this->expectException(VoteConstraintException::class);
        $this->expectExceptionMessage('The vote constraint could not be set up: class is already registered');

        $class = NoTie::class;

        self::assertTrue($this->election->addConstraint($class));

        self::assertSame([$class], $this->election->getConstraints());

        self::assertTrue($this->election->clearConstraints());

        self::assertsame([], $this->election->getConstraints());

        self::assertTrue($this->election->addConstraint($class));

        $this->election->addConstraint($class);
    }

    public function testPhantomClass(): never
    {
        $this->expectException(VoteConstraintException::class);
        $this->expectExceptionMessage('The vote constraint could not be set up: class is not defined');

        $this->election->addConstraint('WrongNamespace\AndWrongClass');
    }

    public function testBadClass(): never
    {
        $this->expectException(VoteConstraintException::class);
        $this->expectExceptionMessage('The vote constraint could not be set up: class is not a valid subclass');

        $class = Vote::class;

        $this->election->addConstraint($class);
    }

    public function testConstraintsOnVote(): void
    {
        $NoTieImplementation = [NoTie::class, AlternativeNoTieConstraintClass::class];

        foreach ($NoTieImplementation as $constraintClass) {
            $this->setUp();

            $this->election->parseVotes('
                tag1 || A>B>C
                C>B=A * 3
                B^42
            ');

            $this->election->allowsVoteWeight();

            self::assertEquals('B', $this->election->getWinner());

            $this->election->addConstraint($constraintClass);

            self::assertEquals('A', $this->election->getWinner());

            $this->election->clearConstraints();

            self::assertEquals('B', $this->election->getWinner());

            $this->election->addConstraint($constraintClass);

            self::assertEquals('A', $this->election->getWinner());

            self::assertEquals(1, $this->election->sumValidVotesWeightWithConstraints());
            self::assertEquals(46, $this->election->sumVotesWeight());
            self::assertEquals(5, $this->election->countVotes());
            self::assertEquals(1, $this->election->countValidVoteWithConstraints());
            self::assertEquals(4, $this->election->countInvalidVoteWithConstraints());

            self::assertEquals('A', $this->election->getWinner('FTPT'));

            self::assertFalse($this->election->setImplicitRanking(false));

            self::assertEquals('B', $this->election->getWinner('FTPT'));
            self::assertEquals('A', $this->election->getWinner());

            self::assertEquals(43, $this->election->sumValidVotesWeightWithConstraints());
            self::assertEquals(46, $this->election->sumVotesWeight());
            self::assertEquals(5, $this->election->countVotes());
            self::assertEquals(2, $this->election->countValidVoteWithConstraints());
            self::assertEquals(3, $this->election->countInvalidVoteWithConstraints());

            self::assertTrue($this->election->setImplicitRanking(true));

            self::assertEquals('A', $this->election->getWinner());
            self::assertEquals('A', $this->election->getWinner('FTPT'));

            self::assertEquals(1, $this->election->sumValidVotesWeightWithConstraints());
            self::assertEquals(46, $this->election->sumVotesWeight());
            self::assertEquals(5, $this->election->countVotes());
            self::assertEquals(1, $this->election->countValidVoteWithConstraints());
            self::assertEquals(4, $this->election->countInvalidVoteWithConstraints());
            self::assertCount(1, $this->election->getVotesValidUnderConstraintGenerator(['tag1'], true));
            self::assertCount(0, $this->election->getVotesValidUnderConstraintGenerator(['tag1'], false));
        }
    }
}


class AlternativeNoTieConstraintClass implements VoteConstraintInterface
{
    public static function isVoteAllow(Election $election, Vote $vote): bool
    {
        foreach ($vote->getContextualRanking($election) as $oneRank) {
            if (\count($oneRank) > 1) {
                return false;
            }
        }

        return true;
    }
}
