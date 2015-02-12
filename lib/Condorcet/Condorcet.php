<?php
/*
	Condorcet PHP Class, with Schulze Methods and others !

	Version : 0.90

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


	const VERSION = '0.90' ;

	const ENV = 'DEV' ;
	const MAX_LENGTH_CANDIDATE_ID = 30 ; // Max length for candidate identifiant string

	protected static $_classMethod	= null ;
	protected static $_authMethods	= [] ;
	protected static $_forceMethod	= false ;
	protected static $_max_parse_iteration = null ;
	protected static $_max_vote_number = null ;
	protected static $_checksumMode = false ;

	// Return library version numer
	public static function getClassVersion ($options = 'FULL')
	{
			switch ($options)
			{
				case 'MAJOR':
					$version = explode('.', self::VERSION);
					return $version[0].'.'.$version[1];

				case 'ENV':
					return ( (self::ENV === 'DEV') ? self::ENV . ' - ' : '') . self::VERSION ;

				default:
					return self::VERSION ;
			}
	}


	// Change max parse iteration
	public static function setMaxParseIteration ($value)
	{
		if (is_int($value) || $value === null)
		{
			self::$_max_parse_iteration = $value ;
			return self::$_max_parse_iteration ;
		}
		else
			{ return false ; }
	}

	// Change max vote number
	public static function setMaxVoteNumber ($value)
	{
		if ( is_int($value) || ($value === null || $value === false) )
		{
			self::$_max_vote_number = ($value === false) ? null : $value ;
			return self::$_max_vote_number ;
		}
		else
			{ return false ; }
	}


	// Return an array with auth methods
	public static function getAuthMethods ($basic = false)
	{
		$auth = self::$_authMethods;

		// Don't show Natural Condorcet
		if (!$basic) :
			unset($auth[array_search('Condorcet_Basic', $auth, true)]);
		endif;

		return $auth ;
	}


	// Return the Class default method
	public static function getClassDefaultMethod ()
	{
		return self::$_classMethod ;
	}


	// Check if the method is supported
	public static function isAuthMethod ($methods)
	{
		$auth = self::getAuthMethods(true) ;

		if (is_string($methods))
		{
			$methods = array($methods);
		}

		if (is_array($methods))
		{
			foreach ($methods as $method)
			{
				if ( !in_array($method,$auth, true) )
					{ return false ; }
			}

			return true ;
		}

		return false ;
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
			self::$_authMethods[] = $value;
		}

		return true ;
	}


		// Check if the class Algo. exist and ready to be used
		protected static function testAlgos ($algos)
		{
			if ( !class_exists(__NAMESPACE__.'\\'.$algos, false) )
			{				
				throw new namespace\CondorcetException(9) ;
			}

			if ( !in_array(__NAMESPACE__.'\\'.'Condorcet_Algo', class_implements(__NAMESPACE__.'\\'.$algos, false), true) )
			{
				throw new namespace\CondorcetException(10) ;
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


	// Check JSON format
	public static function isJson ($string)
	{
		if (is_numeric($string) || $string === 'true' || $string === 'false' || $string === 'null' || empty($string))
		{ return false ; }

		// try to decode string
		json_decode($string);

		// check if error occured
		$isValid = json_last_error() === JSON_ERROR_NONE;

		return $isValid;
	}


	// Generic action before parsing data from string input
	public static function prepareParse ($input, $allowFile)
	{
		// Input must be a string
		if (!is_string($input))
			{ throw new namespace\CondorcetException(14); }

		// Is string or is file ?
		if ($allowFile === true && is_file($input))
		{
			$input = file_get_contents($input);
		}

		// Line
		$input = preg_replace("(\r\n|\n|\r)",';',$input);
		$input = explode(';', $input);

		// Delete comments
		foreach ($input as &$line)
		{
			// Delete comments
			$is_comment = strpos($line, '#') ;
			if ($is_comment !== false)
			{
				$line = substr($line, 0, $is_comment) ;
			}

			// Trim
			$line = trim($line);
		}

		return $input ;
	}


	public static function prepareJson ($input)
	{
		if (!self::isJson($input))
			{ throw new namespace\CondorcetException(15); }

		return json_decode($input, true);
	}


	// Simplify Condorcet Var_Dump. Transform object to String.
	public static function format ($input, $out = true, $convertObject = true)
	{
		if (is_object($input)) :
				$r = ($convertObject) ? (string) $input : $input;
		elseif (!is_array($input)) :
			$r = $input;
		else :
			foreach ($input as $key => $line) :
				$input[$key] = self::format($line,false,$convertObject);
			endforeach;

			if (count($input) === 1):
				$r = reset($input);
			else:
				$r = $input;
			endif;
		endif;

			///

		if ($out): var_dump($r); endif;
		
		return $r;
	}

/////////// CONSTRUCTOR ///////////


	// Data and global options
	protected $_Method ; // Default method for this object
	protected $_Candidates ; // Candidate list
	protected $_Votes ; // Votes list

	// Mechanics
	protected $_i_CandidateId = 'A' ;
	protected $_State = 1 ; // 1 = Add Candidates / 2 = Voting / 3 = Some result have been computing
	protected $_CandidatesCount = 0 ;
	protected $_nextVoteTag = 0 ;
	protected $_objectVersion ;
	protected $_globalTimer = 0.0 ;
	protected $_lastTimer = 0.0 ;
	protected $_ignoreStaticMaxVote = false ;

	// Result
	protected $_Pairwise ;
	protected $_Calculator ;

		//////

	public function __construct ($method = null)
	{
		$this->_Method = self::$_classMethod ;

		$this->_Candidates = array() ;
		$this->_Votes = array() ;

		$this->setMethod($method) ;

		// Store constructor version (security for caching)
		$this->_objectVersion = self::VERSION ;
	}

	public function __destruct ()
	{
		$this->destroyAllLink();
	}

		public function getObjectVersion ($options = null)
		{
			switch ($options)
			{
				case 'MAJOR':
					$version = explode('.', $this->_objectVersion);
					return $version[0].'.'.$version[1];

				default:
					return $this->_objectVersion ;
			}
		}

	public function __sleep ()
	{
		// Don't include others data
		$include = array (
			'_Method',
			'_Candidates',
			'_Votes',

			'_i_CandidateId',
			'_State',
			'_CandidatesCount',
			'_nextVoteTag',
			'_objectVersion',
			'_ignoreStaticMaxVote',

			'_Pairwise',
			'_Calculator',
		);

		!self::$_checksumMode
			AND
				array_push($include, '_lastTimer','_globalTimer');

		return $include ;
	}

	public function __wakeup ()
	{
		if ( version_compare($this->getObjectVersion('MAJOR'),self::getClassVersion('MAJOR'),'!=') )
		{
			$this->_Candidates = [];
			$this->_Votes = [];

			throw new namespace\CondorcetException(11, 'Your object version is '.$this->getObjectVersion().' but the class engine version is '.self::getClassVersion('ENV'));
		}
	}

	public function __clone ()
	{
		$this->registerAllLinks();
	}

	protected function registerAllLinks ()
	{
		foreach ($this->_Candidates as $value)
			{ $value->registerLink($this); }

		foreach ($this->_Votes as $value)
			{ $value->registerLink($this); }
	}

	protected function destroyAllLink ()
	{
		foreach ($this->_Candidates as $value)
			{ $value->destroyLink($this); }

		foreach ($this->_Votes as $value)
			{ $value->destroyLink($this); }
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
							'is_ClassForceMethod'=> self::$_forceMethod,

							'class_authMethods'=> self::getAuthMethods(),
							'class_MaxParseIterations'=> self::$_max_parse_iteration,

							'state'		=> $this->_State
						);
	}


	public function getMethod ()
	{
		return $this->setMethod() ;
	}

	protected function setTimer ($timer)
	{
		$this->_lastTimer = microtime(true) - $timer ;
		$this->_globalTimer += $this->_lastTimer ;
	}

	public function getGlobalTimer ($float = false)
		{ return ($float) ? $this->_globalTimer : number_format($this->_globalTimer, 5) ; }

	public function getLastTimer ($float = false)
		{ return ($float) ? $this->_lastTimer : number_format($this->_lastTimer, 5) ; }

	public function getChecksum ()
	{
		self::$_checksumMode = true;

		$r = hash('sha256',
			serialize( array( $this->_Candidates, $this->_Votes, $this->_Pairwise, $this->_Calculator )	).
			$this->getObjectVersion('major')
		);

		self::$_checksumMode = false;

		return $r;
	}

	public function ignoreMaxVote ($state = true)
	{
		$this->_ignoreStaticMaxVote = (is_bool($state)) ? $state : true ;
		return $this->_ignoreStaticMaxVote ;
	}


/////////// CANDIDATES ///////////


	// Add a vote candidate before voting
	public function addCandidate ($candidate_id = null)
	{
		// only if the vote has not started
		if ( $this->_State > 1 )
			{ throw new namespace\CondorcetException(2); }

		// Filter
		if ( is_bool($candidate_id) || is_array($candidate_id) || (is_object($candidate_id) && !($candidate_id instanceof namespace\Candidate)) )
			{ throw new namespace\CondorcetException(1, $candidate_id) ; }


		// Process
		if ( empty($candidate_id) ) // $candidate_id is empty ...
		{
			while ( !$this->canAddCandidate($this->_i_CandidateId) )
			{
				$this->_i_CandidateId++ ;
			}

			$newCandidate = new Candidate($this->_i_CandidateId) ;
		}
		else // Try to add the candidate_id
		{
			$newCandidate = ($candidate_id instanceof namespace\Candidate) ? $candidate_id : new Candidate ($candidate_id) ;

			if ( !$this->canAddCandidate($newCandidate) )
				{ throw new namespace\CondorcetException(3,$candidate_id); }
		}

		// Register it
		$this->_Candidates[] = $newCandidate ;

		// Linking
		$newCandidate->registerLink($this);

		// Candidate Counter
		$this->_CandidatesCount++ ;

		return $newCandidate ;
	}

		public function canAddCandidate ($candidate_id)
		{
			return !$this->existCandidateId($candidate_id, false) ;
		}


	// Destroy a register vote candidate before voting
	public function removeCandidate ($list)
	{
		// only if the vote has not started
		if ( $this->_State > 1 ) { throw new namespace\CondorcetException(2) ; }

		
		if ( !is_array($list) )
		{
			$list	= array($list) ;
		}

		foreach ($list as &$candidate_id)
		{
			$candidate_key = $this->getCandidateKey($candidate_id) ;

			if ( $candidate_key === false )
				{ throw new namespace\CondorcetException(4,$candidate_id) ; }

			$candidate_id = $candidate_key ;
		}

		$rem = [];
		foreach ($list as $candidate_key)
		{
			$this->_Candidates[$candidate_key]->destroyLink($this);

			$rem[] = $this->_Candidates[$candidate_key];

			unset($this->_Candidates[$candidate_key]) ;
			$this->_CandidatesCount-- ;
		}

		return $rem ;
	}


	public function jsonCandidates ($input)
	{
		$input = self::prepareJson($input);
		if ($input === false) { return $input ; }

			//////

		$adding = [] ;
		foreach ($input as $candidate)
		{
			try {
				$adding[] = $this->addCandidate($candidate);
			}
			catch (Exception $e) {}
		}

		return $adding ;
	}


	public function parseCandidates ($input, $allowFile = true)
	{
		$input = self::prepareParse($input, $allowFile) ;
		if ($input === false) { return $input ; }

		$adding = [] ;
		foreach ($input as $line)
		{
			// Empty Line
			if (empty($line)) { continue ; }

			// addCandidate
			try {
				if (self::$_max_parse_iteration !== null && count($adding) >= self::$_max_parse_iteration) :
					throw new namespace\CondorcetException(12, self::$_max_parse_iteration);
				endif;

				$adding[] = $this->addCandidate($line);
			} catch (namespace\CondorcetException $e) {
				if ($e->getCode() === 12)
					{throw $e;}
			}
		}

		return $adding;
	}


		//:: CANDIDATES TOOLS :://

		// Count registered candidates
		public function countCandidates ()
		{
			return $this->_CandidatesCount ;
		}

		// Get the list of registered CANDIDATES
		public function getCandidatesList ($stringMode = false)
		{
			if (!$stringMode) : return $this->_Candidates ;
			else :
				$result = array() ;

				foreach ($this->_Candidates as $candidateKey => &$oneCandidate)
				{
					$result[$candidateKey] = $oneCandidate->getName();
				}

				return $result;
			endif;
		}

		protected function getCandidateKey ($candidate_id)
		{
			if ($candidate_id instanceof namespace\Candidate) :
				return array_search($candidate_id, $this->_Candidates, true);
			else:
				return array_search(trim((string) $candidate_id), $this->_Candidates);
			endif;
		}

		public function getCandidateId ($candidate_key, $onlyName = false)
		{
			if (!array_key_exists($candidate_key, $this->_Candidates)) :
				return false ;
			else :
				return ($onlyName) ? $this->_Candidates[$candidate_key]->getName() : $this->_Candidates[$candidate_key] ;
			endif;
		}

		public function existCandidateId ($candidate_id, $strict = true)
		{
			return ($strict) ? in_array($candidate_id, $this->_Candidates, true) : in_array((string) $candidate_id, $this->_Candidates) ;
		}

		public function getCandidateObjectByName ($s)
		{
			foreach ($this->_Candidates as &$oneCandidate)
			{
				if ($oneCandidate->getName() === $s) {
					return $oneCandidate;
				}
			}
		}



/////////// VOTING ///////////


	// Close the candidate config, be ready for voting (optional)
	protected function setStateToVote ()
	{
		if ( $this->_State === 1 )
			{ 
				if (empty($this->_Candidates))
					{ throw new namespace\CondorcetException(20); }

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
		$this->prepareVoteInput($vote, $tag);

		// Check Max Vote Count
		if ( self::$_max_vote_number !== null && !$this->_ignoreStaticMaxVote && $this->countVotes() >= self::$_max_vote_number )
			{ throw new namespace\CondorcetException(16, self::$_max_vote_number); }


		// Register vote
		return $this->registerVote($vote, $tag) ; // Return the vote object
	}

		// return True or throw an Exception
		public function prepareModifyVote (namespace\Vote $existVote)
			{
				try	{
					$this->prepareVoteInput($existVote);
					$this->setStateToVote();
				}
				catch (Exception $e) {
					throw $e;
				}
			}

		// Return the well formated vote to use.
		protected function prepareVoteInput (&$vote, $tag = null)
		{
			if (!($vote instanceof namespace\Vote))
			{
				$vote = new namespace\Vote ($vote, $tag);
			}

			// Check array format && Make checkVoteCandidate
			if ( !$this->checkVoteCandidate($vote) )
				{ throw new namespace\CondorcetException(5); }
		}


		protected function checkVoteCandidate (namespace\Vote $vote)
		{
			$linkCount = $vote->countLinks();

			if ( $vote->countInputRanking() > $this->_CandidatesCount )
				{ return false ; }

			$mirror = $vote->getRanking(); $change = false;
			foreach ($vote as $rank => $choice)
			{
				foreach ($choice as $choiceKey => $candidate)
				{
					if ( !$this->existCandidateId($candidate, true) )
					{
						if ($linkCount === 0 && $this->existCandidateId($candidate, false))  :
							$mirror[$rank][$choiceKey] = $this->_Candidates[$this->getCandidateKey((string) $candidate)];
							$change = true;
						else :
							return false;
						endif;
					}
				}
			}

			if ($change)
			{
				$vote->setRanking(
									$mirror,
									( abs($vote->getTimestamp() - microtime(true)) > 0.5 ) ? ($vote->getTimestamp() + 0.001) : false
				);
			}

			return true ;
		}

		// Write a new vote
		protected function registerVote (namespace\Vote $vote, $tag = null)
		{
			// Set Phase 2
			$this->setStateToVote();

			// Vote identifiant
			$vote->addTags($tag);			
			
			// Register
			try {
				$vote->registerLink($this);
				$this->_Votes[] = $vote;				
			} catch (namespace\CondorcetException $e) {
				// Security : Check if vote object not already register
				throw new namespace\CondorcetException(6,'Vote object already registred');
			}			

			return $vote ;
		}


	public function removeVote ($in, $with = true)
	{
		$this->setStateToVote();

			//////
		
		$rem = [];

		if ($in instanceof namespace\Vote) :
			$key = $this->getVoteKey($in);
			if ($key !== false) :
				$this->_Votes[$key]->destroyLink($this);

				$rem[] = $this->_Votes[$key];

				unset($this->_Votes[$key]);
			endif;
		else :
			// Prepare Tags
			$tag = namespace\Vote::tagsConvert($in);

			// Deleting

			foreach ($this->getVotesList($tag, $with) as $key => $value)
			{
				$this->_Votes[$key]->destroyLink($this);

				$rem[] = $this->_Votes[$key];

				unset($this->_Votes[$key]);
			}

		endif;

		return $rem;
	}


	public function jsonVotes ($input)
	{
		$input = self::prepareJson($input);
		if ($input === false) { return $input ; }

			//////

		$adding = [] ;

		foreach ($input as $record)
		{
			if (empty($record['vote']))
				{ continue ; }

			$tags = (!isset($record['tag'])) ? null : $record['tag'] ;
			$multi = (!isset($record['multi'])) ? 1 : $record['multi'] ;

			for ($i = 0 ; $i < $multi ; $i++)
			{
				if (self::$_max_parse_iteration !== null && $count >= self::$_max_parse_iteration)
				{
					throw new namespace\CondorcetException(12, self::$_max_parse_iteration);
				}

				try {
					$adding[] = $this->addVote($record['vote'], $tags);
				} catch (Exception $e) {}
			}
		}

		return $adding ;
	}

	public function parseVotes ($input, $allowFile = true)
	{
		$input = self::prepareParse($input, $allowFile) ;
		if ($input === false) { return $input ; }

		// Check each lines
		$adding = [] ;
		foreach ($input as $line)
		{
			// Empty Line
			if (empty($line)) { continue ; }

			// Multiples
			$is_multiple = strpos($line, '*') ;
			if ($is_multiple !== false)
			{
				$multiple = trim( substr($line, $is_multiple + 1) ) ;

				// Errors
				if ( !is_numeric($multiple) )
				{ 
					throw new namespace\CondorcetException(13, null);
				}

				$multiple = intval($multiple) ;
				$multiple = floor($multiple) ;


				// Reformat line
				$line = substr($line, 0, $is_multiple) ;
			}
			else
				{ $multiple = 1 ; }

			// Tags + vote
			if (strpos($line, '||') !== false)
			{
				$data = explode('||', $line);

				$vote = $data[1] ;
				$tags = $data[0] ;
			}
			// Vote without tags
			else
			{
				$vote = $line ;
				$tags = null ;
			}

			// addVote
			for ($i = 0 ; $i < $multiple ; $i++)
			{
				if (self::$_max_parse_iteration !== null && count($adding) >= self::$_max_parse_iteration)
				{
					throw new namespace\CondorcetException(12, self::$_max_parse_iteration);
				}

				try {
					$adding[] = $this->addVote($vote, $tags);
				} catch (Exception $e) {}
			}
		}

		return $adding;
	}


	//:: VOTING TOOLS :://

	// How many votes are registered ?
	public function countVotes ($tag = null, $with = true)
	{
		if (!empty($tag))
		{
			return count( $this->getVotesList($tag, $with) ) ;
		}
		else
		{
			return count($this->_Votes) ;
		}
	}

	// Get the votes registered list
	public function getVotesList ($tag = null, $with = true)
	{
		if ($tag === null)
		{
			return $this->_Votes ;
		}
		else
		{
			$tag = namespace\Vote::tagsConvert($tag) ;
			if ($tag === null)
				{$tag = array();}

			$search = array() ;

			foreach ($this->_Votes as $key => $value)
			{
				$noOne = true ;
				foreach ($tag as $oneTag)
				{
					if ( ( $oneTag === $key ) || in_array($oneTag, $value->getTags(),true) ) :
						if ($with) :
							$search[$key] = $value;
							break ;
						else :
							$noOne = false;
						endif;
					endif;
				}

				if (!$with && $noOne)
					{ $search[$key] = $value ;}
			}

			return $search ;
		}
	}

	public function getVoteKey (namespace\Vote $vote) {
		return array_search($vote, $this->_Votes, true);
	}

	public function getVoteByKey ($key) {
		if (!is_int($key)) :
			return false;
		elseif (!isset($this->_Votes[$key])) :
			return false;
		else :
			return $this->_Votes[$key];
		endif;
	}


/////////// RETURN RESULT ///////////


	//:: PUBLIC FUNCTIONS :://

	// Generic function for default result with ability to change default object method
	public function getResult ($method = true, array $options = [])
	{
		$options = $this->formatResultOptions($options);

		// Filter if tag is provided & return
		if ($options['%tagFilter'])
		{ 
			$timer_start = microtime(true);

			$filter = new self ($this->_Method) ;

			foreach ($this->getCandidatesList() as $candidate)
			{
				$filter->addCandidate($candidate);
			}
			foreach ($this->getVotesList($options['tags'], $options['withTag']) as $vote)
			{
				$filter->addVote($vote) ;
			}

			$this->setTimer($timer_start) ;

			return $filter->getResult($method, ['algoOptions' => $options['algoOptions']]) ;
		}

			////// Start //////

		// Method
		$this->setMethod() ;
		// Prepare
		$this->prepareResult() ;

			//////

		$timer_start = microtime(true);

		if ($method === true)
		{
			$this->initResult($this->_Method) ;

			$result = $this->_Calculator[$this->_Method]->getResult($options['algoOptions']);
		}
		elseif (self::isAuthMethod($method))
		{
			$this->initResult($method) ;

			$result = $this->_Calculator[$method]->getResult($options['algoOptions']);
		}
		else
		{
			throw new namespace\CondorcetException(8,$method) ;
		}

		$this->setTimer($timer_start) ;

		return ($options['human']) ? $this->humanResult($result) : $result ;
	}

		protected function humanResult ($robot, $asString = false)
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
				elseif ($asString)
					{ $human[$key] = implode(',',$value); }
			}

			return $human ;
		}


	public function getWinner ($substitution = null)
	{
		if ( $substitution )
		{
			if ($substitution === true)
				{$substitution = $this->_Method ;}

			if ( self::isAuthMethod($substitution) )
				{$algo = $substitution ;}
			else
				{throw new namespace\CondorcetException(9,$substitution);}
		}
		else
			{$algo = 'Condorcet_Basic';}

			//////

		return self::format($this->getResult($algo)[1],false,false);
	}


	public function getLoser ($substitution = null)
	{
		if ( $substitution )
		{			
			if ($substitution === true)
				{$substitution = $this->_Method ;}
			
			if ( self::isAuthMethod($substitution) )
				{$algo = $substitution ;}
			else
				{throw new namespace\CondorcetException(9,$substitution);}
		}
		else
			{$algo = 'Condorcet_Basic';}

			//////

		$result = $this->getResult($algo);

		return self::format($result[count($result)],false,false);
	}


	public function getResultStats ($method = true)
	{
		// Method
		$this->setMethod() ;
		// Prepare
		$this->prepareResult() ;

			//////

		if ($method === true)
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
			throw new namespace\CondorcetException(8) ;
		}

		if (!is_null($stats))
			{ return $stats ; }
		else
			{ return $this->getPairwise(); }
	}


	public function computeResult ($method = true)
	{
		$this->getResult($method,['human' => false]);
		$this->getResultStats($method);
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
			throw new namespace\CondorcetException(6) ;
		}
	}


	protected function initResult ($method)
	{
		if ( !isset($this->_Calculator[$method]) )
		{
			$class = __NAMESPACE__.'\\'.$method ;
			$this->_Calculator[$method] = new $class($this);
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


	protected function formatResultOptions ($arg)
	{
		// About tag filter
		if (isset($arg['tags'])):
			$arg['%tagFilter'] = true;

			if ( !isset($arg['withTag']) || !is_bool($arg['withTag']) ) :
				$arg['withTag'] = true;
			endif;
		else:
			$arg['%tagFilter'] = false;
		endif;

		// About algo Options
		if ( !isset($arg['algoOptions']) ) {
			$arg['algoOptions'] = null;
		}

		// Human Option (internal use)
		if ( !isset($arg['human']) || !is_bool($arg['human']) ) {
			$arg['human'] = true;
		}

		return $arg;
	}


	//:: GET RAW DATA :://

	public function getPairwise ($explicit = true)
	{
		$this->prepareResult() ;

		if (!$explicit)
			{ return $this->_Pairwise; }

		$explicit_pairwise = array() ;

		foreach ($this->_Pairwise as $candidate_key => $candidate_value)
		{
			$candidate_name = $this->getCandidateId($candidate_key, true) ;
			
			foreach ($candidate_value as $mode => $mode_value)
			{
				foreach ($mode_value as $candidate_list_key => $candidate_list_value)
				{
					$explicit_pairwise[$candidate_name][$mode][$this->getCandidateId($candidate_list_key, true)] = $candidate_list_value ;
				}
			}
		}

		return $explicit_pairwise ;
	}



/////////// PROCESS RESULT ///////////


	//:: COMPUTE PAIRWISE :://

	protected function doPairwise ()
	{		
		$timer_start = microtime(true);

		$this->_Pairwise = array() ;

		foreach ( $this->_Candidates as $candidate_key => $candidate_id )
		{
			$this->_Pairwise[$candidate_key] = array( 'win' => array(), 'null' => array(), 'lose' => array() ) ;

			foreach ( $this->_Candidates as $candidate_key_r => $candidate_id_r )
			{
				if ($candidate_key_r !== $candidate_key)
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
			$done_Candidates = array() ;

			foreach ($vote_ranking->getContextualVote($this) as $candidates_in_rank)
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
								in_array($g_candidate_key, $candidates_in_rank_keys, true)
							)
						{
							$this->_Pairwise[$candidate_key]['null'][$g_candidate_key]++ ;
						}
					}
				}
			}
		}

		// Lose
		foreach ( $this->_Pairwise as $option_key => $option_results )
		{
			foreach ($option_results['win'] as $option_compare_key => $option_compare_value)
			{
				$this->_Pairwise[$option_key]['lose'][$option_compare_key] = $this->countVotes() - (
							$this->_Pairwise[$option_key]['win'][$option_compare_key] + 
							$this->_Pairwise[$option_key]['null'][$option_compare_key]
						);
			}
		}

		$this->setTimer($timer_start);
	}



/////////// TOOLS FOR MODULAR ALGORITHMS ///////////


	public static function makeStatic_PairwiseComparison ($pairwise)
	{
		$comparison = array();

		foreach ($pairwise as $candidate_key => $candidate_data)
		{
			$comparison[$candidate_key]['win'] = 0 ;
			$comparison[$candidate_key]['null'] = 0 ;
			$comparison[$candidate_key]['lose'] = 0 ;
			$comparison[$candidate_key]['balance'] = 0 ;
			$comparison[$candidate_key]['worst_defeat'] = 0 ;

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

					// Worst defeat
					if ($comparison[$candidate_key]['worst_defeat'] < $candidate_data['lose'][$opponenent['key']])
					{
						$comparison[$candidate_key]['worst_defeat'] = $candidate_data['lose'][$opponenent['key']] ;
					}
				}
			}
		}

		return $comparison ;
	}

	public static function makeStatic_PairwiseSort ($pairwise)
	{
		$comparison = self::makeStatic_PairwiseComparison($pairwise);

		$score = array() ;	

		foreach ($pairwise as $candidate_key => $candidate_value)
		{
			foreach ($candidate_value['win'] as $challenger_key => $challenger_value)
			{
				if ($challenger_value > $candidate_value['lose'][$challenger_key])
				{
					$score[$candidate_key.'>'.$challenger_key]['score'] = $challenger_value ;
					$score[$candidate_key.'>'.$challenger_key]['minority'] = $candidate_value['lose'][$challenger_key] ;
					$score[$candidate_key.'>'.$challenger_key]['margin'] = $candidate_value['win'][$challenger_key] - $candidate_value['lose'][$challenger_key] ;
				}
				elseif ( $challenger_value === $candidate_value['lose'][$challenger_key] && !isset($score[$challenger_key.'>'.$candidate_key]) )
				{
					if ($comparison[$candidate_key]['worst_defeat'] <= $comparison[$challenger_key]['worst_defeat'])
					{
						$score[$candidate_key.'>'.$challenger_key]['score'] = 0.1 ;
						$score[$candidate_key.'>'.$challenger_key]['minority'] = $candidate_value['lose'][$challenger_key] ;
						$score[$candidate_key.'>'.$challenger_key]['margin'] = $candidate_value['win'][$challenger_key] - $candidate_value['lose'][$challenger_key] ;
					}
				}
			}
		}

		uasort($score, function ($a, $b){
			if ($a['score'] < $b['score']) {return 1;} elseif ($a['score'] > $b['score']) {return -1 ;}
			elseif ($a['score'] === $b['score'])
			{
				if ($a['minority'] > $b['minority'])
					{ return 1 ; }
				elseif ($a['minority'] < $b['minority'])
					{ return -1 ; }
				elseif ($a['minority'] === $b['minority'])
					{ 
						if ($a['margin'] < $b['margin'])
							{ return 1 ; }
						elseif ($a['margin'] > $b['margin'])
							{ return -1 ; }
						else
							{ return 0 ; }
					}
			}
		});

		return $score ;
	}

}

// Interface with the aim of verifying the good modular implementation of algorithms.
interface Condorcet_Algo
{
	public function getResult($options);
	public function getStats();
}


// Generic for Algorithms
abstract class CondorcetAlgo
{
	protected $_selfElection;

	public function __construct (namespace\Condorcet $mother)
	{
		$this->_selfElection = $mother;
	}
}

// Custom Exeption
class CondorcetException extends \Exception
{
	protected $_infos ;

	public function __construct ($code = 0, $infos = '')
	{
		$this->_infos = $infos ;

		parent::__construct($this->correspondence($code), $code);
	}

	public function __toString ()
	{
		   return __CLASS__ . ": [{$this->code}]: {$this->message} (line: {$this->file}:{$this->line})\n";
	}

	protected function correspondence ($code)
	{
		// Common
		$error[1] = 'Bad candidate format';
		$error[2] = 'The voting process has already started';
		$error[3] = 'This candidate ID is already registered';
		$error[4] = 'This candidate ID do not exist';
		$error[5] = 'Bad vote format | '.$this->_infos;
		$error[6] = 'You need to specify votes before results';
		$error[7] = 'Your Candidate ID is too long > ' . namespace\Condorcet::MAX_LENGTH_CANDIDATE_ID;
		$error[8] = 'This method do not exist';
		$error[9] = 'The algo class you want has not been defined';
		$error[10] = 'The algo class you want is not correct';
		$error[11] = 'You try to unserialize an object version older than your actual Class version. This is a problematic thing';
		$error[12] = 'You have exceeded the number of votes allowed for this method.';
		$error[13] = 'Formatting error: You do not multiply by a number!';
		$error[14] = 'parseVote() must take a string (raw or path) as argument';
		$error[15] = 'Input must be valid Json format';
		$error[16] = 'You have exceeded the maximum number of votes allowed per election ('.$this->_infos.').';
		$error[17] = 'Bad tags input format';
		$error[18] = 'New vote can\'t match Candidate of his elections';
		$error[19] = 'This name is not allowed in because of a namesake in the election in which the object participates.';
		$error[20] = 'You need to specify one or more candidates before voting';
		$error[21] = 'Bad vote timestamp format';


		// Algorithms
		$error[101] = 'KemenyYoung is configured to accept only '.$this->_infos.' candidates';

		if ( array_key_exists($code, $error) )
		{
			return $error[$code];
		}
		else
		{
			return (!is_null($this->_infos)) ? $this->_infos : 'Mysterious Error' ;
		}
	}
}

class Candidate
{
	use namespace\CandidateVote_CondorcetLink ;

	private $_name ;

		///

	public function __construct ($name)
	{
		$this->setName($name);
	}

	public function __toString ()
	{
		return $this->getName();
	}

		///

	// SETTERS

	public function setName ($name)
	{
		$name = trim((string) $name);

		if (mb_strlen($name) > namespace\Condorcet::MAX_LENGTH_CANDIDATE_ID )
			{ throw new namespace\CondorcetException(1, $name) ; }

		if (!$this->checkName($name))
			{ throw new namespace\CondorcetException(19, $name); }

		$this->_name[] = array('name' => $name, 'timestamp' => microtime(true));

		return $this->getName() ;
	}

	// GETTERS

	public function getName ($full = false)
	{
		return ($full) ? end($this->_name) : end($this->_name)['name'] ;
	}

	public function getHistory ()
	{
		return $this->_name;
	}

	public function getCreateTimestamp ()
	{
		return $this->_name[0]['timestamp'];
	}

	public function getTimestamp ()
	{
		return end($this->_name)['timestamp'];
	}

		///

	// INTERNAL

	private function checkName ($name)
	{
		foreach ($this->_link as &$link)
		{
			if (!$link->canAddCandidate($name))
				{ return false; }
		}

		return true;
	}
}


class Vote implements \Iterator
{
	use namespace\CandidateVote_CondorcetLink ;

	// Implement Iterator

		private $position = 1;

		public function rewind() {
			$this->position = 1;
		}

		public function current() {
			return $this->getRanking()[$this->position];
		}

		public function key() {
			return $this->position;
		}

		public function next() {
			++$this->position;
		}

		public function valid() {
			return isset($this->getRanking()[$this->position]);
		}

	// Vote

	private $_ranking = array();

	private $_tags = array();
	private $_id;

		///

	public function __construct ($ranking, $tags = null, $ownTimestamp = false)
	{
		$this->setRanking($ranking, $ownTimestamp);
		$this->addTags($tags);
	}

	public function __sleep ()
	{
		$this->position = 1;

		return array_keys(get_object_vars($this));
	}

		///

	// GETTERS

	public function getRanking ()
	{
		if (!empty($this->_ranking))
		{
			return end($this->_ranking)['ranking'];
		}
		else
			{ return null; }
	}

	public function getHistory ()
	{
		return $this->_ranking;
	}


	public function getTags ()
	{
		return $this->_tags;
	}

	public function getCreateTimestamp ()
	{
		return $this->_ranking[0]['timestamp'];
	}

	public function getTimestamp ()
	{
		return end($this->_ranking)['timestamp'];
	}

	public function countInputRanking ()
	{
		return count(end($this->_ranking)['counter']);
	}

	public function getAllCandidates ()
	{
		$list = array();

		foreach ($this->getRanking() as $rank)
		{
			foreach ($rank as $oneCandidate)
			{
				$list[] = $oneCandidate ;
			}
		}

		return $list;
	}

	public function getContextualVote (namespace\Condorcet &$election, $string = false)
	{
		if (!$this->haveLink($election))
			{ return false; }

		$ranking = $this->getRanking();
		$present = $this->getAllCandidates();

		if (count($present) < $election->countCandidates())
		{
			$last_rank = array();
			foreach ($election->getCandidatesList(false) as $oneCandidate)
			{
				if (!in_array($oneCandidate->getName(), $present))
				{
					$last_rank[] = $oneCandidate;
				}
			}

			$ranking[] = $last_rank;
		}

		if ($string)
		{
			foreach ($ranking as &$rank)
			{
				foreach ($rank as &$oneCandidate)
				{
					$oneCandidate = (string) $oneCandidate;
				}
			}
		}

		return $ranking;
	}


	// SETTERS

	public function setRanking ($rankingCandidate, $ownTimestamp = false)
	{
		// Timestamp
		if ($ownTimestamp !== false) :
			if (!is_numeric($ownTimestamp)) :
				throw new namespace\CondorcetException(21);
			elseif (!empty($this->_ranking) && $this->getTimestamp() >= $ownTimestamp) :
				throw new namespace\CondorcetException(21);
			endif;
		endif;

		// Ranking
		$candidateCounter = $this->formatRanking($rankingCandidate);

		$this->archiveRanking($rankingCandidate, $candidateCounter, $ownTimestamp);

		if (!empty($this->_link))
		{
			try {
				foreach ($this->_link as &$link)
				{
					$link->prepareModifyVote($this);
				}
			}
			catch (namespace\CondorcetException $e) {
				
				array_pop($this->_ranking);

				throw new namespace\CondorcetException(18);
			}
		}
	}

		private function formatRanking (&$ranking)
		{
			if (is_string($ranking))
				{ $ranking = self::convertVoteInput($ranking); }

			if (!is_array($ranking) || empty($ranking)) :
				throw new namespace\CondorcetException(5);
			endif;


			ksort($ranking);
			
			$i = 1 ; $vote_r = array() ;
			foreach ($ranking as &$value)
			{
				if ( !is_array($value) )
				{
					$vote_r[$i] = array($value) ;
				}
				else
				{
					$vote_r[$i] = $value ;
				}

				$i++ ;
			}

			$ranking = $vote_r;

			$counter = 0;
			$list_candidate = array();
			foreach ($ranking as &$line)
			{
				foreach ($line as &$Candidate) :
					if ( !($Candidate instanceof namespace\Candidate) ) :
						$Candidate = new namespace\Candidate ($Candidate);
					endif;

					$counter++;

				// Check Duplicate

					// Check objet reference AND check candidates name
					if (!in_array($Candidate, $list_candidate, true) && !in_array($Candidate, $list_candidate)) :
						$list_candidate[] = $Candidate;
					else : throw new namespace\CondorcetException(5); endif;

				endforeach;
			}

			return $counter;
		}

		// From a string like 'A>B=C=H>G=T>Q'
		public static function convertVoteInput ($formula)
		{
			$vote = explode('>', $formula);

			foreach ($vote as &$rank_vote)
			{
				$rank_vote = explode('=', $rank_vote);

				// Del space at start and end
				foreach ($rank_vote as &$value)
				{
					$value = trim($value);
				}
			}

			return $vote ;
		}


	public function addTags ($tags)
	{
		if (is_object($tags) || is_bool($tags))
			{ throw new namespace\CondorcetException(17); }

		$tags = self::tagsConvert($tags);

		if (empty($tags))
			{ return $this->getTags(); }


		foreach ($tags as $key => $tag)
		{			
			if (is_numeric($tag)) :
				throw new namespace\CondorcetException(17);
			elseif (in_array($tag, $this->_tags, true)) :
				unset($tags[$key]);
			endif;
		}

		foreach ($tags as $tag)
		{
			$this->_tags[] = $tag;
		}

		return $this->getTags();
	}

		public static function tagsConvert ($tags)
		{
			if (empty($tags))
				{ return null; }

			// Make Array
			if (!is_array($tags))
			{
				$tags = explode(',', $tags);
			}

			// Trim tags
			foreach ($tags as $key => &$oneTag)
			{
				$oneTag = (!ctype_digit($oneTag)) ? trim($oneTag) : intval($oneTag) ;

				if (empty($oneTag) || is_object($oneTag) || is_bool($oneTag))
					{unset($tags[$key]);}
			}

			return $tags ;
		}


		///

	// INTERNAL

		private function archiveRanking ($ranking, $counter, $ownTimestamp)
		{
			$this->_ranking[] = array(
										'ranking' => $ranking,
										'timestamp' => ($ownTimestamp !== false) ? (float) $ownTimestamp : microtime(true),
										'counter' => $counter
										);

			$this->rewind();
		}
}


/////////// TRAITS ///////////


// Generic for Candidate & Vote Class
trait CandidateVote_CondorcetLink
{
	private $_link = array() ;

	public function __clone ()
	{
		$this->destroyAllLink();
	}

	public function __debugInfo ()
	{
		$var = get_object_vars($this);

		foreach ($var['_link'] as $key => $oneLink)
		{
			$var['_link'][$key] = "Object \Condorcet\Condorcet => " . sha1(spl_object_hash($oneLink));
		}

		return $var;
	}

	public function haveLink (namespace\Condorcet &$election)
	{
		return in_array($election, $this->_link, true);
	}

	public function countLinks ()
	{
		return count($this->_link);
	}

	public function getLinks ()
	{
		return (!empty($this->_link)) ? $this->_link : NULL ;
	}

	// Internal
		# Dot not Overloading ! Do not Use !

	public function registerLink (namespace\Condorcet &$election)
	{
		if (array_search($election, $this->_link, true) === false)
			{ $this->_link[] = $election ; }
		else
			{ throw new CondorcetException; }
	}

	public function destroyLink (namespace\Condorcet &$election)
	{
		$destroyKey = array_search($election, $this->_link, true);

		if ($destroyKey !== false)
		{
			unset($this->_link[$destroyKey]);
			return true ;
		}
		else
			{ throw new CondorcetException; }
	}

	protected function destroyAllLink ()
	{
		$this->_link = array();
	}
}


/////////// TOOLS ///////////


// Thanks to Jorge Gomes @cyberkurumin 
class Permutation
{
	public static $_prefix = 'C';

	public $results = [];

	public static function countPossiblePermutations ($candidatesNumber) {
		
		if (!is_int($candidatesNumber))
			{ return false; }


		$result = $candidatesNumber ;

		for ($iteration = 1 ; $iteration < $candidatesNumber ; $iteration++)
		{
			$result = $result * ($candidatesNumber - $iteration) ;
		}

		return $result ;
	}


	public function __construct($arr) {
		$this->_exec(
			$this->_permute( (is_int($arr)) ? $this->createCandidates($arr) : $arr )
		);
	}

	public function getResults ($serialize = false)	{
		return ($serialize) ? serialize($this->results) : $this->results ;
	}

	public function writeResults ($path) {
		file_put_contents($path, $this->getResults(true));
	}

	protected function createCandidates ($numberOfCandidates) {
		$arr = array();

		for ($i = 0 ; $i < $numberOfCandidates ; $i++) {
			$arr[] = self::$_prefix.$i;
		}
		return $arr;
	}

	private function _exec($a, array $i = []) {
		if (is_array($a)) {
			foreach($a as $k => $v) {
				$i2 = $i;
				$i2[] = $k;

				$this->_exec($v, $i2);
			}
		}
		else {
			$i[] = $a;

			$this->results[] = $i;
		}
	}

	private function _permute(array $arr) {
		$out = array();
		if (count($arr) > 1)
			foreach($arr as $r => $c) {
				$n = $arr;
				unset($n[$r]);
				$out[$c] = $this->_permute($n);
			}
		else {
			return array_shift($arr);
		}
		return $out;
	}
}