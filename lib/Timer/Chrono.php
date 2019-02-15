<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace Condorcet\Timer;


use Condorcet\CondorcetVersion;

class Chrono
{
    use CondorcetVersion;

    protected Manager $_manager;
    protected float $_start;
    protected ?string $_role = null;

    public function __construct (Manager $timer, $role = null)
    {
        $this->_manager = $timer;
        $this->setRole($role);        
        $this->resetStart();
        $this->managerStartDeclare();
    }

    public function __destruct () {
        $this->_manager->addTime($this);
    }

    public function getStart () : float {
        return $this->_start;
    }

    public function getTimerManager () : Manager {
        return $this->_manager;
    }

    protected function resetStart () : void {
        $this->_start = microtime(true);
    }

    public function getRole () : ?string {
        return $this->_role;
    }

    public function setRole (?string $role) : void {
        $this->_role = $role;
    }

    protected function managerStartDeclare () : void {
        $this->_manager->startDeclare( $this );
    }
}
