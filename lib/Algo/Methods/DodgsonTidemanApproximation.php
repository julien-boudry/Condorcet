<?php
/*
    Dodgson part of the Condorcet PHP Class

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace Condorcet\Algo\Methods;

use Condorcet\Algo\Methods\PairwiseStatsBased_Core;
use Condorcet\Algo\MethodInterface;

// DODGSON Tideman is an approximation for Dodgson method | https://www.maa.org/sites/default/files/pdf/cmj_ftp/CMJ/September%202010/3%20Articles/6%2009-229%20Ratliff/Dodgson_CMJ_Final.pdf
class DodgsonTidemanApproximation extends PairwiseStatsBased_Core implements MethodInterface
{
    // Method Name
    public const METHOD_NAME = ['Dodgson Tideman Approximation','DodgsonTidemanApproximation','Dodgson Tideman','DodgsonTideman'];

    protected $_countType = 'sum_defeat_margin';


/////////// COMPUTE ///////////

    //:: DODGSON ALGORITHM. :://

    protected function looking (array $challenge) : int
    {
        return min($challenge);
    }

}
