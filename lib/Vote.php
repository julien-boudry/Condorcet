<?php
/*
    Condorcet PHP Class, with Schulze Methods and others !

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
namespace Condorcet;

use Condorcet\CondorcetException;
use Condorcet\CondorcetVersion;
use Condorcet\Candidate;
use Condorcet\Election;
use Condorcet\Linkable;


class Vote implements \Iterator
{
    use Linkable, CondorcetVersion;

    // Implement Iterator

        private $position = 1;

        public function rewind() {
            $this->position = 1;
        }

        public function current() {
            return $this->getRanking()[$this->position];
        }

        public function key() {
            return $this->position;
        }

        public function next() {
            ++$this->position;
        }

        public function valid() {
            return isset($this->getRanking()[$this->position]);
        }

    // Vote

    private $_ranking = [];

    private $_tags = [];
    private $_id;

        ///

    public function __construct ($ranking, $tags = null, $ownTimestamp = false)
    {
        $this->setRanking($ranking, $ownTimestamp);
        $this->addTags($tags);
    }

    public function __sleep ()
    {
        $this->position = 1;

        return array_keys(get_object_vars($this));
    }

        ///

    // GETTERS

    public function getRanking ()
    {
        if (!empty($this->_ranking))
        {
            return end($this->_ranking)['ranking'];
        }
        else
            { return null; }
    }

    public function getHistory ()
    {
        return $this->_ranking;
    }


    public function getTags ()
    {
        return $this->_tags;
    }

    public function getCreateTimestamp ()
    {
        return $this->_ranking[0]['timestamp'];
    }

    public function getTimestamp ()
    {
        return end($this->_ranking)['timestamp'];
    }

    public function countRankingCandidates ()
    {
        return end($this->_ranking)['counter'];
    }

    public function getAllCandidates ()
    {
        $list = [];

        foreach ($this->getRanking() as $rank) :
            foreach ($rank as $oneCandidate) :
                $list[] = $oneCandidate;
            endforeach;
        endforeach;

        return $list;
    }

    public function getContextualVote (Election &$election, $string = false)
    {
        if (!$this->haveLink($election))
            { return false; }

        $ranking = $this->getRanking();
        $present = $this->getAllCandidates();

        if (count($present) < $election->countCandidates())
        {
            $last_rank = [];
            foreach ($election->getCandidatesList(false) as $oneCandidate)
            {
                if (!in_array($oneCandidate->getName(), $present))
                {
                    $last_rank[] = $oneCandidate;
                }
            }

            $ranking[] = $last_rank;
        }

        if ($string)
        {
            foreach ($ranking as &$rank)
            {
                foreach ($rank as &$oneCandidate)
                {
                    $oneCandidate = (string) $oneCandidate;
                }
            }
        }

        return $ranking;
    }


    // SETTERS

    public function setRanking ($rankingCandidate, $ownTimestamp = false)
    {
        // Timestamp
        if ($ownTimestamp !== false) :
            if (!is_numeric($ownTimestamp)) :
                throw new CondorcetException(21);
            elseif (!empty($this->_ranking) && $this->getTimestamp() >= $ownTimestamp) :
                throw new CondorcetException(21);
            endif;
        endif;

        // Ranking
        $candidateCounter = $this->formatRanking($rankingCandidate);

        $this->archiveRanking($rankingCandidate, $candidateCounter, $ownTimestamp);

        if (!empty($this->_link))
        {
            try {
                foreach ($this->_link as &$link)
                {
                    $link->prepareModifyVote($this);
                }
            }
            catch (CondorcetException $e) {
                
                array_pop($this->_ranking);

                throw new CondorcetException(18);
            }
        }

        return $this->getRanking();
    }

        private function formatRanking (&$ranking)
        {
            if (is_string($ranking))
                { $ranking = self::convertVoteInput($ranking); }

            if (!is_array($ranking)) :
                throw new CondorcetException(5);
            endif;


            ksort($ranking);
            
            $i = 1; $vote_r = [];
            foreach ($ranking as &$value)
            {
                if ( !is_array($value) )
                {
                    $vote_r[$i] = array($value);
                }
                else
                {
                    $vote_r[$i] = $value;
                }

                $i++;
            }

            $ranking = $vote_r;

            $counter = 0;
            $list_candidate = [];
            foreach ($ranking as &$line)
            {
                foreach ($line as &$Candidate) :
                    if ( !($Candidate instanceof Candidate) ) :
                        $Candidate = new Candidate ($Candidate);
                    endif;

                    $counter++;

                // Check Duplicate

                    // Check objet reference AND check candidates name
                    if (!in_array($Candidate, $list_candidate, true) && !in_array($Candidate, $list_candidate)) :
                        $list_candidate[] = $Candidate;
                    else : throw new CondorcetException(5); endif;

                endforeach;
            }

            return $counter;
        }

        // From a string like 'A>B=C=H>G=T>Q'
        public static function convertVoteInput ($formula)
        {
            $vote = explode('>', $formula);

            foreach ($vote as &$rank_vote)
            {
                $rank_vote = explode('=', $rank_vote);

                // Del space at start and end
                foreach ($rank_vote as &$value)
                {
                    $value = trim($value);
                }
            }

            return $vote;
        }


    public function removeCandidates (array $candidatesList)
    {
        $ranking = $this->getRanking();

        if ($ranking === null) :
            return false;
        endif;

        $rankingCandidate = $this->getAllCandidates();

        $canRemove = false;
        foreach ($candidatesList as $oneCandidate) {
            if (in_array($oneCandidate, $rankingCandidate, false)) :
                $canRemove = true;
                break;
            endif;
        }

        if (!$canRemove) :
            return false;
        endif;

        $newRanking = [];

        foreach ($ranking as $rankingKey => &$rank) :
            foreach ($rank as $oneRankKey => $oneRankValue) :
                if (in_array($oneRankValue, $candidatesList, false)) :
                    unset($rank[$oneRankKey]);
                endif;
            endforeach;

            if (empty($rank)) :
                unset($ranking[$rankingKey]);
            endif;
        endforeach;

        $this->setRanking($ranking);
    }


    public function addTags ($tags)
    {
        if (is_object($tags) || is_bool($tags))
            { throw new CondorcetException(17); }

        $tags = self::tagsConvert($tags);

        if (empty($tags))
            { return $this->getTags(); }


        foreach ($tags as $key => $tag)
        {
            if (is_numeric($tag)) :
                throw new CondorcetException(17);
            elseif (in_array($tag, $this->_tags, true)) :
                unset($tags[$key]);
            endif;
        }

        foreach ($tags as $tag)
        {
            $this->_tags[] = $tag;
        }

        return $this->getTags();
    }

    public function removeTags ($tags)
    {
        if (is_object($tags) || is_bool($tags))
            { throw new CondorcetException(17); }

        $tags = self::tagsConvert($tags);

        if (empty($tags))
            { return []; }


        $rm = [];
        foreach ($tags as $key => $tag)
        {
            $tagK = array_search($tag, $this->_tags, true);

            if ($tagK === false) :
                unset($tags[$key]);
            else :
                $rm[] = $this->_tags[$tagK];
                unset($this->_tags[$tagK]);
            endif;
        }

        return $rm;
    }

        public static function tagsConvert ($tags)
        {
            if (empty($tags))
                { return null; }

            // Make Array
            if (!is_array($tags))
            {
                $tags = explode(',', $tags);
            }

            // Trim tags
            foreach ($tags as $key => &$oneTag)
            {
                $oneTag = (!ctype_digit($oneTag)) ? trim($oneTag) : intval($oneTag);

                if (empty($oneTag) || is_object($oneTag) || is_bool($oneTag))
                    {unset($tags[$key]);}
            }

            return $tags;
        }


        ///

    // INTERNAL

        private function archiveRanking ($ranking, $counter, $ownTimestamp)
        {
            $this->_ranking[] = array(
                                        'ranking' => $ranking,
                                        'timestamp' => ($ownTimestamp !== false) ? (float) $ownTimestamp : microtime(true),
                                        'counter' => $counter
                                        );

            $this->rewind();
        }
}
