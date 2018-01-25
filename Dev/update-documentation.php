<?php
declare(strict_types=1);

    $call = str_replace('/', DIRECTORY_SEPARATOR, '../vendor/bin/condorcet-doc');
    $arg = substr(__DIR__, 0, strlen(__DIR__) - 4);
    $arg .= DIRECTORY_SEPARATOR.'Documentation';

    $cmd = $call.' "'.$arg.'"';

    shell_exec($cmd);