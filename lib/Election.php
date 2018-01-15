<?php
/*
    Condorcet PHP Class, with Schulze Methods and others !

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace Condorcet;

use Condorcet\Condorcet;
use Condorcet\CondorcetException;
use Condorcet\CondorcetVersion;
use Condorcet\Result;
use Condorcet\CondorcetUtil;
use Condorcet\Vote;
use Condorcet\Algo\Pairwise;
use Condorcet\DataManager\VotesManager;
use Condorcet\DataManager\DataHandlerDrivers\DataHandlerDriverInterface;
use Condorcet\ElectionProcess\ResultManager;
use Condorcet\Timer\Chrono as Timer_Chrono;
use Condorcet\Timer\Manager as Timer_Manager;


// Base Condorcet class
class Election
{

/////////// PROPERTIES ///////////

    public const MAX_LENGTH_CANDIDATE_ID = 30; // Max length for candidate identifiant string

    protected static $_maxParseIteration = null;
    protected static $_maxVoteNumber = null;
    protected static $_checksumMode = false;

/////////// STATICS METHODS ///////////

    // Change max parse iteration
    public static function setMaxParseIteration (?int $value) : ?int
    {
        self::$_maxParseIteration = $value;
        return self::$_maxParseIteration;
    }

    // Change max vote number
    public static function setMaxVoteNumber (?int $value) : ?int
    {
        self::$_maxVoteNumber = $value;
        return self::$_maxVoteNumber;
    }


/////////// CONSTRUCTOR ///////////

    use CondorcetVersion;

    // Data and global options
    protected $_Candidates = []; // Candidate list
    protected $_Votes; // Votes list

    // Mechanics
    protected $_i_CandidateId = 'A';
    protected $_State = 1; // 1 = Add Candidates / 2 = Voting / 3 = Some result have been computing
    protected $_timer;
    protected $_nextVoteTag = 0;
    protected $_ignoreStaticMaxVote = false;

    // Params
    protected $_ImplicitRanking = true;
    protected $_VoteWeightRule = false;

    // Result
    protected $_ResultManager;

        //////

    public function __construct ()
    {
        $this->_Candidates = [];
        $this->_Votes = new VotesManager ($this);
        $this->_timer = new Timer_Manager;
    }

    public function __destruct ()
    {
        $this->destroyAllLink();
    }

    public function __sleep () : array
    {
        // Don't include others data
        $include = [
            '_Candidates',
            '_Votes',

            '_i_CandidateId',
            '_State',
            '_nextVoteTag',
            '_objectVersion',
            '_ignoreStaticMaxVote',

            '_ImplicitRanking',
            '_VoteWeightRule',

            '_ResultManager'
        ];

        !self::$_checksumMode && array_push($include, '_timer');

        return $include;
    }

    public function __wakeup ()
    {
        if ( version_compare($this->getObjectVersion('MAJOR'),Condorcet::getVersion('MAJOR'),'!=') ) :
            throw new CondorcetException(11, 'Your object version is '.$this->getObjectVersion().' but the class engine version is '.Condorcet::getVersion('ENV'));
        endif;
    }

    public function __clone ()
    {
        $this->_Votes = clone $this->_Votes;
        $this->_Votes->setElection($this);      
        $this->registerAllLinks();

        $this->_timer = clone $this->_timer;

        if ($this->_ResultManager !== null) :
            $this->_ResultManager = clone $this->_ResultManager;
            $this->_ResultManager->setElection($this);
        endif;
    }


/////////// INTERNAL GENERIC REGULATION ///////////


    protected function registerAllLinks () : void
    {
        foreach ($this->_Candidates as $value) :
            $value->registerLink($this);
        endforeach;

        if ($this->_State > 1) :
            foreach ($this->_Votes as $value) :
                $value->registerLink($this);
            endforeach;
        endif;
    }

    protected function destroyAllLink () : void
    {
        foreach ($this->_Candidates as $value) :
            $value->destroyLink($this);
        endforeach;

        if ($this->_State > 1) :
            foreach ($this->_Votes as $value) :
                $value->destroyLink($this);
            endforeach;
        endif;
    }

        //////


    public function getGlobalTimer (bool $float = false) {
        return $this->_timer->getGlobalTimer($float);
    }

    public function getLastTimer (bool $float = false) {
        return $this->_timer->getLastTimer($float);
    }

    public function getTimerManager () : Timer_Manager {
        return $this->_timer;
    }

    public function getChecksum () : string
    {
        self::$_checksumMode = true;

        $r = hash_init('sha256');

        foreach ($this->_Candidates as $value) :
            hash_update($r, (string) $value);
        endforeach;

        foreach ($this->_Votes as $value) :
            hash_update($r, (string) $value);
        endforeach;

        $this->_ResultManager !== null
            && hash_update($r,serialize($this->_ResultManager->getPairwise()->getExplicitPairwise()));

        hash_update($r, $this->getObjectVersion('major'));

        self::$_checksumMode = false;

        return hash_final($r);
    }

    public function ignoreMaxVote (bool $state = true) : bool
    {
        return $this->_ignoreStaticMaxVote = $state;
    }

    public function getImplicitRankingRule () : bool
    {
        return $this->_ImplicitRanking;
    }

    public function setImplicitRanking (bool $rule = true) : bool
    {
        $this->_ImplicitRanking = $rule;
        $this->cleanupResult();
        return $this->getImplicitRankingRule();
    }

    public function isVoteWeightIsAllowed () : bool
    {
        return $this->_VoteWeightRule;
    }

    public function allowVoteWeight (bool $rule = true) : bool
    {
        $this->_VoteWeightRule = $rule;
        $this->cleanupResult();
        return $this->isVoteWeightIsAllowed();
    }


/////////// CANDIDATES ///////////


    // Add a vote candidate before voting
    public function addCandidate ($candidate_id = null) : Candidate
    {
        // only if the vote has not started
        if ( $this->_State > 1 ) :
            throw new CondorcetException(2);
        endif;

        // Filter
        if ( is_bool($candidate_id) || is_array($candidate_id) || (is_object($candidate_id) && !($candidate_id instanceof Candidate)) ) :
            throw new CondorcetException(1, $candidate_id);
        endif;


        // Process
        if ( empty($candidate_id) ) :
            while ( !$this->canAddCandidate($this->_i_CandidateId) ) :
                $this->_i_CandidateId++;
            endwhile;

            $newCandidate = new Candidate($this->_i_CandidateId);
        else : // Try to add the candidate_id
            $newCandidate = ($candidate_id instanceof Candidate) ? $candidate_id : new Candidate ((string) $candidate_id);

            if ( !$this->canAddCandidate($newCandidate) ) :
                throw new CondorcetException(3,$candidate_id);
            endif;
        endif;

        // Register it
        $this->_Candidates[] = $newCandidate;

        // Linking
        $newCandidate->registerLink($this);

        // Disallow other candidate object name matching 
        $newCandidate->setProvisionalState(false);

        return $newCandidate;
    }

        public function canAddCandidate ($candidate_id) : bool
        {
            return !$this->existCandidateId($candidate_id, false);
        }


    // Destroy a register vote candidate before voting
    public function removeCandidate ($list) : array
    {
        // only if the vote has not started
        if ( $this->_State > 1 ) :
            throw new CondorcetException(2);
        endif;
        
        if ( !is_array($list) ) :
            $list = [$list];
        endif;

        foreach ($list as &$candidate_id) :
            $candidate_key = $this->getCandidateKey($candidate_id);

            if ( $candidate_key === false ) :
                throw new CondorcetException(4,$candidate_id);
            endif;

            $candidate_id = $candidate_key;
        endforeach;

        $rem = [];
        foreach ($list as $candidate_key) :
            $this->_Candidates[$candidate_key]->destroyLink($this);

            $rem[] = $this->_Candidates[$candidate_key];

            unset($this->_Candidates[$candidate_key]);
        endforeach;

        return $rem;
    }


    public function jsonCandidates (string $input)
    {
        $input = CondorcetUtil::prepareJson($input);
        if ($input === false) :
            return $input;
        endif;

            //////

        $adding = [];
        foreach ($input as $candidate) :
            try {
                $adding[] = $this->addCandidate($candidate);
            }
            catch (CondorcetException $e) {}
        endforeach;

        return $adding;
    }

    public function parseCandidates (string $input, bool $allowFile = true)
    {
        $input = CondorcetUtil::prepareParse($input, $allowFile);
        if ($input === false) :
            return $input;
        endif;

        $adding = [];
        foreach ($input as $line) :
            // Empty Line
            if (empty($line)) :
                continue;
            endif;

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
        endforeach;

        return $adding;
    }


        //:: CANDIDATES TOOLS :://

        // Count registered candidates
        public function countCandidates () : int
        {
            return count($this->_Candidates);
        }

        // Get the list of registered CANDIDATES
        public function getCandidatesList (bool $stringMode = false) : array
        {
            if (!$stringMode) :
                return $this->_Candidates;
            else :
                $result = [];

                foreach ($this->_Candidates as $candidateKey => &$oneCandidate) :
                    $result[$candidateKey] = $oneCandidate->getName();
                endforeach;

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

        public function getCandidateId (int $candidate_key, bool $onlyName = false)
        {
            if (!array_key_exists($candidate_key, $this->_Candidates)) :
                return false;
            else :
                return ($onlyName) ? $this->_Candidates[$candidate_key]->getName() : $this->_Candidates[$candidate_key];
            endif;
        }

        public function existCandidateId ($candidate_id, bool $strict = true) : bool
        {
            return ($strict) ? in_array($candidate_id, $this->_Candidates, true) : in_array((string) $candidate_id, $this->_Candidates);
        }

        public function getCandidateObjectByName (string $s)
        {
            foreach ($this->_Candidates as $oneCandidate) :

                if ($oneCandidate->getName() === $s) :
                    return $oneCandidate;
                endif;
            endforeach;

            return false;
        }



/////////// VOTING ///////////


    // Close the candidate config, be ready for voting (optional)
    public function setStateToVote () : bool
    {
        if ( $this->_State === 1 ) :
                if (empty($this->_Candidates)) :
                    throw new CondorcetException(20);
                endif;

                $this->_State = 2;

        // If voting continues after a first set of results
        elseif ( $this->_State > 2 ) :
                $this->cleanupResult();
        endif;

        return true;
    }


    // Add a single vote. Array key is the rank, each candidate in a rank are separate by ',' It is not necessary to register the last rank.
    public function addVote ($vote, $tag = null) : Vote
    {
        $this->prepareVoteInput($vote, $tag);

        // Check Max Vote Count
        if ( self::$_maxVoteNumber !== null && !$this->_ignoreStaticMaxVote && $this->countVotes() >= self::$_maxVoteNumber ) :
            throw new CondorcetException(16, self::$_maxVoteNumber);
        endif;


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
            catch (\Exception $e) {
                throw $e;
            }
        }

        // Return the well formated vote to use.
        protected function prepareVoteInput (&$vote, $tag = null) : void
        {
            if (!($vote instanceof Vote)) :
                $vote = new Vote ($vote, $tag);
            endif;

            // Check array format && Make checkVoteCandidate
            if ( !$this->checkVoteCandidate($vote) ) :
                throw new CondorcetException(5);
            endif;
        }


        public function checkVoteCandidate (Vote $vote) : bool
        {
            $linkCount = $vote->countLinks();
            $links = $vote->getLinks();

            $mirror = $vote->getRanking();
            $change = false;
            foreach ($vote as $rank => $choice) :
                foreach ($choice as $choiceKey => $candidate) :
                    if ( !$this->existCandidateId($candidate, true) ) :
                        if ($candidate->getProvisionalState() && $this->existCandidateId($candidate, false)) :
                            if ( $linkCount === 0 || ($linkCount === 1 && reset($links) === $this) ) :
                                $mirror[$rank][$choiceKey] = $this->_Candidates[$this->getCandidateKey((string) $candidate)];
                                $change = true;
                            else :
                                return false;
                            endif;
                        endif;
                    endif;
                endforeach;
            endforeach;

            if ($change) :
                $vote->setRanking(
                                    $mirror,
                                    ( abs($vote->getTimestamp() - microtime(true)) > 0.5 ) ? ($vote->getTimestamp() + 0.001) : false
                );
            endif;

            return true;
        }

        // Write a new vote
        protected function registerVote (Vote $vote, $tag = null) : Vote
        {
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


    public function removeVote ($in, bool $with = true) : array
    {    
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
            foreach ($this->getVotesList($tag, $with) as $key => $value) :
                $this->_Votes[$key]->destroyLink($this);

                $rem[] = $this->_Votes[$key];

                unset($this->_Votes[$key]);
            endforeach;

        endif;

        return $rem;
    }


    public function jsonVotes (string $input)
    {
        $input = CondorcetUtil::prepareJson($input);
        if ($input === false) :
            return $input;
        endif;

            //////

        $adding = [];

        foreach ($input as $record) :
            if (empty($record['vote'])) :
                continue;
            endif;

            $tags = (!isset($record['tag'])) ? null : $record['tag'];
            $multi = (!isset($record['multi'])) ? 1 : $record['multi'];

            for ($i = 0; $i < $multi; $i++) :
                if (self::$_maxParseIteration !== null && $this->countVotes() >= self::$_maxParseIteration) :
                    throw new CondorcetException(12, self::$_maxParseIteration);
                endif;

                try {
                    $adding[] = $this->addVote($record['vote'], $tags);
                } catch (\Exception $e) {}
            endfor;
        endforeach;

        return $adding;
    }

    public function parseVotes (string $input, bool $allowFile = true)
    {
        $input = CondorcetUtil::prepareParse($input, $allowFile);
        if ($input === false) :
            return $input;
        endif;

        // Check each lines
        $adding = [];
        foreach ($input as $line) :
            // Empty Line
            if (empty($line)) :
                continue;
            endif;

            // Multiples
            $is_multiple = mb_strpos($line, '*');
            if ($is_multiple !== false) :
                $multiple = trim( substr($line, $is_multiple + 1) );

                // Errors
                if ( !is_numeric($multiple) ) :
                    throw new CondorcetException(13, null);
                endif;

                $multiple = intval($multiple);

                // Reformat line
                $line = substr($line, 0, $is_multiple);
            else :
                $multiple = 1;
            endif;

            // Vote Weight
            $is_voteWeight = mb_strpos($line, '^');
            if ($is_voteWeight !== false) :
                $weight = trim( substr($line, $is_voteWeight + 1) );

                // Errors
                if ( !is_numeric($weight) ) :
                    throw new CondorcetException(13, null);
                endif;

                $weight = intval($weight);

                // Reformat line
                $line = substr($line, 0, $is_voteWeight);
            else :
                $weight = 1;
            endif;

            // Tags + vote
            if (mb_strpos($line, '||') !== false) :
                $data = explode('||', $line);

                $vote = $data[1];
                $tags = $data[0];
            // Vote without tags
            else :
                $vote = $line;
                $tags = null;
            endif;

            // addVote
            for ($i = 0; $i < $multiple; $i++) :
                if (self::$_maxParseIteration !== null && count($adding) >= self::$_maxParseIteration) :
                    throw new CondorcetException(12, self::$_maxParseIteration);
                endif;

                try {
                    $adding[] = ($newVote = $this->addVote($vote, $tags));
                    $newVote->setWeight($weight);
                } catch (CondorcetException $e) {}
            endfor;
        endforeach;

        return $adding;
    }


    //:: LARGE ELECTION MODE :://

    public function setExternalDataHandler (DataHandlerDriverInterface $driver) : bool
    {
        if (!$this->_Votes->isUsingHandler()) :
            $this->_Votes->importHandler($driver);
            return true;
        else :
            throw new CondorcetException(24);
        endif;
    }

    public function removeExternalDataHandler () : bool
    {
        if ($this->_Votes->isUsingHandler()) :
            $this->_Votes->closeHandler();
            return true;
        else :
            throw new CondorcetException(23);
        endif;
    }


    //:: VOTING TOOLS :://

    // How many votes are registered ?
    public function countVotes ($tag = null, bool $with = true) : int
    {
        return $this->_Votes->countVotes(Vote::tagsConvert($tag),$with);
    }

    // Get the votes registered list
    public function getVotesList ($tag = null, bool $with = true) : array
    {
        return $this->_Votes->getVotesList(Vote::tagsConvert($tag), $with);
    }

    public function getVotesListAsString () : string
    {
        return $this->_Votes->getVotesListAsString();
    }

    public function getVotesManager () : VotesManager {
        return $this->_Votes;
    }

    public function getVoteKey (Vote $vote) {
        return $this->_Votes->getVoteKey($vote);
    }


/////////// RESULTS ///////////

    //:: PUBLIC FUNCTIONS :://

    // Generic function for default result with ability to change default object method
    public function getResult ($method = true, array $options = []) : Result
    {
        $this->prepareResult();

        return $this->_ResultManager->getResult($method,$options);
    }


    public function getWinner (?string $substitution = null)
    {
        $this->prepareResult();

        return $this->_ResultManager->getWinner($substitution);
    }


    public function getLoser (?string $substitution = null)
    {
        $this->prepareResult();

        return $this->_ResultManager->getLoser($substitution);
    }


    public function computeResult ($method = true) : void
    {
        $this->getResult($method);
    }


    //:: TOOLS FOR RESULT PROCESS :://


    // Prepare to compute results & caching system
    protected function prepareResult () : bool
    {
        if ($this->_State > 2) :
            return false;
        elseif ($this->_State === 2) :
            $this->cleanupResult();

            $this->_ResultManager = new ResultManager ($this);

            // Change state to result
            $this->_State = 3;

            // Return
            return true;
        else :
            throw new CondorcetException(6);
        endif;
    }


    // Cleanup results to compute again with new votes
    protected function cleanupResult () : void
    {
        // Reset state
        if ($this->_State > 2) : 
            $this->_State = 2;
        endif;

            //////

        $this->_ResultManager = null;
    }


    //:: GET RAW DATA :://

    public function getPairwise (bool $explicit = true)
    {
        $this->prepareResult();

        $pairwise = $this->_ResultManager->getPairwise($explicit);

        return (!$explicit) ? $pairwise : $pairwise->getExplicitPairwise($explicit);
    }

}
