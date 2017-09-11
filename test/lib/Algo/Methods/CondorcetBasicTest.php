<?php
declare(strict_types=1);
namespace Condorcet;

use Condorcet\CondorcetException;
use Condorcet\Candidate;
use Condorcet\Election;
use Condorcet\Vote;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Condorcet\Algo\Methods\CondorcetBasic
 */
class CondorcetBasicTest extends TestCase
{
    /**
     * @var election1
     */
    private $election;

    public function setUp()
    {
        $this->election = new Election;
    }

    public function testResult_1 ()
    {
        $this->election->addCandidate('a');
        $this->election->addCandidate('b');
        $this->election->addCandidate('c');

        for($i=0;$i<23;$i++) {
            $this->election->addVote('a > c > b');
        }
        for($i=0;$i<19;$i++) {
            $this->election->addVote('b > c > a');
        }
        for($i=0;$i<16;$i++) {
            $this->election->addVote('c > b > a');
        }
        for($i=0;$i<2;$i++) {
            $this->election->addVote('c > a > b');
        }

        self::assertEquals($this->election->getWinner(),'c');
    }

    public function testResult_2 ()
    {
        $this->election->addCandidate('X');
        $this->election->addCandidate('Y');
        $this->election->addCandidate('Z');

        for($i=0;$i<41;$i++) {
            $this->election->addVote('X > Y > Z');
        }
        for($i=0;$i<33;$i++) {
            $this->election->addVote('Y > Z > X');
        }
        for($i=0;$i<22;$i++) {
            $this->election->addVote('Z > X > Y');
        }

        self::assertSame($this->election->getWinner(),null);
    }

    public function testResult_3 ()
    {
        $this->election->addCandidate('Memphis');
        $this->election->addCandidate('Nashville');
        $this->election->addCandidate('Knoxville');
        $this->election->addCandidate('Chattanooga');

        for($i=0;$i<42;$i++) {
            $this->election->addVote('Memphis > Nashville > Chattanooga');
        }
        for($i=0;$i<26;$i++) {
            $this->election->addVote('Nashville > Chattanooga > Knoxville');
        }
        for($i=0;$i<15;$i++) {
            $this->election->addVote( 'Chattanooga > Knoxville > Nashville');
        }
        for($i=0;$i<17;$i++) {
            $this->election->addVote( 'Knoxville > Chattanooga > Nashville');
        }

        self::assertEquals($this->election->getWinner(),'Nashville');
    }

    public function testResult_4 ()
    {
        $this->election->addCandidate('Memphis');
        $this->election->addCandidate('Nashville');
        $this->election->addCandidate('Knoxville');
        $this->election->addCandidate('Chattanooga');

        for($i=0;$i<42;$i++) {
            $this->election->addVote('Memphis > Chattanooga > Nashville ');
        }
        for($i=0;$i<26;$i++) {
            $this->election->addVote('Nashville > Chattanooga > Knoxville');
        }
        for($i=0;$i<15;$i++) {
            $this->election->addVote( 'Chattanooga > Knoxville > Nashville');
        }
        for($i=0;$i<17;$i++) {
            $this->election->addVote( 'Knoxville > Chattanooga > Nashville');
        }

        self::assertEquals($this->election->getWinner(),'Chattanooga');
    }


}