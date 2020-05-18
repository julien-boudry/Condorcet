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

use CondorcetPHP\Condorcet\Algo\Methods\Majority\Majority_Core;

class FirstPastThePost extends Majority_Core
{
    // Method Name
    public const METHOD_NAME = ['First-past-the-post voting', 'First-past-the-post', 'First Choice', 'FirstChoice', 'FPTP', 'FPP', 'SMP', 'FTPT'];

    // Mod
    public const MAX_ROUND = 1;
    public const TARGET_NUMBER_OF_CANDIDATES_FOR_THE_NEXT_ROUND = 2;
    public const CHANGING_THE_NUMBER_OF_TARGETED_CANDIDATES_AFTER_EACH_ROUND = 0;
}
