<?php
/*
	Part of the Condorcet PHP Class, with Copeland Methods and others !

	Version : 0.8

	By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
	https://github.com/julien-boudry/Condorcet_Copeland-PHP_Class 
*/

namespace Condorcet ;

// Registering algorithm
namespace\Condorcet::addAlgos('Copeland') ;


// Copeland is a Condorcet Algorithm | http://en.wikipedia.org/wiki/Copeland_method
class Copeland
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
	public function getResult ()
	{
		// Cache
		if ( $this->_Result !== null )
		{
			return $this->_Result ;
		}

			//////

		// Comparison calculation
		$this->makeComparison() ;

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

	protected function makeComparison ()
	{
		foreach ($this->_Pairwise as $candidate_key => $candidate_data)
		{
			$this->_Comparison[$candidate_key]['win'] = 0 ;
			$this->_Comparison[$candidate_key]['null'] = 0 ;
			$this->_Comparison[$candidate_key]['lose'] = 0 ;
			$this->_Comparison[$candidate_key]['balance'] = 0 ;


			foreach ($candidate_data['win'] as $opponenent['key'] => $opponenent['lose']) 
			{
				if ( $opponenent['lose'] > $candidate_data['lose'][$opponenent['key']] )
				{
					$this->_Comparison[$candidate_key]['win']++ ;
					$this->_Comparison[$candidate_key]['balance']++ ;
				}
				elseif ( $opponenent['lose'] === $candidate_data['lose'][$opponenent['key']] )
				{
					$this->_Comparison[$candidate_key]['null']++ ;
				}
				else
				{
					$this->_Comparison[$candidate_key]['lose']++ ;
					$this->_Comparison[$candidate_key]['balance']-- ;
				}
			}
		}
	}

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
					$this->_Result[$rank][] = namespace\Condorcet::getStatic_CandidateId($candidate, $this->_Candidates) ;

					$done++ ; unset($challenge[$candidate]) ;
				}
			}

			$this->_Result[$rank] = implode(',', $this->_Result[$rank]) ;
			$rank++ ;
		}
	}

}
