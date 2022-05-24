<?php
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Benchmarks;

ini_set('memory_limit','51200M');

class MethodsProportionalBench extends MethodsNonProportionalBench
{
    public bool $IS_A_PROPORTIONAL_BENCH = true;

    public array $numberOfCandidates = [3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20];
}