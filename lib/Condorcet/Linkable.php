<?php
/*
    Condorcet PHP Class, with Schulze Methods and others !

    Version: 0.93

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
namespace Condorcet ;

// Generic for Candidate & Vote Class
trait Linkable
{
    private $_link = array() ;

    public function __clone ()
    {
        $this->destroyAllLink();
    }

    public function __debugInfo ()
    {
        $var = get_object_vars($this);

        foreach ($var['_link'] as $key => $oneLink)
        {
            $var['_link'][$key] = "Object \Condorcet\Condorcet => " . sha1(spl_object_hash($oneLink));
        }

        return $var;
    }

    public function haveLink (namespace\Condorcet &$election)
    {
        return in_array($election, $this->_link, true);
    }

    public function countLinks ()
    {
        return count($this->_link);
    }

    public function getLinks ()
    {
        return (!empty($this->_link)) ? $this->_link : NULL ;
    }

    // Internal
        # Dot not Overloading ! Do not Use !

    public function registerLink (namespace\Condorcet &$election)
    {
        if (array_search($election, $this->_link, true) === false)
            { $this->_link[] = $election ; }
        else
            { throw new CondorcetException; }
    }

    public function destroyLink (namespace\Condorcet &$election)
    {
        $destroyKey = array_search($election, $this->_link, true);

        if ($destroyKey !== false)
        {
            unset($this->_link[$destroyKey]);
            return true ;
        }
        else
            { throw new CondorcetException; }
    }

    protected function destroyAllLink ()
    {
        $this->_link = array();
    }
}
