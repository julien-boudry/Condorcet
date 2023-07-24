<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests;

use CondorcetPHP\Condorcet\ElectionProcess\ElectionState;
use CondorcetPHP\Condorcet\Candidate;
use CondorcetPHP\Condorcet\Condorcet;
use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Vote;
use CondorcetPHP\Condorcet\Throwable\CandidateDoesNotExistException;
use CondorcetPHP\Condorcet\Throwable\CandidateExistsException;
use CondorcetPHP\Condorcet\Throwable\ElectionObjectVersionMismatchException;
use CondorcetPHP\Condorcet\Throwable\FileDoesNotExistException;
use CondorcetPHP\Condorcet\Throwable\NoCandidatesException;
use CondorcetPHP\Condorcet\Throwable\NoSeatsException;
use CondorcetPHP\Condorcet\Throwable\ParseVotesMaxNumberReachedException;
use CondorcetPHP\Condorcet\Throwable\ResultRequestedWithoutVotesException;
use CondorcetPHP\Condorcet\Throwable\VoteException;
use CondorcetPHP\Condorcet\Throwable\VoteInvalidFormatException;
use CondorcetPHP\Condorcet\Throwable\VoteMaxNumberReachedException;
use CondorcetPHP\Condorcet\Throwable\VotingHasStartedException;
use CondorcetPHP\Condorcet\Tools\Converters\CEF\CondorcetElectionFormat;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class ElectionTest extends TestCase
{
    private Election $election1;
    private Election $election2;

    private Candidate $candidate1;
    private Candidate $candidate2;
    private Candidate $candidate3;

    private Vote $vote1;
    private Vote $vote2;
    private Vote $vote3;
    private Vote $vote4;

    protected function setUp(): void
    {
        $this->election1 = new Election;

        $this->candidate1 = $this->election1->addCandidate('candidate1');
        $this->candidate2 = $this->election1->addCandidate('candidate2');
        $this->candidate3 = $this->election1->addCandidate('candidate3');

        $this->election1->addVote($this->vote1 = new Vote([$this->candidate1, $this->candidate2, $this->candidate3]));
        $this->election1->addVote($this->vote2 = new Vote([$this->candidate2, $this->candidate3, $this->candidate1]));
        $this->election1->addVote($this->vote3 = new Vote([$this->candidate3, $this->candidate1, $this->candidate2]));
        $this->election1->addVote($this->vote4 = new Vote([$this->candidate1, $this->candidate2, $this->candidate3]));

        $this->election2 = new Election;
    }

    protected function tearDown(): void
    {
        Election::setMaxParseIteration((new ReflectionClass(Election::class))->getProperty('maxParseIteration')->getDefaultValue());
        Election::setMaxVoteNumber((new ReflectionClass(Election::class))->getProperty('maxVoteNumber')->getDefaultValue());
    }

    public function testRemoveAllVotes(): void
    {
        $this->assertTrue($this->election1->removeAllVotes());
        $this->assertCount(0, $this->election1->getVotesList());
        $this->assertSame(0, $this->election1->countVotes());
    }

    public function testRemoveVotes(): never
    {
        $this->assertTrue($this->election1->removeVote($this->vote2));
        $this->assertCount(3, $this->election1->getVotesList());

        $badRemoveVote = new Vote('A');

        $this->expectException(VoteException::class);
        $this->expectExceptionMessage('Problem handling vote: Cannot remove vote not registered in this election');

        $this->election1->removeVote($badRemoveVote);
    }

    public function testRemoveVotesByTags(): void
    {
        $this->vote1->addtags('tag1,tag2,tag3');
        $this->vote2->addtags('tag3,tag4');
        $this->vote3->addtags('tag3,tag4,tag5');
        $this->vote4->addtags('tag1,tag4');

        $this->assertCount(3, $r = $this->election1->removeVotesByTags(['tag1', 'tag5']));

        $this->assertSame([$this->vote1, $this->vote3, $this->vote4], $r);

        $this->assertSame([1 => $this->vote2], $this->election1->getVotesList());

        $this->setUp();

        $this->vote1->addtags('tag1,tag2,tag3');
        $this->vote2->addtags('tag3,tag4');
        $this->vote3->addtags('tag3,tag4,tag5');
        $this->vote4->addtags('tag1,tag4');

        $this->assertCount(1, $r = $this->election1->removeVotesByTags('tag1,tag5', false));

        $this->assertSame([$this->vote2], $r);

        $this->assertSame([0 => $this->vote1, 2=> $this->vote3, 3 => $this->vote4], $this->election1->getVotesList());
    }


    public function testTagsFilter(): void
    {
        $this->vote1->addtags('tag1,tag2,tag3');
        $this->vote2->addtags('tag3,tag4');
        $this->vote3->addtags('tag3,tag4,tag5');
        $this->vote4->addtags('tag1,tag4');

        $this->assertSame($this->election1->getVotesList('tag1,tag2', true), [0=>$this->vote1, 3=>$this->vote4]);
        $this->assertSame($this->election1->countVotes('tag1,tag2', true), 2);

        $this->assertSame($this->election1->getVotesList('tag1,tag2', false), [1=>$this->vote2, 2=>$this->vote3]);
        $this->assertSame($this->election1->countVotes('tag1,tag2', false), 2);

        $resultGlobal = $this->election1->getResult('Schulze');
        $resultFilter1 = $this->election1->getResult('Schulze', ['tags' => 'tag1', 'withTag' => true]);
        $resultFilter2 = $this->election1->getResult('Schulze', ['tags' => 'tag1', 'withTag' => false]);

        $this->assertNotSame($resultGlobal, $resultFilter1);
        $this->assertNotSame($resultGlobal, $resultFilter2);
        $this->assertNotSame($resultFilter1, $resultFilter2);
    }

    public function testParseCandidates(): void
    {
        $this->assertCount(
            4,
            $this->election2->parseCandidates('Bruckner;   Mahler   ;
                Debussy
                Bibendum')
        );

        $this->assertSame(
            ['Bruckner', 'Mahler', 'Debussy', 'Bibendum'],
            $this->election2->getCandidatesListAsString()
        );
    }

    public function testgetCandidateObjectFromName(): void
    {
        $this->assertSame($this->candidate1, $this->election1->getCandidateObjectFromName('candidate1'));
        $this->assertNull($this->election1->getCandidateObjectFromName('candidate42'));
    }

    public function testParseError(): never
    {
        $this->expectException(VoteInvalidFormatException::class);
        $this->expectExceptionMessage("The format of the vote is invalid: the value 'text' is not an integer.");

        $this->election1->parseVotes('candidate1>candidate2 * text');
    }

    public function testMaxParseIteration1(): never
    {
        $this->assertSame(42, Election::setMaxParseIteration(42));

        $this->assertSame(42, $this->election1->parseVotes('candidate1>candidate2 * 42'));

        $this->assertSame(42, $this->election1->parseVotes('candidate1>candidate2 * 42'));

        $this->assertNull(Election::setMaxParseIteration(null));

        $this->assertSame(43, $this->election1->parseVotes('candidate1>candidate2 * 43'));

        $this->assertSame(42, Election::setMaxParseIteration(42));

        $this->expectException(ParseVotesMaxNumberReachedException::class);
        $this->election1->parseVotes('candidate1>candidate2 * 43');
    }

    public function testMaxParseIteration2(): never
    {
        $this->assertSame(42, Election::setMaxParseIteration(42));

        $this->expectException(ParseVotesMaxNumberReachedException::class);
        $this->assertSame(42, $this->election1->parseVotes('
            candidate1>candidate2 * 21
            candidate1>candidate2 * 21
            candidate1>candidate2 * 21
        '));
    }

    public function testMaxParseIteration3(): never
    {
        $this->assertSame(2, Election::setMaxParseIteration(2));

        $this->assertSame([0=>'candidate1', 1=>'candidate2'], $this->election2->parseCandidates('candidate1;candidate2'));

        $this->assertSame([0=>'candidate3', 1=>'candidate4'], $this->election2->parseCandidates('candidate3;candidate4'));

        $this->assertNull(Election::setMaxParseIteration(null));

        $this->assertSame([0=>'candidate5', 1=>'candidate6', 2=>'candidate7'], $this->election2->parseCandidates('candidate5;candidate6;candidate7'));

        $this->assertSame(2, Election::setMaxParseIteration(2));

        $this->expectException(VoteMaxNumberReachedException::class);
        $this->expectExceptionMessage('The maximal number of votes for the method is reached: 2');

        $this->election2->parseCandidates('candidate8;candidate9;candidate10');
    }

    public function testMaxVoteNumber(): never
    {
        $election = new Election;
        $this->assertCount(3, $election->parseCandidates('candidate1;candidate2;candidate3'));

        $this->assertSame(42, Election::setMaxVoteNumber(42));

        $this->assertSame(21, $election->parseVotes('candidate1>candidate2 * 21'));

        try {
            $election->parseVotes('candidate1>candidate2 * 42');
            $this->assertTrue(false);
        } catch (VoteMaxNumberReachedException $e) {
            $this->assertEquals('The maximal number of votes for the method is reached', $e->getMessage());
        }

        $this->assertSame(21, $election->countVotes());

        $election->parseVotes('candidate1 * 21');

        $this->assertSame(42, $election->countVotes());

        $this->assertNull(Election::setMaxVoteNumber(null));

        $election->addVote('candidate3');

        $this->assertSame(42, Election::setMaxVoteNumber(42));

        try {
            $election->addVote('candidate3');
        } catch (VoteMaxNumberReachedException $e) {
            $reserveException = $e;
        }

        $this->assertNull(Election::setMaxVoteNumber(null));

        $this->expectException(VoteMaxNumberReachedException::class);
        $this->expectExceptionMessage('The maximal number of votes for the method is reached');

        throw $reserveException;
    }

    public function testGetVotesListAsString(): void
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

        $this->assertSame(
            "A > B = C = D = E * 6\n".
        "D > A = B = C = E * 6\n".
        "A > B = C > E > D * 5\n".
        "A = B = E > C = D * 3\n".
        'A = B = C = D = E * 1',
            $this->election1->getVotesListAsString()
        );

        $this->election1->setImplicitRanking(false);

        $this->assertSame(
            "A * 6\n".
        "D * 6\n".
        "A > B = C > E * 5\n".
        "A = B = E * 3\n".
        '/EMPTY_RANKING/ * 1',
            $this->election1->getVotesListAsString()
        );

        $this->assertSame(
            <<<'VOTES'
                A * 6
                D * 6
                A > B = C > E * 5
                A = B = E * 3
                Y > Z * 1
                VOTES,
            $this->election1->getVotesListAsString(false)
        );
    }

    public function testEmptyRankingExport(): void
    {
        $this->election2->parseCandidates('A;B;C');
        $this->election2->setImplicitRanking(false);

        $this->election2->addVote(new Vote(''));
        $this->election2->addVote(new Vote('D>E'));

        $this->assertSame('/EMPTY_RANKING/ * 2', $this->election2->getVotesListAsString(true));
        $this->assertSame('/EMPTY_RANKING/ * 1'. "\n" .'D > E * 1', $this->election2->getVotesListAsString(false));

        $this->assertSame(
            $cvotes_explicit_without_context =
                            <<<'CVOTES'
                                #/Candidates: A ; B ; C
                                #/Implicit Ranking: false
                                #/Weight Allowed: false

                                /EMPTY_RANKING/ * 1
                                D > E * 1
                                CVOTES,
            CondorcetElectionFormat::createFromElection(election: $this->election2, includeNumberOfSeats: false, aggregateVotes: true, inContext: false)
        );

        $this->assertSame(
            str_replace(' * 1', '', $cvotes_explicit_without_context),
            CondorcetElectionFormat::createFromElection(election: $this->election2, includeNumberOfSeats: false, aggregateVotes: false, inContext: false)
        );

        $this->assertSame(
            <<<'CVOTES'
                #/Candidates: A ; B ; C
                #/Implicit Ranking: false
                #/Weight Allowed: false

                /EMPTY_RANKING/ * 2
                CVOTES,
            CondorcetElectionFormat::createFromElection(election: $this->election2, includeNumberOfSeats: false, aggregateVotes: true, inContext: true)
        );

        $this->assertSame(
            <<<'CVOTES'
                #/Candidates: A ; B ; C
                #/Implicit Ranking: false
                #/Weight Allowed: false

                /EMPTY_RANKING/
                /EMPTY_RANKING/
                CVOTES,
            CondorcetElectionFormat::createFromElection(election: $this->election2, includeNumberOfSeats: false, aggregateVotes: false, inContext: true)
        );

        $this->election2->setImplicitRanking(true);

        $this->assertSame(
            <<<'CVOTES'
                #/Candidates: A ; B ; C
                #/Implicit Ranking: true
                #/Weight Allowed: false

                A = B = C * 2
                CVOTES,
            CondorcetElectionFormat::createFromElection(election: $this->election2, includeNumberOfSeats: false, aggregateVotes: true, inContext: true)
        );

        $this->assertSame(
            <<<'CVOTES'
                #/Candidates: A ; B ; C
                #/Implicit Ranking: true
                #/Weight Allowed: false

                A = B = C
                A = B = C
                CVOTES,
            CondorcetElectionFormat::createFromElection(election: $this->election2, includeNumberOfSeats: false, aggregateVotes: false, inContext: true)
        );

        $this->election2 = new Election;
        $this->election2->parseCandidates('A;B;C;D');
        $this->election2->setImplicitRanking(true);

        $this->election2->addVote(new Vote('A>B'));

        $this->assertSame(
            <<<'CVOTES'
                #/Candidates: A ; B ; C ; D
                #/Implicit Ranking: true
                #/Weight Allowed: false

                A > B > C = D * 1
                CVOTES,
            CondorcetElectionFormat::createFromElection(election: $this->election2, includeNumberOfSeats: false, aggregateVotes: true, inContext: true)
        );

        $this->assertSame(
            $cvotes_implicit_without_context =
                            <<<'CVOTES'
                                #/Candidates: A ; B ; C ; D
                                #/Implicit Ranking: true
                                #/Weight Allowed: false

                                A > B * 1
                                CVOTES,
            CondorcetElectionFormat::createFromElection(election: $this->election2, includeNumberOfSeats: false, aggregateVotes: true, inContext: false)
        );

        $this->assertSame(
            str_replace(' * 1', '', $cvotes_implicit_without_context),
            CondorcetElectionFormat::createFromElection(election: $this->election2, includeNumberOfSeats: false, aggregateVotes: false, inContext: false)
        );
    }

    public function testParseVoteCandidateCoherence(): void
    {
        $this->election1 = new Election;

        $cA = $this->election1->addCandidate('A');
        $cB = $this->election1->addCandidate('B');
        $cC = $this->election1->addCandidate('C');

        $this->assertSame(2, $this->election1->parseVotes('
            A>B>C * 2
        '));

        $votes = $this->election1->getVotesList();

        foreach ($votes as $vote) {
            $ranking = $vote->getRanking();

            $this->assertSame($cA, $ranking[1][0]);
            $this->assertSame($cB, $ranking[2][0]);
            $this->assertSame($cC, $ranking[3][0]);
        }
    }

    public function testParseVotesInvalidPath(): void
    {
        $this->election1 = new Election;

        $this->election1->addCandidate('A');
        $this->election1->addCandidate('B');

        $this->expectException(FileDoesNotExistException::class);
        $this->expectExceptionMessageMatches('/bad_file.txt$/');

        $this->election1->parseVotes('bad_file.txt', true);
    }

    public function testParseVotesWithoutFail(): void
    {
        $this->election1 = new Election;

        $this->election1->addCandidate('A');
        $this->election1->addCandidate('B');
        $this->election1->addCandidate('C');

        $this->assertSame(2, $this->election1->parseVotesWithoutFail('
            A > B > C
            A > B > C * 4;tag1 || A > B > C*4 #Coucou
            A < B < C * 10
            D <> B
            A > B > C
        '));

        $this->assertSame(10, $this->election1->countVotes());

        $this->assertSame(2, $this->election1->parseVotesWithoutFail(__DIR__.'/../LargeElectionData/smallVote1.votes', true));

        $this->assertSame(20, $this->election1->countVotes());

        $this->assertSame(2, $this->election1->parseVotesWithoutFail(new \SplFileObject(__DIR__.'/../LargeElectionData/smallVote1.votes'), true));

        $this->assertSame(30, $this->election1->countVotes());

        $this->assertSame(2, $this->election1->parseVotesWithoutFail(new \SplFileInfo(__DIR__.'/../LargeElectionData/smallVote1.votes'), true));

        $this->assertSame(40, $this->election1->countVotes());
    }

    public function testParseVotesWithoutFailInvalidPath(): void
    {
        $this->election1 = new Election;

        $this->election1->addCandidate('A');
        $this->election1->addCandidate('B');

        $this->expectException(FileDoesNotExistException::class);
        $this->expectExceptionMessageMatches('/bad_file.txt$/');

        $this->election1->parseVotesWithoutFail('bad_file.txt', true);
    }

    public function testVoteWeight(): void
    {
        $election = new Election;

        $election->addCandidate('A');
        $election->addCandidate('B');
        $election->addCandidate('C');
        $election->addCandidate('D');

        $election->parseVotes('
            tag1 || A > C > D * 6
            tag2 || B > A > D * 1
            C > B > D * 3
            D > B > A * 3
        ');

        $voteWithWeight = $election->addVote('D > C > B');
        $voteWithWeight->setWeight(2);

        $this->assertSame(
            14,
            $election->sumVotesWeight()
        );

        $this->assertSame(
            $election->sumVotesWeight(),
            $election->sumValidVotesWeightWithConstraints()
        );

        // Some test about votes weight tags filters
        $this->assertSame(
            7,
            $election->sumVotesWeight('tag1,tag2')
        );

        $this->assertSame(
            13,
            $election->sumVotesWeight('tag2', false)
        );

        $this->assertSame(
            0,
            $election->sumVotesWeight('tag1,tag2', 2)
        );

        // Continue
        $this->assertSame(
            'D > C > B ^2',
            (string) $voteWithWeight
        );

        $this->assertSame(
            'D > C > B > A',
            $voteWithWeight->getSimpleRanking($election)
        );

        $this->assertNotSame(
            'A = D > C > B',
            $election->getResult('Schulze Winning')->getResultAsString()
        );

        $election->allowsVoteWeight(true);

        $this->assertSame(
            15,
            $election->sumVotesWeight()
        );

        $this->assertSame(
            'D > C > B > A ^2',
            $voteWithWeight->getSimpleRanking($election)
        );

        $this->assertSame(
            'A = D > C > B',
            $election->getResult('Schulze Winning')->getResultAsString()
        );

        $election->allowsVoteWeight(false);

        $this->assertSame(
            14,
            $election->sumVotesWeight()
        );

        $this->assertNotSame(
            'A = D > C > B',
            $election->getResult('Schulze Winning')->getResultAsString()
        );

        $election->allowsVoteWeight(!$election->isVoteWeightAllowed());

        $election->removeVote($voteWithWeight);

        $this->assertSame(
            13,
            $election->sumVotesWeight()
        );

        $election->parseVotes('
            D > C > B ^2 * 1
        ');

        $this->assertSame(
            15,
            $election->sumVotesWeight()
        );

        $this->assertSame(
            'A = D > C > B',
            $election->getResult('Schulze Winning')->getResultAsString()
        );

        $election->addVote('D > C > B');

        $this->assertSame(
            <<<'VOTES'
                A > C > D > B * 6
                C > B > D > A * 3
                D > B > A > C * 3
                D > C > B > A ^2 * 1
                B > A > D > C * 1
                D > C > B > A * 1
                VOTES,
            $election->getVotesListAsString()
        );
    }

    public function testaddVotesFromJson(): never
    {
        $election = new Election;

        $election->addCandidate('A');
        $election->addCandidate('B');
        $election->addCandidate('C');

        $votes = [];

        $votes[]['vote'] = 'B>C>A';
        $votes[]['vote'] = new \stdClass; // Invalid Vote
        $votes[]['vote'] = ['C', 'B', 'A'];

        $this->assertSame(2, $election->addVotesFromJson(json_encode($votes)));

        $this->assertSame(
            <<<'VOTES'
                B > C > A * 1
                C > B > A * 1
                VOTES,
            $election->getVotesListAsString()
        );

        $votes = [];

        $votes[0]['vote'] = 'A>B>C';
        $votes[0]['multi'] = 5;
        $votes[0]['tag'] = 'tag1';
        $votes[0]['weight'] = '42';

        $election->addVotesFromJson(json_encode($votes));

        $election->allowsVoteWeight(true);

        $this->assertSame(
            <<<'VOTES'
                A > B > C ^42 * 5
                B > C > A * 1
                C > B > A * 1
                VOTES,
            $election->getVotesListAsString()
        );
        $this->assertSame(5, $election->countVotes('tag1'));

        $this->expectException(\JsonException::class);

        $election->addVotesFromJson(json_encode($votes).'{42');
    }

    public function testaddCandidatesFromJson(): never
    {
        $election = new Election;

        $candidates = ['candidate1 ', 'candidate2'];

        $election->addCandidatesFromJson(json_encode($candidates));

        $this->assertSame(2, $election->countCandidates());

        $this->assertEquals(['candidate1', 'candidate2'], $election->getCandidatesListAsString());

        $this->expectException(CandidateExistsException::class);
        $this->expectExceptionMessage('This candidate already exists: candidate2');

        $election->addCandidatesFromJson(json_encode(['candidate2']));
    }

    public function testaddCandidatesFromInvalidJson(): never
    {
        $election = new Election;

        $this->expectException(\JsonException::class);
        $election->addCandidatesFromJson(json_encode(['candidate3']).'{42');
    }


    public function testaddVotesFromJsonWithInvalidJson(): void
    {
        $errors = ['42', 'true', 'false', 'null', '', ' ', json_encode(new \stdClass)];

        foreach ($errors as $oneError) {
            try {
                $this->election2->addVotesFromJson($oneError);

                // Else fail
                $this->fail("{$oneError} is not a valid Json for PHP");
            } catch (\JsonException) {
            }
        }

        $this->assertEmpty($this->election2->getVotesList());
    }

    public function testCachingResult(): void
    {
        $election = new Election;

        $election->addCandidate('A');
        $election->addCandidate('B');
        $election->addCandidate('C');
        $election->addCandidate('D');

        $election->addVote($vote1 = new Vote('A > C > D'));

        $result1 = $election->getResult('Schulze');
        $this->assertSame($result1, $election->getResult('Schulze'));
    }

    public function testElectionSerializing(): void
    {
        $election = new Election;

        $election->addCandidate('A');
        $election->addCandidate('B');
        $election->addCandidate('C');
        $election->addCandidate('D');

        $election->addVote($vote1 = new Vote('A > C > D'));
        $result1 = $election->getResult('Schulze');

        $election = serialize($election);
        // file_put_contents("Tests/src/ElectionData/serialized_election_v3.2.0.txt", $election); # For next test
        $election = unserialize($election);

        $this->assertNotSame($result1, $election->getResult('Schulze'));
        $this->assertSame($result1->getResultAsString(), $election->getResult('Schulze')->getResultAsString());

        $this->assertNotSame($vote1, $election->getVotesList()[0]);
        $this->assertSame($vote1->getSimpleRanking(), $election->getVotesList()[0]->getSimpleRanking());
        $this->assertTrue($election->getVotesList()[0]->haveLink($election));
        $this->assertFalse($vote1->haveLink($election));
    }

    public function testElectionUnserializing(): void
    {
        $this->expectException(ElectionObjectVersionMismatchException::class);
        $this->expectExceptionMessage(
            "Version mismatch: The election object has version '3.2' " .
            "which is different from the current class version '".Condorcet::getVersion(true)."'"
        );

        unserialize(
            file_get_contents('Tests/src/ElectionData/serialized_election_v3.2.0.txt')
        );
    }

    public function testElectionUnserializingWithoutVotes(): void
    {
        $e = new Election;
        $e->parseCandidates('A;B');
        $e = serialize($e);
        $e = unserialize($e);

        $this->assertInstanceOf(Election::class, $e);
    }

    public function testCloneElection(): void
    {
        $this->election1->computeResult();

        $cloneElection = clone $this->election1;

        $this->assertNotSame($this->election1->getVotesManager(), $cloneElection->getVotesManager());
        $this->assertSame($this->election1->getVotesList(), $cloneElection->getVotesList());

        $this->assertSame($this->election1->getCandidatesList(), $cloneElection->getCandidatesList());

        $this->assertNotSame($this->election1->getPairwise(), $cloneElection->getPairwise());
        $this->assertEquals($this->election1->getExplicitPairwise(), $cloneElection->getExplicitPairwise());

        $this->assertNotSame($this->election1->getTimerManager(), $cloneElection->getTimerManager());

        $this->assertSame($this->election1->getVotesList()[0], $cloneElection->getVotesList()[0]);

        $this->assertTrue($cloneElection->getVotesList()[0]->haveLink($this->election1));
        $this->assertTrue($cloneElection->getVotesList()[0]->haveLink($cloneElection));
    }

    public function testPairwiseArrayAccess(): void
    {
        $this->election1->computeResult();

        $this->assertTrue($this->election1->getPairwise()->offsetExists(1));
    }

    public function testGetCandidateObjectFromKey(): void
    {
        $this->assertSame($this->candidate2, $this->election1->getCandidateObjectFromKey(1));

        $this->assertNull($this->election1->getCandidateObjectFromKey(42));
    }

    public function testElectionState1(): never
    {
        $this->expectException(VotingHasStartedException::class);
        $this->expectExceptionMessage("The voting has started: cannot add 'candidate4'");

        $this->election1->addCandidate('candidate4');
    }

    public function testElectionState2(): never
    {
        $this->expectException(VotingHasStartedException::class);
        $this->expectExceptionMessage('The voting has started');

        $this->election1->removeCandidates('candidate4');
    }

    public function testElectionState3(): never
    {
        $this->expectException(NoCandidatesException::class);
        $this->expectExceptionMessage('You need to specify one or more candidates before voting');

        $election = new Election;
        $election->setStateTovote();
    }

    public function testElectionState4(): never
    {
        $this->expectException(ResultRequestedWithoutVotesException::class);
        $this->expectExceptionMessage('The result cannot be requested without votes');

        $election = new Election;
        $election->getResult();
    }

    public function testElectionState5(): void
    {
        $this->election1->getResult();

        $this->assertTrue($this->election1->setStateTovote());

        $this->assertSame(ElectionState::VOTES_REGISTRATION, $this->election1->getState());
    }

    public function testAddSameVote(): never
    {
        $this->expectException(VoteException::class);
        $this->expectExceptionMessage('This vote is already linked to the election');

        $this->election1->addVote($this->vote1);
    }

    public function testDestroy(): void
    {
        $election = new Election;

        $election->addCandidate('candidate1');
        $election->addCandidate('candidate2');
        $election->addCandidate('candidate3');

        $election->addVote('candidate1>candidate2');

        $weakref = \WeakReference::create($election);

        // PHP circular reference can bug
        // \debug_zval_dump($election);
        unset($election);

        $this->assertNull($weakref->get());
    }

    public function testRemoveCandidate(): never
    {
        $this->expectException(CandidateDoesNotExistException::class);
        $this->expectExceptionMessage('This candidate does not exist: B');

        $election = new Election;

        $election->addCandidate('candidate1');

        $badCandidate = new Candidate('B');

        $election->removeCandidates($badCandidate);
    }

    #[DataProvider('MethodsListProvider')]
    public function testRemoveCandidateResult(string $method): void
    {
        $votes = '  Memphis * 4
                    Nashville * 3
                    Chattanooga * 2
                    Knoxville * 1';

        // Ref
        $electionRef = new Election;

        $electionRef->addCandidate('Memphis');
        $electionRef->addCandidate('Nashville');
        $electionRef->addCandidate('Knoxville');
        $electionRef->addCandidate('Chattanooga');

        $electionRef->parseVotes($votes);

        // Test
        $electionTest = new Election;

        $electionTest->addCandidate('Memphis');
        $electionTest->addCandidate('BadCandidate');
        $electionTest->addCandidate('Nashville');
        $electionTest->addCandidate('Knoxville');
        $electionTest->addCandidate('Chattanooga');

        $electionTest->removeCandidates('BadCandidate');

        $electionTest->parseVotes($votes);


        $this->assertSame(
            $electionRef->getResult($method)->getResultAsArray(true),
            $electionTest->getResult($method)->getResultAsArray(true)
        );
    }

    public static function MethodsListProvider(): array
    {
        $withNonDeterministicMethod = false;

        $r = [];

        foreach (Condorcet::getAuthMethods() as $method) {
            if ($withNonDeterministicMethod || Condorcet::getMethodClass($method)::IS_DETERMINISTIC) {
                $r[] = [$method];
            }
        }

        return $r;
    }

    public function testAmbiguousCandidatesOnElectionSide(): never
    {
        $this->expectException(VoteInvalidFormatException::class);
        $this->expectExceptionMessage('The format of the vote is invalid');

        $vote = new Vote('candidate1>candidate2');

        $election1 = new Election;
        $election2 = new Election;

        $election1->addCandidate(new Candidate('candidate2'));
        $election2->addCandidate(new Candidate('candidate1'));

        $election1->addVote($vote);
        $election2->addVote($vote);
    }

    public function testAmbiguousCandidatesOnVoteSide(): never
    {
        $this->expectException(VoteInvalidFormatException::class);
        $this->expectExceptionMessage('The format of the vote is invalid: vote does not match candidate in this election');

        $election1 = new Election;
        $election2 = new Election;

        $election1->addCandidate(new Candidate('candidate1'));
        $election2->addCandidate(new Candidate('candidate2'));

        $candidate3 = new Candidate('candidate3');
        $election1->addCandidate($candidate3);
        $election2->addCandidate($candidate3);

        $vote = new Vote('candidate3');

        $election1->addVote($vote);
        $election2->addVote($vote);

        $election1->getResult();
        $election2->getResult();

        try {
            $vote->setRanking('candidate1>candidate2>candidate3');
        } catch (\Exception $e) {
        }

        $this->assertEmpty($election1->debugGetCalculator());
        $this->assertEmpty($election2->debugGetCalculator());

        throw $e;
    }

    public function testInvalidSeats(): never
    {
        $this->expectException(NoSeatsException::class);
        $this->expectExceptionMessage('No seats defined');

        $this->election1->setNumberOfSeats(0);
    }

    public function testSeats(): void
    {
        $this->assertSame(100, $this->election1->getNumberOfSeats());

        $this->election1->setNumberOfSeats(5);

        $this->assertSame(5, $this->election1->getNumberOfSeats());
    }
}
