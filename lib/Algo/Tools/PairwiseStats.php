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
                if ($comparison[$candidate_key]['worst_pairwise_defeat_margin'] < $defeat_margin) :

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
        $comparison = self::PairwiseComparison($pairwise);

        $score = [];  

        foreach ($pairwise as $candidate_key => $candidate_value) :
            foreach ($candidate_value['win'] as $challenger_key => $challenger_value) :

                if ($challenger_value > $candidate_value['lose'][$challenger_key]) :

                    $score[$candidate_key.'>'.$challenger_key]['score'] = $challenger_value;
                    $score[$candidate_key.'>'.$challenger_key]['minority'] = $candidate_value['lose'][$challenger_key];
                    $score[$candidate_key.'>'.$challenger_key]['margin'] = $candidate_value['win'][$challenger_key] - $candidate_value['lose'][$challenger_key];

                elseif (    $challenger_value === $candidate_value['lose'][$challenger_key] &&
                            !isset($score[$challenger_key.'>'.$candidate_key]) ) :

                    if ($comparison[$candidate_key]['worst_pairwise_defeat_winning'] <= $comparison[$challenger_key]['worst_pairwise_defeat_winning']) :
                        $score[$candidate_key.'>'.$challenger_key]['score'] = 0.1;
                        $score[$candidate_key.'>'.$challenger_key]['minority'] = $candidate_value['lose'][$challenger_key];
                        $score[$candidate_key.'>'.$challenger_key]['margin'] = $candidate_value['win'][$challenger_key] - $candidate_value['lose'][$challenger_key];
                    endif;

                endif;

            endforeach;
        endforeach;

        uasort($score, function ($a, $b) : int {
            if ($a['score'] < $b['score']) : return 1;
            elseif ($a['score'] > $b['score']) : return -1;
            elseif ($a['score'] === $b['score']) :
                if ($a['minority'] > $b['minority']) :
                    return 1;
                elseif ($a['minority'] < $b['minority']) :
                    return -1;
                elseif ($a['minority'] === $b['minority']) :
                    if ($a['margin'] < $b['margin']) :
                        return 1;
                    elseif ($a['margin'] > $b['margin']) :
                        return -1;
                    else :
                        return 0;
                    endif;
                endif;
            endif;
        });

        return $score;
    }
}
