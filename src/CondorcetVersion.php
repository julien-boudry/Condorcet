<?php

/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

declare(strict_types=1);

namespace CondorcetPHP\Condorcet;

use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{FunctionParameter};

// Generic for many Condorcet Class
trait CondorcetVersion
{
    // Build by Version
    protected string $objectVersion = Condorcet::VERSION;
    /**
     * Get the Condorcet PHP version who built this object. Usefull pour serializing Election.
     * @api Candidate, Election, Result, Vote, Algo\Pairwise, DataManager\VotesManager, Timer\Manager
     * @return mixed Condorcet PHP version.
     * @see static Condorcet::getVersion
     * @param $major true will return 2.0 and false will return 2.0.0.
     */
    public function getObjectVersion(
        #[FunctionParameter('true will return 2.0 and false will return 2.0.0')]
        bool $major = false
    ): string {
        if ($major === true) {
            $version = explode('.', $this->objectVersion);
            return $version[0] . '.' . $version[1];
        } else {
            return $this->objectVersion;
        }
    }
}
