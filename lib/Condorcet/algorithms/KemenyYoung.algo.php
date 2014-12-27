<?php
/*
	Kemeny-Young part of the Condorcet PHP Class

	Last modified at: Condorcet Class v0.90

	By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
	https://github.com/julien-boudry/Condorcet_Schulze-PHP_Class
*/

namespace Condorcet ;

// Note : This class use some configuration method preset at the bottom of this file.

// Kemeny-Young is a Condorcet Algorithm | http://en.wikipedia.org/wiki/Kemeny%E2%80%93Young_method
class KemenyYoung extends namespace\CondorcetAlgo implements namespace\Condorcet_Algo
{
	// Limits
		/* If you need to put it on 9, You must use ini_set('memory_limit','1024M'); before. The first use will be slower because Kemeny-Young will write its cache for life, you must have write permissions in the directory lib / Condorcet / algorithms / KemenyYoung-Data /.
		Do not try to go to 10, it is not viable! */
		public static $_maxCandidates = 8 ;


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

		if (isset($options['noConflict']) && $options['noConflict'] === true)
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
				$candidate_key = $this->_selfElection->getCandidateId($candidate_key, true);
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

		$i = 0 ;
		$search = array();
		$replace = array();

		foreach ($this->_selfElection->getCandidatesList() as $candidate_id => $candidate_name)
		{
			$search[] = 's:'.(($i < 10) ? "2" : "3").':"C'.$i++.'"';
			$replace[] = 'i:'.$candidate_id;
		}

		$this->_PossibleRanking = unserialize( str_replace($search, $replace, file_get_contents($path)) );
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