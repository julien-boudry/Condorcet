<?php
/*
    Schulze part of the Condorcet PHP Class

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace Condorcet\Algo\Methods;

use Condorcet\Algo\Methods\Schulze_Core;
use Condorcet\CondorcetException;

class SchulzeMargin extends Schulze_Core
{
    // Method Name
    const METHOD_NAME = ['Schulze Margin','SchulzeMargin','Schulze_Margin'];

    protected function schulzeVariant (int &$i, int &$j) {
        return $this->_selfElection->getPairwise(false)[$i]['win'][$j] - $this->_selfElection->getPairwise(false)[$j]['win'][$i];
    }
}
