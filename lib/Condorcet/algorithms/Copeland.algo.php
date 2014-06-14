<?php
/*
	Copeland part of the Condorcet PHP Class

	Version : 0.11

	By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
	https://github.com/julien-boudry/Condorcet_Schulze-PHP_Class
*/

namespace Condorcet ;


// Copeland is a Condorcet Algorithm | http://en.wikipedia.org/wiki/Copeland_method
class Copeland implements namespace\Condorcet_Algo
{
	// Config
	protected $_Pairwise ;
	protected $_CandidatesCount ;
	protected $_Candidates ;

	// Copeland
	protected $_Comparison ;
	protected $_Result ;


	public function __construct (array $config)
	{
		$this->_Pairwise = $config['_Pairwise'] ;
		$this->_CandidatesCount = $config['_CandidatesCount'] ;
		$this->_Candidates = $config['_Candidates'] ;
	}


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
		$this->_Comparison = namespace\Condorcet::makeStatic_PairwiseComparison($this->_Pairwise) ;

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
			$explicit[namespace\Condorcet::getStatic_CandidateId($candidate_key, $this->_Candidates)] = $value ;
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

		while ($done < $this->_CandidatesCount)
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
