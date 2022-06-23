<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\Algo\Methods\RankedPairs;

use CondorcetPHP\Condorcet\{Candidate, Condorcet, CondorcetUtil, Election, Result, Vote, VoteConstraint};
use CondorcetPHP\Condorcet\Throwable\CandidatesMaxNumberReachedException;
use PHPUnit\Framework\TestCase;

class RankedPairsTest extends TestCase
{
    private readonly Election $election;

    protected function setUp(): void
    {
        $this->election = new Election;
    }

    public function testResult_1(): void
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

        self::assertEquals('A', $this->election->getWinner('Ranked Pairs Winning'));

        $expected = [   1 => 'A',
                        2 => 'C',
                        3 => 'E',
                        4 => 'B',
                        5 => 'D'    ];

        self::assertSame(
            $expected,
            $this->election->getResult('Ranked Pairs Winning')->getResultAsArray(true)
        );

        self::assertSame(
            unserialize('a:2:{s:5:"tally";a:10:{i:0;a:1:{i:0;a:5:{s:4:"from";s:1:"B";s:2:"to";s:1:"D";s:3:"win";i:33;s:8:"minority";i:12;s:6:"margin";i:21;}}i:1;a:1:{i:0;a:5:{s:4:"from";s:1:"E";s:2:"to";s:1:"D";s:3:"win";i:31;s:8:"minority";i:14;s:6:"margin";i:17;}}i:2;a:1:{i:0;a:5:{s:4:"from";s:1:"A";s:2:"to";s:1:"D";s:3:"win";i:30;s:8:"minority";i:15;s:6:"margin";i:15;}}i:3;a:1:{i:0;a:5:{s:4:"from";s:1:"C";s:2:"to";s:1:"B";s:3:"win";i:29;s:8:"minority";i:16;s:6:"margin";i:13;}}i:4;a:1:{i:0;a:5:{s:4:"from";s:1:"D";s:2:"to";s:1:"C";s:3:"win";i:28;s:8:"minority";i:17;s:6:"margin";i:11;}}i:5;a:1:{i:0;a:5:{s:4:"from";s:1:"E";s:2:"to";s:1:"B";s:3:"win";i:27;s:8:"minority";i:18;s:6:"margin";i:9;}}i:6;a:1:{i:0;a:5:{s:4:"from";s:1:"A";s:2:"to";s:1:"C";s:3:"win";i:26;s:8:"minority";i:19;s:6:"margin";i:7;}}i:7;a:1:{i:0;a:5:{s:4:"from";s:1:"B";s:2:"to";s:1:"A";s:3:"win";i:25;s:8:"minority";i:20;s:6:"margin";i:5;}}i:8;a:1:{i:0;a:5:{s:4:"from";s:1:"C";s:2:"to";s:1:"E";s:3:"win";i:24;s:8:"minority";i:21;s:6:"margin";i:3;}}i:9;a:1:{i:0;a:5:{s:4:"from";s:1:"E";s:2:"to";s:1:"A";s:3:"win";i:23;s:8:"minority";i:22;s:6:"margin";i:1;}}}s:4:"arcs";a:7:{i:0;a:2:{s:4:"from";s:1:"B";s:2:"to";s:1:"D";}i:1;a:2:{s:4:"from";s:1:"E";s:2:"to";s:1:"D";}i:2;a:2:{s:4:"from";s:1:"A";s:2:"to";s:1:"D";}i:3;a:2:{s:4:"from";s:1:"C";s:2:"to";s:1:"B";}i:4;a:2:{s:4:"from";s:1:"E";s:2:"to";s:1:"B";}i:5;a:2:{s:4:"from";s:1:"A";s:2:"to";s:1:"C";}i:6;a:2:{s:4:"from";s:1:"C";s:2:"to";s:1:"E";}}}'),
            $this->election->getResult('Ranked Pairs Winning')->getStats()
        );

        self::assertSame(
            $expected,
            $this->election->getResult('Ranked Pairs Margin')->getResultAsArray(true)
        );

        self::assertSame(
            unserialize('a:2:{s:5:"tally";a:10:{i:0;a:1:{i:0;a:5:{s:4:"from";s:1:"B";s:2:"to";s:1:"D";s:3:"win";i:33;s:8:"minority";i:12;s:6:"margin";i:21;}}i:1;a:1:{i:0;a:5:{s:4:"from";s:1:"E";s:2:"to";s:1:"D";s:3:"win";i:31;s:8:"minority";i:14;s:6:"margin";i:17;}}i:2;a:1:{i:0;a:5:{s:4:"from";s:1:"A";s:2:"to";s:1:"D";s:3:"win";i:30;s:8:"minority";i:15;s:6:"margin";i:15;}}i:3;a:1:{i:0;a:5:{s:4:"from";s:1:"C";s:2:"to";s:1:"B";s:3:"win";i:29;s:8:"minority";i:16;s:6:"margin";i:13;}}i:4;a:1:{i:0;a:5:{s:4:"from";s:1:"D";s:2:"to";s:1:"C";s:3:"win";i:28;s:8:"minority";i:17;s:6:"margin";i:11;}}i:5;a:1:{i:0;a:5:{s:4:"from";s:1:"E";s:2:"to";s:1:"B";s:3:"win";i:27;s:8:"minority";i:18;s:6:"margin";i:9;}}i:6;a:1:{i:0;a:5:{s:4:"from";s:1:"A";s:2:"to";s:1:"C";s:3:"win";i:26;s:8:"minority";i:19;s:6:"margin";i:7;}}i:7;a:1:{i:0;a:5:{s:4:"from";s:1:"B";s:2:"to";s:1:"A";s:3:"win";i:25;s:8:"minority";i:20;s:6:"margin";i:5;}}i:8;a:1:{i:0;a:5:{s:4:"from";s:1:"C";s:2:"to";s:1:"E";s:3:"win";i:24;s:8:"minority";i:21;s:6:"margin";i:3;}}i:9;a:1:{i:0;a:5:{s:4:"from";s:1:"E";s:2:"to";s:1:"A";s:3:"win";i:23;s:8:"minority";i:22;s:6:"margin";i:1;}}}s:4:"arcs";a:7:{i:0;a:2:{s:4:"from";s:1:"B";s:2:"to";s:1:"D";}i:1;a:2:{s:4:"from";s:1:"E";s:2:"to";s:1:"D";}i:2;a:2:{s:4:"from";s:1:"A";s:2:"to";s:1:"D";}i:3;a:2:{s:4:"from";s:1:"C";s:2:"to";s:1:"B";}i:4;a:2:{s:4:"from";s:1:"E";s:2:"to";s:1:"B";}i:5;a:2:{s:4:"from";s:1:"A";s:2:"to";s:1:"C";}i:6;a:2:{s:4:"from";s:1:"C";s:2:"to";s:1:"E";}}}'),
            $this->election->getResult('Ranked Pairs Margin')->getStats()
        );
    }

    public function testResult_2(): void
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

        $expected = [   1 => 'Nashville',
                        2 => 'Chattanooga',
                        3 => 'Knoxville',
                        4 => 'Memphis'];


        self::assertSame(
            $expected,
            $this->election->getResult('Ranked Pairs Winning')->getResultAsArray(true)
        );

        self::assertSame(
            unserialize('a:2:{s:5:"tally";a:3:{i:0;a:1:{i:0;a:5:{s:4:"from";s:11:"Chattanooga";s:2:"to";s:9:"Knoxville";s:3:"win";i:83;s:8:"minority";i:17;s:6:"margin";i:66;}}i:1;a:2:{i:0;a:5:{s:4:"from";s:9:"Nashville";s:2:"to";s:9:"Knoxville";s:3:"win";i:68;s:8:"minority";i:32;s:6:"margin";i:36;}i:1;a:5:{s:4:"from";s:9:"Nashville";s:2:"to";s:11:"Chattanooga";s:3:"win";i:68;s:8:"minority";i:32;s:6:"margin";i:36;}}i:2;a:3:{i:0;a:5:{s:4:"from";s:9:"Nashville";s:2:"to";s:7:"Memphis";s:3:"win";i:58;s:8:"minority";i:42;s:6:"margin";i:16;}i:1;a:5:{s:4:"from";s:9:"Knoxville";s:2:"to";s:7:"Memphis";s:3:"win";i:58;s:8:"minority";i:42;s:6:"margin";i:16;}i:2;a:5:{s:4:"from";s:11:"Chattanooga";s:2:"to";s:7:"Memphis";s:3:"win";i:58;s:8:"minority";i:42;s:6:"margin";i:16;}}}s:4:"arcs";a:6:{i:0;a:2:{s:4:"from";s:11:"Chattanooga";s:2:"to";s:9:"Knoxville";}i:1;a:2:{s:4:"from";s:9:"Nashville";s:2:"to";s:9:"Knoxville";}i:2;a:2:{s:4:"from";s:9:"Nashville";s:2:"to";s:11:"Chattanooga";}i:3;a:2:{s:4:"from";s:9:"Nashville";s:2:"to";s:7:"Memphis";}i:4;a:2:{s:4:"from";s:9:"Knoxville";s:2:"to";s:7:"Memphis";}i:5;a:2:{s:4:"from";s:11:"Chattanooga";s:2:"to";s:7:"Memphis";}}}'),
            $this->election->getResult('Ranked Pairs Winning')->getStats()
        );

        self::assertSame(
            $expected,
            $this->election->getResult('Ranked Pairs Margin')->getResultAsArray(true)
        );

        self::assertSame(
            unserialize('a:2:{s:5:"tally";a:3:{i:0;a:1:{i:0;a:5:{s:4:"from";s:11:"Chattanooga";s:2:"to";s:9:"Knoxville";s:3:"win";i:83;s:8:"minority";i:17;s:6:"margin";i:66;}}i:1;a:2:{i:0;a:5:{s:4:"from";s:9:"Nashville";s:2:"to";s:9:"Knoxville";s:3:"win";i:68;s:8:"minority";i:32;s:6:"margin";i:36;}i:1;a:5:{s:4:"from";s:9:"Nashville";s:2:"to";s:11:"Chattanooga";s:3:"win";i:68;s:8:"minority";i:32;s:6:"margin";i:36;}}i:2;a:3:{i:0;a:5:{s:4:"from";s:9:"Nashville";s:2:"to";s:7:"Memphis";s:3:"win";i:58;s:8:"minority";i:42;s:6:"margin";i:16;}i:1;a:5:{s:4:"from";s:9:"Knoxville";s:2:"to";s:7:"Memphis";s:3:"win";i:58;s:8:"minority";i:42;s:6:"margin";i:16;}i:2;a:5:{s:4:"from";s:11:"Chattanooga";s:2:"to";s:7:"Memphis";s:3:"win";i:58;s:8:"minority";i:42;s:6:"margin";i:16;}}}s:4:"arcs";a:6:{i:0;a:2:{s:4:"from";s:11:"Chattanooga";s:2:"to";s:9:"Knoxville";}i:1;a:2:{s:4:"from";s:9:"Nashville";s:2:"to";s:9:"Knoxville";}i:2;a:2:{s:4:"from";s:9:"Nashville";s:2:"to";s:11:"Chattanooga";}i:3;a:2:{s:4:"from";s:9:"Nashville";s:2:"to";s:7:"Memphis";}i:4;a:2:{s:4:"from";s:9:"Knoxville";s:2:"to";s:7:"Memphis";}i:5;a:2:{s:4:"from";s:11:"Chattanooga";s:2:"to";s:7:"Memphis";}}}'),
            $this->election->getResult('Ranked Pairs Margin')->getStats()
        );
    }

    public function testResult_3(): void
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

        $expected =[    1 => 'Brad',
                        2 => 'Abby',
                        3 => 'Erin',
                        4 => 'Dave',
                        5 => 'Cora'];

        self::assertEquals('Brad', $this->election->getWinner('Ranked Pairs Winning'));

        self::assertSame(
            $expected,
            $this->election->getResult('Ranked Pairs Winning')->getResultAsArray(true)
        );

        self::assertSame(
            $expected,
            $this->election->getResult('Ranked Pairs Margin')->getResultAsArray(true)
        );
    }

    public function testResult_4(): void
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
        self::assertNotEquals('A', $this->election->getWinner('Ranked Pairs Winning'));

        // Supporting not ranked candidate
        $this->election->setImplicitRanking(false);
        self::assertEquals('A', $this->election->getWinner('Ranked Pairs Winning'));
    }

    public function testResult_5(): void
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

        $expected = [   1 => 'A',
                        2 => 'B',
                        3 => 'C' ];

        self::assertEquals('A', $this->election->getWinner('Ranked Pairs Winning'));

        self::assertSame(
            $expected,
            $this->election->getResult('Ranked Pairs Winning')->getResultAsArray(true)
        );

        self::assertSame(
            $expected,
            $this->election->getResult('Ranked Pairs Margin')->getResultAsArray(true)
        );
    }

    public function testResult_6(): void
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

        $expected = [   1 => 'A',
                        2 => 'B',
                        3 => 'C' ];

        self::assertEquals('A', $this->election->getWinner('Ranked Pairs Winning'));

        self::assertSame(
            $expected,
            $this->election->getResult('Ranked Pairs Winning')->getResultAsArray(true)
        );

        self::assertSame(
            $expected,
            $this->election->getResult('Ranked Pairs Margin')->getResultAsArray(true)
        );
    }

    public function testResult_7(): void
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

        $expected =  [   1 => ['A','B'],
                         2 => 'C' ];

        self::assertSame(
            $expected,
            $this->election->getResult('Ranked Pairs Winning')->getResultAsArray(true)
        );

        self::assertSame(
            $expected,
            $this->election->getResult('Ranked Pairs Margin')->getResultAsArray(true)
        );
    }

    public function testResult_8(): void
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

        $expected = [   1 => 'B',
                        2 => 'A',
                        3 => 'D',
                        4 => 'C' ];

        self::assertEquals('B', $this->election->getWinner('Ranked Pairs Winning'));

        self::assertSame(
            $expected,
            $this->election->getResult('Ranked Pairs Winning')->getResultAsArray(true)
        );

        self::assertSame(
            unserialize('a:2:{s:5:"tally";a:4:{i:0;a:1:{i:0;a:5:{s:4:"from";s:1:"A";s:2:"to";s:1:"D";s:3:"win";i:61;s:8:"minority";i:39;s:6:"margin";i:22;}}i:1;a:1:{i:0;a:5:{s:4:"from";s:1:"B";s:2:"to";s:1:"A";s:3:"win";i:55;s:8:"minority";i:45;s:6:"margin";i:10;}}i:2;a:2:{i:0;a:5:{s:4:"from";s:1:"A";s:2:"to";s:1:"C";s:3:"win";i:54;s:8:"minority";i:46;s:6:"margin";i:8;}i:1;a:5:{s:4:"from";s:1:"C";s:2:"to";s:1:"B";s:3:"win";i:54;s:8:"minority";i:46;s:6:"margin";i:8;}}i:3;a:2:{i:0;a:5:{s:4:"from";s:1:"D";s:2:"to";s:1:"B";s:3:"win";i:51;s:8:"minority";i:49;s:6:"margin";i:2;}i:1;a:5:{s:4:"from";s:1:"D";s:2:"to";s:1:"C";s:3:"win";i:51;s:8:"minority";i:49;s:6:"margin";i:2;}}}s:4:"arcs";a:3:{i:0;a:2:{s:4:"from";s:1:"A";s:2:"to";s:1:"D";}i:1;a:2:{s:4:"from";s:1:"B";s:2:"to";s:1:"A";}i:2;a:2:{s:4:"from";s:1:"D";s:2:"to";s:1:"C";}}}'),
            $this->election->getResult('Ranked Pairs Winning')->getStats()
        );

        self::assertSame(
            $expected,
            $this->election->getResult('Ranked Pairs Margin')->getResultAsArray(true)
        );

        self::assertSame(
            unserialize('a:2:{s:5:"tally";a:4:{i:0;a:1:{i:0;a:5:{s:4:"from";s:1:"A";s:2:"to";s:1:"D";s:3:"win";i:61;s:8:"minority";i:39;s:6:"margin";i:22;}}i:1;a:1:{i:0;a:5:{s:4:"from";s:1:"B";s:2:"to";s:1:"A";s:3:"win";i:55;s:8:"minority";i:45;s:6:"margin";i:10;}}i:2;a:2:{i:0;a:5:{s:4:"from";s:1:"A";s:2:"to";s:1:"C";s:3:"win";i:54;s:8:"minority";i:46;s:6:"margin";i:8;}i:1;a:5:{s:4:"from";s:1:"C";s:2:"to";s:1:"B";s:3:"win";i:54;s:8:"minority";i:46;s:6:"margin";i:8;}}i:3;a:2:{i:0;a:5:{s:4:"from";s:1:"D";s:2:"to";s:1:"B";s:3:"win";i:51;s:8:"minority";i:49;s:6:"margin";i:2;}i:1;a:5:{s:4:"from";s:1:"D";s:2:"to";s:1:"C";s:3:"win";i:51;s:8:"minority";i:49;s:6:"margin";i:2;}}}s:4:"arcs";a:3:{i:0;a:2:{s:4:"from";s:1:"A";s:2:"to";s:1:"D";}i:1;a:2:{s:4:"from";s:1:"B";s:2:"to";s:1:"A";}i:2;a:2:{s:4:"from";s:1:"D";s:2:"to";s:1:"C";}}}'),
            $this->election->getResult('Ranked Pairs Winning')->getStats()
        );
    }

    public function testResult_9(): void
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

        self::assertEquals('B', $this->election->getWinner('Ranked Pairs Winning'));
    }

    public function testResult_10(): void
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


        self::assertEquals('v', $this->election->getWinner('Ranked Pairs Winning'));

        $expected = [   1 => 'v',
                        2 => 'w',
                        3 => 'x',
                        4 => 'y',
                        5 => 'z' ];

        self::assertSame(
            $expected,
            $this->election->getResult('Ranked Pairs Winning')->getResultAsArray(true)
        );

        self::assertSame(
            $expected,
            $this->election->getResult('Ranked Pairs Margin')->getResultAsArray(true)
        );
    }

    public function testResult_11(): void
    {
        # From http://rangevoting.org/WinningVotes.htmls

        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');

        $this->election->parseVotes('
            B > C > A * 9
            C = A > B * 6
            A > B > C * 5
        ');

        self::assertNotEquals(
            $this->election->getResult('Ranked Pairs Winning')->getResultAsArray(true),
            $this->election->getResult('Ranked Pairs Margin')->getResultAsArray(true)
        );
    }

    public function testMaxCandidates(): never
    {
        $this->expectException(CandidatesMaxNumberReachedException::class);
        $this->expectExceptionMessage("Maximum number of candidates reached: The method 'Ranked Pairs Winning' is configured to accept only 60 candidates");

        for ($i=0; $i < 61; $i++) {
            $this->election->addCandidate();
        }

        $this->election->parseVotes('A');

        $this->election->getWinner('Ranked Pairs Winning');
    }

    // public function testResult_stressTests (): void
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
    //         \shuffle($VoteModel);

    //         for ($i = 0 ; $i < $votes ; $i++) {
    //             \shuffle($VoteModel);
    //             $this->election->addVote( $VoteModel );
    //         }

    //         \var_dump($j);

    //         \var_dump($this->election->getVotesListAsString());

    //         \var_dump($this->election->getResult('Ranked Pairs Winning')->getResultAsArray(true));

    //         self::assertEquals(true,true);
    //     }
    // }
}
