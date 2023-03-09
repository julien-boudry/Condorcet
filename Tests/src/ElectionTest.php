<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests;

use CondorcetPHP\Condorcet\ElectionProcess\ElectionState;
use CondorcetPHP\Condorcet\{Candidate, Condorcet, Election, Vote};
use CondorcetPHP\Condorcet\Throwable\{CandidateDoesNotExistException, CandidateExistsException, ElectionObjectVersionMismatchException, FileDoesNotExistException, NoCandidatesException, NoSeatsException, ResultRequestedWithoutVotesException, VoteException, VoteInvalidFormatException, VoteMaxNumberReachedException, VotingHasStartedException};
use CondorcetPHP\Condorcet\Tools\Converters\CondorcetElectionFormat;
use PHPUnit\Framework\Attributes\{BackupStaticProperties, DataProvider, PreserveGlobalState, RunInSeparateProcess};
use PHPUnit\Framework\TestCase;

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

    public function testRemoveVotes(): never
    {
        $this->expectException(VoteException::class);
        $this->expectExceptionMessage('Problem handling vote: cannot remove vote, is not registered in this election');

        self::assertTrue($this->election1->removeVote($this->vote2));
        self::assertCount(3, $this->election1->getVotesList());

        $badRemoveVote = new Vote('A');

        $this->election1->removeVote($badRemoveVote);
    }

    public function testRemoveVotesByTags(): void
    {
        $this->vote1->addtags('tag1,tag2,tag3');
        $this->vote2->addtags('tag3,tag4');
        $this->vote3->addtags('tag3,tag4,tag5');
        $this->vote4->addtags('tag1,tag4');

        self::assertCount(3, $r = $this->election1->removeVotesByTags(['tag1', 'tag5']));

        self::assertSame([$this->vote1, $this->vote3, $this->vote4], $r);

        self::assertSame([1 => $this->vote2], $this->election1->getVotesList());

        $this->setUp();

        $this->vote1->addtags('tag1,tag2,tag3');
        $this->vote2->addtags('tag3,tag4');
        $this->vote3->addtags('tag3,tag4,tag5');
        $this->vote4->addtags('tag1,tag4');

        self::assertCount(1, $r = $this->election1->removeVotesByTags('tag1,tag5', false));

        self::assertSame([$this->vote2], $r);

        self::assertSame([0 => $this->vote1, 2=> $this->vote3, 3 => $this->vote4], $this->election1->getVotesList());
    }


    public function testTagsFilter(): void
    {
        $this->vote1->addtags('tag1,tag2,tag3');
        $this->vote2->addtags('tag3,tag4');
        $this->vote3->addtags('tag3,tag4,tag5');
        $this->vote4->addtags('tag1,tag4');

        self::assertSame($this->election1->getVotesList('tag1,tag2', true), [0=>$this->vote1, 3=>$this->vote4]);
        self::assertSame($this->election1->countVotes('tag1,tag2', true), 2);

        self::assertSame($this->election1->getVotesList('tag1,tag2', false), [1=>$this->vote2, 2=>$this->vote3]);
        self::assertSame($this->election1->countVotes('tag1,tag2', false), 2);

        $resultGlobal = $this->election1->getResult('Schulze');
        $resultFilter1 = $this->election1->getResult('Schulze', ['tags' => 'tag1', 'withTag' => true]);
        $resultFilter2 = $this->election1->getResult('Schulze', ['tags' => 'tag1', 'withTag' => false]);

        self::assertNotSame($resultGlobal, $resultFilter1);
        self::assertNotSame($resultGlobal, $resultFilter2);
        self::assertNotSame($resultFilter1, $resultFilter2);
    }

    public function testParseCandidates(): void
    {
        self::assertCount(
            4,
            $this->election2->parseCandidates('Bruckner;   Mahler   ;
                Debussy
                Bibendum')
        );

        self::assertSame(
            ['Bruckner', 'Mahler', 'Debussy', 'Bibendum'],
            $this->election2->getCandidatesListAsString()
        );
    }

    public function testgetCandidateObjectFromName(): void
    {
        self::assertSame($this->candidate1, $this->election1->getCandidateObjectFromName('candidate1'));
        self::assertNull($this->election1->getCandidateObjectFromName('candidate42'));
    }

    public function testParseError(): never
    {
        $this->expectException(VoteInvalidFormatException::class);
        $this->expectExceptionMessage("The format of the vote is invalid: the value 'text' is not an integer.");

        $this->election1->parseVotes('candidate1>candidate2 * text');
    }

    #[PreserveGlobalState(false)]
    #[BackupStaticProperties(false)]
    #[RunInSeparateProcess]
    public function testMaxParseIteration1(): never
    {
        $this->expectException(VoteMaxNumberReachedException::class);
        $this->expectExceptionMessage('The maximal number of votes for the method is reached: 42');

        self::assertSame(42, Election::setMaxParseIteration(42));

        self::assertSame(42, $this->election1->parseVotes('candidate1>candidate2 * 42'));

        self::assertSame(42, $this->election1->parseVotes('candidate1>candidate2 * 42'));

        self::assertNull(Election::setMaxParseIteration(null));

        self::assertSame(43, $this->election1->parseVotes('candidate1>candidate2 * 43'));

        self::assertSame(42, Election::setMaxParseIteration(42));

        $this->election1->parseVotes('candidate1>candidate2 * 43');
    }

    #[PreserveGlobalState(false)]
    #[BackupStaticProperties(false)]
    #[RunInSeparateProcess]
    public function testMaxParseIteration2(): never
    {
        $this->expectException(VoteMaxNumberReachedException::class);
        $this->expectExceptionMessage('The maximal number of votes for the method is reached: 42');

        self::assertSame(42, Election::setMaxParseIteration(42));

        self::assertSame(42, $this->election1->parseVotes('
            candidate1>candidate2 * 21
            candidate1>candidate2 * 21
            candidate1>candidate2 * 21
        '));
    }

    #[PreserveGlobalState(false)]
    #[BackupStaticProperties(false)]
    #[RunInSeparateProcess]
    public function testMaxParseIteration3(): never
    {
        $this->expectException(VoteMaxNumberReachedException::class);
        $this->expectExceptionMessage('The maximal number of votes for the method is reached: 2');

        self::assertSame(2, Election::setMaxParseIteration(2));

        self::assertSame([0=>'candidate1', 1=>'candidate2'], $this->election2->parseCandidates('candidate1;candidate2'));

        self::assertSame([0=>'candidate3', 1=>'candidate4'], $this->election2->parseCandidates('candidate3;candidate4'));

        self::assertNull(Election::setMaxParseIteration(null));

        self::assertSame([0=>'candidate5', 1=>'candidate6', 2=>'candidate7'], $this->election2->parseCandidates('candidate5;candidate6;candidate7'));

        self::assertSame(2, Election::setMaxParseIteration(2));

        $this->election2->parseCandidates('candidate8;candidate9;candidate10');
    }

    #[PreserveGlobalState(false)]
    #[BackupStaticProperties(false)]
    #[RunInSeparateProcess]
    public function testMaxVoteNumber(): never
    {
        $this->expectException(VoteMaxNumberReachedException::class);
        $this->expectExceptionMessage('The maximal number of votes for the method is reached');

        $election = new Election;
        self::assertCount(3, $election->parseCandidates('candidate1;candidate2;candidate3'));

        self::assertSame(42, Election::setMaxVoteNumber(42));

        self::assertSame(21, $election->parseVotes('candidate1>candidate2 * 21'));

        try {
            $election->parseVotes('candidate1>candidate2 * 42');
            self::assertTrue(false);
        } catch (VoteMaxNumberReachedException $e) {
            $this->assertEquals('The maximal number of votes for the method is reached', $e->getMessage());
        }

        self::assertSame(21, $election->countVotes());

        $election->parseVotes('candidate1 * 21');

        self::assertSame(42, $election->countVotes());

        self::assertNull(Election::setMaxVoteNumber(null));

        $election->addVote('candidate3');

        self::assertSame(42, Election::setMaxVoteNumber(42));

        try {
            $election->addVote('candidate3');
        } catch (VoteMaxNumberReachedException $e) {
            $reserveException = $e;
        }

        self::assertNull(Election::setMaxVoteNumber(null));

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

        self::assertSame(
            "A > B = C = D = E * 6\n".
        "D > A = B = C = E * 6\n".
        "A > B = C > E > D * 5\n".
        "A = B = E > C = D * 3\n".
        'A = B = C = D = E * 1',
            $this->election1->getVotesListAsString()
        );

        $this->election1->setImplicitRanking(false);

        self::assertSame(
            "A * 6\n".
        "D * 6\n".
        "A > B = C > E * 5\n".
        "A = B = E * 3\n".
        '/EMPTY_RANKING/ * 1',
            $this->election1->getVotesListAsString()
        );

        self::assertSame(
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

        self::assertSame('/EMPTY_RANKING/ * 2', $this->election2->getVotesListAsString(true));
        self::assertSame('/EMPTY_RANKING/ * 1'. "\n" .'D > E * 1', $this->election2->getVotesListAsString(false));

        self::assertSame(
            $cvotes_explicit_without_context =
                            <<<'CVOTES'
                                #/Candidates: A ; B ; C
                                #/Implicit Ranking: false
                                #/Weight Allowed: false

                                /EMPTY_RANKING/ * 1
                                D > E * 1
                                CVOTES,
            CondorcetElectionFormat::exportElectionToCondorcetElectionFormat(election: $this->election2, includeNumberOfSeats: false, aggregateVotes: true, inContext: false)
        );

        self::assertSame(
            str_replace(' * 1', '', $cvotes_explicit_without_context),
            CondorcetElectionFormat::exportElectionToCondorcetElectionFormat(election: $this->election2, includeNumberOfSeats: false, aggregateVotes: false, inContext: false)
        );

        self::assertSame(
            <<<'CVOTES'
                #/Candidates: A ; B ; C
                #/Implicit Ranking: false
                #/Weight Allowed: false

                /EMPTY_RANKING/ * 2
                CVOTES,
            CondorcetElectionFormat::exportElectionToCondorcetElectionFormat(election: $this->election2, includeNumberOfSeats: false, aggregateVotes: true, inContext: true)
        );

        self::assertSame(
            <<<'CVOTES'
                #/Candidates: A ; B ; C
                #/Implicit Ranking: false
                #/Weight Allowed: false

                /EMPTY_RANKING/
                /EMPTY_RANKING/
                CVOTES,
            CondorcetElectionFormat::exportElectionToCondorcetElectionFormat(election: $this->election2, includeNumberOfSeats: false, aggregateVotes: false, inContext: true)
        );

        $this->election2->setImplicitRanking(true);

        self::assertSame(
            <<<'CVOTES'
                #/Candidates: A ; B ; C
                #/Implicit Ranking: true
                #/Weight Allowed: false

                A = B = C * 2
                CVOTES,
            CondorcetElectionFormat::exportElectionToCondorcetElectionFormat(election: $this->election2, includeNumberOfSeats: false, aggregateVotes: true, inContext: true)
        );

        self::assertSame(
            <<<'CVOTES'
                #/Candidates: A ; B ; C
                #/Implicit Ranking: true
                #/Weight Allowed: false

                A = B = C
                A = B = C
                CVOTES,
            CondorcetElectionFormat::exportElectionToCondorcetElectionFormat(election: $this->election2, includeNumberOfSeats: false, aggregateVotes: false, inContext: true)
        );

        $this->election2 = new Election;
        $this->election2->parseCandidates('A;B;C;D');
        $this->election2->setImplicitRanking(true);

        $this->election2->addVote(new Vote('A>B'));

        self::assertSame(
            <<<'CVOTES'
                #/Candidates: A ; B ; C ; D
                #/Implicit Ranking: true
                #/Weight Allowed: false

                A > B > C = D * 1
                CVOTES,
            CondorcetElectionFormat::exportElectionToCondorcetElectionFormat(election: $this->election2, includeNumberOfSeats: false, aggregateVotes: true, inContext: true)
        );

        self::assertSame(
            $cvotes_implicit_without_context =
                            <<<'CVOTES'
                                #/Candidates: A ; B ; C ; D
                                #/Implicit Ranking: true
                                #/Weight Allowed: false

                                A > B * 1
                                CVOTES,
            CondorcetElectionFormat::exportElectionToCondorcetElectionFormat(election: $this->election2, includeNumberOfSeats: false, aggregateVotes: true, inContext: false)
        );

        self::assertSame(
            str_replace(' * 1', '', $cvotes_implicit_without_context),
            CondorcetElectionFormat::exportElectionToCondorcetElectionFormat(election: $this->election2, includeNumberOfSeats: false, aggregateVotes: false, inContext: false)
        );
    }

    public function testParseVoteCandidateCoherence(): void
    {
        $this->election1 = new Election;

        $cA = $this->election1->addCandidate('A');
        $cB = $this->election1->addCandidate('B');
        $cC = $this->election1->addCandidate('C');

        self::assertSame(2, $this->election1->parseVotes('
            A>B>C * 2
        '));

        $votes = $this->election1->getVotesList();

        foreach ($votes as $vote) {
            $ranking = $vote->getRanking();

            self::assertSame($cA, $ranking[1][0]);
            self::assertSame($cB, $ranking[2][0]);
            self::assertSame($cC, $ranking[3][0]);
        }
    }

    public function testParseVotesInvalidPath(): void
    {
        $this->expectException(FileDoesNotExistException::class);
        $this->expectExceptionMessageMatches('/bad_file.txt$/');

        $this->election1 = new Election;

        $this->election1->addCandidate('A');
        $this->election1->addCandidate('B');

        $this->election1->parseVotes('bad_file.txt', true);
    }

    public function testParseVotesWithoutFail(): void
    {
        $this->election1 = new Election;

        $this->election1->addCandidate('A');
        $this->election1->addCandidate('B');
        $this->election1->addCandidate('C');

        self::assertSame(2, $this->election1->parseVotesWithoutFail('
            A > B > C
            A > B > C * 4;tag1 || A > B > C*4 #Coucou
            A < B < C * 10
            D <> B
            A > B > C
        '));

        self::assertSame(10, $this->election1->countVotes());

        self::assertSame(2, $this->election1->parseVotesWithoutFail(__DIR__.'/../LargeElectionData/smallVote1.votes', true));

        self::assertSame(20, $this->election1->countVotes());

        self::assertSame(2, $this->election1->parseVotesWithoutFail(new \SplFileObject(__DIR__.'/../LargeElectionData/smallVote1.votes'), true));

        self::assertSame(30, $this->election1->countVotes());

        self::assertSame(2, $this->election1->parseVotesWithoutFail(new \SplFileInfo(__DIR__.'/../LargeElectionData/smallVote1.votes'), true));

        self::assertSame(40, $this->election1->countVotes());
    }

    public function testParseVotesWithoutFailInvalidPath(): void
    {
        $this->expectException(FileDoesNotExistException::class);
        $this->expectExceptionMessageMatches('/bad_file.txt$/');

        $this->election1 = new Election;

        $this->election1->addCandidate('A');
        $this->election1->addCandidate('B');

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

        $election->allowsVoteWeight(true);

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

        $election->allowsVoteWeight(false);

        self::assertSame(
            14,
            $election->sumVotesWeight()
        );

        self::assertNotSame(
            'A = D > C > B',
            $election->getResult('Schulze Winning')->getResultAsString()
        );

        $election->allowsVoteWeight(!$election->isVoteWeightAllowed());

        $election->removeVote($voteWithWeight);

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
        $this->expectException(\JsonException::class);

        $election = new Election;

        $election->addCandidate('A');
        $election->addCandidate('B');
        $election->addCandidate('C');

        $votes = [];

        $votes[]['vote'] = 'B>C>A';
        $votes[]['vote'] = new \stdClass; // Invalid Vote
        $votes[]['vote'] = ['C', 'B', 'A'];

        self::assertSame(2, $election->addVotesFromJson(json_encode($votes)));

        self::assertSame(
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

        self::assertSame(
            <<<'VOTES'
                A > B > C ^42 * 5
                B > C > A * 1
                C > B > A * 1
                VOTES,
            $election->getVotesListAsString()
        );
        self::assertSame(5, $election->countVotes('tag1'));

        $election->addVotesFromJson(json_encode($votes).'{42');
    }

    public function testaddCandidatesFromJson(): never
    {
        $this->expectException(CandidateExistsException::class);
        $this->expectExceptionMessage('This candidate already exists: candidate2');

        $election = new Election;

        $candidates = ['candidate1 ', 'candidate2'];

        $election->addCandidatesFromJson(json_encode($candidates));

        self::assertSame(2, $election->countCandidates());

        self::assertEquals(['candidate1', 'candidate2'], $election->getCandidatesListAsString());

        $election->addCandidatesFromJson(json_encode(['candidate2']));
    }

    public function testaddCandidatesFromInvalidJson(): never
    {
        $this->expectException(\JsonException::class);

        $election = new Election;

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

        self::assertEmpty($this->election2->getVotesList());
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
        self::assertSame($result1, $election->getResult('Schulze'));
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

        self::assertNotSame($result1, $election->getResult('Schulze'));
        self::assertSame($result1->getResultAsString(), $election->getResult('Schulze')->getResultAsString());

        self::assertNotSame($vote1, $election->getVotesList()[0]);
        self::assertSame($vote1->getSimpleRanking(), $election->getVotesList()[0]->getSimpleRanking());
        self::assertTrue($election->getVotesList()[0]->haveLink($election));
        self::assertFalse($vote1->haveLink($election));
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

        self::assertInstanceOf(Election::class, $e);
    }

    public function testCloneElection(): void
    {
        $this->election1->computeResult();

        $cloneElection = clone $this->election1;

        self::assertNotSame($this->election1->getVotesManager(), $cloneElection->getVotesManager());
        self::assertSame($this->election1->getVotesList(), $cloneElection->getVotesList());

        self::assertSame($this->election1->getCandidatesList(), $cloneElection->getCandidatesList());

        self::assertNotSame($this->election1->getPairwise(), $cloneElection->getPairwise());
        self::assertEquals($this->election1->getExplicitPairwise(), $cloneElection->getExplicitPairwise());

        self::assertNotSame($this->election1->getTimerManager(), $cloneElection->getTimerManager());

        self::assertSame($this->election1->getVotesList()[0], $cloneElection->getVotesList()[0]);

        self::assertTrue($cloneElection->getVotesList()[0]->haveLink($this->election1));
        self::assertTrue($cloneElection->getVotesList()[0]->haveLink($cloneElection));
    }

    public function testPairwiseArrayAccess(): void
    {
        $this->election1->computeResult();

        self::assertTrue($this->election1->getPairwise()->offsetExists(1));
    }

    public function testGetCandidateObjectFromKey(): void
    {
        self::assertSame($this->candidate2, $this->election1->getCandidateObjectFromKey(1));

        self::assertNull($this->election1->getCandidateObjectFromKey(42));
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

        self::assertTrue($this->election1->setStateTovote());

        self::assertSame(ElectionState::VOTES_REGISTRATION, $this->election1->getState());
    }

    public function testAddSameVote(): never
    {
        $this->expectException(VoteException::class);
        $this->expectExceptionMessage('Problem handling vote: seats are already registered');

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

        self::assertNull($weakref->get());
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


        self::assertSame(
            $electionRef->getResult($method)->getResultAsArray(true),
            $electionTest->getResult($method)->getResultAsArray(true)
        );
    }

    public static function MethodsListProvider(): array
    {
        $r = [];

        foreach (Condorcet::getAuthMethods() as $method) {
            $r[] = [$method];
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

        self::assertEmpty($election1->debugGetCalculator());
        self::assertEmpty($election2->debugGetCalculator());

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
        self::assertSame(100, $this->election1->getNumberOfSeats());

        $this->election1->setNumberOfSeats(5);

        self::assertSame(5, $this->election1->getNumberOfSeats());
    }
}
