<?php
/*
    Minimax part of the Condorcet PHP Class

    Last modified at: Condorcet Class v0.93

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
namespace Condorcet\Algo\Methods;
use Condorcet\CondorcetException;

// Beware, this method is not a Condorcet method ! Winner can be different than Condorcet Basic method
class MinimaxOpposition extends namespace\Minimax_Core
{
    // Method Name
    const METHOD_NAME = 'MinimaxOpposition,Minimax_Opposition,Minimax Opposition';

    protected function makeRanking ()
    {
        $this->_Result = self::makeRanking_method('opposition', $this->_Stats);
    }
}

