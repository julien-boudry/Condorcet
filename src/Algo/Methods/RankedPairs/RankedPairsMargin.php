<?php declare(strict_types=1);
/*
    Part of RANKED PAIRS method Module - From the original Condorcet PHP

    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

namespace CondorcetPHP\Condorcet\Algo\Methods\RankedPairs;

/** @internal */
class RankedPairsMargin extends RankedPairsCore
{
    // Method Name
    public const array METHOD_NAME = ['Ranked Pairs Margin', 'RankedPairsMargin', 'Tideman Margin', 'RP Margin', 'Ranked Pairs', 'RankedPairs', 'Tideman method'];

    protected const RP_VARIANT VARIANT = RP_VARIANT::MARGIN;
}
