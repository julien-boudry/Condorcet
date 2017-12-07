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

        self::assertEquals('B',$this->election->getWinner('Ranked Pairs'));

        self::assertSame(
            [   1 => 'B',
                2 => 'A',
                3 => 'D',
                4 => 'C' ],
            $this->election->getResult('Ranked Pairs')->getResultAsArray(true)
        );
    }

    public function testResult_9 ()
    {
        # Test fix for rare bug

        for ($i=0; $i < 8 ; $i++) { 
            $this->election->addCandidate();
        }

        $this->election->parseVotes('
            A > E > B > H > G > F > D > C * 1
            B > F > E > H > C > A > G > D * 1
            G > F > B > C > D > E > H > A * 1
            H > A > B > F > E > C > D > G * 1
            B > H > A > E > G > F > D > C * 1
            E > D > H > C > B > A > F > G * 1
            C > A > F > B > E > D > H > G * 1
            G > H > D > C > E > F > B > A * 1
            F > E > H > A > B > C > G > D * 1
            D > B > F > C > G > E > A > H * 1
            H > G > A > E > B > C > F > D * 1
            E > D > G > F > A > B > H > C * 1
            C > D > G > A > E > H > B > F * 1
            H > C > B > G > A > D > F > E * 1
            C > B > G > A > D > H > F > E * 1
            B > D > F > H > G > E > A > C * 1
            B > C > E > F > G > H > D > A * 1
            C > G > H > F > D > E > A > B * 1
            E > A > H > C > F > D > G > B * 1
            C > D > G > H > B > A > E > F * 1
            B > D > A > C > G > F > E > H * 1
            C > A > B > G > E > D > H > F * 1
            E > G > H > A > D > C > F > B * 1
            F > G > B > H > E > C > D > A * 1
            A > H > D > C > F > E > B > G * 1
           ');

        self::assertEquals('B',$this->election->getWinner('Ranked Pairs'));
    }

    public function testResult_10 ()
    {
        # Tideman: Independence of Clones as a Criterion for Voting Rules (1987)
        # Example 5

        $this->election->addCandidate('v');
        $this->election->addCandidate('w');
        $this->election->addCandidate('x');
        $this->election->addCandidate('y');
        $this->election->addCandidate('z');

        $this->election->parseVotes('
            v>w>x>y>z*7
            z>y>v>w>x*3
            y>z>w>x>v*6
            w>x>v>z>y*3
            z>x>v>w>y*5
            y>x>v>w>z*3
        ');


        self::assertEquals('v',$this->election->getWinner('Ranked Pairs'));

        self::assertSame(
            [   1 => 'v',
                2 => 'w',
                3 => 'x',
                4 => 'y',
                5 => 'z' ],
            $this->election->getResult('Ranked Pairs')->getResultAsArray(true)
        );
    }

    /**
      * @expectedException Condorcet\CondorcetException
      * @expectedExceptionCode 101
      * @expectedExceptionMessage Ranked Pairs is configured to accept only 40 candidates
      */
    public function testMaxCandidates ()
    {
        for ($i=0; $i < 41; $i++) :
            $this->election->addCandidate();
        endfor;

        $this->election->parseVotes('A');

        $this->election->getWinner('Ranked Pairs');
    }

    // public function testResult_stressTests ()
    // {
    //     $rounds = 1;
    //     $candidates = 332;
    //     $votes = 500;

    //     # Test fix for rare bug
    //     for ($j=0; $j < $rounds; $j++) { 
    //         $this->election = new Election;

    //         for ($i=0; $i < $candidates ; $i++) { 
    //             $this->election->addCandidate();
    //         }


    //         $VoteModel = $this->election->getCandidatesList();
    //         shuffle($VoteModel);

    //         for ($i = 0 ; $i < $votes ; $i++) {
    //             shuffle($VoteModel);
    //             $this->election->addVote( $VoteModel );
    //         }

    //         var_dump($j);

    //         var_dump($this->election->getVotesListAsString());

    //         var_dump($this->election->getResult('Ranked Pairs')->getResultAsArray(true));

    //         self::assertEquals(true,true);
    //     }
    // }

}