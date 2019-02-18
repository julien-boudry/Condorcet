<?php
/*
    Part of SCHULZE method Module - From the original Condorcet PHP

    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Algo\Methods;


class SchulzeWinning extends Schulze_Core
{
    // Method Name
    public const METHOD_NAME = ['Schulze Winning','Schulze','SchulzeWinning','Schulze_Winning','Schwartz Sequential Dropping','SSD','Cloneproof Schwartz Sequential Dropping','CSSD','Beatpath','Beatpath Method','Beatpath Winner','Path Voting','Path Winner'];

    protected function schulzeVariant (int &$i, int &$j) : int {
        return $this->_selfElection->getPairwise(false)[$i]['win'][$j];
    }
}
