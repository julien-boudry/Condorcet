<?php
declare(strict_types=1);
namespace Condorcet;

use Condorcet\CondorcetException;
use Condorcet\Candidate;
use Condorcet\Election;
use Condorcet\Vote;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Condorcet\Algo\Methods\Copeland
 */
class CopelandTest extends TestCase
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
        # From https://en.wikipedia.org/wiki/Copeland%27s_method

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


        self::assertEquals( [
                1 => 'Nashville',
                2 => 'Chattanooga',
                3 => 'Knoxville',
                4 => 'Memphis' ],
            $this->election->getResult('Copeland')->getResultAsArray(true)
        );

        self::assertSame($this->election->getWinner('Copeland'),$this->election->getWinner());
    }

    public function testResult_2 ()
    {
        # From https://en.wikipedia.org/wiki/Copeland%27s_method

        $candidateA = $this->election->addCandidate('A');
        $candidateB = $this->election->addCandidate('B');
        $candidateC = $this->election->addCandidate('C');
        $candidateD = $this->election->addCandidate('D');
        $candidateE = $this->election->addCandidate('E');

        $this->election->parseVotes('
            A > E > C > D * 31
            B > A > E * 30
            C > D > B * 29
            D > A > E * 10
        ');

        self::assertSame(null, $this->election->getWinner());
        self::assertSame($candidateA, $this->election->getWinner('Copeland'));

        self::assertSame(
            [   1 => $candidateA,
                2 => [$candidateB,$candidateC,$candidateE],
                3 => $candidateD,
            ],
            $this->election->getResult('Copeland')->getResultAsArray()
        );
    }

    public function testResult_3 ()
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

        self::assertEquals(['Abby','Brad'],$this->election->getWinner('Copeland'));

        self::assertSame(
            [   1 => ['Abby','Brad'],
                2 => ['Dave','Erin'],
                3 => 'Cora'    ],
        $this->election->getResult('Copeland')->getResultAsArray(true)
        );
    }
}