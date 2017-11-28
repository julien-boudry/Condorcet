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

// DODGSON Quick is an approximation for Dodgson method | https://www.maa.org/sites/default/files/pdf/cmj_ftp/CMJ/September%202010/3%20Articles/6%2009-229%20Ratliff/Dodgson_CMJ_Final.pdf
class DodgsonQuick extends Method implements MethodInterface
{
    // Method Name
    public const METHOD_NAME = ['Dodgson Quick','DodgsonQuick','Dodgson Quick Winner'];

    public function getResult () : Result
    {
        // Cache
        if ( $this->_Result !== null ) :
            return $this->_Result;
        endif;

        $this->computeDodgson();

        return $this->_Result;
    }

    protected function getStats () : array
    {
        return $this->_Stats;
    }


/////////// COMPUTE ///////////

    //:: DODGSON ALGORITHM. :://

    protected function computeDodgson () : void
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

        $dodgsonQuick = [];

        foreach ($HeadToHead as $candidateId => $CandidateTidemanScores) :
            $dodgsonQuick[$candidateId] = 0;

            foreach ($CandidateTidemanScores as $opponentId => $oneTidemanScore) :
                $dodgsonQuick[$candidateId] += ceil($oneTidemanScore / 2);
            endforeach;
        endforeach;
        asort($dodgsonQuick);

        $rank = 0;
        $result = [];

        if($basicCondorcetWinner = $this->_selfElection->getWinner(null)) :
            $result[++$rank][] = $this->_selfElection->getCandidateKey($basicCondorcetWinner);
        endif;

        $lastDodgsonQuickValue = null;

        foreach ($dodgsonQuick as $CandidateId => $dodgsonQuickValue) :
            if($lastDodgsonQuickValue === $dodgsonQuickValue) :
                $result[$rank][] = $CandidateId;
            else:
                $result[++$rank][] = $CandidateId;
                $lastDodgsonQuickValue = $dodgsonQuickValue;
            endif;
        endforeach;

        $this->_Stats = $dodgsonQuick;
        $this->_Result = $this->createResult($result);
    }

}
