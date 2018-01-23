<?php
/*
    Part of SCHULZE method Module - From the original Condorcet PHP

    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace Condorcet\Algo\Methods;

use Condorcet\Algo\Methods\Schulze_Core;
use Condorcet\CondorcetException;

class SchulzeMargin extends Schulze_Core
{
    // Method Name
    public const METHOD_NAME = ['Schulze Margin','SchulzeMargin','Schulze_Margin'];

    protected function schulzeVariant (int &$i, int &$j) : int {
        return $this->_selfElection->getPairwise(false)[$i]['win'][$j] - $this->_selfElection->getPairwise(false)[$j]['win'][$i];
    }
}
