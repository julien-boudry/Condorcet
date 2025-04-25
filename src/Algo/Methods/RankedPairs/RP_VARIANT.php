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
enum RP_VARIANT
{
    case UNDEFINED;
    case WINNING;
    case MARGIN;
    case MINORITY;
}
