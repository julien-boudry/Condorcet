<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\ElectionProcess;

use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\Throws;
use CondorcetPHP\Condorcet\Throwable\VoteInvalidFormatException;
use CondorcetPHP\Condorcet\Tools\Converters\CondorcetElectionFormat;

// Base Condorcet class
abstract class VoteUtil
{
    #[Throws(VoteInvalidFormatException::class)]
    public static function tagsConvert(array|string|null $tags): ?array
    {
        if (empty($tags)) {
            return null;
        } elseif (\is_string($tags)) {
            $tags = explode(',', $tags);
        } else {
            foreach ($tags as $tag) {
                if (!\is_string($tag)) {
                    throw new VoteInvalidFormatException('every tag must be of type string, ' . \gettype($tag) . ' given');
                }
            }
        }

        $tags = array_map(static fn (string $x): string => trim($x), $tags);

        foreach ($tags as $tag) {
            if (empty($tag)) {
                throw new VoteInvalidFormatException('found empty tag');
            }
        }

        return $tags;
    }

    public static function getRankingAsString(array $ranking): string
    {
        foreach ($ranking as &$rank) {
            if (\is_array($rank)) {
                sort($rank);
                $rank = implode(' = ', $rank);
            }
        }

        return implode(' > ', $ranking);
    }

    // From a string like 'A>B=C=H>G=T>Q'
    public static function convertVoteInput(string $formula): array
    {
        $formula = trim($formula);

        // Condorcet Election Format special string
        if (empty($formula) || $formula === CondorcetElectionFormat::SPECIAL_KEYWORD_EMPTY_RANKING) {
            $ranking = [];
        } else {
            $ranking = explode('>', $formula);

            foreach ($ranking as &$rank_vote) {
                $rank_vote = explode('=', $rank_vote);
                $rank_vote = array_filter($rank_vote);

                // Del space at start and end
                foreach ($rank_vote as &$value) {
                    $value = trim($value);
                }
            }
        }

        return array_filter($ranking);
    }

    #[Throws(VoteInvalidFormatException::class)]
    public static function parseAnalysingOneLine(int|bool $searchCharacter, string &$line): int
    {
        if (\is_int($searchCharacter)) {
            $value = trim(mb_substr($line, $searchCharacter + 1));

            // Errors
            if (!is_numeric($value)) {
                throw new VoteInvalidFormatException("the value '{$value}' is not numeric");
            }

            // Reformat line
            $line = mb_substr($line, 0, $searchCharacter);

            return \intval($value);
        } else {
            return 1;
        }
    }
}
