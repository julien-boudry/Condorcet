<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet;


// Generic for many Condorcet Class
trait CondorcetVersion
{
    // Build by Version
    protected string $_objectVersion = Condorcet::VERSION;

    public function getObjectVersion (bool $major = false) : string
    {
        if ($major === true) :
            $version = explode('.', $this->_objectVersion);
            return $version[0].'.'.$version[1];
        else :
            return $this->_objectVersion;
        endif;
    }
}
