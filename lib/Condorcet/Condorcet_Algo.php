<?php
/*
	Condorcet PHP Class, with Schulze Methods and others !

	Version : 0.91

	By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
	https://github.com/julien-boudry/Condorcet
*/
namespace Condorcet ;

// Interface with the aim of verifying the good modular implementation of algorithms.
interface Condorcet_Algo
{
	public function getResult($options);
	public function getStats();
}