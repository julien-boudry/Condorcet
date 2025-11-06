<?php declare(strict_types=1);
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

namespace CondorcetPHP\Condorcet\Throwable;

/**
 * Exception thrown when attempting to add a duplicate candidate.
 */
class CandidateExistsException extends CondorcetPublicApiException
{
    protected $message = 'This candidate already exists';
}
