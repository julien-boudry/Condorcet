<?php
/*
    Part of SCHULZE method Module - From the original Condorcet PHP

    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Algo\Methods\Schulze;

use CondorcetPHP\Condorcet\Algo\Tools\PairwiseDeductedApprovals;
use CondorcetPHP\Condorcet\Election;

class Gecko extends Schulze_Core
{
    // Method Name
    public const METHOD_NAME = ['Gecko'];

    protected readonly PairwiseDeductedApprovals $rankedCandidatesStats;
    protected readonly PairwiseDeductedApprovals $candidatesApprovals;

    protected function schulzeVariant(int $i, int $j, Election $election): float
    {
        $this->rankedCandidatesStats ??= new PairwiseDeductedApprovals(2, $election);
        $this->candidatesApprovals ??= new PairwiseDeductedApprovals(1, $election);

        $v = $election->getPairwise()[$i]['win'][$j];
        $V = $this->rankedCandidatesStats->sumWeightIfVotesIncludeCandidates([$i, $j]);
        $a = $this->candidatesApprovals->sumWeightIfVotesIncludeCandidates([$i]);

        if ($V === 0) {
            $V = 0.0000001;
        }

        return (float) $v + $V * sqrt($a/$V);
    }
}
