<?php
/*
    Schulze part of the Condorcet PHP Class

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
namespace Condorcet\Algo\Methods;

use Condorcet\Algo\Methods\Schulze_Core;
use Condorcet\CondorcetException;

class SchulzeMargin extends Schulze_Core
{
    // Method Name
    const METHOD_NAME = ['Schulze Margin','SchulzeMargin','Schulze_Margin'];

    protected function schulzeVariant (&$i, &$j) {
        return $this->_selfElection->getPairwise(false)[$i]['win'][$j] - $this->_selfElection->getPairwise(false)[$j]['win'][$i];
    }
}
