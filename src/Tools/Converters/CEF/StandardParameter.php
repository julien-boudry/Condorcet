<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tools\Converters\CEF;

use CondorcetPHP\Condorcet\Candidate;
use CondorcetPHP\Condorcet\Utils\CondorcetUtil;

enum StandardParameter: String
{
    case CANDIDATES = 'candidates';
    case SEATS = 'number of seats';
    case IMPLICIT = 'implicit ranking';
    case WEIGHT = 'weight allowed';

    public function formatValue(string $parameterValue): mixed
    {
        return match ($this) {
            self::CANDIDATES => array_map(static fn($c): Candidate => new Candidate($c), CondorcetUtil::prepareParse($parameterValue, false)),
            self::SEATS => (int) $parameterValue,
            self::IMPLICIT, self::WEIGHT => CondorcetElectionFormat::boolParser($parameterValue),
        };
    }
}
