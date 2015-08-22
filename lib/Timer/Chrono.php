<?php
/*
    Condorcet PHP Class, with Schulze Methods and others !

    Version: 0.96

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
namespace Condorcet\Timer;

use Condorcet\CondorcetVersion;

class Chrono
{
    use CondorcetVersion;

    protected $_manager;
    protected $_start;

    public function __construct (namespace\Manager $timer)
    {
        $this->_manager = $timer;
        $this->resetStart();
    }

    public function __destruct ()
    {
        $this->_manager->addTime($this);
    }

    public function getStart ($startPoint = false)
    {
        return $this->_start;
    }

    public function getTimerManager () {
        return $this->_manager;
    }

    protected function resetStart ($pro = null) {
        $this->_start = ($pro) ? $pro : microtime(true);
    }
}
