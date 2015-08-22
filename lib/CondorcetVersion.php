<?php
/*
    Condorcet PHP Class, with Schulze Methods and others !

    Version: 0.96

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
namespace Condorcet;

// Generic for many Condorcet Class
trait CondorcetVersion
{
    // Build by Version
    protected $_objectVersion = Condorcet::VERSION;

    public function getObjectVersion ($options = null)
    {
        switch ($options)
        {
            case 'MAJOR':
                $version = explode('.', $this->_objectVersion);
                return $version[0].'.'.$version[1];

            default:
                return $this->_objectVersion;
        }
    }
}
