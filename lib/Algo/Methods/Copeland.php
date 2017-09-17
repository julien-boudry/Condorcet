<?php
/*
    Copeland part of the Condorcet PHP Class

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace Condorcet\Algo\Methods;

use Condorcet\Algo\Methods\PairwiseStatsBased_Core;
use Condorcet\Algo\MethodInterface;

// Copeland is a Condorcet Algorithm | http://en.wikipedia.org/wiki/Copeland_method
class Copeland extends PairwiseStatsBased_Core implements MethodInterface
{
    // Method Name
    public const METHOD_NAME = ['Copeland'];

    protected $_countType = 'balance';


/////////// COMPUTE ///////////

    //:: COPELAND ALGORITHM. :://

    protected function looking (array $challenge) : int
    {
        return max($challenge);
    }
}