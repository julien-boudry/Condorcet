<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\ElectionProcess;

use CondorcetPHP\Condorcet\Candidate;
use CondorcetPHP\Condorcet\Throwable\CondorcetException;
use CondorcetPHP\Condorcet\CondorcetUtil;


// Manage Candidates for Election class
trait CandidatesProcess
{

/////////// CONSTRUCTOR ///////////

    // Data and global options
    protected $_Candidates = []; // Candidate list
    protected string $_AutomaticNewCandidateName = 'A';


/////////// GET CANDIDATES ///////////

    // Count registered candidates
    public function countCandidates () : int
    {
        return count($this->_Candidates);
    }

    public function getCandidatesList () : array
    {
        return $this->_Candidates;
    }

    // Get the list of registered CANDIDATES
    public function getCandidatesListAsString () : array
    {
        $result = [];

        foreach ($this->_Candidates as $candidateKey => &$oneCandidate) :
            $result[$candidateKey] = $oneCandidate->getName();
        endforeach;

        return $result;
    }

    public function getCandidateKey ($candidate) : ?int
    {
        if ($candidate instanceof Candidate) :
            $r = array_search($candidate, $this->_Candidates, true);
        else:
            $r = array_search(trim((string) $candidate), $this->_Candidates, false);
        endif;

        return ($r !== false) ? $r : null;
    }

    public function getCandidateObjectFromKey (int $candidate_key) : ?Candidate
    {
        if (!array_key_exists($candidate_key, $this->_Candidates)) :
            return null;
        else :
            return $this->_Candidates[$candidate_key];
        endif;
    }

    public function isRegisteredCandidate ($candidate, bool $strict = true) : bool
    {
        return $strict ? in_array($candidate, $this->_Candidates, true) : in_array((string) $candidate, $this->_Candidates);
    }

    public function getCandidateObjectFromName (string $s) : ?Candidate
    {
        foreach ($this->_Candidates as $oneCandidate) :

            if ($oneCandidate->getName() === $s) :
                return $oneCandidate;
            endif;
        endforeach;

        return null;
    }


/////////// ADD & REMOVE CANDIDATE ///////////

    // Add a vote candidate before voting
    public function addCandidate ($candidate = null) : Candidate
    {
        // only if the vote has not started
        if ( $this->_State > 1 ) :
            throw new CondorcetException(2);
        endif;

        // Filter
        if ( is_bool($candidate) || is_array($candidate) || (is_object($candidate) && !($candidate instanceof Candidate)) ) :
            throw new CondorcetException(1);
        endif;


        // Process
        if ( empty($candidate) ) :
            while ( !$this->canAddCandidate($this->_AutomaticNewCandidateName) ) :
                $this->_AutomaticNewCandidateName++;
            endwhile;

            $newCandidate = new Candidate($this->_AutomaticNewCandidateName);
        else : // Try to add the candidate_id
            $newCandidate = ($candidate instanceof Candidate) ? $candidate : new Candidate ((string) $candidate);

            if ( !$this->canAddCandidate($newCandidate) ) :
                throw new CondorcetException(3,(string) $candidate);
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

    public function canAddCandidate ($candidate) : bool
    {
        return !$this->isRegisteredCandidate($candidate, false);
    }

    // Destroy a register vote candidate before voting
    public function removeCandidates ($candidates_input) : array
    {
        // only if the vote has not started
        if ( $this->_State > 1 ) :
            throw new CondorcetException(2);
        endif;
        
        if ( !is_array($candidates_input) ) :
            $candidates_input = [$candidates_input];
        endif;

        foreach ($candidates_input as &$candidate) :
            $candidate_key = $this->getCandidateKey($candidate);

            if ( $candidate_key === null ) :
                throw new CondorcetException(4,$candidate);
            endif;

            $candidate = $candidate_key;
        endforeach;

        $rem = [];
        foreach ($candidates_input as $candidate_key) :
            $this->_Candidates[$candidate_key]->destroyLink($this);

            $rem[] = $this->_Candidates[$candidate_key];

            unset($this->_Candidates[$candidate_key]);
        endforeach;

        return $rem;
    }


/////////// PARSE CANDIDATES ///////////

    public function addCandidatesFromJson (string $input) : array
    {
        $input = CondorcetUtil::prepareJson($input);

            //////

        $adding = [];
        foreach ($input as $candidate) :
            $candidate = new Candidate ($candidate);

            if (!$this->canAddCandidate($candidate)) :
                throw new CondorcetException(3);
            endif;

            $adding[] = $candidate;
        endforeach;

        // Add Candidates
        foreach ($adding as $oneCandidate) :
            $this->addCandidate($oneCandidate);
        endforeach;

        return $adding;
    }

    public function parseCandidates (string $input, bool $isFile = false) : array
    {
        $input = CondorcetUtil::prepareParse($input, $isFile);

        $adding = [];
        foreach ($input as $line) :
            // Empty Line
            if (empty($line)) :
                continue;
            endif;

            // addCandidate
            if (self::$_maxParseIteration !== null && count($adding) >= self::$_maxParseIteration) :
                throw new CondorcetException(12, (string) self::$_maxParseIteration);
            endif;

            if (!$this->canAddCandidate($line)) :
                throw new CondorcetException(3);
            endif;

            $adding[] = $line;
        endforeach;

        foreach ($adding as $oneNewCandidate) :
            $this->addCandidate($oneNewCandidate);
        endforeach;

        return $adding;
    }
}
