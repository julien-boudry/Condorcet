<?php
/*
    Part of RANKED PAIRS method Module - From the original Condorcet PHP

    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Algo\Methods\RankedPairs;

use CondorcetPHP\Condorcet\Result;
use CondorcetPHP\Condorcet\Algo\{Method, MethodInterface};

// Ranked Pairs is a Condorcet Algorithm | http://en.wikipedia.org/wiki/Ranked_Pairs
abstract class RankedPairs_Core extends Method implements MethodInterface
{
    // Limits
    public static ?int $MaxCandidates = 60;

    // Ranked Pairs
    protected readonly array $PairwiseSort;
    protected array $Arcs = [];
    protected ?array $Stats = null;
    protected bool $StatsDone = false;


    /////////// PUBLIC ///////////


    // Get the Ranked Pairs ranking
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

        // Make Stats
        $this->Stats['tally'] = $this->PairwiseSort;
        $this->Stats['arcs'] = $this->Arcs;

        // Make Result
        return $this->Result = $this->createResult($this->makeResult());
    }

    // Get the Ranked Pair ranking
    protected function getStats(): array
    {
        $election = $this->getElection();

        if (!$this->StatsDone) {
            foreach ($this->Stats['tally'] as &$Roundvalue) {
                foreach ($Roundvalue as &$Arcvalue) {
                    foreach ($Arcvalue as $key => &$value) {
                        if ($key === 'from' || $key === 'to') {
                            $value = $election->getCandidateObjectFromKey($value)->getName();
                        }
                    }
                }
            }

            foreach ($this->Stats['arcs'] as &$Arcvalue) {
                foreach ($Arcvalue as $key => &$value) {
                    if ($key === 'from' || $key === 'to') {
                        $value = $election->getCandidateObjectFromKey($value)->getName();
                    }
                }
            }

            $this->StatsDone = true;
        }

        return $this->Stats;
    }


    /////////// COMPUTE ///////////


    //:: RANKED PAIRS ALGORITHM. :://

    protected function makeResult(): array
    {
        $election = $this->getElection();

        $result = [];
        $alreadyDone = [];

        $rang = 1;
        while (\count($alreadyDone) < $election->countCandidates()) {
            $winners = $this->getWinners($alreadyDone);

            foreach ($this->Arcs as $ArcKey => $Arcvalue) {
                foreach ($winners as $oneWinner) {
                    if ($Arcvalue['from'] === $oneWinner || $Arcvalue['to'] === $oneWinner) {
                        unset($this->Arcs[$ArcKey]);
                    }
                }
            }

            $result[$rang++] = $winners;
            array_push($alreadyDone, ...$winners);
        }

        return $result;
    }

    protected function getWinners(array $alreadyDone): array
    {
        $winners = [];

        foreach (array_keys($this->getElection()->getCandidatesList()) as $candidateKey) {
            if (!\in_array(needle: $candidateKey, haystack: $alreadyDone, strict: true)) {
                $win = true;
                foreach ($this->Arcs as $ArcValue) {
                    if ($ArcValue['to'] === $candidateKey) {
                        $win = false;
                    }
                }

                if ($win) {
                    $winners[] = $candidateKey;
                }
            }
        }

        return $winners;
    }


    protected function makeArcs(): void
    {
        foreach ($this->PairwiseSort as $newArcsRound) {
            $virtualArcs = $this->Arcs;
            $testNewsArcs = [];

            $newKey = max((empty($highKey = array_keys($virtualArcs)) ? [-1] : $highKey)) + 1;
            foreach ($newArcsRound as $newArc) {
                $virtualArcs[$newKey] = ['from' => $newArc['from'], 'to' => $newArc['to']];
                $testNewsArcs[$newKey] = $virtualArcs[$newKey];
                $newKey++;
            }

            foreach ($this->getArcsInCycle($virtualArcs) as $cycleArcKey) {
                if (\array_key_exists($cycleArcKey, $testNewsArcs)) {
                    unset($testNewsArcs[$cycleArcKey]);
                }
            }

            foreach ($testNewsArcs as $newArc) {
                $this->Arcs[] = $newArc;
            }
        }
    }

    protected function getArcsInCycle(array $virtualArcs): array
    {
        $cycles = [];

        foreach (array_keys($this->getElection()->getCandidatesList()) as $candidateKey) {
            array_push($cycles, ...$this->followCycle(
                startCandidateKey: $candidateKey,
                searchCandidateKey: $candidateKey,
                virtualArcs: $virtualArcs
            ));
        }

        return $cycles;
    }

    protected function followCycle(array $virtualArcs, int $startCandidateKey, int $searchCandidateKey, array &$done = []): array
    {
        $arcsInCycle = [];

        foreach ($virtualArcs as $ArcKey => $ArcValue) {
            if ($ArcValue['from'] === $startCandidateKey) {
                if (\in_array(needle: $ArcKey, haystack: $done, strict: true)) {
                    continue;
                } elseif ($ArcValue['to'] === $searchCandidateKey) {
                    $done[] = $ArcKey;
                    $arcsInCycle[] = $ArcKey;
                } else {
                    $done[] = $ArcKey;
                    array_push(
                        $arcsInCycle,
                        ...$this->followCycle(
                            startCandidateKey: $ArcValue['to'],
                            searchCandidateKey: $searchCandidateKey,
                            virtualArcs: $virtualArcs,
                            done: $done
                        )
                    );
                }
            }
        }

        return $arcsInCycle;
    }

    protected function pairwiseSort(): array
    {
        $pairs = [];

        $i = 0;
        foreach ($this->getElection()->getPairwise() as $candidate_key => $candidate_value) {
            foreach ($candidate_value['win'] as $challenger_key => $challenger_value) {
                if ($challenger_value > $candidate_value['lose'][$challenger_key]) {

                    // Victory
                    $pairs[$i]['from'] = $candidate_key;
                    // Defeat
                    $pairs[$i]['to'] = $challenger_key;

                    $pairs[$i]['win'] = $challenger_value;
                    $pairs[$i]['minority'] = $candidate_value['lose'][$challenger_key];
                    $pairs[$i]['margin'] = $candidate_value['win'][$challenger_key] - $candidate_value['lose'][$challenger_key];

                    $i++;
                }
            }
        }

        usort($pairs, static function (array $a, array $b): int {
            if ($a[static::RP_VARIANT_1] < $b[static::RP_VARIANT_1]) {
                return 1;
            } elseif ($a[static::RP_VARIANT_1] > $b[static::RP_VARIANT_1]) {
                return -1;
            } else { // Equal
                return $a['minority'] <=> $b['minority'];
            }
        });

        $newArcs = [];
        $i = 0;
        $f = true;
        foreach (array_keys($pairs) as $pairsKey) {
            if ($f === true) {
                $newArcs[$i][] = $pairs[$pairsKey];
                $f = false;
            } elseif ($pairs[$pairsKey][static::RP_VARIANT_1] === $pairs[$pairsKey - 1][static::RP_VARIANT_1] && $pairs[$pairsKey]['minority'] === $pairs[$pairsKey - 1]['minority']) {
                $newArcs[$i][] = $pairs[$pairsKey];
            } else {
                $newArcs[++$i][] = $pairs[$pairsKey];
            }
        }

        return $newArcs;
    }
}
