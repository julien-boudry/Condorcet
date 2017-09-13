<?php
/*
    Minimax part of the Condorcet PHP Class

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace Condorcet\Algo\Methods;

use Condorcet\Algo\Methods\PairwiseStatsBased_Core;
use Condorcet\Algo\MethodInterface;

// Minimax is a Condorcet Algorithm | http://en.wikipedia.org/wiki/Schulze_method
class MinimaxWinning extends PairwiseStatsBased_Core implements MethodInterface
{
    // Method Name
    public const METHOD_NAME = ['Minimax Winning','MinimaxWinning','Minimax','Minimax_Winning','Simpson','Simpson-Kramer','Simpson-Kramer Method','Simpson Method'];

    protected $_countType = 'worst_pairwise_defeat_winning';


/////////// COMPUTE ///////////

    //:: SIMPSON ALGORITHM. :://

    protected function looking (array $challenge) : int
    {
        return min($challenge);
    }
}