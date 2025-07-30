<?php declare(strict_types=1);
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

namespace CondorcetPHP\Condorcet\Algo;

use CondorcetPHP\Condorcet\{Election, Result};

// Interface with the aim of verifying the good modular implementation of algorithms.
/**
 * @internal
 */
interface MethodInterface
{
    public const array METHOD_NAME = [];

    public function __construct(Election $election);

    public function setElection(Election $election): void;

    public function getResult(): Result;
}
