<?php

declare(strict_types=1);

namespace Tests\src\Tools\Randomizers;

use Tests\CondorcetTestCase;

abstract class RandomizerTestCase extends CondorcetTestCase
{
    public const SEED = 'CondorcetSeed';

    public const CANDIDATE_SET_1 = [
        'Candidate1', 'Candidate2', 'Candidate3', 'Candidate4', 'Candidate5', 'Candidate6', 'Candidate7', 'Candidate8', 'Candidate9',
    ];

    public const CANDIDATE_SET_2 = [
        'Candidate1', 'Candidate2', 'Candidate3',
    ];

    public const CANDIDATE_SET_3 = [
        'Candidate1', 'Candidate2', 'Candidate3', ['Candidate4'],
    ];
}
