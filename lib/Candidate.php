<?php
/*
    Condorcet PHP Class, with Schulze Methods and others !

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace Condorcet;

use Condorcet\Condorcet;
use Condorcet\CondorcetException;
use Condorcet\CondorcetVersion;
use Condorcet\Linkable;

class Candidate
{
    use Linkable, CondorcetVersion;

    private $_name = [];
    private $_provisional = false;

        ///

    public function __construct (string $name)
    {
        $this->setName($name);
    }

    public function __toString () : string
    {
        return $this->getName();
    }

        ///

    // SETTERS

    public function setName (string $name) : bool
    {
        $name = trim($name);

        if (mb_strlen($name) > Election::MAX_LENGTH_CANDIDATE_ID )
            { throw new CondorcetException(1, $name); }

        if (!$this->checkName($name))
            { throw new CondorcetException(19, $name); }

        $this->_name[] =  [ 'name' => $name, 'timestamp' => microtime(true) ];

        return true;
    }

    public function setProvisionalState (bool $provisional) : bool
    {
        $this->_provisional = $provisional;
        return true;
    }

    // GETTERS

    public function getName () : string
    {
        return end($this->_name)['name'];
    }

    public function getHistory () : array
    {
        return $this->_name;
    }

    public function getCreateTimestamp () : float
    {
        return $this->_name[0]['timestamp'];
    }

    public function getTimestamp () : float
    {
        return end($this->_name)['timestamp'];
    }

    public function getProvisionalState () : bool
    {
        return $this->_provisional;
    }

        ///

    // INTERNAL

    private function checkName (string $name) : bool
    {
        foreach ($this->_link as &$link)
        {
            if (!$link->canAddCandidate($name))
                { return false; }
        }

        return true;
    }
}
