<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet;

use CondorcetPHP\Condorcet\ElectionProcess\VoteUtil;
use CondorcetPHP\Condorcet\Throwable\CondorcetException;

class Vote implements \Iterator
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

    private $_ranking;

    private $_lastTimestamp;

    private $_counter;

    private $_ranking_history = [];

    private int $_weight = 1;

    private array $_tags = [];

    private string $_hashCode = '';

    private ?Election $_electionContext = null;

        ///

    public function __construct ($ranking, $tags = null, ?float $ownTimestamp = null, ?Election $electionContext = null)
    {
        $this->_electionContext = $electionContext;
        $tagsFromString = null;

        // Vote Weight
        if (is_string($ranking)) :
            $is_voteWeight = mb_strpos($ranking, '^');
            if ($is_voteWeight !== false) :
                $weight = trim( substr($ranking, $is_voteWeight + 1) );

                // Errors
                if ( !is_numeric($weight) ) :
                    throw new CondorcetException(13);
                endif;

                $weight = intval($weight);

                $ranking = substr($ranking, 0,$is_voteWeight);

            endif;

            $is_voteTags = mb_strpos($ranking, '||');
            if ($is_voteTags !== false) :
                $tagsFromString = explode(',', trim( substr($ranking, 0, $is_voteTags) ));
                $ranking = substr($ranking, $is_voteTags + 2);
            endif;
        endif;

        $this->setRanking($ranking, $ownTimestamp);
        $this->addTags($tags);
        $this->addTags($tagsFromString);

        if (isset($weight)) :
            $this->setWeight($weight);
        endif;

        $this->_electionContext = null;
    }

    public function __serialize () : array
    {
        $this->position = 1;

        return get_object_vars($this);
    }

    public function __clone ()
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

    public function getHashCode () : string {
        return $this->_hashCode;
    }

        ///

    // GETTERS

    public function getRanking () : array
    {
        return $this->_ranking;
    }

    public function getHistory () : array
    {
        return $this->_ranking_history;
    }


    public function getTags () : array
    {
        return $this->_tags;
    }

    public function getTagsAsString () : string
    {
        return implode(',',$this->getTags());
    }

    public function getCreateTimestamp () : float
    {
        return $this->_ranking_history[0]['timestamp'];
    }

    public function getTimestamp () : float
    {
        return $this->_lastTimestamp;
    }

    public function countRankingCandidates () : int
    {
        return $this->_counter;
    }

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
                if (!in_array($oneCandidate, $present, true)) :
                    $last_rank[] = $oneCandidate;
                endif;
            endforeach;

            $newRanking[] = $last_rank;
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

    public function getContextualRankingAsString (Election $election) : array
    {
        return CondorcetUtil::format($this->getContextualRanking($election),true);
    }

    public function getSimpleRanking (?Election $context = null) : string
    {
        $ranking = $context ? $this->getContextualRanking($context) : $this->getRanking();

        $simpleRanking = VoteUtil::getRankingAsString($ranking);

        if ($this->_weight > 1 && ( ($context && $context->isVoteWeightAllowed()) || $context === null )  ) :
            $simpleRanking .= " ^".$this->getWeight();
        endif;

        return $simpleRanking;
    }


    // SETTERS

    public function setRanking ($ranking, ?float $ownTimestamp = null) : bool
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

        foreach ($this->_link as $link) :
            $link->prepareUpdateVote($this);
        endforeach;

        $this->_ranking = $ranking;
        $this->_lastTimestamp = $ownTimestamp ?? microtime(true);
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

            foreach ($this->_link as $link) :
                $link->finishUpdateVote($this);
            endforeach;
        endif;

        $this->setHashCode();
        return true;
    }

        private function formatRanking (&$ranking) : int
        {
            if (is_string($ranking)) :
                $ranking = VoteUtil::convertVoteInput($ranking);
            endif;

            if (!is_array($ranking)) :
                throw new CondorcetException(5);
            endif;

            $ranking = array_filter($ranking, function ($key) {
                return is_numeric($key);
            }, ARRAY_FILTER_USE_KEY);

            ksort($ranking);
            
            $i = 1; $vote_r = [];
            foreach ($ranking as &$value) :
                if ( !is_array($value) ) :
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
                    if (!in_array($Candidate, $list_candidate)) :
                        $list_candidate[] = $Candidate;
                    else : 
                        throw new CondorcetException(5);
                    endif;

                endforeach;
            endforeach;

            return $counter;
        }


    public function removeCandidates ($candidate) : bool
    {
        if ($candidate instanceof Candidate) :
            $strict = true;
        elseif (is_string($candidate)) :
            $strict = false;
        else :
            throw new CondorcetException (32);
        endif;

        $ranking = $this->getRanking();

        $rankingCandidate = $this->getAllCandidates();

        if (!in_array($candidate, $rankingCandidate, $strict)) :
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


    public function addTags ($tags) : bool
    {
        $tags = VoteUtil::tagsConvert($tags);

        if (empty($tags)) :
            return false;
        endif;

        foreach ($tags as $key => $tag) :
            if (is_numeric($tag)) :
                throw new CondorcetException(17);
            elseif (in_array($tag, $this->_tags, true)) :
                unset($tags[$key]);
            endif;
        endforeach;

        foreach ($tags as $tag) :
            $this->_tags[] = $tag;
        endforeach;

        $this->setHashCode();

        return true;
    }

    public function removeTags ($tags) : array
    {
        $tags = VoteUtil::tagsConvert($tags);

        if (empty($tags)) :
            return [];
        endif;

        $rm = [];
        foreach ($tags as $key => $tag) :
            $tagK = array_search($tag, $this->_tags, true);

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

    public function removeAllTags () : bool
    {
        $this->removeTags($this->getTags());
        return true;
    }

    public function getWeight () : int
    {
        return $this->_weight;
    }

    public function setWeight (int $newWeight) : int
    {
        if ($newWeight < 1) :
            throw new CondorcetException(26);
        endif;

        $this->_weight = $newWeight;

        if (!empty($this->_link)) :
            foreach ($this->_link as &$link) :
                $link->setStateToVote();
            endforeach;
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
        return $this->_hashCode = hash('sha224', ((string) $this) . microtime(false));
    }
}
