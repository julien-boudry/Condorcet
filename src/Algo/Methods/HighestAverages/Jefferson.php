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

# Jefferson is a proportional algorithm | https://en.wikipedia.org/wiki/D%27Hondt_method
class Jefferson extends HighestAverages_Core implements MethodInterface
{
    // Method Name
    public const array METHOD_NAME = ['Jefferson', 'D\'Hondt', 'Thomas Jefferson'];


    /////////// COMPUTE ///////////

    protected function computeQuotient(int $votesWeight, int $seats): float
    {
        return (float) ($votesWeight / ($seats + 1));
    }
}
