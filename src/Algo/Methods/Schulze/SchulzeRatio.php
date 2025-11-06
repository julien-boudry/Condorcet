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

/**
 * Implements the Schulze Ratio variant of the Schulze method.
 *
 * @internal
 */
class SchulzeRatio extends SchulzeCore
{
    // Method Name
    public const array METHOD_NAME = ['Schulze Ratio', 'SchulzeRatio', 'Schulze_Ratio'];

    #[\Override]
    protected function schulzeVariant(int $i, int $j, Pairwise $pairwise): float
    {
        if ($pairwise[$j]['win'][$i] !== 0) {
            return (float) ($pairwise[$i]['win'][$j] / $pairwise[$j]['win'][$i]);
        } else {
            return (float) (($pairwise[$i]['win'][$j] + 1) / 1);
        }
    }
}
