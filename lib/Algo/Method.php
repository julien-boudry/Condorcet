<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Algo;

use CondorcetPHP\Condorcet\{CondorcetVersion, Election, Result};
use CondorcetPHP\Condorcet\Throwable\CondorcetException;

// Generic for Algorithms
abstract class Method
{
    use CondorcetVersion;

    public static ?int $MaxCandidates = null;

    protected Election $_selfElection;
    protected ?Result $_Result = null;

    public function __construct (Election $mother)
    {
        $this->_selfElection = $mother;

        if (!\is_null(static::$MaxCandidates) && $this->_selfElection->countCandidates() > static::$MaxCandidates) :
            throw new CondorcetException(101, static::METHOD_NAME[0].' is configured to accept only '.static::$MaxCandidates.' candidates');
        endif;
    }

    public function __destruct ()
    {
        unset($this->_selfElection, $this->_Result);
    }

    public function getResult () : Result
    {
        // Cache
        if ( $this->_Result !== null ) :
            return $this->_Result;
        endif;

        $this->compute();

        return $this->_Result;
    }

    abstract protected function getStats () : array;

    protected function createResult (array $result) : Result
    {
    	return new Result (
            static::METHOD_NAME[0],
            $this::class,
    		$this->_selfElection,
    		$result,
            $this->getStats()
    	);
    }
}
