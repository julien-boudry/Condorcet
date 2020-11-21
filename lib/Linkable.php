<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet;

use CondorcetPHP\Condorcet\CondorcetDocAttributes\{Description, Examples, FunctionReturn, PublicAPI, Related};
use CondorcetPHP\Condorcet\Throwable\{CondorcetException, CondorcetInternalException};

trait Linkable
{
    private array $_link = [];

    public function __destruct ()
    {
        $this->destroyAllLink();
    }

    public function __clone () : void
    {
        $this->destroyAllLink();
    }

    #[PublicAPI]
    public function haveLink (Election $election) : bool
    {
        return \in_array(needle: $election, haystack: $this->_link, strict: true);
    }

    #[PublicAPI]
    public function countLinks () : int
    {
        return \count($this->_link);
    }

    #[PublicAPI]
    public function getLinks () : ?array
    {
        return !empty($this->_link) ? $this->_link : null;
    }

    // Internal
        # Dot not Overloading ! Do not Use !

    public function registerLink (Election $election) : void
    {
        if ( !$this->haveLink($election) ) :
            $this->_link[] = $election;
        else :
            throw new CondorcetInternalException ('Link is already registered.');
        endif;
    }

    public function destroyLink (Election $election) : bool
    {
        $destroyKey = \array_search(needle: $election, haystack: $this->_link, strict: true);

        if ($destroyKey !== false) :
            unset($this->_link[$destroyKey]);
            return true;
        else :
            return false;
        endif;
    }

    protected function destroyAllLink () : void
    {
        $this->_link = [];
    }
}
