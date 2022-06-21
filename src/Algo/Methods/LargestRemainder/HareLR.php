<?php
/*
    Part of Highest Averages Methods module - From the original Condorcet PHP

    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Algo\Methods\LargestRemainder;

use CondorcetPHP\Condorcet\Algo\{MethodInterface};
use CondorcetPHP\Condorcet\Algo\Tools\StvQuotas;

# Hare Largest Remainder is a proportional algorithm | https://en.wikipedia.org/wiki/Largest_remainder_method
class HareLR extends LargestRemainder_Core implements MethodInterface
{
    final public const IS_PROPORTIONAL = true;

    // Method Name
    public const METHOD_NAME = ['Hare-LR'];

    protected function computeQuotient (int $votesWeight, int $seats): float
    {
        return StvQuotas::HARE->getQuota($votesWeight, $seats);
    }
}
