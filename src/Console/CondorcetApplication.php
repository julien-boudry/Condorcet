<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Console;

use Symfony\Component\Console\Application as SymfonyConsoleApplication;
use CondorcetPHP\Condorcet\Condorcet;
use CondorcetPHP\Condorcet\Console\Commands\ElectionCommand;

abstract class CondorcetApplication
{
    public static SymfonyConsoleApplication $SymfonyConsoleApplication;

    /**
     * @infection-ignore-all
     */
    public static function run(): void
    {
        // Run
        self::create() && self::$SymfonyConsoleApplication->run();
    }

    public static function create(): bool
    {
        // New App
        self::$SymfonyConsoleApplication = new SymfonyConsoleApplication('Condorcet', Condorcet::getVersion());

        // ... register commands

        // Election commande
        $command = new ElectionCommand;
        self::$SymfonyConsoleApplication->add($command);
        self::$SymfonyConsoleApplication->setDefaultCommand($command->getName(), false);

        return true;
    }
}
