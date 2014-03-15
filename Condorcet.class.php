<?php
/*
	Condorcet PHP Class, with Schulze Methods and others !

	Version : 0.2 (maybe not ready for production, please test)

	By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
	https://github.com/julien-boudry/Condorcet_Schulze-PHP_Class 
*/

class Condorcet
{

/////////// CLASS ///////////

	protected static $_class_method	= null ;
	protected static $_auth_methods	= '' ;

	protected static $_force_method	= FALSE ;
	protected static $_show_error	= TRUE ;

	const LENGTH_OPION_ID = 10 ;


	// Return an array with auth methods
	public static function get_auth_methods ()
	{
		$auth = explode(',', self::$_auth_methods) ;

		return $auth ;
	}

	// Check if the method is supported
	public static function is_auth_method ($method)
	{
		$auth = self::get_auth_methods() ;

		if ( in_array($method,$auth, true) )
			{ return TRUE ;	}
		else
			{ return FALSE ; }
	}


	// Add algos
	static public function add_algos ($algos)
	{
		if ( is_null($algos) )
			{ return ; }

		elseif ( is_string($algos) && !self::is_auth_method($algos) )
		{
			if ( empty(self::$_auth_methods) )
				{ self::$_auth_methods .= $algos ; }
			else
				{ self::$_auth_methods .= ','.$algos ; }
		}

		elseif ( is_array($algos) )
		{
			foreach ($algos as $value)
			{
				if ( !self::is_auth_method($value) )
					{ continue; }

				if ( empty(self::$_auth_methods) )
					{ self::$_auth_methods .= $value ; }
				else
					{ self::$_auth_methods .= ','.$value ; }				
			}
		}
	}


	// Change default method for this class, if $force == TRUE all current and further objects will be forced to use this method and will not be able to change it by themselves.
	static public function setClassMethod ($method, $force = false)
	{		
		if ( self::is_auth_method($method) )
		{
			self::$_class_method = $method ;

			self::forceMethod($force);
		}
	}

			// if $force == TRUE all current and further objects will be forced to use this method and will not be abble to change it by themselves.
			static public function forceMethod ($force = true)
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


	// Active trigger_error() - True by default
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




/////////// CONSTRUCTOR ///////////


	// Data and global options
	protected $_method ;
	protected $_options ;
	protected $_votes ;

	// Mechanics 
	protected $_i_option_id	= 'A' ;
	protected $_vote_state	= 1 ;
	protected $_options_count = 0 ;

	// Result
	protected $_Pairwise ;
	protected $_algos ;


		///

	public function __construct ($algo = null)
	{
		$this->_method = self::$_class_method ;

		$this->_options	= array() ;
		$this->_votes 	= array() ;
		$this->_algos 	= self::add_algos($algo) ;
	}


	// Change the object method, except if self::$_for_method == TRUE
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

		return $this->_method ;
	}


	// Return object state with options & votes input
	public function getConfig ()
	{
		$this->setMethod() ;

		return array 	(
							'object_method'		=> $this->get_method(),
							'class_default_method'	=> self::$_class_method,
							'class_auth_methods'=> self::get_auth_methods(),
							'force_class_method'=> self::$_force_method,

							'class_show_error'	=> self::$_show_error,

							'object_state'		=> $this->_vote_state						

						);
	}


	public function get_method ()
	{
		return $this->setMethod() ;
	}


	protected function error ($code, $infos = null)
	{
		$error[1] = array('text'=>'Bad option format', 'level'=>E_USER_WARNING) ;
		$error[2] = array('text'=>'The voting process has already started', 'level'=>E_USER_WARNING) ;
		$error[3] = array('text'=>'This option ID is already registered', 'level'=>E_USER_NOTICE) ;
		$error[4] = array('This option ID do not exist'=>'', 'level'=>E_USER_WARNING) ;
		$error[5] = array('text'=>'Bad vote format', 'level'=>E_USER_WARNING) ;
		$error[6] = array('text'=>'You need to specify votes before results', 'level'=>E_USER_ERROR) ;
		$error[7] = array('text'=>'Your Option ID is too long > '.self::LENGTH_OPION_ID, 'level'=>E_USER_ERROR) ;
		$error[8] = array('text'=>'This method do not exist', 'level'=>E_USER_ERROR) ;

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


	// Reset all, be ready for a new vote - PREFER A CLEAN DESTRUCT of this object
	public function reset_all ()
	{
		$this->cleanup_result() ;

		$this->_options = null ;
		$this->_options_count = 0 ;
		$this->_votes = null ;
		$this->_i_option_id = 'A' ;
		$this->_vote_state	= 1 ;


		$this->setMethod() ;

	}



/////////// OPTIONS ///////////


	// Add a vote option before voting
	public function add_option ($option_id = null)
	{
		// only if the vote has not started
		if ( $this->_vote_state > 1 ) { return $this->error(2) ; }
		
		// Filter
		if ( !is_null($option_id) && !ctype_alnum($option_id) && !is_int($option_id) )
			{ return $this->error(1, $option_id) ; }
		if ( mb_strlen($option_id) > self::LENGTH_OPION_ID )
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


	// Destroy a register vote option before voting
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
			$option_key = $this->get_option_key($value) ;
			if ( $option_key === FALSE )
				{ return $this->error(4,$value) ;  }


			unset($this->_options[$option_key]) ;
			$this->_options_count-- ;
		}

	}


		//:: OPTIONS TOOLS :://

		// Count registered options
		public function count_options ()
		{
			return $this->_options_count ;
		}

		// Get the list of registered option
		public function get_options_list ()
		{
			return $this->_options ;
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


	// Close the option config, be ready for voting (optional)
	public function close_options_config ()
	{
		if ( $this->_vote_state === 1 )
			{ $this->_vote_state = 2 ; }
		else
			{ return $this->error(2) ; }
	}


	// Add a single vote. Array key is the rank, each option in a rank are separate by ',' It is not necessary to register the last rank.
	public function add_vote (array $vote)
	{
		// Close option if needed
		if ( $this->_vote_state === 1 )
			{ $this->close_options_config(); }
		// If voting continues after a first set of results
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

					// Do not do 2x the same option
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


		//:: VOTING TOOLS :://

		// How many votes are registered ?
		public function count_votes ()
		{
			return count($this->_votes) ;
		}

		// Get the votes registered list
		public function get_votes_list ()
		{
			return $this->_votes ;
		}




/////////// RETURN RESULT ///////////


	//:: PUBLIC FUNCTIONS :://


	// Generic function for default result with ability to change default object method
	public function get_result ($method = null)
	{
		// Method
		$this->setMethod() ;
		// Prepare
		$this->prepare_result() ;


		if ($method === null)
		{
			$this->init_result($this->_method) ;

			return $this->_algos[$this->_method]->get_result() ;
		}
		elseif ( $method === 'Condorcet_basic' )
		{ 
			return $this->get_winner() ;
		}
		elseif (self::is_auth_method($method))
		{
			$this->init_result($method) ;

			return $this->_algos[$method]->get_result() ;
		}
		else
		{
			return $this->error(8,$option_id) ;
		}

	}

	public function get_winner ($substitution = false)
	{
		// Method
		$this->setMethod() ;
		// Prepare
		$this->prepare_result() ;

		$this->init_result('Condorcet_basic') ;
		$condorcet_winner = $this->_algos['Condorcet_basic']->get_winner() ;

		if ($condorcet_winner !== null)
		{
			return $condorcet_winner ;
		}

		if ( $substitution && $substitution !== 'Condorcet_basic' )
		{
			if ( self::is_auth_method($substitution) )
			{
				$this->init_result($substitution) ;

				return $this->_algos[$substitution]->get_winner() ;
			}
			elseif ( $this->_method !== 'Condorcet_basic' && $substitution === TRUE )
			{
				$this->init_result($this->_method) ;

				return $this->_algos[$method]->get_winner() ;
			}
		}
	}


	public function get_loser ($substitution = false)
	{
		// Method
		$this->setMethod() ;
		// Prepare
		$this->prepare_result() ;

		$this->init_result('Condorcet_basic') ;
		$condorcet_loser = $this->_algos['Condorcet_basic']->get_loser() ;

		if ($condorcet_winner !== null)
		{
			return $condorcet_loser ;
		}

		if ( $substitution && $substitution !== 'Condorcet_basic' )
		{
			if ( self::is_auth_method($substitution) )
			{
				$this->init_result($substitution) ;

				return $this->_algos[$substitution]->get_loser() ;
			}
			elseif ( $this->_method !== 'Condorcet_basic' && $substitution === TRUE )
			{
				$this->init_result($this->_method) ;

				return $this->_algos[$method]->get_loser() ;
			}
		}
		
	}


	public function get_result_stats ($method = null)
	{
		// Method
		$this->setMethod() ;
		// Prepare
		$this->prepare_result() ;

		if ($method === null)
		{
			$this->init_result($this->_method) ;

			return $this->_algos[$this->_method]->get_stats() ;
		}
		elseif (self::is_auth_method($method))
		{
			$this->init_result($method) ;

			return $this->_algos[$method]->get_stats() ;
		}
		else
		{
			return $this->error(8,$option_id) ;
		}

	}



	//:: TOOLS FOR RESULT PROCESS :://


	// Prepare to compute results & caching system
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

			// Return
			return TRUE ;
		}
		else
		{
			$this->error(6) ;
			return FALSE ;
		}

	}

	protected function init_result ($method)
	{
		if ( !isset($this->_algos[$method]) )
		{
			$param['_Pairwise'] = $this->_Pairwise ;
			$param['_options_count'] = $this->_options_count ;
			$param['_options'] = $this->_options ;

			$class = 'Condorcet_'.$method ;
			$this->_algos[$method] = new $class($param) ;
		}
	}

		// Cleanup results to compute again with new votes
		protected function cleanup_result ()
		{
			// Reset state
			$this->_vote_state = 2 ; 

				///

			// Clean pairwise
			$this->_Pairwise = null ;

			// Algos
			$this->_basic_Condorcet_winner = array() ;


			// Clean data from extend class
			if (method_exists($this, 'extend_cleanup_result'))
			{
				$this->extend_cleanup_result() ;
			}
		}


	//:: GET RAW DATA :://

	public function get_Pairwise ()
	{
		$this->prepare_result() ;

			///

		$explicit_pairwise = array() ;

		foreach ($this->_Pairwise as $candidate_key => $candidate_value)
		{
			$candidate_key = $this->get_option_id($candidate_key) ;
			
			foreach ($candidate_value as $mode => $mode_value)
			{
				foreach ($mode_value as $option_key => $option_value)
				{
					$explicit_pairwise[$candidate_key][$mode][$this->get_option_id($option_key)] = $option_value ;
				}
			}

		}


		return $explicit_pairwise ;
	}



/////////// PROCESS RESULT ///////////


	//:: COMPUTE PAIRWISE :://

	protected function do_Pairwise ()
	{
		
		$this->_Pairwise = array() ;

		foreach ( $this->_options as $option_key => $option_id )
		{
			$this->_Pairwise[$option_key] = array( 'win' => array(), 'null' => array(), 'lose' => array() ) ;


			foreach ( $this->_options as $option_key_r => $option_id_r )
			{
				if ($option_key_r != $option_key)
				{
					$this->_Pairwise[$option_key]['win'][$option_key_r]		= 0 ;
					$this->_Pairwise[$option_key]['null'][$option_key_r]	= 0 ;
					$this->_Pairwise[$option_key]['lose'][$option_key_r]	= 0 ;
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

							$this->_Pairwise[$option_key]['win'][$g_option_key]++ ;

							$done_options[] = $option_key ;
						}

						// Null
						if ( 
								$option_key !== $g_option_key &&
								count($options_in_rank) > 1 &&
								in_array($g_option_key, $options_in_rank_keys)
							)
						{
							$this->_Pairwise[$option_key]['null'][$g_option_key]++ ;
						}
					}
				}

			}
		}


		// Loose
		foreach ( $this->_Pairwise as $option_key => $option_results )
		{
			foreach ($option_results['win'] as $option_compare_key => $option_compare_value)
			{
				$this->_Pairwise[$option_key]['lose'][$option_compare_key] = count($this->_votes) -
						(
							$this->_Pairwise[$option_key]['win'][$option_compare_key] + 
							$this->_Pairwise[$option_key]['null'][$option_compare_key]
						) ;
			}
		}

	}

}

Condorcet::add_algos('Condorcet_basic') ;

class Condorcet_Condorcet_basic
{
	// Config
	protected $_Pairwise ;
	protected $_options_count ;
	protected $_options ;

	// Basic Condorcet
	protected $_basic_Condorcet_winner ;
	protected $_basic_Condorcet_loser ;


	public function __construct (array $config)
	{
		$this->_Pairwise = $config['_Pairwise'] ;
		$this->_options_count = $config['_options_count'] ;
		$this->_options = $config['_options'] ;
	}


// Public

	// Get the Schulze ranking
	public function get_stats ()
	{
		return $this->_Pairwise ;
	}


	// Get a Condorcet certified winner. If there is none = null. You can force a winner choice with alternative supported methods ($substitution)
	public function get_winner ()
	{
		// Cache
		if ( $this->_basic_Condorcet_winner !== null )
		{
			return $this->_basic_Condorcet_winner ;
		}


		// Basic Condorcet calculation
		foreach ( $this->_Pairwise as $candidat_key => $candidat_detail )
		{
			$winner = TRUE ;

			foreach ($candidat_detail['win'] as $challenger_key => $win_count )
			{
				if	( $win_count <= $candidat_detail['lose'][$challenger_key] ) 
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

			return NULL ;
	}

	// Get a Condorcet certified loser. If there is none = null. You can force a winner choice with alternative supported methods ($substitution)
	public function get_loser ()
	{

		// Cache
		if ( $this->_basic_Condorcet_loser !== null )
		{
			return $this->_basic_Condorcet_loser ;
		}


		// Basic Condorcet calculation
		foreach ( $this->_Pairwise as $candidat_key => $candidat_detail )
		{
			$loser = TRUE ;

			foreach ($candidat_detail['lose'] as $challenger_key => $lose_count )
			{
				if	( $lose_count <= $candidat_detail['win'][$challenger_key] ) 
				{  
					$loser = FALSE ;
					break ;
				}
			}

			if ($loser)
			{ 
				$this->_basic_Condorcet_loser = $this->_options[$candidat_key] ;

				return $this->_basic_Condorcet_loser ;
			}
		}

			return NULL ;
	}



}



Condorcet::add_algos('Schulze') ;
Condorcet::setClassMethod('Schulze') ;

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


// Public


	// Get the Schulze ranking
	public function get_result ()
	{
		// Cache
		if ( $this->_Schulze_result !== null )
		{
			return $this->_Schulze_result ;
		}

			///

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
		$this->get_result_Schulze();

			///

		$explicit = array() ;

		foreach ($this->_Schulze_strongest_paths as $candidate_key => $candidate_value)
		{
			$candidate_key = $this->get_option_id($candidate_key) ;

			foreach ($candidate_value as $option_key => $option_value)
			{
				$explicit[$candidate_key][$this->get_option_id($option_key)] = $option_value ;
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

			return $this->_Schulze_result[count($this->get_result_Schulze())] ;
		}



// Compute

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