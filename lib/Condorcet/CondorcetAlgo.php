<?php
/*
    Condorcet PHP Class, with Schulze Methods and others !

    Version : 0.92

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
namespace Condorcet ;

// Generic for Algorithms
abstract class CondorcetAlgo
{
    protected $_selfElection;

    public function __construct (namespace\Condorcet $mother)
    {
        $this->_selfElection = $mother;
    }
}
