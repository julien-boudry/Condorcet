<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

use CondorcetPHP\Condorcet\Console\CondorcetApplication;

$composer_autoload_path = __DIR__ . '/../vendor/autoload.php';

if (is_file($composer_autoload_path)) :
    require_once $composer_autoload_path;
else :
    echo 'Cannot find the vendor directory, have you executed composer install?' . PHP_EOL;
    echo 'See https://getcomposer.org to get Composer.' . PHP_EOL;
    exit(1);
endif;

unset($composer_autoload_path);

CondorcetApplication::run();

