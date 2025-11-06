<?php declare(strict_types=1);
/*
    Part of DODGSON TIDEMAN APPROXIMATION method Module - From the original Condorcet PHP

    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

namespace CondorcetPHP\Condorcet\Algo\Methods\Dodgson;

use CondorcetPHP\Condorcet\Algo\MethodInterface;
use CondorcetPHP\Condorcet\Algo\Methods\PairwiseStatsBased_Core;

/**
 * Implements the Dodgson Tideman approximation method.
 *
 * @internal
 */
class DodgsonTidemanApproximation extends PairwiseStatsBased_Core implements MethodInterface
{
    // Method Name
    public const array METHOD_NAME = ['Dodgson Tideman Approximation', 'DodgsonTidemanApproximation', 'Dodgson Tideman', 'DodgsonTideman'];

    protected const string COUNT_TYPE = 'sum_defeat_margin';


    /////////// COMPUTE ///////////

    //:: DODGSON ALGORITHM. :://

    #[\Override]
    protected function looking(array $challenge): int
    {
        return min($challenge);
    }
}
