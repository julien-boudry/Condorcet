<?php
/*
    Minimax part of the Condorcet PHP Class

    Last modified at: Condorcet Class v0.93

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
namespace Condorcet\Algo\Methods;
use Condorcet\CondorcetException;

class MinimaxWinning extends namespace\Minimax_Core
{
    // Method Name
    const METHOD_NAME = 'MinimaxWinning,Minimax,Minimax Winning,Minimax_Winning';

    protected function makeRanking ()
    {
        $this->_Result = self::makeRanking_method('winning', $this->_Stats);
    }
}
