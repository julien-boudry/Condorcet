<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet;

use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{Description, Example, FunctionParameter, FunctionReturn, PublicAPI, Related};
use CondorcetPHP\Condorcet\Throwable\CondorcetException;

abstract class CondorcetUtil
{
    // Check JSON format
    public static function isJson (string $string) : bool
    {
        if (\is_numeric($string) || $string === 'true' || $string === 'false' || $string === 'null' || empty($string)) :
            return false;
        endif;

        // try to decode string
        \json_decode($string);

        // check if error occured
        return \json_last_error() === \JSON_ERROR_NONE;
    }

    public static function prepareJson (string $input) : mixed
    {
        if (!self::isJson($input)) :
            throw new CondorcetException(15);
        endif;

        return \json_decode($input, true);
    }

    // Generic action before parsing data from string input
    public static function prepareParse (string $input, bool $isFile) : array
    {
        // Is string or is file ?
        if ($isFile === true) :
            $input = \file_get_contents($input);
        endif;

        // Line
        $input = \preg_replace("(\r\n|\n|\r)",';',$input);
        $input = \explode(';', $input);

        // Delete comments
        foreach ($input as &$line) :
            // Delete comments
            $is_comment = \strpos($line, '#');
            if ($is_comment !== false) :
                $line = \substr($line, 0, $is_comment);
            endif;

            // Trim
            $line = \trim($line);
        endforeach;

        return $input;
    }

    // Simplify Condorcet Var_Dump. Transform object to String.
    #[PublicAPI]
    #[Description("Provide pretty re-formatting, human compliant, of all Condorcet PHP object or result set.\nCan be use before a var_dump, or just to get more simple data output.")]
    #[FunctionReturn("New formatted data.")]
    public static function format (
        #[FunctionParameter('Input to convert')] mixed $input,
        #[FunctionParameter('If true. Will convert Candidate objects into string representation of their name')] bool $convertObject = true
    ) : mixed
    {
        if (\is_object($input)) :

            $r = $input;

            if ($convertObject) :
                if ($input instanceof Candidate) :
                    $r = (string) $input;
                elseif ($input instanceof Vote) :
                    $r = $input->getSimpleRanking();
                elseif ($input instanceof Result) :
                    $r = $input->getResultAsArray(true);
                endif;
            endif;

        elseif (!\is_array($input)) :
            $r = $input;
        else :
            foreach ($input as $key => $line) :
                $input[$key] = self::format($line,$convertObject);
            endforeach;

            if (\count($input) === 1 && \is_int(\key($input)) && (!\is_array(\reset($input)) || \count(\reset($input)) === 1)):
                $r = \reset($input);
            else:
                $r = $input;
            endif;
        endif;

        return $r;
    }
}
