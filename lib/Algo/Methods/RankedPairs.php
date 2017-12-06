<?php
/*
    Ranked Pairs part of the Condorcet PHP Class

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace Condorcet\Algo\Methods;

use Condorcet\Algo\Method;
use Condorcet\Algo\MethodInterface;
use Condorcet\Algo\Tools\PairwiseStats;
use Condorcet\CondorcetException;
use Condorcet\Election;
use Condorcet\Result;

// Ranker Pairs is a Condorcet Algorithm | http://en.wikipedia.org/wiki/Ranked_Pairs
class RankedPairs extends Method implements MethodInterface
{
    // Method Name
    public const METHOD_NAME = ['Ranked Pairs','RankedPairs','Tideman method'];

    // Limits
        public static $_maxCandidates = 40;

    // Ranked Pairs
    protected $_PairwiseSort;
    protected $_Arcs;
    protected $_Stats;
    protected $_StatsDone = false;


/////////// PUBLIC ///////////

    public function __construct (Election $mother)
    {
        parent::__construct($mother);

        if (!is_null(self::$_maxCandidates) && $this->_selfElection->countCandidates() > self::$_maxCandidates) :
            throw new CondorcetException( 101,self::$_maxCandidates.'|'.self::METHOD_NAME[0] );
        endif;
    }


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

        // Make Result      
        return $this->_Result = $this->createResult($this->makeResult());
    }

    // Get the Ranked Pair ranking
    protected function getStats () : array
    {
        if (!$this->_StatsDone) :
            foreach ($this->_Stats as $ArcKey => &$Arcvalue) :
                foreach ($Arcvalue as $key => &$value) :
                    if ($key === 'from' || $key === 'to') :
                        $value = $this->_selfElection->getCandidateId($value);
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
        $lastWinner = null;

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
        $this->_Arcs = [];

        foreach ($this->_PairwiseSort as $newArcsRound) :
            $virtualArcs = $this->_Arcs;
            $testNewsArcs = [];
            $candidatesToCheck = [];

            $newKey = max((empty($highKey = array_keys($virtualArcs)) ? [-1] : $highKey)) + 1;
            foreach ($newArcsRound as $newArc) :
                $virtualArcs[$newKey] = [ 'from' => $newArc['victory'], 'to' => $newArc['defeat'] ];
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

        $this->_Stats = $this->_Arcs;
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
        $score = [];  

        $i = 0;
        foreach ($this->_selfElection->getPairwise(false) as $candidate_key => $candidate_value) :
            foreach ($candidate_value['win'] as $challenger_key => $challenger_value) :

                if ($challenger_value > $candidate_value['lose'][$challenger_key]) :

                    $score[$i]['victory'] = $candidate_key;
                    $score[$i]['defeat'] = $challenger_key;

                    $score[$i]['win'] = $challenger_value;
                    $score[$i]['minority'] = $candidate_value['lose'][$challenger_key];
                    $score[$i]['margin'] = $candidate_value['win'][$challenger_key] - $candidate_value['lose'][$challenger_key];

                    $i++;

                endif;

            endforeach;
        endforeach;

        usort($score, function ($a, $b) : int {
            if ($a['win'] < $b['win']) : return 1;
            elseif ($a['win'] > $b['win']) : return -1;
            elseif ($a['win'] === $b['win']) :
                if ($a['minority'] > $b['minority']) :
                    return 1;
                elseif ($a['minority'] < $b['minority']) :
                    return -1;
                elseif ($a['minority'] === $b['minority']) :
                    return 0;
                endif;
            endif;
        });

        $newArcs = [];
        $i = 0;
        $f = true;
        foreach ($score as $scoreKey => $scoreValue) :
            if ($f === true) :
                $newArcs[$i][] = $score[$scoreKey];
                $f = false;
            elseif ($score[$scoreKey]['win'] === $score[$scoreKey - 1]['win'] && $score[$scoreKey]['minority'] === $score[$scoreKey - 1]['minority']) :
                $newArcs[$i][] = $score[$scoreKey];
            else :
                $newArcs[++$i][] = $score[$scoreKey];
            endif;
        endforeach;

        return $newArcs;
    }

}
