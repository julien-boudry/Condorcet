<?php declare(strict_types=1);
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

namespace CondorcetPHP\Condorcet\Utils;

use CondorcetPHP\Condorcet\Throwable\FileDoesNotExistException;
use CondorcetPHP\Condorcet\{Candidate, Result, Vote};

abstract class CondorcetUtil
{
    // Check JSON format
    public static function isValidJsonForCondorcet(string $string): void
    {
        if (is_numeric($string) || $string === 'true' || $string === 'false' || $string === 'null' || $string === '{}' || empty($string)) {
            throw new \JsonException;
        }
    }

    public static function prepareJson(string $input): mixed
    {
        self::isValidJsonForCondorcet($input);

        return json_decode(json: $input, associative: true, flags: \JSON_THROW_ON_ERROR);
    }

    // Generic action before parsing data from string input
    public static function prepareParse(string $input, bool $isFile): array
    {
        // Is string or is file ?
        if ($isFile) {
            if (!is_file($input)) {
                throw new FileDoesNotExistException('Specified input file does not exist. path: ' . $input);
            }

            $input = file_get_contents($input);
        }

        // Line
        $input = preg_replace("(\r\n|\n|\r)", ';', $input);
        $input = explode(';', $input);

        // Delete comments
        foreach ($input as $key => &$line) {
            // Delete comments
            $is_comment = mb_strpos($line, '#');
            if ($is_comment !== false) {
                $line = mb_substr($line, 0, $is_comment);
            }

            // Trim
            $line = mb_trim($line);

            // Remove empty
            if (empty($line)) {
                unset($input[$key]);
            }
        }

        return $input;
    }

    // Simplify Condorcet Var_Dump. Transform object to String.
    /**
     * Provides pretty re-formatting, human readable, of all Condorcet PHP objects or result sets.
     * Can be used before a var_dump, or just to get simpler data output.
     * @api
     * @param $input Input to convert.
     * @param $convertObject If true. Will convert Candidate objects into string representation of their name.
     * @return mixed New formatted data.
     */
    public static function format(
        mixed $input,
        bool $convertObject = true
    ): mixed {
        if (\is_object($input)) {
            $r = $input;

            if ($convertObject) {
                if ($input instanceof Candidate) {
                    $r = (string) $input;
                } elseif ($input instanceof Vote) {
                    $r = $input->getRankingAsString();
                } elseif ($input instanceof Result) {
                    $r = $input->rankingAsArrayString;
                }
            }
        } elseif (!\is_array($input)) {
            $r = $input;
        } else {
            foreach ($input as $key => $line) {
                $input[$key] = self::format($line, $convertObject);
            }

            if (\count($input) === 1 && \is_int(key($input)) && (!\is_array(reset($input)) || \count(reset($input)) === 1)) {
                $r = reset($input);
            } else {
                $r = $input;
            }
        }

        return $r;
    }
}
