<?php



class Condorcet
{

	// Default method
	private static $_class_method = 'Schulze';

	static function setMethod ($method, $force = false)
	{
		self::$_class_method = $method ;

		if ($force === true)
		{
			$this->_force_method = false ;
			$this->set_method() ;
		}
	}



	// Paramètrage
	protected $_method ;
	protected $_options ;
	protected $_votes ;

	// Mécanique
	protected $_i_option_id	= 'A' ;
	protected $_vote_state	= 1 ;
	protected $_options_count = 0 ;
	protected $_force_method = FALSE ;

	// Result
	protected $_pairwise ;
	protected $_schulze_strongest_paths ;
	protected $_schulze_result ;


	// Constructor

	public function __construct ($method = null)
	{
		$this->set_method($method) ;

		$this->_options	= array() ;
		$this->_votes 	= array() ;
	}

	public function set_method ($method = null)
	{
		if ($method != null)
		{
			$this->_method = $method ;
			$this->_force_method = true ;
		}
		elseif ($this->_force_method === false)
		{
			$this->_method = self::$_class_method ;
		}
	}


	// Register vote option

	public function add_option ($option_id = null)
	{
		// only if the vote has not started
		if ( $this->_vote_state > 1 ) { return "error : Les votes ont commencé" ; }
		
		// Filter
		if ( !is_null($option_id) && !is_string($option_id) && !is_int($option_id) )
			{ return "error : Mauvaise saisie de l'option" ; }

		
		// Process
		if ( empty($option_id) ) // Option_id is empty ...
		{

			while ( !$this->try_add_option($this->_i_option_id) )
			{
				$this->_i_option_id++ ;
			}

			$this->_options[] = $this->_i_option_id ;
			$this->_options_count++ ;

			return $this->_i_option_id ;

		}
		else // Try to add the option_id
		{

			if ( $this->try_add_option($option_id) )
			{
				$this->_options[] = $option_id ;
				$this->_options_count++ ;

				return TRUE ;
			}
			else
			{
				return 'error : Option déjà existante' ;
			}

		}

	}

			protected function try_add_option ($option_id)
			{
				return !$this->option_id_exist($option_id) ;
			}



	public function remove_option ($option_id)
	{
		// only if the vote has not started
		if ( $this->_vote_state > 1 ) { return "error : Les votes ont commencé" ; }

		
		if ( !is_array($option_id) )
		{
			$option_id	= array($option_id) ;
		}


		foreach ($option_id as $value)
		{
			$option_key = $this->get_option_key ($value) ;
			if ( $option_key === FALSE )
				{ return "error : Mauvaise saisie de l'option à supprimer" ; }


			unset($this->_options[$option_key]) ;
		}

	}


		protected function get_option_key ($option_id)
		{
			return array_search($option_id, $this->_options, true);
		}


	public function count_options ()
	{
		return $this->_options_count ;
	}




	// Register votes

	public function close_options_config ()
	{
		if ( $this->_vote_state === 1 )
		{
			$this->_vote_state = 2 ;
		}
		else
		{
			return 'Error : Vote already close' ;
		}
	}

	public function add_vote (array $vote)
	{

		// Close option if needed
		if ( $this->_vote_state === 1 ) { $this->close_options_config(); }


		// Check array format
		if ( $this->check_vote_input($vote) === FALSE ) 
			{ return "error : Input de vote mal formulé"; }

		// Sort
		ksort($vote);

		// Register vote
		$this->register_vote($vote) ;


		return TRUE ;
	}

			protected function check_vote_input ($vote)
			{

				$list_option = array() ;

				if ( isset($vote[0]) || count($vote) > $this->_options_count || count($vote) < 1 )
					{ return FALSE ; }

				foreach ($vote as $key => $value)
				{

					// Check key
					if ( !is_numeric($key) || $key > $this->_options_count )
						{ return FALSE ; }


					// Check options
					if ( empty($value) )
						{ return FALSE ; }

					$options = explode(',', $value) ;


					foreach ($options as $option)
					{

						if ( !$this->option_id_exist($option) )
						{
							return FALSE ;
						}

						// Don't do 2x the same option
						if ( !in_array($option, $list_option)  ) { $list_option[] = $option ; }
							else { return FALSE ; }

					}

				}

				return TRUE ;
			}

			protected function option_id_exist ($option_id)
			{
				return in_array($option_id, $this->_options) ;
			}


			protected function register_vote ($vote)
			{
				$last_line_check = array() ;

				$i = 1 ;
				foreach ($vote as $value)
				{
					$vote_r[$i] = explode(',', $value) ;					

						// $last_line_check
						foreach ($vote_r[$i] as $option)
						{
							$last_line_check[] = $this->get_option_key($option) ;
						}

					$i++ ;
				}

				if ( count($last_line_check) < count($this->_options) )
				{
					foreach ($this->_options as $key => $value)
					{
						if ( !in_array($key,$last_line_check) )
						{
							$vote_r[$i][] = $value ;
						}
					}
				}



				$this->_votes[] = $vote_r ;

			}




		// Calc Votes

			// Public
			public function get_complete_result ($method = null)
			{
				// Method
				$this->set_method($method) ;

				// State
				$this->_vote_state = 3 ;


				if ( $this->_method === 'Schulze' )
				{
					$this->do_Pairwise() ;
					$this->calc_Schulze() ;
				}

			}

			public function get_condorcet_winner ()
			{
				// Method
				$this->set_method() ;

				// State
				$this->_vote_state = 3 ;


				$this->do_Pairwise() ;


				//////////////////


				foreach ( $this->_pairwise as $candidat_key => $candidat_detail )
				{
					$winner = TRUE ;

					foreach ($candidat_detail['win'] as $challenger_key => $win_count )
					{
						if		( $win_count <= $candidat_detail['loose'][$challenger_key] ) 
						{  
							$winner = FALSE ;
							break ;
						}

					}

					if ($winner)
						{ return $this->_options[$candidat_key] ; }
				}

				// There is no Winner
				return NULL ;

			}


			// Calc functions

			// Pairwise
			protected function do_Pairwise ()
			{

				
				// Format array
				$this->_pairwise = array() ;

				foreach ( $this->_options as $option_key => $option_id )
				{
					$this->_pairwise[$option_key] = array( 'win' => array(), 'null' => array(), 'loose' => array() ) ;


					foreach ( $this->_options as $option_key_r => $option_id_r )
					{
						if ($option_key_r != $option_key)
						{
							$this->_pairwise[$option_key]['win'][$option_key_r]		= 0 ;
							$this->_pairwise[$option_key]['null'][$option_key_r]	= 0 ;
							$this->_pairwise[$option_key]['loose'][$option_key_r]	= 0 ;
						}
					}
				}


				// Win && Null
				foreach ( $this->_votes as $vote_id => $vote_ranking )
				{
					$done_options = array() ;

					foreach ($vote_ranking as $options_in_rank)
					{
						$options_in_rank_keys = array() ;

						foreach ($options_in_rank as $option)
						{
							$options_in_rank_keys[] = $this->get_option_key($option) ;
						}


						foreach ($options_in_rank as $option)
						{
							$option_key = $this->get_option_key($option);


							// Process
							foreach ( $this->_options as $g_option_key => $g_option_id )
							{

								// Win
								if ( 
										$option_key !== $g_option_key && 
										!in_array($g_option_key, $done_options, true) && 
										!in_array($g_option_key, $options_in_rank_keys, true)
									)
								{

									$this->_pairwise[$option_key]['win'][$g_option_key]++ ;

									$done_options[] = $option_key ;
								}

								// Null
								if ( 
										$option_key !== $g_option_key &&
										count($options_in_rank) > 1 &&
										in_array($g_option_key, $options_in_rank_keys)
									)
								{
									$this->_pairwise[$option_key]['null'][$g_option_key]++ ;
								}
							}
						}

					}
				}


				// Loose
				foreach ( $this->_pairwise as $option_key => $option_results )
				{
					foreach ($option_results['win'] as $option_compare_key => $option_compare_value)
					{
						$this->_pairwise[$option_key]['loose'][$option_compare_key] = count($this->_votes) -
								(
									$this->_pairwise[$option_key]['win'][$option_compare_key] + 
									$this->_pairwise[$option_key]['null'][$option_compare_key]
								) ;
					}
				}


				$this->_pairwise ;

			}




			// Schulze
			protected function calc_Schulze ()
			{
				// Format array
				foreach ( $this->_options as $option_key => $option_id )
				{
					$this->_schulze_strongest_paths[$option_key] = array() ;

					// Format array for stronghest path
					foreach ( $this->_options as $option_key_r => $option_id_r )
					{
						if ($option_key_r != $option_key)
						{
							$this->_schulze_strongest_paths[$option_key][$option_key_r]	= 0 ;
						}
					}

				}



				// Algo
				foreach ($this->_options as $i => $i_value)
				{

					foreach ($this->_options as $j => $j_value)
					{

						if ($i !== $j)
						{
							if ( $this->_pairwise[$i]['win'][$j] > $this->_pairwise[$j]['win'][$i] )
							{
								$this->_schulze_strongest_paths[$i][$j] = $this->_pairwise[$i]['win'][$j] ;
							}
							else
							{
								$this->_schulze_strongest_paths[$i][$j] = 0 ;
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
									$this->_schulze_strongest_paths[$j][$k] = 
																	max( 
																			$this->_schulze_strongest_paths[$j][$k], 
																			min( $this->_schulze_strongest_paths[$j][$i], $this->_schulze_strongest_paths[$i][$k] ) 
																		) ;
								}
							}

						}
					}
				}

			}






}