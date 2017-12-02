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
            [   1 => 'A',
                2 => 'C',
                3 => 'E',
                4 => 'B',
                5 => 'D'    ],
            $this->election->getResult('Ranked Pairs')->getResultAsArray(true)
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


        self::assertEquals(
            [
                1 => 'Nashville',
                2 => 'Chattanooga',
                3 => 'Knoxville',
                4 => 'Memphis'
            ],
            $this->election->getResult('Ranked Pairs')->getResultAsArray(true)
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
            [   1 => 'Brad',
                2 => 'Abby',
                3 => 'Erin',
                4 => 'Dave',
                5 => 'Cora'    ],
            $this->election->getResult('Ranked Pairs')->getResultAsArray(true)
        );
    }

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

        // Not supporting not ranked candidate
        self::assertNotEquals('A',$this->election->getWinner('Ranked Pairs'));

        // Supporting not ranked candidate
        $this->election->setImplicitRanking(false);
        self::assertEquals('A',$this->election->getWinner('Ranked Pairs'));
    }

    public function testResult_5 ()
    {
        # From http://ericgorr.net/condorcet/rankedpairs/example1/

        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');

        $this->election->parseVotes('
            A > B > C * 7
            B > A > C * 5
            C > A > B * 4
            B > C > A * 2
        ');

        self::assertEquals('A',$this->election->getWinner('Ranked Pairs'));

        self::assertSame(
            [   1 => 'A',
                2 => 'B',
                3 => 'C' ],
            $this->election->getResult('Ranked Pairs')->getResultAsArray(true)
        );
    }

    public function testResult_6 ()
    {
        # From http://ericgorr.net/condorcet/rankedpairs/example2/

        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');

        $this->election->parseVotes('
            A > B > C * 40
            B > C > A * 35
            C > A > B * 25
        ');

        self::assertEquals('A',$this->election->getWinner('Ranked Pairs'));

        self::assertSame(
            [   1 => 'A',
                2 => 'B',
                3 => 'C' ],
            $this->election->getResult('Ranked Pairs')->getResultAsArray(true)
        );
    }

    public function testResult_7 ()
    {
        # From http://ericgorr.net/condorcet/rankedpairs/example3/

        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');

        $this->election->parseVotes('
            A > B > C * 7
            B > A > C * 7
            C > A > B * 2
            C > B > A * 2
        ');


        self::assertSame(
            [   1 => ['A','B'],
                2 => 'C' ],
            $this->election->getResult('Ranked Pairs')->getResultAsArray(true)
        );
    }

    public function testResult_8 ()
    {
        # From http://ericgorr.net/condorcet/rankedpairs/example4/

        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');
        $this->election->addCandidate('D');

        $this->election->parseVotes('
            A>D>C>B*12
            B>A>C>D*3
            B>C>A>D*25
            C>B>A>D*21
            D>A>B>C*12
            D>A>C>B*21
            D>B>A>C*6
        ');

        self::assertEquals('B',(string) $this->election->getWinner('Ranked Pairs'));

        self::assertSame(
            [   1 => 'B',
                2 => 'A',
                3 => 'D',
                4 => 'C' ],
            $this->election->getResult('Ranked Pairs')->getResultAsArray(true)
        );
    }  

}