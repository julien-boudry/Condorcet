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

        for($i=0;$i<42;$i++) {
            $this->election->addVote('Memphis > Nashville > Chattanooga');
        }
        for($i=0;$i<26;$i++) {
            $this->election->addVote('Nashville > Chattanooga > Knoxville');
        }
        for($i=0;$i<15;$i++) {
            $this->election->addVote( 'Chattanooga > Knoxville > Nashville');
        }
        for($i=0;$i<17;$i++) {
            $this->election->addVote( 'Knoxville > Chattanooga > Nashville');
        }


        self::assertEquals($this->election->getResult('KemenyYoung')->getResultAsArray(true),
            [
                1 => 'Nashville',
                2 => 'Chattanooga',
                3 => 'Knoxville',
                4 => 'Memphis'
            ]
        );

        self::assertSame(393, $this->election->getResult('KemenyYoung')->getStats()['bestScore']);

        $this->election->getWinner('KemenyYoung');

        self::assertSame($this->election->getWinner(),$this->election->getWinner('KemenyYoung'));
    }
}