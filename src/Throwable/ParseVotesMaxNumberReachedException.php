<?php declare(strict_types=1);
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

namespace CondorcetPHP\Condorcet\Throwable;

class ParseVotesMaxNumberReachedException extends CondorcetPublicApiException
{
    protected $message = 'The maximum number of votes in a single parse has been reached. Please refer to Election::maxParseIteration to increase it.';
}
