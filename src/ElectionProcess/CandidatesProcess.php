<?php declare(strict_types=1);
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

namespace CondorcetPHP\Condorcet\ElectionProcess;

use CondorcetPHP\Condorcet\Candidate;
use CondorcetPHP\Condorcet\Throwable\{CandidateDoesNotExistException, CandidateExistsException, VoteMaxNumberReachedException, VotingHasStartedException};
use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{Book, Throws};
use CondorcetPHP\Condorcet\Utils\CondorcetUtil;

/**
 * Manage Candidates for an Election class
 */
trait CandidatesProcess
{
    /////////// CONSTRUCTOR ///////////

    // Data and global options
    public protected(set) array $candidates = []; // Candidate list
    public protected(set) string $nextAutomaticCandidateName = 'A';


    /////////// GET CANDIDATES ///////////

    // Count registered candidates
    /**
     * Count the number of registered candidates
     * @api
     * @return int Number of registered candidates for this election.
     * @see Election::getCandidatesList()
     */
    public function countCandidates(): int
    {
        return \count($this->candidates);
    }
    /**
     * Return a list of registered candidates for this election.
     * @api
     * @return array List of candidates in an array.
     * @see Election::countCandidates()
     */
    public function getCandidatesList(): array
    {
        return $this->candidates;
    }

    // Get the list of registered CANDIDATES
    /**
     * Return a list of registered candidates for this election as strings.
     * @api
     * @return array List of candidates in an array populated with strings instead of Candidate objects.
     * @see Election::countCandidates()
     */
    public function getCandidatesListAsString(): array
    {
        $result = [];

        foreach ($this->candidates as $candidateKey => &$oneCandidate) {
            $result[$candidateKey] = $oneCandidate->name;
        }

        return $result;
    }
    /**
     * @internal
     */
    public function getCandidateKey(Candidate|string $candidate): ?int
    {
        if ($candidate instanceof Candidate) {
            $r = array_search(needle: $candidate, haystack: $this->candidates, strict: true);
        } else {
            $r = array_search(needle: mb_trim($candidate), haystack: $this->candidates, strict: false);
        }

        return ($r !== false) ? $r : null; // @phpstan-ignore return.type
    }
    /**
     * @internal
     */
    public function getCandidateObjectFromKey(int $candidate_key): ?Candidate
    {
        return $this->candidates[$candidate_key] ?? null;
    }
    /**
     * Check if a candidate is already registered for this election.
     * @api
     * @see Election::addCandidate()
     * @param $candidate Candidate object or candidate name as a string. The candidate name as a string only works if the strict mode is disabled.
     * @param $strictMode Strict comparison mode. In strict mode, candidate objects are compared strictly and a string entry can match nothing. If strict mode is disabled, the comparison will be based on the name.
     */
    public function hasCandidate(
        Candidate|string $candidate,
        bool $strictMode = true
    ): bool {
        return $strictMode ? \in_array(needle: $candidate, haystack: $this->candidates, strict: true) : \in_array(needle: (string) $candidate, haystack: $this->candidates, strict: false);
    }
    /**
     * Find the candidate object from its name and return it.
     * @api
     * @param $candidateName Name of the candidate.
     * @return null|Candidate Candidate object
     */
    public function getCandidateObjectFromName(
        string $candidateName
    ): ?Candidate {
        foreach ($this->candidates as $oneCandidate) {
            if ($oneCandidate->name === $candidateName) {
                return $oneCandidate;
            }
        }

        return null;
    }


    /////////// ADD & REMOVE CANDIDATE ///////////

    // Add a vote candidate before voting
    /**
     * Add a candidate to an election.
     * @param $candidate Alphanumeric string or CondorcetPHP\Condorcet\Candidate object. The candidate name's white spaces will be removed. If null, this function will create a new candidate with an automatic name.
     * @throws CandidateExistsException
     * @throws VotingHasStartedException
     * @return Candidate The newly created candidate object (yours or automatically generated). Throws an exception in case of error (existing candidate...).
     * @api
     * @book \CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\BookLibrary::Candidates
     * @see Election::parseCandidates(), Election::addCandidatesFromJson(), Election::removeCandidates(), Election::getCandidatesList(), Election::canAddCandidate()
     */
    public function addCandidate(
        Candidate|string|null $candidate = null
    ): Candidate {
        // only if the vote has not started
        if ($this->state->value > ElectionState::CANDIDATES_REGISTRATION->value) {
            throw new VotingHasStartedException("cannot add '{$candidate}'");
        }

        // Process
        if (empty($candidate) && $candidate !== '0') {
            while (!$this->canAddCandidate($this->nextAutomaticCandidateName)) {
                $this->nextAutomaticCandidateName = str_increment($this->nextAutomaticCandidateName);
            }

            $newCandidate = new Candidate($this->nextAutomaticCandidateName);
        } else { // Try to add the candidate_id
            $newCandidate = ($candidate instanceof Candidate) ? $candidate : new Candidate($candidate);

            if (!$this->canAddCandidate($newCandidate)) {
                throw new CandidateExistsException((string) $candidate);
            }
        }

        // Register it
        $this->candidates[] = $newCandidate;

        // Linking
        $newCandidate->registerLink($this);

        // Disallow other candidate object name matching
        $newCandidate->setProvisionalState(false);

        return $newCandidate;
    }
    /**
     * Check if a candidate is already registered. Equivalent of `!$election->hasCandidate($candidate, false)`.
     * @api
     * @see Election::addCandidate(), Election::hasCandidate()
     * @param $candidate String or Condorcet/Vote object.
     * @return bool True if your candidate is available, false otherwise.
     */
    public function canAddCandidate(
        Candidate|string $candidate
    ): bool {
        return !$this->hasCandidate($candidate, false);
    }

    // Destroy a register vote candidate before voting
    /**
     * Remove candidates from an election.
     *
     * *Please note: You cannot remove candidates after the first vote. An exception will be thrown.*
     * @api
     * @book \CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\BookLibrary::Candidates
     * @see Election::addCandidate(), Election::getCandidatesList()
     * @param $candidates_input String corresponding to the candidate's name or CondorcetPHP\Condorcet\Candidate object. Array filled with CondorcetPHP\Condorcet\Candidate objects. Array filled with strings corresponding to the candidate's name.
     * @throws CandidateDoesNotExistException
     * @throws VotingHasStartedException
     * @return array List of removed candidate objects.
     */
    public function removeCandidates(
        array|Candidate|string $candidates_input
    ): array {
        // only if the vote has not started
        if ($this->state->value > ElectionState::CANDIDATES_REGISTRATION->value) {
            throw new VotingHasStartedException;
        }

        if (!\is_array($candidates_input)) {
            $candidates_input = [$candidates_input];
        }

        foreach ($candidates_input as &$candidate) {
            $candidate_key = $this->getCandidateKey($candidate);

            if ($candidate_key === null) {
                throw new CandidateDoesNotExistException($candidate->name);
            }

            $candidate = $candidate_key;
        }

        $rem = [];
        foreach ($candidates_input as $candidate_key) {
            $this->candidates[$candidate_key]->destroyLink($this);

            $rem[] = $this->candidates[$candidate_key];

            unset($this->candidates[$candidate_key]);
        }

        return $rem;
    }


    /////////// PARSE CANDIDATES ///////////
    /**
     * Import candidates from a JSON source.
     * @api
     * @book \CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\BookLibrary::Candidates
     * @see Election::addCandidate(), Election::parseCandidates(), Election::addVotesFromJson()
     * @param $input JSON string.
     * @throws CandidateExistsException
     * @return array List of newly registered candidate objects.
     */
    public function addCandidatesFromJson(
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
    /**
     * Import candidates from a text source.
     * @api
     * @book \CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\BookLibrary::Candidates
     * @see Election::addCandidate(), Election::addCandidatesFromJson(), Election::parseVotes()
     * @param $input String or valid path to a text file.
     * @param $isFile If true, the input is evaluated as a path to a text file.
     * @throws CandidateExistsException
     * @throws VoteMaxNumberReachedException
     * @return array List of newly registered candidate objects. Count to check if all candidates were correctly registered.
     */
    public function parseCandidates(
        string $input,
        bool $isFile = false
    ): array {
        $input = CondorcetUtil::prepareParse($input, $isFile);

        $adding = [];
        foreach ($input as $line) {
            // addCandidate
            if (self::$maxParseIteration !== null && \count($adding) >= self::$maxParseIteration) {
                throw new VoteMaxNumberReachedException(self::$maxParseIteration);
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
