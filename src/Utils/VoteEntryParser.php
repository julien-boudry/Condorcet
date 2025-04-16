<?php declare(strict_types=1);
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

namespace CondorcetPHP\Condorcet\Utils;

use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\Throws;
use CondorcetPHP\Condorcet\Throwable\VoteInvalidFormatException;
use CondorcetPHP\Condorcet\Tools\Converters\CEF\CondorcetElectionFormat;

// Base Condorcet class
class VoteEntryParser
{
    public private(set) readonly ?string $comment;
    public private(set) readonly int $multiple;
    public private(set) readonly ?array $ranking;
    public private(set) readonly ?array $tags;
    public private(set) readonly int $weight;

    public function __construct(public private(set) readonly string $originalEntry)
    {
        $entry = $this->originalEntry;

        // Disallow < and "
        if (preg_match('/<|"/mi', $entry) === 1) {
            throw new VoteInvalidFormatException("found '<' or '|' in " . $entry);
        }

        $this->comment = self::getComment($entry, true);

        $this->multiple = self::parseIntValueFromVoteStringOffset('*', $entry, true);
        $this->weight = self::parseIntValueFromVoteStringOffset('^', $entry, true);

        $this->tags = self::convertTagsFromVoteString($entry, true);

        $this->ranking = self::convertRankingFromString($entry);
    }

    // From a string like 'A>B=C=H>G=T>Q'
    public static function convertRankingFromString(string $formula): ?array
    {
        $formula = mb_trim($formula);

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
                    $value = mb_trim($value);
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
                $value = mb_trim($value);
            });

            if ($cut) {
                $voteString = mb_substr($voteString, $offset + 2);
            }

            return $tags;
        } else {
            return null;
        }
    }

    public static function getComment(string &$voteString, bool $cut = false): ?string
    {
        $offset = mb_strpos($voteString, '#');

        if (\is_int($offset)) {
            $comment = mb_trim(mb_substr($voteString, $offset + 1));

            if ($cut) {
                $voteString = mb_substr($voteString, 0, $offset);
            }

            return $comment;
        } else {
            return null;
        }
    }
/**
 * @throws VoteInvalidFormatException
 */
    public static function parseIntValueFromVoteStringOffset(string $character, string &$entry, bool $cut = false): int
    {
        $offset = mb_strpos($entry, $character);

        if (\is_int($offset)) {
            $input = mb_trim(mb_substr($entry, $offset + 1));

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

            if ($cut) {
                $entry = mb_substr($entry, 0, $offset);
            }

            $value = \intval($value);
            return ($value > 0) ? $value : 1;
        } else {
            return 1;
        }
    }
}
