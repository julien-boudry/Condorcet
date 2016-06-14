<?php
/*
    Condorcet PHP Class, with Schulze Methods and others !

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace Condorcet;

use Condorcet\Condorcet;

// Generic for many Condorcet Class
trait CondorcetVersion
{
    // Build by Version
    protected $_objectVersion = Condorcet::VERSION;

    public function getObjectVersion (string $options = null) : string
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
