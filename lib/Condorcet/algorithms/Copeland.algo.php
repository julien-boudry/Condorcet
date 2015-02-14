<?php
/*
	Copeland part of the Condorcet PHP Class

	Last modified at: Condorcet Class v0.90

	By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
	https://github.com/julien-boudry/Condorcet_Schulze-PHP_Class
*/

namespace Condorcet ;


// Copeland is a Condorcet Algorithm | http://en.wikipedia.org/wiki/Copeland_method
class Copeland extends namespace\CondorcetAlgo implements namespace\Condorcet_Algo
{
	// Copeland
	protected $_Comparison ;
	protected $_Result ;


/////////// PUBLIC ///////////


	// Get the Coepland ranking
	public function getResult ($options = null)
	{
		// Cache
		if ( $this->_Result !== null )
		{
			return $this->_Result ;
		}

			//////

		// Comparison calculation
		$this->_Comparison = namespace\Condorcet::makeStatic_PairwiseComparison($this->_selfElection->getPairwise(false)) ;

		// Ranking calculation
		$this->makeRanking() ;


		// Return
		return $this->_Result ;
	}


	// Get the Copeland ranking
	public function getStats ()
	{
		$this->getResult();

			//////

		$explicit = array() ;

		foreach ($this->_Comparison as $candidate_key => $value)
		{
			$explicit[$this->_selfElection->getCandidateId($candidate_key, true)] = $value;
		}

		return $explicit ;
	}



/////////// COMPUTE ///////////


	//:: COPELAND ALGORITHM. :://

	protected function makeRanking ()
	{
		$this->_Result = array() ;

		// Calculate ranking
		$challenge = array () ;
		$rank = 1 ;
		$done = 0 ;

		foreach ($this->_Comparison as $candidate_key => $candidate_data)
		{
			$challenge[$candidate_key] = $candidate_data['balance'] ;
		}

		while ($done < $this->_selfElection->countCandidates())
		{
			$looking = max($challenge) ;

			foreach ($challenge as $candidate => $value)
			{
				if ($value === $looking)
				{
					$this->_Result[$rank][] = $candidate ;

					$done++ ; unset($challenge[$candidate]) ;
				}
			}

			$rank++ ;
		}
	}

}

// Registering algorithm
namespace\Condorcet::addAlgos('Copeland') ;
