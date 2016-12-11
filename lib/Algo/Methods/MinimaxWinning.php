<?php
/*
    Minimax part of the Condorcet PHP Class

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace Condorcet\Algo\Methods;

use Condorcet\Algo\Methods\Minimax_Core;
use Condorcet\CondorcetException;
use Condorcet\Result;

class MinimaxWinning extends Minimax_Core
{
    // Method Name
    public const METHOD_NAME = ['Minimax Winning','MinimaxWinning','Minimax','Minimax_Winning'];

    protected function makeRanking () : void
    {
        $this->_Result = $this->createResult(self::makeRanking_method('winning', $this->_Stats));
    }
}
