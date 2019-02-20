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

use CondorcetPHP\Condorcet\Algo\Method;
use CondorcetPHP\Condorcet\Algo\MethodInterface;
use CondorcetPHP\Condorcet\Algo\Tools\PairwiseStats;

use CondorcetPHP\Condorcet\Result;

// DODGSON is a Condorcet Algorithm | http://en.wikipedia.org/wiki/DODGSON_method
abstract class PairwiseStatsBased_Core extends Method implements MethodInterface
{
    protected array $_Comparison;


/////////// PUBLIC ///////////


    // Get the ranking
    public function getResult () : Result
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


    // Get the stats
    protected function getStats () : array
    {
        $explicit = [];

        foreach ($this->_Comparison as $candidate_key => $value) :
            $explicit[$this->_selfElection->getCandidateObjectFromKey($candidate_key)->getName()] = [static::COUNT_TYPE => $value[static::COUNT_TYPE]];
        endforeach;

        return $explicit;
    }


/////////// COMPUTE ///////////


    //:: ALGORITHM. :://

    protected function makeRanking () : void
    {
        $result = [];

        // Calculate ranking
        $challenge = array ();
        $rank = 1;
        $done = 0;

        foreach ($this->_Comparison as $candidate_key => $candidate_data) :
            $challenge[$candidate_key] = $candidate_data[static::COUNT_TYPE];
        endforeach;

        while ($done < $this->_selfElection->countCandidates()) :
            $looking = $this->looking($challenge);

            foreach ($challenge as $candidate => $value) :
                if ($value === $looking) :
                    $result[$rank][] = $candidate;

                    $done++;
                    unset($challenge[$candidate]);
                endif;
            endforeach;

            $rank++;
        endwhile;

        $this->_Result = $this->createResult($result);
    }

    abstract protected function looking (array $challenge) : int;
}
