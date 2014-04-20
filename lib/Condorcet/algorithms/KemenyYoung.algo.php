<?php
/*
	Part of the Condorcet PHP Class, with Copeland Methods and others !

	Version : 0.8

	By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
	https://github.com/julien-boudry/Condorcet_Copeland-PHP_Class 
*/

namespace Condorcet ;

// Registering algorithm
namespace\Condorcet::addAlgos('KemenyYoung') ;


// Kemeny Young is a Condorcet Algorithm | http://en.wikipedia.org/wiki/Kemeny%E2%80%93Young_method
class KemenyYoung
{
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
	}


/////////// PUBLIC ///////////


	// Get the Kemeny ranking
	public function getResult ()
	{
		// Cache
		if ( $this->_Result !== null )
		{
			return $this->_Result ;
		}

			//////

		$this->calcPossibleRanking();
		$this->calcRankingScore();

		// Return
		return $this->_Result ;
	}


	public function getStats ()
	{
		$this->getResult();

			//////

		$explicit = array() ;

		return $explicit ;
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
				$this->_PossibleRanking[$i_arrangement][1] = $CandidateId ;

				for ($ir = 2 ; $ir <= $this->_CandidatesCount ; $ir++ )
				{
					$this->_PossibleRanking[$i_arrangement][$ir] = null ;
				}

				$i_arrangement++ ;
			}

			// Populate the nexts
			$this->rPossibleRanking($start, $i_arrangement) ;
		}

		// Tested the integrity of the calculation of possible classifications
		/*
		$test = $this->_PossibleRanking ;
		foreach ($test as $key => $value)
		{
			unset($test[$key]);

			if (array_search($value, $test, true))
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
				for ($i = $start ; $i < $end ; $i++)
				{
					if (	$do < $each &&
							is_null($this->_PossibleRanking[$i][$rank]) &&
							array_search($CandidateId, $this->_PossibleRanking[$i], true) === FALSE
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

				for ($partielEnd = $start + $each ; $partielEnd <= $end ; $partielEnd += $each)
				{
					$this->rPossibleRanking($start, $partielEnd, $rank);
				}
			}
		}

	protected function calcRankingScore ()
	{

	}

	protected function makeRanking ()
	{

	}

}
