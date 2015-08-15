<?php
/*
    Condorcet PHP Class, with Schulze Methods and others !

    Version: 0.94

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
namespace Condorcet;

use Condorcet\Condorcet;
use Condorcet\CondorcetException;

class Candidate
{
    use namespace\Linkable;

    private $_name;

        ///

    public function __construct ($name)
    {
        $this->setName($name);
    }

    public function __toString ()
    {
        return $this->getName();
    }

        ///

    // SETTERS

    public function setName ($name)
    {
        $name = trim((string) $name);

        if (mb_strlen($name) > Election::MAX_LENGTH_CANDIDATE_ID )
            { throw new CondorcetException(1, $name); }

        if (!$this->checkName($name))
            { throw new CondorcetException(19, $name); }

        $this->_name[] = array('name' => $name, 'timestamp' => microtime(true));

        return $this->getName();
    }

    // GETTERS

    public function getName ($full = false)
    {
        return ($full) ? end($this->_name) : end($this->_name)['name'];
    }

    public function getHistory ()
    {
        return $this->_name;
    }

    public function getCreateTimestamp ()
    {
        return $this->_name[0]['timestamp'];
    }

    public function getTimestamp ()
    {
        return end($this->_name)['timestamp'];
    }

        ///

    // INTERNAL

    private function checkName ($name)
    {
        foreach ($this->_link as &$link)
        {
            if (!$link->canAddCandidate($name))
                { return false; }
        }

        return true;
    }
}
