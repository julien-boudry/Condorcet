<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Timer;

use CondorcetPHP\Condorcet\CondorcetDocAttributes\{Description, Examples, FunctionReturn, PublicAPI, Related};
use CondorcetPHP\Condorcet\CondorcetVersion;
use CondorcetPHP\Condorcet\Throwable\CondorcetException;

class Manager
{
    use CondorcetVersion;

    protected float $_globalTimer = 0.0;
    protected ?float $_lastTimer = null;
    protected ?float $_lastChronoTimestamp = null;
    protected ?float $_startDeclare = null;
    protected array $_history = [];

    public function addTime ( Chrono $chrono ) : void
    {
        if ($chrono->getTimerManager() === $this) :
            if ($this->_lastChronoTimestamp === null && $chrono->getStart() !== $this->_startDeclare) :
                return;
            endif;

            $m = \microtime(true);

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
            throw new CondorcetException (0, 'Only chrono linked to this Manager can be used');
        endif;
    }

    public function getGlobalTimer () : float
    {
        return $this->_globalTimer;
    }

    public function getLastTimer () : float
    {
        return $this->_lastTimer;
    }

    #[PublicAPI]
    public function getHistory () : array
    {
        return $this->_history;
    }

    public function startDeclare (Chrono $chrono) : void
    {
        if ($this->_startDeclare === null) :
            $this->_startDeclare = $chrono->getStart();
        endif;
    }
}
