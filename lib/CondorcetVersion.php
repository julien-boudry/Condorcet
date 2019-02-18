<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP;



// Generic for many Condorcet Class
trait CondorcetVersion
{
    // Build by Version
    protected $_objectVersion = Condorcet::VERSION;

    public function getObjectVersion (string $options = null) : string
    {
        switch ($options) :
            case 'MAJOR':
                $version = explode('.', $this->_objectVersion);
                return $version[0].'.'.$version[1];

            default:
                return $this->_objectVersion;
        endswitch;
    }
}
