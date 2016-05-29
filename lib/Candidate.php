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

    public function setName (string $name)
    {
        $name = trim($name);

        if (mb_strlen($name) > Election::MAX_LENGTH_CANDIDATE_ID )
            { throw new CondorcetException(1, $name); }

        if (!$this->checkName($name))
            { throw new CondorcetException(19, $name); }

        $this->_name[] = array('name' => $name, 'timestamp' => microtime(true));

        return $this->getName();
    }

    // GETTERS

    public function getName (bool $full = false)
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

    private function checkName (string $name)
    {
        foreach ($this->_link as &$link)
        {
            if (!$link->canAddCandidate($name))
                { return false; }
        }

        return true;
    }
}
