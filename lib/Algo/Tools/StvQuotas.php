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

// Generic for Algorithms
abstract class StvQuotas
{
    public static function getQuota (string $quota, int $weight, int $seats) : float
    {
        try {
            return match (strtolower($quota)) {
                'droop quota', 'droop' => floor(( $weight / ($seats + 1) ) + 1),
                'hare quota', 'hare' => $weight / $seats,
                'hagenbach-bischoff quota', 'hagenbach-bischoff' => $weight / ($seats + 1),
                'imperiali quota', 'imperiali' => $weight / ($seats+ 2),
            };
        } catch (\UnhandledMatchError $e) {
            throw new CondorcetException(103);
        }
    }
}
