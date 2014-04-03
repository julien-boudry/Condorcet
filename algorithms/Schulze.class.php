<?php
/*
	Part of the Condorcet PHP Class, with Schulze Methods and others !

	Version : 0.4

	By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
	https://github.com/julien-boudry/Condorcet_Schulze-PHP_Class 
*/


// Registering algorithm
Condorcet::add_algos('Schulze') ;


// Schulze is a Condorcet Algorithm | http://en.wikipedia.org/wiki/Schulze_method
class Condorcet_Schulze
{
	// Config
	protected $_Pairwise ;
	protected $_options_count ;
	protected $_options ;

	// Schulze
	protected $_Schulze_strongest_paths ;
	protected $_Schulze_result ;


	public function __construct (array $config)
	{
		$this->_Pairwise = $config['_Pairwise'] ;
		$this->_options_count = $config['_options_count'] ;
		$this->_options = $config['_options'] ;
	}



/////////// PUBLIC ///////////


	// Get the Schulze ranking
	public function get_result ()
	{
		// Cache
		if ( $this->_Schulze_result !== null )
		{
			return $this->_Schulze_result ;
		}

			//////

		// Format array
		$this->Schulze_strongest_array() ;

		// Strongest Paths calculation
		$this->strongest_paths() ;


		// Ranking calculation
		$this->Schulze_make_ranking() ;


		// Return
		return $this->_Schulze_result ;
	}


	// Get the Schulze ranking
	public function get_stats ()
	{
		$this->get_result();

			//////

		$explicit = array() ;

		foreach ($this->_Schulze_strongest_paths as $candidate_key => $candidate_value)
		{
			$candidate_key = Condorcet::get_static_option_id($candidate_key,$this->_options) ;

			foreach ($candidate_value as $option_key => $option_value)
			{
				$explicit[$candidate_key][Condorcet::get_static_option_id($option_key, $this->_options)] = $option_value ;
			}
		}

		return $explicit ;
	}


		// Get only the Schulze Winner(s)
		public function get_winner ()
		{
			// If there is not Cache
			if ( $this->_Schulze_result === null )
			{
				$this->get_result();
			}

			return $this->_Schulze_result[1] ;
		}

		// Get only the Schulze Loser(s)
		public function get_loser ()
		{
			// If there is not Cache
			if ( $this->_Schulze_result === null )
			{
				$this->get_result();
			}

			return $this->_Schulze_result[count($this->_Schulze_result)] ;
		}



/////////// COMPUTE ///////////


	//:: SCHULZE ALGORITHM. :://


	// Calculate the strongest Paths for Schulze Method
	protected function Schulze_strongest_array ()
	{
		$this->_Schulze_strongest_paths = array() ;

		foreach ( $this->_options as $option_key => $option_id )
		{
			$this->_Schulze_strongest_paths[$option_key] = array() ;

			// Format array for the strongest path
			foreach ( $this->_options as $option_key_r => $option_id_r )
			{
				if ($option_key_r != $option_key)
				{
					$this->_Schulze_strongest_paths[$option_key][$option_key_r]	= 0 ;
				}
			}

		}				
	}


	// Calculate the Strongest Paths
	protected function strongest_paths ()
	{
		foreach ($this->_options as $i => $i_value)
		{
			foreach ($this->_options as $j => $j_value)
			{
				if ($i !== $j)
				{
					if ( $this->_Pairwise[$i]['win'][$j] > $this->_Pairwise[$j]['win'][$i] )
					{
						$this->_Schulze_strongest_paths[$i][$j] = $this->_Pairwise[$i]['win'][$j] ;
					}
					else
					{
						$this->_Schulze_strongest_paths[$i][$j] = 0 ;
					}
				}
			}
		}
		 

		foreach ($this->_options as $i => $i_value)
		{
			foreach ($this->_options as $j => $j_value)
			{
				if ($i !== $j)
				{
					foreach ($this->_options as $k => $k_value)
					{
						if ($i !== $k && $j !== $k)
						{
							$this->_Schulze_strongest_paths[$j][$k] = 
											max( 
													$this->_Schulze_strongest_paths[$j][$k], 
													min( $this->_Schulze_strongest_paths[$j][$i], $this->_Schulze_strongest_paths[$i][$k] )
												) ;
						}
					}
				}
			}
		}
	}


	// Calculate && Format human readable ranking
	protected function Schulze_make_ranking ()
	{		
		$this->_Schulze_result = array() ;

		// Calculate ranking
		$done = array () ;
		$rank = 1 ;

		while (count($done) < $this->_options_count)
		{
			$to_done = array() ;

			foreach ( $this->_Schulze_strongest_paths as $candidate_key => $options_key )
			{
				if ( in_array($candidate_key, $done) )
				{
					continue ;
				}

				$winner = TRUE ;

				foreach ($options_key as $beaten_key => $beaten_value)
				{
					if ( in_array($beaten_key, $done) )
					{
						continue ;
					}

					if ( $beaten_value < $this->_Schulze_strongest_paths[$beaten_key][$candidate_key] )
					{
						$winner = FALSE ;
					}
				}

				if ($winner)
				{
					$this->_Schulze_result[$rank][] = $candidate_key ;

					$to_done[] = $candidate_key ;
				}
			}

			$done = array_merge($done, $to_done);

			$rank++ ;

		}


		// Format ranking
		foreach ( $this->_Schulze_result as $key => $value )
		{
			foreach ($value as $ord => $option_key)
			{
				$this->_Schulze_result[$key][$ord] = $this->_options[$option_key] ;
			}
		}

		foreach ( $this->_Schulze_result as $key => $value )
		{
			$this->_Schulze_result[$key] = implode(',',$value);
		}
	}

}