<?php
/*
    Condorcet PHP Class, with Schulze Methods and others !

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
namespace Condorcet;

use Condorcet\Condorcet;
use Condorcet\CondorcetException;
use Condorcet\CondorcetVersion;
use Condorcet\Vote;
use Condorcet\Algo\Pairwise;
use Condorcet\DataManager\VotesManager;
use Condorcet\Timer\Chrono as Timer_Chrono;
use Condorcet\Timer\Manager as Timer_Manager;


// Base Condorcet class
class Election
{

/////////// PROPERTIES ///////////
    const MAX_LENGTH_CANDIDATE_ID = 30; // Max length for candidate identifiant string

    protected static $_maxParseIteration = null;
    protected static $_maxVoteNumber = null;
    protected static $_checksumMode = false;

/////////// STATICS METHODS ///////////

    // Change max parse iteration
    public static function setMaxParseIteration ($value)
    {
        if (is_int($value) || $value === null)
        {
            self::$_maxParseIteration = $value;
            return self::$_maxParseIteration;
        }
        else
            { return false; }
    }

    // Change max vote number
    public static function setMaxVoteNumber ($value)
    {
        if ( is_int($value) || ($value === null || $value === false) )
        {
            self::$_maxVoteNumber = ($value === false) ? null : $value;
            return self::$_maxVoteNumber;
        }
        else
            { return false; }
    }


    // Check JSON format
    public static function isJson ($string)
    {
        if (is_numeric($string) || $string === 'true' || $string === 'false' || $string === 'null' || empty($string))
        { return false; }

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
            { throw new CondorcetException(14); }

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
            $is_comment = strpos($line, '#');
            if ($is_comment !== false)
            {
                $line = substr($line, 0, $is_comment);
            }

            // Trim
            $line = trim($line);
        }

        return $input;
    }


    public static function prepareJson ($input)
    {
        if (!self::isJson($input))
            { throw new CondorcetException(15); }

        return json_decode($input, true);
    }


/////////// CONSTRUCTOR ///////////

    use CondorcetVersion;

    // Data and global options
    protected $_Candidates; // Candidate list
    protected $_Votes; // Votes list

    // Mechanics
    protected $_i_CandidateId = 'A';
    protected $_State = 1; // 1 = Add Candidates / 2 = Voting / 3 = Some result have been computing
    protected $_timer;
    protected $_nextVoteTag = 0;
    protected $_ignoreStaticMaxVote = false;

    // Result
    protected $_Pairwise;
    protected $_Calculator;

        //////

    public function __construct ()
    {
        $this->_Candidates = [];
        $this->_Votes = new VotesManager;
        $this->_timer = new Timer_Manager;
    }

    public function __destruct ()
    {
        $this->destroyAllLink();
    }

    public function __sleep ()
    {
        // Don't include others data
        $include = array (
            '_Candidates',
            '_Votes',

            '_i_CandidateId',
            '_State',
            '_nextVoteTag',
            '_objectVersion',
            '_ignoreStaticMaxVote',

            '_Pairwise',
            '_Calculator',
        );

        !self::$_checksumMode AND array_push($include, '_timer');

        return $include;
    }

    public function __wakeup ()
    {
        if ( version_compare($this->getObjectVersion('MAJOR'),Condorcet::getVersion('MAJOR'),'!=') )
        {
            throw new CondorcetException(11, 'Your object version is '.$this->getObjectVersion().' but the class engine version is '.Condorcet::getVersion('ENV'));
        }
    }

    public function __clone ()
    {
        $this->registerAllLinks();
    }


/////////// INTERNAL GENERIC REGULATION ///////////


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


    // Return object state with somes infos
    public function getConfig ()
    {
        return array    (
                            'CondorcetObject_Version' => $this->getObjectVersion(),

                            'class_default_Method'  => Condorcet::getDefaultMethod(),

                            'class_authMethods'=> Condorcet::getAuthMethods(),
                            'class_MaxParseIterations'=> self::$_maxParseIteration,

                            'state'     => $this->_State
                        );
    }


    protected function setTimer ($timer)
    {
        $this->_lastTimer = microtime(true) - $timer;
        $this->_globalTimer += $this->_lastTimer;
    }

    public function getGlobalTimer ($float = false) {
        return $this->_timer->getGlobalTimer($float);
    }

    public function getLastTimer ($float = false) {
        return $this->_timer->getLastTimer($float);
    }

    public function getTimerManager () {
        return $this->_timer;
    }

    public function getChecksum ()
    {
        self::$_checksumMode = true;

        $r = hash('sha256',
            serialize( array( $this->_Candidates, $this->_Votes, $this->_Pairwise, $this->_Calculator ) ).
            $this->getObjectVersion('major')
        );

        self::$_checksumMode = false;

        return $r;
    }

    public function ignoreMaxVote ($state = true)
    {
        $this->_ignoreStaticMaxVote = (is_bool($state)) ? $state : true;
        return $this->_ignoreStaticMaxVote;
    }


/////////// CANDIDATES ///////////


    // Add a vote candidate before voting
    public function addCandidate ($candidate_id = null)
    {
        // only if the vote has not started
        if ( $this->_State > 1 )
            { throw new CondorcetException(2); }

        // Filter
        if ( is_bool($candidate_id) || is_array($candidate_id) || (is_object($candidate_id) && !($candidate_id instanceof Candidate)) )
            { throw new CondorcetException(1, $candidate_id); }


        // Process
        if ( empty($candidate_id) ) // $candidate_id is empty ...
        {
            while ( !$this->canAddCandidate($this->_i_CandidateId) )
            {
                $this->_i_CandidateId++;
            }

            $newCandidate = new Candidate($this->_i_CandidateId);
        }
        else // Try to add the candidate_id
        {
            $newCandidate = ($candidate_id instanceof Candidate) ? $candidate_id : new Candidate ($candidate_id);

            if ( !$this->canAddCandidate($newCandidate) )
                { throw new CondorcetException(3,$candidate_id); }
        }

        // Register it
        $this->_Candidates[] = $newCandidate;

        // Linking
        $newCandidate->registerLink($this);

        return $newCandidate;
    }

        public function canAddCandidate ($candidate_id)
        {
            return !$this->existCandidateId($candidate_id, false);
        }


    // Destroy a register vote candidate before voting
    public function removeCandidate ($list)
    {
        // only if the vote has not started
        if ( $this->_State > 1 ) { throw new CondorcetException(2); }

        
        if ( !is_array($list) )
        {
            $list   = array($list);
        }

        foreach ($list as &$candidate_id)
        {
            $candidate_key = $this->getCandidateKey($candidate_id);

            if ( $candidate_key === false )
                { throw new CondorcetException(4,$candidate_id); }

            $candidate_id = $candidate_key;
        }

        $rem = [];
        foreach ($list as $candidate_key)
        {
            $this->_Candidates[$candidate_key]->destroyLink($this);

            $rem[] = $this->_Candidates[$candidate_key];

            unset($this->_Candidates[$candidate_key]);
        }

        return $rem;
    }


    public function jsonCandidates ($input)
    {
        $input = self::prepareJson($input);
        if ($input === false) { return $input; }

            //////

        $adding = [];
        foreach ($input as $candidate)
        {
            try {
                $adding[] = $this->addCandidate($candidate);
            }
            catch (Exception $e) {}
        }

        return $adding;
    }


    public function parseCandidates ($input, $allowFile = true)
    {
        $input = self::prepareParse($input, $allowFile);
        if ($input === false) { return $input; }

        $adding = [];
        foreach ($input as $line)
        {
            // Empty Line
            if (empty($line)) { continue; }

            // addCandidate
            try {
                if (self::$_maxParseIteration !== null && count($adding) >= self::$_maxParseIteration) :
                    throw new CondorcetException(12, self::$_maxParseIteration);
                endif;

                $adding[] = $this->addCandidate($line);
            } catch (CondorcetException $e) {
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
            return count($this->_Candidates);
        }

        // Get the list of registered CANDIDATES
        public function getCandidatesList ($stringMode = false)
        {
            if (!$stringMode) :
                return $this->_Candidates;
            else :
                $result = [];

                foreach ($this->_Candidates as $candidateKey => &$oneCandidate)
                {
                    $result[$candidateKey] = $oneCandidate->getName();
                }

                return $result;
            endif;
        }

        public function getCandidateKey ($candidate_id)
        {
            if ($candidate_id instanceof Candidate) :
                return array_search($candidate_id, $this->_Candidates, true);
            else:
                return array_search(trim((string) $candidate_id), $this->_Candidates, false);
            endif;
        }

        public function getCandidateId ($candidate_key, $onlyName = false)
        {
            if (!array_key_exists($candidate_key, $this->_Candidates)) :
                return false;
            else :
                return ($onlyName) ? $this->_Candidates[$candidate_key]->getName() : $this->_Candidates[$candidate_key];
            endif;
        }

        public function existCandidateId ($candidate_id, $strict = true)
        {
            return ($strict) ? in_array($candidate_id, $this->_Candidates, true) : in_array((string) $candidate_id, $this->_Candidates);
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
                    { throw new CondorcetException(20); }

                $this->_State = 2;
            }

        // If voting continues after a first set of results
        elseif ( $this->_State > 2 )
            { 
                $this->cleanupResult();
            }

        return true;
    }


    // Add a single vote. Array key is the rank, each candidate in a rank are separate by ',' It is not necessary to register the last rank.
    public function addVote ($vote, $tag = null)
    {
        $this->prepareVoteInput($vote, $tag);

        // Check Max Vote Count
        if ( self::$_maxVoteNumber !== null && !$this->_ignoreStaticMaxVote && $this->countVotes() >= self::$_maxVoteNumber )
            { throw new CondorcetException(16, self::$_maxVoteNumber); }


        // Register vote
        return $this->registerVote($vote, $tag); // Return the vote object
    }

        // return True or throw an Exception
        public function prepareModifyVote (Vote $existVote)
            {
                try {
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
            if (!($vote instanceof Vote))
            {
                $vote = new Vote ($vote, $tag);
            }

            // Check array format && Make checkVoteCandidate
            if ( !$this->checkVoteCandidate($vote) )
                { throw new CondorcetException(5); }
        }


        protected function checkVoteCandidate (Vote $vote)
        {
            $linkCount = $vote->countLinks();

            if ( $vote->countRankingCandidates() > $this->countCandidates() )
                { return false; }

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

            return true;
        }

        // Write a new vote
        protected function registerVote (Vote $vote, $tag = null)
        {
            // Set Phase 2
            $this->setStateToVote();

            // Vote identifiant
            $vote->addTags($tag);           
            
            // Register
            try {
                $vote->registerLink($this);
                $this->_Votes[] = $vote;                
            } catch (CondorcetException $e) {
                // Security : Check if vote object not already register
                throw new CondorcetException(6,'Vote object already registred');
            }           

            return $vote;
        }


    public function removeVote ($in, $with = true)
    {
        $this->setStateToVote();

            //////
        
        $rem = [];

        if ($in instanceof Vote) :
            $key = $this->getVoteKey($in);
            if ($key !== false) :
                $this->_Votes[$key]->destroyLink($this);

                $rem[] = $this->_Votes[$key];

                unset($this->_Votes[$key]);
            endif;
        else :
            // Prepare Tags
            $tag = Vote::tagsConvert($in);

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
        if ($input === false) { return $input; }

            //////

        $adding = [];

        foreach ($input as $record)
        {
            if (empty($record['vote']))
                { continue; }

            $tags = (!isset($record['tag'])) ? null : $record['tag'];
            $multi = (!isset($record['multi'])) ? 1 : $record['multi'];

            for ($i = 0; $i < $multi; $i++)
            {
                if (self::$_maxParseIteration !== null && $count >= self::$_maxParseIteration)
                {
                    throw new CondorcetException(12, self::$_maxParseIteration);
                }

                try {
                    $adding[] = $this->addVote($record['vote'], $tags);
                } catch (Exception $e) {}
            }
        }

        return $adding;
    }

    public function parseVotes ($input, $allowFile = true)
    {
        $input = self::prepareParse($input, $allowFile);
        if ($input === false) { return $input; }

        // Check each lines
        $adding = [];
        foreach ($input as $line)
        {
            // Empty Line
            if (empty($line)) { continue; }

            // Multiples
            $is_multiple = strpos($line, '*');
            if ($is_multiple !== false)
            {
                $multiple = trim( substr($line, $is_multiple + 1) );

                // Errors
                if ( !is_numeric($multiple) )
                { 
                    throw new CondorcetException(13, null);
                }

                $multiple = intval($multiple);
                $multiple = floor($multiple);


                // Reformat line
                $line = substr($line, 0, $is_multiple);
            }
            else
                { $multiple = 1; }

            // Tags + vote
            if (strpos($line, '||') !== false)
            {
                $data = explode('||', $line);

                $vote = $data[1];
                $tags = $data[0];
            }
            // Vote without tags
            else
            {
                $vote = $line;
                $tags = null;
            }

            // addVote
            for ($i = 0; $i < $multiple; $i++)
            {
                if (self::$_maxParseIteration !== null && count($adding) >= self::$_maxParseIteration)
                {
                    throw new CondorcetException(12, self::$_maxParseIteration);
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
            return count( $this->getVotesList($tag, $with) );
        }
        else
        {
            return count($this->_Votes);
        }
    }

    // Get the votes registered list
    public function getVotesList ($tag = null, $with = true)
    {
        return $this->_Votes->getVotesList($tag, $with);
    }

    public function getVotesManager () {
        return $this->_Votes;
    }

    public function getVoteKey (Vote $vote) {
        return $this->_Votes->getVoteKey($vote);
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


/////////// RESULTS ///////////


    //:: PUBLIC FUNCTIONS :://

    // Generic function for default result with ability to change default object method
    public function getResult ($method = true, array $options = [])
    {
        $options = $this->formatResultOptions($options);

        // Filter if tag is provided & return
        if ($options['%tagFilter'])
        { 
            $chrono = new Timer_Chrono ($this->_timer, 'GetResult with filter');

            $filter = new self;

            foreach ($this->getCandidatesList() as $candidate)
            {
                $filter->addCandidate($candidate);
            }
            foreach ($this->getVotesList($options['tags'], $options['withTag']) as $vote)
            {
                $filter->addVote($vote);
            }

            unset($chrono);

            return $filter->getResult($method, ['algoOptions' => $options['algoOptions']]);
        }

            ////// Start //////

        // Prepare
        $this->prepareResult();

            //////

        $chrono = new Timer_Chrono ($this->_timer);

        if ($method === true)
        {
            $this->initResult(Condorcet::getDefaultMethod());

            $result = $this->_Calculator[Condorcet::getDefaultMethod()]->getResult($options['algoOptions']);
        }
        elseif ($method = Condorcet::isAuthMethod($method))
        {
            $this->initResult($method);

            $result = $this->_Calculator[$method]->getResult($options['algoOptions']);
        }
        else
        {
            throw new CondorcetException(8,$method);
        }

        $chrono->setRole('GetResult for '.$method);

        return ($options['human']) ? $this->humanResult($result) : $result;
    }

        protected function humanResult ($robot, $asString = false)
        {
            if (!is_array($robot))
                {return $robot;}

            $human = [];

            foreach ( $robot as $key => $value )
            {
                if (is_array($value))
                {
                    foreach ($value as $candidate_key)
                    {
                        $human[$key][] = $this->getCandidateId($candidate_key);
                    }
                }
                elseif (is_null($value))
                    { $human[$key] = null; }
                else
                    { $human[$key][] = $this->getCandidateId($value); }
            }

            foreach ( $human as $key => $value )
            {
                if (is_null($value))
                    { $human[$key] = null; }
                elseif ($asString)
                    { $human[$key] = implode(',',$value); }
            }

            return $human;
        }


    public function getWinner ($substitution = null)
    {
        $algo = $this->condorcetBasicSubstitution($substitution);

            //////

        if ($algo === Condorcet::CONDORCET_BASIC_CLASS) :
            $chrono = new Timer_Chrono ($this->_timer, 'GetWinner for CondorcetBasic');
            $this->initResult($algo);
            $result = $this->_Calculator[$algo]->getWinner();

            return ($result === null) ? null : $this->getCandidateId($result);
        else :
            return Condorcet::format($this->getResult($algo)[1],false,false);
        endif;
    }


    public function getLoser ($substitution = null)
    {
        $algo = $this->condorcetBasicSubstitution($substitution);

            //////

        if ($algo === Condorcet::CONDORCET_BASIC_CLASS) :
            $chrono = new Timer_Chrono ($this->_timer, 'GetLoser for CondorcetBasic');
            $this->initResult($algo);
            $result = $this->_Calculator[$algo]->getLoser();

            return ($result === null) ? null : $this->getCandidateId($result);
        else :
            $result = $this->getResult($algo);

            return Condorcet::format($result[count($result)],false,false);
        endif;
    }

        protected function condorcetBasicSubstitution ($substitution) {
            if ( $substitution )
            {           
                if ($substitution === true)
                    {$substitution = Condorcet::getDefaultMethod();}
                
                if ( Condorcet::isAuthMethod($substitution) )
                    {$algo = $substitution;}
                else
                    {throw new CondorcetException(9,$substitution);}
            }
            else
                {$algo = Condorcet::CONDORCET_BASIC_CLASS;}

            return $algo;
        }


    public function getResultStats ($method = true)
    {
        // Prepare
        $this->prepareResult();

            //////

        if ($method === true)
        {
            $this->initResult(Condorcet::getDefaultMethod());

            $stats = $this->_Calculator[Condorcet::getDefaultMethod()]->getStats();
        }
        elseif ($method = Condorcet::isAuthMethod($method))
        {
            $this->initResult($method);

            $stats = $this->_Calculator[$method]->getStats();
        }
        else
        {
            throw new CondorcetException(8);
        }

        if (!is_null($stats))
            { return $stats; }
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
        if ($this->_State > 2) :
            return false;
        elseif ($this->_State === 2) :
            $this->cleanupResult();

            // Do Pairewise
            $this->_Pairwise = new Pairwise ($this);

            // Change state to result
            $this->_State = 3;

            // Return
            return true;
        else :
            throw new CondorcetException(6);
        endif;
    }


    protected function initResult ($class)
    {
        if ( !isset($this->_Calculator[$class]) )
        {
            $this->_Calculator[$class] = new $class($this);
        }
    }


    // Cleanup results to compute again with new votes
    protected function cleanupResult ()
    {
        // Reset state
        if ($this->_State > 2)
        {
            $this->_State = 2;
        }

            //////

        // Clean pairwise
        $this->_Pairwise = null;

        // Algos
        $this->_Calculator = null;
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
        $this->prepareResult();

        return (!$explicit) ? $this->_Pairwise : $this->_Pairwise->getExplicitPairwise();
    }

}
