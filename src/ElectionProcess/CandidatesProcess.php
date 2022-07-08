<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\ElectionProcess;

use CondorcetPHP\Condorcet\{Candidate, CondorcetUtil};
use CondorcetPHP\Condorcet\Throwable\{CandidateDoesNotExistException, CandidateExistsException, VoteMaxNumberReachedException, VotingHasStartedException};
use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{Description, Example, FunctionParameter, FunctionReturn, InternalModulesAPI, PublicAPI, Related, Throws};

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
    #[Description('Count the number of registered candidates')]
    #[FunctionReturn('Number of registered candidates for this election.')]
    #[Related('Election::getCandidatesList')]
    public function countCandidates(): int
    {
        return \count($this->_Candidates);
    }

    #[PublicAPI]
    #[Description('Return a list of registered candidates for this election.')]
    #[FunctionReturn('List of candidates in an array.')]
    #[Related('Election::countCandidates')]
    public function getCandidatesList(): array
    {
        return $this->_Candidates;
    }

    // Get the list of registered CANDIDATES
    #[PublicAPI]
    #[Description('Return a list of registered candidates for this election.')]
    #[FunctionReturn('List of candidates in an array populated with strings instead of CandidateObjects.')]
    #[Related('Election::countCandidates')]
    public function getCandidatesListAsString(): array
    {
        $result = [];

        foreach ($this->_Candidates as $candidateKey => &$oneCandidate) {
            $result[$candidateKey] = $oneCandidate->getName();
        }

        return $result;
    }

    #[InternalModulesAPI]
    public function getCandidateKey(Candidate|string $candidate): ?int
    {
        if ($candidate instanceof Candidate) {
            $r = array_search(needle: $candidate, haystack: $this->_Candidates, strict: true);
        } else {
            $r = array_search(needle: trim((string) $candidate), haystack: $this->_Candidates, strict: false);
        }

        return ($r !== false) ? $r : null;
    }

    #[InternalModulesAPI]
    public function getCandidateObjectFromKey(int $candidate_key): ?Candidate
    {
        return $this->_Candidates[$candidate_key] ?? null;
    }

    #[PublicAPI]
    #[Description('Check if a candidate is already taking part in the election.')]
    #[FunctionReturn('True / False')]
    #[Related('Election::addCandidate')]
    public function isRegisteredCandidate(
        #[FunctionParameter('Candidate object or candidate string name. String name works only if the strict mode is active')]
        Candidate|string $candidate,
        #[FunctionParameter("Search comparison mode. In strict mode, candidate objects are compared strictly and a string input can't match anything.\nIf strict mode is false, the comparison will be based on name")]
        bool $strictMode = true
    ): bool {
        return $strictMode ? \in_array(needle: $candidate, haystack: $this->_Candidates, strict: true) : \in_array(needle: (string) $candidate, haystack: $this->_Candidates, strict: false);
    }

    #[PublicAPI]
    #[Description('Find candidate object by string and return the candidate object.')]
    #[FunctionReturn('Candidate object')]
    public function getCandidateObjectFromName(
        #[FunctionParameter('Candidate name')]
        string $candidateName
    ): ?Candidate {
        foreach ($this->_Candidates as $oneCandidate) {
            if ($oneCandidate->getName() === $candidateName) {
                return $oneCandidate;
            }
        }

        return null;
    }


    /////////// ADD & REMOVE CANDIDATE ///////////

    // Add a vote candidate before voting
    #[PublicAPI]
    #[Description('Add one candidate to an election.')]
    #[FunctionReturn('The new candidate object (your or automatic one). Throws an exception on error (existing candidate...).')]
    #[Throws(CandidateExistsException::class, VotingHasStartedException::class)]
    #[Example('Manual - Manage Candidate', 'https://github.com/julien-boudry/Condorcet/wiki/II-%23-A.-Create-an-Election-%23-2.-Create-Candidates')]
    #[Related('Election::parseCandidates', 'Election::addCandidatesFromJson', 'Election::removeCandidate', 'Election::getCandidatesList', 'Election::canAddCandidate')]
    public function addCandidate(
        #[FunctionParameter('Alphanumeric string or CondorcetPHP\Condorcet\Candidate object. The whitespace of your candidate name will be trimmed. If null, this function will create a new candidate with an automatic name.')]
        Candidate|string|null $candidate = null
    ): Candidate {
        // only if the vote has not started
        if ($this->_State->value > ElectionState::CANDIDATES_REGISTRATION->value) {
            throw new VotingHasStartedException("cannot add '{$candidate}'");
        }

        // Process
        if (empty($candidate) && $candidate !== '0') {
            while (!$this->canAddCandidate($this->_AutomaticNewCandidateName)) {
                $this->_AutomaticNewCandidateName++;
            }

            $newCandidate = new Candidate($this->_AutomaticNewCandidateName);
        } else { // Try to add the candidate_id
            $newCandidate = ($candidate instanceof Candidate) ? $candidate : new Candidate((string) $candidate);

            if (!$this->canAddCandidate($newCandidate)) {
                throw new CandidateExistsException((string) $candidate);
            }
        }

        // Register it
        $this->_Candidates[] = $newCandidate;

        // Linking
        $newCandidate->registerLink($this);

        // Disallow other candidate object name matching
        $newCandidate->setProvisionalState(false);

        return $newCandidate;
    }

    #[PublicAPI]
    #[Description('Check if a candidate is already registered. Uses strict Vote object comparison, but also string naming comparison in the election.')]
    #[FunctionReturn('True if your candidate is available, false otherwise.')]
    #[Related('Election::addCandidate')]
    public function canAddCandidate(
        #[FunctionParameter('String or Condorcet/Vote object')]
        Candidate|string $candidate
    ): bool {
        return !$this->isRegisteredCandidate($candidate, false);
    }

    // Destroy a register vote candidate before voting
    #[PublicAPI]
    #[Description("Remove candidates from an election.\n\n*Please note: You can't remove candidates after the first vote. An exception will be thrown.*")]
    #[FunctionReturn("List of removed CondorcetPHP\Condorcet\Candidate object.")]
    #[Throws(CandidateDoesNotExistException::class, VotingHasStartedException::class)]
    #[Example('Manual - Manage Candidate', 'https://github.com/julien-boudry/Condorcet/wiki/II-%23-A.-Create-an-Election-%23-2.-Create-Candidates')]
    #[Related('Election::addCandidate', 'Election::getCandidatesList')]
    public function removeCandidates(
        #[FunctionParameter("* String matching candidate name\n* CondorcetPHP\Condorcet\Candidate object\n* Array populated by CondorcetPHP\Condorcet\Candidate\n* Array populated by string matching candidate name")]
        array|Candidate|string $candidates_input
    ): array {
        // only if the vote has not started
        if ($this->_State->value > ElectionState::CANDIDATES_REGISTRATION->value) {
            throw new VotingHasStartedException;
        }

        if (!\is_array($candidates_input)) {
            $candidates_input = [$candidates_input];
        }

        foreach ($candidates_input as &$candidate) {
            $candidate_key = $this->getCandidateKey($candidate);

            if ($candidate_key === null) {
                throw new CandidateDoesNotExistException($candidate->getName());
            }

            $candidate = $candidate_key;
        }

        $rem = [];
        foreach ($candidates_input as $candidate_key) {
            $this->_Candidates[$candidate_key]->destroyLink($this);

            $rem[] = $this->_Candidates[$candidate_key];

            unset($this->_Candidates[$candidate_key]);
        }

        return $rem;
    }


    /////////// PARSE CANDIDATES ///////////

    #[PublicAPI]
    #[Description('Import candidate from a JSON source.')]
    #[FunctionReturn('List of newly registered candidate object.')]
    #[Throws(CandidateExistsException::class)]
    #[Example('Manual - Manage Candidates', 'https://github.com/julien-boudry/Condorcet/wiki/II-%23-A.-Create-an-Election-%23-2.-Create-Candidates')]
    #[Related('Election::addCandidate', 'Election::parseCandidates', 'Election::addVotesFromJson')]
    public function addCandidatesFromJson(
        #[FunctionParameter('JSON string input')]
        string $input
    ): array {
        $input = CondorcetUtil::prepareJson($input);

        // -------

        $adding = [];
        foreach ($input as $candidate) {
            $candidate = new Candidate($candidate);

            if (!$this->canAddCandidate($candidate)) {
                throw new CandidateExistsException((string) $candidate);
            }

            $adding[] = $candidate;
        }

        // Add Candidates
        foreach ($adding as $oneCandidate) {
            $this->addCandidate($oneCandidate);
        }

        return $adding;
    }

    #[PublicAPI]
    #[Description('Import candidate from a text source.')]
    #[FunctionReturn('List of newly registered candidate object. Count it for checking if all candidates have been correctly registered.')]
    #[Throws(CandidateExistsException::class, VoteMaxNumberReachedException::class)]
    #[Example('Manual - Manage Candidates', 'https://github.com/julien-boudry/Condorcet/wiki/II-%23-A.-Create-an-Election-%23-2.-Create-Candidates')]
    #[Related('Election::addCandidate', 'Election::addCandidatesFromJson', 'Election::parseVotes')]
    public function parseCandidates(
        #[FunctionParameter('String or valid path to a text file')]
        string $input,
        #[FunctionParameter('If true, the input is evaluated as path to a text file')]
        bool $isFile = false
    ): array {
        $input = CondorcetUtil::prepareParse($input, $isFile);

        $adding = [];
        foreach ($input as $line) {
            // addCandidate
            if (self::$_maxParseIteration !== null && \count($adding) >= self::$_maxParseIteration) {
                throw new VoteMaxNumberReachedException(self::$_maxParseIteration);
            }

            if (!$this->canAddCandidate($line)) {
                throw new CandidateExistsException($line);
            }

            $adding[] = $line;
        }

        foreach ($adding as $oneNewCandidate) {
            $this->addCandidate($oneNewCandidate);
        }

        return $adding;
    }
}
