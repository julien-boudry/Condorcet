<?php
/*
    Condorcet PHP Class, with Schulze Methods and others !

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

namespace Condorcet\DataManager;

use Condorcet\DataManager\ArrayManager;
use Condorcet\CondorcetException;
use Condorcet\Vote;

class VotesManager extends ArrayManager
{
    public function offsetSet($offset, $value) {
        if ($value instanceof Vote) :
            parent::offsetSet($offset,$value);
        else :
            throw new CondorcetException (0,'Value must be an instanceof Condorcet\\Vote');
        endif;
    }

    public function getVoteKey (Vote $vote) {
        return array_search($vote, $this->_Container, true);
    }

    // Get the votes registered list
    public function getVotesList ($tag = null, $with = true)
    {
        if ($tag === null)
        {
            return $this->getArray();
        }
        else
        {
            $tag = Vote::tagsConvert($tag);
            if ($tag === null)
                {$tag = [];}

            $search = [];

            foreach ($this->_Container as $key => $value)
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
