<?php
declare(strict_types=1);
namespace CondorcetPHP\Condorcet;


use PHPUnit\Framework\TestCase;
use CondorcetPHP\Condorcet\Throwable\CondorcetException;


class ElectionTest extends TestCase
{
    /**
     * @var election1
     */
    private $election1;
    private $election2;

    public function setUp() : void
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

    public function testRemoveVotes ()
    {
        self::assertTrue($this->election1->removeVotes($this->vote2));

        self::assertCount(3,$this->election1->getVotesList());
    }

    public function testRemoveVotesByTags ()
    {
        $this->vote1->addtags('tag1,tag2,tag3');
        $this->vote2->addtags('tag3,tag4');
        $this->vote3->addtags('tag3,tag4,tag5');
        $this->vote4->addtags('tag1,tag4');

        self::assertCount(3,$r = $this->election1->removeVotesByTags(['tag1','tag5']));

        self::assertSame([$this->vote1,$this->vote3,$this->vote4],$r);

        self::assertSame([1 => $this->vote2],$this->election1->getVotesList());

        $this->setUp();

        $this->vote1->addtags('tag1,tag2,tag3');
        $this->vote2->addtags('tag3,tag4');
        $this->vote3->addtags('tag3,tag4,tag5');
        $this->vote4->addtags('tag1,tag4');

        self::assertCount(1,$r = $this->election1->removeVotesByTags('tag1,tag5', false));

        self::assertSame([$this->vote2],$r);

        self::assertSame([0 => $this->vote1, 2=> $this->vote3, 3 => $this->vote4],$this->election1->getVotesList());
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
        $this->election2->getCandidatesListAsString()
        );
    }

    public function testgetCandidateObjectFromName()
    {
        self::assertSame($this->candidate1,$this->election1->getCandidateObjectFromName('candidate1'));
        self::assertNull($this->election1->getCandidateObjectFromName('candidate42'));
    }

    /**
      * @preserveGlobalState disabled
      * @backupStaticAttributes disabled
      * @runInSeparateProcess
      */
    public function testMaxParseIteration1 ()
    {
        $this->expectException(\CondorcetPHP\Condorcet\Throwable\CondorcetException::class);
        $this->expectExceptionCode(12);

        self::assertSame(42,Election::setMaxParseIteration(42));

        self::assertCount(42,$this->election1->parseVotes('candidate1>candidate2 * 42'));

        self::assertCount(42,$this->election1->parseVotes('candidate1>candidate2 * 42'));

        self::assertSame(null,Election::setMaxParseIteration(null));

        self::assertCount(43,$this->election1->parseVotes('candidate1>candidate2 * 43'));

        self::assertSame(42,Election::setMaxParseIteration(42));

        $this->election1->parseVotes('candidate1>candidate2 * 43');
    }

    /**
      * @preserveGlobalState disabled
      * @backupStaticAttributes disabled
      * @runInSeparateProcess
      */
    public function testMaxParseIteration2 ()
    {
        self::expectException(\CondorcetPHP\Condorcet\Throwable\CondorcetException::class);
        self::expectExceptionCode(12);

        self::assertSame(42,Election::setMaxParseIteration(42));

        self::assertCount(42,$this->election1->parseVotes('
            candidate1>candidate2 * 21
            candidate1>candidate2 * 21
            candidate1>candidate2 * 21
        '));
    }

    /**
      * @preserveGlobalState disabled
      * @backupStaticAttributes disabled
      * @runInSeparateProcess
      */
    public function testMaxParseIteration3 ()
    {
        self::expectException(\CondorcetPHP\Condorcet\Throwable\CondorcetException::class);
        self::expectExceptionCode(12);

        self::assertSame(2,Election::setMaxParseIteration(2));

        $this->election2->parseCandidates('candidate1;candidate2');

        $this->election2->parseCandidates('candidate3;candidate4');

        self::assertSame(null,Election::setMaxParseIteration(null));

        $this->election2->parseCandidates('candidate5;candidate6;candidate7');

        self::assertSame(2,Election::setMaxParseIteration(2));

        $this->election2->parseCandidates('candidate8;candidate9;candidate10');
    }

    /**
      * @preserveGlobalState disabled
      * @backupStaticAttributes disabled
      * @runInSeparateProcess
      */
    public function testMaxVoteNumber ()
    {
        $this->expectException(\CondorcetPHP\Condorcet\Throwable\CondorcetException::class);
        $this->expectExceptionCode(16);

        $election = new Election;
        self::assertCount(3,$election->parseCandidates('candidate1;candidate2;candidate3'));

        self::assertSame(42,Election::setMaxVoteNumber(42));

        self::assertCount(21,$election->parseVotes('candidate1>candidate2 * 21'));

        try {
            $election->parseVotes('candidate1>candidate2 * 42');
            self::assertSame(true,false);
        } catch (CondorcetException $e) {
            self::assertSame(16,$e->getCode());
        }

        self::assertSame(21,$election->countVotes());

        $election->parseVotes('candidate1 * 21');

        self::assertSame(42,$election->countVotes());

        self::assertSame(null,Election::setMaxVoteNumber(null));

        $election->addVote('candidate3');

        self::assertSame(42,Election::setMaxVoteNumber(42));

        try {
            $election->addVote('candidate3');
        } catch (CondorcetException $e) {}

        self::assertSame(null,Election::setMaxVoteNumber(null));

        throw $e;
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
            Y > Z
        ');

        self::assertSame(
        "A > B = C = D = E * 6\n".
        "D > A = B = C = E * 6\n".
        "A > B = C > E > D * 5\n".
        "A = B = E > C = D * 3\n".
        "A = B = C = D = E * 1",
        $this->election1->getVotesListAsString());

        $this->election1->setImplicitRanking(false);

        self::assertSame(
        "A * 6\n".
        "D * 6\n".
        "A > B = C > E * 5\n".
        "A = B = E * 3\n".
        "{{EMPTY_VOTE_IN_CONTEXT}} * 1",
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
            14,
            $election->sumVotesWeight()
        );

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
            15,
            $election->sumVotesWeight()
        );

        self::assertSame(
            'D > C > B > A ^2',
            $voteWithWeight->getSimpleRanking($election)
        );

        self::assertSame(
            'A = D > C > B',
            $election->getResult('Schulze Winning')->getResultAsString()
        );

        $election->allowVoteWeight(false);

        self::assertSame(
            14,
            $election->sumVotesWeight()
        );

        self::assertNotSame(
            'A = D > C > B',
            $election->getResult('Schulze Winning')->getResultAsString()
        );

        $election->allowVoteWeight( !$election->isVoteWeightAllowed() );

        $election->removeVotes($voteWithWeight);

        self::assertSame(
            13,
            $election->sumVotesWeight()
        );

        $election->parseVotes('
            D > C > B ^2 * 1
        ');

        self::assertSame(
            15,
            $election->sumVotesWeight()
        );

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

    public function testaddVotesFromJson ()
    {
        self::expectException(\CondorcetPHP\Condorcet\Throwable\CondorcetException::class);
        self::expectExceptionCode(15);

        $election = new Election;

        $election->addCandidate('A');
        $election->addCandidate('B');
        $election->addCandidate('C');

        $votes = [];

        $votes[]['vote'] = 'B>C>A';
        $votes[]['vote'] = new \stdClass(); // Invalid Vote
        $votes[]['vote'] = ['C','B','A'];

        $election->addVotesFromJson(json_encode($votes));

        self::assertSame(
'B > C > A * 1
C > B > A * 1',
            $election->getVotesListAsString()
        );

        $votes = [];

        $votes[0]['vote'] = 'A>B>C';
        $votes[0]['multi'] = 5;
        $votes[0]['tag'] = 'tag1';

        $election->addVotesFromJson(json_encode($votes));

        self::assertSame(
'A > B > C * 5
B > C > A * 1
C > B > A * 1',
            $election->getVotesListAsString()
        );
        self::assertSame(5,$election->countVotes('tag1'));

        $election->addVotesFromJson(json_encode($votes).'{42');
    }

    public function testaddCandidatesFromJson ()
    {
        self::expectException(\CondorcetPHP\Condorcet\Throwable\CondorcetException::class);
        self::expectExceptionCode(15);

        $election = new Election;

        $candidates = ['candidate1 ','candidate2'];

        $election->addCandidatesFromJson(json_encode($candidates));

        self::assertSame(2,$election->countCandidates());

        self::assertEquals(['candidate1','candidate2'],$election->getCandidatesListAsString());

        $election->addCandidatesFromJson(json_encode(['candidate3']).'{42');
    }

    public function testaddVotesFromJsonWithInvalidJson ()
    {
        $this->expectException(\CondorcetPHP\Condorcet\Throwable\CondorcetException::class);
        $this->expectExceptionCode(15);

        self::assertFalse($this->election1->addVotesFromJson("42"));
        self::assertFalse($this->election1->addVotesFromJson(42));
        self::assertFalse($this->election1->addVotesFromJson(false));
        self::assertFalse($this->election1->addVotesFromJson(true));
        self::assertFalse($$this->election1->addVotesFromJson(""));
        self::assertFalse($$this->election1->addVotesFromJson(" "));
        self::assertFalse($$this->election1->addVotesFromJson([]));
        self::assertFalse($$this->election1->addVotesFromJson(json_encode(new \stdClass())));
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

        $election->addCandidate('A');
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

        self::assertNotSame($this->election1->getPairwise(),$cloneElection->getPairwise());
        self::assertEquals($this->election1->getExplicitPairwise(),$cloneElection->getExplicitPairwise());

        self::assertNotSame($this->election1->getTimerManager(),$cloneElection->getTimerManager());

        self::assertSame($this->election1->getVotesList()[0],$cloneElection->getVotesList()[0]);

        self::assertTrue($cloneElection->getVotesList()[0]->haveLink($this->election1));
        self::assertTrue($cloneElection->getVotesList()[0]->haveLink($cloneElection));
    }

    public function testGetCandidateObjectFromKey ()
    {
        self::assertSame($this->candidate2,$this->election1->getCandidateObjectFromKey(1));

        self::assertSame(null,$this->election1->getCandidateObjectFromKey(42));
    }

    public function testElectionState1 ()
    {
        self::expectException(\CondorcetPHP\Condorcet\Throwable\CondorcetException::class);
        self::expectExceptionCode(2);

        $this->election1->addCandidate('candidate4');
    }

    public function testElectionState2 ()
    {
        self::expectException(\CondorcetPHP\Condorcet\Throwable\CondorcetException::class);
        self::expectExceptionCode(2);

        $this->election1->removeCandidates('candidate4');
    }

    public function testAddSameVote ()
    {
        self::expectException(\CondorcetPHP\Condorcet\Throwable\CondorcetException::class);
        self::expectExceptionCode(31);

        $this->election1->addVote($this->vote1);
    }

    public function testState1 ()
    {
        self::expectException(\CondorcetPHP\Condorcet\Throwable\CondorcetException::class);
        self::expectExceptionCode(20);

        $election = new Election;
        $election->setStateTovote();
    }

    public function testState2 ()
    {
        self::expectException(\CondorcetPHP\Condorcet\Throwable\CondorcetException::class);
        self::expectExceptionCode(6);

        $election = new Election;
        $election->getResult();
    }

    public function testDestroy ()
    {
        $this->candidate1 = null;
        $this->candidate2 = null;
        $this->candidate3 = null;

        $this->election1 = null;

        self::assertTrue(true);
    }

}