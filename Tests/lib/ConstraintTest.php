<?php
declare(strict_types=1);
namespace CondorcetPHP\Condorcet;


use PHPUnit\Framework\TestCase;


class ConstraintTest extends TestCase
{
    /**
     * @var election
     */
    private $election;

    public function setUp() : void
    {
        $this->election = new Election;

        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');
    }

    public function testAddConstraintAndClear ()
    {
        $this->expectException(\CondorcetPHP\Condorcet\CondorcetException::class);
        $this->expectExceptionCode(29);

        $class = Constraints\NoTie::class;

        self::assertTrue($this->election->addConstraint($class));

        self::assertSame( [ $class ], $this->election->getConstraints() );

        self::assertTrue( $this->election->clearConstraints() );

        self::assertsame([], $this->election->getConstraints());

        self::assertTrue($this->election->addConstraint($class));

        $this->election->addConstraint($class);
    }

    public function testPhantomClass ()
    {
        $this->expectException(\CondorcetPHP\Condorcet\CondorcetException::class);
        $this->expectExceptionCode(27);

        $class = Constraints\NoJuju::class;

        $this->election->addConstraint($class);
    }

    public function testBadClass ()
    {
        $this->expectException(\CondorcetPHP\Condorcet\CondorcetException::class);
        $this->expectExceptionCode(28);

        $class = Vote::class;

        $this->election->addConstraint($class);
    }

    public function testConstraintsOnVote ()
    {
        $constraintClass = Constraints\NoTie::class;

        $this->election->parseVotes('
            A>B>C
            C>B=A * 3
            B^42
        ' );

        $this->election->allowVoteWeight();

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
    }

}