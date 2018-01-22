<?php
/*
    Condorcet PHP Class, with Schulze Methods and others !

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace Condorcet;

use Condorcet\CondorcetException;
use Condorcet\Election;

trait Linkable
{
    private $_link = [];

    public function __clone ()
    {
        $this->destroyAllLink();
    }

    public function haveLink (Election $election) : bool
    {
        return in_array($election, $this->_link, true);
    }

    public function countLinks () : int
    {
        return count($this->_link);
    }

    public function getLinks () : ?array
    {
        return (!empty($this->_link)) ? $this->_link : null;
    }

    // Internal
        # Dot not Overloading ! Do not Use !

    public function registerLink (Election $election) : void
    {
        if (array_search($election, $this->_link, true) === false)
            { $this->_link[] = $election; }
        else
            { throw new CondorcetException; }
    }

    public function destroyLink (Election $election) : bool
    {
        $destroyKey = array_search($election, $this->_link, true);

        if ($destroyKey !== false) :
            unset($this->_link[$destroyKey]);
            return true;
        else :
            return false;
        endif;
    }

    protected function destroyAllLink () : void
    {
        $this->_link = [];
    }
}
