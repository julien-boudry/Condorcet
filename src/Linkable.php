<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

declare(strict_types=1);

namespace CondorcetPHP\Condorcet;

use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{Description, FunctionParameter, FunctionReturn, PublicAPI, Related};
use CondorcetPHP\Condorcet\Throwable\Internal\CondorcetInternalException;

trait Linkable
{
    private ?\WeakMap $_link;

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

        return $this->_link->offsetExists($election);
    }

    #[PublicAPI]
    #[Description('Count number of linked election to this object.')]
    #[FunctionReturn('Number of linked elections.')]
    #[Related('Vote::countLinks', 'Candidate::countLinks', 'Vote::getLinks', 'Candidate::getLinks', 'Vote::haveLink', 'Candidate::haveLink')]
    public function countLinks(): int
    {
        $this->initWeakMap();

        return \count($this->_link);
    }

    #[PublicAPI]
    #[Description('Get elections object linked to this Vote or Candidate object.')]
    #[FunctionReturn('Populated by each elections Condorcet object.')]
    #[Related('Vote::countLinks', 'Candidate::countLinks', 'Vote::getLinks', 'Candidate::getLinks', 'Vote::haveLink', 'Candidate::haveLink')]
    public function getLinks(): \WeakMap
    {
        $this->initWeakMap();

        return $this->_link;
    }

    // Internal
    # Dot not Overloading ! Do not Use !

    protected function initWeakMap(): void
    {
        $this->_link ??= new \WeakMap;
    }

    public function registerLink(Election $election): void
    {
        if (!$this->haveLink($election)) { // haveLink will initWeakmap if necessary
            $this->_link->offsetSet($election, true);
        } else {
            throw new CondorcetInternalException('Link is already registered.');
        }
    }

    public function destroyLink(Election $election): bool
    {
        if ($this->haveLink($election)) { // haveLink will initWeakmap if necessary
            $this->_link->offsetUnset($election);
            return true;
        } else {
            return false;
        }
    }

    protected function destroyAllLink(): void
    {
        $this->_link = null;
        $this->initWeakMap();
    }
}
