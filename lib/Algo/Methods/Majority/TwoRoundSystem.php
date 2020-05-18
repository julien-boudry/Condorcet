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

class TwoRoundSystem extends Majority_Core
{
    // Method Name
    public const METHOD_NAME = ['Two-round system', 'second ballot', 'runoff voting', 'ballotage', 'two round system', 'two round', 'two rounds', 'two rounds system', 'runoff voting'];

    // Mod
    public const MAX_ROUND = 2;
    public const TARGET_NUMBER_OF_CANDIDATES_FOR_THE_NEXT_ROUND = 2;
    public const CHANGING_THE_NUMBER_OF_TARGETED_CANDIDATES_AFTER_EACH_ROUND = 0;
}
