<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\Datasets;

use CondorcetPHP\Condorcet\Condorcet;

class MethodsDatasets
{
    public static function MethodsListProvider(): array
    {
        $methods = Condorcet::getAuthMethods(withNonDeterministicMethods: false);
        array_walk($methods, static fn(&$m): array => $m = [$m]);

        return $methods;
    }
}
