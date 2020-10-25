<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\ElectionProcess;

use CondorcetPHP\Condorcet\Throwable\CondorcetException;

// Base Condorcet class
abstract class VoteUtil
{
    public static function tagsConvert (array|string|null $tags) : ?array
    {
        if (empty($tags)) :
            return null;
        elseif (\is_string($tags)) :
            $tags = \explode(',', $tags);
        else :
            foreach ($tags as &$oneTag) :
                if (!\is_string($oneTag)) :
                    throw new CondorcetException(17);
                endif;
            endforeach;
        endif;

        $tags = \array_map('trim', $tags);

        foreach ($tags as $oneTag) :
           if (empty($oneTag)) :
                throw new CondorcetException(17);
            endif;
        endforeach;

        return $tags;
    }

    public static function getRankingAsString (array $ranking) : string
    {
        foreach ($ranking as &$rank) :
            if (\is_array($rank)) :
                \sort($rank);
                $rank = \implode(' = ',$rank);
            endif;
        endforeach;

        return \implode(' > ', $ranking);
    }

    // From a string like 'A>B=C=H>G=T>Q'
    public static function convertVoteInput (string $formula) : array
    {
        $ranking = \explode('>', $formula);

        foreach ($ranking as &$rank_vote) :
            $rank_vote = \explode('=', $rank_vote);

            // Del space at start and end
            foreach ($rank_vote as &$value) :
                $value = \trim($value);
            endforeach;
        endforeach;

        return $ranking;
    }

    public static function parseAnalysingOneLine (int|bool $searchCharacter, string &$line) : int
    {
        if (is_int($searchCharacter)) :
            $value = \trim( \substr($line, $searchCharacter + 1) );

            // Errors
            if ( !\is_numeric($value) ) :
                throw new CondorcetException(13);
            endif;

            // Reformat line
            $line = \substr($line, 0, $searchCharacter);

            return \intval($value);
        else :
            return 1;
        endif;
    }
}
