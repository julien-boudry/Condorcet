<?php

/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Relations;

use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Throwable\Internal\AlreadyLinkedException;

trait Linkable
{
    private ?\WeakMap $link;

    public function __clone(): void
    {
        $this->destroyAllLink();
    }
    /**
     * Check if this election is linked with this Candidate/Vote object.
     * @api
     * @return mixed True or False.
     * @see Vote::countLinks, Candidate::countLinks, Vote::getLinks, Candidate::getLinks, Vote::haveLink, Candidate::haveLink
     * @param $election Condorcet election to check.
     */
    public function haveLink(
        Election $election
    ): bool {
        $this->initWeakMap();

        return $this->link->offsetExists($election);
    }
    /**
     * Count number of linked election to this object.
     * @api
     * @return mixed Number of linked elections.
     * @see Vote::countLinks, Candidate::countLinks, Vote::getLinks, Candidate::getLinks, Vote::haveLink, Candidate::haveLink
     */
    public function countLinks(): int
    {
        $this->initWeakMap();

        return \count($this->link);
    }
    /**
     * Get elections object linked to this Vote or Candidate object.
     * @api
     * @return mixed Populated by each elections Condorcet object.
     * @see Vote::countLinks, Candidate::countLinks, Vote::getLinks, Candidate::getLinks, Vote::haveLink, Candidate::haveLink
     */
    public function getLinks(): array
    {
        $this->initWeakMap();

        $r = [];

        foreach ($this->link as $k => $v) {
            $r[] = $k;
        }

        return $r;
    }

    // Internal
    # Dot not Overloading ! Do not Use !

    protected function initWeakMap(): void
    {
        $this->link ??= new \WeakMap;
    }

    public function registerLink(Election $election): void
    {
        if (!$this->haveLink($election)) { // haveLink will initWeakmap if necessary
            $this->link->offsetSet($election, true);
        } else {
            throw new AlreadyLinkedException;
        }
    }

    public function destroyLink(Election $election): bool
    {
        if ($this->haveLink($election)) { // haveLink will initWeakmap if necessary
            $this->link->offsetUnset($election);
            return true;
        } else {
            return false;
        }
    }

    protected function destroyAllLink(): void
    {
        $this->link = null;
        $this->initWeakMap();
    }
}
