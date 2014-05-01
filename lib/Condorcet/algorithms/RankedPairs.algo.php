<?php
/*
	Copeland part of the Condorcet PHP Class

	Version : 0.9

	By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
	https://github.com/julien-boudry/Condorcet_Schulze-PHP_Class
*/

namespace Condorcet ;


// Ranker Pairs is a Condorcet Algorithm | http://en.wikipedia.org/wiki/Ranked_Pairs
class RankedPairs implements namespace\Condorcet_Algo
{
	// Config
	protected $_Pairwise ;
	protected $_CandidatesCount ;
	protected $_Candidates ;

	// Ranked Pairs
	protected $_Comparison ;
	protected $_Result ;


	public function __construct (array $config)
	{
		$this->_Pairwise = $config['_Pairwise'] ;
		$this->_CandidatesCount = $config['_CandidatesCount'] ;
		$this->_Candidates = $config['_Candidates'] ;
	}


/////////// PUBLIC ///////////


	// Get the Ranked Pairs ranking
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


	// Get the Ranked Pair ranking
	public function getStats ()
	{
		$this->getResult();

			//////

		$explicit = array() ;

		return $explicit ;
	}



/////////// COMPUTE ///////////


	//:: RANKED PAIRS ALGORITHM. :://

	protected function makeRanking ()
	{

	}

}

// Registering algorithm
namespace\Condorcet::addAlgos('RankedPairs') ;
