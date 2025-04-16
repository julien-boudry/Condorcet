<?php declare(strict_types=1);
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

namespace CondorcetPHP\Condorcet\Throwable;

class CandidatesMaxNumberReachedException extends MethodLimitReachedException
{
    protected $message = 'Maximum number of candidates reached';

    public function __construct(string $method, int $maxCandidates)
    {
        parent::__construct($method, "{$this->message}: The method '{$method}' is configured to accept only {$maxCandidates} candidates");
    }
}
