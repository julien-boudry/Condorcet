<?php
/*
	Condorcet PHP Class, with Schulze Methods and others !

	Version : 0.8

	By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
	https://github.com/julien-boudry/Condorcet_Schulze-PHP_Class
*/

namespace Condorcet ;


// Include Algorithms
foreach (glob( __DIR__ . DIRECTORY_SEPARATOR."algorithms".DIRECTORY_SEPARATOR."*.algo.php" ) as $Condorcet_filename)
{
	include_once $Condorcet_filename ;
}

// Set the default Condorcet Class algorithm
namespace\Condorcet::setClassMethod('Schulze') ;


// Base Condorcet class
class Condorcet
{

/////////// CLASS ///////////


	const VERSION = '0.8' ;
	const MAX_LENGTH_CANDIDATE_ID = 30 ; // Max length for candidate identifiant string

	protected static $_classMethod	= null ;
	protected static $_authMethods	= '' ;

	protected static $_forceMethod	= false ;

	// Return library version numer
	public static function getClassVersion ()
	{
		return self::VERSION ;
	}

	// Return an array with auth methods
	public static function getAuthMethods ()
	{
		$auth = explode(',', self::$_authMethods) ;

		return $auth ;
	}

	// Return the Class default method
	public static function getClassDefaultMethod ()
	{
		return self::$_classMethod ;
	}


	// Check if the method is supported
	public static function isAuthMethod ($method)
	{
		$auth = self::getAuthMethods() ;

		if ( in_array($method,$auth, true) )
			{ return true ;	}
		else
			{ return false ; }
	}


	// Add algos
	public static function addAlgos ($algos)
	{
		$to_add = array() ;

		// Check algos
		if ( is_null($algos) )
			{ return false ; }

		elseif ( is_string($algos) && !self::isAuthMethod($algos) )
		{
			if ( !self::testAlgos($algos) )
			{
				return false ;
			}

			$to_add[] = $algos ; 
		}

		elseif ( is_array($algos) )
		{
			foreach ($algos as $value)
			{
				if ( !self::testAlgos($value) )
				{
					return false ;
				}

				if ( !self::isAuthMethod($value) )
				{
					$to_add[] = $value ; 
				}
			}
		}

		// Adding algo
		foreach ($to_add as $value)
		{
			if ( empty(self::$_authMethods) )
				{ self::$_authMethods .= $value ; }
			else
				{ self::$_authMethods .= ','.$value ; }
		}

		return true ;
	}

		// Check if the class Algo. exist and ready to be used
		protected static function testAlgos ($algos)
		{
			if ( !class_exists(__NAMESPACE__.'\\'.$algos, false) )
			{				
				self::error(9) ;
				return false ;
			}

			if ( !in_array(__NAMESPACE__.'\\'.'Condorcet_Algo', class_implements(__NAMESPACE__.'\\'.$algos), false) )
			{
				self::error(10) ;
				return false ;
			}

			return true ;
		}


	// Change default method for this class, if $force == true all current and further objects will be forced to use this method and will not be able to change it by themselves.
	public static function setClassMethod ($method, $force = null)
	{		
		if ( self::isAuthMethod($method) )
		{
			self::$_classMethod = $method ;

			if (is_bool($force))
			{
				self::forceMethod($force);
			}

			return self::getClassDefaultMethod() ;
		}
		else
			{ return false ; }
	}

			// If $force == true all current and further objects will be forced to use this method and will not be abble to change it by themselves.
			public static function forceMethod ($force = true)
			{
				if ($force)
				{
					self::$_forceMethod = true ;
					return true ;
				}
				else
				{
					self::$_forceMethod = false ;
					return false ;
				}
			}


	public static function error ($code, $infos = null, $level = E_USER_WARNING)
	{
		$error[1] = array('text'=>'Bad candidate format', 'level'=>E_USER_WARNING) ;
		$error[2] = array('text'=>'The voting process has already started', 'level'=>E_USER_WARNING) ;
		$error[3] = array('text'=>'This candidate ID is already registered', 'level'=>E_USER_NOTICE) ;
		$error[4] = array('text'=> 'This candidate ID do not exist', 'level'=>E_USER_WARNING) ;
		$error[5] = array('text'=>'Bad vote format', 'level'=>E_USER_WARNING) ;
		$error[6] = array('text'=>'You need to specify votes before results', 'level'=>E_USER_ERROR) ;
		$error[7] = array('text'=>'Your Candidate ID is too long > '.self::MAX_LENGTH_CANDIDATE_ID, 'level'=>E_USER_WARNING) ;
		$error[8] = array('text'=>'This method do not exist', 'level'=>E_USER_ERROR) ;
		$error[9] = array('text'=>'The algo class you want has not been defined', 'level'=>E_USER_ERROR) ;
		$error[10] = array('text'=>'The algo class you want is not correct', 'level'=>E_USER_ERROR) ;
		$error[11] = array('text'=>'You try to unserialize an object version older than your actual Class version. This is a problematic thing', 'level'=>E_USER_WARNING) ;

		
		if ( array_key_exists($code, $error) )
		{
			trigger_error( $error[$code]['text'].' : '.$infos, $error[$code]['level'] );
		}
		else
		{
			if (!is_null($infos))
			{
				trigger_error( $infos, $level );
			}
			else
			{
				trigger_error( 'Mysterious Error', $level );
			}
		}

		return false ;
	}



/////////// CONSTRUCTOR ///////////


	// Data and global options
	protected $_Method ; // Default method for this object
	protected $_Candidates ; // Candidate list
	protected $_Votes ; // Votes list

	// Mechanics 
	protected $_i_CandidateId	= 'A' ;
	protected $_State	= 1 ; // 1 = Add Candidates / 2 = Voting / 3 = Some result have been computing
	protected $_CandidatesCount = 0 ;
	protected $_nextVoteTag = 0 ;
	protected $_objectVersion ;

	// Result
	protected $_Pairwise ;
	protected $_Calculator ;

		//////

	public function __construct ($method = null)
	{
		$this->_Method = self::$_classMethod ;

		$this->_Candidates	= array() ;
		$this->_Votes 	= array() ;

		$this->setMethod($method) ;

		// Store constructor version (security for caching)
		$this->_objectVersion = self::VERSION ;
	}

		public function getObjectVersion ()
		{
			return $this->_objectVersion ;
		}

	public function __sleep ()
	{
		// Don't include computing data, only candidates & votes
		return array	(
			'_Method',
			'_Candidates',
			'_Votes',
			'_i_CandidateId',
			'_State',
			'_CandidatesCount',
			'_nextVoteTag',
			'_objectVersion'
						);
	}

	public function __wakeup ()
	{
		if ( version_compare($this->getObjectVersion(),self::getClassVersion(),'<') )
		{
			return self::error(11, 'Your object version is '.$this->getObjectVersion().' but the class engine version is '.self::getClassVersion());
		}

		if ($this->_State > 2) 
			{$this->_State = 2 ;}
	}

		//////

	// Change the object method, except if self::$_for_Method == true
	public function setMethod ($method = null)
	{
		if (self::$_forceMethod)
		{
			$this->_Method = self::$_classMethod ;
		}
		elseif ( $method != null && self::isAuthMethod($method) )
		{
			$this->_Method = $method ;
		}

		return $this->_Method ;
	}


	// Return object state with somes infos
	public function getConfig ()
	{
		$this->setMethod() ;

		return array 	(
							'CondorcetObject_Version' => $this->getObjectVersion(),

							'object_Method'		=> $this->getMethod(),
							'class_default_Method'	=> self::$_classMethod,
							'force_classMethod'=> self::$_forceMethod,

							'class_authMethods'=> self::getAuthMethods(),

							'state'		=> $this->_State
						);
	}


	public function getMethod ()
	{
		return $this->setMethod() ;
	}


	// Reset all, be ready for a new vote - PREFER A CLEAN DESTRUCT of this object
	public function resetAll ()
	{
		$this->cleanupResult() ;

		$this->_Candidates = array() ;
		$this->_CandidatesCount = 0 ;
		$this->_nextVoteTag = 0 ;
		$this->_Votes = array() ;
		$this->_i_CandidateId = 'A' ;
		$this->_State	= 1 ;

		$this->setMethod() ;

		return true ;
	}



/////////// CANDIDATES ///////////


	// Add a vote candidate before voting
	public function addCandidate ($candidate_id = null)
	{
		// only if the vote has not started
		if ( $this->_State > 1 ) { return self::error(2) ; }
		
		// Filter
		if ( !is_null($candidate_id) && !ctype_alnum($candidate_id) && !is_int($candidate_id) )
			{ return self::error(1, $candidate_id) ; }
		if ( mb_strlen($candidate_id) > self::MAX_LENGTH_CANDIDATE_ID || is_bool($candidate_id) )
			{ return self::error(1, $candidate_id) ; }

		
		// Process
		if ( empty($candidate_id) ) // $candidate_id is empty ...
		{
			while ( !$this->try_addCandidate($this->_i_CandidateId) )
			{
				$this->_i_CandidateId++ ;
			}

			$this->_Candidates[] = $this->_i_CandidateId ;
			$this->_CandidatesCount++ ;

			return $this->_i_CandidateId ;
		}
		else // Try to add the candidate_id
		{
			$candidate_id = trim($candidate_id);

			if ( $this->try_addCandidate($candidate_id) )
			{
				$this->_Candidates[] = $candidate_id ;
				$this->_CandidatesCount++ ;

				return $candidate_id ;
			}
			else
			{
				return self::error(3,$candidate_id) ;
			}
		}
	}

		protected function try_addCandidate ($candidate_id)
		{
			return !$this->existCandidateId($candidate_id) ;
		}


	// Destroy a register vote candidate before voting
	public function removeCandidate ($list)
	{
		// only if the vote has not started
		if ( $this->_State > 1 ) { return self::error(2) ; }

		
		if ( !is_array($list) )
		{
			$list	= array($list) ;
		}

		foreach ($list as &$candidate_id)
		{
			$candidate_id = trim($candidate_id) ;

			$candidate_key = $this->getCandidateKey($candidate_id) ;

			if ( $candidate_key === false )
				{ return self::error(4,$candidate_id) ; }

			$candidate_id = $candidate_key ;
		}

		foreach ($list as $candidate_id)
		{
			unset($this->_Candidates[$candidate_id]) ;
			$this->_CandidatesCount-- ;
		}

		return true ;
	}


		//:: CANDIDATES TOOLS :://

		// Count registered candidates
		public function countCandidates ()
		{
			return $this->_CandidatesCount ;
		}

		// Get the list of registered CANDIDATES
		public function getCandidatesList ()
		{
			return $this->_Candidates ;
		}

		protected function getCandidateKey ($candidate_id)
		{
			return array_search($candidate_id, $this->_Candidates, true) ;
		}

		protected function getCandidateId ($candidate_key)
		{
			return self::getStatic_CandidateId($candidate_key, $this->_Candidates) ;
		}

			public static function getStatic_CandidateId ($candidate_key, &$candidates)
			{
				if (!array_key_exists($candidate_key, $candidates))
					{ return false ; }

				return $candidates[$candidate_key] ;
			}

		public function existCandidateId ($candidate_id)
		{
			return in_array($candidate_id, $this->_Candidates) ;
		}



/////////// VOTING ///////////


	// Close the candidate config, be ready for voting (optional)
	public function closeCandidatesConfig ()
	{
		if ( $this->_State === 1 )
			{ 
				$this->_State = 2 ;
			}

		// If voting continues after a first set of results
		elseif ( $this->_State > 2 )
			{ 
				$this->cleanupResult();
			}

		return true ;
	}


	// Add a single vote. Array key is the rank, each candidate in a rank are separate by ',' It is not necessary to register the last rank.
	public function addVote ($vote, $tag = null)
	{
		$this->closeCandidatesConfig() ;

			////////

		// Translate the string if needed
		if ( is_string($vote) )
		{
			$vote = $this->convertVoteInput($vote) ;
		}

		// Check array format
		if ( !is_array($vote) || !$this->checkVoteInput($vote) )
			{ return self::error(5) ; }

		// Check tag format
		if ( is_bool($tag) )
			{ return self::error(5) ; }

		// Sort
		ksort($vote);

		// Register vote
		return $this->registerVote($vote, $tag) ; // Return the array vote tag(s)
	}

		// From a string like 'A>B=C=H>G=T>Q'
		protected function convertVoteInput ($formula)
		{
			$vote = explode('>', $formula);

			foreach ($vote as $rank => $rank_vote)
			{
				$vote[$rank] = explode('=', $rank_vote);

				// Del space at start and end
				foreach ($vote[$rank] as $key => $value)
				{
					$vote[$rank][$key] = trim($value);
				}
			}

			return $vote ;
		}

		protected function checkVoteInput ($vote)
		{
			$list_candidate = array() ;

			if ( count($vote) > $this->_CandidatesCount || count($vote) < 1 )
				{ return false ; }

			foreach ($vote as $rank => $choice)
			{
				// Check key & candidate
				if ( !is_numeric($rank) || $rank > $this->_CandidatesCount || empty($choice) )
					{ return false ; }

					//////

				if (!is_array($choice))
				{
					$candidates = explode(',', $choice) ;
				}
				else
				{
					$candidates = $choice ;
				}

				foreach ($candidates as $candidate)
				{
					if ( !$this->existCandidateId($candidate) )
					{
						return false ;
					}

					// Do not do 2x the same candidate
					if ( !in_array($candidate, $list_candidate)  )
						{ $list_candidate[] = $candidate ; }
					else 
						{ return false ; }
				}
			}

			return true ;
		}

		// Write a new vote
		protected function registerVote ($vote, $tag = null)
		{
			$last_line_check = array() ;
			$vote_r = array() ;

			$i = 1 ;
			foreach ($vote as $value)
			{
				if ( !is_array($value) )
				{
					$vote_r[$i] = explode(',', $value) ;
				}
				else
				{
					$vote_r[$i] = $value ;
				}

				// $last_line_check
				foreach ($vote_r[$i] as $candidate)
				{
					$last_line_check[] = $this->getCandidateKey($candidate) ;
				}

				$i++ ;
			}

			if ( count($last_line_check) < count($this->_Candidates) )
			{
				foreach ($this->_Candidates as $key => $value)
				{
					if ( !in_array($key,$last_line_check) )
					{
						$vote_r[$i][] = $value ;
					}
				}
			}

			// Vote identifiant
			if ($tag !== null)
			{
				$vote_r['tag'] = explode(',',$tag) ;
			}
			
			$vote_r['tag'][] = $this->_nextVoteTag++ ;
			
			
			// Register
			$this->_Votes[] = $vote_r ;

			return $vote_r['tag'] ;
		}


	public function removeVote ($tag, $with = true)
	{
		$this->closeCandidatesConfig() ;

			//////

		$effective = 0 ;

		foreach ($this->_Votes as $key => $value)
		{
			if ($with)
			{
				if (in_array($tag, $value['tag']))
				{
					unset($this->_Votes[$key]) ;
					$effective++ ;
				}
			}
			else
			{
				if (!in_array($tag, $value['tag']))
				{
					unset($this->_Votes[$key]) ;
					$effective++ ;
				}
			}
		}

		return $effective ;
	}


	//:: VOTING TOOLS :://

	// How many votes are registered ?
	public function countVotes ()
	{
		return count($this->_Votes) ;
	}

	// Get the votes registered list
	public function getVotesList ($tag = null, $with = true)
	{
		if (empty($tag))
		{
			return $this->_Votes ;
		}
		else
		{
			$search = array() ;

			foreach ($this->_Votes as $key => $value)
			{					
				if ($with)
				{
					if (in_array($tag, $value['tag']))
					{
						$search[$key] = $value ;
					}
				}
				else
				{
					if (!in_array($tag, $value['tag']))
					{
						$search[$key] = $value ;
					}
				}
			}

			return $search ;
		}
	}



/////////// RETURN RESULT ///////////


	//:: PUBLIC FUNCTIONS :://


	// Generic function for default result with ability to change default object method
	public function getResult ($method = null, array $options = null)
	{
		// Method
		$this->setMethod() ;
		// Prepare
		$this->prepareResult() ;

			//////

		if ($method === null)
		{
			$this->initResult($this->_Method) ;

			$result = $this->_Calculator[$this->_Method]->getResult($options) ;
		}
		elseif (self::isAuthMethod($method))
		{
			$this->initResult($method) ;

			$result = $this->_Calculator[$method]->getResult($options) ;
		}
		else
		{
			return self::error(8,$method) ;
		}

		return $this->humanResult($result) ;
	}

		protected function humanResult ($robot)
		{
			if (!is_array($robot))
				{return $robot ;}

			$human = array() ;

			foreach ( $robot as $key => $value )
			{
				if (is_array($value))
				{
					foreach ($value as $candidate_key)
					{
						$human[$key][] = $this->getCandidateId($candidate_key) ;
					}
				}
				elseif (is_null($value))
					{ $human[$key] = null ;	}
				else
					{ $human[$key][] = $this->getCandidateId($value) ; }
			}

			foreach ( $human as $key => $value )
			{
				if (is_null($value))
					{ $human[$key] = null; }
				else
					{ $human[$key] = implode(',',$value); }
			}

			return $human ;
		}


	public function getWinner ($substitution = false)
	{
		if ( $substitution )
		{
			if ($substitution === true)
				{$substitution = $this->_Method ;}

			if ( self::isAuthMethod($substitution) )
				{$algo = $substitution ;}
			else
				{return self::error(9,$substitution);}
		}
		else
			{$algo = 'Condorcet_Basic';}

			//////

		return $this->getResult($algo)[1] ;
	}


	public function getLoser ($substitution = false)
	{
		if ( $substitution )
		{			
			if ($substitution === true)
				{$substitution = $this->_Method ;}
			
			if ( self::isAuthMethod($substitution) )
				{$algo = $substitution ;}
			else
				{return self::error(9,$substitution);}
		}
		else
			{$algo = 'Condorcet_Basic';}

			//////

		$result = $this->getResult($algo) ;

		return $result[count($result)] ;
	}


	public function getResultStats ($method = null)
	{
		// Method
		$this->setMethod() ;
		// Prepare
		$this->prepareResult() ;

			//////

		if ($method === null)
		{
			$this->initResult($this->_Method) ;

			$stats = $this->_Calculator[$this->_Method]->getStats() ;
		}
		elseif (self::isAuthMethod($method))
		{
			$this->initResult($method) ;

			$stats = $this->_Calculator[$method]->getStats() ;
		}
		else
		{
			return self::error(8) ;
		}

		if (!is_null($stats))
			{ return $stats ; }
		else
			{ return $this->getPairwise(); }
	}



	//:: TOOLS FOR RESULT PROCESS :://


	// Prepare to compute results & caching system
	protected function prepareResult ()
	{
		if ($this->_State > 2)
		{
			return false ;
		}
		elseif ($this->_State === 2)
		{
			$this->cleanupResult();

			// Do Pairewise
			$this->doPairwise() ;

			// Change state to result
			$this->_State = 3 ;

			// Return
			return true ;
		}
		else
		{
			self::error(6) ;
			return false ;
		}
	}


	protected function initResult ($method)
	{
		if ( !isset($this->_Calculator[$method]) )
		{
			$param['_Pairwise'] = $this->_Pairwise ;
			$param['_CandidatesCount'] = $this->_CandidatesCount ;
			$param['_Candidates'] = $this->_Candidates ;
			$param['_Votes'] = $this->_Votes ;

			$class = __NAMESPACE__.'\\'.$method ;
			$this->_Calculator[$method] = new $class($param) ;
		}
	}


	// Cleanup results to compute again with new votes
	protected function cleanupResult ()
	{
		// Reset state
		if ($this->_State > 2)
		{
			$this->_State = 2 ;
		}

			//////

		// Clean pairwise
		$this->_Pairwise = null ;

		// Algos
		$this->_Calculator = null ;
	}


	//:: GET RAW DATA :://

	public function getPairwise ()
	{
		$this->prepareResult() ;

		$explicit_pairwise = array() ;

		foreach ($this->_Pairwise as $candidate_key => $candidate_value)
		{
			$candidate_key = $this->getCandidateId($candidate_key) ;
			
			foreach ($candidate_value as $mode => $mode_value)
			{
				foreach ($mode_value as $candidate_list_key => $candidate_list_value)
				{
					$explicit_pairwise[$candidate_key][$mode][$this->getCandidateId($candidate_list_key)] = $candidate_list_value ;
				}
			}
		}

		return $explicit_pairwise ;
	}



/////////// PROCESS RESULT ///////////


	//:: COMPUTE PAIRWISE :://

	protected function doPairwise ()
	{		
		$this->_Pairwise = array() ;

		foreach ( $this->_Candidates as $candidate_key => $candidate_id )
		{
			$this->_Pairwise[$candidate_key] = array( 'win' => array(), 'null' => array(), 'lose' => array() ) ;

			foreach ( $this->_Candidates as $candidate_key_r => $candidate_id_r )
			{
				if ($candidate_key_r != $candidate_key)
				{
					$this->_Pairwise[$candidate_key]['win'][$candidate_key_r]	= 0 ;
					$this->_Pairwise[$candidate_key]['null'][$candidate_key_r]	= 0 ;
					$this->_Pairwise[$candidate_key]['lose'][$candidate_key_r]	= 0 ;
				}
			}
		}

		// Win && Null
		foreach ( $this->_Votes as $vote_id => $vote_ranking )
		{
			// Del vote identifiant
			unset($vote_ranking['tag']) ;

			$done_Candidates = array() ;

			foreach ($vote_ranking as $candidates_in_rank)
			{
				$candidates_in_rank_keys = array() ;

				foreach ($candidates_in_rank as $candidate)
				{
					$candidates_in_rank_keys[] = $this->getCandidateKey($candidate) ;
				}

				foreach ($candidates_in_rank as $candidate)
				{
					$candidate_key = $this->getCandidateKey($candidate);

					// Process
					foreach ( $this->_Candidates as $g_candidate_key => $g_CandidateId )
					{
						// Win
						if (	$candidate_key !== $g_candidate_key && 
								!in_array($g_candidate_key, $done_Candidates, true) && 
								!in_array($g_candidate_key, $candidates_in_rank_keys, true)
							)
						{
							$this->_Pairwise[$candidate_key]['win'][$g_candidate_key]++ ;

							$done_Candidates[] = $candidate_key ;
						}

						// Null
						if (	$candidate_key !== $g_candidate_key &&
								count($candidates_in_rank) > 1 &&
								in_array($g_candidate_key, $candidates_in_rank_keys)
							)
						{
							$this->_Pairwise[$candidate_key]['null'][$g_candidate_key]++ ;
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
				$this->_Pairwise[$option_key]['lose'][$option_compare_key] = $this->countVotes() - (
							$this->_Pairwise[$option_key]['win'][$option_compare_key] + 
							$this->_Pairwise[$option_key]['null'][$option_compare_key]
						) ;
			}
		}
	}



/////////// TOOLS FOR MODULAR ALGORITHMS ///////////


	public static function makeStatic_PairwiseComparison (&$pairwise)
	{
		$comparison = array();

		foreach ($pairwise as $candidate_key => $candidate_data)
		{
			$comparison[$candidate_key]['win'] = 0 ;
			$comparison[$candidate_key]['null'] = 0 ;
			$comparison[$candidate_key]['lose'] = 0 ;
			$comparison[$candidate_key]['balance'] = 0 ;

			foreach ($candidate_data['win'] as $opponenent['key'] => $opponenent['lose']) 
			{
				if ( $opponenent['lose'] > $candidate_data['lose'][$opponenent['key']] )
				{
					$comparison[$candidate_key]['win']++ ;
					$comparison[$candidate_key]['balance']++ ;
				}
				elseif ( $opponenent['lose'] === $candidate_data['lose'][$opponenent['key']] )
				{
					$comparison[$candidate_key]['null']++ ;
				}
				else
				{
					$comparison[$candidate_key]['lose']++ ;
					$comparison[$candidate_key]['balance']-- ;
				}
			}
		}

		return $comparison ;
	}

}

// Interface with the aim of verifying the good modular implementation of algorithms.
interface Condorcet_Algo
{
	public function getResult($options);
	public function getStats();
}
