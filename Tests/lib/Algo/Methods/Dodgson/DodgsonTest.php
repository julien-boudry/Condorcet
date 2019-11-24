<?php
declare(strict_types=1);
namespace CondorcetPHP\Condorcet;


use PHPUnit\Framework\TestCase;


class DodgsonTest extends TestCase
{
    /**
     * @var election
     */
    private $election;

    public function setUp() : void
    {
        $this->election = new Election;
    }

    public function testResult_1 () : void
    {
        # From http://www.cs.wustl.edu/~legrand/rbvote/desc.html

        $CandidateCora = $this->election->addCandidate('Cora');
        $this->election->addCandidate('Abby');
        $this->election->addCandidate('Brad');
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

        self::assertSame($CandidateCora,$this->election->getWinner('DodgsonTideman'));

        self::assertSame(
            [   1 => 'Cora',
                2 => 'Abby',
                3 => 'Brad',
                4 => 'Dave',
                5 => 'Erin'   ],
            $this->election->getResult('DodgsonTideman')->getResultAsArray(true)
        );
    }

    # Require real Dodgson method. This test fail with both approximations.
    // public function testResult_2 () : void
    // {
    //     # From http://dss.in.tum.de/files/brandt-research/dodgson.pdf
           # Table 1

    //     $this->election->addCandidate('A');
    //     $this->election->addCandidate('B');
    //     $this->election->addCandidate('C');
    //     $this->election->addCandidate('D');

    //     $this->election->parseVotes('
    //         D>C>A>B*2
    //         B>C>A>D*2
    //         C>A>B>D*2
    //         D>B>C>A*2
    //         A>B>C>D*2
    //         A>D>B>C*1
    //         D>A>B>C*1
    //     ');

    //     self::assertEquals(
    //         'A', $this->election->getWinner('DodgsonQuick'));
    // }

    public function testResult_3 () : void
    {
        # From http://dss.in.tum.de/files/brandt-research/dodgson.pdf
        # Table 2

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
            'D', $this->election->getWinner('DodgsonQuick'));
    }

    # Require real Dodgson method. This test fail with both approximations.
    // public function testResult_4 () : void
    // {
    //     # From http://dss.in.tum.de/files/brandt-research/dodgson.pdf
           # Table 3

    //     $this->election->addCandidate('A');
    //     $this->election->addCandidate('B');
    //     $this->election->addCandidate('C');
    //     $this->election->addCandidate('D');

    //     $this->election->parseVotes('
    //         C>A>D>B*15
    //         B>D>C>A*9
    //         A>B>D>C*9
    //         A>C>B>D*5
    //         B>A>C>D*5
    //     ');

    //     self::assertEquals(
    //         'A', $this->election->getWinner('DodgsonQuick'));
    // }

    public function testResult_5 () : void
    {
        # From http://dss.in.tum.de/files/brandt-research/dodgson.pdf
        # Table 4

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
            'C', $this->election->getWinner('DodgsonQuick'));
    }

    public function testResult_6 () : void
    {
        # From http://dss.in.tum.de/files/brandt-research/dodgson.pdf
        # Table 5

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
            'D', $this->election->getWinner('DodgsonQuick'));
    }

    public function testResult_7 () : void
    {
        # From http://dss.in.tum.de/files/brandt-research/dodgson.pdf
        # Table 6

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
            'D', $this->election->getWinner('DodgsonQuick'));
    }

    public function testResult_8 () : void
    {
        # From http://dss.in.tum.de/files/brandt-research/dodgson.pdf
        # Table 7

        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');

        $this->election->parseVotes('
            A>B>C*5
            B>C>A*4
            C>A>B*3
        ');

        self::assertEquals(
            'A', $this->election->getWinner('DodgsonQuick'));
    }

    # Require real Dodgson method. This test fail with both approximations.
    // public function testResult_9 () : void
    // {
    //     # From http://dss.in.tum.de/files/brandt-research/dodgson.pdf
           # Table 8

    //     $this->election->addCandidate('A');
    //     $this->election->addCandidate('B');
    //     $this->election->addCandidate('C');
    //     $this->election->addCandidate('Cp');

    //     $this->election->parseVotes('
    //         A>B>C>Cp*5
    //         B>C>Cp>A*4
    //         C>Cp>A>B*3
    //     ');

    //     self::assertEquals(
    //         'B', $this->election->getWinner('DodgsonQuick'));
    // }

    public function testResult_10 () : void
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
            'B', $this->election->getWinner('DodgsonQuick'));

        self::assertEquals(
            'B', $this->election->getWinner('DodgsonTideman'));
    }

    public function testResult_11 () : void
    {
        # From https://www.maa.org/sites/default/files/pdf/cmj_ftp/CMJ/September%202010/3%20Articles/6%2009-229%20Ratliff/Dodgson_CMJ_Final.pdf
        # Figure 2 with Tideman Approximation

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

        self::assertEquals(1, $this->election->getResult('DodgsonTideman')->getStats()['A']['sum_defeat_margin']);
        self::assertEquals(1, $this->election->getResult('DodgsonTideman')->getStats()['B']['sum_defeat_margin']);
        self::assertEquals(4, $this->election->getResult('DodgsonTideman')->getStats()['C']['sum_defeat_margin']);
        self::assertEquals(2, $this->election->getResult('DodgsonTideman')->getStats()['D']['sum_defeat_margin']);

        self::assertSame(
            [   1 => ['A','B'],
                2 => 'D',
                3 => 'C'
            ],
            $this->election->getResult('DodgsonTideman')->getResultAsArray(true)
        );
    }

    public function testResult_12 () : void
    {
        # From https://www.maa.org/sites/default/files/pdf/cmj_ftp/CMJ/September%202010/3%20Articles/6%2009-229%20Ratliff/Dodgson_CMJ_Final.pdf
        # Figure 3 with Tideman Approximation

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

        self::assertEquals(11, $this->election->getResult('DodgsonTideman')->getStats()['A']['sum_defeat_margin']);
        self::assertEquals(11, $this->election->getResult('DodgsonTideman')->getStats()['B']['sum_defeat_margin']);
        self::assertEquals(7, $this->election->getResult('DodgsonTideman')->getStats()['C']['sum_defeat_margin']);
        self::assertEquals(3, $this->election->getResult('DodgsonTideman')->getStats()['D']['sum_defeat_margin']);


        self::assertEquals(
            'D',$this->election->getWinner('DodgsonTideman'));

        self::assertSame(
            [   1 => 'D',
                2 => 'C',
                3 => ['A','B']
            ],
            $this->election->getResult('DodgsonTideman')->getResultAsArray(true)
        );
    }

    public function testResult_13 () : void
    {
        # From https://www.maa.org/sites/default/files/pdf/cmj_ftp/CMJ/September%202010/3%20Articles/6%2009-229%20Ratliff/Dodgson_CMJ_Final.pdf
        # Figure 4

        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');
        $this->election->addCandidate('D');
        $this->election->addCandidate('E');
        $this->election->addCandidate('F');

        $this->election->parseVotes('
            A>B>C>D>E>F*19
            F>A>B>C>D>E*12
            E>D>C>B>F>A*12
            B>A>C>D>E>F*9
            F>E>D>C>B>A*9
            F>B>A>C>D>E*10
            E>D>C>A>F>B*10
            E>B>A>C>D>F*10
            F>D>C>A>E>B*10
            D>B>A>C>E>F*10
            F>E>C>A>D>B*10
        ');

        self::assertEquals(
            3, $this->election->getResult('DodgsonQuick')->getStats()['A']);

        self::assertEquals(
            4, $this->election->getResult('DodgsonQuick')->getStats()['B']);

        self::assertEquals(
            'A', $this->election->getWinner('DodgsonQuick'));
    }

    public function testResult_14 () : void
    {
        # From https://www.maa.org/sites/default/files/pdf/cmj_ftp/CMJ/September%202010/3%20Articles/6%2009-229%20Ratliff/Dodgson_CMJ_Final.pdf
        # Figure 4: each voters add 4 friends.

        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');
        $this->election->addCandidate('D');
        $this->election->addCandidate('E');
        $this->election->addCandidate('F');

        $this->election->parseVotes('
            A>B>C>D>E>F*95
            F>A>B>C>D>E*60
            E>D>C>B>F>A*60
            B>A>C>D>E>F*45
            F>E>D>C>B>A*45
            F>B>A>C>D>E*50
            E>D>C>A>F>B*50
            E>B>A>C>D>F*50
            F>D>C>A>E>B*50
            D>B>A>C>E>F*50
            F>E>C>A>D>B*50
        ');

        self::assertEquals(
            13, $this->election->getResult('DodgsonQuick')->getStats()['A']);

        self::assertEquals(
            12, $this->election->getResult('DodgsonQuick')->getStats()['B']);

        self::assertEquals(
            'B', $this->election->getWinner('DodgsonQuick'));
    }

    public function testResult_15 () : void
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

        self::assertSame($this->election->getWinner(null),$this->election->getWinner('DodgsonQuick'));
    }

}