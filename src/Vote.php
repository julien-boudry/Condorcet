<?php

/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

declare(strict_types=1);

namespace CondorcetPHP\Condorcet;

use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\BookLibrary;
use CondorcetPHP\Condorcet\Throwable\{CandidateDoesNotExistException, VoteException, VoteInvalidFormatException, VoteNotLinkedException};
use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{Book, Description, FunctionParameter, FunctionReturn, InternalModulesAPI, PublicAPI, Related, Throws};
use CondorcetPHP\Condorcet\Relations\Linkable;
use CondorcetPHP\Condorcet\Utils\{CondorcetUtil, VoteEntryParser, VoteUtil};
use Deprecated;

/**
 * @implements \ArrayAccess<int,array<Candidate>>
 * @implements \Iterator<int,array<Candidate>>
 */
class Vote implements \Iterator, \Stringable, \ArrayAccess
{
    use Linkable;
    use CondorcetVersion;

    // Implement ArrayAccess
/**
 * @throws VoteException
 */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new VoteException('Modifying a Vote as a table is not yet supported.');
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->ranking[$offset]);
    }
/**
 * @throws VoteException
 */
    public function offsetUnset(mixed $offset): void
    {
        throw new VoteException('Modifying a Vote as a table is not yet supported.');
    }

    public function offsetGet(mixed $offset): array|Candidate|null
    {
        return $this->ranking[$offset] ?? null;
    }

    // Implement Iterator

    private int $position = 1;

    public function rewind(): void
    {
        $this->position = 1;
    }

    public function current(): array
    {
        return $this->getRanking()[$this->position];
    }

    public function key(): int
    {
        return $this->position;
    }

    public function next(): void
    {
        ++$this->position;
    }

    public function valid(): bool
    {
        return isset($this->getRanking()[$this->position]);
    }

    // Vote

    /** @var array<int,array<Candidate>> */
    private array $ranking;

    private float $lastTimestamp;

    /**
     * @api
     */
    public private(set) int $candidatesCount;

    private array $ranking_history = [];

    private int $weight = 1;

    /**
     * Get the registered tags for this Vote.
     * @api
     * @return mixed List of registered tag.
     * @see Vote::getTagsAsString, Vote::addTags, Vote::removeTags
     */
    public private(set) array $tags = [];

    public private(set) string $hash = '';

    private ?Election $electionContext = null;

    public bool $notUpdate = false;


    // Performance (for internal use)
    protected static ?\stdClass $cacheKey = null;

    /** @var \WeakMap<\stdClass, array> */
    protected \WeakMap $cacheMap;

    public static function initCache(): \stdClass
    {
        self::$cacheKey = new \stdClass;
        return self::$cacheKey;
    }

    public static function clearCache(): void
    {
        self::$cacheKey = null;
    }

    // -------
/**
 * Build a vote object.
 * @api
 * @throws VoteInvalidFormatException
 * @book \CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\BookLibrary::Votes
 * @see Vote::setRanking, Vote::addTags
 * @param $ranking Equivalent to Vote::setRanking method.
 * @param $tags Equivalent to Vote::addTags method.
 * @param $ownTimestamp Set your own timestamp metadata for this Ranking.
 * @param $electionContext Try to convert directly your candidates from string input to Candidate object of one election.
 */
    public function __construct(

        array|string $ranking,

        array|string|null $tags = null,

        ?float $ownTimestamp = null,

        ?Election $electionContext = null
    ) {
        $this->cacheMap = new \WeakMap;

        $this->electionContext = $electionContext;
        $tagsFromString = null;

        // Vote Weight
        if (\is_string($ranking)) {
            $parsedVote = new VoteEntryParser($ranking);

            if ($parsedVote->weight > 1) {
                $weight = $parsedVote->weight;
            }

            $tagsFromString = $parsedVote->tags;

            $ranking = $parsedVote->ranking ?? [];
        }

        $this->setRanking($ranking, $ownTimestamp);
        if ($tags !== null) {
            $this->addTags($tags);
        }
        if ($tagsFromString !== null) {
            $this->addTags($tagsFromString);
        }

        if (isset($weight)) {
            $this->setWeight($weight);
        }

        $this->electionContext = null;
    }

    public function __serialize(): array
    {
        $this->position = 1;
        $this->link = null;

        $var = get_object_vars($this);
        unset($var['cacheMap']);

        return $var;
    }

    public function __wakeup(): void
    {
        $this->cacheMap = new \WeakMap;
    }

    public function __clone(): void
    {
        $this->destroyAllLink();
        $this->computeHashCode();
    }

    public function __toString(): string
    {
        if (empty($this->tags)) {
            return $this->getSimpleRanking();
        } else {
            return $this->getTagsAsString() . ' || ' . $this->getSimpleRanking();
        }
    }
/**
 * Get Object hash (cryptographic)
 * @api
 * @return mixed SHA hash code.
 * @see Vote::getWeight
 */
    public function getHashCode(): string
    {
        return $this->hash;
    }

    // -------

    // GETTERS
/**
 * Get the actual Ranking of this Vote.
 * @api
 * @return mixed Multidimenssionnal array populated by Candidate object.
 * @see Vote::setRanking
 * @param $sortCandidatesInRank Sort Candidate in a Rank by name. Useful for performant internal calls from methods.
 */
    public function getRanking(

        bool $sortCandidatesInRank = true
    ): array {
        $r = $this->ranking;

        if ($sortCandidatesInRank) {
            foreach ($r as &$oneRank) {
                sort($oneRank, \SORT_STRING);
            }
        }

        return $r;
    }
/**
 * Return an history of each vote change, with timestamp.
 * @api
 * @return mixed An explicit multi-dimenssional array.
 * @see Vote::getCreateTimestamp
 */
    public function getHistory(): array
    {
        return $this->ranking_history;
    }
/**
 * Get the registered tags for this Vote.
 * @api
 * @return mixed List of registered tag.
 * @see Vote::getTagsAsString, Vote::addTags, Vote::removeTags
 */
#[Deprecated]
    public function getTags(): array
    {
        return $this->tags;
    }
/**
 * Get the registered tags for this Vote.
 * @api
 * @return mixed List of registered tag as string separated by commas.
 * @see Vote::getTags, Vote::addTags, Vote::removeTags
 */
    public function getTagsAsString(): string
    {
        return implode(',', $this->tags);
    }
/**
 * Get the timestamp corresponding of the creation of this vote.
 * @api
 * @return mixed Timestamp
 * @see Candidate::getTimestamp
 */
    public function getCreateTimestamp(): float
    {
        return $this->ranking_history[0]['timestamp'];
    }
/**
 * Get the timestamp corresponding of the last vote change.
 * @api
 * @return mixed Timestamp
 * @see Vote::getCreateTimestamp
 */
    public function getTimestamp(): float
    {
        return $this->lastTimestamp;
    }
/**
 * Count the number of candidate provide into the active Ranking set.
 * @api
 * @return mixed Number of Candidate into ranking.
 */
    public function countRankingCandidates(): int
    {
        return $this->candidatesCount;
    }
/**
 * Count the number of ranks.
 * @api
 * @return mixed Number of ranks.
 */
    public function countRanks(): int
    {
        return \count($this->ranking);
    }
/**
 * Get all the candidates object set in the last ranking of this Vote.
 * @api
 * @return mixed Candidates list.
 * @see Vote::getRanking, Vote::countRankingCandidates
 * @param $context An election already linked to the Vote.
 */
    public function getAllCandidates(

        ?Election $context = null
    ): array {
        $ranking = ($context !== null) ? $this->getContextualRanking($context) : $this->getRanking(false);
        $list = [];

        foreach ($ranking as $rank) {
            foreach ($rank as $oneCandidate) {
                $list[] = $oneCandidate;
            }
        }

        return $list;
    }
/**
 * Return the vote actual ranking complete for the contexte of the provide election. Election must be linked to the Vote object.
 * @api
 * @return mixed Contextual full ranking.
 * @throws VoteNotLinkedException
 * @see Vote::getContextualRankingAsString, Vote::getRanking
 * @param $election An election already linked to the Vote.
 */
    public function getContextualRanking(

        Election $election,
    ): array {
        return $this->computeContextualRanking($election, true);
    }

    // Performances
/**
 * @internal
 * @param $election An election already linked to the Vote.
 */
    public function getContextualRankingWithoutSort(

        Election $election,
    ): array {
        return $this->computeContextualRanking($election, false);
    }
/**
 * @internal
 * @param $election An election already linked to the Vote.
 */
    public function getContextualRankingWithCandidateKeys(

        Election $election,
    ): array {
        $ranking = $this->getContextualRankingWithoutSort($election);

        VoteUtil::convertRankingFromCandidateObjectToInternalKeys($election, $ranking);

        return $ranking;
    }

    protected function computeContextualRanking(

        Election $election,

        bool $sortLastRankByName
    ): array {
        // Cache for internal use
        if (self::$cacheKey !== null && !$sortLastRankByName && $this->cacheMap->offsetExists(self::$cacheKey)) {
            return $this->cacheMap->offsetGet(self::$cacheKey);
        }

        // Normal procedure
        if (!$this->haveLink($election)) {
            throw new VoteNotLinkedException;
        }

        $countContextualCandidate = 0;

        $present = $this->getAllCandidates();
        $candidates_list = $election->getCandidatesList();
        $candidates_count = $election->countCandidates();

        $newRanking = $this->computeContextualRankingWithoutImplicit($this->getRanking(false), $election, $countContextualCandidate);

        if ($election->getImplicitRankingRule() && $countContextualCandidate < $candidates_count) {
            $last_rank = [];
            $needed = $candidates_count - $countContextualCandidate;

            foreach ($candidates_list as $oneCandidate) {
                if (!\in_array(needle: $oneCandidate, haystack: $present, strict: true)) {
                    $last_rank[] = $oneCandidate;
                }

                if (\count($last_rank) === $needed) {
                    break;
                }
            }

            if ($sortLastRankByName) {
                sort($last_rank, \SORT_STRING);
            }

            $newRanking[\count($newRanking) + 1] = $last_rank;
        }

        // Cache for internal use
        if (self::$cacheKey !== null && !$sortLastRankByName) {
            $this->cacheMap->offsetSet(self::$cacheKey, $newRanking);
        }

        return $newRanking;
    }

    protected function computeContextualRankingWithoutImplicit(array $ranking, Election $election, int &$countContextualCandidate = 0): array
    {
        $newRanking = [];
        $nextRank = 1;
        $rankChange = false;

        foreach ($ranking as $CandidatesInRanks) {
            foreach ($CandidatesInRanks as $candidate) {
                if ($election->isRegisteredCandidate($candidate, true)) {
                    $newRanking[$nextRank][] = $candidate;
                    $countContextualCandidate++;
                    $rankChange = true;
                }
            }

            if ($rankChange) {
                $nextRank++;
                $rankChange = false;
            }
        }

        return $newRanking;
    }
/**
 * Return the vote actual ranking complete for the contexte of the provide election. Election must be linked to the Vote object.
 * @api
 * @return mixed Contextual full ranking, with string instead Candidate object.
 * @see Vote::getContextualRanking, Vote::getRanking
 * @param $election An election already linked to the Vote.
 */
    public function getContextualRankingAsString(

        Election $election
    ): array {
        return CondorcetUtil::format($this->getContextualRanking($election), true);
    }
/**
 * Get the current ranking as a string format. Optionally with an election context, see Election::getContextualRanking()
 * @api
 * @return mixed String like 'A>D=C>B
 * @see Vote::getRanking
 * @param $context An election already linked to the Vote.
 * @param $displayWeight Include or not the weight symbol and value.
 */
    public function getSimpleRanking(

        ?Election $context = null,

        bool $displayWeight = true
    ): string {
        $ranking = $context !== null ? $this->getContextualRanking($context) : $this->getRanking();

        $simpleRanking = VoteUtil::getRankingAsString($ranking);

        if ($displayWeight && $this->weight > 1 && (($context && $context->isVoteWeightAllowed()) || $context === null)) {
            $simpleRanking .= ' ^' . $this->getWeight();
        }

        return $simpleRanking;
    }


    // SETTERS
/**
 * Set a new ranking for this vote.
 *
 * Note that if your vote is already linked to one ore more elections, your ranking must be compliant with all of them, else an exception is throw. For do this, you need to use only valid Candidate object, you can't register a new ranking from string if your vote is already linked to an election.
 * @api
 * @throws VoteInvalidFormatException
 * @book \CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\BookLibrary::Votes
 * @see Vote::getRanking, Vote::getHistory, Vote::__construct
 * @param $ranking A Ranking. Have a look at the Wiki https://github.com/julien-boudry/Condorcet/wiki/II-%23-B.-Vote-management-%23-1.-Add-Vote to learn the available ranking formats.
 * @param $ownTimestamp Set your own timestamp metadata on Ranking. Your timestamp must be > than last registered timestamp. Else, an exception will be throw.
 */
    public function setRanking(

        array|string $ranking,

        ?float $ownTimestamp = null
    ): static {
        // Timestamp
        if ($ownTimestamp !== null) {
            if (!empty($this->ranking_history) && $this->getTimestamp() >= $ownTimestamp) {
                throw new VoteInvalidFormatException('Timestamp format of vote is not correct');
            }
        }

        // Ranking
        $ranking = $this->formatRanking($ranking);

        if ($this->electionContext !== null) {
            $this->electionContext->convertRankingCandidates($ranking);
        }

        if (!$this->notUpdate) {
            foreach ($this->getLinks() as $link) {
                $link->beginVoteUpdate($this);
            }
        }

        $this->ranking = $ranking;
        $this->lastTimestamp = $ownTimestamp ?? microtime(true);

        $this->archiveRanking();

        if (\count($this->link) > 0) {
            try {
                foreach ($this->getLinks() as $link) {
                    if (!$link->checkVoteCandidate($this)) {
                        throw new VoteInvalidFormatException('vote does not match candidate in this election');
                    }
                }
            } catch (VoteInvalidFormatException $e) {
                foreach ($this->getLinks() as $link) {
                    $link->setStateToVote();
                }

                throw $e;
            }

            if (!$this->notUpdate) {
                foreach ($this->getLinks() as $link) {
                    $link->finishUpdateVote($this);
                }
            }
        }

        $this->computeHashCode();

        return $this;
    }

    private function formatRanking(array|string $ranking): array
    {
        if (\is_string($ranking)) {
            $ranking = new VoteEntryParser($ranking)->ranking ?? [];
        }

        $ranking = array_filter($ranking, static fn($key): bool => is_numeric($key), \ARRAY_FILTER_USE_KEY);

        ksort($ranking);

        $i = 1;
        $vote_r = [];
        foreach ($ranking as &$value) {
            $vote_r[$i] = !\is_array($value) ? [$value] : $value;

            $i++;
        }

        $ranking = $vote_r;

        $candidatesCount = 0;
        $list_candidate = [];
        foreach ($ranking as &$line) {
            foreach ($line as &$Candidate) {
                if (!($Candidate instanceof Candidate)) {
                    $Candidate = new Candidate($Candidate);
                    $Candidate->setProvisionalState(true);
                }

                $candidatesCount++;

                // Check Duplicate

                // Check objet reference AND check candidates name
                if (!\in_array($name = $Candidate->name, $list_candidate, true)) {
                    $list_candidate[] = $name;
                } else {
                    throw new VoteInvalidFormatException;
                }
            }
        }

        $this->candidatesCount = $candidatesCount;

        return $ranking;
    }
/**
 * Remove candidate from ranking. Set a new ranking and archive the old ranking.
 * @api
 * @return mixed True on success.
 * @throws CandidateDoesNotExistException
 * @see Vote::setRanking
 * @param $candidate Candidate object or string.
 */
    public function removeCandidate(

        Candidate|string $candidate
    ): true {
        $strict = $candidate instanceof Candidate;

        $ranking = $this->getRanking();

        $rankingCandidate = $this->getAllCandidates();

        if (!\in_array(needle: $candidate, haystack: $rankingCandidate, strict: $strict)) {
            throw new CandidateDoesNotExistException((string) $candidate);
        }

        foreach ($ranking as $rankingKey => &$rank) {
            foreach ($rank as $oneRankKey => $oneRankValue) {
                if ($strict ? $oneRankValue === $candidate : $oneRankValue == $candidate) {
                    unset($rank[$oneRankKey]);
                }
            }

            if (empty($rank)) {
                unset($ranking[$rankingKey]);
            }
        }

        $this->setRanking($ranking);

        return true;
    }
/**
 * Add tag(s) on this Vote.
 * @api
 * @return mixed In case of success, return TRUE
 * @throws VoteInvalidFormatException
 * @book \CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\BookLibrary::VotesTags
 * @see Vote::removeTags
 * @param $tags Tag(s) are non-numeric alphanumeric string. They can be added by string separated by commas or an array. Tags will be trimmed.
 */
    public function addTags(

        array|string $tags
    ): bool {
        $tags = VoteUtil::tagsConvert($tags) ?? [];

        foreach ($tags as $key => $tag) {
            if (\in_array(needle: $tag, haystack: $this->tags, strict: true)) {
                unset($tags[$key]);
            }
        }

        foreach ($tags as $tag) {
            $this->tags[] = $tag;
        }

        $this->computeHashCode();

        return true;
    }
/**
 * Remove registered tag(s) on this Vote.
 * @api
 * @return mixed List of deleted tags.
 * @book \CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\BookLibrary::VotesTags
 * @see Vote::addTags
 * @param $tags They can be added by string separated by commas or an array.
 */
    public function removeTags(

        array|string $tags
    ): array {
        $tags = VoteUtil::tagsConvert($tags);

        if (empty($tags)) {
            return [];
        }

        $rm = [];
        foreach ($tags as $key => $tag) {
            $tagK = array_search(needle: $tag, haystack: $this->tags, strict: true);

            if ($tagK === false) {
                unset($tags[$key]);
            } else {
                $rm[] = $this->tags[$tagK];
                unset($this->tags[$tagK]);
            }
        }

        $this->computeHashCode();
        return $rm;
    }
/**
 * Remove all registered tag(s) on this Vote.
 * @api
 * @return mixed Return True.
 * @see Vote::addTags, Vote::removeTags
 */
    public function removeAllTags(): true
    {
        $this->removeTags($this->tags);
        return true;
    }
/**
 * Get the vote weight. The vote weight capacity must be active at the election level for producing effect on the result.
 * @api
 * @return mixed Weight. Default weight is 1.
 * @see Vote::setWeight
 * @param $context In the context of wich election? (optional).
 */
    public function getWeight(

        ?Election $context = null
    ): int {
        if ($context !== null && !$context->isVoteWeightAllowed()) {
            return 1;
        } else {
            return $this->weight;
        }
    }
/**
 * Set a vote weight. The vote weight capacity must be active at the election level for producing effect on the result.
 * @api
 * @return mixed New weight.
 * @throws VoteInvalidFormatException
 * @see Vote::getWeight
 * @param $newWeight The new vote weight.
 */
    public function setWeight(

        int $newWeight
    ): int {
        if ($newWeight < 1) {
            throw new VoteInvalidFormatException('the vote weight can not be less than 1');
        }

        if ($newWeight !== $this->weight) {
            $this->weight = $newWeight;

            if (\count($this->link) > 0) {
                foreach ($this->getLinks() as $link) {
                    $link->setStateToVote();
                }
            }
        }

        $this->computeHashCode();

        return $this->getWeight();
    }

    /////////// INTERNAL ///////////

    private function archiveRanking(): void
    {
        $this->ranking_history[] = ['ranking' => $this->ranking, 'timestamp' => $this->lastTimestamp, 'counter' => $this->candidatesCount];

        $this->rewind();
    }

    private function computeHashCode(): string
    {
        return $this->hash = hash('sha224', $this . microtime(false));
    }
}
