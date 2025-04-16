<?php declare(strict_types=1);
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

namespace CondorcetPHP\Condorcet\Console\Helper;

abstract class CommandInputHelper
{
    public static function getFilePath(string $path): ?string
    {
        if (self::isAbsoluteAndExist($path)) {
            return $path;
        } else {
            return (is_file($file = getcwd() . \DIRECTORY_SEPARATOR . $path)) ? $file : null;
        }
    }

    public static function isAbsoluteAndExist(string $path): bool
    {
        return self::pathIsAbsolute($path) && is_file($path);
    }

    public static function pathIsAbsolute(string $path): bool
    {
        return empty($path) ? false : (strspn($path, '/\\', 0, 1) || (mb_strlen($path) > 3 && ctype_alpha($path[0]) && $path[1] === ':' && strspn($path, '/\\', 2, 1)));
    }
}
