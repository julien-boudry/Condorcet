<?php declare(strict_types=1);
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

namespace CondorcetPHP\Condorcet\ElectionProcess;

use CondorcetPHP\Condorcet\Vote;
use CondorcetPHP\Condorcet\Throwable\{FileDoesNotExistException, ParseVotesMaxNumberReachedException, VoteException, VoteInvalidFormatException, VoteMaxNumberReachedException};
use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{Book, Throws};
use CondorcetPHP\Condorcet\DataManager\{VotesManager, VotesManagerEvent};
use CondorcetPHP\Condorcet\Utils\{CondorcetUtil, VoteEntryParser, VoteUtil};

/**
 * Manage Votes for an Election class.
 *
 * @mixin \CondorcetPHP\Condorcet\Election
 */
trait VotesProcess
{
    /////////// CONSTRUCTOR ///////////

    // Data and global options
    protected readonly VotesManager $Votes; // Votes list
    protected VotesFastMode $votesFastMode = VotesFastMode::NONE; // When parsing vote, avoid unnecessary checks


    /////////// VOTES LIST ///////////

    // How many votes are registered ?
    /**
     * Count the number of actual registered and valid votes for this election. This method ignores votes constraints, only valid votes will be counted.
     *
     * @api
     *
     * @see Election::getVotesList()
     * @see Election::countValidVoteWithConstraints()
     *
     * @param $tags Tag in string separated by commas, or an Array.
     * @param $with Count Votes with this tag or without this tag.
     *
     * @return int Number of valid and registered votes in this election.
     */
    public function countVotes(
        array|null|string $tags = null,
        bool|int $with = true
    ): int {
        return $this->Votes->countVotes(VoteUtil::tagsConvert($tags), $with);
    }

    /**
     * Count the number of actual invalid (if constraints functionality is enabled) but registered votes for this election.
     *
     * @api
     *
     * @return int Number of valid and registered votes in this election.
     *
     * @see Election::countValidVoteWithConstraints()
     * @see Election::countVotes()
     * @see Election::sumValidVoteWeightsWithConstraints()
     */
    public function countInvalidVoteWithConstraints(): int
    {
        return $this->Votes->countInvalidVoteWithConstraints();
    }

    /**
     * Count the number of actual registered and valid votes for this election. This method doesn't ignore votes constraints, only valid votes will be counted.
     *
     * @api
     *
     * @see Election::countInvalidVoteWithConstraints()
     * @see Election::countVotes()
     * @see Election::sumValidVoteWeightsWithConstraints()
     *
     * @param $tags Tag in string separated by commas, or an Array.
     * @param $with Count Votes with this tag or without this tag.
     *
     * @return int Number of valid and registered votes in this election.
     */
    public function countValidVoteWithConstraints(
        array|null|string $tags = null,
        bool|int $with = true
    ): int {
        return $this->Votes->countValidVotesWithConstraints(VoteUtil::tagsConvert($tags), $with);
    }

    // Sum votes weight
    /**
     * Sum total votes weight in this election. If vote weight functionality is disabled (default setting), it will return the number of registered votes. This method ignores votes constraints.
     *
     * @param $tags Tag in string separated by commas, or an Array.
     * @param $with Count Votes with this tag or without this tag.
     *
     * @return int (int) Total vote weight
     *
     * @api
     *
     * @see Election::sumValidVoteWeightsWithConstraints()
     */
    public function sumVoteWeights(
        array|null|string $tags = null,
        bool|int $with = true
    ): int {
        return $this->Votes->sumVoteWeights(VoteUtil::tagsConvert($tags), $with);
    }

    /**
     * Sum total votes weight in this election. If vote weight functionality is disabled (default setting), it will return the number of registered votes. This method doesn't ignore votes constraints, only valid votes will be counted.
     *
     * @param $tags Tag in string separated by commas, or an Array.
     * @param $with Count Votes with this tag or without this tag.
     *
     * @return int Total vote weight
     *
     * @api
     *
     * @see Election::countValidVoteWithConstraints()
     * @see Election::countInvalidVoteWithConstraints()
     */
    public function sumValidVoteWeightsWithConstraints(
        array|null|string $tags = null,
        bool|int $with = true
    ): int {
        return $this->Votes->sumVoteWeightsWithConstraints(VoteUtil::tagsConvert($tags), $with);
    }

    // Get the votes registered list
    /**
     * Get registered votes list.
     *
     * @api
     *
     * @see Election::countVotes()
     * @see Election::getVotesListAsString()
     *
     * @param $tags Tags list as a string separated by commas or array.
     * @param $with Get votes with these tags or without.
     *
     * @return array Populated by each Vote object.
     */
    public function getVotesList(
        array|null|string $tags = null,
        bool $with = true
    ): array {
        return $this->Votes->getVotesList(VoteUtil::tagsConvert($tags), $with);
    }

    /**
     * Get registered votes list as string.
     *
     * @api
     *
     * @see Election::parseVotes()
     *
     * @param $withContext Depending on the implicit ranking rule of the election, will complete or not the ranking. If $withContext is false, rankings are never adapted to the context.
     *
     * @return string Return a string like:
     *               A > B > C * 3
     *               A = B > C * 6
     */
    public function getVotesListAsString(
        bool $withContext = true
    ): string {
        return $this->Votes->getVotesListAsString($withContext);
    }

    public function getVotesManager(): VotesManager
    {
        return $this->Votes;
    }

    /**
     * Same as Election::getVotesList. But returns a PHP generator object.
     * Useful if you work on very large elections with an external DataHandler, because it will not use large amounts of memory.
     *
     * @api
     *
     * @see Election::getVotesList()
     *
     * @param $tags Tags list as a string separated by commas or array.
     * @param $with Get votes with these tags or without.
     *
     * @return \Generator Populated by each Vote object.
     */
    public function getVotesListGenerator(
        array|null|string $tags = null,
        bool $with = true
    ): \Generator {
        return $this->Votes->getVotesListGenerator(VoteUtil::tagsConvert($tags), $with);
    }

    /**
     * Same as Election::getVotesList, filter out vote invalid under constraint. But returns a PHP generator object.
     * Useful if you work on very large elections with an external DataHandler, because it will not use large amounts of memory.
     *
     * @api
     *
     * @see Election::getVotesListGenerator()
     * @see Election::getVotesList()
     *
     * @param $tags Tags list as a string separated by commas or array.
     * @param $with Get votes with these tags or without.
     *
     * @return \Generator Populated by each Vote object.
     */
    public function getVotesValidUnderConstraintGenerator(
        array|null|string $tags = null,
        bool $with = true
    ): \Generator {
        return $this->Votes->getVotesValidUnderConstraintGenerator($tags, $with);
    }

    /**
     * @internal
     */
    public function getVoteKey(Vote $vote): ?int
    {
        return $this->Votes->getVoteKey($vote);
    }


    /////////// ADD & REMOVE VOTE ///////////

    // Add a single vote. Array key is the rank, each candidate in a rank are separate by ',' It is not necessary to register the last rank.
    /**
     * Add a vote to an election.
     *
     * @api
     *
     * @book \CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\BookLibrary::Votes
     *
     * @see Election::parseVotes()
     * @see Election::addVotesFromJson()
     * @see Election::removeVote()
     * @see Election::getVotesList()
     *
     * @param $vote String or array representation. Or CondorcetPHP\Condorcet\Vote object. If you do not provide a Vote object yourself, a new one will be generated for you.
     * @param $tags String separated by commas or an array. Will add tags to the vote object for you. But you can also add them yourself to the Vote object.
     *
     * @throws VoteMaxNumberReachedException
     *
     * @return Vote The vote object.
     */
    public function addVote(
        array|string|Vote $vote,
        array|string|null $tags = null
    ): Vote {
        $vote = $this->normalizeVoteInput($vote, $tags);

        // Check Max Vote Count
        if (self::$maxVotePerElection !== null && $this->countVotes() >= self::$maxVotePerElection) {
            throw new VoteMaxNumberReachedException(self::$maxVotePerElection);
        }

        // Register vote
        return $this->registerVote($vote, $tags); // Return the vote object
    }

    /**
     * @internal
     */
    public function beginVoteUpdate(Vote $existVote): void
    {
        $this->Votes->UpdateAndResetComputing(key: $this->getVoteKey($existVote), type: VotesManagerEvent::VoteUpdateInProgress);
    }

    /**
     * @internal
     */
    public function finishUpdateVote(Vote $existVote): void
    {
        $this->Votes->UpdateAndResetComputing(key: $this->getVoteKey($existVote), type: VotesManagerEvent::FinishUpdateVote);

        if ($this->Votes->hasExternalHandler()) {
            $this->Votes[$this->getVoteKey($existVote)] = $existVote;
        }
    }

    /**
     * @internal
     */
    public function checkVoteCandidate(Vote $vote): bool
    {
        if ($this->votesFastMode === VotesFastMode::NONE) {
            $linkCount = $vote->countLinks();
            $linkCheck = ($linkCount === 0 || ($linkCount === 1 && $vote->haveLink($this)));

            foreach ($vote->getAllCandidates() as $candidate) {
                if (!$linkCheck && $candidate->provisionalState && !$this->hasCandidate(candidate: $candidate, strictMode: true) && $this->hasCandidate(candidate: $candidate, strictMode: false)) {
                    return false;
                }
            }
        }

        if ($this->votesFastMode !== VotesFastMode::BYPASS_RANKING_UPDATE) {
            $ranking = $vote->getRanking();

            $change = $this->convertRankingCandidates($ranking);

            if ($change) {
                $vote->setRanking(
                    $ranking,
                    (abs($vote->updatedAt - microtime(true)) > 0.5) ? ($vote->updatedAt + 0.001) : null
                );
            }
        }

        return true;
    }

    /**
     * @internal
     */
    public function convertRankingCandidates(array &$ranking): bool
    {
        $change = false;

        foreach ($ranking as &$choice) {
            foreach ($choice as &$candidate) {
                if (!$this->hasCandidate($candidate, true)) {
                    if ($candidate->provisionalState && $this->hasCandidate(candidate: $candidate, strictMode: false)) {
                        $candidate = $this->candidates[$this->getCandidateKey((string) $candidate)];
                        $change = true;
                    }
                }
            }
        }

        return $change;
    }

    // Write a new vote
    protected function registerVote(Vote $vote, array|string|null $tags): Vote
    {
        // Vote identifiant
        if ($tags !== null) {
            $vote->addTags($tags);
        }

        // Register
        $this->Votes[] = $vote;

        return $vote;
    }

    /**
     * Remove all Votes from an election.
     *
     * @api
     *
     * @return bool True on success.
     *
     * @book \CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\BookLibrary::Votes
     *
     * @see Election::addVote()
     * @see Election::removeVote()
     * @see Election::removeVotesByTags()
     */
    public function removeAllVotes(): true
    {
        foreach ($this->getVotesList() as $oneVote) {
            $this->removeVote($oneVote);
        }

        return true;
    }

    /**
     * Remove Votes from an election.
     *
     * @api
     *
     * @book \CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\BookLibrary::Votes
     *
     * @see Election::removeAllVotes()
     * @see Election::addVote()
     * @see Election::getVotesList()
     * @see Election::removeVotesByTags()
     *
     * @param $vote Vote object.
     *
     * @return bool True on success
     */
    public function removeVote(
        Vote $vote
    ): true {
        $key = $this->getVoteKey($vote);
        if ($key !== null) {
            $this->Votes->offsetUnset($key);

            return true;
        } else {
            throw new VoteException('Cannot remove vote not registered in this election');
        }
    }

    /**
     * Remove Vote from an election using tags.
     *
     * ```php
     * \$election->removeVotesByTags('Charlie') ; // Remove vote(s) with tag Charlie
     * \$election->removeVotesByTags('Charlie', false) ; // Remove votes without tag Charlie
     * \$election->removeVotesByTags('Charlie, Julien', false) ; // Remove votes without tag Charlie AND without tag Julien.
     * \$election->removeVotesByTags(array('Julien','Charlie')) ; // Remove votes with tag Charlie OR with tag Julien.
     * ```
     *
     * @api
     *
     * @book \CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\BookLibrary::VotesTags
     *
     * @see Election::addVote()
     * @see Election::getVotesList()
     * @see Election::removeVote()
     *
     * @param $tags Tags as string separated by commas or array.
     * @param $with Votes with these tags or without.
     *
     * @return array List of removed CondorcetPHP\Condorcet\Vote object.
     */
    public function removeVotesByTags(
        array|string $tags,
        bool $with = true
    ): array {
        $rem = [];

        // Prepare Tags
        $tags = VoteUtil::tagsConvert($tags);

        // Deleting
        foreach ($this->getVotesList($tags, $with) as $oneVote) {
            $rem[] = $oneVote;

            $this->removeVote($oneVote);
        }

        return $rem;
    }


    /////////// PARSE VOTE ///////////

    // Return the well formatted vote to use.
    /**
     * @throws VoteInvalidFormatException
     */
    protected function normalizeVoteInput(array|string|Vote $vote, array|string|null $tags = null): Vote
    {
        if (!($vote instanceof Vote)) {
            $vote = new Vote(ranking: $vote, tags: $tags, ownTimestamp: null, electionContext: $this);
        }

        // Check array format && Make checkVoteCandidate
        if (!$this->checkVoteCandidate($vote)) {
            throw new VoteInvalidFormatException;
        }

        return $vote;
    }

    /**
     * Import votes from a Json source.
     *
     * @api
     *
     * @book \CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\BookLibrary::Votes
     *
     * @see Election::addVote()
     * @see Election::parseVotes()
     * @see Election::addCandidatesFromJson()
     *
     * @param $input Json string input.
     *
     * @return int Count of newly registered votes.
     */
    public function addVotesFromJson(
        string $input
    ): int {
        $input = CondorcetUtil::prepareJson($input);

        $adding = [];
        $count = 0;

        foreach ($input as $record) {
            if (empty($record['vote'])) {
                continue;
            }

            $vote = $record['vote'];
            $tags = !isset($record['tag']) ? null : $record['tag'];
            $multiple = !isset($record['multi']) ? 1 : (int) $record['multi'];
            $weight = !isset($record['weight']) ? 1 : (int) $record['weight'];

            $this->aggregateVotesFromParse($count, $multiple, $adding, $vote, $tags, $weight);
        }

        $this->bulkAddVotes($adding);

        return $count;
    }

    /**
     * Import votes from a text source. If any invalid vote is found inside, nothing is registered.
     *
     * @api
     *
     * @book \CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\BookLibrary::Votes
     *
     * @see Election::addVote()
     * @see Election::parseCandidates()
     * @see Election::parseVotesSafe()
     * @see Election::addVotesFromJson()
     *
     * @param $input String or valid path to a text file.
     * @param $isFile If true, the input is evaluated as path to text file.
     *
     * @return int Count of the newly registered votes.
     */
    public function parseVotes(
        string $input,
        bool $isFile = false
    ): int {
        $input = CondorcetUtil::prepareParse($input, $isFile);

        $adding = [];
        $count = 0;

        foreach ($input as $line) {
            $voteParser = new VoteEntryParser($line);

            $this->aggregateVotesFromParse(
                count: $count,
                multiple: $voteParser->multiple,
                adding: $adding,
                vote: $voteParser->ranking,
                tags: $voteParser->tags,
                weight: $voteParser->weight,
            );
        }

        $this->bulkAddVotes($adding);

        return $count;
    }

    /**
     * Similar to parseVote method. But will ignore invalid lines. This method is also far less greedy in memory and should be preferred for very large file inputs. Best used in combination with an external data handler.
     *
     * @api
     *
     * @book \CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\BookLibrary::Votes
     *
     * @see Election::addVote()
     * @see Election::parseCandidates()
     * @see Election::parseVotes()
     * @see Election::addVotesFromJson()
     *
     * @param $input String, valid path to a text file or an object SplFileInfo or extending it like SplFileObject.
     * @param $isFile If true, the string input is evaluated as path to text file.
     * @param $callBack Callback function to execute after each valid line, before vote registration.
     *
     * @return int Number of invalid records in input (except empty lines). It's not an invalid votes count. Check Election::countVotes if you want to be sure.
     */
    public function parseVotesSafe(
        \SplFileInfo|string $input,
        bool $isFile = false,
        ?\Closure $callBack = null
    ): int {
        $parsedVotesCounter = 0;
        $fail_count = 0;
        $doCallBack = $callBack !== null;

        if (!$isFile && !($input instanceof \SplFileInfo)) {
            $file = new \SplTempFileObject(256 * 1024 * 1024);
            $file->fwrite($input);
        } elseif ($input instanceof \SplFileObject) {
            $file = $input;
        } else {
            $file = ($input instanceof \SplFileInfo) ? $input : new \SplFileInfo($input);

            if ($file->isFile() && $file->isReadable()) {
                $file = $file->openFile('r');
            } else {
                throw new FileDoesNotExistException('Specified input file does not exist. path: ' . $input);
            }
        }

        unset($input); // Memory Optimization
        $file->setFlags($file->getFlags() | \SplFileObject::SKIP_EMPTY);
        $file->rewind();

        $char = '';
        $record = '';

        while ($char !== false) {
            $char = $file->fgetc();

            if ($char === ';' || $char === "\n" || $char === '#' || $char === false) {
                if ($char === '#') {
                    $record .= $char;

                    while (($char = $file->fgetc()) !== false) {
                        if ($char === "\n") {
                            break;
                        } else {
                            $record .= $char;
                        }
                    }
                }

                try {
                    $parsedVote = new VoteEntryParser($record);

                    if ($parsedVote->ranking === null) {
                        $record = '';

                        continue;
                    }

                    $count = 0;
                    $adding = [];

                    $this->aggregateVotesFromParse(
                        count: $count,
                        adding: $adding,
                        vote: $parsedVote->ranking,
                        tags: $parsedVote->tags,
                        weight: $parsedVote->weight,
                        multiple: $parsedVote->multiple
                    );

                    $parsedVotesCounter += $count;

                    if ($doCallBack) {
                        $doCallBack = $callBack($parsedVotesCounter);
                    }

                    $this->bulkAddVotes($adding);
                } catch (VoteInvalidFormatException) {
                    ++$fail_count;
                } finally {
                    $record = '';
                }
            } else {
                $record .= $char;
            }
        }

        return $fail_count;
    }

    protected function aggregateVotesFromParse(int &$count, int $multiple, array &$adding, array|string|Vote $vote, null|array|string $tags, int $weight): void
    {
        $adding_predicted_count = $count + $multiple;

        if (self::$maxVotePerElection && self::$maxVotePerElection < ($this->countVotes() + $adding_predicted_count)) {
            throw new VoteMaxNumberReachedException(self::$maxParseIteration);
        }

        if (self::$maxParseIteration !== null && $adding_predicted_count > self::$maxParseIteration) {
            throw new ParseVotesMaxNumberReachedException(self::$maxParseIteration);
        }

        $newVote = new Vote(ranking: $vote, tags: $tags, electionContext: $this);
        $newVote->setWeight($weight);

        $adding[] = ['multiple' => $multiple, 'vote' => $newVote];

        $count += $multiple;
    }

    protected function bulkAddVotes(array $adding): void
    {
        $this->votesFastMode = VotesFastMode::BYPASS_CANDIDATES_CHECK;

        foreach ($adding as $oneLine) {
            $finalVoteModel = $this->addVote($oneLine['vote']);

            for ($i = 1; $i <= $oneLine['multiple']; $i++) {
                if ($i === 1) {
                    $this->votesFastMode = VotesFastMode::BYPASS_RANKING_UPDATE;
                } else {
                    $this->addVote(clone $finalVoteModel);
                }
            }
        }

        $this->votesFastMode = VotesFastMode::NONE;
    }
}
