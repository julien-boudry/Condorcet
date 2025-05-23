<?php declare(strict_types=1);
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

namespace CondorcetPHP\Condorcet\Relations;

use CondorcetPHP\Condorcet\Election;

trait HasElection
{
    /** @var \WeakReference<Election> */
    protected \WeakReference $selfElection;

    /**
     * @internal
     */
    public function getElection(): ?Election
    {
        return $this->selfElection->get(); // @phpstan-ignore-line
    }

    /** @internal */
    public function setElection(Election $election): void
    {
        $this->selfElection = \WeakReference::create($election);
    }
}
