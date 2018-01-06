<?php
declare(strict_types=1);
namespace Condorcet;

use Condorcet\Election;

use PHPUnit\Framework\TestCase;


class ResultTest extends TestCase
{
    /**
     * @var election1
     */
    private $election1;

    public function setUp()
    {
        $this->election1 = new Election;
    }

    public function testGetResultAsString ()
    {
        $this->election1->addCandidate('B');
        $this->election1->addCandidate('A');
        $this->election1->addCandidate('C');

        $this->election1->parseVotes('
            B > A > C * 7
            A > B > C * 7
            C > A > B * 2
            C > B > A * 2
        ');


        self::assertSame(
            'A = B > C',
            $this->election1->getResult('Ranked Pairs')->getResultAsString()
        );
    }

}