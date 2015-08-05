<?php
/*
    Minimax part of the Condorcet PHP Class

    Last modified at: Condorcet Class v0.93

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
namespace Condorcet\Algo\Methods;

class Minimax_Winning extends namespace\Minimax
{
    // Method Name
    const METHOD_NAME = 'Minimax_Winning';
    protected static $method_alias = 'Minimax';

    protected function makeRanking ()
    {
        $this->_Result = self::makeRanking_method('winning', $this->_Stats);
    }
}
