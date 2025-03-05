<?php

/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Relations;

use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{Description, FunctionParameter, FunctionReturn, PublicAPI, Related};
use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Throwable\Internal\AlreadyLinkedException;

trait Linkable
{
    private ?\WeakMap $link;

    public function __clone(): void
    {
        $this->destroyAllLink();
    }

    #[PublicAPI]
    #[Description('Check if this election is linked with this Candidate/Vote object.')]
    #[FunctionReturn('True or False.')]
    #[Related('Vote::countLinks', 'Candidate::countLinks', 'Vote::getLinks', 'Candidate::getLinks', 'Vote::haveLink', 'Candidate::haveLink')]
    public function haveLink(
        #[FunctionParameter('Condorcet election to check')]
        Election $election
    ): bool {
        $this->initWeakMap();

        return $this->link->offsetExists($election);
    }

    #[PublicAPI]
    #[Description('Count number of linked election to this object.')]
    #[FunctionReturn('Number of linked elections.')]
    #[Related('Vote::countLinks', 'Candidate::countLinks', 'Vote::getLinks', 'Candidate::getLinks', 'Vote::haveLink', 'Candidate::haveLink')]
    public function countLinks(): int
    {
        $this->initWeakMap();

        return \count($this->link);
    }

    #[PublicAPI]
    #[Description('Get elections object linked to this Vote or Candidate object.')]
    #[FunctionReturn('Populated by each elections Condorcet object.')]
    #[Related('Vote::countLinks', 'Candidate::countLinks', 'Vote::getLinks', 'Candidate::getLinks', 'Vote::haveLink', 'Candidate::haveLink')]
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
