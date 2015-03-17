<?php

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