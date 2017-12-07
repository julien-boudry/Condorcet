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
class RankedPairsWinning extends RankedPairs_Core
{
    // Method Name
    public const METHOD_NAME = ['Ranked Pairs','RankedPairs','Tideman method', 'Ranked Pairs Winning', 'Tideman Winning'];

    protected const RP_VARIANT_1 = 'win';
}