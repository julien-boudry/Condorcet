<?php
/*
    DODGSON part of the Condorcet PHP Class

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

// DODGSON is a Condorcet Algorithm | http://en.wikipedia.org/wiki/DODGSON_method
class Simpson extends Method implements MethodInterface
{
    // Method Name
    public const METHOD_NAME = ['Simpson','Simpson Method'];

    // DODGSON
    protected $_Comparison;


/////////// PUBLIC ///////////


    // Get the DODGSON ranking
    public function getResult ($options = null) : Result
    {
        // Cache
        if ( $this->_Result !== null ) :
            return $this->_Result;
        endif;

            //////

        // Comparison calculation
        $this->_Comparison = PairwiseStats::PairwiseComparison($this->_selfElection->getPairwise(false));

        // Ranking calculation
        $this->makeRanking();

        // Return
        return $this->_Result;
    }


    // Get the DODGSON ranking
    protected function getStats () : array
    {
        $explicit = [];

        foreach ($this->_Comparison as $candidate_key => $value)
        {
            $explicit[$this->_selfElection->getCandidateId($candidate_key, true)] = ['worst_defeat' => $value['worst_defeat']];
        }

        return $explicit;
    }



/////////// COMPUTE ///////////


    //:: DODGSON ALGORITHM. :://

    protected function makeRanking () : void
    {
        $result = [];

        // Calculate ranking
        $challenge = array ();
        $rank = 1;
        $done = 0;

        foreach ($this->_Comparison as $candidate_key => $candidate_data)
        {
            $challenge[$candidate_key] = $candidate_data['worst_defeat'];
        }

        while ($done < $this->_selfElection->countCandidates())
        {
            $looking = min($challenge);

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
