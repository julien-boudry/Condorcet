<?php
declare(strict_types=1);
namespace Condorcet;



use PHPUnit\Framework\TestCase;


class ResultTest extends TestCase
{
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

    public function testgetCondorcetElectionGeneratorVersion ()
    {
        $this->election1->addCandidate('B');
        $this->election1->addCandidate('A');
        $this->election1->addCandidate('C');

        $this->election1->addVote('C > B > A');

        self::assertSame(Condorcet::getVersion(),$this->election1->getResult('Ranked Pairs')->getCondorcetElectionGeneratorVersion());
    }

    public function testResultClassgenerator ()
    {
        $this->election1->addCandidate('B');
        $this->election1->addCandidate('A');
        $this->election1->addCandidate('C');

        $this->election1->addVote('C > B > A');

        self::assertSame(__NAMESPACE__.'\Algo\Methods\RankedPairsMargin',$this->election1->getResult('Ranked Pairs')->getClassGenerator());
    }

    public function testMethod ()
    {
        $this->election1->addCandidate('B');
        $this->election1->addCandidate('A');
        $this->election1->addCandidate('C');

        $this->election1->addVote('C > B > A');

        self::assertSame('Ranked Pairs Margin',$this->election1->getResult('Ranked Pairs')->getMethod());
    }

    public function testGetBuildTimeStamp ()
    {
        $this->election1->addCandidate('B');
        $this->election1->addCandidate('A');
        $this->election1->addCandidate('C');

        $this->election1->addVote('C > B > A');

        self::assertTrue(is_float($this->election1->getResult('Ranked Pairs')->getBuildTimeStamp()));
    }

    public function testGetWinner ()
    {
        $this->election1->addCandidate('a');
        $this->election1->addCandidate('b');
        $this->election1->addCandidate('c');

        $this->election1->parseVotes('
            a > c > b * 23
            b > c > a * 19
            c > b > a * 16
            c > a > b * 2
        ');

        self::assertEquals('c', $this->election1->getResult()->getWinner());
        self::assertEquals('c', $this->election1->getResult()->getCondorcetWinner());

    }

    public function testGetLoser ()
    {
        $this->election1->addCandidate('Memphis');
        $this->election1->addCandidate('Nashville');
        $this->election1->addCandidate('Knoxville');
        $this->election1->addCandidate('Chattanooga');

        $this->election1->parseVotes('
            Memphis > Nashville > Chattanooga * 42
            Nashville > Chattanooga > Knoxville * 26
            Chattanooga > Knoxville > Nashville * 15
            Knoxville > Chattanooga > Nashville * 17
        ');

        self::assertEquals('Memphis', $this->election1->getResult()->getLoser());
        self::assertEquals('Memphis', $this->election1->getResult()->getCondorcetLoser());
    }

    public function testgetOriginalArrayWithString ()
    {
        $this->election1->addCandidate('a');
        $this->election1->addCandidate('b');
        $this->election1->addCandidate('c');

        $this->election1->addVote('a > b > c');

        self::assertEquals(
            [   1 => 'a',
                2 => 'b',
                3 => 'c',
            ],
            $this->election1->getResult()->getOriginalArrayWithString()
        );
    }

    /**
      * @expectedException Condorcet\CondorcetException
      */
    public function testOffsetSet ()
    {
        $this->election1->addCandidate('B');
        $this->election1->addCandidate('A');
        $this->election1->addCandidate('C');

        $this->election1->addVote('C > B > A');

        $result = $this->election1->getResult('Schulze');

        $result[] = 42;
    }

    /**
      * @expectedException Condorcet\CondorcetException
      */
    public function testOffUnset ()
    {
        $this->election1->addCandidate('B');
        $this->election1->addCandidate('A');
        $this->election1->addCandidate('C');

        $this->election1->addVote('C > B > A');

        $result = $this->election1->getResult('Schulze');

        self::assertSame(true,isset($result[1]));

        unset($result[1]);
    }

    public function testIterator ()
    {
        $this->election1->addCandidate('B');
        $this->election1->addCandidate('A');
        $this->election1->addCandidate('C');

        $vote = $this->election1->addVote('C > B > A');

        $result = $this->election1->getResult('Schulze');

        foreach ($result as $key => $value) :
            self::assertSame($vote->getRanking()[$key],$value);
        endforeach;
    }

}