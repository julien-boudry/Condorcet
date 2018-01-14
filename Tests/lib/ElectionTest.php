<?php
declare(strict_types=1);
namespace Condorcet;

use Condorcet\CondorcetException;
use Condorcet\Candidate;
use Condorcet\Election;
use Condorcet\Vote;

use PHPUnit\Framework\TestCase;


class ElectionTest extends TestCase
{
    /**
     * @var election1
     */
    private $election1;
    private $election2;

    public function setUp()
    {
        $this->election1 = new Election;

        $this->candidate1 = $this->election1->addCandidate('candidate1');
        $this->candidate2 = $this->election1->addCandidate('candidate2');
        $this->candidate3 = $this->election1->addCandidate('candidate3');

        $this->election1->addVote($this->vote1 = new Vote ([$this->candidate1,$this->candidate2,$this->candidate3]));
        $this->election1->addVote($this->vote2 = new Vote ([$this->candidate2,$this->candidate3,$this->candidate1]));
        $this->election1->addVote($this->vote3 = new Vote ([$this->candidate3,$this->candidate1,$this->candidate2]));
        $this->election1->addVote($this->vote4 = new Vote ([$this->candidate1,$this->candidate2,$this->candidate3]));

        $this->election2 = new Election;
    }

    public function testRemoveVote ()
    {
        self::assertSame([$this->vote2],$this->election1->removeVote($this->vote2));

        self::assertCount(3,$this->election1->getVotesList());
    }

    public function testTagsFilter ()
    {
        $this->vote1->addtags('tag1,tag2,tag3');
        $this->vote2->addtags('tag3,tag4');
        $this->vote3->addtags('tag3,tag4,tag5');
        $this->vote4->addtags('tag1,tag4');

        self::assertSame($this->election1->getVotesList('tag1,tag2',true),[0=>$this->vote1,3=>$this->vote4]);
        self::assertSame($this->election1->countVotes('tag1,tag2',true),2);

        self::assertSame($this->election1->getVotesList('tag1,tag2',false),[1=>$this->vote2,2=>$this->vote3]);
        self::assertSame($this->election1->countVotes('tag1,tag2',false),2);

        $resultGlobal = $this->election1->getResult('Schulze');
        $resultFilter1 = $this->election1->getResult('Schulze',['tags' => 'tag1','withTag' => true]);
        $resultFilter2 = $this->election1->getResult('Schulze',['tags' => 'tag1','withTag' => false]);

        self::assertNotSame($resultGlobal,$resultFilter1);
        self::assertNotSame($resultGlobal,$resultFilter2);
        self::assertNotSame($resultFilter1,$resultFilter2);
    }

    public function testParseCandidates ()
    {
        self::assertSame(4,
        count($this->election2->parseCandidates('Bruckner;   Mahler   ;   
            Debussy
             Bibendum'))
        );

        self::assertSame(['Bruckner','Mahler','Debussy','Bibendum'],
        $this->election2->getCandidatesList(true)
        );
    }

    public function testGetCandidateObjectByName()
    {
        self::assertSame($this->candidate1,$this->election1->getCandidateObjectByName('candidate1'));
        self::assertFalse($this->election1->getCandidateObjectByName('candidate42'));
    }

    /**
      * @expectedException Condorcet\CondorcetException
      * @expectedExceptionCode 12
      * @preserveGlobalState disabled
      * @backupStaticAttributes disabled
      * @runInSeparateProcess
      */
    public function testMaxParseIteration ()
    {
        self::assertSame(42,Election::setMaxParseIteration(42));

        self::assertCount(42,$this->election1->parseVotes('candidate1>candidate2 * 42'));

        self::assertCount(42,$this->election1->parseVotes('candidate1>candidate2 * 42'));

        self::assertSame(null,Election::setMaxParseIteration(null));

        self::assertCount(43,$this->election1->parseVotes('candidate1>candidate2 * 43'));

        self::assertSame(42,Election::setMaxParseIteration(42));

        $this->election1->parseVotes('candidate1>candidate2 * 43');
    }

    /**
      * @expectedException Condorcet\CondorcetException
      * @expectedExceptionCode 16
      * @preserveGlobalState disabled
      * @backupStaticAttributes disabled
      * @runInSeparateProcess
      */
    public function testMaxVoteNumber ()
    {
        self::assertSame(42,Election::setMaxVoteNumber(42));

        self::assertCount(17,$this->election1->parseVotes('candidate1>candidate2 * 17'));

        self::assertCount(21,$this->election1->parseVotes('candidate1>candidate2 * 42'));

        $this->election1->addVote('candidate3');
    }

    public function testGetVotesListAsString ()
    {
        $this->election1 = new Election;

        $this->election1->addCandidate('C');
        $this->election1->addCandidate('B');
        $this->election1->addCandidate('D');
        $this->election1->addCandidate('E');
        $this->election1->addCandidate('A');

        $this->election1->parseVotes('
            D * 6
            A * 6
            E = A = B *3
            A > C = B > E * 5
        ');

        self::assertSame(
        "A > B = C = D = E * 6\n".
        "D > A = B = C = E * 6\n".
        "A > B = C > E > D * 5\n".
        "A = B = E > C = D * 3",
        $this->election1->getVotesListAsString());
    }

    public function testVoteWeight ()
    {
        $election = new Election;

        $election->addCandidate('A');
        $election->addCandidate('B');
        $election->addCandidate('C');
        $election->addCandidate('D');

        $election->parseVotes('
            A > C > D * 6
            B > A > D * 1
            C > B > D * 3
            D > B > A * 3
        ');

        $voteWithWeight = $election->addVote('D > C > B');
        $voteWithWeight->setWeight(2);

        self::assertSame(
            'D > C > B ^2',
            (string) $voteWithWeight
        );

        self::assertSame(
            'D > C > B > A',
            $voteWithWeight->getSimpleRanking($election)
        );

        self::assertNotSame(
            'A = D > C > B',
            $election->getResult('Schulze Winning')->getResultAsString()
        );

        $election->allowVoteWeight(true);

        self::assertSame(
            'D > C > B > A ^2',
            $voteWithWeight->getSimpleRanking($election)
        );

        self::assertSame(
            'A = D > C > B',
            $election->getResult('Schulze Winning')->getResultAsString()
        );

        $election->allowVoteWeight(false);

        self::assertNotSame(
            'A = D > C > B',
            $election->getResult('Schulze Winning')->getResultAsString()
        );

        $election->allowVoteWeight( !$election->isVoteWeightIsAllowed() );

        $election->removeVote($voteWithWeight);

        $election->parseVotes('
            D > C > B ^2 * 1
        ');

        self::assertSame(
            'A = D > C > B',
            $election->getResult('Schulze Winning')->getResultAsString()
        );

        $election->addVote('D > C > B');

        self::assertSame(
'A > C > D > B * 6
C > B > D > A * 3
D > B > A > C * 3
D > C > B > A ^2 * 1
B > A > D > C * 1
D > C > B > A * 1',
            $election->getVotesListAsString()
        );

    }

    public function testJsonVotes ()
    {
        $election = new Election;

        $election->addCandidate('A');
        $election->addCandidate('B');
        $election->addCandidate('C');

        $votes = [];

        $votes[]['vote'] = 'B>C>A';
        $votes[]['vote'] = new \stdClass(); // Invalid Vote
        $votes[]['vote'] = ['C','B','A'];

        $election->jsonVotes(json_encode($votes));

        self::assertSame(
'B > C > A * 1
C > B > A * 1',
            $election->getVotesListAsString()
        );

        $votes = [];

        $votes[0]['vote'] = 'A>B>C';
        $votes[0]['multi'] = 5;
        $votes[0]['tag'] = 'tag1';

        $election->jsonVotes(json_encode($votes));

        self::assertSame(
'A > B > C * 5
B > C > A * 1
C > B > A * 1',
            $election->getVotesListAsString()
        );
        self::assertSame(5,$election->countVotes('tag1'));
    }

    public function testJsonCadidates ()
    {
        $election = new Election;

        $candidates = ['candidate1 ','candidate2'];

        $election->jsonCandidates(json_encode($candidates));

        self::assertSame(2,$election->countCandidates());

        self::assertEquals(['candidate1','candidate2'],$election->getCandidatesList(true));
    }

    /**
      * @expectedException Condorcet\CondorcetException
      * @expectedExceptionCode 15
      */
    public function testJsonVotesWithInvalidJson ()
    {
        self::assertFalse($this->election1->jsonVotes("42"));
        self::assertFalse($this->election1->jsonVotes(42));
        self::assertFalse($this->election1->jsonVotes(false));
        self::assertFalse($this->election1->jsonVotes(true));
        self::assertFalse($$this->election1->jsonVotes(""));
        self::assertFalse($$this->election1->jsonVotes(" "));
        self::assertFalse($$this->election1->jsonVotes([]));
        self::assertFalse($$this->election1->jsonVotes(json_encode(new \stdClass())));
    }

    public function testCachingResult()
    {
        $election = new Election;

        $election->addCandidate('A');
        $election->addCandidate('B');
        $election->addCandidate('C');
        $election->addCandidate('D');

        $election->addVote($vote1 = new Vote ('A > C > D'));

        $result1 = $election->getResult('Schulze');
        self::assertSame($result1,$election->getResult('Schulze'));
    }

    public function testElectionSerializing ()
    {
        $election = new Election;

        $candidateA = $election->addCandidate('A');
        $election->addCandidate('B');
        $election->addCandidate('C');
        $election->addCandidate('D');

        $election->addVote($vote1 = new Vote ('A > C > D'));
        $result1 = $election->getResult('Schulze');

        $election = serialize($election);
        $election = unserialize($election);

        self::assertNotSame($result1,$election->getResult('Schulze'));
        self::assertSame($result1->getResultAsString(),$election->getResult('Schulze')->getResultAsString());

        self::assertNotSame($vote1,$election->getVotesList()[0]);
        self::assertSame($vote1->getSimpleRanking(),$election->getVotesList()[0]->getSimpleRanking());
        self::assertTrue($election->getVotesList()[0]->haveLink($election));
        self::assertFalse($vote1->haveLink($election));
    }

    public function testCloneElection ()
    {
        $this->election1->computeResult();

        $cloneElection = clone $this->election1;

        self::assertNotSame($this->election1->getVotesManager(),$cloneElection->getVotesManager());
        self::assertSame($this->election1->getVotesList(),$cloneElection->getVotesList());

        self::assertSame($this->election1->getCandidatesList(),$cloneElection->getCandidatesList());

        self::assertNotSame($this->election1->getPairwise(false),$cloneElection->getPairwise(false));
        self::assertEquals($this->election1->getPairwise(true),$cloneElection->getPairwise(true));

        self::assertNotSame($this->election1->getTimerManager(),$cloneElection->getTimerManager());

        self::assertSame($this->election1->getVotesList()[0],$cloneElection->getVotesList()[0]);

        self::assertTrue($cloneElection->getVotesList()[0]->haveLink($this->election1));
        self::assertTrue($cloneElection->getVotesList()[0]->haveLink($cloneElection));
    }
}