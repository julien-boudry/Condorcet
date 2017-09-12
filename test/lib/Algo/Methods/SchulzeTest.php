<?php
declare(strict_types=1);
namespace Condorcet;

use Condorcet\CondorcetException;
use Condorcet\Candidate;
use Condorcet\Election;
use Condorcet\Vote;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Condorcet\Algo\Methods\SchulzeCore
 */
class SchulzeTest extends TestCase
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
        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');
        $this->election->addCandidate('D');
        $this->election->addCandidate('E');

        $this->election->parseVotes('
            A > C > B > E * 5
            A > D > E > C * 5
            B > E > D > A * 8
            C > A > B > E * 3
            C > A > E > B * 7
            C > B > A > D * 2
            D > C > E > B * 7
            E > B > A > D * 8
        ');

        self::assertEquals('E',$this->election->getWinner('Schulze Winning'));

        self::assertSame(
            $this->election->getResult('Schulze Winning')->getResultAsArray(true),
            [   1 => 'E',
                2 => 'A',
                3 => 'C',
                4 => 'B',
                5 => 'D'    ]
        );
    }

    public function testResult_2 ()
    {
        $candidateA = $this->election->addCandidate('A');
        $candidateB = $this->election->addCandidate('B');
        $candidateC = $this->election->addCandidate('C');
        $candidateD = $this->election->addCandidate('D');

        $this->election->parseVotes('
            A > B > C * 3
            D > A > B * 2
            D > B > C * 2
            C > B > D * 2
        ');

        self::assertSame([$candidateB,$candidateD],$this->election->getWinner('Schulze Winning'));

        self::assertSame(
            $this->election->getResult('Schulze Winning')->getResultAsArray(true),
            [   1 => ['B','D'],
                2 => ['A','C']  ]
        );
    }

    public function testSchulzeOfficialExampleResult_1 ()
    {
        $candidateA = $this->election->addCandidate('A');
        $candidateB = $this->election->addCandidate('B');
        $candidateC = $this->election->addCandidate('C');
        $candidateD = $this->election->addCandidate('D');

        $this->election->parseVotes('
            A > C > D * 8
            B > A > D * 2
            C > D > B * 4
            D > B > A * 4
            D > C > B * 3
        ');

        self::assertSame($candidateD, $this->election->getWinner('Schulze Winning'));
    }

    public function testSchulzeOfficialExampleResult_2 ()
    {
        $candidateA = $this->election->addCandidate('A');
        $candidateB = $this->election->addCandidate('B');
        $candidateC = $this->election->addCandidate('C');
        $candidateD = $this->election->addCandidate('D');

        $this->election->parseVotes('
            A > B > C * 3
            C > B > D * 2
            D > A > B * 2
            D > B > C * 2
        ');

        self::assertSame([$candidateB,$candidateD], $this->election->getWinner('Schulze Winning'));
    }

    public function testSchulzeOfficialExampleResult_3 ()
    {
        $candidateA = $this->election->addCandidate('A');
        $candidateB = $this->election->addCandidate('B');
        $candidateC = $this->election->addCandidate('C');
        $candidateD = $this->election->addCandidate('D');

        $this->election->parseVotes('
            A > B > C * 12
            A > D > B * 6
            B > C > D * 9
            C > D > A * 15
            D > B > A * 21
        ');

        self::assertSame($candidateD, $this->election->getWinner('Schulze Winning'));
    }

    public function testSchulzeOfficialExampleResult_4 ()
    {
        $candidateA = $this->election->addCandidate('A');
        $candidateB = $this->election->addCandidate('B');
        $candidateC = $this->election->addCandidate('C');
        $candidateD = $this->election->addCandidate('D');

        $this->election->parseVotes('
            A > C > D * 6
            B > A > D * 1
            C > B > D * 3
            D > B > A * 3
            D > C > B * 2
        ');

        self::assertSame([$candidateA,$candidateD], $this->election->getWinner('Schulze Winning'));
    }

    public function testSchulzeOfficialExampleResult_5 ()
    {
        $candidateA = $this->election->addCandidate('A');
        $candidateB = $this->election->addCandidate('B');
        $candidateC = $this->election->addCandidate('C');
        $candidateD = $this->election->addCandidate('D');
        $candidateE = $this->election->addCandidate('E');
        $candidateF = $this->election->addCandidate('F');

        $this->election->parseVotes('
            A > D > E > B > C * 3
            B > F > E > C > D * 3
            C > A > B > F > D * 4
            D > B > C > E > F * 1
            D > E > F > A > B * 4
            E > C > B > D > F * 2
            F > A > C > D > B * 2
        ');

        # Situation 1
        self::assertSame($candidateA, $this->election->getWinner('Schulze Winning'));

        # Situation 2
        $this->election->parseVotes('A > E > F > C > B * 2');

        self::assertSame($candidateD, $this->election->getWinner('Schulze Winning'));
    }

    public function testSchulzeOfficialExampleResult_6_situation_1 ()
    {
        $candidateA = $this->election->addCandidate('A');
        $candidateB = $this->election->addCandidate('B');
        $candidateC = $this->election->addCandidate('C');
        $candidateD = $this->election->addCandidate('D');

        $this->election->parseVotes('
            A > B > D * 3
            A > D > B * 5
            A > D > C * 1
            B > A > D * 2
            B > D > C * 2
            C > A > B * 4
            C > B > A * 6
            D > B > C * 2
            D > C > A * 5
        ');

        self::assertSame($candidateA, $this->election->getWinner('Schulze Winning'));
    }

    public function testSchulzeOfficialExampleResult_6_situation_2 ()
    {
        $candidateA = $this->election->addCandidate('A');
        $candidateB = $this->election->addCandidate('B');
        $candidateC = $this->election->addCandidate('C');
        $candidateD = $this->election->addCandidate('D');
        $candidateE = $this->election->addCandidate('E');

        $this->election->parseVotes('
            A > B > D > E * 3
            A > D > E > B * 5
            A > D > E > C * 1
            B > A > D > E * 2
            B > D > E > C * 2
            C > A > B > D * 4
            C > B > A > D * 6
            D > B > E > C * 2
            D > E > C > A * 5
        ');

        self::assertSame($candidateB, $this->election->getWinner('Schulze Winning'));
    }

    public function testSchulzeOfficialExampleResult_7 ()
    {
        $candidateA = $this->election->addCandidate('A');
        $candidateB = $this->election->addCandidate('B');
        $candidateC = $this->election->addCandidate('C');
        $candidateD = $this->election->addCandidate('D');

        $this->election->parseVotes('
            A > B > C > D * 6
            A = B * 8
            A = C * 8
            A = C > D * 18
            A = C = D * 8
            B * 40
            C > B > D * 4
            C > D > A * 9
            C = D * 8
            D > A > B * 14
            D > B > C * 11
            D > C > A * 4
        ');

        # Margin
        self::assertSame($candidateA, $this->election->getWinner('Schulze Margin'));

        # Margin
        self::assertSame($candidateB, $this->election->getWinner('Schulze Ratio'));

        # Winning
        self::assertSame($candidateD, $this->election->getWinner('Schulze Winning'));

        # Losing Votes
        // not implemented
    }

    public function testSchulzeOfficialExampleResult_8 ()
    {
        $candidateA = $this->election->addCandidate('A');
        $candidateB = $this->election->addCandidate('B');
        $candidateC = $this->election->addCandidate('C');
        $candidateD = $this->election->addCandidate('D');
        $candidateE = $this->election->addCandidate('E');

        $this->election->parseVotes('
            A > D > B > E * 9
            B > C > A > D * 6
            B > C > D > E * 5
            C > D > B > E * 2
            D > E > C > B * 6
            E > A > C > B * 14
            E > C > A > B * 2
            E > D > A > C * 1
        ');

        self::assertSame($candidateB, $this->election->getWinner('Schulze Winning'));
    }

    public function testSchulzeOfficialExampleResult_9 ()
    {
        $candidateA = $this->election->addCandidate('A');
        $candidateB = $this->election->addCandidate('B');
        $candidateC = $this->election->addCandidate('C');
        $candidateD = $this->election->addCandidate('D');
        $candidateE = $this->election->addCandidate('E');

        $this->election->parseVotes('
            A > D > B > E * 9
            B > A > C > E * 1
            C > B > A > D * 6
            C > D > B > E * 2
            C > D > E > A * 5
            D > E > C > A * 6
            E > B > A > C * 14
            E > B > C > A * 2
        ');

        self::assertSame($candidateE, $this->election->getWinner('Schulze Winning'));
    }

    public function testSchulzeOfficialExampleResult_10 ()
    {
        $candidateA = $this->election->addCandidate('A');
        $candidateB = $this->election->addCandidate('B');
        $candidateC = $this->election->addCandidate('C');
        $candidateD = $this->election->addCandidate('D');
        $candidateE = $this->election->addCandidate('E');

        $this->election->parseVotes('
            A > C > B > E * 5
            A > D > E > C * 5
            B > E > D > A * 8
            C > A > B > E * 3
            C > A > E > B * 7
            C > B > A > D * 2
            D > C > E > B * 7
            E > B > A > D * 8
        ');

        self::assertSame($candidateE, $this->election->getWinner('Schulze Winning'));
    }

    public function testResult_11 ()
    {
        $this->election->addCandidate('Abby');
        $this->election->addCandidate('Brad');
        $this->election->addCandidate('Cora');
        $this->election->addCandidate('Dave');
        $this->election->addCandidate('Erin');

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

        self::assertEquals('Abby',$this->election->getWinner('Schulze Winning'));

        self::assertSame(
            $this->election->getResult('Schulze Winning')->getResultAsArray(true),
            [   1 => 'Abby',
                2 => 'Brad',
                3 => 'Erin',
                4 => 'Dave',
                5 => 'Cora'    ]
        );
    }
}