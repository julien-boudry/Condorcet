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

// Thanks to Jorge Gomes @cyberkurumin
/**
 * @internal 
 */
class Permutations
{
    /**
     * @api
     */
    #[PublicAPI] // Must be available with composer installation. Only applied to getPossibleCountOfPermutations() method. PHP and memory can't do the compute() with such large numbers.
    public static bool $useBigIntegerIfAvailable = true;

    public protected(set) readonly array $candidates;

    public static function getPossibleCountOfPermutations(int $candidatesNumber): int
    {
        if ($candidatesNumber < 1) {
            throw new CondorcetInternalException('Parameters invalid');
        }

        if (self::$useBigIntegerIfAvailable && class_exists(BigInteger::class)) {
            $result = BigInteger::of($candidatesNumber);

            for ($iteration = 1; $iteration < $candidatesNumber; $iteration++) {
                $result = $result->multipliedBy($candidatesNumber - $iteration);
            }

            try {
                return $result->toInt();
            } catch (IntegerOverflowException $e) {
                throw new CondorcetIntegerOverflowException($e->getMessage());
            }
        } else {
            $result = $candidatesNumber;

            for ($iteration = 1; $iteration < $candidatesNumber; $iteration++) {
                $result = $result * ($candidatesNumber - $iteration);
            }

            if (\is_float($result)) { // @phpstan-ignore-line
                throw new CondorcetIntegerOverflowException;
            } else {
                return $result;
            }
        }
    }

    public function __construct(array $candidates)
    {
        $this->candidates = array_values($candidates);
    }

    public function getResults(): SplFixedArray
    {
        $results = new SplFixedArray(self::getPossibleCountOfPermutations(\count($this->candidates)));
        $arrKey = 0;

        foreach ($this->getPermutationGenerator() as $onePermutation) {
            $results[$arrKey++] = $onePermutation;
        }

        return $results;
    }

    public function getPermutationGenerator(): \Generator
    {
        return $this->permutationGenerator($this->candidates);
    }

    protected function permutationGenerator(array $elements): \Generator
    {
        if (\count($elements) <= 1) {
            yield [1 => reset($elements)]; // Set the only key to index 1
        } else {
            foreach ($this->permutationGenerator(\array_slice($elements, 1)) as $permutation) {
                foreach (range(0, \count($elements) - 1) as $i) {
                    $r = array_merge(
                        \array_slice($permutation, 0, $i),
                        [$elements[0]],
                        \array_slice($permutation, $i)
                    );

                    // Set first key to 1
                    $r = [null, ...$r];
                    unset($r[0]);

                    yield $r;
                }
            }
        }
    }
}
