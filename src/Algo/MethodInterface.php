<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Algo;

use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{Description, Example, FunctionReturn, InternalModulesAPI, PublicAPI, Related};
use CondorcetPHP\Condorcet\{Election, Result};

// Interface with the aim of verifying the good modular implementation of algorithms.
#[InternalModulesAPI]
interface MethodInterface
{
    public function __construct(Election $election);

    public function setElection(Election $election): void;
    public function getResult(): Result;
}
