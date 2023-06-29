<?php

namespace CondorcetPHP\Condorcet\Tests\Algo\Tools;

use CondorcetPHP\Condorcet\Algo\Tools\Schulze_proportional_prefilter;
use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Tools\Converters\CondorcetElectionFormat;
use PHPUnit\Framework\TestCase;

class SchulzePrefilterTest extends TestCase
{
    private Election $election;
    //Checks that the Schulze-STV winners (as determined using Schulze's C++ program) are not filtered out.
    public function VerifyCandidatesOutput():void
    {
        $correctwinners = [
            2 => ['C', 'D'],
            6 => ['B', 'C', 'E', 'H', 'I'],
            19 => ['A', 'E', 'G'],
            22 => ['C', 'K'],
            52 => ['A', 'B', 'C', 'D', 'E', 'G'],
            53 => ['A', 'D', 'G', 'J'],
            57 => ['D', 'E'],
            69 => ['A', 'C', 'E'],
            76 => ['A', 'C'],
            86 => ['A', 'C', 'D', 'E'],
            88 => ['A', 'C', 'E', 'F', 'G', 'H'],
            95 => ['A', 'B']
        ];

        $this->election = new Election;

        foreach ($correctwinners as $electionCycle=>$winningSet) {
            (new CondorcetElectionFormat(__DIR__.'/../../../LargeElectionData/Tideman A'.$electionCycle.'.cvotes'))->setDataToAnElection($this->election);
            $prefilter = new Schulze_proportional_prefilter($this->election);
            $survivors = $prefilter->getResult('Schulze proportional prefilter')->getResultAsArray(true);
            foreach ($correctwinners[$electionCycle] as $candidate) {
                self::assertContains($candidate, $survivors, "Winning candidate '".$candidate."' in election ".$electionCycle." has been incorrectly eliminated by the pre-filter.");
            }
        }
    }
}