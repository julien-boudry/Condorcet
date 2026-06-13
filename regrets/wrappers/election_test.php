<?php declare(strict_types=1);
/**
 * Wrapper for Condorcet election methods — refactored for single responsibility
 *
 * REFACTOR LOG:
 * - DECOMPOSITION: Extracted createElectionFromInput() helper — eliminates duplication
 * - COHESION: Grouped election setup logic into one function
 * - NAMING: Renamed generic extraction functions to be self-explanatory
 * - SINGLE RESPONSIBILITY: Each function now does exactly one thing
 *   - createElectionFromInput(): builds election from input data
 *   - computeMethodRanking(): runs a method and extracts ranking
 *   - extractDeterministicRanking(): strips non-deterministic fields from result
 *   - extractDeterministicPairwise(): strips non-deterministic fields from pairwise
 * - REDUCE COUPLING: Method functions no longer know about election construction
 */

require_once __DIR__ . '/../../vendor/autoload.php';
use CondorcetPHP\Condorcet\Election;

// ─── Shared Election Builder ───────────────────────────────────────────────────

/**
 * Create and populate an Election from the standard input format.
 * Single point of truth for election construction — eliminates duplication.
 *
 * @param array $input Must contain 'candidates' (string[]) and 'votes' (string[])
 * @return Election Populated election ready for result computation
 */
function createElectionFromInput(array $input): Election
{
    $election = new Election();
    foreach ($input['candidates'] as $candidate) {
        $election->addCandidate($candidate);
    }
    foreach ($input['votes'] as $vote) {
        $election->addVote($vote);
    }
    return $election;
}

// ─── Output Extraction (Pure Functions) ────────────────────────────────────────

/**
 * Extract deterministic ranking from a Result object.
 * Strips timestamps, version info, and other non-deterministic fields
 * to produce a stable, hashable output.
 *
 * @param array $rankingAsArray The raw rankingAsArray from Result
 * @return array rank => candidate_name mapping (names are strings, sorted for ties)
 */
function extractDeterministicRanking(array $rankingAsArray): array
{
    $result = [];
    foreach ($rankingAsArray as $rank => $candidateOrArray) {
        if (is_array($candidateOrArray)) {
            // Tie: multiple candidates at same rank — sort names for determinism
            $names = array_map(fn($c) => $c->name, $candidateOrArray);
            sort($names);
            $result[(string)$rank] = $names;
        } else {
            $result[(string)$rank] = $candidateOrArray->name;
        }
    }
    return $result;
}

/**
 * Extract deterministic pairwise comparison matrix.
 * Strips object references and non-deterministic data,
 * keeping only the win/lose/null counts for each candidate pair.
 *
 * @param array $explicitPairwise Raw pairwise data from getExplicitPairwise()
 * @return array Structured as [candidate => [opponent => [win, null, lose]]]
 */
function extractDeterministicPairwise(array $explicitPairwise): array
{
    $result = [];
    foreach ($explicitPairwise as $candidateName => $comparisons) {
        $wins = [];
        foreach ($comparisons as $type => $opponents) {
            if (is_array($opponents)) {
                foreach ($opponents as $opponentName => $count) {
                    if (!isset($wins[$opponentName])) {
                        $wins[$opponentName] = [];
                    }
                    $wins[$opponentName][$type] = $count;
                }
            }
        }
        ksort($wins);
        $result[$candidateName] = $wins;
    }
    ksort($result);
    return $result;
}

// ─── Method Runner (Decoupled from Election Construction) ─────────────────────

/**
 * Run a specific voting method and return the deterministic ranking.
 * Decoupled from election construction — takes a pre-built Election.
 *
 * @param Election $election Pre-populated election
 * @param string $methodName Method name as recognized by Condorcet
 * @return array Deterministic ranking
 */
function computeMethodRanking(Election $election, string $methodName): array
{
    $result = $election->getResult($methodName);
    return extractDeterministicRanking($result->rankingAsArray);
}

// ─── Public API: Method-Specific Entry Points ─────────────────────────────────
// Each function: create election → compute method → return deterministic result

function runSchulzeWinning(array $input): array
{
    return computeMethodRanking(createElectionFromInput($input), 'Schulze');
}

function runBordaCount(array $input): array
{
    return computeMethodRanking(createElectionFromInput($input), 'BordaCount');
}

function runCopeland(array $input): array
{
    return computeMethodRanking(createElectionFromInput($input), 'Copeland');
}

function runRankedPairsWinning(array $input): array
{
    return computeMethodRanking(createElectionFromInput($input), 'RankedPairsWinning');
}

function runInstantRunoff(array $input): array
{
    return computeMethodRanking(createElectionFromInput($input), 'Instant-runoff');
}

function runMinimaxWinning(array $input): array
{
    return computeMethodRanking(createElectionFromInput($input), 'Minimax Winning');
}

function runKemenyYoung(array $input): array
{
    return computeMethodRanking(createElectionFromInput($input), 'KemenyYoung');
}

function runSmithSet(array $input): array
{
    return computeMethodRanking(createElectionFromInput($input), 'Smith set');
}

function runPairwiseComparison(array $input): array
{
    $election = createElectionFromInput($input);
    $pairwise = $election->getPairwise();
    return extractDeterministicPairwise($pairwise->getExplicitPairwise());
}

// ─── Utility Functions ─────────────────────────────────────────────────────────

function runCombinationsCount(array $input): int
{
    return \CondorcetPHP\Condorcet\Algo\Tools\Combinations::getPossibleCountOfCombinations($input['count'], $input['length']);
}

function runCombinationsCompute(array $input): array
{
    $result = \CondorcetPHP\Condorcet\Algo\Tools\Combinations::compute($input['values'], $input['length']);
    return $result->toArray();
}
