<?php
declare(strict_types=1);
namespace Condorcet;

use Condorcet\CondorcetException;
use Condorcet\Candidate;
use Condorcet\Election;
use Condorcet\Vote;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Condorcet\Vote
 */
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
                $vote1->getContextualVote($this->election1)
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
                $vote1->getContextualVote($this->election1)
            );

        // Ranking 6
        self::assertTrue(
            $vote1->setRanking(
                [42 => 'candidate2', 142=> 'candidate1']
            )
        );

            self::assertNotSame(
                $newRanking1,
                $vote1->getContextualVote($this->election1)
            );

        // Ranking 7
        self::assertTrue(
            $vote1->setRanking(
                "candidate1>Kim Jong>candidate2>Trump"
            )
        );

            self::assertSame(
                $newRanking1,
                $vote1->getContextualVote($this->election1)
            );


        // Ranking 8
        self::assertTrue(
            $vote1->setRanking([
                new Candidate('candidate1'),
                $this->candidate2,
                $this->candidate3
            ])
        );

            /* The new object will be ignored and replaced with the legitimate object of the same name from election. However, this behavior is confusing and is not really safe!
            We need to think about a major refactoring of how candidates are managed in the vote object. For version 1.3? */
            self::assertSame(
                $newRanking1,
                $vote1->getContextualVote($this->election1)
            );


        // Ranking 9
        self::assertTrue(
            $vote1->setRanking([
                2=> $this->candidate2,
                1=> $this->candidate1,
                3=> $this->candidate3
            ])
        );

            self::assertSame(
                $newRanking1,
                $vote1->getContextualVote($this->election1)
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
            $vote1->getContextualVote($election1)
        );
        self::assertSame(
            [1=>[$this->candidate1],2=>[$this->candidate2],3=>[$this->candidate4]],
            $vote1->getContextualVote($election2)
        );
        self::assertNotSame($vote1->getRanking(),$vote1->getContextualVote($election2));

        self::assertTrue($vote1->setRanking([
            [$this->candidate5,$this->candidate2],
            $this->candidate3,
        ]));

        self::assertSame(
            [1=>[$this->candidate2],2=>[$this->candidate3],3=>[$this->candidate1]],
            $vote1->getContextualVote($election1)
        );
        self::assertSame(
            [1=>[$this->candidate2],2=>[$this->candidate1,$this->candidate4]],
            $vote1->getContextualVote($election2)
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

}