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

class SchulzeWinning extends Schulze_Core
{
    // Method Name
    const METHOD_NAME = ['Schulze Winning','Schulze','SchulzeWinning','Schulze_Winning','Schwartz Sequential Dropping','SSD','Cloneproof Schwartz Sequential Dropping','CSSD','Beatpath','Beatpath Method','Beatpath Winner','Path Voting','Path Winner'];

    protected function schulzeVariant (int &$i, int &$j) : int {
        return $this->_selfElection->getPairwise(false)[$i]['win'][$j];
    }
}
