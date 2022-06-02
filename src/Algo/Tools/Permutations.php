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
use SplFixedArray;

// Thanks to Jorge Gomes @cyberkurumin
class Permutations
{
    protected readonly int $arr_count;
    protected SplFixedArray $results;
    protected int $arrKey = 0;

    public static function countPossiblePermutations (int $candidatesNumber): int
    {
        $result = BigInteger::of($candidatesNumber);

        for ($iteration = 1; $iteration < $candidatesNumber; $iteration++) :
            $result = $result->multipliedBy($candidatesNumber - $iteration);
        endfor;

        try {
            return $result->toInt();
        } catch (IntegerOverflowException $e) {
            throw new CondorcetIntegerOverflowException($e->getMessage());
        }
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
