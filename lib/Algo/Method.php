<?php
/*
    Condorcet PHP Class, with Schulze Methods and others !

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace Condorcet\Algo;

use Condorcet\CondorcetVersion;
use Condorcet\Election;
use Condorcet\Result;

// Generic for Algorithms
abstract class Method
{
    use CondorcetVersion;

    protected $_selfElection;
    protected $_Result;

    public function __construct (Election $mother)
    {
        $this->_selfElection = $mother;
    }

    abstract protected function getStats () : array;

    protected function createResult (array $result) : Result
    {
    	return new Result (
    		$this->_selfElection,
    		$result,
            $this->getStats()
    	);
    }
}
