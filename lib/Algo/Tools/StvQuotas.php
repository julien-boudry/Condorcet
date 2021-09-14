<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

/////////// TOOLS FOR MODULAR ALGORITHMS ///////////

namespace CondorcetPHP\Condorcet\Algo\Tools;

use CondorcetPHP\Condorcet\Throwable\CondorcetException;
use CondorcetPHP\Condorcet\Throwable\StvQuotaNotImplementedException;

// Generic for Algorithms
abstract class StvQuotas
{
    public static function getQuota (string $quota, int $votesWeight, int $seats): float
    {
        try {
            return match (strtolower($quota)) {
                'droop quota', 'droop' => floor(( $votesWeight / ($seats + 1) ) + 1),
                'hare quota', 'hare' => $votesWeight / $seats,
                'hagenbach-bischoff quota', 'hagenbach-bischoff' => $votesWeight / ($seats + 1),
                'imperiali quota', 'imperiali' => $votesWeight / ($seats+ 2),
            };
        } catch (\UnhandledMatchError $e) {
            throw new StvQuotaNotImplementedException('"'.$quota.'"');
        }
    }
}
