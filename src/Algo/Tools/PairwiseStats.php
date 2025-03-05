<?php

/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

declare(strict_types=1);

/////////// TOOLS FOR MODULAR ALGORITHMS ///////////

namespace CondorcetPHP\Condorcet\Algo\Tools;

use CondorcetPHP\Condorcet\Algo\Pairwise\Pairwise;

// Generic for Algorithms
abstract class PairwiseStats
{
    public static function PairwiseComparison(Pairwise $pairwise): array
    {
        $comparison = [];

        foreach ($pairwise as $candidate_key => $candidate_data) {
            $comparison[$candidate_key]['win'] = 0;
            $comparison[$candidate_key]['null'] = 0;
            $comparison[$candidate_key]['lose'] = 0;
            $comparison[$candidate_key]['balance'] = 0;
            $comparison[$candidate_key]['sum_defeat_margin'] = 0;
            $comparison[$candidate_key]['worst_pairwise_defeat_winning'] = 0;
            $comparison[$candidate_key]['worst_pairwise_defeat_margin'] = null;
            $comparison[$candidate_key]['worst_pairwise_opposition'] = 0;

            foreach ($candidate_data['win'] as $opponentKey => $opponentLose) {
                $defeat_margin = $candidate_data['lose'][$opponentKey] - $opponentLose;

                // Worst margin defeat
                if ($comparison[$candidate_key]['worst_pairwise_defeat_margin'] === null || $comparison[$candidate_key]['worst_pairwise_defeat_margin'] < $defeat_margin) {
                    $comparison[$candidate_key]['worst_pairwise_defeat_margin'] = $defeat_margin;
                }

                // Worst pairwise opposition
                if ($comparison[$candidate_key]['worst_pairwise_opposition'] < $candidate_data['lose'][$opponentKey]) {
                    $comparison[$candidate_key]['worst_pairwise_opposition'] = $candidate_data['lose'][$opponentKey];
                }


                // for each Win, null, Lose
                if ($opponentLose > $candidate_data['lose'][$opponentKey]) {
                    $comparison[$candidate_key]['win']++;
                    $comparison[$candidate_key]['balance']++;
                } elseif ($opponentLose === $candidate_data['lose'][$opponentKey]) {
                    $comparison[$candidate_key]['null']++;
                } else {
                    $comparison[$candidate_key]['lose']++;
                    $comparison[$candidate_key]['balance']--;

                    $comparison[$candidate_key]['sum_defeat_margin'] += $defeat_margin;

                    // Worst winning defeat
                    if ($comparison[$candidate_key]['worst_pairwise_defeat_winning'] < $candidate_data['lose'][$opponentKey]) {
                        $comparison[$candidate_key]['worst_pairwise_defeat_winning'] = $candidate_data['lose'][$opponentKey];
                    }
                }
            }
        }

        return $comparison;
    }
}
