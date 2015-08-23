<?php
/*
    Condorcet PHP Class, with Schulze Methods and others !

    Version: 0.97

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

}
