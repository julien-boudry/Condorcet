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
class Jefferson extends HighestAveragesMethod implements MethodInterface
{
    final public const IS_PROPORTIONAL = true;

    // Method Name
    public const METHOD_NAME = ['Jefferson'];


/////////// COMPUTE ///////////

    protected function computeQuotient (int $votes, int $seats): float
    {
        return (float) ($votes / ($seats + 1));
    }

}
