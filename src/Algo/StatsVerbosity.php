<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Algo;

// Manage Candidates for Election class
enum StatsVerbosity: int
{
    case NONE = 0; // No stats
    case LOW = 100; // Minal Stats
    case STD = 200; // Standards Stats
    case HIGH = 300; // High Level of stats
    case FULL = 400; // Fulls stats
    case DEBUG = 500; // More full
}
