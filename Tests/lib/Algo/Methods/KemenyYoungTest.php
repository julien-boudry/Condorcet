<?php
declare(strict_types=1);
namespace Condorcet;

use Condorcet\CondorcetException;
use Condorcet\Candidate;
use Condorcet\Election;
use Condorcet\Vote;

use PHPUnit\Framework\TestCase;


class KemenyYoungTest extends TestCase
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
            $this->election->getResult('KemenyYoung')->getResultAsArray(true)
        );

        self::assertSame(393, $this->election->getResult('KemenyYoung')->getStats()['bestScore']);

        self::assertSame($this->election->getWinner(),$this->election->getWinner('KemenyYoung'));
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testResult2 ()
    {
        \Condorcet\Algo\Methods\KemenyYoung::$useCache = false;

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

        self::assertEquals(
            [
                1 => 'Elliot',
                2 => 'Roland',
                3 => 'Meredith',
                4 => 'Selden'
            ],
            $this->election->getResult('KemenyYoung')->getResultAsArray(true)
        );
    }

    /**
      * @expectedException Condorcet\CondorcetException
      * @expectedExceptionCode 101
      * @expectedExceptionMessage Kemeny–Young is configured to accept only 8 candidates
      */
    public function testMaxCandidates ()
    {
        for ($i=0; $i < 10; $i++) :
            $this->election->addCandidate();
        endfor;

        $this->election->parseVotes('A');

        $this->election->getWinner('KemenyYoung');
    }

    public function testConflicts ()
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
            $result->getWarning(\Condorcet\Algo\Methods\KemenyYoung::CONFLICT_WARNING_CODE)
        );

        $this->election->addVote('A>B>C');

        $result = $this->election->getResult( 'KemenyYoung' ) ;

        self::assertEquals(
            [],
            $result->getWarning(\Condorcet\Algo\Methods\KemenyYoung::CONFLICT_WARNING_CODE)
        );

        self::assertEquals('A',$this->election->getWinner('KemenyYoung'));
    }
}