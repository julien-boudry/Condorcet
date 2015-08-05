<?php
/*
    Schulze part of the Condorcet PHP Class

    Last modified at: Condorcet Class v0.93

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
namespace Condorcet\Algo\Methods;

class Schulze_Margin extends namespace\SchulzeCore
{
    // Method Name
    const METHOD_NAME = 'Schulze_Margin';
    protected static $method_alias = ['Schulze Margin'];

    protected function schulzeVariant (&$i, &$j) {
        return $this->_selfElection->getPairwise(false)[$i]['win'][$j] - $this->_selfElection->getPairwise(false)[$j]['win'][$i];
    }
}
