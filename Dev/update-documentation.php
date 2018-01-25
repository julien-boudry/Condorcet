<?php
declare(strict_types=1);

    $cmd = '../vendor/bin/condorcet-doc "'.__DIR__.'/Documentation"';
    $cmd = str_replace('/', DIRECTORY_SEPARATOR, $cmd);
    $cmd = str_replace(str_replace('/',DIRECTORY_SEPARATOR,'/Dev/Documentation'), str_replace('/',DIRECTORY_SEPARATOR,'/Documentation'), $cmd);

    var_dump($cmd);

    shell_exec($cmd);