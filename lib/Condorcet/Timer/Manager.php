<?php
/*
    Condorcet PHP Class, with Schulze Methods and others !

    Version: 0.94

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
namespace Condorcet\Timer;

use Condorcet\CondorcetException;

class Manager
{
    protected $_globalTimer = 0.0;
    protected $_lastTimer;

    public function addTime ( namespace\Chrono $chrono )
    {
        if ($chrono->getTimerManager() === $this) :
            $this->_lastTimer = $chrono->getInterval(false);
            $this->_globalTimer += $this->_lastTimer;
        else :
            throw new CondorcetException ('Only chrono linked to this Manager can be used');
        endif;
    }

    public function getGlobalTimer ($float = false)
    {
        return ($float) ? $this->_globalTimer : number_format($this->_globalTimer, 5);
    }

    public function getLastTimer ($float = false)
    {
        return ($float || $this->_lastTimer === null) ? $this->_lastTimer : number_format($this->_lastTimer, 5);
    }
}
