<?php
/*
	Condorcet PHP Class, with Schulze Methods and others !

	Version : 0.91

	By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
	https://github.com/julien-boudry/Condorcet_Schulze-PHP_Class
*/
namespace Condorcet ;


// Condorcet PSR-O autoload. Can be skipped by other Autoload from framework & Co
require_once __DIR__ . DIRECTORY_SEPARATOR . '__CondorcetAutoload.php';


// Set the default Condorcet Class algorithm
namespace\Condorcet::setClassMethod('Schulze') ;


// Base Condorcet class
class Condorcet
{

/////////// CLASS ///////////


	const VERSION = '0.91' ;

	const ENV = 'DEV' ;
	const MAX_LENGTH_CANDIDATE_ID = 30 ; // Max length for candidate identifiant string

	protected static $_classMethod	= null ;
	protected static $_authMethods	= [] ;
	protected static $_forceMethod	= false ;
	protected static $_maxParseIteration = null ;
	protected static $_maxVoteNumber = null ;
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
			self::$_maxParseIteration = $value ;
			return self::$_maxParseIteration ;
		}
		else
			{ return false ; }
	}

	// Change max vote number
	public static function setMaxVoteNumber ($value)
	{
		if ( is_int($value) || ($value === null || $value === false) )
		{
			self::$_maxVoteNumber = ($value === false) ? null : $value ;
			return self::$_maxVoteNumber ;
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
			
			$r = $input;

			if ($convertObject) :
				if ($input instanceof namespace\Candidate) :
					$r = (string) $input;
				elseif ($input instanceof namespace\Vote) :
					$r = $input->getRanking();
				endif;
			endif;

		elseif (!is_array($input)) :
			$r = $input;
		else :
			foreach ($input as $key => $line) :
				$input[$key] = self::format($line,false,$convertObject);
			endforeach;

			if (count($input) === 1 && count(reset($input)) === 1):
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
							'class_MaxParseIterations'=> self::$_maxParseIteration,

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
				if (self::$_maxParseIteration !== null && count($adding) >= self::$_maxParseIteration) :
					throw new namespace\CondorcetException(12, self::$_maxParseIteration);
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
		if ( self::$_maxVoteNumber !== null && !$this->_ignoreStaticMaxVote && $this->countVotes() >= self::$_maxVoteNumber )
			{ throw new namespace\CondorcetException(16, self::$_maxVoteNumber); }


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

			if ( $vote->countRankingCandidates() > $this->_CandidatesCount )
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
				if (self::$_maxParseIteration !== null && $count >= self::$_maxParseIteration)
				{
					throw new namespace\CondorcetException(12, self::$_maxParseIteration);
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
				if (self::$_maxParseIteration !== null && count($adding) >= self::$_maxParseIteration)
				{
					throw new namespace\CondorcetException(12, self::$_maxParseIteration);
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