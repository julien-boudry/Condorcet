<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests;

use CondorcetPHP\Condorcet\{Candidate, Election, Vote};
use CondorcetPHP\Condorcet\Throwable\{CandidateDoesNotExistException, VoteException, VoteInvalidFormatException, VoteNotLinkedException};
use CondorcetPHP\Condorcet\Tools\Converters\CEF\CondorcetElectionFormat;
use CondorcetPHP\Condorcet\Utils\CondorcetUtil;
use PHPUnit\Framework\TestCase;

class VoteTest extends TestCase
{
    private readonly Election $election1;

    private readonly Candidate $candidate1;
    private readonly Candidate $candidate2;
    private readonly Candidate $candidate3;
    private readonly Candidate $candidate4;
    private readonly Candidate $candidate5;
    private readonly Candidate $candidate6;

    protected function setUp(): void
    {
        $this->election1 = new Election;

        $this->candidate1 = $this->election1->addCandidate('candidate1');
        $this->candidate2 = $this->election1->addCandidate('candidate2');
        $this->candidate3 = $this->election1->addCandidate('candidate3');
        $this->candidate4 = new Candidate('candidate4');
        $this->candidate5 = new Candidate('candidate5');
        $this->candidate6 = new Candidate('candidate6');
    }

    public function testTimestamp(): void
    {
        $vote1 = new Vote([$this->candidate1, $this->candidate2, $this->candidate3]);

        self::assertEquals($vote1->getCreateTimestamp(), $vote1->getTimestamp());

        $vote1->setRanking([$this->candidate1, $this->candidate2, $this->candidate3]);

        self::assertLessThan($vote1->getTimestamp(), $vote1->getCreateTimestamp());
    }

    public function testDifferentRanking(): never
    {
        $this->expectException(VoteNotLinkedException::class);
        $this->expectExceptionMessage('The vote is not linked to an election');

        // Ranking 1
        $vote1 = new Vote([$this->candidate1, $this->candidate2, $this->candidate3]);

        $newRanking1 = $vote1->getRanking();

        // Ranking 2
        self::assertTrue(
            $vote1->setRanking(
                [$this->candidate1, $this->candidate2, $this->candidate3]
            )
        );

        self::assertSame(
            $newRanking1,
            $vote1->getRanking()
        );

        // Ranking 3
        self::assertTrue(
            $vote1->setRanking(
                [4=> $this->candidate1, 6=> $this->candidate2, 14 => $this->candidate3]
            )
        );

        self::assertSame(
            $newRanking1,
            $vote1->getRanking()
        );

        // Add vote into an election
        self::assertSame(
            $this->election1->addVote($vote1),
            $vote1
        );

        // Ranking 4
        self::assertTrue(
            $vote1->setRanking(
                [$this->candidate1, $this->candidate2]
            )
        );

        self::assertSame(
            $newRanking1,
            $vote1->getContextualRanking($this->election1)
        );

        self::assertCount(
            2,
            $vote1->getRanking()
        );

        // Ranking 5
        self::assertTrue(
            $vote1->setRanking(
                ['candidate1', 'candidate2']
            )
        );

        self::assertSame(
            $newRanking1,
            $vote1->getContextualRanking($this->election1)
        );

        // Ranking 6
        self::assertTrue(
            $vote1->setRanking(
                [42 => 'candidate2', 142=> 'candidate1']
            )
        );

        self::assertNotSame(
            $newRanking1,
            $vote1->getContextualRanking($this->election1)
        );

        // Ranking 7
        self::assertTrue(
            $vote1->setRanking(
                'candidate1>Kim Jong>candidate2>Trump'
            )
        );

        self::assertSame(
            $newRanking1,
            $vote1->getContextualRanking($this->election1)
        );


        // Ranking 8
        self::assertTrue(
            $vote1->setRanking([
                2=> $this->candidate2,
                1=> $this->candidate1,
                3=> $this->candidate3,
            ])
        );

        self::assertSame(
            $newRanking1,
            $vote1->getContextualRanking($this->election1)
        );


        // Ranking 9

        $vote = new Vote('candidate4 > candidate3 = candidate1 > candidate2');

        self::assertSame(
            CondorcetUtil::format($vote->getRanking()),
            [
                1 => 'candidate4',
                2 => ['candidate1', 'candidate3'],
                3 => 'candidate2',
            ]
        );

        $election = new Election;
        $election->parseCandidates('candidate2;candidate3;candidate4;candidate1');
        $election->addVote($vote);

        self::assertSame(
            CondorcetUtil::format($vote->getContextualRanking($election)),
            [
                1 => 'candidate4',
                2 => ['candidate1', 'candidate3'],
                3 => 'candidate2',
            ]
        );

        self::assertSame(
            $election->getResult()->getResultAsArray(true),
            [
                1 => 'candidate4',
                2 => ['candidate1', 'candidate3'],
                3 => 'candidate2',
            ]
        );

        // Contextual Ranking Fail

        $unexpectedElection = new Election;

        $vote1->getContextualRanking($unexpectedElection);
    }

    public function testArrayAccess(): void
    {
        // Ranking 1
        $vote = new Vote('candidate1 > candidate3 = candidate2 > candidate4');

        self::assertSame('candidate1', $vote[1][0]->getName());
        self::assertSame('candidate4', $vote[3][0]->getName());
    }

    public function testArrayAccessSetException(): void
    {
        $this->expectException(VoteException::class);

        // Ranking 1
        $vote = new Vote('candidate1 > candidate3 = candidate2 > candidate4');

        $vote[1] = 'candidateX';
    }

    public function testArrayAccessUnsetException(): void
    {
        $this->expectException(VoteException::class);

        // Ranking 1
        $vote = new Vote('candidate1 > candidate3 = candidate2 > candidate4');

        unset($vote[1]);
    }

    public function testSimpleRanking(): void
    {
        // Ranking 1
        $vote1 = new Vote('candidate1 > candidate3 = candidate2 > candidate4');

        self::assertSame($vote1->getSimpleRanking(), 'candidate1 > candidate2 = candidate3 > candidate4');

        $this->election1->addVote($vote1);

        self::assertSame($vote1->getSimpleRanking($this->election1), 'candidate1 > candidate2 = candidate3');
    }

    public function testProvisionalCandidateObject(): void
    {
        // Ranking 1
        $vote1 = new Vote([$this->candidate1, $this->candidate2, $this->candidate3]);
        $newRanking1 = $vote1->getRanking();
        $this->election1->addVote($vote1);

        // I
        self::assertTrue(
            $vote1->setRanking([
                new Candidate('candidate1'),
                $this->candidate2,
                $this->candidate3,
            ])
        );

        self::assertNotSame(
            $newRanking1,
            $vote1->getContextualRanking($this->election1)
        );

        self::assertSame(
            [1 => [$this->candidate2],
                2 => [$this->candidate3],
                3 => [$this->candidate1], ],
            $vote1->getContextualRanking($this->election1)
        );

        self::assertSame(
            [1 => 'candidate2',
                2 => 'candidate3',
                3 => 'candidate1', ],
            $vote1->getContextualRankingAsString($this->election1)
        );

        // II
        $vote2 = new Vote('candidate1>candidate2');

        self::assertTrue($vote2->getRanking()[1][0]->getProvisionalState());
        $vote2_firstRanking = $vote2->getRanking();

        $this->election1->addVote($vote2);

        self::assertFalse($vote2->getRanking()[1][0]->getProvisionalState());

        self::assertSame(
            [1 => [$this->candidate1],
                2 => [$this->candidate2],
                3 => [$this->candidate3], ],
            $vote2->getContextualRanking($this->election1)
        );

        self::assertNotEquals(
            $vote2_firstRanking,
            $vote2->getRanking()
        );


        // III
        $otherCandidate1 = new candidate('candidate1');
        $otherCandidate2 = new candidate('candidate2');

        $vote3 = new Vote([$otherCandidate1, $otherCandidate2, $this->candidate3]);

        self::assertFalse($vote3->getRanking()[1][0]->getProvisionalState());
        $vote3_firstRanking = $vote3->getRanking();

        $this->election1->addVote($vote3);

        self::assertFalse($vote2->getRanking()[1][0]->getProvisionalState());

        self::assertSame(
            [1 => [$this->candidate3],
                2 => [$this->candidate1, $this->candidate2], ],
            $vote3->getContextualRanking($this->election1)
        );

        self::assertSame(
            [1 => 'candidate3',
                2 => ['candidate1', 'candidate2'], ],
            $vote3->getContextualRankingAsString($this->election1)
        );

        self::assertEquals(
            $vote3_firstRanking,
            $vote3->getRanking()
        );
    }

    public function testDifferentElection(): void
    {
        $election1 = $this->election1;

        $election2 = new Election;
        $election2->addCandidate($this->candidate1);
        $election2->addCandidate($this->candidate2);
        $election2->addCandidate($this->candidate4);

        $vote1 = new Vote([
            $this->candidate1,
            $this->candidate2,
            $this->candidate3,
            $this->candidate4,
        ]);
        $vote1_originalRanking = $vote1->getRanking();

        $election1->addVote($vote1);
        $election2->addVote($vote1);

        self::assertSame($vote1_originalRanking, $vote1->getRanking());
        self::assertSame(
            [1=>[$this->candidate1], 2=>[$this->candidate2], 3=>[$this->candidate3]],
            $vote1->getContextualRanking($election1)
        );
        self::assertSame(
            [1=>[$this->candidate1], 2=>[$this->candidate2], 3=>[$this->candidate4]],
            $vote1->getContextualRanking($election2)
        );
        self::assertNotSame($vote1->getRanking(), $vote1->getContextualRanking($election2));

        self::assertTrue($vote1->setRanking([
            [$this->candidate5, $this->candidate2],
            $this->candidate3,
        ]));

        self::assertSame(
            [1=>[$this->candidate2], 2=>[$this->candidate3], 3=>[$this->candidate1]],
            $vote1->getContextualRanking($election1)
        );
        self::assertSame(
            [1=>[$this->candidate2], 2=>[$this->candidate1, $this->candidate4]],
            $vote1->getContextualRanking($election2)
        );
    }

    public function testValidTags(): void
    {
        $vote1 = new Vote([$this->candidate1, $this->candidate2, $this->candidate3]);

        $targetTags = ['tag1', 'tag2', 'tag3'];

        self::assertTrue($vote1->addTags(
            'tag1,tag2,tag3'
        ));

        self::assertSame(
            $targetTags,
            array_values($vote1->getTags())
        );

        self::assertTrue($vote1->removeAllTags());
        self::assertSame(
            [],
            $vote1->getTags()
        );

        self::assertTrue($vote1->addTags(
            ['tag1', 'tag2', 'tag3']
        ));

        self::assertSame(
            $targetTags,
            array_values($vote1->getTags())
        );

        self::assertEquals(['tag2'], $vote1->removeTags('tag2'));

        self::assertEquals(
            ['tag1', 'tag3'],
            array_values($vote1->getTags())
        );

        self::assertTrue($vote1->removeAllTags());

        self::assertSame(
            [],
            $vote1->getTags()
        );
    }

    public function testBadTagInput1(): never
    {
        $this->expectException(VoteInvalidFormatException::class);
        $this->expectExceptionMessage('The format of the vote is invalid: every tag must be of type string, integer given');

        $vote = new Vote('A');
        $vote->addTags(['tag1', 42]);
    }

    public function testBadTagInput2(): never
    {
        $this->expectException(VoteInvalidFormatException::class);
        $this->expectExceptionMessage('The format of the vote is invalid: found empty tag');

        $vote = new Vote('A');
        $vote->addTags(
            ['tag1 ', ' tag2', ' tag3 ', ' ']
        );

        self::assertSame(
            [],
            $vote->getTags()
        );

        self::assertTrue($vote->removeAllTags());
    }

    public function testBadTagInput3(): never
    {
        $this->expectException(VoteInvalidFormatException::class);
        $this->expectExceptionMessage('The format of the vote is invalid: found empty tag');

        $vote = new Vote('A');
        $vote->addTags(
            ' tag1,tag2 , tag3 ,'
        );

        self::assertSame(
            [],
            $vote->getTags()
        );

        self::assertTrue($vote->removeAllTags());
    }

    public function testBadTagInput4(): never
    {
        $this->expectException(VoteInvalidFormatException::class);
        $this->expectExceptionMessage('The format of the vote is invalid: every tag must be of type string, NULL given');

        $vote = new Vote('A');
        $vote->addTags(
            [null]
        );

        self::assertSame(
            [],
            $vote->getTags()
        );
    }

    public function testBadTagInput5(): void
    {
        $vote = new Vote('A');
        $vote->addTags(
            []
        );

        self::assertSame(
            [],
            $vote->getTags()
        );
    }

    public function testAddRemoveTags(): void
    {
        $vote1 = new Vote([$this->candidate1, $this->candidate2, $this->candidate3]);

        $vote1->addTags('tag1');
        $vote1->addTags(['tag2', 'tag3']);
        self::assertTrue($vote1->addTags('tag4,tag5'));

        self::assertSame(
            ['tag1', 'tag2', 'tag3', 'tag4', 'tag5'],
            $vote1->getTags()
        );

        self::assertsame([], $vote1->removeTags(''));
        $vote1->removeTags('tag1');
        $vote1->removeTags(['tag2', 'tag3']);
        self::assertsame($vote1->removeTags('tag4,tag5,tag42'), ['tag4', 'tag5']);

        self::assertSame(
            [],
            $vote1->getTags()
        );

        self::assertTrue($vote1->addTags('tag4,tag5'));
        self::assertTrue($vote1->removeAllTags());

        self::assertSame(
            [],
            $vote1->getTags()
        );
    }

    public function testTagsOnConstructorByStringInput(): void
    {
        $vote1 = new Vote('tag1,tag2 ||A > B >C', 'tag3,tag4');

        self::assertSame(['tag3', 'tag4', 'tag1', 'tag2'], $vote1->getTags());

        self::assertSame('A > B > C', $vote1->getSimpleRanking());

        $vote2 = new Vote((string) $vote1);

        self::assertSame((string) $vote1, (string) $vote2);
    }

    public function testCloneVote(): void
    {
        // Ranking 1
        $vote1 = new Vote('candidate1 > candidate3 = candidate2 > candidate4');

        $this->election1->addVote($vote1);

        $vote2 = clone $vote1;

        self::assertSame(0, $vote2->countLinks());
        self::assertSame(1, $vote1->countLinks());
    }

    public function testIterator(): void
    {
        $vote = new Vote('C > B > A');

        foreach ($vote as $key => $value) {
            self::assertSame($vote->getRanking()[$key], $value);
        }
    }

    public function testWeight(): never
    {
        $vote = new Vote('A>B>C^42');

        self::assertsame(42, $vote->getWeight());
        self::assertsame(2, $vote->setWeight(2));
        self::assertsame(2, $vote->getWeight());
        self::assertsame(1, $vote->getWeight($this->election1));

        $this->expectException(VoteInvalidFormatException::class);
        $this->expectExceptionMessage("The format of the vote is invalid: the value 'a' is not an integer.");

        $vote = new Vote('A>B>C^a');
    }

    public function testCustomTimestamp(): never
    {
        $this->expectException(VoteInvalidFormatException::class);
        $this->expectExceptionMessage('The format of the vote is invalid: Timestamp format of vote is not correct');

        $vote = new Vote(
            'A>B>C',
            null,
            $createTimestamp = microtime(true) - (3600 * 1000)
        );

        self::assertSame($createTimestamp, $vote->getTimestamp());

        $vote->setRanking('B>C>A', $ranking2Timestamp = microtime(true) - (60 * 1000));

        self::assertSame($ranking2Timestamp, $vote->getTimestamp());

        self::assertSame($createTimestamp, $vote->getCreateTimestamp());

        self::assertSame($createTimestamp, $vote->getHistory()[0]['timestamp']);

        self::assertSame($ranking2Timestamp, $vote->getHistory()[1]['timestamp']);

        $vote->setRanking('A', 1);
    }

    public function testHashCode(): void
    {
        $vote = new Vote('A>B>C');

        $hashCode[1] = $vote->getHashCode();

        $vote->addTags('tag1');

        $hashCode[2] = $vote->getHashCode();

        $vote->setRanking('C>A>B');

        $hashCode[3] = $vote->getHashCode();

        $vote->setRanking('C>A>B');

        $hashCode[4] = $vote->getHashCode();

        self::assertNotsame($hashCode[2], $hashCode[1]);
        self::assertNotsame($hashCode[3], $hashCode[2]);
        self::assertNotSame($hashCode[4], $hashCode[3]);
    }

    public function testCountRanks(): void
    {
        $vote = new Vote('A>B=C>D');

        self::assertsame(3, $vote->countRanks());
        self::assertsame(4, $vote->countRankingCandidates());
    }

    public function testCountRankingCandidates(): void
    {
        $vote = new Vote('A>B>C');

        self::assertsame(3, $vote->countRankingCandidates());
    }

    public function testInvalidWeight(): never
    {
        $this->expectException(VoteInvalidFormatException::class);
        $this->expectExceptionMessage('The format of the vote is invalid: the vote weight can not be less than 1');

        $vote = new Vote('A>B>C');

        $vote->setWeight(0);
    }

    public function testInvalidTag1(): never
    {
        $this->expectException(\TypeError::class);

        $vote = new Vote('A>B>C');

        $vote->addTags(true);
    }

    public function testInvalidTag2(): never
    {
        $this->expectException(\TypeError::class);

        $vote = new Vote('A>B>C');

        $vote->addTags(42);
    }

    public function testRemoveCandidate(): never
    {
        $vote1 = new Vote('candidate1 > candidate2 > candidate3 ^ 42');

        $this->election1->addVote($vote1);

        self::assertSame('candidate1 > candidate2 > candidate3', $this->election1->getResult()->getResultAsString());

        $vote1->removeCandidate('candidate2');

        self::assertSame('candidate1 > candidate3 ^42', $vote1->getSimpleRanking());

        self::assertSame('candidate1 > candidate3 > candidate2', $this->election1->getResult()->getResultAsString());

        $vote1->removeCandidate($this->candidate3);

        self::assertSame('candidate1 > candidate2 = candidate3', $this->election1->getResult()->getResultAsString());

        $this->expectException(CandidateDoesNotExistException::class);
        $this->expectExceptionMessage('This candidate does not exist: candidate4');

        $vote1->removeCandidate($this->candidate4);
    }

    public function testRemoveCandidateInvalidInput(): never
    {
        $vote1 = new Vote('candidate1 > candidate2 > candidate3 ^ 42');

        $this->expectException(\TypeError::class);

        $vote1->removeCandidate([]);
    }

    public function testVoteHistory(): void
    {
        $this->election1->addCandidate($this->candidate4);
        $this->election1->addCandidate($this->candidate5);
        $this->election1->addCandidate($this->candidate6);


        $vote1 = $this->election1->addVote(['candidate1', 'candidate2']);

        self::assertCount(1, $vote1->getHistory());

        // -------

        $vote2 = $this->election1->addVote('candidate1 > candidate2');

        self::assertCount(1, $vote2->getHistory());

        // -------

        $vote3 = new Vote(['candidate1', 'candidate2']);

        $this->election1->addVote($vote3);

        self::assertCount(2, $vote3);

        // -------

        $this->election1->parseVotes('voterParsed || candidate1 > candidate2');

        $votes_lists = $this->election1->getVotesListGenerator('voterParsed', true);
        $vote4 = $votes_lists->current();

        self::assertCount(1, $vote4->getHistory());

        // -------

        $vote5 = new Vote([$this->candidate5, $this->candidate6]);

        $this->election1->addVote($vote5);

        self::assertCount(1, $vote5->getHistory());

        // -------

        $vote6 = new Vote([$this->candidate5, 'candidate6']);

        $this->election1->addVote($vote6);

        self::assertCount(2, $vote6->getHistory());

        // -------

        $vote7 = new Vote([$this->candidate6, 'candidate8']);

        $candidate8 = $vote7->getAllCandidates()[1];

        self::assertsame('candidate8', $candidate8->getName());

        self::assertTrue($candidate8->getProvisionalState());

        $this->election1->addVote($vote7);

        self::assertTrue($candidate8->getProvisionalState());

        self::assertCount(1, $vote7->getHistory());
    }

    public function testBadRankingInput1(): never
    {
        $this->expectException(\TypeError::class);

        $vote = new Vote(42);
    }

    public function testBadRankingInput2(): never
    {
        $this->expectException(VoteInvalidFormatException::class);
        $this->expectExceptionMessage('The format of the vote is invalid');

        $candidate = new Candidate('A');

        $vote = new Vote([$candidate, $candidate]);
    }

    public function testEmptyVoteContextualInRanking(): void
    {
        $vote = $this->election1->addVote('candidate4 > candidate5');

        self::assertSame(
            [1 => [$this->candidate1, $this->candidate2, $this->candidate3]],
            $vote->getContextualRanking($this->election1)
        );

        $cr = $vote->getContextualRankingAsString($this->election1);

        self::assertSame(
            [1 => ['candidate1', 'candidate2', 'candidate3']],
            $cr
        );
    }

    public function testNonEmptyVoteContextualInRanking(): void
    {
        $vote = $this->election1->addVote('candidate1 = candidate2 = candidate3');

        self::assertSame(
            [1 => [$this->candidate1, $this->candidate2, $this->candidate3]],
            $vote->getContextualRanking($this->election1)
        );

        $cr = $vote->getContextualRankingAsString($this->election1);

        self::assertSame(
            [1 => ['candidate1', 'candidate2', 'candidate3']],
            $cr
        );
    }


    // https://github.com/julien-boudry/Condorcet/issues/32
    public function testDuplicateCandidates1(): never
    {
        $this->expectException(VoteInvalidFormatException::class);
        $this->expectExceptionMessage('The format of the vote is invalid');

        new Vote('Spain>Japan>France>Netherlands>Australia>France');
    }


    // https://github.com/julien-boudry/Condorcet/issues/32
    public function testDuplicateCandidates2(): void
    {
        $election = new Election;
        $election->parseCandidates('Spain;Japan;France;Netherlands;Australia');

        $vote = $election->addVote('Spain>Japan>France>Netherlands>Australia>france');

        self::assertSame(
            'Spain > Japan > France > Netherlands > Australia',
            $vote->getSimpleRanking($election)
        );
    }

    public function testEmptySpecialKeyWord(): void
    {
        $vote1 = new Vote(CondorcetElectionFormat::SPECIAL_KEYWORD_EMPTY_RANKING);
        $vote2 = new Vote('  '.CondorcetElectionFormat::SPECIAL_KEYWORD_EMPTY_RANKING.'  ');

        self::assertSame([], $vote1->getRanking());
        self::assertSame([], $vote2->getRanking());
    }

    public function testGetAllCandidates(): void
    {
        $vote = new Vote('candidate1>candidate2>candidate8>candidate3=candidate4=candidate6>candidate5');
        $election = new Election;

        $election->addCandidate($this->candidate1);
        $election->addCandidate($this->candidate2);
        $election->addCandidate($this->candidate3);
        $election->addCandidate($this->candidate4);
        $election->addCandidate($this->candidate5);
        $election->addCandidate($this->candidate6);

        $election->addVote($vote);

        // var_dump(array_map(fn ($v) => (string) $v ,$vote->getAllCandidates()));

        self::assertSame([
            $this->candidate1,
            $this->candidate2,
            $vote[3][0],
            $this->candidate3,
            $this->candidate4,
            $this->candidate6,
            $this->candidate5,

        ], $vote->getAllCandidates());

        self::assertSame([
            $this->candidate1,
            $this->candidate2,
            $this->candidate3,
            $this->candidate4,
            $this->candidate6,
            $this->candidate5,

        ], $vote->getAllCandidates($election));
    }
}
