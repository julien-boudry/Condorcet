<?php declare(strict_types=1);
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

namespace CondorcetPHP\Condorcet\Relations;

use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Throwable\Internal\AlreadyLinkedException;

trait Linkable
{
    /** @var \WeakMap<Election,true> */
    private ?\WeakMap $link;

    public function __clone(): void
    {
        $this->destroyAllLink();
    }
    /**
     * Check if this election is linked with this Candidate/Vote object.
     * @api
     * @see Vote::countLinks(), Candidate::countLinks(), Vote::getLinks(), Candidate::getLinks(), Vote::haveLink(), Candidate::haveLink()
     * @param $election Condorcet election to check.
     * @return bool True or False.
     */
    public function haveLink(
        Election $election
    ): bool {
        $this->initWeakMap();

        return $this->link->offsetExists($election);
    }
    /**
     * Count number of linked elections to this object.
     * @api
     * @return int Number of linked elections.
     * @see Vote::countLinks(), Candidate::countLinks(), Vote::getLinks(), Candidate::getLinks(), Vote::haveLink(), Candidate::haveLink()
     */
    public function countLinks(): int
    {
        $this->initWeakMap();

        return \count($this->link);
    }
    /**
     * Get election objects linked to this Vote or Candidate object.
     * @api
     * @return array<Election> Array containing linked Condorcet election objects.
     * @see Vote::countLinks(), Candidate::countLinks(), Vote::getLinks(), Candidate::getLinks(), Vote::haveLink(), Candidate::haveLink()
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
    # Do not override! Do not use!

    protected function initWeakMap(): void
    {
        $this->link ??= new \WeakMap; // @phpstan-ignore assign.propertyType
    }

    /** @internal */
    public function registerLink(Election $election): void
    {
        if (!$this->haveLink($election)) { // haveLink will initWeakmap if necessary
            $this->link->offsetSet($election, true);
        } else {
            throw new AlreadyLinkedException;
        }
    }

    /** @internal */
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
