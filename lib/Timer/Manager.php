<?php
/*
    Condorcet PHP Class, with Schulze Methods and others !

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace Condorcet\Timer;

use Condorcet\Timer\Chrono;
use Condorcet\CondorcetException;
use Condorcet\CondorcetVersion;

class Manager
{
    use CondorcetVersion;

    protected $_globalTimer = 0.0;
    protected $_lastTimer;
    protected $_lastChronoTimestamp = null;
    protected $_startDeclare = null;
    protected $_history = [];

    public function addTime ( Chrono $chrono )
    {
        if ($this->_lastChronoTimestamp === null && $chrono->getStart() !== $this->_startDeclare) :
            return;
        endif;

        if ($chrono->getTimerManager() === $this) :
            $m = microtime(true);

            if ( $this->_lastChronoTimestamp > $chrono->getStart() ) :
                $c = $this->_lastChronoTimestamp;
            else :
                $c = $chrono->getStart();
                $this->_history[] = [   'role' => $chrono->getRole(),
                                        'process_in' => ($m - $c),
                                        'timer_start' => $c,
                                        'timer_end' => $m
                                    ];
            endif;

            $this->_globalTimer += ($m - $c);

            $this->_lastTimer = ($m - $chrono->getStart());
            $this->_lastChronoTimestamp = $m;
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

    public function getHistory ()
    {
        return $this->_history;
    }

    public function startDeclare (Chrono $chrono)
    {
        if ($this->_startDeclare === null) {
            $this->_startDeclare = $chrono->getStart();
        }
    }
}
