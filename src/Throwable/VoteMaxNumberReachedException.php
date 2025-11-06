<?php declare(strict_types=1);
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

namespace CondorcetPHP\Condorcet\Throwable;

/**
 * Exception thrown when the maximum number of votes is reached.
 */
class VoteMaxNumberReachedException extends CondorcetPublicApiException
{
    protected $message = 'The maximal number of votes for the method is reached';
}
