<?php

/*
    Part of FTPT method Module - From the original Condorcet PHP

    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Algo\Methods\Majority;

class FirstPastThePost extends MajorityCore
{
    // Method Name
    public const array METHOD_NAME = ['First-past-the-post voting', 'First-past-the-post', 'First Choice', 'FirstChoice', 'FPTP', 'FPP', 'SMP', 'FTPT'];

    // Mod
    protected int $maxRound = 1;
    protected int $targetNumberOfCandidatesForTheNextRound = 2;
    protected int $numberOfTargetedCandidatesAfterEachRound = 0;
}
