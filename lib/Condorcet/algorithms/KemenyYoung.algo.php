<?php
/*
	Kemeny-Young part of the Condorcet PHP Class

	Last modified at: Condorcet Class v0.15

	By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
	https://github.com/julien-boudry/Condorcet_Schulze-PHP_Class
*/

namespace Condorcet ;

// Note : This class use some configuration method preset at the bottom of this file.

// Kemeny-Young is a Condorcet Algorithm | http://en.wikipedia.org/wiki/Kemeny%E2%80%93Young_method
class KemenyYoung extends namespace\CondorcetAlgo implements namespace\Condorcet_Algo
{
	// Limits
	public static $_maxCandidates = 9 ; // Beyond, and for the performance of PHP on recursive functions, it would be folly for this implementation.

		public static function setMaxCandidates ($max) { /* backwards compatibility */ }


	// Kemeny Young
	protected $_PossibleRanking ;
	protected $_RankingScore ;
	protected $_Result ;


	public function __construct (namespace\Condorcet $mother)
	{
		parent::__construct($mother);

		if (!is_null(self::$_maxCandidates) && $this->_selfElection->countCandidates() > self::$_maxCandidates)
		{
			throw new namespace\CondorcetException(101,self::$_maxCandidates);
		}
	}


/////////// PUBLIC ///////////


	// Get the Kemeny ranking
	public function getResult ($options = null)
	{
		// Cache
		if ( $this->_Result === null )
		{
			$this->calcPossibleRanking();
			$this->calcRankingScore();
			$this->makeRanking();
		}

		if (!is_null($options) && isset($options['noConflict']) && $options['noConflict'] === true)
		{
			$conflicts = $this->conflictInfos() ;
			if ( $conflicts !== false)
			{
				return $this->conflictInfos() ;
			}
		}

		// Return
		return $this->_Result ;
	}


	public function getStats ()
	{
		$this->getResult();

			//////

		$explicit = array() ;

		foreach ($this->_PossibleRanking as $key => $value)
		{
			$explicit[$key] = $value ;

			// Human readable
			foreach ($explicit[$key] as &$candidate_key)
			{
				$candidate_key = $this->_selfElection->getCandidateId($candidate_key);
			}

			$explicit[$key]['score'] = $this->_RankingScore[$key] ;
		}

		return $explicit ;
	}

		protected function conflictInfos ()
		{
			$max = max($this->_RankingScore) ;

			$conflict = -1 ;
			foreach ($this->_RankingScore as $value)
			{
				if ($value === $max)
				{
					$conflict++ ;
				}
			}

			if ($conflict === 0) 
				{return false ;}
			else
			{
				return ($conflict + 1).';'.max($this->_RankingScore) ;
			}
		}


/////////// COMPUTE ///////////


	//:: Kemeny-Young ALGORITHM. :://

	protected function calcPossibleRanking ()
	{
		$path = __DIR__ . '/KemenyYoung-Data/'.$this->_selfElection->countCandidates().'.data' ;

		// But ... where are the data ?! Okay, old way now...
		if (!file_exists($path))
			{ $this->doPossibleRanking($path); }

		$this->_PossibleRanking = unserialize(file_get_contents($path));

		$i = 0 ;
		foreach ($this->_selfElection->getCandidatesList() as $candidate_id => $candidate_name)
		{
			$identity = 'C'.$i;

			foreach ($this->_PossibleRanking as &$onePossibleRanking)
			{
				$onePossibleRanking = str_replace($identity, $candidate_id, $onePossibleRanking);
			}

			$i++;
		}
	}

	protected function doPossibleRanking ($path)
	{
		( new namespace\Permutation ($this->_selfElection->countCandidates()) )->writeResults($path);
	}

	protected function calcRankingScore ()
	{
		$this->_RankingScore = array() ;
		$pairwise = $this->_selfElection->getPairwise(false);

		foreach ($this->_PossibleRanking as $keyScore => $ranking) 
		{
			$this->_RankingScore[$keyScore] = 0 ;

			$do = array() ;

			foreach ($ranking as $candidateId)
			{
				$do[] = $candidateId ;

				foreach ($ranking as $rank => $rankCandidate)
				{
					if (!in_array($rankCandidate, $do, true))
					{
						$this->_RankingScore[$keyScore] += $pairwise[$candidateId]['win'][$rankCandidate];
					}
				}
			}
		}
	}


	/*
	I do not know how in the very unlikely event that several possible classifications have the same highest score.
	In the current state, one of them is chosen arbitrarily.

	See issue on Github : https://github.com/julien-boudry/Condorcet_Schulze-PHP_Class/issues/6
	*/
	protected function makeRanking ()
	{
		$this->_Result = $this->_PossibleRanking[ array_search(max($this->_RankingScore), $this->_RankingScore, true) ];
	}

}

// Registering algorithm
namespace\Condorcet::addAlgos('KemenyYoung') ;