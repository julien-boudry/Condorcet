<?php
/*
    Condorcet PHP Class, with Schulze Methods and others !

    Version: 0.94

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
namespace Condorcet\Timer;

class Chrono
{
    protected $_timer;
    protected $_start;

    public function __construct (namespace\Manager $timer)
    {
        $this->_timer = $timer;
        $this->resetStart();
    }

    public function __destruct ()
    {
        $this->_timer->addTime($this);
    }

    public function getInterval ($reset = true)
    {
        $m = microtime(true);
        $r = $m - $this->_start;

        if ($reset) { 
            $this->resetStart($m);
        }

        return $r;
    }

    public function getTimerManager () {
        return $this->_timer;
    }

    protected function resetStart ($pro = null) {
        $this->_start = ($pro) ? $pro : microtime(true);
    }
}
