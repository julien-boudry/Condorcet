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

use CondorcetPHP\Condorcet\Election;

class SchulzeRatio extends Schulze_Core
{
    // Method Name
    public const array METHOD_NAME = ['Schulze Ratio', 'SchulzeRatio', 'Schulze_Ratio'];

    #[\Override]
    protected function schulzeVariant(int $i, int $j, Election $election): float
    {
        if ($election->getPairwise()[$j]['win'][$i] !== 0) {
            return (float) ($election->getPairwise()[$i]['win'][$j] / $election->getPairwise()[$j]['win'][$i]);
        } else {
            return (float) (($election->getPairwise()[$i]['win'][$j] + 1) / 1);
        }
    }
}
