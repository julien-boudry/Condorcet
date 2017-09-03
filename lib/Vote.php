<?php
/*
    Condorcet PHP Class, with Schulze Methods and others !

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

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

        public function rewind() : void {
            $this->position = 1;
        }

        public function current() {
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

    private $_ranking = [];

    private $_tags = [];

    private $_hashCode;

        ///

    public function __construct ($ranking, $tags = null, $ownTimestamp = false)
    {
        $this->setRanking($ranking, $ownTimestamp);
        $this->addTags($tags);
    }

    public function __sleep () : array
    {
        $this->position = 1;

        return array_keys(get_object_vars($this));
    }

    public function __clone ()
    {
        $this->destroyAllLink();
        $this->setHashCode();
    }

    public function __toString () : string {
        return $this->getSimpleRanking();
    }

    public function getHashCode () : string {
        return $this->_hashCode;
    }

        ///

    // GETTERS

    public function getRanking () : array
    {
        if (!empty($this->_ranking))
        {
            return end($this->_ranking)['ranking'];
        }
        else
            { return null; }
    }

    public function getHistory () : array
    {
        return $this->_ranking;
    }


    public function getTags () : array
    {
        return $this->_tags;
    }

    public function getCreateTimestamp () : float
    {
        return $this->_ranking[0]['timestamp'];
    }

    public function getTimestamp () : float
    {
        return end($this->_ranking)['timestamp'];
    }

    public function countRankingCandidates () : int
    {
        return end($this->_ranking)['counter'];
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

    public function getContextualVote (Election $election, bool $string = false) : array
    {
        if (!$this->haveLink($election)) :
            throw new CondorcetException(22);
        endif;

        $ranking = $this->getRanking();
        $present = $this->getAllCandidates();
        $newRanking = [];
        $candidates_list = $election->getCandidatesList(false);

        $nextRank = 1;
        $countContextualCandidate = 0;

        foreach ($ranking as $CandidatesInRanks) :
            foreach ($CandidatesInRanks as $candidate) :
                if ( $election->existCandidateId($candidate, true) ) :
                    $newRanking[$nextRank][] = $candidate;
                    $countContextualCandidate++;
                endif;
            endforeach;

            if (isset($newRanking[$nextRank])) :
                $nextRank++;
            endif;
        endforeach;

        if ($countContextualCandidate < $election->countCandidates()) :
            $last_rank = [];
            foreach ($candidates_list as $oneCandidate) :
                if (!in_array($oneCandidate, $present, true)) :
                    $last_rank[] = $oneCandidate;
                endif;
            endforeach;

            $newRanking[] = $last_rank;
        endif;

        if ($string) :
            foreach ($newRanking as &$rank) :
                foreach ($rank as &$oneCandidate) :
                    $oneCandidate = (string) $oneCandidate;
                endforeach;
            endforeach;
        endif;

        return $newRanking;
    }

    public function getSimpleRanking (bool $context = false) : string
    {
        $ranking = ($context) ? $this->getContextualVote($context) : $this->getRanking();

        foreach ($ranking as &$rank) :
            $rank = implode('=',$rank);
        endforeach;

        return implode('>', $ranking);
    }


    // SETTERS

    public function setRanking ($rankingCandidate, $ownTimestamp = false) : bool
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
            catch (CondorcetException $e)
            {                
                array_pop($this->_ranking);

                throw new CondorcetException(18);
            }
        }

        $this->setHashCode();
        return true;
    }

        private function formatRanking (&$ranking) : int
        {
            if (is_string($ranking))
                { $ranking = self::convertVoteInput($ranking); }

            if (!is_array($ranking)) :
                throw new CondorcetException(5);
            endif;

            $ranking = array_filter($ranking, function ($key) {
                return is_numeric($key);
            }, ARRAY_FILTER_USE_KEY);

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
                    if (!in_array($Candidate, $list_candidate)) :
                        $list_candidate[] = $Candidate;
                    else : 
                        throw new CondorcetException(5);
                    endif;

                endforeach;
            }

            return $counter;
        }

        // From a string like 'A>B=C=H>G=T>Q'
        public static function convertVoteInput (string $formula) : array
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


    public function removeCandidates (array $candidatesList) : bool
    {
        $ranking = $this->getRanking();

        if ($ranking === null) :
            return false;
        endif;

        $rankingCandidate = $this->getAllCandidates();

        $canRemove = false;
        foreach ($candidatesList as $oneCandidate) :
            if (in_array($oneCandidate, $rankingCandidate, false)) :
                $canRemove = true;
                break;
            endif;
        endforeach;

        if (!$canRemove) :
            return false;
        endif;

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

        return true;
    }


    public function addTags ($tags) : bool
    {
        if (is_object($tags) || is_bool($tags))
            { throw new CondorcetException(17); }

        $tags = self::tagsConvert($tags);

        if (empty($tags)) :
            return false;
        endif;


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

        $this->setHashCode();

        return true;
    }

    public function removeTags ($tags) : array
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

        $this->setHashCode();
        return $rm;
    }

        public static function tagsConvert ($tags) : ?array
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

/////////// INTERNAL ///////////

    private function archiveRanking ($ranking, int $counter, $ownTimestamp) : void
    {
        $this->_ranking[] = array(
                                    'ranking' => $ranking,
                                    'timestamp' => ($ownTimestamp !== false) ? (float) $ownTimestamp : microtime(true),
                                    'counter' => $counter
                                    );

        $this->rewind();
    }

    private function setHashCode () : string
    {
        return $this->_hashCode = hash('sha224', ((string) $this) . microtime(false));
    }
}
