<?php
/*
    Part of RANKED PAIRS method Module - From the original Condorcet PHP

    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Algo\Methods;


use CondorcetPHP\Condorcet\Result;
use CondorcetPHP\Condorcet\Algo\Method;
use CondorcetPHP\Condorcet\Algo\MethodInterface;


// Ranker Pairs is a Condorcet Algorithm | http://en.wikipedia.org/wiki/Ranked_Pairs
class RankedPairs_Core extends Method implements MethodInterface
{
    // Limits
        public static ?int $_maxCandidates = 40;

    // Ranked Pairs
    protected array $_PairwiseSort;
    protected array $_Arcs = [];
    protected ?array $_Stats = null;
    protected bool $_StatsDone = false;


/////////// PUBLIC ///////////


    // Get the Ranked Pairs ranking
    public function getResult () : Result
    {
        // Cache
        if ( $this->_Result !== null ) :
            return $this->_Result;
        endif;

            //////

        // Sort pairwise
        $this->_PairwiseSort = $this->pairwiseSort();

        // Ranking calculation
        $this->makeArcs();

        // Make Stats
        $this->_Stats['tally'] = $this->_PairwiseSort;
        $this->_Stats['arcs'] = $this->_Arcs;

        // Make Result      
        return $this->_Result = $this->createResult($this->makeResult());
    }

    // Get the Ranked Pair ranking
    protected function getStats () : array
    {
        if (!$this->_StatsDone) :
            foreach ($this->_Stats['tally'] as &$Roundvalue) :
                foreach ($Roundvalue as $ArcKey => &$Arcvalue) :
                    foreach ($Arcvalue as $key => &$value) :
                        if ($key === 'from' || $key === 'to') :
                            $value = $this->_selfElection->getCandidateObjectFromKey($value)->getName();
                        endif;
                    endforeach;
                endforeach;
            endforeach;

            foreach ($this->_Stats['arcs'] as $ArcKey => &$Arcvalue) :
                foreach ($Arcvalue as $key => &$value) :
                    if ($key === 'from' || $key === 'to') :
                        $value = $this->_selfElection->getCandidateObjectFromKey($value)->getName();
                    endif;
                endforeach;
            endforeach;

            $this->_StatsDone = true;
        endif;

        return $this->_Stats;
    }


/////////// COMPUTE ///////////


    //:: RANKED PAIRS ALGORITHM. :://

    protected function makeResult () : array
    {
        $result = [];
        $alreadyDone = [];

        $rang = 1;
        while (count($alreadyDone) < $this->_selfElection->countCandidates()) :
            $winners = $this->getWinners($alreadyDone);

            foreach ($this->_Arcs as $ArcKey => $Arcvalue) :
                foreach ($winners as $oneWinner) :
                    if ($Arcvalue['from'] === $oneWinner || $Arcvalue['to'] === $oneWinner ) :
                        unset($this->_Arcs[$ArcKey]);
                    endif;
                endforeach;
            endforeach;

            $result[$rang++] = $winners;
            $alreadyDone = array_merge($alreadyDone,$winners);
        endwhile;

        return $result;
    }

    protected function getWinners (array $alreadyDone) : array
    {
        $winners = [];

        foreach ($this->_selfElection->getCandidatesList() as $candidateKey => $candidateId) :
            if (!in_array($candidateKey, $alreadyDone, true)) :
                $win = true;
                foreach ($this->_Arcs as $ArcKey => $ArcValue) :
                    if ($ArcValue['to'] === $candidateKey) :
                        $win = false;
                    endif;
                endforeach;

                if ($win) :
                    $winners[] = $candidateKey;
                endif;
            endif;
        endforeach;

        return $winners;
    }


    protected function makeArcs () : void
    {
        foreach ($this->_PairwiseSort as $newArcsRound) :
            $virtualArcs = $this->_Arcs;
            $testNewsArcs = [];

            $newKey = max((empty($highKey = array_keys($virtualArcs)) ? [-1] : $highKey)) + 1;
            foreach ($newArcsRound as $newArc) :
                $virtualArcs[$newKey] = [ 'from' => $newArc['from'], 'to' => $newArc['to'] ];
                $testNewsArcs[$newKey] = $virtualArcs[$newKey];
                $newKey++;
            endforeach;

            foreach ($this->getArcsInCycle($virtualArcs) as $cycleArcKey) :
                if (array_key_exists($cycleArcKey, $testNewsArcs)) :
                    unset($testNewsArcs[$cycleArcKey]);
                endif;
            endforeach;

            foreach ($testNewsArcs as $newArc) :
                $this->_Arcs[] = $newArc;
            endforeach;

        endforeach;
    }

    protected function getArcsInCycle (array $virtualArcs) : array
    {
        $cycles = [];

        foreach ($this->_selfElection->getCandidatesList() as $candidateKey => $candidateId) :
            $cycles = array_merge($cycles,$this->followCycle($virtualArcs,$candidateKey,$candidateKey));
        endforeach;

        return $cycles;
    }

    protected function followCycle (array $virtualArcs, int $startCandidateKey, int $searchCandidateKey, array &$done = [])
    {
        $arcsInCycle = [];

        foreach ($virtualArcs as $ArcKey => $ArcValue) :
            if ($ArcValue['from'] === $startCandidateKey) :
                if (in_array($ArcKey, $done, true)) :
                    continue;
                elseif ($ArcValue['to'] === $searchCandidateKey) :
                    $done[] = $ArcKey;
                    $arcsInCycle[] = $ArcKey;
                else :
                    $done[] = $ArcKey;
                    $arcsInCycle = array_merge($arcsInCycle,$this->followCycle($virtualArcs,$ArcValue['to'],$searchCandidateKey, $done));
                endif;
            endif;
        endforeach;

        return $arcsInCycle;
    }

    protected function pairwiseSort () : array
    {
        $pairs = [];  

        $i = 0;
        foreach ($this->_selfElection->getPairwise(false) as $candidate_key => $candidate_value) :
            foreach ($candidate_value['win'] as $challenger_key => $challenger_value) :

                if ($challenger_value > $candidate_value['lose'][$challenger_key]) :

                    // Victory
                    $pairs[$i]['from'] = $candidate_key;
                    // Defeat
                    $pairs[$i]['to'] = $challenger_key;

                    $pairs[$i]['win'] = $challenger_value;
                    $pairs[$i]['minority'] = $candidate_value['lose'][$challenger_key];
                    $pairs[$i]['margin'] = $candidate_value['win'][$challenger_key] - $candidate_value['lose'][$challenger_key];

                    $i++;

                endif;

            endforeach;
        endforeach;

        usort($pairs, function ($a, $b) : int {
            if ($a[static::RP_VARIANT_1] < $b[static::RP_VARIANT_1]) :
                return 1;
            elseif ($a[static::RP_VARIANT_1] > $b[static::RP_VARIANT_1]) :
                return -1;
            else : // Equal
                if ($a['minority'] > $b['minority']) :
                    return 1;
                elseif ($a['minority'] < $b['minority']) :
                    return -1;
                else : // Equal
                    return 0;
                endif;
            endif;
        });

        $newArcs = [];
        $i = 0;
        $f = true;
        foreach ($pairs as $pairsKey => $pairsValue) :
            if ($f === true) :
                $newArcs[$i][] = $pairs[$pairsKey];
                $f = false;
            elseif ($pairs[$pairsKey][static::RP_VARIANT_1] === $pairs[$pairsKey - 1][static::RP_VARIANT_1] && $pairs[$pairsKey]['minority'] === $pairs[$pairsKey - 1]['minority']) :
                $newArcs[$i][] = $pairs[$pairsKey];
            else :
                $newArcs[++$i][] = $pairs[$pairsKey];
            endif;
        endforeach;

        return $newArcs;
    }
}
