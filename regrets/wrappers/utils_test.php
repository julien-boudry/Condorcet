<?php declare(strict_types=1);
/**
 * Wrapper for pure utility functions from Condorcet
 */

require_once __DIR__ . '/../../vendor/autoload.php';
use CondorcetPHP\Condorcet\Algo\Tools\Combinations;

function runCombinationsCount(array $input): int
{
    return Combinations::getPossibleCountOfCombinations($input['count'], $input['length']);
}

function runCombinationsCompute(array $input): array
{
    $result = Combinations::compute($input['values'], $input['length']);
    return $result->toArray();
}

function runPermutationsCount(array $input): int
{
    use CondorcetPHP\Condorcet\Algo\Tools\Permutations;
    return Permutations::getPossibleCountOfPermutations($input['count']);
}
