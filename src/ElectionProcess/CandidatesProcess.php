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
use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\BookLibrary;
use CondorcetPHP\Condorcet\Throwable\{CandidateDoesNotExistException, CandidateExistsException, VoteMaxNumberReachedException, VotingHasStartedException};
use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{Book, Description, FunctionParameter, FunctionReturn, InternalModulesAPI, PublicAPI, Related, Throws};
use CondorcetPHP\Condorcet\Utils\CondorcetUtil;

// Manage Candidates for Election class
trait CandidatesProcess
{
    /////////// CONSTRUCTOR ///////////

    // Data and global options
    public protected(set) array $candidates = []; // Candidate list
    public protected(set) string $nextAutomaticCandidateName = 'A';


    /////////// GET CANDIDATES ///////////

    // Count registered candidates
    #[PublicAPI]
    #[Description('Count the number of registered candidates')]
    #[FunctionReturn('Number of registered candidates for this election.')]
    #[Related('Election::getCandidatesList')]
    public function countCandidates(): int
    {
        return \count($this->candidates);
    }

    #[PublicAPI]
    #[Description('Return a list of registered candidates for this election.')]
    #[FunctionReturn('List of candidates in an array.')]
    #[Related('Election::countCandidates')]
    public function getCandidatesList(): array
    {
        return $this->candidates;
    }

    // Get the list of registered CANDIDATES
    #[PublicAPI]
    #[Description('Return a list of registered candidates for this election.')]
    #[FunctionReturn('List of candidates in an array populated with strings instead of CandidateObjects.')]
    #[Related('Election::countCandidates')]
    public function getCandidatesListAsString(): array
    {
        $result = [];

        foreach ($this->candidates as $candidateKey => &$oneCandidate) {
            $result[$candidateKey] = $oneCandidate->name;
        }

        return $result;
    }

    #[InternalModulesAPI]
    public function getCandidateKey(Candidate|string $candidate): ?int
    {
        if ($candidate instanceof Candidate) {
            $r = array_search(needle: $candidate, haystack: $this->candidates, strict: true);
        } else {
            $r = array_search(needle: mb_trim((string) $candidate), haystack: $this->candidates, strict: false);
        }

        return ($r !== false) ? $r : null;
    }

    #[InternalModulesAPI]
    public function getCandidateObjectFromKey(int $candidate_key): ?Candidate
    {
        return $this->candidates[$candidate_key] ?? null;
    }

    #[PublicAPI]
    #[Description('Check if a candidate is already taking part in the election.')]
    #[FunctionReturn('True / False')]
    #[Related('Election::addCandidate')]
    public function isRegisteredCandidate(
        #[FunctionParameter('Candidate object or candidate string name. String name works only if the strict mode is false')]
        Candidate|string $candidate,
        #[FunctionParameter('Search comparison mode. In strict mode, candidate objects are compared strictly and a string input cannot match anything. If strict mode is false, the comparison will be based on name')]
        bool $strictMode = true
    ): bool {
        return $strictMode ? \in_array(needle: $candidate, haystack: $this->candidates, strict: true) : \in_array(needle: (string) $candidate, haystack: $this->candidates, strict: false);
    }

    #[PublicAPI]
    #[Description('Find candidate object by string and return the candidate object.')]
    #[FunctionReturn('Candidate object')]
    public function getCandidateObjectFromName(
        #[FunctionParameter('Candidate name')]
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
    #[PublicAPI]
    #[Description('Add one candidate to an election.')]
    #[FunctionReturn('The new candidate object (your or automatic one). Throws an exception on error (existing candidate...).')]
    #[Throws(CandidateExistsException::class, VotingHasStartedException::class)]
    #[Book(\CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\BookLibrary::Candidates)]
    #[Related('Election::parseCandidates', 'Election::addCandidatesFromJson', 'Election::removeCandidate', 'Election::getCandidatesList', 'Election::canAddCandidate')]
    public function addCandidate(
        #[FunctionParameter('Alphanumeric string or CondorcetPHP\Condorcet\Candidate object. The whitespace of your candidate name will be trimmed. If null, this function will create a new candidate with an automatic name.')]
        Candidate|string|null $candidate = null
    ): Candidate {
        // only if the vote has not started
        if ($this->state->value > ElectionState::CANDIDATES_REGISTRATION->value) {
            throw new VotingHasStartedException("cannot add '{$candidate}'");
        }

        // Process
        if (empty($candidate) && $candidate !== '0') {
            while (!$this->canAddCandidate($this->nextAutomaticCandidateName)) {
                $this->nextAutomaticCandidateName++;
            }

            $newCandidate = new Candidate($this->nextAutomaticCandidateName);
        } else { // Try to add the candidate_id
            $newCandidate = ($candidate instanceof Candidate) ? $candidate : new Candidate((string) $candidate);

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

    #[PublicAPI]
    #[Description('Check if a candidate is already registered. Equivalent of `!$election->isRegisteredCandidate($candidate, false)`.')]
    #[FunctionReturn('True if your candidate is available, false otherwise.')]
    #[Related('Election::addCandidate', 'Election::isRegisteredCandidate')]
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
    #[Book(\CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\BookLibrary::Candidates)]
    #[Related('Election::addCandidate', 'Election::getCandidatesList')]
    public function removeCandidates(
        #[FunctionParameter('String matching candidate name CondorcetPHP\Condorcet\Candidate object. Array populated by CondorcetPHP\Condorcet\Candidate\. Array populated by string matching candidate name')]
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

    #[PublicAPI]
    #[Description('Import candidate from a JSON source.')]
    #[FunctionReturn('List of newly registered candidate object.')]
    #[Throws(CandidateExistsException::class)]
    #[Book(\CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\BookLibrary::Candidates)]
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
    #[Book(\CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\BookLibrary::Candidates)]
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
