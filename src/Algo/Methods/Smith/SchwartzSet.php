<?php

/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Algo\Methods\Smith;

use CondorcetPHP\Condorcet\Algo\{Method, MethodInterface};
use CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats;
use CondorcetPHP\Condorcet\Result;

/**
 * Schwartz Set algorithm implementation.
 *
 * The Schwartz Set is the union of minimal undominated sets, where a set is undominated
 * if no candidate outside the set defeats (or ties) any candidate in the set.
 */
class SchwartzSet extends Method implements MethodInterface
{
    public const array METHOD_NAME = ['Schwartz set', 'Schwartz'];

    protected readonly array $SchwartzSet;

    #[\Override]
    public function getResult(): Result
    {
        // Cache
        if ($this->Result !== null) {
            return $this->Result;
        }

        // Calculate Schwartz Set
        $this->SchwartzSet = $this->computeSchwartzSet();

        // Create result
        $result = [];

        // All candidates in the Schwartz set are ranked first (equally)
        foreach ($this->SchwartzSet as $candidateKey) {
            $result[1][] = $candidateKey;
        }

        // All other candidates are ranked second (equally)
        $rank = 2;
        foreach (array_keys($this->getElection()->getCandidatesList()) as $candidateKey) {
            if (!\in_array($candidateKey, $this->SchwartzSet, true)) {
                $result[$rank][] = $candidateKey;
            }
        }

        // Create and return the Result object
        return $this->Result = $this->createResult($result);
    }

    /**
     * Compute the Schwartz Set.
     *
     * @return array Array of candidate IDs in the Schwartz Set
     */
    protected function computeSchwartzSet(): array
    {
        $election = $this->getElection();
        $pairwise = $election->getPairwise();
        $candidateList = array_keys($election->getCandidatesList());

        // Create a directed graph representing the defeat relation
        $graph = [];
        $reversedGraph = [];
        foreach ($candidateList as $candidateKey) {
            $graph[$candidateKey] = [];
            $reversedGraph[$candidateKey] = [];
            foreach ($candidateList as $opponentKey) {
                if ($candidateKey !== $opponentKey) {
                    // candidateKey defeats opponentKey if it has more wins than losses
                    if ($pairwise->candidateKeyWinVersus($candidateKey, $opponentKey)) {
                        $graph[$candidateKey][] = $opponentKey;
                        $reversedGraph[$opponentKey][] = $candidateKey;
                    }
                }
            }
        }

        // Find strongly connected components (SCCs) using Kosaraju's algorithm
        $sccs = $this->findStronglyConnectedComponents($graph, $reversedGraph, $candidateList);

        // Create a graph of SCCs
        $sccGraph = [];
        for ($i = 0; $i < \count($sccs); $i++) {
            $sccGraph[$i] = [];
            for ($j = 0; $j < \count($sccs); $j++) {
                if ($i !== $j) {
                    // Check if any candidate in SCC i defeats any candidate in SCC j
                    foreach ($sccs[$i] as $candidateI) {
                        foreach ($sccs[$j] as $candidateJ) {
                            if (\in_array($candidateJ, $graph[$candidateI], true)) {
                                $sccGraph[$i][] = $j;
                                break 2;
                            }
                        }
                    }
                }
            }
        }

        // Find undominated SCCs (sources in the SCC graph)
        $undominatedSccs = [];
        for ($i = 0; $i < \count($sccs); $i++) {
            $isDominated = false;
            for ($j = 0; $j < \count($sccs); $j++) {
                if ($i !== $j && \in_array($i, $sccGraph[$j], true)) {
                    $isDominated = true;
                    break;
                }
            }
            if (!$isDominated) {
                $undominatedSccs[] = $i;
            }
        }

        // Build the Schwartz Set from undominated SCCs
        $schwartzSet = [];
        foreach ($undominatedSccs as $sccIndex) {
            $schwartzSet = array_merge($schwartzSet, $sccs[$sccIndex]);
        }

        return $schwartzSet;
    }

    /**
     * Find strongly connected components using Kosaraju's algorithm
     */
    private function findStronglyConnectedComponents(array $graph, array $reversedGraph, array $nodes): array
    {
        // First DFS to fill the stack
        $stack = [];
        $visited = array_fill_keys($nodes, false);

        foreach ($nodes as $node) {
            if (!$visited[$node]) {
                $this->dfs($graph, $node, $visited, $stack);
            }
        }

        // Second DFS to find SCCs
        $visited = array_fill_keys($nodes, false);
        $sccs = [];

        while (!empty($stack)) {
            $node = array_pop($stack);

            if (!$visited[$node]) {
                $scc = [];
                $this->dfsReverse($reversedGraph, $node, $visited, $scc);
                $sccs[] = $scc;
            }
        }

        // Vérifier si les SCCs peuvent être fusionnées
        return $this->consolidateStrongComponents($graph, $sccs);
    }

    /**
     * Consolidate strongly connected components by checking for cycles between them
     */
    private function consolidateStrongComponents(array $graph, array $sccs): array
    {
        if (\count($sccs) <= 1) {
            return $sccs;
        }

        // Construire un graphe des composantes
        $sccGraph = [];
        for ($i = 0; $i < \count($sccs); $i++) {
            $sccGraph[$i] = [];
            for ($j = 0; $j < \count($sccs); $j++) {
                if ($i !== $j) {
                    foreach ($sccs[$i] as $from) {
                        foreach ($sccs[$j] as $to) {
                            if (\in_array($to, $graph[$from], true)) {
                                $sccGraph[$i][] = $j;
                                break 2;
                            }
                        }
                    }
                }
            }
        }

        // Détecter les cycles entre SCCs
        $merged = false;
        $newSccs = [];
        $visited = array_fill(0, \count($sccs), false);

        for ($i = 0; $i < \count($sccs); $i++) {
            if ($visited[$i]) {
                continue;
            }

            $component = $sccs[$i];
            $visited[$i] = true;

            // Trouver tous les autres composants qui forment un cycle avec celui-ci
            for ($j = 0; $j < \count($sccs); $j++) {
                if ($i !== $j && !$visited[$j] &&
                    \in_array($j, $sccGraph[$i], true) && \in_array($i, $sccGraph[$j], true)) {
                    $component = array_merge($component, $sccs[$j]);
                    $visited[$j] = true;
                    $merged = true;
                }
            }

            $newSccs[] = $component;
        }

        // Si des SCCs ont été fusionnées, vérifier à nouveau
        if ($merged) {
            return $this->consolidateStrongComponents($graph, $newSccs);
        }

        return $sccs;
    }

    /**
     * DFS for the first pass of Kosaraju's algorithm
     */
    private function dfs(array $graph, int $node, array &$visited, array &$stack): void
    {
        $visited[$node] = true;

        foreach ($graph[$node] as $neighbor) {
            if (!$visited[$neighbor]) {
                $this->dfs($graph, $neighbor, $visited, $stack);
            }
        }

        $stack[] = $node;
    }

    /**
     * DFS for the second pass of Kosaraju's algorithm
     */
    private function dfsReverse(array $graph, int $node, array &$visited, array &$scc): void
    {
        $visited[$node] = true;
        $scc[] = $node;

        foreach ($graph[$node] as $neighbor) {
            if (!$visited[$neighbor]) {
                $this->dfsReverse($graph, $neighbor, $visited, $scc);
            }
        }
    }

    #[\Override]
    protected function getStats(): BaseMethodStats
    {
        $stats = new BaseMethodStats(closed: false);

        return $stats->setEntry(
            'schwartz_set',
            array_map(fn(int $k): string => $this->getElection()->getCandidateObjectFromKey($k)->name, $this->SchwartzSet)
        )->close();
    }
}
