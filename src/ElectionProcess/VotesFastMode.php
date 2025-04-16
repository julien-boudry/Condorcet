<?php declare(strict_types=1);
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

namespace CondorcetPHP\Condorcet\ElectionProcess;

/**
 * Manage Candidates for Election class
 */
enum VotesFastMode
{
    case NONE;
    case BYPASS_CANDIDATES_CHECK;
    case BYPASS_RANKING_UPDATE;

}
