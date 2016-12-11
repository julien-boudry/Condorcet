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
        public static $_maxCandidates = 7;

    // Ranked Pairs
    protected $_PairwiseSort;
    protected $_Arcs;
    protected $_Stats;
    protected $_StatsDone = false;


/////////// PUBLIC ///////////

    public function __construct (Election $mother)
    {
        parent::__construct($mother);

        if (!is_null(self::$_maxCandidates) && $this->_selfElection->countCandidates() > self::$_maxCandidates)
        {
            throw new CondorcetException( 101,self::$_maxCandidates.'|'.self::METHOD_NAME[0] );
        }
    }


    // Get the Ranked Pairs ranking
    public function getResult ($options = null) : Result
    {
        // Cache
        if ( $this->_Result !== null )
        {
            return $this->_Result;
        }

            //////

        // Sort pairwise
        $this->_PairwiseSort = PairwiseStats::PairwiseSort($this->_selfElection->getPairwise(false));

        // Ranking calculation
        $this->makeArcs();

        $result = [];

        $rang = 1;
        while (count($result) < $this->_selfElection->countCandidates())
        {
            $winner = $this->getOneWinner($result);

            foreach ($this->_Arcs as $ArcKey => $Arcvalue)
            {
                if ($Arcvalue['from'] === $winner || $Arcvalue['to'] === $winner )
                {
                    unset($this->_Arcs[$ArcKey]);
                }
            }

            $result[$rang++] = $winner;
        }

        // Return
        return $this->_Result = $this->createResult($result);
    }

    // Get the Ranked Pair ranking
    protected function getStats () : array
    {
        if (!$this->_StatsDone)
        {
            foreach ($this->_Stats as $ArcKey => &$Arcvalue)
            {
                foreach ($Arcvalue as $key => &$value)
                {
                    if ($key === 'from' || $key === 'to')
                    {
                        $value = $this->_selfElection->getCandidateId($value);
                    }
                }
            }

            $this->_StatsDone = true;
        }

        return $this->_Stats;
    }



/////////// COMPUTE ///////////


    //:: RANKED PAIRS ALGORITHM. :://

    protected function getOneWinner (array $result)
    {
        foreach ($this->_selfElection->getCandidatesList() as $candidateKey => $candidateId)
        {
            if (!in_array($candidateKey, $result, true))
            {
                $winner = true;
                foreach ($this->_Arcs as $ArcKey => $ArcValue)
                {
                    if ($ArcValue['to'] === $candidateKey)
                        { $winner = false;}
                }

                if ($winner)
                {
                    return $candidateKey;
                }
            }
        }
    }

    protected function makeArcs () : void
    {
        $this->_Arcs = [];

        foreach ($this->_PairwiseSort as $wise => $strength)
        {
            $ord = explode ('>',$wise);

            $this->_Arcs[] = array('from' => intval($ord[0]), 'to' => intval($ord[1]), 'strength' => $strength['score']);
        }

        foreach ($this->_Arcs as $key => $value)
        {
            if (!isset($this->_Arcs[$key]))
                {continue;}

            $this->checkingArc($value['from'], $value['to'], $value['from'].'-'.$value['to'], array($key));
        }

        $this->_Stats = $this->_Arcs;
    }

        protected function checkingArc ($candidate, $candidate_next, $construct, $done) : void
        {
            // Deleting arc
            if (count($done) > 1)
            {
                $test_cycle = explode('-', $construct);
                $count_cycle = array_count_values($test_cycle);

                if ($count_cycle[$candidate] > 1) // There is a cycle
                {                   
                    $this->delArc($test_cycle, $candidate);

                    return;
                }
            }

            foreach ($this->_Arcs as $new_arc_key => $new_arc)
            {
                if (!isset($this->_Arcs[$new_arc_key]))
                    {continue;}

                if (!in_array($new_arc_key, $done, true))
                {
                    if ($new_arc['from'] !== $candidate_next)
                    {
                        continue;
                    }

                    $done_next = $done;
                    $done_next[] = $new_arc_key;

                    // Recursive
                    $this->checkingArc($candidate, $new_arc['to'], $construct.'-'.$new_arc['to'], $done_next);
                }
            }
        }

        protected function delArc ($test_cycle, $candidate) : void
        {
            $cycles = [];

            $i = 1; $phase = false;
            foreach ($test_cycle as $value)
            {
                if ($i === 1 && !$phase)
                {
                    $cycles[$i] = '';
                    $cycles[$i] .= $value;

                    $phase = !$phase;

                    continue;
                }

                ///

                $cycles[$i] .= '>'.$value;

                if ($i + 1 < count($test_cycle))
                {
                    $cycles[$i + 1] = '';
                    $cycles[$i + 1] .= $value;
                }

                $i++;
            }

            $score = [];
            foreach ($cycles as $key => $value)
            {
                $score[$key] = $this->_PairwiseSort[$value]['score'];
            }

            $to_del = $cycles[array_search(min($score), $score, true)];
            $to_del = explode ('>', $to_del);


            foreach ($this->_Arcs as $key => $value)
            {
                if ($value['from'] == $to_del[0] && $value['to'] == $to_del[1])
                {
                    unset($this->_Arcs[$key]);
                }
            }
        }

}
