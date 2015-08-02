<?php
/*
    Condorcet PHP Class, with Schulze Methods and others !

    Version: 0.93

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/


/////////// TOOLS FOR MODULAR ALGORITHMS ///////////

namespace Condorcet\AlgoTools;

// Generic for Algorithms
abstract class PairwiseStats
{

    public static function PairwiseComparison ($pairwise)
    {
        $comparison = array();

        foreach ($pairwise as $candidate_key => $candidate_data)
        {
            $comparison[$candidate_key]['win'] = 0;
            $comparison[$candidate_key]['null'] = 0;
            $comparison[$candidate_key]['lose'] = 0;
            $comparison[$candidate_key]['balance'] = 0;
            $comparison[$candidate_key]['worst_defeat'] = 0;

            foreach ($candidate_data['win'] as $opponenent['key'] => $opponenent['lose']) 
            {
                if ( $opponenent['lose'] > $candidate_data['lose'][$opponenent['key']] )
                {
                    $comparison[$candidate_key]['win']++;
                    $comparison[$candidate_key]['balance']++;
                }
                elseif ( $opponenent['lose'] === $candidate_data['lose'][$opponenent['key']] )
                {
                    $comparison[$candidate_key]['null']++;
                }
                else
                {
                    $comparison[$candidate_key]['lose']++;
                    $comparison[$candidate_key]['balance']--;

                    // Worst defeat
                    if ($comparison[$candidate_key]['worst_defeat'] < $candidate_data['lose'][$opponenent['key']])
                    {
                        $comparison[$candidate_key]['worst_defeat'] = $candidate_data['lose'][$opponenent['key']];
                    }
                }
            }
        }

        return $comparison;
    }

    public static function PairwiseSort ($pairwise)
    {
        $comparison = self::PairwiseComparison($pairwise);

        $score = array();  

        foreach ($pairwise as $candidate_key => $candidate_value)
        {
            foreach ($candidate_value['win'] as $challenger_key => $challenger_value)
            {
                if ($challenger_value > $candidate_value['lose'][$challenger_key])
                {
                    $score[$candidate_key.'>'.$challenger_key]['score'] = $challenger_value;
                    $score[$candidate_key.'>'.$challenger_key]['minority'] = $candidate_value['lose'][$challenger_key];
                    $score[$candidate_key.'>'.$challenger_key]['margin'] = $candidate_value['win'][$challenger_key] - $candidate_value['lose'][$challenger_key];
                }
                elseif ( $challenger_value === $candidate_value['lose'][$challenger_key] && !isset($score[$challenger_key.'>'.$candidate_key]) )
                {
                    if ($comparison[$candidate_key]['worst_defeat'] <= $comparison[$challenger_key]['worst_defeat'])
                    {
                        $score[$candidate_key.'>'.$challenger_key]['score'] = 0.1;
                        $score[$candidate_key.'>'.$challenger_key]['minority'] = $candidate_value['lose'][$challenger_key];
                        $score[$candidate_key.'>'.$challenger_key]['margin'] = $candidate_value['win'][$challenger_key] - $candidate_value['lose'][$challenger_key];
                    }
                }
            }
        }

        uasort($score, function ($a, $b){
            if ($a['score'] < $b['score']) {return 1;} elseif ($a['score'] > $b['score']) {return -1;}
            elseif ($a['score'] === $b['score'])
            {
                if ($a['minority'] > $b['minority'])
                    { return 1; }
                elseif ($a['minority'] < $b['minority'])
                    { return -1; }
                elseif ($a['minority'] === $b['minority'])
                    { 
                        if ($a['margin'] < $b['margin'])
                            { return 1; }
                        elseif ($a['margin'] > $b['margin'])
                            { return -1; }
                        else
                            { return 0; }
                    }
            }
        });

        return $score;
    }

}
