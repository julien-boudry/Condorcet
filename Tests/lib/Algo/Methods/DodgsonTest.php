<?php
declare(strict_types=1);
namespace Condorcet;

use Condorcet\CondorcetException;
use Condorcet\Candidate;
use Condorcet\Election;
use Condorcet\Vote;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Condorcet\Algo\Methods\Dodgson
 */
class DodgsonTest extends TestCase
{
    /**
     * @var election
     */
    private $election;

    public function setUp()
    {
        $this->election = new Election;
    }

    public function testResult_1 ()
    {
        # From http://www.cs.wustl.edu/~legrand/rbvote/desc.html

        $CandidateAbby = $this->election->addCandidate('Abby');
        $CandidateBrad = $this->election->addCandidate('Brad');
        $CandidateCora = $this->election->addCandidate('Cora');
        $CandidateDave = $this->election->addCandidate('Dave');
        $CandidateErin = $this->election->addCandidate('Erin');

        $this->election->parseVotes('
            Abby>Cora>Erin>Dave>Brad * 98
            Brad>Abby>Erin>Cora>Dave * 64
            Brad>Abby>Erin>Dave>Cora * 12
            Brad>Erin>Abby>Cora>Dave * 98
            Brad>Erin>Abby>Dave>Cora * 13
            Brad>Erin>Dave>Abby>Cora * 125
            Cora>Abby>Erin>Dave>Brad * 124
            Cora>Erin>Abby>Dave>Brad * 76
            Dave>Abby>Brad>Erin>Cora * 21
            Dave>Brad>Abby>Erin>Cora * 30
            Dave>Brad>Erin>Cora>Abby * 98
            Dave>Cora>Abby>Brad>Erin * 139
            Dave>Cora>Brad>Abby>Erin * 23
        ');

        self::assertSame($CandidateCora,$this->election->getWinner('Dodgson'));

        self::assertSame(
            $this->election->getResult('Dodgson')->getResultAsArray(true),
            [   1 => 'Cora',
                2 => 'Abby',
                3 => 'Brad',
                4 => 'Dave',
                5 => 'Erin'   ]
        );
    }

    public function testResult_2 ()
    {
        # From http://dss.in.tum.de/files/brandt-research/dodgson.pdf

        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');
        $this->election->addCandidate('D');

        $this->election->parseVotes('
            D>C>A>B*2
            B>C>A>D*2
            C>A>B>D*2
            D>B>C>A*2
            A>B>C>D*2
            A>D>B>C*1
            D>A>B>C*1
        ');

        self::assertEquals(
            (string) $this->election->getWinner('Dodgson'),'A');
    }

    public function testResult_3 ()
    {
        # From http://dss.in.tum.de/files/brandt-research/dodgson.pdf

        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');
        $this->election->addCandidate('D');

        $this->election->parseVotes('
            D>C>A>B*6
            B>C>A>D*6
            C>A>B>D*6
            D>B>C>A*6
            A>B>C>D*6
            A>D>B>C*3
            D>A>B>C*3
        ');

        self::assertEquals(
            $this->election->getWinner('Dodgson'),'D');
    }

    public function testResult_4 ()
    {
        # From http://dss.in.tum.de/files/brandt-research/dodgson.pdf

        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');
        $this->election->addCandidate('D');

        $this->election->parseVotes('
            C>A>D>B*15
            B>D>C>A*9
            A>B>D>C*9
            A>C>B>D*5
            B>A>C>D*5
        ');

        self::assertEquals(
            (string) $this->election->getWinner('Dodgson'),'A');
    }

    public function testResult_5 ()
    {
        # From http://dss.in.tum.de/files/brandt-research/dodgson.pdf

        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');
        $this->election->addCandidate('D');

        $this->election->parseVotes('
            C>A>D>B*15
            B>D>C>A*9
            A>B>D>C*9
            A>C>B>D*5
            A>B>C>D*5
        ');

        self::assertEquals(
            $this->election->getWinner('Dodgson'),'C');
    }

    public function testResult_6 ()
    {
        # From http://dss.in.tum.de/files/brandt-research/dodgson.pdf

        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');
        $this->election->addCandidate('D');

        $this->election->parseVotes('
            D>A>B>C*10
            B>C>A>D*8
            C>A>B>D*7
            D>C>A>B*4
        ');

        self::assertEquals(
            $this->election->getWinner('Dodgson'),'D');
    }

    public function testResult_7 ()
    {
        # From http://dss.in.tum.de/files/brandt-research/dodgson.pdf

        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');
        $this->election->addCandidate('D');

        $this->election->parseVotes('
            C>B>A>D*10
            D>A>C>B*8
            D>B>A>C*7
            B>A>C>D*4
        ');

        self::assertEquals(
            $this->election->getWinner('Dodgson'),'D');
    }

    public function testResult_8 ()
    {
        # From http://dss.in.tum.de/files/brandt-research/dodgson.pdf

        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');

        $this->election->parseVotes('
            A>B>C*5
            B>C>A*4
            C>A>B*3
        ');

        self::assertEquals(
            $this->election->getWinner('Dodgson'),'A');
    }

    public function testResult_9 ()
    {
        # From http://dss.in.tum.de/files/brandt-research/dodgson.pdf

        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');
        $this->election->addCandidate('Cp');

        $this->election->parseVotes('
            A>B>C>Cp*5
            B>C>Cp>A*4
            C>Cp>A>B*3
        ');

        self::assertEquals(
            $this->election->getWinner('Dodgson'),'B');
    }

    public function testResult_10 ()
    {
        # From https://link.springer.com/article/10.1007/s003550000060

        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');
        $this->election->addCandidate('D');

        $this->election->parseVotes('
            A>B>C>D*21
            C>D>B>A*12
            D>C>A>B*5
            B>D>A>C*12
        ');

        self::assertEquals(
            $this->election->getWinner('Dodgson'),'B');
    }

    public function testResult_11 ()
    {
        # From https://www.maa.org/sites/default/files/pdf/cmj_ftp/CMJ/September%202010/3%20Articles/6%2009-229%20Ratliff/Dodgson_CMJ_Final.pdf

        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');
        $this->election->addCandidate('D');

        $this->election->parseVotes('
            A>B>C>D*3
            D>B>A>C*1
            D>C>A>B*1
            B>D>C>A*1
            C>D>B>A*1
        ');

        self::assertEquals(
            $this->election->getWinner('Dodgson'),'B');
    }

    public function testResult_12 ()
    {
        # From https://www.maa.org/sites/default/files/pdf/cmj_ftp/CMJ/September%202010/3%20Articles/6%2009-229%20Ratliff/Dodgson_CMJ_Final.pdf

        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');
        $this->election->addCandidate('D');

        $this->election->parseVotes('
            A>B>C>D*5
            D>C>A>B*6
            C>A>B>D*5
            D>B>C>A*5
            B>C>A>D*4
            D>A>B>C*4
            C>D>A>B*1
            B>A>C>D*1
            B>D>A>C*1
            C>A>B>D*1
            A>D>B>C*1
            C>B>A>D*1
        ');

        self::assertEquals(
            $this->election->getWinner('Dodgson'),'C');
    }


}