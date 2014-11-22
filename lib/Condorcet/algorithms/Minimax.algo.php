<?php
/*
	Minimax part of the Condorcet PHP Class

	Last modified at: Condorcet Class v0.10

	By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
	https://github.com/julien-boudry/Condorcet_Schulze-PHP_Class
*/

namespace Condorcet ;


// Schulze is a Condorcet Algorithm | http://en.wikipedia.org/wiki/Schulze_method
abstract class Minimax extends namespace\CondorcetAlgo implements namespace\Condorcet_Algo
{
	// Minimax
	protected $_Stats ;
	protected $_Result ;


/////////// PUBLIC ///////////


	// Get the Schulze ranking
	public function getResult ($options = null)
	{
		// Cache
		if ( $this->_Result !== null )
		{
			return $this->_Result ;
		}

			//////

		// Computing
		$this->computeMinimax ();

		// Ranking calculation
		$this->makeRanking () ;

		// Return
		return $this->_Result ;
	}


	// Get the Schulze ranking
	public function getStats ()
	{
		$this->getResult();

			//////

		$explicit = array() ;

		foreach ($this->_Stats as $candidate_key => $value)
		{
			$explicit[$this->_selfElection->getCandidateId($candidate_key)] = $value ;
		}

		return $explicit ;
	}



/////////// COMPUTE ///////////

	protected function computeMinimax ()
	{
		$this->_Stats = array() ;

		foreach ($this->_selfElection->getCandidatesList() as $candidate_key => $candidate_id)
		{			
			$lose_score			= array() ;
			$margin_score		= array() ;
			$opposition_score	= array() ;

			foreach ($this->_selfElection->getPairwise(false)[$candidate_key]['lose'] as $key_lose => $value_lose)
			{
				// Margin
				$margin = $value_lose - $this->_selfElection->getPairwise(false)[$candidate_key]['win'][$key_lose] ;
				$margin_score[] = $margin ;

				// Winning
				if ($margin > 0)
				{
					$lose_score[] = $value_lose ;
				}

				// Opposition
				$opposition_score[] = $value_lose ;
			}

			// Write result
				// Winning
			if (!empty($lose_score)) {$this->_Stats[$candidate_key]['winning'] = max($lose_score) ;}
			else {$this->_Stats[$candidate_key]['winning'] = 0 ;}
			
				// Margin
			$this->_Stats[$candidate_key]['margin'] = max($margin_score) ;

				// Opposition
			$this->_Stats[$candidate_key]['opposition'] = max($opposition_score) ;
		}
	}

	abstract protected function makeRanking () ;

	protected static function makeRanking_method ($type, array $stats)
	{
		$result = array() ;
		$values = array() ;

		foreach ($stats as $candidate_key => $candidate_Stats)
		{
			$values[$candidate_key] = $candidate_Stats[$type] ;
		}

		for ($rank = 1 ; !empty($values) ; $rank++)
		{
			$looking = min($values);

			foreach ($values as $candidate_key => $candidate_Stats)
			{
				if ($candidate_Stats === $looking)
				{
					$result[$rank][] = $candidate_key ;

					unset($values[$candidate_key]);
				}
			}
		}

		return $result ;
	}
}

class Minimax_Winning extends namespace\Minimax
{
	protected function makeRanking ()
	{
		$this->_Result = self::makeRanking_method('winning', $this->_Stats) ;
	}
}

class Minimax_Margin extends namespace\Minimax
{
	protected function makeRanking ()
	{
		$this->_Result = self::makeRanking_method('margin', $this->_Stats) ;
	}
}

// Beware, this method is not a Condorcet method ! Winner can be different than Condorcet Basic method
class Minimax_Opposition extends namespace\Minimax
{
	protected function makeRanking ()
	{
		$this->_Result = self::makeRanking_method('opposition', $this->_Stats) ;
	}
}

// Registering algorithm
namespace\Condorcet::addAlgos( array('Minimax_Winning','Minimax_Margin', 'Minimax_Opposition') ) ;
