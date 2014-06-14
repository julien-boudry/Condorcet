<?php
/*
	Basic Condorcet Winner & Loser core part of the Condorcet PHP Class

	Version : 0.11

	By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
	https://github.com/julien-boudry/Condorcet_Schulze-PHP_Class
*/

namespace Condorcet ;


// Condorcet Basic Class, provide natural Condorcet winner or looser
class Condorcet_Basic implements namespace\Condorcet_Algo
{
	// Config
	protected $_Pairwise ;
	protected $_CandidatesCount ;
	protected $_Candidates ;

	// Basic Condorcet
	protected $_CondorcetWinner ;
	protected $_CondorcetLoser ;


	public function __construct (array $config)
	{
		$this->_Pairwise = $config['_Pairwise'] ;
		$this->_CandidatesCount = $config['_CandidatesCount'] ;
		$this->_Candidates = $config['_Candidates'] ;
	}


/////////// PUBLIC ///////////


	public function getResult ($options = null)
	{
		return array (
						1 => $this->getWinner(),
						2 => $this->getLoser()
					) ;
	}

	// Get the Schulze ranking
	public function getStats ()
	{
		return null ;
	}


	// Get a Condorcet certified winner. If there is none = null. You can force a winner choice with alternative supported methods ($substitution)
	public function getWinner ()
	{
		// Cache
		if ( $this->_CondorcetWinner !== null )
		{
			return $this->_CondorcetWinner ;
		}

			//////

		// Basic Condorcet calculation
		foreach ( $this->_Pairwise as $candidate_key => $candidat_detail )
		{
			$winner = true ;

			foreach ($candidat_detail['win'] as $challenger_key => $win_count )
			{
				if	( $win_count <= $candidat_detail['lose'][$challenger_key] )
				{
					$winner = false ;
					break ;
				}
			}

			if ($winner)
			{
				$this->_CondorcetWinner = $candidate_key ;

				return $this->_CondorcetWinner ;
			}
		}

			return null ;
	}

	// Get a Condorcet certified loser. If there is none = null. You can force a winner choice with alternative supported methods ($substitution)
	public function getLoser ()
	{
		// Cache
		if ( $this->_CondorcetLoser !== null )
		{
			return $this->_CondorcetLoser ;
		}

			//////

		// Basic Condorcet calculation
		foreach ( $this->_Pairwise as $candidate_key => $candidat_detail )
		{
			$loser = true ;

			foreach ( $candidat_detail['lose'] as $challenger_key => $lose_count )
			{
				if	( $lose_count <= $candidat_detail['win'][$challenger_key] )
				{  
					$loser = false ;
					break ;
				}
			}

			if ($loser)
			{ 
				$this->_CondorcetLoser = $candidate_key ;

				return $this->_CondorcetLoser ;
			}
		}

			return null ;
	}

}

// Registering algorithm
namespace\Condorcet::addAlgos('Condorcet_Basic') ;
