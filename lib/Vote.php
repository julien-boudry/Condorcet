<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet;

use CondorcetPHP\Condorcet\CondorcetDocAttributes\{CondorcetDoc_PublishAsPublicAPI};
use CondorcetPHP\Condorcet\ElectionProcess\VoteUtil;
use CondorcetPHP\Condorcet\Throwable\CondorcetException;

class Vote implements \Iterator, \Stringable
{
    use Linkable, CondorcetVersion;

    // Implement Iterator

        private int $position = 1;

        public function rewind() : void {
            $this->position = 1;
        }

        public function current() : array {
            return $this->getRanking()[$this->position];
        }

        public function key() : int {
            return $this->position;
        }

        public function next() : void {
            ++$this->position;
        }

        public function valid() : bool {
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

    #[CondorcetDoc_PublishAsPublicAPI(isPublicApi: true)]
    public function __construct (array|string $ranking, array|string|null $tags = null, ?float $ownTimestamp = null, ?Election $electionContext = null)
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
                    throw new CondorcetException(13);
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

    public function __serialize () : array
    {
        $this->position = 1;

        return \get_object_vars($this);
    }

    public function __clone () : void
    {
        $this->destroyAllLink();
        $this->setHashCode();
    }

    public function __toString () : string {

        if (empty($this->getTags())) :
            return $this->getSimpleRanking();
        else :
            return $this->getTagsAsString().' || '.$this->getSimpleRanking();
        endif;
    }

    #[CondorcetDoc_PublishAsPublicAPI(isPublicApi: true)]
    public function getHashCode () : string {
        return $this->_hashCode;
    }

        ///

    // GETTERS

    #[CondorcetDoc_PublishAsPublicAPI(isPublicApi: true)]
    public function getRanking () : array
    {
        return $this->_ranking;
    }

    #[CondorcetDoc_PublishAsPublicAPI(isPublicApi: true)]
    public function getHistory () : array
    {
        return $this->_ranking_history;
    }


    #[CondorcetDoc_PublishAsPublicAPI(isPublicApi: true)]
    public function getTags () : array
    {
        return $this->_tags;
    }

    #[CondorcetDoc_PublishAsPublicAPI(isPublicApi: true)]
    public function getTagsAsString () : string
    {
        return \implode(',',$this->getTags());
    }

    #[CondorcetDoc_PublishAsPublicAPI(isPublicApi: true)]
    public function getCreateTimestamp () : float
    {
        return $this->_ranking_history[0]['timestamp'];
    }

    #[CondorcetDoc_PublishAsPublicAPI(isPublicApi: true)]
    public function getTimestamp () : float
    {
        return $this->_lastTimestamp;
    }

    #[CondorcetDoc_PublishAsPublicAPI(isPublicApi: true)]
    public function countRankingCandidates () : int
    {
        return $this->_counter;
    }

    #[CondorcetDoc_PublishAsPublicAPI(isPublicApi: true)]
    public function getAllCandidates () : array
    {
        $list = [];

        foreach ($this->getRanking() as $rank) :
            foreach ($rank as $oneCandidate) :
                $list[] = $oneCandidate;
            endforeach;
        endforeach;

        return $list;
    }

    #[CondorcetDoc_PublishAsPublicAPI(isPublicApi: true)]
    public function getContextualRanking (Election $election) : array
    {
        if (!$this->haveLink($election)) :
            throw new CondorcetException(22);
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

            $newRanking[count($newRanking) + 1] = $last_rank;
        endif;

        return $newRanking;
    }

        protected function computeContextualRankingWithoutImplicit (array $ranking, Election $election, int &$countContextualCandidate = 0) : array
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

    #[CondorcetDoc_PublishAsPublicAPI(isPublicApi: true)]
    public function getContextualRankingAsString (Election $election) : array
    {
        return CondorcetUtil::format($this->getContextualRanking($election),true);
    }

    #[CondorcetDoc_PublishAsPublicAPI(isPublicApi: true)]
    public function getSimpleRanking (?Election $context = null, bool $displayWeight = true) : string
    {
        $ranking = $context ? $this->getContextualRanking($context) : $this->getRanking();

        $simpleRanking = VoteUtil::getRankingAsString($ranking);

        if ($displayWeight && $this->_weight > 1 && ( ($context && $context->isVoteWeightAllowed()) || $context === null )  ) :
            $simpleRanking .= " ^".$this->getWeight();
        endif;

        return $simpleRanking;
    }


    // SETTERS

    #[CondorcetDoc_PublishAsPublicAPI(isPublicApi: true)]
    public function setRanking (array|string $ranking, ?float $ownTimestamp = null) : bool
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

        private function formatRanking (array|string &$ranking) : int
        {
            if (\is_string($ranking)) :
                $ranking = VoteUtil::convertVoteInput($ranking);
            endif;

            if (!\is_array($ranking)) :
                throw new CondorcetException(5);
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
                    if (!\in_array($Candidate, $list_candidate)) :
                        $list_candidate[] = $Candidate;
                    else :
                        throw new CondorcetException(5);
                    endif;

                endforeach;
            endforeach;

            return $counter;
        }


    #[CondorcetDoc_PublishAsPublicAPI(isPublicApi: true)]
    public function removeCandidate (Candidate|string $candidate) : bool
    {
        if ($candidate instanceof Candidate) :
            $strict = true;
        elseif (\is_string($candidate)) :
            $strict = false;
        endif;

        $ranking = $this->getRanking();

        $rankingCandidate = $this->getAllCandidates();

        if (!\in_array(needle: $candidate, haystack: $rankingCandidate, strict:  $strict)) :
            throw new CondorcetException (32);
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


    #[CondorcetDoc_PublishAsPublicAPI(isPublicApi: true)]
    public function addTags (array|string $tags) : bool
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

    #[CondorcetDoc_PublishAsPublicAPI(isPublicApi: true)]
    public function removeTags (array|string $tags) : array
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

    #[CondorcetDoc_PublishAsPublicAPI(isPublicApi: true)]
    public function removeAllTags () : bool
    {
        $this->removeTags($this->getTags());
        return true;
    }

    #[CondorcetDoc_PublishAsPublicAPI(isPublicApi: true)]
    public function getWeight (?Election $context = null) : int
    {
        if ($context !== null && !$context->isVoteWeightAllowed()) :
            return 1;
        else :
            return $this->_weight;
        endif;
    }

    #[CondorcetDoc_PublishAsPublicAPI(isPublicApi: true)]
    public function setWeight (int $newWeight) : int
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

    private function archiveRanking () : void
    {
        $this->_ranking_history[] = [   'ranking' => $this->_ranking,
                                        'timestamp' => $this->_lastTimestamp,
                                        'counter' => $this->_counter   ];

        $this->rewind();
    }

    private function setHashCode () : string
    {
        return $this->_hashCode = \hash('sha224', ((string) $this) . \microtime(false));
    }
}
