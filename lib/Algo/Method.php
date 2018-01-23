<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace Condorcet\Algo;

use Condorcet\CondorcetException;
use Condorcet\CondorcetVersion;
use Condorcet\Election;
use Condorcet\Result;

// Generic for Algorithms
abstract class Method
{
    use CondorcetVersion;

    public static $_maxCandidates = null;

    protected $_selfElection;
    protected $_Result;

    public function __construct (Election $mother)
    {
        $this->_selfElection = $mother;

        if (!is_null(static::$_maxCandidates) && $this->_selfElection->countCandidates() > static::$_maxCandidates) :
            throw new CondorcetException(101, static::METHOD_NAME[0].' is configured to accept only '.static::$_maxCandidates.' candidates');
        endif;
    }

    abstract protected function getStats () : array;

    protected function createResult (array $result) : Result
    {
    	return new Result (
            static::METHOD_NAME[0],
            get_class($this),
    		$this->_selfElection,
    		$result,
            $this->getStats()
    	);
    }
}
