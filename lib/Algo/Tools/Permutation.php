<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Algo\Tools;

// Thanks to Jorge Gomes @cyberkurumin
class Permutation
{
    protected readonly int $arr_count;
    protected array $results = [];

    public static function countPossiblePermutations (int $candidatesNumber): int
    {
        $result = $candidatesNumber;

        for ($iteration = 1; $iteration < $candidatesNumber; $iteration++) :
            $result = $result * ($candidatesNumber - $iteration);
        endfor;

        return $result;
    }

    public function __construct (int $arr_count)
    {
        $this->arr_count = $arr_count;
    }

    public function getResults (): array
    {
        if (empty($this->results)) :
            $this->_exec(
                $this->_permute( $this->createCandidates() )
            );
        endif;

        return $this->results;
    }

    public function writeResults (string $path): void {
        $f = new \SplFileObject($path,'w+');

        foreach ($this->getResults() as $oneResult) :
            $f->fputcsv($oneResult);
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
            $r = [...[0=>null],...$i];
            unset($r[0]);

            $this->results[] = $r;
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
