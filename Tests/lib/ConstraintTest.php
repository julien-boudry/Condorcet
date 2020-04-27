<?php
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests;

use CondorcetPHP\Condorcet\{Candidate, Condorcet, CondorcetUtil, Election, Result, Vote, VoteConstraint};
use PHPUnit\Framework\TestCase;

class ConstraintTest extends TestCase
{
    /**
     * @var election
     */
    private Election $election;

    public function setUp() : void
    {
        $this->election = new Election;

        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');
    }

    public function testAddConstraintAndClear () : void
    {
        $this->expectException(\CondorcetPHP\Condorcet\Throwable\CondorcetException::class);
        $this->expectExceptionCode(29);

        $class = \CondorcetPHP\Condorcet\Constraints\NoTie::class;

        self::assertTrue($this->election->addConstraint($class));

        self::assertSame( [ $class ], $this->election->getConstraints() );

        self::assertTrue( $this->election->clearConstraints() );

        self::assertsame([], $this->election->getConstraints());

        self::assertTrue($this->election->addConstraint($class));

        $this->election->addConstraint($class);
    }

    public function testPhantomClass () : void
    {
        $this->expectException(\CondorcetPHP\Condorcet\Throwable\CondorcetException::class);
        $this->expectExceptionCode(27);

        $class = Constraints\NoJuju::class;

        $this->election->addConstraint($class);
    }

    public function testBadClass () : void
    {
        $this->expectException(\CondorcetPHP\Condorcet\Throwable\CondorcetException::class);
        $this->expectExceptionCode(28);

        $class = Vote::class;

        $this->election->addConstraint($class);
    }

    public function testConstraintsOnVote () : void
    {
        $NoTieImplementation = [\CondorcetPHP\Condorcet\Constraints\NoTie::class, AlternativeNoTieConstraintClass::class];

        foreach ($NoTieImplementation as $constraintClass ) :
            $this->setUp();

            $this->election->parseVotes('
                tag1 || A>B>C
                C>B=A * 3
                B^42
            ' );

            $this->election->allowsVoteWeight();

            self::assertEquals('B',$this->election->getWinner());

            $this->election->addConstraint($constraintClass);

            self::assertEquals('A',$this->election->getWinner());

            $this->election->clearConstraints();

            self::assertEquals('B',$this->election->getWinner());

            $this->election->addConstraint($constraintClass);

            self::assertEquals('A',$this->election->getWinner());

            self::assertEquals(1,$this->election->sumValidVotesWeightWithConstraints());
            self::assertEquals(46,$this->election->sumVotesWeight());
            self::assertEquals(5,$this->election->countVotes());
            self::assertEquals(1,$this->election->countValidVoteWithConstraints());
            self::assertEquals(4,$this->election->countInvalidVoteWithConstraints());

            self::assertEquals('A',$this->election->getWinner('FTPT'));

            self::assertFalse($this->election->setImplicitRanking(false));

            self::assertEquals('B',$this->election->getWinner('FTPT'));
            self::assertEquals('A',$this->election->getWinner());

            self::assertEquals(43,$this->election->sumValidVotesWeightWithConstraints());
            self::assertEquals(46,$this->election->sumVotesWeight());
            self::assertEquals(5,$this->election->countVotes());
            self::assertEquals(2,$this->election->countValidVoteWithConstraints());
            self::assertEquals(3,$this->election->countInvalidVoteWithConstraints());

            self::assertTrue($this->election->setImplicitRanking(true));

            self::assertEquals('A',$this->election->getWinner());
            self::assertEquals('A',$this->election->getWinner('FTPT'));

            self::assertEquals(1,$this->election->sumValidVotesWeightWithConstraints());
            self::assertEquals(46,$this->election->sumVotesWeight());
            self::assertEquals(5,$this->election->countVotes());
            self::assertEquals(1,$this->election->countValidVoteWithConstraints());
            self::assertEquals(4,$this->election->countInvalidVoteWithConstraints());
            self::assertCount(1,$this->election->getVotesValidUnderConstraintGenerator(['tag1'],true));
            self::assertCount(0,$this->election->getVotesValidUnderConstraintGenerator(['tag1'],false));
        endforeach;
    }
}


class AlternativeNoTieConstraintClass extends VoteConstraint
{
    public static function isVoteAllow (Election $election, Vote $vote) : bool
    {
        foreach ($vote->getContextualRanking($election) as $oneRank) :
            if (\count($oneRank) > 1) :
                return false;
            endif;
        endforeach;

        return true;
    }
}