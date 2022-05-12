<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Algo\Tools;

class Combinations
{
    public static function compute (array $values, int $length, array $append_before = []): array
    {
        $count = \count($values);
        $size = 2 ** $count;
        $keys = \array_keys($values);
        $return = [];

        for ($i = 0; $i < $size; $i++) :
            $b = \sprintf("%0" . $count . "b", $i);
            $out = [];

            for ($j = 0; $j < $count; $j++) :
                if ($b[$j] == '1') :
                    $out[$keys[$j]] = $values[$keys[$j]];
                endif;
            endfor;

            if (count($out) === $length) :
                 $return[] = \array_merge($append_before, $out);
            endif;
        endfor;

        return $return;
    }
}
