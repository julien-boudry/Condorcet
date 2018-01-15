<?php
declare(strict_types=1);
namespace Condorcet;

use Condorcet\CondorcetException;
use Condorcet\Candidate;
use Condorcet\Election;
use Condorcet\Vote;

use PHPUnit\Framework\TestCase;


class VoteTest extends TestCase
{
    /**
     * @var election1
     */
    private $election1;

    public function setUp()
    {
        $this->election1 = new Election;

        $this->candidate1 = $this->election1->addCandidate('candidate1');
        $this->candidate2 = $this->election1->addCandidate('candidate2');
        $this->candidate3 = $this->election1->addCandidate('candidate3');
        $this->candidate4 = new Candidate('candidate4');
        $this->candidate5 = new Candidate('candidate5');
        $this->candidate6 = new Candidate('candidate6');
    }

    public function testTimestamp ()
    {
        $vote1 = new Vote([$this->candidate1,$this->candidate2,$this->candidate3]);

        self::assertEquals($vote1->getCreateTimestamp(), $vote1->getTimestamp());

        $vote1->setRanking([$this->candidate1,$this->candidate2,$this->candidate3]);

        self::assertLessThan($vote1->getTimestamp(), $vote1->getCreateTimestamp());
    }

    public function testDifferentRanking ()
    {
        // Ranking 1
        $vote1 = new Vote([$this->candidate1,$this->candidate2,$this->candidate3]);

        $newRanking1 = $vote1->getRanking();

        // Ranking 2
        self::assertTrue(
            $vote1->setRanking(
                [$this->candidate1,$this->candidate2,$this->candidate3]
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
                [$this->candidate1,$this->candidate2]
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
                ['candidate1','candidate2']
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
                "candidate1>Kim Jong>candidate2>Trump"
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
                3=> $this->candidate3
            ])
        );

            self::assertSame(
                $newRanking1,
                $vote1->getContextualRanking($this->election1)
            );
    }

    public function testSimpleRanking ()
    {
        // Ranking 1
        $vote1 = new Vote('candidate1 > candidate3 = candidate2 > candidate4');

        self::assertSame($vote1->getSimpleRanking(),'candidate1 > candidate2 = candidate3 > candidate4');

        $this->election1->addVote($vote1);

        self::assertSame($vote1->getSimpleRanking($this->election1),'candidate1 > candidate2 = candidate3');
    }

    public function testProvisionalCandidateObject ()
    {
        // Ranking 1
        $vote1 = new Vote([$this->candidate1,$this->candidate2,$this->candidate3]);
        $newRanking1 = $vote1->getRanking();
        $this->election1->addVote($vote1);

        // I
        self::assertTrue(
            $vote1->setRanking([
                new Candidate('candidate1'),
                $this->candidate2,
                $this->candidate3
            ])
        );

            self::assertNotSame(
                $newRanking1,
                $vote1->getContextualRanking($this->election1)
            );

            self::assertSame(
                [   1 => [$this->candidate2],
                    2 => [$this->candidate3],
                    3 => [$this->candidate1]  ],
                $vote1->getContextualRanking($this->election1)
            );


        // II
        $vote2 = new Vote ('candidate1>candidate2');

        self::assertTrue($vote2->getRanking()[1][0]->getProvisionalState());
        $vote2_firstRanking = $vote2->getRanking();

        $this->election1->addVote($vote2);

        self::assertFalse($vote2->getRanking()[1][0]->getProvisionalState());

        self::assertSame(
            [   1 => [$this->candidate1],
                2 => [$this->candidate2],
                3 => [$this->candidate3]  ],
            $vote2->getContextualRanking($this->election1)
        );

        self::assertNotEquals(
            $vote2_firstRanking,
            $vote2->getRanking()
        );


        // III
        $otherCandidate1 = new candidate ('candidate1');
        $otherCandidate2 = new candidate ('candidate2');

        $vote3 = new Vote ([$otherCandidate1,$otherCandidate2,$this->candidate3]);

        self::assertFalse($vote3->getRanking()[1][0]->getProvisionalState());
        $vote3_firstRanking = $vote3->getRanking();

        $this->election1->addVote($vote3);

        self::assertFalse($vote2->getRanking()[1][0]->getProvisionalState());

        self::assertSame(
            [   1 => [$this->candidate3],
                2 => [$this->candidate1,$this->candidate2]  ],
            $vote3->getContextualRanking($this->election1)
        );

        self::assertEquals(
            $vote3_firstRanking,
            $vote3->getRanking()
        );
    }

    public function testDifferentElection () {

        $election1 = $this->election1;

        $election2 = new Election;
        $election2->addCandidate($this->candidate1);
        $election2->addCandidate($this->candidate2);
        $election2->addCandidate($this->candidate4);

        $vote1 = new Vote ([
            $this->candidate1,
            $this->candidate2,
            $this->candidate3,
            $this->candidate4
        ]);
        $vote1_originalRanking = $vote1->getRanking();

        $election1->addVote($vote1);
        $election2->addVote($vote1);

        self::assertSame($vote1_originalRanking,$vote1->getRanking());
        self::assertSame(
            [1=>[$this->candidate1],2=>[$this->candidate2],3=>[$this->candidate3]],
            $vote1->getContextualRanking($election1)
        );
        self::assertSame(
            [1=>[$this->candidate1],2=>[$this->candidate2],3=>[$this->candidate4]],
            $vote1->getContextualRanking($election2)
        );
        self::assertNotSame($vote1->getRanking(),$vote1->getContextualRanking($election2));

        self::assertTrue($vote1->setRanking([
            [$this->candidate5,$this->candidate2],
            $this->candidate3,
        ]));

        self::assertSame(
            [1=>[$this->candidate2],2=>[$this->candidate3],3=>[$this->candidate1]],
            $vote1->getContextualRanking($election1)
        );
        self::assertSame(
            [1=>[$this->candidate2],2=>[$this->candidate1,$this->candidate4]],
            $vote1->getContextualRanking($election2)
        );

    }

    public function testTags ()
    {
        $vote1 = new Vote([$this->candidate1,$this->candidate2,$this->candidate3]);

        $targetTags = ['tag1','tag2','tag3'];

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
            ['tag1','tag2','tag3']
        ));

            self::assertSame(
                $targetTags,
                array_values($vote1->getTags())
            );

            self::assertEquals(['tag2'],$vote1->removeTags('tag2'));

            self::assertEquals(
                ['tag1','tag3'],
                array_values($vote1->getTags()));

            self::assertTrue($vote1->removeAllTags());

        self::assertTrue($vote1->addTags(
            ' tag1,tag2 , tag3 ,'
        ));

            self::assertSame(
                $targetTags,
                array_values($vote1->getTags())
            );

            self::assertTrue($vote1->removeAllTags());

        self::assertTrue($vote1->addTags(
            ['tag1 ',' tag2',' tag3 ','',null,false]
        ));

            self::assertSame(
                $targetTags,
                array_values($vote1->getTags())
            );

            self::assertTrue($vote1->removeAllTags());
    }

    public function testAddRemoveTags ()
    {
        $this->vote1 = new Vote ([$this->candidate1,$this->candidate2,$this->candidate3]);

        $this->vote1->addTags('tag1');
        $this->vote1->addTags(['tag2','tag3']);
        self::assertTrue($this->vote1->addTags('tag4,tag5'));

        self::assertSame(
            ['tag1','tag2','tag3','tag4','tag5'],
            $this->vote1->getTags()
        );

        self::assertsame([],$this->vote1->removeTags(''));
        $this->vote1->removeTags('tag1');
        $this->vote1->removeTags(['tag2','tag3']);
        self::assertsame($this->vote1->removeTags('tag4,tag5,tag42'),['tag4','tag5']);

        self::assertSame(
            [],
            $this->vote1->getTags()
        );

        self::assertTrue($this->vote1->addTags('tag4,tag5'));
        self::assertTrue($this->vote1->removeAllTags());

        self::assertSame(
            [],
            $this->vote1->getTags()
        );
    }

    public function testTagsOnConstructorByStringInput ()
    {
        $vote1 = new Vote('tag1,tag2 ||A > B >C','tag3,tag4');

        self::assertSame(['tag3','tag4','tag1','tag2'],$vote1->getTags());

        self::assertSame('A > B > C',$vote1->getSimpleRanking());

        $vote2 = new Vote ((string) $vote1);

        self::assertSame((string) $vote1,(string) $vote2);
    }

    public function testCloneVote ()
    {
        // Ranking 1
        $vote1 = new Vote('candidate1 > candidate3 = candidate2 > candidate4');

        $this->election1->addVote($vote1);

        $vote2 = clone $vote1;

        self::assertSame(0,$vote2->countLinks());
        self::assertSame(1,$vote1->countLinks());
    }

    public function testIterator ()
    {
        $vote = new Vote ('C > B > A');

        foreach ($vote as $key => $value) :
            self::assertSame($vote->getRanking()[$key],$value);
        endforeach;
    }

    public function testWeight()
    {
        $vote = new Vote ('A>B>C^42');

        self::assertsame(42,$vote->getWeight());
        self::assertsame(2,$vote->setWeight(2));
        self::assertsame(2,$vote->getWeight());
    }

    public function testCustomTimestamp()
    {
        $vote = new Vote (
            'A>B>C',
            null, 
            $createTimestamp = microtime(true) - (3600 * 1000));

        self::assertSame($createTimestamp, $vote->getTimestamp());

        $vote->setRanking('B>C>A',$ranking2Timestamp = microtime(true) - (60 * 1000));

        self::assertSame($ranking2Timestamp, $vote->getTimestamp());

        self::assertSame($createTimestamp, $vote->getCreateTimestamp());

        self::assertSame($createTimestamp, $vote->getHistory()[0]['timestamp']);

        self::assertSame($ranking2Timestamp, $vote->getHistory()[1]['timestamp']);

    }
}