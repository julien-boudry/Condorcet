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
use CondorcetPHP\Condorcet\Throwable\Internal\{CondorcetInternalException, IntegerOverflowException as CondorcetIntegerOverflowException};
use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{InternalModulesAPI, PublicAPI};
use SplFixedArray;

#[InternalModulesAPI]
class Combinations
{
    #[PublicAPI] // Must be available with composer installation. Only appliez to getPossibleCountOfCombinations() method. PHP and memory can't do the compute() with such large numbers.
    public static bool $useBigIntegerIfAvailable = true;

    public static function getPossibleCountOfCombinations(int $count, int $length): int
    {
        if ($count < 1 || $length < 1 || $count < $length) {
            throw new CondorcetInternalException('Parameters invalid');
        }

        if (self::$useBigIntegerIfAvailable && class_exists(BigInteger::class)) {
            $a = BigInteger::of(1);
            for ($i = $count; $i > ($count - $length); $i--) {
                $a = $a->multipliedBy($i);
            }

            $b = BigInteger::of(1);
            for ($i = $length; $i > 0; $i--) {
                $b = $b->multipliedBy($i);
            }

            $r = $a->dividedBy($b);

            try {
                return $r->toInt();
            } catch (IntegerOverflowException $e) {
                throw new CondorcetIntegerOverflowException($e->getMessage());
            }
        } else {
            $a = 1;
            for ($i = $count; $i > ($count - $length); $i--) {
                $a *= $i;
            }

            $b = 1;
            for ($i = $length; $i > 0; $i--) {
                $b *= $i;
            }

            if (\is_float($a) || \is_float($b)) { // @phpstan-ignore-line
                throw new CondorcetIntegerOverflowException;
            } else {
                return (int) ($a / $b);
            }
        }
    }

    public static function compute(array $values, int $length, array $append_before = []): SplFixedArray
    {
        $count = \count($values);
        $r = new SplFixedArray(self::getPossibleCountOfCombinations($count, $length));

        $arrKey = 0;
        foreach (self::computeGenerator($values, $length, $append_before) as $oneCombination) {
            $r[$arrKey++] = $oneCombination;
        }

        return $r;
    }

    public static function computeGenerator(array $values, int $length, array $append_before = []): \Generator
    {
        $count = \count($values);
        $size = 2 ** $count;
        $keys = array_keys($values);

        for ($i = 0; $i < $size; $i++) {
            $b = sprintf('%0' . $count . 'b', $i);
            $out = [];

            for ($j = 0; $j < $count; $j++) {
                if ($b[$j] === '1') {
                    $out[$keys[$j]] = $values[$keys[$j]];
                }
            }

            if (\count($out) === $length) {
                yield array_values(array_merge($append_before, $out));
            }
        }
    }
}
