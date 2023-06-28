<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

declare(strict_types=1);

namespace CondorcetPHP\Condorcet;

use ArrayAccess;
use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\BookLibrary;
use CondorcetPHP\Condorcet\Throwable\{CandidateDoesNotExistException, VoteException, VoteInvalidFormatException, VoteNotLinkedException};
use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{Book, Description, Example, FunctionParameter, FunctionReturn, InternalModulesAPI, PublicAPI, Related, Throws};
use CondorcetPHP\Condorcet\Relations\Linkable;
use CondorcetPHP\Condorcet\Utils\{CondorcetUtil, VoteEntryParser, VoteUtil};

class Vote implements \Iterator, \Stringable, ArrayAccess
{
    use Linkable;
    use CondorcetVersion;

    // Implement ArrayAccess

    #[Throws(VoteException::class)]
    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new VoteException('Modifying a Vote as a table is not yet supported.');
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->ranking[$offset]);
    }

    #[Throws(VoteException::class)]
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

    private array $ranking;

    private float $lastTimestamp;

    private int $counter;

    private array $ranking_history = [];

    private int $weight = 1;

    private array $tags = [];

    private string $hashCode = '';

    private ?Election $electionContext = null;

    public bool $notUpdate = false;


    // Performance (for internal use)
    protected static ?\stdClass $cacheKey = null;
    protected ?\WeakMap $cacheMap = null;

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

    #[PublicAPI]
    #[Description('Build a vote object.')]
    #[Throws(VoteInvalidFormatException::class)]
    #[Book(BookLibrary::Votes)]
    #[Related('Vote::setRanking', 'Vote::addTags')]
    public function __construct(
        #[FunctionParameter('Equivalent to Vote::setRanking method')]
        array|string $ranking,
        #[FunctionParameter('Equivalent to Vote::addTags method')]
        array|string|null $tags = null,
        #[FunctionParameter('Set your own timestamp metadata on Ranking')]
        ?float $ownTimestamp = null,
        #[FunctionParameter('Try to convert directly your candidates from sting input" to Candidate object of one election')]
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
        $tags === null || $this->addTags($tags);
        $tagsFromString === null || $this->addTags($tagsFromString);

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
        if (empty($this->getTags())) {
            return $this->getSimpleRanking();
        } else {
            return $this->getTagsAsString().' || '.$this->getSimpleRanking();
        }
    }

    #[PublicAPI]
    #[Description('Get Object hash (cryptographic)')]
    #[FunctionReturn('SHA hash code.')]
    #[Related('Vote::getWeight')]
    public function getHashCode(): string
    {
        return $this->hashCode;
    }

    // -------

    // GETTERS

    #[PublicAPI]
    #[Description('Get the actual Ranking of this Vote.')]
    #[FunctionReturn('Multidimenssionnal array populated by Candidate object.')]
    #[Related('Vote::setRanking')]
    public function getRanking(
        #[FunctionParameter('Sort Candidate in a Rank by name. Useful for performant internal calls from methods.')]
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
    
    #[PublicAPI]
    #[Description('Get the Rankings of each candidate in this Vote as an array with candidate keys.')]
    #[FunctionReturn('Array populated by Candidate keys.')]
    #[Related('Vote::setRanking')]
    public function getRankingsAsAssociativeArray(Election $election, bool $implicitRank): array
    {
        $rankings = [];
        foreach($election->getCandidatesList() as $candidateKey=>$candidate)
        {
            if ($implicitRank) {
                $rankings[$candidateKey] = $this->countRanks()+1;
            }
            foreach($this->getRanking(true) as $rank=>$equalCandidates) {
                if (in_array($candidate, $equalCandidates)) {
                    $rankings[$candidateKey] = $rank;
                    break;
                }
            }
        }
        return $rankings;
    }

    #[PublicAPI]
    #[Description('Return a history of each vote change, with timestamp.')]
    #[FunctionReturn('An explicit multi-dimenssional array.')]
    #[Related('Vote::getCreateTimestamp')]
    public function getHistory(): array
    {
        return $this->ranking_history;
    }


    #[PublicAPI]
    #[Description('Get the registered tags for this Vote.')]
    #[FunctionReturn('List of registered tag.')]
    #[Related('Vote::getTagsAsString', 'Vote::addTags', 'Vote::removeTags')]
    public function getTags(): array
    {
        return $this->tags;
    }

    #[PublicAPI]
    #[Description('Get the registered tags for this Vote.')]
    #[FunctionReturn('List of registered tag as string separated by commas.')]
    #[Related('Vote::getTags', 'Vote::addTags', 'Vote::removeTags')]
    public function getTagsAsString(): string
    {
        return implode(',', $this->getTags());
    }

    #[PublicAPI]
    #[Description('Get the timestamp corresponding of the creation of this vote.')]
    #[FunctionReturn('Timestamp')]
    #[Related('Candidate::getTimestamp')]
    public function getCreateTimestamp(): float
    {
        return $this->ranking_history[0]['timestamp'];
    }

    #[PublicAPI]
    #[Description('Get the timestamp corresponding of the last vote change.')]
    #[FunctionReturn('Timestamp')]
    #[Related('Vote::getCreateTimestamp')]
    public function getTimestamp(): float
    {
        return $this->lastTimestamp;
    }

    #[PublicAPI]
    #[Description('Count the number of candidate provide into the active Ranking set.')]
    #[FunctionReturn('Number of Candidate into ranking.')]
    public function countRankingCandidates(): int
    {
        return $this->counter;
    }

    #[PublicAPI]
    #[Description('Count the number of ranks.')]
    #[FunctionReturn('Number of ranks.')]
    public function countRanks(): int
    {
        return \count($this->ranking);
    }

    #[PublicAPI]
    #[Description('Get all the candidates object set in the last ranking of this Vote.')]
    #[FunctionReturn('Candidates list.')]
    #[Related('Vote::getRanking', 'Vote::countRankingCandidates')]
    public function getAllCandidates(
        #[FunctionParameter('An election already linked to the Vote')]
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

    #[PublicAPI]
    #[Description('Return the vote actual ranking complete for the contexte of the provide election. Election must be linked to the Vote object.')]
    #[FunctionReturn('Contextual full ranking.')]
    #[Throws(VoteNotLinkedException::class)]
    #[Related('Vote::getContextualRankingAsString', 'Vote::getRanking')]
    public function getContextualRanking(
        #[FunctionParameter('An election already linked to the Vote')]
        Election $election,
    ): array {
        return $this->computeContextualRanking($election, true);
    }

    // Performances
    #[InternalModulesAPI]
    public function getContextualRankingWithoutSort(
        #[FunctionParameter('An election already linked to the Vote')]
        Election $election,
    ): array {
        return $this->computeContextualRanking($election, false);
    }

    protected function computeContextualRanking(
        #[FunctionParameter('An election already linked to the Vote')]
        Election $election,
        #[FunctionParameter('If false, performance can be increased for Implicit Ranking election.')]
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

    #[PublicAPI]
    #[Description('Return the vote actual ranking complete for the contexte of the provide election. Election must be linked to the Vote object.')]
    #[FunctionReturn('Contextual full ranking, with string instead Candidate object.')]
    #[Related('Vote::getContextualRanking', 'Vote::getRanking')]
    public function getContextualRankingAsString(
        #[FunctionParameter('An election already linked to the Vote')]
        Election $election
    ): array {
        return CondorcetUtil::format($this->getContextualRanking($election), true);
    }

    #[PublicAPI]
    #[Description('Get the current ranking as a string format. Optionally with an election context, see Election::getContextualRanking()')]
    #[FunctionReturn("String like 'A>D=C>B'")]
    #[Related('Vote::getRanking')]
    public function getSimpleRanking(
        #[FunctionParameter('An election already linked to the Vote')]
        ?Election $context = null,
        #[FunctionParameter('Include or not the weight symbol and value')]
        bool $displayWeight = true
    ): string {
        $ranking = $context ? $this->getContextualRanking($context) : $this->getRanking();

        $simpleRanking = VoteUtil::getRankingAsString($ranking);

        if ($displayWeight && $this->weight > 1 && (($context && $context->isVoteWeightAllowed()) || $context === null)) {
            $simpleRanking .= ' ^'.$this->getWeight();
        }

        return $simpleRanking;
    }


    // SETTERS

    #[PublicAPI]
    #[Description("Set a new ranking for this vote.\n\nNote that if your vote is already linked to one ore more elections, your ranking must be compliant with all of them, else an exception is throw. For do this, you need to use only valid Candidate object, you can't register a new ranking from string if your vote is already linked to an election.")]
    #[FunctionReturn('In case of success, return TRUE')]
    #[Throws(VoteInvalidFormatException::class)]
    #[Book(BookLibrary::Votes)]
    #[Related('Vote::getRanking', 'Vote::getHistory', 'Vote::__construct')]
    public function setRanking(
        #[FunctionParameter('A Ranking. Have a look at the Wiki https://github.com/julien-boudry/Condorcet/wiki/II-%23-B.-Vote-management-%23-1.-Add-Vote to learn the available ranking formats.')]
        array|string $ranking,
        #[FunctionParameter('Set your own timestamp metadata on Ranking. Your timestamp must be > than last registered timestamp. Else, an exception will be throw.')]
        ?float $ownTimestamp = null
    ): true {
        // Timestamp
        if ($ownTimestamp !== null) {
            if (!empty($this->ranking_history) && $this->getTimestamp() >= $ownTimestamp) {
                throw new VoteInvalidFormatException('Timestamp format of vote is not correct');
            }
        }

        // Ranking
        $candidateCounter = $this->formatRanking($ranking);

        if ($this->electionContext !== null) {
            $this->electionContext->convertRankingCandidates($ranking);
        }

        if (!$this->notUpdate) {
            foreach ($this->getLinks() as $link) {
                $link->prepareUpdateVote($this);
            }
        }

        $this->ranking = $ranking;
        $this->lastTimestamp = $ownTimestamp ?? microtime(true);
        $this->counter = $candidateCounter;

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
        return true;
    }

    private function formatRanking(array|string &$ranking): int
    {
        if (\is_string($ranking)) {
            $ranking = (new VoteEntryParser($ranking))->ranking ?? [];
        }

        $ranking = array_filter($ranking, static fn ($key): bool => is_numeric($key), \ARRAY_FILTER_USE_KEY);

        ksort($ranking);

        $i = 1;
        $vote_r = [];
        foreach ($ranking as &$value) {
            if (!\is_array($value)) {
                $vote_r[$i] = [$value];
            } else {
                $vote_r[$i] = $value;
            }

            $i++;
        }

        $ranking = $vote_r;

        $counter = 0;
        $list_candidate = [];
        foreach ($ranking as &$line) {
            foreach ($line as &$Candidate) {
                if (!($Candidate instanceof Candidate)) {
                    $Candidate = new Candidate($Candidate);
                    $Candidate->setProvisionalState(true);
                }

                $counter++;

                // Check Duplicate

                // Check objet reference AND check candidates name
                if (!\in_array($name = $Candidate->getName(), $list_candidate, true)) {
                    $list_candidate[] = $name;
                } else {
                    throw new VoteInvalidFormatException;
                }
            }
        }

        return $counter;
    }


    #[PublicAPI]
    #[Description('Remove candidate from ranking. Set a new ranking and archive the old ranking.')]
    #[FunctionReturn('True on success.')]
    #[Throws(CandidateDoesNotExistException::class)]
    #[Related('Vote::setRanking')]
    public function removeCandidate(
        #[FunctionParameter('Candidate object or string')]
        Candidate|string $candidate
    ): true {
        if ($candidate instanceof Candidate) {
            $strict = true;
        } else {
            $strict = false;
        }

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


    #[PublicAPI]
    #[Description('Add tag(s) on this Vote.')]
    #[FunctionReturn('In case of success, return TRUE')]
    #[Throws(VoteInvalidFormatException::class)]
    #[Book(BookLibrary::VotesTags)]
    #[Related('Vote::removeTags')]
    public function addTags(
        #[FunctionParameter('Tag(s) are non-numeric alphanumeric string. They can be added by string separated by commas or an array. Tags will be trimmed')]
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

    #[PublicAPI]
    #[Description('Remove registered tag(s) on this Vote.')]
    #[FunctionReturn('List of deleted tags.')]
    #[Book(BookLibrary::VotesTags)]
    #[Related('Vote::addTags')]
    public function removeTags(
        #[FunctionParameter('They can be added by string separated by commas or an array.')]
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

    #[PublicAPI]
    #[Description('Remove all registered tag(s) on this Vote.')]
    #[FunctionReturn('Return True.')]
    #[Related('Vote::addTags', 'Vote::removeTags')]
    public function removeAllTags(): true
    {
        $this->removeTags($this->getTags());
        return true;
    }

    #[PublicAPI]
    #[Description('Get the vote weight. The vote weight capacity must be active at the election level for producing effect on the result.')]
    #[FunctionReturn('Weight. Default weight is 1.')]
    #[Related('Vote::setWeight')]
    public function getWeight(
        #[FunctionParameter('In the context of wich election? (optional)')]
        ?Election $context = null
    ): int {
        if ($context !== null && !$context->isVoteWeightAllowed()) {
            return 1;
        } else {
            return $this->weight;
        }
    }

    #[PublicAPI]
    #[Description('Set a vote weight. The vote weight capacity must be active at the election level for producing effect on the result.')]
    #[FunctionReturn('New weight.')]
    #[Throws(VoteInvalidFormatException::class)]
    #[Related('Vote::getWeight')]
    public function setWeight(
        #[FunctionParameter('The new vote weight.')]
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
        $this->ranking_history[] = ['ranking' => $this->ranking, 'timestamp' => $this->lastTimestamp, 'counter' => $this->counter];

        $this->rewind();
    }

    private function computeHashCode(): string
    {
        return $this->hashCode = hash('sha224', ((string) $this) . microtime(false));
    }
}
