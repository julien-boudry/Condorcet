<?php
/*
    Part of BORDA COUNT method Module - From the original Condorcet PHP

    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Algo\Methods\Borda;

use CondorcetPHP\Condorcet\CondorcetDocAttributes\{PublicAPI};
use CondorcetPHP\Condorcet\Result;
use CondorcetPHP\Condorcet\Algo\{Method, MethodInterface};
use CondorcetPHP\Condorcet\Algo\Methods\Borda\BordaCount;

class DowdallSystem extends BordaCount
{
    // Method Name
    public const METHOD_NAME = ['DowdallSystem','Dowdall System','Nauru', 'Borda Nauru'];

    protected function getScoreByCandidateRanking (int $CandidatesRanked) : float
    {
        return (float) 1 / ($CandidatesRanked + 1);
    }
}
