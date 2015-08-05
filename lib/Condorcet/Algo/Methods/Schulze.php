<?php
/*
    Schulze part of the Condorcet PHP Class

    Last modified at: Condorcet Class v0.93

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
namespace Condorcet\Algo\Methods;

use Condorcet\CondorcetException;

class Schulze extends namespace\SchulzeCore
{
    // Method Name
    const METHOD_NAME = 'Schulze';
    protected static $method_alias = ['Schulze_Winning','Schulze Winning','Schwartz Sequential Dropping','SSD','Cloneproof Schwartz Sequential Dropping','CSSD', 'Beatpath','Beatpath Method','Beatpath Winner','Path Voting','Path Winner'];

    protected function schulzeVariant (&$i, &$j) {
        return $this->_selfElection->getPairwise(false)[$i]['win'][$j];
    }
}
