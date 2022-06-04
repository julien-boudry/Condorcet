<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Algo\Tools;

use Brick\Math\BigInteger;
use Brick\Math\Exception\IntegerOverflowException;
use CondorcetPHP\Condorcet\Throwable\Internal\IntegerOverflowException as CondorcetIntegerOverflowException;
use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\InternalModulesAPI;
use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\PublicAPI;
use CondorcetPHP\Condorcet\Throwable\Internal\CondorcetInternalException;
use SplFixedArray;

#[InternalModulesAPI]
class Combinations
{

    #[PublicAPI] // Must be available with composer installation. Only appliez to getNumberOfCombinations() method. PHP and memory can't do the compute() with such large numbers.
    static bool $useBigIntegerIfAvailable = true;

    public static function getNumberOfCombinations (int $count, int $length): int
    {
        if ($count < 1 || $length < 1 || $count < $length) :
            throw new CondorcetInternalException('Parameters invalid');
        endif;

        if (self::$useBigIntegerIfAvailable && \class_exists('Brick\Math\BigInteger')) :
            $a = BigInteger::of(1);
            for ($i = $count ; $i > ($count - $length) ; $i--) :
                $a = $a->multipliedBy($i);
            endfor;

            $b = BigInteger::of(1);
            for ($i = $length ; $i > 0 ; $i--) :
                $b = $b->multipliedBy($i);
            endfor;

            try {
                return $a->dividedBy($b)->toInt();
            } catch (IntegerOverflowException $e) {
                throw new CondorcetIntegerOverflowException($e->getMessage());
            }
        else :
            $a = 1;
            for ($i = $count ; $i > ($count - $length) ; $i--) :
                $a = $a * $i;
            endfor;

            $b = 1;
            for ($i = $length ; $i > 0 ; $i--) :
                $b = $b * $i;
            endfor;

            if (\is_float($a) || \is_float($b)) :
                throw new CondorcetIntegerOverflowException;
            else :
                return (int) ($a / $b);
            endif;
        endif;
    }

    public static function compute (array $values, int $length, array $append_before = []): SplFixedArray
    {
        $count = \count($values);
        $r = new SplFixedArray(self::getNumberOfCombinations($count, $length));

        $arrKey = 0;
        foreach (self::computeGenerator($values, $length, $append_before) as $oneCombination) :
            $r[$arrKey++] = $oneCombination;
        endforeach;

        return $r;
    }

    public static function computeGenerator (array $values, int $length, array $append_before = []): \Generator
    {
        $count = \count($values);
        $size = 2 ** $count;
        $keys = \array_keys($values);

        for ($i = 0; $i < $size; $i++) :
            $b = \sprintf("%0" . $count . "b", $i);
            $out = [];

            for ($j = 0; $j < $count; $j++) :
                if ($b[$j] == '1') :
                    $out[$keys[$j]] = $values[$keys[$j]];
                endif;
            endfor;

            if (count($out) === $length) :
                 yield \array_values(\array_merge($append_before, $out));
            endif;
        endfor;
    }
}
