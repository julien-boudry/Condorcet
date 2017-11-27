<?php
/*
    Dodgson part of the Condorcet PHP Class

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace Condorcet\Algo\Methods;

use Condorcet\Algo\Method;
use Condorcet\Algo\MethodInterface;
use Condorcet\Algo\Tools\PairwiseStats;
use Condorcet\Result;

// DODGSON is a Condorcet Algorithm | https://en.wikipedia.org/wiki/Dodgson%27s_method
class Dodgson extends Method implements MethodInterface
{
    // Method Name
    public const METHOD_NAME = ['Dodgson','Dodgson Method','Lewis Carroll'];

    public function getResult () : Result
    {
        // Cache
        if ( $this->_Result !== null ) :
            return $this->_Result;
        endif;

        $this->computeDodgson();
        $this->_Result = $this->createResult($this->_Result);

        return $this->_Result;
    }

    protected function getStats () : array
    {
        return $this->_Stats;
    }


/////////// COMPUTE ///////////

    //:: DODGSON ALGORITHM. :://

    protected function computeDodgson ()
    {
        $pairwise = $this->_selfElection->getPairwise(false);
        $HeadToHead = [];

        foreach ($pairwise as $candidateId => $CandidateStats) :
            foreach ($CandidateStats['lose'] as $opponentId => $CandidateLose) :
                if (($diff = $CandidateLose - $CandidateStats['win'][$opponentId]) >= 0) :
                    $HeadToHead[$candidateId][$opponentId] = $diff;
                endif;
            endforeach;
        endforeach;
        foreach ($HeadToHead as &$value) :
            asort($value);
        endforeach;

        $swaps = [];

        foreach ($HeadToHead as $CandidateId => $CandidateDeafeats) :
            $CandidateNeededWinCout = count($CandidateDeafeats);
            $swaps[$CandidateId] = 0;
            $i = 0;

            foreach ($CandidateDeafeats as $DeafeatMarging) :
                if ($i === $CandidateNeededWinCout) :
                    break;
                endif;

                $swaps[$CandidateId] += $DeafeatMarging;

                $i++;
            endforeach;
        endforeach;

        asort($swaps);

        // var_dump($HeadToHead);
        // var_dump($swaps);

        $rank = 0;

        if($basicCondorcetWinner = $this->_selfElection->getWinner(null)) :
            $this->_Result[++$rank][] = $this->_selfElection->getCandidateKey($basicCondorcetWinner);
        endif;

        $lastSwapsValue = null;

        foreach ($swaps as $CandidateId => $swapsValue) :
            if($lastSwapsValue === $swapsValue) :
                $this->_Result[$rank][] = $CandidateId;
            else:
                $this->_Result[++$rank][] = $CandidateId;
                $lastSwapsValue = $swapsValue;
            endif;
        endforeach;

        $this->_Stats = $swaps;
    }

}
