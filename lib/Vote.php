<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet;

use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{Description, Example, FunctionParameter, FunctionReturn, PublicAPI, Related, Throws};
use CondorcetPHP\Condorcet\ElectionProcess\VoteUtil;
use CondorcetPHP\Condorcet\Throwable\CondorcetException;
use CondorcetPHP\Condorcet\Throwable\CandidateDoesNotExistException;
use CondorcetPHP\Condorcet\Throwable\VoteInvalidFormatException;
use CondorcetPHP\Condorcet\Throwable\VoteNotLinkedException;
use phpDocumentor\Reflection\Types\Void_;

class Vote implements \Iterator, \Stringable
{
    use Linkable, CondorcetVersion;

    // Implement Iterator

        private int $position = 1;

        public function rewind(): void {
            $this->position = 1;
        }

        public function current(): array {
            return $this->getRanking()[$this->position];
        }

        public function key(): int {
            return $this->position;
        }

        public function next(): void {
            ++$this->position;
        }

        public function valid(): bool {
            return isset($this->getRanking()[$this->position]);
        }

    // Vote

    private array $_ranking;

    private float $_lastTimestamp;

    private int $_counter;

    private array $_ranking_history = [];

    private int $_weight = 1;

    private array $_tags = [];

    private string $_hashCode = '';

    private ?Election $_electionContext = null;

    public bool $notUpdate = false;

        ///

    #[PublicAPI]
    #[Description("Build a vote object.")]
    #[Throws(VoteInvalidFormatException::class)]
    #[Example("Manual - Add Vote","https://github.com/julien-boudry/Condorcet/wiki/II-%23-B.-Vote-management-%23-1.-Add-Vote")]
    #[Related("Vote::setRanking", "Vote::addTags")]
    public function __construct (
        #[FunctionParameter('Equivalent to Vote::setRanking method')]
        array|string $ranking,
        #[FunctionParameter('Equivalent to Vote::addTags method')]
        array|string|null $tags = null,
        #[FunctionParameter('Set your own timestamp metadata on Ranking')]
        ?float $ownTimestamp = null,
        #[FunctionParameter('Try to convert directly your candidates from sting input" to Candidate object of one election')]
        ?Election $electionContext = null
    )
    {
        $this->_electionContext = $electionContext;
        $tagsFromString = null;

        // Vote Weight
        if (\is_string($ranking)) :
            $is_voteWeight = \strpos(haystack: $ranking, needle: '^');
            if ($is_voteWeight !== false) :
                $weight = \trim( \substr($ranking, $is_voteWeight + 1) );

                // Errors
                if ( !\is_numeric($weight) ) :
                    throw new VoteInvalidFormatException("you must specify an integer for the vote weight");
                endif;

                $weight = \intval($weight);

                $ranking = \substr($ranking, 0,$is_voteWeight);

            endif;

            $is_voteTags = \strpos($ranking, '||');
            if ($is_voteTags !== false) :
                $tagsFromString = \explode(',', \trim( \substr($ranking, 0, $is_voteTags) ));
                $ranking = \substr($ranking, $is_voteTags + 2);
            endif;
        endif;

        $this->setRanking($ranking, $ownTimestamp);
        $tags === null || $this->addTags($tags);
        $tagsFromString === null || $this->addTags($tagsFromString);

        if (isset($weight)) :
            $this->setWeight($weight);
        endif;

        $this->_electionContext = null;
    }

    public function __serialize (): array
    {
        $this->position = 1;

        return \get_object_vars($this);
    }

    public function __clone (): void
    {
        $this->destroyAllLink();
        $this->setHashCode();
    }

    public function __toString (): string {

        if (empty($this->getTags())) :
            return $this->getSimpleRanking();
        else :
            return $this->getTagsAsString().' || '.$this->getSimpleRanking();
        endif;
    }

    #[PublicAPI]
    #[Description("Get Object hash (cryptographic)")]
    #[FunctionReturn("SHA hash code.")]
    #[Related("Vote::getWeight")]
    public function getHashCode (): string {
        return $this->_hashCode;
    }

        ///

    // GETTERS

    #[PublicAPI]
    #[Description("Get the actual Ranking of this Vote.")]
    #[FunctionReturn("Multidimenssionnal array populated by Candidate object.")]
    #[Related("Vote::setRanking")]
    public function getRanking (): array
    {
        $r = $this->_ranking;

        foreach ($r as &$oneRank) :
            if (count($oneRank) > 1) :
                sort($oneRank, \SORT_STRING);
            endif;
        endforeach;
        
        return $r;
    }

    #[PublicAPI]
    #[Description("Return an history of each vote change, with timestamp.")]
    #[FunctionReturn("An explicit multi-dimenssional array.")]
    #[Related("Vote::getCreateTimestamp")]
    public function getHistory (): array
    {
        return $this->_ranking_history;
    }


    #[PublicAPI]
    #[Description("Get the registered tags for this Vote.")]
    #[FunctionReturn("List of registered tag.")]
    #[Related("Vote::getTagsAsString", "Vote::addTags", "Vote::removeTags")]
    public function getTags (): array
    {
        return $this->_tags;
    }

    #[PublicAPI]
    #[Description("Get the registered tags for this Vote.")]
    #[FunctionReturn("List of registered tag as string separated by commas.")]
    #[Related("Vote::getTags", "Vote::addTags", "Vote::removeTags")]
    public function getTagsAsString (): string
    {
        return \implode(',',$this->getTags());
    }

    #[PublicAPI]
    #[Description("Get the timestamp corresponding of the creation of this vote.")]
    #[FunctionReturn("Timestamp")]
    #[Related("Candidate::getTimestamp")]
    public function getCreateTimestamp (): float
    {
        return $this->_ranking_history[0]['timestamp'];
    }

    #[PublicAPI]
    #[Description("Get the timestamp corresponding of the last vote change.")]
    #[FunctionReturn("Timestamp")]
    #[Related("Vote::getCreateTimestamp")]
    public function getTimestamp (): float
    {
        return $this->_lastTimestamp;
    }

    #[PublicAPI]
    #[Description("Count the number of candidate provide into the active Ranking set.")]
    #[FunctionReturn("Number of Candidate into ranking.")]
    public function countRankingCandidates (): int
    {
        return $this->_counter;
    }

    #[PublicAPI]
    #[Description("Get all the candidates object set in the last ranking of this Vote.")]
    #[FunctionReturn("Candidates list.")]
    #[Related("Vote::getRanking", "Vote::countRankingCandidates")]
    public function getAllCandidates (): array
    {
        $list = [];

        foreach ($this->getRanking() as $rank) :
            foreach ($rank as $oneCandidate) :
                $list[] = $oneCandidate;
            endforeach;
        endforeach;

        return $list;
    }

    #[PublicAPI]
    #[Description("Return the vote actual ranking complete for the contexte of the provide election. Election must be linked to the Vote object.")]
    #[FunctionReturn("Contextual full ranking.")]
    #[Throws(VoteNotLinkedException::class)]
    #[Related("Vote::getContextualRankingAsString", "Vote::getRanking")]
    public function getContextualRanking (
        #[FunctionParameter('An election already linked to the Vote')]
        Election $election
    ): array
    {
        if (!$this->haveLink($election)) :
            throw new VoteNotLinkedException();
        endif;

        $countContextualCandidate = 0;

        $present = $this->getAllCandidates();
        $candidates_list = $election->getCandidatesList();

        $newRanking = $this->computeContextualRankingWithoutImplicit($this->getRanking(), $election, $countContextualCandidate);

        if ($election->getImplicitRankingRule() && $countContextualCandidate < $election->countCandidates()) :
            $last_rank = [];
            foreach ($candidates_list as $oneCandidate) :
                if (!\in_array(needle: $oneCandidate, haystack: $present, strict: true)) :
                    $last_rank[] = $oneCandidate;
                endif;
            endforeach;

            sort($last_rank, \SORT_STRING);

            $newRanking[count($newRanking) + 1] = $last_rank;
        endif;

        return $newRanking;
    }

        protected function computeContextualRankingWithoutImplicit (array $ranking, Election $election, int &$countContextualCandidate = 0): array
        {
            $newRanking = [];
            $nextRank = 1;
            $rankChange = false;

            foreach ($ranking as $CandidatesInRanks) :
                foreach ($CandidatesInRanks as $candidate) :
                    if ( $election->isRegisteredCandidate($candidate, true) ) :
                        $newRanking[$nextRank][] = $candidate;
                        $countContextualCandidate++;
                        $rankChange = true;
                    endif;
                endforeach;

                if ($rankChange) :
                    $nextRank++;
                    $rankChange = false;
                endif;
            endforeach;

            return $newRanking;
        }

    #[PublicAPI]
    #[Description("Return the vote actual ranking complete for the contexte of the provide election. Election must be linked to the Vote object.")]
    #[FunctionReturn("Contextual full ranking, with string instead Candidate object.")]
    #[Related("Vote::getContextualRanking", "Vote::getRanking")]
    public function getContextualRankingAsString (
        #[FunctionParameter('An election already linked to the Vote')]
        Election $election
    ): array
    {
        return CondorcetUtil::format($this->getContextualRanking($election),true);
    }

    #[PublicAPI]
    #[Description("Get the current ranking as a string format. Optionally with an election context, see Election::getContextualRanking()")]
    #[FunctionReturn("String like 'A>D=C>B'")]
    #[Related("Vote::getRanking")]
    public function getSimpleRanking (
        #[FunctionParameter('An election already linked to the Vote')]
        ?Election $context = null,
        #[FunctionParameter('Include or not the weight symbol and value')]
        bool $displayWeight = true
    ): string
    {
        $ranking = $context ? $this->getContextualRanking($context): $this->getRanking();

        $simpleRanking = VoteUtil::getRankingAsString($ranking);

        if ($displayWeight && $this->_weight > 1 && ( ($context && $context->isVoteWeightAllowed()) || $context === null )  ) :
            $simpleRanking .= " ^".$this->getWeight();
        endif;

        return $simpleRanking;
    }


    // SETTERS

    #[PublicAPI]
    #[Description("Set a new ranking for this vote.\n\nNote that if your vote is already linked to one ore more elections, your ranking must be compliant with all of them, else an exception is throw. For do this, you need to use only valid Candidate object, you can't register a new ranking from string if your vote is already linked to an election.")]
    #[FunctionReturn("In case of success, return TRUE")]
    #[Throws(VoteInvalidFormatException::class)]
    #[Example("Manual - Add a vote","https://github.com/julien-boudry/Condorcet/wiki/II-%23-B.-Vote-management-%23-1.-Add-Vote")]
    #[Related("Vote::getRanking", "Vote::getHistory", "Vote::__construct")]
    public function setRanking (
        #[FunctionParameter('A Ranking. Have a look at the Wiki https://github.com/julien-boudry/Condorcet/wiki/II-%23-B.-Vote-management-%23-1.-Add-Vote to learn the available ranking formats.')]
        array|string $ranking,
        #[FunctionParameter('Set your own timestamp metadata on Ranking. Your timestamp must be > than last registered timestamp. Else, an exception will be throw.')]
        ?float $ownTimestamp = null
    ): bool
    {
        // Timestamp
        if ($ownTimestamp !== null) :
            if (!empty($this->_ranking_history) && $this->getTimestamp() >= $ownTimestamp) :
                throw new CondorcetException(21);
            endif;
        endif;

        // Ranking
        $candidateCounter = $this->formatRanking($ranking);

        if ($this->_electionContext !== null) :
            $this->_electionContext->convertRankingCandidates($ranking);
        endif;

        if(!$this->notUpdate) :
            foreach ($this->_link as $link) :
                $link->prepareUpdateVote($this);
            endforeach;
        endif;

        $this->_ranking = $ranking;
        $this->_lastTimestamp = $ownTimestamp ?? \microtime(true);
        $this->_counter = $candidateCounter;

        $this->archiveRanking();

        if (!empty($this->_link)) :

            try {
                foreach ($this->_link as $link) :
                    if (!$link->checkVoteCandidate($this)) :
                        throw new CondorcetException(18);
                    endif;
                endforeach;
            } catch (CondorcetException $e) {
                foreach ($this->_link as $link) :
                    $link->setStateToVote();
                endforeach;

                throw $e;
            }

            if (!$this->notUpdate) :
                foreach ($this->_link as $link) :
                    $link->finishUpdateVote($this);
                endforeach;
            endif;
        endif;

        $this->setHashCode();
        return true;
    }

        private function formatRanking (array|string &$ranking): int
        {
            if (\is_string($ranking)) :
                $ranking = VoteUtil::convertVoteInput($ranking);
            endif;

            if (!\is_array($ranking)) :
                throw new VoteInvalidFormatException();
            endif;

            $ranking = \array_filter($ranking, fn ($key): bool => \is_numeric($key), \ARRAY_FILTER_USE_KEY);

            \ksort($ranking);

            $i = 1;
            $vote_r = [];
            foreach ($ranking as &$value) :
                if ( !\is_array($value) ) :
                    $vote_r[$i] = [$value];
                else :
                    $vote_r[$i] = $value;
                endif;

                $i++;
            endforeach;

            $ranking = $vote_r;

            $counter = 0;
            $list_candidate = [];
            foreach ($ranking as &$line) :
                foreach ($line as &$Candidate) :
                    if ( !($Candidate instanceof Candidate) ) :
                        $Candidate = new Candidate ($Candidate);
                        $Candidate->setProvisionalState(true);
                    endif;

                    $counter++;

                // Check Duplicate

                    // Check objet reference AND check candidates name
                    if (!\in_array($Candidate->getName(), $list_candidate)) :
                        $list_candidate[] = $Candidate;
                    else :
                        throw new VoteInvalidFormatException();
                    endif;

                endforeach;
            endforeach;

            return $counter;
        }


    #[PublicAPI]
    #[Description("Remove candidate from ranking. Set a new ranking and archive the old ranking.")]
    #[FunctionReturn("True on success.")]
    #[Throws(CandidateDoesNotExistException::class)]
    #[Related("Vote::setRanking")]
    public function removeCandidate (
        #[FunctionParameter('Candidate object or string')]
        Candidate|string $candidate
    ): bool
    {
        if ($candidate instanceof Candidate) :
            $strict = true;
        elseif (\is_string($candidate)) :
            $strict = false;
        endif;

        $ranking = $this->getRanking();

        $rankingCandidate = $this->getAllCandidates();

        if (!\in_array(needle: $candidate, haystack: $rankingCandidate, strict:  $strict)) :
            throw new CandidateDoesNotExistException((string) $candidate);
        endif;

        foreach ($ranking as $rankingKey => &$rank) :
            foreach ($rank as $oneRankKey => $oneRankValue) :
                if ( $strict ? $oneRankValue === $candidate : $oneRankValue == $candidate ) :
                    unset($rank[$oneRankKey]);
                endif;
            endforeach;

            if (empty($rank)) :
                unset($ranking[$rankingKey]);
            endif;
        endforeach;

        $this->setRanking($ranking);

        return true;
    }


    #[PublicAPI]
    #[Description("Add tag(s) on this Vote.")]
    #[FunctionReturn("In case of success, return TRUE")]
    #[Throws(VoteInvalidFormatException::class)]
    #[Example("Manual - Add Vote","https://github.com/julien-boudry/Condorcet/wiki/II-%23-B.-Vote-management-%23-1.-Add-Vote")]
    #[Related("Vote::removeTags")]
    public function addTags (
        #[FunctionParameter('Tag(s) are non-numeric alphanumeric string. They can be added by string separated by commas or an array.')]
        array|string $tags
    ): bool
    {
        $tags = VoteUtil::tagsConvert($tags);

        if (empty($tags)) :
            return false;
        endif;

        foreach ($tags as $key => $tag) :
            if (\in_array(needle: $tag, haystack: $this->_tags, strict: true)) :
                unset($tags[$key]);
            endif;
        endforeach;

        foreach ($tags as $tag) :
            $this->_tags[] = $tag;
        endforeach;

        $this->setHashCode();

        return true;
    }

    #[PublicAPI]
    #[Description("Remove registered tag(s) on this Vote.")]
    #[FunctionReturn("List of deleted tags.")]
    #[Example("Manual - Add Vote","https://github.com/julien-boudry/Condorcet/wiki/II-%23-B.-Vote-management-%23-1.-Add-Vote")]
    #[Related("Vote::addTags")]
    public function removeTags (
        #[FunctionParameter('They can be added by string separated by commas or an array.')]
        array|string $tags
    ): array
    {
        $tags = VoteUtil::tagsConvert($tags);

        if (empty($tags)) :
            return [];
        endif;

        $rm = [];
        foreach ($tags as $key => $tag) :
            $tagK = \array_search(needle: $tag, haystack: $this->_tags, strict: true);

            if ($tagK === false) :
                unset($tags[$key]);
            else :
                $rm[] = $this->_tags[$tagK];
                unset($this->_tags[$tagK]);
            endif;
        endforeach;

        $this->setHashCode();
        return $rm;
    }

    #[PublicAPI]
    #[Description("Remove all registered tag(s) on this Vote.")]
    #[FunctionReturn("Return True.")]
    #[Related("Vote::addTags", "Vote::removeTags")]
    public function removeAllTags (): bool
    {
        $this->removeTags($this->getTags());
        return true;
    }

    #[PublicAPI]
    #[Description("Get the vote weight. The vote weight capacity must be active at the election level for producing effect on the result.")]
    #[FunctionReturn("Weight. Default weight is 1.")]
    #[Related("Vote::setWeight")]
    public function getWeight (
        #[FunctionParameter('In the context of wich election? (optional)')]
        ?Election $context = null
    ): int
    {
        if ($context !== null && !$context->isVoteWeightAllowed()) :
            return 1;
        else :
            return $this->_weight;
        endif;
    }

    #[PublicAPI]
    #[Description("Set a vote weight. The vote weight capacity must be active at the election level for producing effect on the result.")]
    #[FunctionReturn("New weight.")]
    #[Related("Vote::getWeight")]
    public function setWeight (
        #[FunctionParameter('The new vote weight.')]
        int $newWeight
    ): int
    {
        if ($newWeight < 1) :
            throw new CondorcetException(26);
        endif;

        if ($newWeight !== $this->_weight) :

            $this->_weight = $newWeight;

            if (!empty($this->_link)) :
                foreach ($this->_link as $link) :
                    $link->setStateToVote();
                endforeach;
            endif;
        endif;

        $this->setHashCode();

        return $this->getWeight();
    }

/////////// INTERNAL ///////////

    private function archiveRanking (): void
    {
        $this->_ranking_history[] = [   'ranking' => $this->_ranking,
                                        'timestamp' => $this->_lastTimestamp,
                                        'counter' => $this->_counter   ];

        $this->rewind();
    }

    private function setHashCode (): string
    {
        return $this->_hashCode = \hash('sha224', ((string) $this) . \microtime(false));
    }
}
