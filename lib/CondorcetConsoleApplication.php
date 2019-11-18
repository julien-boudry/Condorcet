<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet;

require __DIR__.'/../vendor/autoload.php';

use Symfony\Component\Console\Application;
use CondorcetPHP\Condorcet\Condorcet;
use CondorcetPHP\Condorcet\ConsoleCommands\ElectionCommand;

// New App
$application = new Application('Condorcet',Condorcet::getVersion());

// ... register commands

    // Election commande
    $electionCommand = new ElectionCommand;
    $application->add($electionCommand);
    $application->setDefaultCommand($electionCommand->getName(), false);

// Run
$application->run();
