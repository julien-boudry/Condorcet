<?php
/*
	Part of the Condorcet PHP Class, with Schulze Methods and others !

	Version : 0.8

	By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
	https://github.com/julien-boudry/Condorcet_Schulze-PHP_Class 
*/

namespace Condorcet ;

// Registering algorithm
namespace\Condorcet::addAlgos('Condorcet_Basic') ;


// Condorcet Basic Class, provide natural Condorcet winner or looser
class Condorcet_Basic
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


	public function getResult ()
	{
		return array (
						1 => $this->getWinner(),
						2 => $this->getLoser()
					) ;
	}

	// Get the Schulze ranking
	public function getStats ()
	{
		return Condorcet::getStatic_Pairwise($this->_Pairwise, $this->_Candidates) ;
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
				$this->_CondorcetWinner = namespace\Condorcet::getStatic_CandidateId($candidate_key, $this->_Candidates) ;

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
				$this->_CondorcetLoser = namespace\Condorcet::getStatic_CandidateId($candidate_key, $this->_Candidates) ;

				return $this->_CondorcetLoser ;
			}
		}

			return null ;
	}

}
