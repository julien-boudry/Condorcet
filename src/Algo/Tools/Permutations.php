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
    #[PublicAPI] // Must be available with composer installation. Only appliez to countPossiblePermutations() method. PHP and memory can't do the compute() with such large numbers.
    static bool $useBigIntegerIfAvailable = true;

    protected readonly int $arr_count;
    protected SplFixedArray $results;
    protected int $arrKey = 0;

    public static function countPossiblePermutations (int $candidatesNumber): int
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

            for ($iteration = 1; $iteration < $candidatesNumber; $iteration++) :
                $result = $result * ($candidatesNumber - $iteration);
            endfor;

            if (\is_float($result)) :
                throw new CondorcetIntegerOverflowException;
            else :
                return $result;
            endif;
        endif;
    }

    public function __construct (int $arr_count)
    {
        $this->arr_count = $arr_count;
        $this->results = new SplFixedArray(self::countPossiblePermutations($this->arr_count));
    }

    public function getResults (): SplFixedArray
    {
        if ($this->arrKey === 0) :
            $this->_exec(
                $this->_permute( $this->createCandidates() )
            );
        endif;

        return $this->results;
    }

    public function writeResults (\SplFileObject $file): void {
        $file->rewind();

        foreach ($this->getResults() as $oneResult) :
            $file->fputcsv($oneResult);
        endforeach;
    }

    protected function createCandidates (): array
    {
        $arr = [];

        for ($i = 0; $i < $this->arr_count; $i++) :
            $arr[] = $i;
        endfor;

        return $arr;
    }

    private function _exec (array|int $a, array $i = []): void
    {
        if (\is_array($a)) :
            foreach($a as $k => $v) :
                $i2 = $i;
                $i2[] = $k;

                $this->_exec($v, $i2);
            endforeach;
        else :
            $i[] = $a;

            // Del 0 key, first key must be 1.
            $r = [null,...$i];
            unset($r[0]);

            $this->results[$this->arrKey++] = $r;
        endif;
    }

    private function _permute (array $arr): array|int
    {
        $out = [];

        if (\count($arr) > 1) :
            foreach($arr as $r => $c) :
                $n = $arr;
                unset($n[$r]);
                $out[$c] = $this->_permute($n);
            endforeach;
        else :
            return \array_shift($arr);
        endif;

        return $out;
    }
}
