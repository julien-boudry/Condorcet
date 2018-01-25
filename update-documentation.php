<?php
    $cmd = 'vendor/bin/condorcet-doc "'.__DIR__.'/Documentation"';
    $cmd = str_replace('/', DIRECTORY_SEPARATOR, $cmd);

    shell_exec($cmd);