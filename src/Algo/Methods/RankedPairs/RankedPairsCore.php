<?php declare(strict_types=1);
/*
    Part of RANKED PAIRS method Module - From the original Condorcet PHP

    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

namespace CondorcetPHP\Condorcet\Algo\Methods\RankedPairs;

use CondorcetPHP\Condorcet\Result;
use CondorcetPHP\Condorcet\Algo\{Method, MethodInterface, StatsVerbosity};
use CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats;
use CondorcetPHP\Condorcet\Throwable\Internal\CondorcetInternalError;

/**
 * Ranked Pairs is a Condorcet Algorithm | http://en.wikipedia.org/wiki/Ranked_Pairs.
 *
 * @internal
 */
abstract class RankedPairsCore extends Method implements MethodInterface
{
    protected const RP_VARIANT VARIANT = RP_VARIANT::UNDEFINED;

    // Limits
    public static ?int $MaxCandidates = 500;

    // Ranked Pairs
    /** @var array<int, array<int, array<string, int>>> Pairs sorted by strength, grouped by rounds of equal strength. */
    protected array $PairwiseSort = [];
    /** @var array<int, array<int, bool>> Lock decision for each pair, mirroring the structure. */
    protected array $LockedPairs = [];
    /** @var array<int, array<int, true>> Adjacency of the locked graph, as winner key to defeated keys. */
    protected array $Adjacency = [];


    /////////// PUBLIC ///////////

    // Get the Ranked Pairs ranking
    #[\Override]
    public function getResult(): Result
    {
        // Cache
        if ($this->Result !== null) {
            return $this->Result;
        }

        // -------

        // Sort pairwise
        $this->PairwiseSort = $this->pairwiseSort();

        // Ranking calculation
        $this->makeArcs();

        // Make Result (stats are built at this point)
        $this->Result = $this->createResult($this->makeResult());

        // Free intermediate computation data, potentially large on big elections
        $this->PairwiseSort = [];
        $this->LockedPairs = [];
        $this->Adjacency = [];

        return $this->Result;
    }

    // Get the Ranked Pair stats
    protected function getStats(): BaseMethodStats
    {
        $election = $this->getElectionOrFail();

        $stats = new BaseMethodStats(closed: false);

        $withFullPairs = $election->statsVerbosity->value >= StatsVerbosity::HIGH->value;

        $lockedPairsCount = 0;
        $rejectedPairsCount = 0;
        $pairs = [];

        foreach ($this->PairwiseSort as $roundKey => $onePairsRound) {
            $roundNumber = $roundKey + 1;

            foreach ($onePairsRound as $pairKey => $onePair) {
                $locked = $this->LockedPairs[$roundKey][$pairKey];
                $locked ? $lockedPairsCount++ : $rejectedPairsCount++;

                if ($withFullPairs) {
                    $pairs[$roundNumber][] = [
                        'winner' => $election->getCandidateObjectFromKey($onePair['from'])->name,
                        'loser' => $election->getCandidateObjectFromKey($onePair['to'])->name,
                        'strength' => $onePair[static::VARIANT->name],
                        'locked' => $locked,
                    ];
                }
            }
        }

        $stats['rounds'] = \count($this->PairwiseSort);
        $stats['lockedPairsCount'] = $lockedPairsCount;
        $stats['rejectedPairsCount'] = $rejectedPairsCount;

        if ($withFullPairs) {
            $stats['pairs'] = $pairs;
        }

        return $stats->close();
    }


    /////////// COMPUTE ///////////


    //:: RANKED PAIRS ALGORITHM. :://

    protected function makeResult(): array
    {
        $election = $this->getElectionOrFail();

        // Kahn topological sort over the locked graph, ranking all in-degree 0 candidates together
        $inDegree = array_fill_keys(array_keys($election->getCandidatesList()), 0);

        foreach ($this->Adjacency as $successors) {
            foreach (array_keys($successors) as $successorKey) {
                $inDegree[$successorKey]++;
            }
        }

        $result = [];
        $remaining = $election->countCandidates();

        $rang = 1;
        while ($remaining > 0) {
            $winners = [];
            foreach ($inDegree as $candidateKey => $degree) {
                if ($degree === 0) {
                    $winners[] = $candidateKey;
                }
            }

            foreach ($winners as $oneWinner) {
                unset($inDegree[$oneWinner]);

                foreach (array_keys($this->Adjacency[$oneWinner] ?? []) as $successorKey) {
                    if (isset($inDegree[$successorKey])) {
                        $inDegree[$successorKey]--;
                    }
                }
            }

            $result[$rang++] = $winners;
            $remaining -= \count($winners);
        }

        return $result;
    }

    protected function makeArcs(): void
    {
        $adjacency = [];

        foreach ($this->PairwiseSort as $roundKey => $newArcsRound) {
            $virtualAdjacency = $adjacency;
            foreach ($newArcsRound as $newArc) {
                $virtualAdjacency[$newArc['from']][$newArc['to']] = true;
            }

            // An arc belongs to a cycle if and only if both of its endpoints are in
            // the same strongly connected component of the virtual graph.
            $components = $this->findStronglyConnectedComponents($virtualAdjacency);

            foreach ($newArcsRound as $pairKey => $newArc) {
                $locked = $components[$newArc['from']] !== $components[$newArc['to']];
                $this->LockedPairs[$roundKey][$pairKey] = $locked;

                if ($locked) {
                    $adjacency[$newArc['from']][$newArc['to']] = true;
                }
            }
        }

        $this->Adjacency = $adjacency;
    }

    /**
     * Tarjan algorithm.
     *
     * @param array<int, array<int, true>> $adjacency
     *
     * @return array<int, int> Candidate key to component id.
     */
    protected function findStronglyConnectedComponents(array $adjacency): array
    {
        $nextIndex = 0;
        $nextComponentId = 0;
        $indexes = [];
        $lowLinks = [];
        $stack = [];
        $onStack = [];
        $components = [];

        $strongConnect = static function (int $v) use (&$strongConnect, &$nextIndex, &$nextComponentId, &$indexes, &$lowLinks, &$stack, &$onStack, &$components, &$adjacency): void {
            $indexes[$v] = $lowLinks[$v] = $nextIndex++;
            $stack[] = $v;
            $onStack[$v] = true;

            foreach (array_keys($adjacency[$v] ?? []) as $w) {
                if (!isset($indexes[$w])) {
                    $strongConnect($w);
                    $lowLinks[$v] = min($lowLinks[$v], $lowLinks[$w]);
                } elseif (isset($onStack[$w])) {
                    $lowLinks[$v] = min($lowLinks[$v], $indexes[$w]);
                }
            }

            if ($lowLinks[$v] === $indexes[$v]) {
                while (($w = array_pop($stack)) !== null) {
                    unset($onStack[$w]);
                    $components[$w] = $nextComponentId;

                    if ($w === $v) {
                        break;
                    }
                }

                $nextComponentId++;
            }
        };

        foreach (array_keys($this->getElectionOrFail()->getCandidatesList()) as $candidateKey) {
            if (!isset($indexes[$candidateKey])) {
                $strongConnect($candidateKey);
            }
        }

        return $components;
    }

    protected function pairwiseSort(): array
    {
        $pairwise = $this->getElectionOrFail()->getPairwise();

        /** @var array<int, array<string, int>> */
        $pairs = [];

        $i = 0;
        foreach ($pairwise as $candidate_key => $candidate_value) {
            foreach ($candidate_value['win'] as $challenger_key => $challenger_value) {
                if ($pairwise->candidateKeyWinVersus($candidate_key, $challenger_key)) {
                    // Victory
                    $pairs[$i]['from'] = $candidate_key;
                    // Defeat
                    $pairs[$i]['to'] = $challenger_key;

                    $pairs[$i][static::VARIANT->name] = match (static::VARIANT) {
                        RP_VARIANT::WINNING => $challenger_value,
                        RP_VARIANT::MARGIN => $pairwise->compareCandidatesKeys($candidate_key, $challenger_key),
                        RP_VARIANT::MINORITY => $candidate_value['lose'][$challenger_key],
                        RP_VARIANT::UNDEFINED => throw new CondorcetInternalError('Ranked Pairs variant is undefined'),
                    };
                    $pairs[$i][RP_VARIANT::MINORITY->name] = $candidate_value['lose'][$challenger_key];

                    $i++;
                }
            }
        }

        usort($pairs, static function (array $a, array $b): int {
            if ($a[static::VARIANT->name] < $b[static::VARIANT->name]) {
                return 1;
            } elseif ($a[static::VARIANT->name] > $b[static::VARIANT->name]) {
                return -1;
            } else { // Equal
                return $a[RP_VARIANT::MINORITY->name] <=> $b[RP_VARIANT::MINORITY->name];
            }
        });

        $newArcs = [];
        $i = 0;
        $f = true;
        foreach (array_keys($pairs) as $pairsKey) {
            if ($f) {
                $newArcs[$i][] = $pairs[$pairsKey];
                $f = false;
            } elseif ($pairs[$pairsKey][static::VARIANT->name] === $pairs[$pairsKey - 1][static::VARIANT->name] && $pairs[$pairsKey][RP_VARIANT::MINORITY->name] === $pairs[$pairsKey - 1][RP_VARIANT::MINORITY->name]) {
                $newArcs[$i][] = $pairs[$pairsKey];
            } else {
                $newArcs[++$i][] = $pairs[$pairsKey];
            }
        }

        return $newArcs;
    }
}
