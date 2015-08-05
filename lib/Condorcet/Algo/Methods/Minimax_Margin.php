<?php
/*
    Minimax part of the Condorcet PHP Class

    Last modified at: Condorcet Class v0.93

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
namespace Condorcet\Algo\Methods;
use Condorcet\CondorcetException;

class Minimax_Margin extends namespace\Minimax
{
    // Method Name
    const METHOD_NAME = 'Minimax_Margin';

    protected function makeRanking ()
    {
        $this->_Result = self::makeRanking_method('margin', $this->_Stats);
    }
}
