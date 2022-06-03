<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Algo\Tools;

use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\InternalModulesAPI;
use CondorcetPHP\Condorcet\Throwable\Internal\CondorcetInternalException;
use SplFixedArray;

#[InternalModulesAPI]
class Combinations
{
    public static function getNumberOfCombinations (int $count, int $length): int
    {
        if ($count < 1 || $length < 1 || $count < $length) :
            throw new CondorcetInternalException('Parameters invalid');
        endif;

        $a = 1;
        for ($i = $count ; $i > ($count - $length) ; $i--) :
            $a = $a * $i;
        endfor;

        $b = 1;
        for ($i = $length ; $i > 0 ; $i--) :
            $b = $b * $i;
        endfor;

        return (int) ($a / $b);
    }

    public static function compute (array $values, int $length, array $append_before = []): SplFixedArray
    {
        $count = \count($values);
        $size = 2 ** $count;
        $keys = \array_keys($values);

        // Get the combinations
        $return = new SplFixedArray(self::getNumberOfCombinations($count, $length));

        $arrKey = 0;
        for ($i = 0; $i < $size; $i++) :
            $b = \sprintf("%0" . $count . "b", $i);
            $out = [];

            for ($j = 0; $j < $count; $j++) :
                if ($b[$j] == '1') :
                    $out[$keys[$j]] = $values[$keys[$j]];
                endif;
            endfor;

            if (count($out) === $length) :
                 $return[$arrKey++] = \array_merge($append_before, $out);
            endif;
        endfor;

        return $return;
    }
}
