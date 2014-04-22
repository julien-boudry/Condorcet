<?php
/*
	Part of the Condorcet PHP Class, with Schulze Methods and others !

	Version : 0.8

	By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
	https://github.com/julien-boudry/Condorcet_Schulze-PHP_Class 
*/

namespace Condorcet ;

// Registering algorithm
namespace\Condorcet::addAlgos('Schulze') ;


// Schulze is a Condorcet Algorithm | http://en.wikipedia.org/wiki/Schulze_method
class Schulze
{
	// Config
	protected $_Pairwise ;
	protected $_CandidatesCount ;
	protected $_Candidates ;

	// Schulze
	protected $_StrongestPaths ;
	protected $_Result ;


	public function __construct (array $config)
	{
		$this->_Pairwise = $config['_Pairwise'] ;
		$this->_CandidatesCount = $config['_CandidatesCount'] ;
		$this->_Candidates = $config['_Candidates'] ;
	}


/////////// PUBLIC ///////////


	// Get the Schulze ranking
	public function getResult ()
	{
		// Cache
		if ( $this->_Result !== null )
		{
			return $this->_Result ;
		}

			//////

		// Format array
		$this->prepareStrongestPath() ;

		// Strongest Paths calculation
		$this->makeStrongestPaths() ;


		// Ranking calculation
		$this->makeRanking() ;


		// Return
		return $this->_Result ;
	}


	// Get the Schulze ranking
	public function getStats ()
	{
		$this->getResult();

			//////

		$explicit = array() ;

		foreach ($this->_StrongestPaths as $candidate_key => $candidate_value)
		{
			$candidate_key = namespace\Condorcet::getStatic_CandidateId($candidate_key,$this->_Candidates) ;

			foreach ($candidate_value as $option_key => $option_value)
			{
				$explicit[$candidate_key][namespace\Condorcet::getStatic_CandidateId($option_key, $this->_Candidates)] = $option_value ;
			}
		}

		return $explicit ;
	}



/////////// COMPUTE ///////////


	//:: SCHULZE ALGORITHM. :://


	// Calculate the strongest Paths for Schulze Method
	protected function prepareStrongestPath ()
	{
		$this->_StrongestPaths = array() ;

		foreach ( $this->_Candidates as $option_key => $candidate_id )
		{
			$this->_StrongestPaths[$option_key] = array() ;

			// Format array for the strongest path
			foreach ( $this->_Candidates as $option_key_r => $candidate_id_r )
			{
				if ($option_key_r != $option_key)
				{
					$this->_StrongestPaths[$option_key][$option_key_r]	= 0 ;
				}
			}
		}				
	}


	// Calculate the Strongest Paths
	protected function makeStrongestPaths ()
	{
		foreach ($this->_Candidates as $i => $i_value)
		{
			foreach ($this->_Candidates as $j => $j_value)
			{
				if ($i !== $j)
				{
					if ( $this->_Pairwise[$i]['win'][$j] > $this->_Pairwise[$j]['win'][$i] )
					{
						$this->_StrongestPaths[$i][$j] = $this->_Pairwise[$i]['win'][$j] ;
					}
					else
					{
						$this->_StrongestPaths[$i][$j] = 0 ;
					}
				}
			}
		}
		 

		foreach ($this->_Candidates as $i => $i_value)
		{
			foreach ($this->_Candidates as $j => $j_value)
			{
				if ($i !== $j)
				{
					foreach ($this->_Candidates as $k => $k_value)
					{
						if ($i !== $k && $j !== $k)
						{
							$this->_StrongestPaths[$j][$k] = 
											max( 
													$this->_StrongestPaths[$j][$k], 
													min( $this->_StrongestPaths[$j][$i], $this->_StrongestPaths[$i][$k] )
												) ;
						}
					}
				}
			}
		}
	}


	// Calculate && Format human readable ranking
	protected function makeRanking ()
	{		
		$this->_Result = array() ;

		// Calculate ranking
		$done = array () ;
		$rank = 1 ;

		while (count($done) < $this->_CandidatesCount)
		{
			$to_done = array() ;

			foreach ( $this->_StrongestPaths as $candidate_key => $options_key )
			{
				if ( in_array($candidate_key, $done) )
				{
					continue ;
				}

				$winner = true ;

				foreach ($options_key as $beaten_key => $beaten_value)
				{
					if ( in_array($beaten_key, $done) )
					{
						continue ;
					}

					if ( $beaten_value < $this->_StrongestPaths[$beaten_key][$candidate_key] )
					{
						$winner = false ;
					}
				}

				if ($winner)
				{
					$this->_Result[$rank][] = $candidate_key ;

					$to_done[] = $candidate_key ;
				}
			}

			$done = array_merge($done, $to_done);

			$rank++ ;
		}
	}

}
