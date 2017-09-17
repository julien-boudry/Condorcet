<?php
declare(strict_types=1);
namespace Condorcet;

use Condorcet\CondorcetException;
use Condorcet\Candidate;
use Condorcet\Election;
use Condorcet\Vote;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Condorcet\Algo\Methods\Dodgson
 */
class DodgsonTest extends TestCase
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
        # From http://www.cs.wustl.edu/~legrand/rbvote/desc.html

        $CandidateAbby = $this->election->addCandidate('Abby');
        $CandidateBrad = $this->election->addCandidate('Brad');
        $CandidateCora = $this->election->addCandidate('Cora');
        $CandidateDave = $this->election->addCandidate('Dave');
        $CandidateErin = $this->election->addCandidate('Erin');

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

        self::assertSame($CandidateCora,$this->election->getWinner('Dodgson'));

        self::assertSame(
            $this->election->getResult('Dodgson')->getResultAsArray(true),
            [   1 => 'Cora',
                2 => 'Abby',
                3 => 'Brad',
                4 => 'Dave',
                5 => 'Erin'   ]
        );
    }
}