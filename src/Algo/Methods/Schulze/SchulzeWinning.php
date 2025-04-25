<?php declare(strict_types=1);
/*
    Part of SCHULZE method Module - From the original Condorcet PHP

    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

namespace CondorcetPHP\Condorcet\Algo\Methods\Schulze;

use CondorcetPHP\Condorcet\Algo\Pairwise\Pairwise;

/** @internal */
class SchulzeWinning extends SchulzeCore
{
    // Method Name
    public const array METHOD_NAME = ['Schulze Winning', 'Schulze', 'SchulzeWinning', 'Schulze_Winning', 'Schwartz Sequential Dropping', 'SSD', 'Cloneproof Schwartz Sequential Dropping', 'CSSD', 'Beatpath', 'Beatpath Method', 'Beatpath Winner', 'Path Voting', 'Path Winner'];

    #[\Override]
    protected function schulzeVariant(int $i, int $j, Pairwise $pairwise): int
    {
        return $pairwise[$i]['win'][$j];
    }
}
