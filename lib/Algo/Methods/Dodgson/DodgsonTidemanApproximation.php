<?php
/*
    Part of DODGSON TIDEMAN APPROXIMATION method Module - From the original Condorcet PHP

    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Algo\Methods\Dodgson;

use CondorcetPHP\Condorcet\Algo\MethodInterface;
use CondorcetPHP\Condorcet\Algo\Methods\PairwiseStatsBased_Core;

// DODGSON Tideman is an approximation for Dodgson method | https://www.maa.org/sites/default/files/pdf/cmj_ftp/CMJ/September%202010/3%20Articles/6%2009-229%20Ratliff/Dodgson_CMJ_Final.pdf
class DodgsonTidemanApproximation extends PairwiseStatsBased_Core implements MethodInterface
{
    // Method Name
    public const METHOD_NAME = ['Dodgson Tideman Approximation','DodgsonTidemanApproximation','Dodgson Tideman','DodgsonTideman'];

    protected const COUNT_TYPE = 'sum_defeat_margin';


/////////// COMPUTE ///////////

    //:: DODGSON ALGORITHM. :://

    protected function looking (array $challenge) : int
    {
        return min($challenge);
    }
}
