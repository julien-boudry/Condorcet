<?php

declare(strict_types=1);

namespace Tests\src\Console;

use Tests\CondorcetTestCase;

abstract class ConsoleTestCase extends CondorcetTestCase
{
    public const DEBIAN_INPUT_FILE = __DIR__ . '/../Tools/Converters/DebianData/leader2020_tally.txt';
    public const DAVIDHILL_INPUT_FILE = __DIR__ . '/../Tools/Converters/TidemanData/A77.HIL';
    public const OUTPUT_FILE = __DIR__ . '/Commands/files/out.temp';
}
