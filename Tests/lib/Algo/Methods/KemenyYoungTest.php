<?php
declare(strict_types=1);
namespace Condorcet;

use Condorcet\CondorcetException;
use Condorcet\Candidate;
use Condorcet\Election;
use Condorcet\Vote;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Condorcet\Algo\Methods\KemenyYoung
 */
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
}