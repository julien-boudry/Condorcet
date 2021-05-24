<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\ElectionProcess;

use CondorcetPHP\Condorcet\CondorcetDocAttributes\{Description, Example, FunctionReturn, PublicAPI, Related};
use CondorcetPHP\Condorcet\{Candidate, CondorcetUtil};
use CondorcetPHP\Condorcet\Throwable\CondorcetException;

// Manage Candidates for Election class
trait CandidatesProcess
{

/////////// CONSTRUCTOR ///////////

    // Data and global options
    protected array $_Candidates = []; // Candidate list
    protected string $_AutomaticNewCandidateName = 'A';


/////////// GET CANDIDATES ///////////

    // Count registered candidates
    #[PublicAPI]
    #[Description("Count the number of registered candidate")]
    #[FunctionReturn("Number of registered candidate for this election.")]
    #[Related("Election::getCandidatesList")]
    public function countCandidates () : int
    {
        return \count($this->_Candidates);
    }

    #[PublicAPI]
    #[Description("Return a list of registered Candidate into this election.")]
    #[FunctionReturn("List of Candidate into an array.")]
    #[Related("Election::countCandidates")]
    public function getCandidatesList () : array
    {
        return $this->_Candidates;
    }

    // Get the list of registered CANDIDATES
    #[PublicAPI]
    #[Description("Return a list of registered Candidate into this election.")]
    #[FunctionReturn("List of Candidate into an array populated by strign instead CandidateObject.")]
    #[Related("Election::countCandidates")]
    public function getCandidatesListAsString () : array
    {
        $result = [];

        foreach ($this->_Candidates as $candidateKey => &$oneCandidate) :
            $result[$candidateKey] = $oneCandidate->getName();
        endforeach;

        return $result;
    }

    public function getCandidateKey (Candidate|string $candidate) : ?int
    {
        if ($candidate instanceof Candidate) :
            $r = \array_search(needle: $candidate, haystack: $this->_Candidates, strict: true);
        else:
            $r = \array_search(needle: \trim((string) $candidate), haystack: $this->_Candidates, strict: false);
        endif;

        return ($r !== false) ? $r : null;
    }

    public function getCandidateObjectFromKey (int $candidate_key) : ?Candidate
    {
        if (!\array_key_exists($candidate_key, $this->_Candidates)) :
            return null;
        else :
            return $this->_Candidates[$candidate_key];
        endif;
    }

    #[PublicAPI]
    #[Description("Check if a candidate is already taking part in the election.")]
    #[FunctionReturn("True / False")]
    #[Related("Election::addCandidate")]
    public function isRegisteredCandidate (Candidate|string $candidate, bool $strictMode = true) : bool
    {
        return $strictMode ? \in_array(needle: $candidate, haystack: $this->_Candidates, strict: true) : \in_array(needle: (string) $candidate, haystack: $this->_Candidates);
    }

    #[PublicAPI]
    #[Description("Find candidate object by his string and return the candidate object.")]
    #[FunctionReturn("Candidate object")]
    public function getCandidateObjectFromName (string $candidateName) : ?Candidate
    {
        foreach ($this->_Candidates as $oneCandidate) :

            if ($oneCandidate->getName() === $candidateName) :
                return $oneCandidate;
            endif;
        endforeach;

        return null;
    }


/////////// ADD & REMOVE CANDIDATE ///////////

    // Add a vote candidate before voting
    #[PublicAPI]
    #[Description("Add one Candidate to an election.")]
    #[FunctionReturn("The new candidate object (your or automatic one). Throw an exception on error (existing candidate...).")]
    #[Example("Manual - Manage Candidate","https://github.com/julien-boudry/Condorcet/wiki/II-%23-A.-Create-an-Election-%23-2.-Create-Candidates")]
    #[Related("Election::parseCandidates", "Election::addCandidatesFromJson", "Election::removeCandidate", "Election::getCandidatesList", "Election::canAddCandidate")]
    public function addCandidate (Candidate|string|null $candidate = null) : Candidate
    {
        // only if the vote has not started
        if ( $this->_State > 1 ) :
            throw new CondorcetException(2);
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

    #[PublicAPI]
    #[Description("Check if a Candidate is alredeay register. User strict Vote object comparaison, but also string namming comparaison into the election.")]
    #[FunctionReturn("True if your Candidate is available. Or False.")]
    #[Related("Election::addCandidate")]
    public function canAddCandidate (Candidate|string $candidate) : bool
    {
        return !$this->isRegisteredCandidate($candidate, false);
    }

    // Destroy a register vote candidate before voting
    #[PublicAPI]
    #[Description("Remove Candidates from an election.\n\n*Please note: You can't remove candidates after the first vote. Exception will be throw.*")]
    #[FunctionReturn("List of removed CondorcetPHP\Condorcet\Candidate object.")]
    #[Example("Manual - Manage Candidate","https://github.com/julien-boudry/Condorcet/wiki/II-%23-A.-Create-an-Election-%23-2.-Create-Candidates")]
    #[Related("Election::addCandidate", "Election::getCandidatesList")]
    public function removeCandidates (mixed $candidates_input) : array
    {
        // only if the vote has not started
        if ( $this->_State > 1 ) :
            throw new CondorcetException(2);
        endif;

        if ( !\is_array($candidates_input) ) :
            $candidates_input = [$candidates_input];
        endif;

        foreach ($candidates_input as &$candidate) :
            $candidate_key = $this->getCandidateKey($candidate);

            if ( $candidate_key === null ) :
                throw new CondorcetException(4,$candidate->getName());
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

    #[PublicAPI]
    #[Description("Import candidate from a Json source.")]
    #[FunctionReturn("List of new registered candidate object.")]
    #[Example("Manual - Manage Candidates","https://github.com/julien-boudry/Condorcet/wiki/II-%23-A.-Create-an-Election-%23-2.-Create-Candidates")]
    #[Related("Election::addCandidate", "Election::parseCandidates", "Election::addVotesFromJson")]
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

    #[PublicAPI]
    #[Description("Import candidate from a text source.")]
    #[FunctionReturn("List of new registered candidate object. Count it for checking if all candidates have been correctly registered.")]
    #[Example("Manual - Manage Candidates","https://github.com/julien-boudry/Condorcet/wiki/II-%23-A.-Create-an-Election-%23-2.-Create-Candidates")]
    #[Related("Election::addCandidate", "Election::addCandidatesFromJson", "Election::parseVotes")]
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
            if (self::$_maxParseIteration !== null && \count($adding) >= self::$_maxParseIteration) :
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
