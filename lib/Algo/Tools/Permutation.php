<?php
/*
    Condorcet PHP Class, with Schulze Methods and others !

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace Condorcet\Algo\Tools;

// Thanks to Jorge Gomes @cyberkurumin 
class Permutation
{
    public static $_prefix = 'C';

    public $results = [];

    public static function countPossiblePermutations (int $candidatesNumber) {
        $result = $candidatesNumber;

        for ($iteration = 1; $iteration < $candidatesNumber; $iteration++)
        {
            $result = $result * ($candidatesNumber - $iteration);
        }

        return $result;
    }


    public function __construct($arr) {
        $this->_exec(
            $this->_permute( (is_int($arr)) ? $this->createCandidates($arr) : $arr )
        );
    }

    public function getResults (bool $serialize = false) {
        return ($serialize) ? serialize($this->results) : $this->results;
    }

    public function writeResults (string $path) {
        file_put_contents($path, $this->getResults(true));
    }

    protected function createCandidates (int $numberOfCandidates) : array
    {
        $arr = [];

        for ($i = 0; $i < $numberOfCandidates; $i++) {
            $arr[] = self::$_prefix.$i;
        }
        return $arr;
    }

    private function _exec($a, array $i = []) {
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

    private function _permute(array $arr) {
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
