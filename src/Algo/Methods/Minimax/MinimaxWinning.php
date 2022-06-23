<?php
/*
    Part of MINIMAX method Module - From the original Condorcet PHP

    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Algo\Methods\Minimax;

use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{Description, Example, FunctionReturn, PublicAPI, Related};
use CondorcetPHP\Condorcet\Algo\Methods\PairwiseStatsBased_Core;

// Minimax is a Condorcet Algorithm | http://en.wikipedia.org/wiki/Schulze_method
class MinimaxWinning extends PairwiseStatsBased_Core
{
    // Method Name
    public const METHOD_NAME = ['Minimax Winning','MinimaxWinning','Minimax','Minimax_Winning','Simpson','Simpson-Kramer','Simpson-Kramer Method','Simpson Method'];

    protected const COUNT_TYPE = 'worst_pairwise_defeat_winning';


    /////////// COMPUTE ///////////

    //:: SIMPSON ALGORITHM. :://

    protected function looking(array $challenge): int
    {
        return \min($challenge);
    }
}
