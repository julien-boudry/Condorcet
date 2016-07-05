<?php
/*
    Copeland part of the Condorcet PHP Class

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace Condorcet\Algo\Methods;

use Condorcet\Algo\Method;
use Condorcet\Algo\MethodInterface;
use Condorcet\Algo\Tools\PairwiseStats;
use Condorcet\CondorcetException;
use Condorcet\Result;

// Copeland is a Condorcet Algorithm | http://en.wikipedia.org/wiki/Copeland_method
class Copeland extends Method implements MethodInterface
{
    // Method Name
    const METHOD_NAME = ['Copeland'];

    // Copeland
    protected $_Comparison;


/////////// PUBLIC ///////////


    // Get the Coepland ranking
    public function getResult ($options = null) : Result
    {
        // Cache
        if ( $this->_Result !== null )
        {
            return $this->_Result;
        }

            //////

        // Comparison calculation
        $this->_Comparison = PairwiseStats::PairwiseComparison($this->_selfElection->getPairwise(false));

        // Ranking calculation
        $this->makeRanking();

        // Return
        return $this->_Result;
    }


    // Get the Copeland ranking
    protected function getStats () : array
    {
        $explicit = [];

        foreach ($this->_Comparison as $candidate_key => $value)
        {
            $explicit[$this->_selfElection->getCandidateId($candidate_key, true)] = $value;
        }

        return $explicit;
    }



/////////// COMPUTE ///////////


    //:: COPELAND ALGORITHM. :://

    protected function makeRanking ()
    {
        $result = [];

        // Calculate ranking
        $challenge = array ();
        $rank = 1;
        $done = 0;

        foreach ($this->_Comparison as $candidate_key => $candidate_data)
        {
            $challenge[$candidate_key] = $candidate_data['balance'];
        }

        while ($done < $this->_selfElection->countCandidates())
        {
            $looking = max($challenge);

            foreach ($challenge as $candidate => $value)
            {
                if ($value === $looking)
                {
                    $result[$rank][] = $candidate;

                    $done++; unset($challenge[$candidate]);
                }
            }

            $rank++;
        }

        $this->_Result = $this->createResult($result);
    }

}
