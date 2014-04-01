<?php
/*
	Condorcet PHP Class, with Schulze Methods and others !

	Version : 0.4

	By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
	https://github.com/julien-boudry/Condorcet_Schulze-PHP_Class 
*/


// Include Algorithms
foreach (glob( __DIR__ . "/algorithms/*.php" ) as $Condorcet_filename)
{
    include_once $Condorcet_filename ;
}

// Set the default Condorcet Class algorithm
Condorcet::setClassMethod('Schulze') ;

// Base Condorcet class
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
			{ return FALSE ; }

		elseif ( is_string($algos) && !self::is_auth_method($algos) )
		{
			if ( !self::test_algos($algos) )
			{
				return FALSE ;
			}

			if ( empty(self::$_auth_methods) )
				{ self::$_auth_methods .= $algos ; }
			else
				{ self::$_auth_methods .= ','.$algos ; }
		}

		elseif ( is_array($algos) )
		{
			foreach ($algos as $value)
			{
				if ( !self::test_algos($value) )
				{
					return FALSE ;
				}

				if ( !self::is_auth_method($value) )
					{ continue; }

				if ( empty(self::$_auth_methods) )
					{ self::$_auth_methods .= $value ; }
				else
					{ self::$_auth_methods .= ','.$value ; }				
			}
		}
	}


		static protected function test_algos ($algos)
		{
			if ( !class_exists('Condorcet_'.$algos) )
			{				
				self::error(9) ;
				return FALSE ;
			}

			$tests_method = array ('get_result', 'get_stats', 'get_winner', 'get_loser') ;

			foreach ($tests_method as $method)
			{
				if ( !method_exists( 'Condorcet_'.$algos , $method ) )
				{
					self::error(10) ;
					return FALSE ;
				}
			}

			return TRUE ;
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
	public static function setError ($param = TRUE)
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


	protected static function error ($code, $infos = null)
	{
		$error[1] = array('text'=>'Bad option format', 'level'=>E_USER_WARNING) ;
		$error[2] = array('text'=>'The voting process has already started', 'level'=>E_USER_WARNING) ;
		$error[3] = array('text'=>'This option ID is already registered', 'level'=>E_USER_NOTICE) ;
		$error[4] = array('This option ID do not exist'=>'', 'level'=>E_USER_WARNING) ;
		$error[5] = array('text'=>'Bad vote format', 'level'=>E_USER_WARNING) ;
		$error[6] = array('text'=>'You need to specify votes before results', 'level'=>E_USER_ERROR) ;
		$error[7] = array('text'=>'Your Option ID is too long > '.self::LENGTH_OPION_ID, 'level'=>E_USER_WARNING) ;
		$error[8] = array('text'=>'This method do not exist', 'level'=>E_USER_ERROR) ;
		$error[9] = array('text'=>'The algo class you want has not been defined', 'level'=>E_USER_ERROR) ;
		$error[10] = array('text'=>'The algo class you want is not correct', 'level'=>E_USER_ERROR) ;

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



/////////// CONSTRUCTOR ///////////


	// Data and global options
	protected $_method ;
	protected $_options ;
	protected $_votes ;

	// Mechanics 
	protected $_i_option_id	= 'A' ;
	protected $_vote_state	= 1 ;
	protected $_options_count = 0 ;
	protected $_vote_tag = 0 ;

	// Result
	protected $_Pairwise ;
	protected $_algos ;


		///

	public function __construct ($method = null)
	{
		$this->_method = self::$_class_method ;

		$this->_options	= array() ;
		$this->_votes 	= array() ;

		$this->setMethod($method) ;
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
		if ( $this->_vote_state > 1 ) { return self::error(2) ; }
		
		// Filter
		if ( !is_null($option_id) && !ctype_alnum($option_id) && !is_int($option_id) )
			{ return self::error(1, $option_id) ; }
		if ( mb_strlen($option_id) > self::LENGTH_OPION_ID )
			{ return self::error(1, $option_id) ; }

		
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
				return self::error(3,$option_id) ;
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
		if ( $this->_vote_state > 1 ) { return self::error(2) ; }

		
		if ( !is_array($option_id) )
		{
			$option_id	= array($option_id) ;
		}


		foreach ($option_id as $value)
		{
			$option_key = $this->get_option_key($value) ;
			if ( $option_key === FALSE )
				{ return self::error(4,$value) ;  }


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
			self::static_get_option_id($option_key, $this->_options) ;
		}

			public static function get_static_option_id ($option_key, &$options)
			{
				return $options[$option_key] ;
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
			{ 
				$this->_vote_state = 2 ;
				return TRUE ;
			}

		// If voting continues after a first set of results
		if ( $this->_vote_state > 2 )
			{ $this->cleanup_result(); }
	}


	// Add a single vote. Array key is the rank, each option in a rank are separate by ',' It is not necessary to register the last rank.
	public function add_vote (array $vote, $tag = null)
	{
		$this->close_options_config () ;

			////////

		// Check array format
		if ( !$this->check_vote_input($vote) ) 
			{ return self::error(5) ;  }

		// Check tag format
		if ( 
				!is_null($tag) &&
				(!is_string($tag) && !is_int($tag)) &&
				strlen($tag) <= 20
			) 
			{ return self::error(5) ; }

		// Sort
		ksort($vote);

		// Register vote
		return $this->register_vote($vote, $tag) ;		
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


		protected function register_vote ($vote, $tag = null)
		{
			$last_line_check = array() ;
			$vote_r = array() ;

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

			// Vote identifiant
			if ($tag === null)
			{
				$this->_vote_tag++ ;

				$vote_r['tag'] = $this->_vote_tag ;
			}
			else
			{
				$vote_r['tag'] = $tag ;
			}

			// Register
			$this->_votes[] = $vote_r ;

			return $vote_r['tag'] ;
		}


		public function remove_vote ($tag, $with = true)
		{
			$this->close_options_config () ;

				////////

			foreach ($this->_votes as $key => $value) 
			{					
				if ($with)
				{
					if ($value['tag'] == $tag)
					{
						unset($this->_votes[key]) ;
					}
				}
				else
				{
					if ($value['tag'] != $tag)
					{
						unset($this->_votes[key]) ;
					}
				}
			}
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
		elseif (self::is_auth_method($method))
		{
			$this->init_result($method) ;

			return $this->_algos[$method]->get_result() ;
		}
		else
		{
			return self::error(8,$option_id) ;
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

		if ( $substitution )
		{
			if ($substitution === true)
			{
				$this->init_result($this->_method) ;

				return $this->_algos[$this->_method]->get_winner() ;
			}
			elseif ( self::is_auth_method($substitution) )
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

		if ($condorcet_loser !== null)
		{
			return $condorcet_loser ;
		}

		if ( $substitution )
		{			
			if ($substitution === true)
			{
				$this->init_result($this->_method) ;

				return $this->_algos[$this->_method]->get_loser() ;
			}
			elseif ( self::is_auth_method($substitution) )
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
			return self::error(8,$option_id) ;
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
			self::error(6) ;
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

		return self::get_static_Pairwise ($this->_Pairwise, $this->_options) ;
	}

		public static function get_static_Pairwise (&$pairwise, &$options)
		{
			$explicit_pairwise = array() ;

			foreach ($pairwise as $candidate_key => $candidate_value)
			{
				$candidate_key = self::get_static_option_id($candidate_key, $options) ;
				
				foreach ($candidate_value as $mode => $mode_value)
				{
					foreach ($mode_value as $option_key => $option_value)
					{
						$explicit_pairwise[$candidate_key][$mode][Condorcet::get_static_option_id($option_key,$options)] = $option_value ;
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
			// Del vote identifiant
			unset($vote_ranking['tag']) ;

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