<?php
/*
    Condorcet PHP Class, with Schulze Methods and others !

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

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

/////////// Array Access - Specials improvements ///////////

    public function offsetSet($offset, $value)
    {
        if ($value instanceof Vote) :
            parent::offsetSet($offset,$value);
            $this->setStateToVote();
        else :
            throw new CondorcetException (0,'Value must be an instanceof Condorcet\\Vote');
        endif;
    }

    public function offsetUnset($offset)
    {
        if (parent::offsetUnset($offset)) {
            $this->setStateToVote();
        }
    }

/////////// Internal Election related methods ///////////

    protected function setStateToVote ()
    {
        foreach ($this->_link as &$element) {
            $element->setStateToVote();
        }
    }

/////////// Public specific methods ///////////

    public function getVoteKey (Vote $vote) {
        // Return False if using with Bdd storing. Futur: Throw a PHP7 Error.
        return array_search($vote, $this->_Container, true);
    }

    // Get the votes registered list
    public function getVotesList ($tag = null, $with = true)
    {
        if ($tag === null)
        {
            return $this->getFullDataSet();
        }
        else
        {
            $tag = Vote::tagsConvert($tag);
            if ($tag === null)
                {$tag = [];}

            $search = [];

            foreach ($this as $key => $value)
            {
                $noOne = true;
                foreach ($tag as $oneTag)
                {
                    if ( ( $oneTag === $key ) || in_array($oneTag, $value->getTags(),true) ) :
                        if ($with) :
                            $search[$key] = $value;
                            break;
                        else :
                            $noOne = false;
                        endif;
                    endif;
                }

                if (!$with && $noOne)
                    { $search[$key] = $value;}
            }

            return $search;
        }
    }
}
