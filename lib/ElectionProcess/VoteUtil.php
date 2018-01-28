<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace Condorcet\ElectionProcess;


// Base Condorcet class
abstract class VoteUtil
{
    public static function tagsConvert ($tags) : ?array
    {
        if (empty($tags)) :
            return null;
        endif;

        // Make Array
        if (!is_array($tags)) :
            $tags = explode(',', $tags);
        endif;

        // Trim tags
        foreach ($tags as $key => &$oneTag) :
            if (empty($oneTag) || is_object($oneTag) || is_bool($oneTag)) :
                unset($tags[$key]);
                continue;
            endif;

            $oneTag = (!ctype_digit($oneTag)) ? trim($oneTag) : intval($oneTag);
        endforeach;

        return $tags;
    }

    public static function getRankingAsString (array $ranking) : string
    {
        foreach ($ranking as &$rank) :
            if (is_array($rank)) :
                sort($rank);
                $rank = implode(' = ',$rank);
            endif;
        endforeach;

        return implode(' > ', $ranking);
    }

    // From a string like 'A>B=C=H>G=T>Q'
    public static function convertVoteInput (string $formula) : array
    {
        $ranking = explode('>', $formula);

        foreach ($ranking as &$rank_vote) :
            $rank_vote = explode('=', $rank_vote);

            // Del space at start and end
            foreach ($rank_vote as &$value) :
                $value = trim($value);
            endforeach;
        endforeach;

        return $ranking;
    }
}
