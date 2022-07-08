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

use CondorcetPHP\Condorcet\Election;

class MultipleRoundsSystem extends Majority_Core
{
    // Method Name
    public const METHOD_NAME = ['Multiple Rounds System', 'MultipleRoundsSystem', 'Multiple Rounds', 'Majority', 'Majority System', 'Two-round system', 'second ballot', 'runoff voting', 'ballotage', 'two round system', 'two round', 'two rounds', 'two rounds system', 'runoff voting'];

    // Mod
    protected static int $optionMAX_ROUND = 2;
    protected static int $optionTARGET_NUMBER_OF_CANDIDATES_FOR_THE_NEXT_ROUND = 2;
    protected static int $optionNUMBER_OF_TARGETED_CANDIDATES_AFTER_EACH_ROUND = 0;

    public function __construct(Election $mother)
    {
        $this->_maxRound = self::$optionMAX_ROUND;
        $this->_targetNumberOfCandidatesForTheNextRound = self::$optionTARGET_NUMBER_OF_CANDIDATES_FOR_THE_NEXT_ROUND;
        $this->_numberOfTargetedCandidatesAfterEachRound = self::$optionNUMBER_OF_TARGETED_CANDIDATES_AFTER_EACH_ROUND;

        parent::__construct($mother);
    }
}
