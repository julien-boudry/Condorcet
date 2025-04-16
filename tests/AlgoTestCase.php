<?php declare(strict_types=1);

namespace Tests;

use CondorcetPHP\Condorcet\Tools\Converters\{DavidHillFormat, DebianFormat};

abstract class AlgoTestCase extends CondorcetTestCase
{
    public static DavidHillFormat $tidemanA77;
    public static DebianFormat $debian2020;
    public static DebianFormat $debian2007;
    public static DebianFormat $debian2006;
}
