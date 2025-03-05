<?php

/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Utils;

use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\Throws;
use CondorcetPHP\Condorcet\Throwable\VoteInvalidFormatException;
use CondorcetPHP\Condorcet\Tools\Converters\CEF\CondorcetElectionFormat;

// Base Condorcet class
class VoteEntryParser
{
    public readonly string $originalEntry;

    public readonly ?string $comment;
    public readonly int $multiple;
    public readonly ?array $ranking;
    public readonly ?array $tags;
    public readonly int $weight;

    public function __construct(string $entry)
    {
        $this->originalEntry = $entry;

        // Disallow < and "
        if (preg_match('/<|"/mi', $entry) === 1) {
            throw new VoteInvalidFormatException("found '<' or '|' in " . $entry);
        }

        $this->comment = $this->getComment($entry, true);

        $this->multiple = self::parseIntValueFromVoteStringOffset('*', $entry, true);
        $this->weight = self::parseIntValueFromVoteStringOffset('^', $entry, true);

        $this->tags = $this->convertTagsFromVoteString($entry, true);

        $this->ranking = self::convertRankingFromString($entry);
    }

    // From a string like 'A>B=C=H>G=T>Q'
    public static function convertRankingFromString(string $formula): ?array
    {
        $formula = trim($formula);

        // Condorcet Election Format special string
        if (empty($formula)) {
            return null;
        } elseif ($formula === CondorcetElectionFormat::SPECIAL_KEYWORD_EMPTY_RANKING) {
            return [];
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

            return array_filter($ranking);
        }
    }

    public static function convertTagsFromVoteString(string &$voteString, bool $cut = false): ?array
    {
        $offset = mb_strpos($voteString, '||');

        if (\is_int($offset)) {
            $tagsPart = mb_substr($voteString, 0, $offset);

            $tags = explode(',', $tagsPart);

            array_walk($tags, static function (string &$value): void {
                $value = trim($value);
            });

            $cut && $voteString = mb_substr($voteString, $offset + 2);

            return $tags;
        } else {
            return null;
        }
    }

    public static function getComment(string &$voteString, bool $cut = false): ?string
    {
        $offset = mb_strpos($voteString, '#');

        if (\is_int($offset)) {
            $comment = trim(mb_substr($voteString, $offset + 1));

            $cut && $voteString = mb_substr($voteString, 0, $offset);

            return $comment;
        } else {
            return null;
        }
    }

    #[Throws(VoteInvalidFormatException::class)]
    public static function parseIntValueFromVoteStringOffset(string $character, string &$entry, bool $cut = false): int
    {
        $offset = mb_strpos($entry, $character);

        if (\is_int($offset)) {
            $input = trim(mb_substr($entry, $offset + 1));

            $value = '';

            foreach (mb_str_split($input) as $char) {
                if (!\in_array($char, ['#', '^', '*', "\n"], true)) {
                    $value .= $char;
                } else {
                    break;
                }
            }

            // Errors
            if (!is_numeric($value)) {
                throw new VoteInvalidFormatException("the value '{$value}' is not an integer.");
            }

            $cut && $entry = mb_substr($entry, 0, $offset);

            $value = \intval($value);
            return ($value > 0) ? $value : 1;
        } else {
            return 1;
        }
    }
}
