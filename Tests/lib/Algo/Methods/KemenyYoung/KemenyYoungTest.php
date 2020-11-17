<?php
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\Algo\Methods\KemenyYoung;

use CondorcetPHP\Condorcet\{Candidate, Condorcet, CondorcetUtil, Election, Result, Vote, VoteConstraint};
use CondorcetPHP\Condorcet\Algo\Methods\KemenyYoung\KemenyYoung;
use PHPUnit\Framework\TestCase;

class KemenyYoungTest extends TestCase
{
    /**
     * @var election
     */
    private  Election $election;

    public function setUp() : void
    {
        $this->election = new Election;
    }

    public function testResult_1 () : void
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


        self::assertSame(
            [
                1 => 'Nashville',
                2 => 'Chattanooga',
                3 => 'Knoxville',
                4 => 'Memphis'
            ],
            $this->election->getResult('KemenyYoung')->getResultAsArray(true)
        );

        self::assertSame(393, $this->election->getResult('KemenyYoung')->getStats()['bestScore']);

        self::assertSame($this->election->getWinner(),$this->election->getWinner('KemenyYoung'));
    }

    /**
     * @preserveGlobalState disabled
     */
    public function testResult2 () : void
    {
        $this->election->parseCandidates('Elliot;Roland;Meredith;Selden');

        $this->election->parseVotes('
            Elliot > Roland ^30
            Elliot > Meredith ^60
            Elliot > Selden ^60
            Roland > Meredith ^70
            Roland > Selden ^60
            Meredith > Selden ^40
        ');

        $this->election->setImplicitRanking(false);

        self::assertSame(
            [
                1 => 'Elliot',
                2 => 'Roland',
                3 => 'Meredith',
                4 => 'Selden'
            ],
            $this->election->getResult('KemenyYoung')->getResultAsArray(true)
        );
    }

    public function testMaxCandidates () : void
    {
        $this->expectException(\CondorcetPHP\Condorcet\Throwable\CondorcetException::class);
        $this->expectExceptionCode(101);
        $this->expectExceptionMessage('Kemeny–Young is configured to accept only 8 candidates');

        for ($i=0; $i < 10; $i++) :
            $this->election->addCandidate();
        endfor;

        $this->election->parseVotes('A');

        $this->election->getWinner('KemenyYoung');
    }

    public function testConflicts () : void
    {
        $this->election->parseCandidates('A;B;C');

        $this->election->parseVotes('
            A>B>C;
            B>C>A;
            C>A>B');

        $result = $this->election->getResult( 'KemenyYoung' ) ;

        self::assertEquals(
            [ 0 => [
                'type' => 42,
                'msg' => '3;5'
              ]
            ],
            $result->getWarning(\CondorcetPHP\Condorcet\Algo\Methods\KemenyYoung\KemenyYoung::CONFLICT_WARNING_CODE)
        );

        self::assertEquals(
            [ 0 => [
                'type' => 42,
                'msg' => '3;5'
              ]
            ],
            $result->getWarning()
        );

        $this->election->addVote('A>B>C');

        $result = $this->election->getResult( 'KemenyYoung' ) ;

        self::assertEquals(
            [],
            $result->getWarning(\CondorcetPHP\Condorcet\Algo\Methods\KemenyYoung\KemenyYoung::CONFLICT_WARNING_CODE)
        );

        self::assertEquals('A',$this->election->getWinner('KemenyYoung'));
    }

    public function testWritePermutation () : void
    {
        $this->election->parseCandidates('A;B');

        $this->election->parseVotes('
            A>B;
            B>A;
            A>B');

        KemenyYoung::$devWriteCache = true;
        $this->election->getResult( 'KemenyYoung' ) ;
        KemenyYoung::$devWriteCache = false;

        self::assertSame(true,true);
    }
}