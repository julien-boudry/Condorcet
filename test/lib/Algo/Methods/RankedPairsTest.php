<?php
declare(strict_types=1);
namespace Condorcet;

use Condorcet\CondorcetException;
use Condorcet\Candidate;
use Condorcet\Election;
use Condorcet\Vote;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Condorcet\Algo\Methods\RankedPairs
 */
class RankedPairsTest extends TestCase
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
        # From https://fr.wikipedia.org/wiki/M%C3%A9thode_Condorcet_avec_rangement_des_paires_par_ordre_d%C3%A9croissant

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

        self::assertEquals('A',$this->election->getWinner('Ranked Pairs'));

        self::assertSame(
            $this->election->getResult('Ranked Pairs')->getResultAsArray(true),
            [   1 => 'A',
                2 => 'C',
                3 => 'E',
                4 => 'B',
                5 => 'D'    ]
        );
    }

    public function testResult_2 ()
    {
        # From https://en.wikipedia.org/wiki/Ranked_pairs

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


        self::assertEquals($this->election->getResult('Ranked Pairs')->getResultAsArray(true),
            [
                1 => 'Nashville',
                2 => 'Chattanooga',
                3 => 'Knoxville',
                4 => 'Memphis'
            ]
        );
    }

    public function testResult_3 ()
    {
        # from http://www.cs.wustl.edu/~legrand/rbvote/desc.html

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

        self::assertEquals('Brad',$this->election->getWinner('Ranked Pairs'));

        self::assertSame(
            $this->election->getResult('Ranked Pairs')->getResultAsArray(true),
            [   1 => 'Brad',
                2 => 'Abby',
                3 => 'Erin',
                4 => 'Dave',
                5 => 'Cora'    ]
        );
    }

/*
    # Condorcet do not support ignored candidates. he puts them at the last rank. So results are different.
    public function testResult_4 ()
    {
        # From https://en.wikipedia.org/wiki/Ranked_pairs
        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');

        $this->election->parseVotes('
            A > B * 68
            B > C * 72
            C > A * 52
        ');

        self::assertEquals('A',$this->election->getWinner('Ranked Pairs'));
*/

}