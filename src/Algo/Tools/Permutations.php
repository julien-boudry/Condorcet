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
use CondorcetPHP\Condorcet\Throwable\Internal\CondorcetInternalException;
use SplFixedArray;

// Thanks to Jorge Gomes @cyberkurumin
#[InternalModulesAPI]
class Permutations
{
    #[PublicAPI] // Must be available with composer installation. Only appliez to getPossibleCountOfPermutations() method. PHP and memory can't do the compute() with such large numbers.
    static bool $useBigIntegerIfAvailable = true;

    protected readonly int $candidates_count;
    protected int $arrKey = 0;

    public static function getPossibleCountOfPermutations (int $candidatesNumber): int
    {
        if ($candidatesNumber < 1) :
            throw new CondorcetInternalException('Parameters invalid');
        endif;

        if (self::$useBigIntegerIfAvailable && \class_exists('Brick\Math\BigInteger')) :
            $result = BigInteger::of($candidatesNumber);

            for ($iteration = 1; $iteration < $candidatesNumber; $iteration++) :
                $result = $result->multipliedBy($candidatesNumber - $iteration);
            endfor;

            try {
                return $result->toInt();
            } catch (IntegerOverflowException $e) {
                throw new CondorcetIntegerOverflowException($e->getMessage());
            }
        else :
            $result = $candidatesNumber;

            for ($iteration = 1 ; $iteration < $candidatesNumber ; $iteration++) :
                $result = $result * ($candidatesNumber - $iteration);
            endfor;

            if (\is_float($result)) :
                throw new CondorcetIntegerOverflowException;
            else :
                return $result;
            endif;
        endif;
    }

    public function __construct (int $candidates_count)
    {
        $this->candidates_count = $candidates_count;
    }

    public function getResults (): SplFixedArray
    {
        $results = new SplFixedArray(self::getPossibleCountOfPermutations($this->candidates_count));
        $this->arrKey = 0;

        foreach ($this->getPermutationGenerator() as $arrKey => $oneResult) :
            $results[$arrKey] = $oneResult;
        endforeach;

        return $results;
    }

    public function writeResults (\SplFileObject $file): void
    {
        $this->arrKey = 0;
        $file->rewind();

        foreach ($this->getPermutationGenerator() as $oneResult) :
            $file->fputcsv($oneResult);
        endforeach;
    }

    public function getPermutationGenerator (): \Generator
    {
        if ($this->candidates_count === 1) :
            return (function (): \Generator {
                $this->arrKey = 0;
                yield $this->arrKey++ => [1=>0];
            })();
        else :
            return $this->_exec(
                $this->_MainPermute( $this->createCandidates() )
            );
        endif;
    }

    protected function createCandidates (): array
    {
        $arr = [];

        for ($i = 0 ; $i < $this->candidates_count ; $i++) :
            $arr[] = $i;
        endfor;

        return $arr;
    }

    private function _exec (array|int|\Generator $a, array $i = []): \Generator
    {
        if (\is_array($a) || is_iterable($a)) :
            foreach($a as $k => $v) :
                $i2 = $i;
                $i2[] = $k;

                yield from $this->_exec($v, $i2);
            endforeach;
        else :
            $i[] = $a;

            // Del 0 key, first key must be 1.
            $r = [null,...$i];
            unset($r[0]);

            yield $this->arrKey++ => $r;
        endif;
    }

    private function _MainPermute (array $arr): \Generator
    {
        foreach($arr as $r => $c) :
            $n = $arr;
            unset($n[$r]);

            yield $c => (\count($n) > 1) ? $this->_permute($n) : \reset($n);
        endforeach;
    }

    private function _permute (array $arr): array
    {
        $out = [];

        foreach($arr as $r => $c) :
            $n = $arr;
            unset($n[$r]);

            $out[$c] = (\count($n) > 1) ? $this->_permute($n) : \reset($n);
        endforeach;

        return $out;
    }
}
