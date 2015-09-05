<?php
/*
    Minimax part of the Condorcet PHP Class

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
namespace Condorcet\Algo\Methods;

use Condorcet\Algo\Methods\Minimax_Core;
use Condorcet\CondorcetException;

class MinimaxWinning extends Minimax_Core
{
    // Method Name
    const METHOD_NAME = 'Minimax Winning,MinimaxWinning,Minimax,Minimax_Winning';

    protected function makeRanking ()
    {
        $this->_Result = self::makeRanking_method('winning', $this->_Stats);
    }
}
