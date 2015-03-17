<?php

namespace Condorcet ;

// Interface with the aim of verifying the good modular implementation of algorithms.
interface Condorcet_Algo
{
	public function getResult($options);
	public function getStats();
}