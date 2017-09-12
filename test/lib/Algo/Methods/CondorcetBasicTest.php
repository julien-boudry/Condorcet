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

        $this->election->parseVotes('
            a > c > b * 23
            b > c > a * 19
            c > b > a * 16
            c > a > b * 2
        ');

        self::assertEquals($this->election->getWinner(),'c');
    }

    public function testResult_2 ()
    {
        $this->election->addCandidate('X');
        $this->election->addCandidate('Y');
        $this->election->addCandidate('Z');

        $this->election->parseVotes('
            X > Y > Z * 41
            Y > Z > X * 33
            Z > X > Y * 22
        ');

        self::assertSame($this->election->getWinner(),null);
    }

    public function testResult_3 ()
    {
        $this->election->addCandidate('Memphis');
        $this->election->addCandidate('Nashville');
        $this->election->addCandidate('Knoxville');
        $this->election->addCandidate('Chattanooga');

        $this->election->parseVotes('
            Memphis > Nashville > Chattanooga * 42
            Nashville > Chattanooga > Knoxville * 26
            Chattanooga > Knoxville > Nashville * 15
            Knoxville > Chattanooga > Nashville * 17
        ');

        self::assertEquals($this->election->getWinner(),'Nashville');
    }

    public function testResult_4 ()
    {
        $this->election->addCandidate('Memphis');
        $this->election->addCandidate('Nashville');
        $this->election->addCandidate('Knoxville');
        $this->election->addCandidate('Chattanooga');

        $this->election->parseVotes('
            Memphis > Chattanooga > Nashville * 42
            Nashville > Chattanooga > Knoxville * 26
            Chattanooga > Knoxville > Nashville * 15
            Knoxville > Chattanooga > Nashville * 17
        ');

        self::assertEquals($this->election->getWinner(),'Chattanooga');
    }


}