<?php
/*
    Part of RANKED PAIRS method Module - From the original Condorcet PHP

    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace Condorcet\Algo\Methods;

use Condorcet\Algo\Methods\PairwiseStatsBased_Core;
use Condorcet\Algo\MethodInterface;

// Minimax is a Condorcet Algorithm | http://en.wikipedia.org/wiki/Schulze_method
class RankedPairsMargin extends RankedPairs_Core
{
    // Method Name
    public const METHOD_NAME = ['Ranked Pairs Margin','RankedPairsMargin','Tideman Margin','RP Margin','Ranked Pairs','RankedPairs','Tideman method'];

    protected const RP_VARIANT_1 = 'margin';
}
