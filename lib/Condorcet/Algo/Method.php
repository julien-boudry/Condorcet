<?php
/*
    Condorcet PHP Class, with Schulze Methods and others !

    Version: 0.94

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
namespace Condorcet\Algo;

use Condorcet\Condorcet;

// Generic for Algorithms
abstract class Method
{
    protected $_selfElection;

    public function __construct (Condorcet $mother)
    {
        $this->_selfElection = $mother;
    }

    public static function getNameAlias () {
    	return (isset(self::$method_alias)) ? self::$method_alias : [];
    }
}
