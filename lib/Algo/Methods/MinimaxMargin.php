<?php
/*
    Minimax part of the Condorcet PHP Class

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
namespace Condorcet\Algo\Methods;

use Condorcet\Algo\Methods\Minimax_Core;
use Condorcet\CondorcetException;

class MinimaxMargin extends Minimax_Core
{
    // Method Name
    const METHOD_NAME = ['Minimax Margin','MinimaxMargin','MinimaxMargin','Minimax_Margin'];

    protected function makeRanking ()
    {
        $this->_Result = self::makeRanking_method('margin', $this->_Stats);
    }
}
