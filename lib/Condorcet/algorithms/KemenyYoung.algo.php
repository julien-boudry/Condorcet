<?php
/*
	Ranked Pairs part of the Condorcet PHP Class

	Version : 0.9

	By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
	https://github.com/julien-boudry/Condorcet_Schulze-PHP_Class
*/

namespace Condorcet ;

// Note : This class use some configuration method preset at the bottom of this file.

// Kemeny-Young is a Condorcet Algorithm | http://en.wikipedia.org/wiki/Kemeny%E2%80%93Young_method
class KemenyYoung implements namespace\Condorcet_Algo
{
	// Limits
	public static $_maxCandidates = 6 ; // Beyond, and for the performance of PHP on recursive functions, it would be folly for this implementation.

		public static function setMaxCandidates ($max)
		{
			if (is_int($max))
			{
				self::$_maxCandidates = $max ;
			}
		}

	// Config
	protected $_Pairwise ;
	protected $_CandidatesCount ;
	protected $_Candidates ;

	// Kemeny Young
	protected $_PossibleRanking ;
	protected $_RankingScore ;
	protected $_Result ;


	public function __construct (array $config)
	{
		$this->_Pairwise = $config['_Pairwise'] ;
		$this->_CandidatesCount = $config['_CandidatesCount'] ;
		$this->_Candidates = $config['_Candidates'] ;

		if ($this->_CandidatesCount > self::$_maxCandidates)
		{
			namespace\Condorcet::error('','KemenyYoung is configured to accept only '.self::$_maxCandidates.' candidates',E_USER_ERROR) ;
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
				$candidate_key = namespace\Condorcet::getStatic_CandidateId($candidate_key, $this->_Candidates);
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
		$this->_PossibleRanking = array() ;

		$arrangements = $this->calcPermutation($this->_CandidatesCount);
		
		$i_arrangement = 1 ;
		foreach ($this->_Candidates as $CandidateId => $CandidateName)
		{
			$start = $i_arrangement ;

			// Create the possible and the first place

			for ($i = 1 ; $i <= ($arrangements / $this->_CandidatesCount) ; $i++)
			{
				// Less clean than to start the recursion from the beginning, but really much faster, and that from 4 candidates!
				$this->_PossibleRanking[$i_arrangement][1] = $CandidateId ;

				// Prepare empty arrays
				for ($ir = 2 ; $ir <= $this->_CandidatesCount ; $ir++ )
				{
					$this->_PossibleRanking[$i_arrangement][$ir] = null ;
				}

				$i_arrangement++ ;
			}

			// Recursive function to populate rank 2 to x rank.
			$this->rPossibleRanking($start, $i_arrangement - 1) ;
		}

		// Tested the integrity of the calculation of possible classifications
		/*
		$test = $this->_PossibleRanking ;
		foreach ($test as $key => $value)
		{
			unset($test[$key]);

			if (in_array($value, $test, true))
			{
				echo '<h2>ALERTE</h2>';
			}
		}
		*/
	}

		protected function calcPermutation ($n)
		{
			$result = $n ;

			for ($iteration = 1 ; $iteration < $n ; $iteration++)
			{
				$result = $result * ($n - $iteration) ;
			}

			return $result ;
		}

		protected function rPossibleRanking ($start, $end, $rank = 2)
		{
			$nbrCandidates = $this->_CandidatesCount - ($rank - 1) ;
			$each = $this->calcPermutation($nbrCandidates) / $nbrCandidates ;

			foreach ($this->_Candidates as $CandidateId => $CandidateName)
			{
				$do = 0 ;

				// Parcours des possibles
				for ($i = $start ; $i <= $end ; $i++)
				{
					if (	$do < $each &&
							is_null($this->_PossibleRanking[$i][$rank]) &&
							!in_array($CandidateId, $this->_PossibleRanking[$i], true)
						)
					{	
						$this->_PossibleRanking[$i][$rank] = $CandidateId ;
						$do++;
					}
				}
			}

			// Recursive
			if ($rank < $this->_CandidatesCount)
			{
				$rank++ ;

				for ($partielEnd = $start + $each - 1 ; $partielEnd <= $end ; $partielEnd += $each)
				{
					$this->rPossibleRanking($start, $partielEnd, $rank);
				}
			}
		}


	protected function calcRankingScore ()
	{
		$this->_RankingScore = array() ;

		foreach ($this->_PossibleRanking as $keyScore => $ranking) 
		{
			$this->_RankingScore[$keyScore] = 0 ;

			$do = array() ;

			foreach ($ranking as $candidateId)
			{
				$do[] = $candidateId ;

				foreach ($ranking as $rank => $rankCandidate)
				{
					if (!in_array($rankCandidate, $do))
					{
						$this->_RankingScore[$keyScore] += $this->_Pairwise[$candidateId]['win'][$rankCandidate];
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

/*
* Maximum number of candidates for this algorithm.
* The script can support six candidates in less than twenty seconds
* found in PHP 5.5 (Linux) * against fifty on a Windows system.
* But because five candidates calculation always under 150ms,
* we will use this number as a limit by default.
*
* The number of voters is indifferent.
*/
namespace\KemenyYoung::setMaxCandidates(5);
