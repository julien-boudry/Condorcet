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
    }

    public function testTimestamp ()
    {
        $vote1 = new Vote([$this->candidate1,$this->candidate2,$this->candidate3]);

        $this->assertEquals($vote1->getCreateTimestamp(), $vote1->getTimestamp());

        $vote1->setRanking([$this->candidate1,$this->candidate2,$this->candidate3]);

        $this->assertLessThan($vote1->getTimestamp(), $vote1->getCreateTimestamp());
    }

    public function testDifferentRanking ()
    {
        // Ranking 1
        $vote1 = new Vote([$this->candidate1,$this->candidate2,$this->candidate3]);

        $newRanking1 = $vote1->getRanking();

        // Ranking 2
        $this->assertTrue(
            $vote1->setRanking(
                [$this->candidate1,$this->candidate2,$this->candidate3]
            )
        );

            $this->assertSame(
                $newRanking1,
                $vote1->getRanking()
            );

        // Ranking 3
        $this->assertTrue(
            $vote1->setRanking(
                [4=> $this->candidate1, 6=> $this->candidate2, 14 => $this->candidate3]
            )
        );

            $this->assertSame(
                $newRanking1,
                $vote1->getRanking()
            );

        // Add vote into an election
        $this->assertSame(
            $this->election1->addVote($vote1),
            $vote1
        );

        // Ranking 4
        $this->assertTrue(
            $vote1->setRanking(
                [$this->candidate1,$this->candidate2]
            )
        );

            $this->assertSame(
                $newRanking1,
                $vote1->getContextualVote($this->election1)
            );

            $this->assertCount(
                2,
                $vote1->getRanking()
            );

        // Ranking 5
        $this->assertTrue(
            $vote1->setRanking(
                ['candidate1','candidate2']
            )
        );

            $this->assertSame(
                $newRanking1,
                $vote1->getContextualVote($this->election1)
            );

        // Ranking 6
        $this->assertTrue(
            $vote1->setRanking(
                [42 => 'candidate2', 142=> 'candidate1']
            )
        );

            $this->assertNotSame(
                $newRanking1,
                $vote1->getContextualVote($this->election1)
            );

        // Ranking 7
        $this->assertTrue(
            $vote1->setRanking(
                "candidate1>Kim Jong>candidate2>Trump"
            )
        );

            $this->assertSame(
                $newRanking1,
                $vote1->getContextualVote($this->election1)
            );


        // Ranking 8
        $this->assertTrue(
            $vote1->setRanking([
                new Candidate('candidate1'),
                $this->candidate2,
                $this->candidate3
            ])
        );

            /* The new object will be ignored and replaced with the legitimate object of the same name from election. However, this behavior is confusing and is not really safe!
            We need to think about a major refactoring of how candidates are managed in the vote object. For version 1.3? */
            $this->assertSame(
                $newRanking1,
                $vote1->getContextualVote($this->election1)
            );


        // Ranking 9
        $this->assertTrue(
            $vote1->setRanking([
                2=> $this->candidate2,
                1=> $this->candidate1,
                3=> $this->candidate3
            ])
        );

            $this->assertSame(
                $newRanking1,
                $vote1->getContextualVote($this->election1)
            );
    }


    public function testTags ()
    {
        $vote1 = new Vote([$this->candidate1,$this->candidate2,$this->candidate3]);

        $targetTags = ['tag1','tag2','tag3'];

        $this->assertTrue($vote1->addTags(
            'tag1,tag2,tag3'
        ));

            $this->assertSame(
                $targetTags,
                array_values($vote1->getTags())
            );

            $this->assertTrue($vote1->removeAllTags());
            $this->assertSame(
                [],
                $vote1->getTags()
            );

        $this->assertTrue($vote1->addTags(
            ['tag1','tag2','tag3']
        ));

            $this->assertSame(
                $targetTags,
                array_values($vote1->getTags())
            );

            $this->assertTrue($vote1->removeAllTags());

        $this->assertTrue($vote1->addTags(
            ' tag1,tag2 , tag3 ,'
        ));

            $this->assertSame(
                $targetTags,
                array_values($vote1->getTags())
            );

            $this->assertTrue($vote1->removeAllTags());

        $this->assertTrue($vote1->addTags(
            ['tag1 ',' tag2',' tag3 ','',null,false]
        ));

            $this->assertSame(
                $targetTags,
                array_values($vote1->getTags())
            );

            $this->assertTrue($vote1->removeAllTags());
    }

}