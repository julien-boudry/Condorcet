<?php
declare(strict_types=1);
namespace Condorcet;


use PHPUnit\Framework\TestCase;


class ConstraintTest extends TestCase
{
    /**
     * @var election
     */
    private $election;

    public function setUp()
    {
        $this->election = new Election;

        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');
    }

    /**
     * @expectedException \Condorcet\CondorcetException
     * @expectedExceptionCode 29
     */
    public function testAddConstraintAndClear ()
    {
        $class = __NAMESPACE__.'\\Constraints\\NoTie';

        self::assertTrue($this->election->addConstraint($class));

        self::assertSame( [ $class ], $this->election->getConstraints() );

        self::assertTrue( $this->election->clearConstraints() );

        self::assertsame([], $this->election->getConstraints());

        self::assertTrue($this->election->addConstraint($class));

        $this->election->addConstraint($class);
    }

    /**
     * @expectedException \Condorcet\CondorcetException
     * @expectedExceptionCode 27
     */
    public function testPhantomClass ()
    {
        $class = __NAMESPACE__.'\\Constraints\\NoJuju';

        $this->election->addConstraint($class);
    }

    /**
     * @expectedException \Condorcet\CondorcetException
     * @expectedExceptionCode 28
     */
    public function testBadClass ()
    {
        $class = __NAMESPACE__.'\\Vote';

        $this->election->addConstraint($class);
    }

    public function testConstraintsOnVote ()
    {
        $constraintClass = __NAMESPACE__.'\\Constraints\\NoTie';

        $this->election->allowVoteWeight();

        $this->election->parseVotes('
            A>B>C
            C>B=A * 3
            B^42
        ' );

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


        self::assertFalse($this->election->setImplicitRanking(false));

        self::assertEquals('B',$this->election->getWinner('FTPT'));
        self::assertEquals(43,$this->election->sumValidVotesWeightWithConstraints());
        self::assertEquals(46,$this->election->sumVotesWeight());
        self::assertEquals(5,$this->election->countVotes());
        self::assertEquals(2,$this->election->countValidVoteWithConstraints());
        self::assertEquals(3,$this->election->countInvalidVoteWithConstraints());

        self::assertTrue($this->election->setImplicitRanking(true));

        self::assertEquals('A',$this->election->getWinner());

        self::assertEquals(1,$this->election->sumValidVotesWeightWithConstraints());
        self::assertEquals(46,$this->election->sumVotesWeight());
        self::assertEquals(5,$this->election->countVotes());
        self::assertEquals(1,$this->election->countValidVoteWithConstraints());
        self::assertEquals(4,$this->election->countInvalidVoteWithConstraints());
    }

}