<?php
/*
    Condorcet PHP Class, with Schulze Methods and others !

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);



/////////// TOOLS FOR MODULAR ALGORITHMS ///////////

namespace Condorcet\Algo\Tools;

use Condorcet\Algo\Pairwise;

// Generic for Algorithms
abstract class PairwiseStats
{
    public static function PairwiseComparison (Pairwise $pairwise) : array
    {
        $comparison = [];

        foreach ($pairwise as $candidate_key => $candidate_data) :

            $comparison[$candidate_key]['win'] = 0;
            $comparison[$candidate_key]['null'] = 0;
            $comparison[$candidate_key]['lose'] = 0;
            $comparison[$candidate_key]['balance'] = 0;
            $comparison[$candidate_key]['sum_defeat_margin'] = 0;
            $comparison[$candidate_key]['worst_pairwise_defeat_winning'] = 0;
            $comparison[$candidate_key]['worst_pairwise_defeat_margin'] = null;
            $comparison[$candidate_key]['worst_pairwise_opposition'] = 0;

            foreach ($candidate_data['win'] as $opponenent['key'] => $opponenent['lose']) :

                $defeat_margin = $candidate_data['lose'][$opponenent['key']] - $opponenent['lose'];

                // Worst margin defeat
                if ($comparison[$candidate_key]['worst_pairwise_defeat_margin'] === null || $comparison[$candidate_key]['worst_pairwise_defeat_margin'] < $defeat_margin) :

                    $comparison[$candidate_key]['worst_pairwise_defeat_margin'] = $defeat_margin;

                endif;

                // Worst pairwise opposition
                if ($comparison[$candidate_key]['worst_pairwise_opposition'] < $candidate_data['lose'][$opponenent['key']]) :

                    $comparison[$candidate_key]['worst_pairwise_opposition'] = $candidate_data['lose'][$opponenent['key']];

                endif;


                // for each Win, null, Lose
                if ( $opponenent['lose'] > $candidate_data['lose'][$opponenent['key']] ) :

                    $comparison[$candidate_key]['win']++;
                    $comparison[$candidate_key]['balance']++;

                elseif ( $opponenent['lose'] === $candidate_data['lose'][$opponenent['key']] ) :

                    $comparison[$candidate_key]['null']++;

                else :

                    $comparison[$candidate_key]['lose']++;
                    $comparison[$candidate_key]['balance']--;

                    $comparison[$candidate_key]['sum_defeat_margin'] += $defeat_margin;

                    // Worst winning defeat
                    if ($comparison[$candidate_key]['worst_pairwise_defeat_winning'] < $candidate_data['lose'][$opponenent['key']]) :

                        $comparison[$candidate_key]['worst_pairwise_defeat_winning'] = $candidate_data['lose'][$opponenent['key']];

                    endif;

                endif;

            endforeach;

        endforeach;

        return $comparison;
    }

    public static function PairwiseSort (Pairwise $pairwise) : array
    {
        $score = [];  

        $i = 0;
        foreach ($pairwise as $candidate_key => $candidate_value) :
            foreach ($candidate_value['win'] as $challenger_key => $challenger_value) :

                if ($challenger_value > $candidate_value['lose'][$challenger_key]) :

                    $score[$i]['victory'] = $candidate_key;
                    $score[$i]['defeat'] = $challenger_key;

                    $score[$i]['win'] = $challenger_value;
                    $score[$i]['minority'] = $candidate_value['lose'][$challenger_key];
                    $score[$i]['margin'] = $candidate_value['win'][$challenger_key] - $candidate_value['lose'][$challenger_key];

                    $i++;

                endif;

            endforeach;
        endforeach;

        usort($score, function ($a, $b) : int {
            if ($a['win'] < $b['win']) : return 1;
            elseif ($a['win'] > $b['win']) : return -1;
            elseif ($a['win'] === $b['win']) :
                if ($a['minority'] > $b['minority']) :
                    return 1;
                elseif ($a['minority'] < $b['minority']) :
                    return -1;
                elseif ($a['minority'] === $b['minority']) :
                    return 0;
                endif;
            endif;
        });

        $newArcs = [];
        $i = 0;
        $f = true;
        foreach ($score as $scoreKey => $scoreValue) :
            if ($f === true) :
                $newArcs[$i][] = $score[$scoreKey];
                $f = false;
            elseif ($score[$scoreKey]['win'] === $score[$scoreKey - 1]['win'] && $score[$scoreKey]['minority'] === $score[$scoreKey - 1]['minority']) :
                $newArcs[$i][] = $score[$scoreKey];
            else :
                $newArcs[++$i][] = $score[$scoreKey];
            endif;
        endforeach;

        return $newArcs;
    }
}
