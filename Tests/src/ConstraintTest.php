<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests;

use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Vote;
use CondorcetPHP\Condorcet\VoteConstraintInterface;
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
        $class = NoTie::class;

        $this->assertTrue($this->election->addConstraint($class));

        $this->assertSame([$class], $this->election->getConstraints());

        $this->assertTrue($this->election->clearConstraints());

        self::assertsame([], $this->election->getConstraints());

        $this->assertTrue($this->election->addConstraint($class));

        $this->expectException(VoteConstraintException::class);
        $this->expectExceptionMessage('The vote constraint could not be set up: class is already registered');

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

            $this->assertEquals('B', $this->election->getWinner());

            $this->election->addConstraint($constraintClass);

            $this->assertEquals('A', $this->election->getWinner());

            $this->election->clearConstraints();

            $this->assertEquals('B', $this->election->getWinner());

            $this->election->addConstraint($constraintClass);

            $this->assertEquals('A', $this->election->getWinner());

            $this->assertEquals(1, $this->election->sumValidVotesWeightWithConstraints());
            $this->assertEquals(46, $this->election->sumVotesWeight());
            $this->assertEquals(45, $this->election->sumVotesWeight('tag1', false));
            $this->assertEquals(0, $this->election->sumValidVotesWeightWithConstraints('tag1', false));
            $this->assertEquals(5, $this->election->countVotes());
            $this->assertEquals(1, $this->election->countValidVoteWithConstraints());
            $this->assertEquals(0, $this->election->countValidVoteWithConstraints('tag1', false));
            $this->assertEquals(4, $this->election->countInvalidVoteWithConstraints());

            $this->assertEquals('A', $this->election->getWinner('FTPT'));

            $this->assertFalse($this->election->setImplicitRanking(false));

            $this->assertEquals('B', $this->election->getWinner('FTPT'));
            $this->assertEquals('A', $this->election->getWinner());

            $this->assertEquals(43, $this->election->sumValidVotesWeightWithConstraints());
            $this->assertEquals(46, $this->election->sumVotesWeight());
            $this->assertEquals(45, $this->election->sumVotesWeight('tag1', false));
            $this->assertEquals(42, $this->election->sumValidVotesWeightWithConstraints('tag1', false));
            $this->assertEquals(5, $this->election->countVotes());
            $this->assertEquals(2, $this->election->countValidVoteWithConstraints());
            $this->assertEquals(1, $this->election->countValidVoteWithConstraints('tag1', false));
            $this->assertEquals(3, $this->election->countInvalidVoteWithConstraints());

            $this->assertTrue($this->election->setImplicitRanking(true));

            $this->assertEquals('A', $this->election->getWinner());
            $this->assertEquals('A', $this->election->getWinner('FTPT'));

            $this->assertEquals(1, $this->election->sumValidVotesWeightWithConstraints());
            $this->assertEquals(46, $this->election->sumVotesWeight());
            $this->assertEquals(45, $this->election->sumVotesWeight('tag1', false));
            $this->assertEquals(0, $this->election->sumValidVotesWeightWithConstraints('tag1', false));
            $this->assertEquals(5, $this->election->countVotes());
            $this->assertEquals(1, $this->election->countValidVoteWithConstraints());
            $this->assertEquals(0, $this->election->countValidVoteWithConstraints('tag1', false));
            $this->assertEquals(4, $this->election->countInvalidVoteWithConstraints());
            $this->assertCount(1, iterator_to_array($this->election->getVotesValidUnderConstraintGenerator(['tag1'], true)));
            $this->assertCount(0, iterator_to_array($this->election->getVotesValidUnderConstraintGenerator(['tag1'], false)));
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
