<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Throwable;

class AlgorithmWithoutRankingFeatureException extends CondorcetPublicApiException
{
    protected $message = "This algortihm can't provide a full ranking (but only Winner and Loser)";
}
