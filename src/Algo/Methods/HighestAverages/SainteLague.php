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
    final public const IS_PROPORTIONAL = true;

    // Method Name
    public const METHOD_NAME = ['SainteLague', 'Sainte-Laguë', 'Webster', 'Major Fractions Method'];

    protected function computeQuotient (int $votesWeight, int $seats): float
    {
        return (float) ($votesWeight / ($seats * 2 + 1));
    }
}
