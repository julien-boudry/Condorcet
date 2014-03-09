<?php



class Condorcet
{

/////////// CLASS ///////////

	private	static $_class_method = 'Schulze';
	public	static $_auth_methods = 'Condorcet,Schulze' ;

	private static $_force_method = FALSE ;
	private static $_show_error = TRUE ;


	static public function setClassMethod ($method, $force = false)
	{
		
		if ( self::is_auth_method($method) )
		{
			self::$_class_method = $method ;

			self::forceMethod($force);
		}
	}

			static private function forceMethod ($force = true)
			{
				if ($force)
				{
					self::$_force_method = TRUE ;
				}
				else
				{
					self::$_force_method = FALSE ;
				}
			}


	static public function setError ($param = TRUE)
	{
		if ($param)
		{
			self::$_show_error = TRUE ;
		}
		else
		{
			self::$_show_error = FALSE ;
		}
	}


	static private function is_auth_method ($method)
	{
		$auth = explode(',', self::$_auth_methods) ;

		if ( in_array($method,$auth, true) )
			{ return TRUE ;	}
		else
			{ return FALSE ; }
	}



/////////// CONSTRUCTOR ///////////


	protected $_method ;
	protected $_options ;
	protected $_votes ;

	// Mécanique
	protected $_i_option_id	= 'A' ;
	protected $_vote_state	= 1 ;
	protected $_options_count = 0 ;

	// Result
	protected $_pairwise ;

		// Basic Condorcet
		protected $_basic_Condorcet_winner ;

		// Schulze
		protected $_Schulze_strongest_paths ;
		protected $_Schulze_result ;


	public function __construct ($method = null)
	{
		if ($method == null)
			{$method = self::$_class_method ;}
		$this->setMethod($method) ;

		$this->_options	= array() ;
		$this->_votes 	= array() ;
	}


	public function setMethod ($method = null)
	{
		if (self::$_force_method)
		{
			$this->_method = self::$_class_method ;
		}
		elseif ( $method != null && self::is_auth_method($method) )
		{
			$this->_method = $method ;
		}

	}


	public function getConfig ()
	{
		$this->setMethod() ;

		return array 	(
							'object_method'		=> $this->_method,
							'class_method'		=> self::$_class_method,
							'force_class_method'=> self::$_force_method,

							'object_state'		=> $this->_vote_state,

							'options'			=> $this->_options,
							'votes'				=> $this->_votes
						);
	}


	protected function error ($code, $infos = null)
	{
		$error[1] = array('text'=>'Bad option format', 'level'=>E_USER_WARNING) ;
		$error[2] = array('text'=>'The voting process already began', 'level'=>E_USER_WARNING) ;
		$error[3] = array('text'=>'This option ID is already register', 'level'=>E_USER_NOTICE) ;
		$error[4] = array('This option ID not exist'=>'', 'level'=>E_USER_WARNING) ;
		$error[5] = array('text'=>'Bad vote format', 'level'=>E_USER_WARNING) ;
		$error[6] = array('text'=>'You need to specify votes befor result', 'level'=>E_USER_ERROR) ;

		if (self::$_show_error)
		{
			if (array_key_exists($code, $error))
			{
				trigger_error ( $error[$code]['text'].' : '.$infos, $error[$code]['level'] );
			}
			else
			{
				trigger_error ( 'Mysterious Error', E_USER_NOTICE );
			}
		}

		return FALSE ;
	}



/////////// OPTIONS ///////////


	public function add_option ($option_id = null)
	{
		// only if the vote has not started
		if ( $this->_vote_state > 1 ) { return $this->error(2) ; }
		
		// Filter
		if ( !is_null($option_id) && !is_string($option_id) && !is_int($option_id) )
			{ return $this->error(1, $option_id) ; }

		
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
				return $this->error(3,$option_id) ;
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
		if ( $this->_vote_state > 1 ) { return $this->error(2) ; }

		
		if ( !is_array($option_id) )
		{
			$option_id	= array($option_id) ;
		}


		foreach ($option_id as $value)
		{
			$option_key = $this->get_option_key ($value) ;
			if ( $option_key === FALSE )
				{ return $this->error(4,$value) ;  }


			unset($this->_options[$option_key]) ;
		}

	}


		//:: OPTIONS TOOLS :://

		public function count_options ()
		{
			return $this->_options_count ;
		}

		protected function get_option_key ($option_id)
		{
			return array_search($option_id, $this->_options, true);
		}

		protected function get_option_id ($option_key)
		{
			return $this->_options[$option_key] ;
		}

		protected function option_id_exist ($option_id)
		{
			return in_array($option_id, $this->_options) ;
		}



/////////// VOTING ///////////


	public function close_options_config ()
	{
		if ( $this->_vote_state === 1 )
		{
			$this->_vote_state = 2 ;
		}
		else
		{
			return $this->error(2) ;
		}
	}


	public function add_vote (array $vote)
	{

		// Close option if needed
		if ( $this->_vote_state === 1 )
			{ $this->close_options_config(); }
		// If voting continue after a first set of results
		if ( $this->_vote_state > 2 )
			{ $this->cleanup_result(); }


		// Check array format
		if ( $this->check_vote_input($vote) === FALSE ) 
			{ return $this->error(5) ;  }

		// Sort
		ksort($vote);

		// Register vote
		$this->register_vote($vote) ;


		return TRUE ;
	}


		protected function cleanup_result ()
		{
			// Reset state
			$this->_vote_state = 2 ; 

				///

			// Clean pairwise
			$this->_pairwise = null ;

			// Clean Basic Condorcet
			$this->_basic_Condorcet_winner = null ;

			// Clean Schulze
			$this->_Schulze_strongest_paths = null ;
			$this->_Schulze_result = null ;
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




/////////// RETURN RESULT ///////////


	//:: PUBLIC FUNCTIONS :://


	public function get_result ($method = null)
	{
		// Method
		$this->setMethod($method) ;

		// Prepare Result
		$this->prepare_result() ;

		
		// Return the good function
		if ($this->_method !== 'Condorcet')
		{
			$fonction = 'get_result_'.$this->_method ;
			return $this->$fonction() ;
		}
		else
			{ return $this->get_winner_Condordet() ; }

	}


	public function get_winner_Condorcet ($substitution = false)
	{
		// Method
		$this->setMethod() ;

		// Prepare Result
		$this->prepare_result() ;

		// Cache
		if ( !$substitution && $this->_basic_Condorcet_winner !== null )
		{
			return $this->_basic_Condorcet_winner ;
		}


		// Calc basic Condorcet :
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
			{ 
				$this->_basic_Condorcet_winner = $this->_options[$candidat_key] ;

				return $this->_basic_Condorcet_winner ;
			}
		}


		// There is no Winner

			if ( $substitution && $substitution !== 'Condorcet' )
			{
				if ( self::is_auth_method($substitution) )
				{
					$fonction = 'get_result_'.$substitution ;

					return $this->$fonction()[1] ;

				}
				elseif ( $this->_method !== 'Condorcet' && $substitution === TRUE )
				{
					$fonction = 'get_result_'.$this->_method ;

					return $this->$fonction()[1] ;
				}
			}

			return NULL ;
	}


	public function get_result_Schulze ()
	{
		// Prepare Result
		$this->prepare_result() ;

		// Cache
		if ( $this->_Schulze_result !== null )
		{
			return $this->_Schulze_result ;
		}

			///

		// Format array
		$this->Schulze_strongest_array() ;

		// Calc Strongest Paths
		$this->strongest_paths() ;


		// Calc ranking
		$this->Schulze_make_ranking() ;


		// Return
		return $this->_Schulze_result ;
	}



	//:: TOOLS FOR RESULT PROCESS :://

	protected function prepare_result ()
	{
		if ($this->_vote_state > 2)
		{
			return FALSE ;
		}
		elseif ($this->_vote_state === 2)
		{
			$this->cleanup_result();

			// Do Pairewise
			$this->do_Pairwise() ;

			// Change state to result
			$this->_vote_state = 3 ;
		}
		else
		{
			$this->error(6) ;
		}
	}




/////////// PROCESS RESULT ///////////


	//:: CALC PAIRWISE :://


	protected function do_Pairwise ()
	{
		
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

	}




	//:: CALC SCHULZE ALGO. :://


	// Calculate the strongest Paths for Schulze Method
	protected function Schulze_strongest_array ()
	{
		$this->_Schulze_strongest_paths = array() ;

		foreach ( $this->_options as $option_key => $option_id )
		{
			$this->_Schulze_strongest_paths[$option_key] = array() ;

			// Format array for stronghest path
			foreach ( $this->_options as $option_key_r => $option_id_r )
			{
				if ($option_key_r != $option_key)
				{
					$this->_Schulze_strongest_paths[$option_key][$option_key_r]	= 0 ;
				}
			}

		}				
	}


	// Calculate Strongest Paths
	protected function strongest_paths ()
	{


		foreach ($this->_options as $i => $i_value)
		{

			foreach ($this->_options as $j => $j_value)
			{

				if ($i !== $j)
				{
					if ( $this->_pairwise[$i]['win'][$j] > $this->_pairwise[$j]['win'][$i] )
					{
						$this->_Schulze_strongest_paths[$i][$j] = $this->_pairwise[$i]['win'][$j] ;
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



	protected function Schulze_make_ranking ()
	{		
		$this->_Schulze_result = array() ;

		// Calc ranking
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

					if ( $beaten_value <= $this->_Schulze_strongest_paths[$beaten_key][$candidate_key] )
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
				$this->_Schulze_result[$key][$ord] = $this->get_option_id($option_key) ;
			}
		}

		foreach ( $this->_Schulze_result as $key => $value )
		{
			$this->_Schulze_result[$key] = implode(',',$value);
		}


	}



}