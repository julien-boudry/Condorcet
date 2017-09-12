<?php
declare(strict_types=1);
namespace Condorcet;

use Condorcet\CondorcetException;
use Condorcet\Candidate;
use Condorcet\Election;
use Condorcet\Vote;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Condorcet\Algo\Methods\Minimax_Core
 */
class MinimaxTest extends TestCase
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

        self::assertSame($this->election->getWinner(),$this->election->getWinner('Minimax Winning'));

        self::assertSame($this->election->getWinner(),$this->election->getWinner('Minimax Margin'));

        self::assertSame($this->election->getWinner(),$this->election->getWinner('Minimax Opposition'));

        $expectedRanking = [
            1 => 'Nashville',
            2 => 'Memphis',
            3 => 'Chattanooga',
            4 => 'Knoxville'
        ];

        self::assertSame(
            $expectedRanking,
            $this->election->getResult('Minimax Winning')->getResultAsArray(true)
        );

        self::assertSame(
            $expectedRanking,
            $this->election->getResult('Minimax Margin')->getResultAsArray(true)
        );

        self::assertSame(
            $expectedRanking,
            $this->election->getResult('Minimax Opposition')->getResultAsArray(true)
        );
        
    }

    public function testResult_2 ()
    {
        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');

        $this->election->parseVotes('
            A = C > B * 4
            A > C > B * 47
            C > B > A * 43
            B > A = C * 6
        ');

        self::assertSame($this->election->getWinner(),$this->election->getWinner('Minimax Winning'));

        self::assertSame($this->election->getWinner(),$this->election->getWinner('Minimax Margin'));

        self::assertEquals('C',$this->election->getWinner('Minimax Opposition'));

        $expectedRanking1 = [
            1 => 'A',
            2 => 'C',
            3 => 'B'
        ];

        self::assertSame(
            $expectedRanking1,
            $this->election->getResult('Minimax Winning')->getResultAsArray(true)
        );

        self::assertSame(
            $expectedRanking1,
            $this->election->getResult('Minimax Margin')->getResultAsArray(true)
        );

        self::assertSame(
            [   1 => 'C',
                2 => 'A',
                3 => 'B'    ],
            $this->election->getResult('Minimax Opposition')->getResultAsArray(true)
        );
    }

    public function testResult_3 ()
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

        self::assertEquals('Cora',$this->election->getWinner('Minimax Winning'));
    }
}