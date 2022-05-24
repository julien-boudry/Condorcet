<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Throwable;

class CandidatesMaxNumberReachedException extends CondorcetPublicApiException
{
    protected $message = "Maximum number of candidates reached";

    public function __construct (string $method = '', int $maxCandidates = 0)
    {
        parent::__construct(
            "The method '$method' " .
            "is configured to accept only " . $maxCandidates . " candidates"
        );
    }
}
