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
}
