<?php
/*
    Part of Highest Averages Methods module - From the original Condorcet PHP

    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Algo\Methods\HighestAverages;

use CondorcetPHP\Condorcet\Algo\{MethodInterface};

# Copeland is a proportional algorithm | https://en.wikipedia.org/wiki/Webster/Sainte-Lagu%C3%AB_method
class SainteLague extends HighestAverages_Core implements MethodInterface
{
    public static int|float $optionFirstDivisor = 1;

    // Method Name
    public const array METHOD_NAME = ['Sainte-Laguë', 'SainteLague', 'Webster', 'Major Fractions Method'];

    protected function computeQuotient(int $votesWeight, int $seats): float
    {
        $divisor = ($seats !== 0) ? ($seats * 2 + 1) : self::$optionFirstDivisor;

        return (float) ($votesWeight / $divisor);
    }
}
