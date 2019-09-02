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
    private const PREFIX = 'C';

    public $results = [];

    public static function countPossiblePermutations (int $candidatesNumber) : int
    {
        $result = $candidatesNumber;

        for ($iteration = 1; $iteration < $candidatesNumber; $iteration++) :
            $result = $result * ($candidatesNumber - $iteration);
        endfor;

        return $result;
    }

    public function __construct ($arr)
    {
        $this->_exec(
            $this->_permute( is_int($arr) ? $this->createCandidates($arr) : $arr )
        );
    }

    public function getResults (bool $serialize = false)
    {
        return $serialize ? serialize($this->results) : $this->results;
    }

    public function writeResults (string $path) : void {
        file_put_contents($path, $this->getResults(true));
    }

    protected function createCandidates (int $numberOfCandidates) : array
    {
        $arr = [];

        for ($i = 0; $i < $numberOfCandidates; $i++) :
            $arr[] = self::PREFIX.$i;
        endfor;

        return $arr;
    }

    private function _exec ($a, array $i = []) : void
    {
        if (is_array($a)) :
            foreach($a as $k => $v) :
                $i2 = $i;
                $i2[] = $k;

                $this->_exec($v, $i2);
            endforeach;
        else :
            $i[] = $a;

            // Del 0 key, first key must be 1.
            $r = [0=>null]; $r = array_merge($r,$i); unset($r[0]);

            $this->results[] = $r;
        endif;
    }

    private function _permute (array $arr)
    {
        $out = [];

        if (count($arr) > 1) :
            foreach($arr as $r => $c) :
                $n = $arr;
                unset($n[$r]);
                $out[$c] = $this->_permute($n);
            endforeach;
        else :
            return array_shift($arr);
        endif;

        return $out;
    }
}
