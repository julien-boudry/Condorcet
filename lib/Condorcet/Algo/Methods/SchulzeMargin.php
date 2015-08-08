<?php
/*
    Schulze part of the Condorcet PHP Class

    Last modified at: Condorcet Class v0.93

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
namespace Condorcet\Algo\Methods;

use Condorcet\CondorcetException;

class SchulzeMargin extends namespace\Schulze_Core
{
    // Method Name
    const METHOD_NAME = 'SchulzeMargin,Schulze Margin,Schulze_Margin';

    protected function schulzeVariant (&$i, &$j) {
        return $this->_selfElection->getPairwise(false)[$i]['win'][$j] - $this->_selfElection->getPairwise(false)[$j]['win'][$i];
    }
}
