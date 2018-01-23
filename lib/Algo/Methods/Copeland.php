<?php
/*
    Part of COPELAND method Module - From the original Condorcet PHP

    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace Condorcet\Algo\Methods;

use Condorcet\Algo\Methods\PairwiseStatsBased_Core;
use Condorcet\Algo\MethodInterface;

// Copeland is a Condorcet Algorithm | http://en.wikipedia.org/wiki/Copeland_method
class Copeland extends PairwiseStatsBased_Core
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