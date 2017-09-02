<?php
/*
    Condorcet PHP Class, with Schulze Methods and others !

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);


namespace Condorcet\DataManager;

use Condorcet\DataManager\ArrayManager;
use Condorcet\CondorcetException;
use Condorcet\Election;
use Condorcet\Vote;

class VotesManager extends ArrayManager
{
    protected $_link = [];

/////////// Magic ///////////

    public function __construct (Election $election = null)
    {
        if ($election !== null) :
            $this->_link[] = $election;
        endif;

        parent::__construct();
    }

/////////// Data CallBack for external drivers ///////////

    public function getDataContextObject ()
    {
        $context = new Class {
            public $election;

            public function dataCallBack ($data)
            {
                $vote = new Vote ($data);
                $this->election->checkVoteCandidate($vote);
                $vote->registerLink($this->election);

                return $vote;
            }
        };

        $context->election = $this->_link[0] ?? null;

        return $context;
    }

/////////// Array Access - Specials improvements ///////////

    public function offsetSet($offset, $value) : void
    {
        if ($value instanceof Vote) :
            parent::offsetSet($offset,$value);
            $this->setStateToVote();
        else :
            throw new CondorcetException (0,'Value must be an instanceof Condorcet\\Vote');
        endif;
    }

    public function offsetUnset($offset) : bool
    {
        if (parent::offsetUnset($offset)) :
            $this->setStateToVote();
            return true;
        endif;
        return false;
    }

/////////// Internal Election related methods ///////////

    protected function setStateToVote () : void
    {
        foreach ($this->_link as &$element) :
            $element->setStateToVote();
        endforeach;
    }

/////////// Public specific methods ///////////

    public function getVoteKey (Vote $vote) {
        // Return False if using with Bdd storing. Futur: Throw a PHP7 Error.
        return array_search($vote, $this->_Container, true);
    }

    // Get the votes registered list
    public function getVotesList ($tag = null, bool $with = true) : array
    {
        if ($tag === null) :
            return $this->getFullDataSet();
        else :
            $tag = Vote::tagsConvert($tag);
            if ($tag === null) :
                $tag = [];
            endif;

            $search = [];

            foreach ($this as $key => $value) :
                $noOne = true;
                foreach ($tag as $oneTag) :
                    if ( ( $oneTag === $key ) || in_array($oneTag, $value->getTags(),true) ) :
                        if ($with) :
                            $search[$key] = $value;
                            break;
                        else :
                            $noOne = false;
                        endif;
                    endif;
                endforeach;

                if (!$with && $noOne) :
                    $search[$key] = $value;
                endif;
            endforeach;

            return $search;
        endif;
    }
}
