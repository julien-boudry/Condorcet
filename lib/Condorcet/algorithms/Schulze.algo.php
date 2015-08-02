<?php
/*
    Schulze part of the Condorcet PHP Class

    Last modified at: Condorcet Class v0.93

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
namespace Condorcet ;


// Schulze is a Condorcet Algorithm | http://en.wikipedia.org/wiki/Schulze_method
abstract class Schulze_Core extends namespace\CondorcetAlgo implements namespace\AlgoInterface
{
    // Schulze
    protected $_StrongestPaths ;
    protected $_Result ;



/////////// PUBLIC ///////////

    abstract protected function schulzeVariant (&$i,&$j);

    public function getResult ($options = null)
    {
        // Cache
        if ( $this->_Result !== null )
        {
            return $this->_Result ;
        }

            //////

        // Format array
        $this->prepareStrongestPath() ;

        // Strongest Paths calculation
        $this->makeStrongestPaths() ;

        // Ranking calculation
        $this->makeRanking() ;


        // Return
        return $this->_Result ;
    }


    // Get the Schulze ranking
    public function getStats ()
    {
        $this->getResult();

            //////

        $explicit = array() ;

        foreach ($this->_StrongestPaths as $candidate_key => $candidate_value)
        {
            $candidate_key = $this->_selfElection->getCandidateId($candidate_key, true);

            foreach ($candidate_value as $challenger_key => $challenger_value)
            {
                $explicit[$candidate_key][$this->_selfElection->getCandidateId($challenger_key, true)] = $challenger_value;
            }
        }

        return $explicit ;
    }



/////////// COMPUTE ///////////


    //:: SCHULZE ALGORITHM. :://


    // Calculate the strongest Paths for Schulze Method
    protected function prepareStrongestPath ()
    {
        $this->_StrongestPaths = array() ;

        foreach ( $this->_selfElection->getCandidatesList() as $candidate_key => $candidate_id )
        {
            $this->_StrongestPaths[$candidate_key] = array() ;

            // Format array for the strongest path
            foreach ( $this->_selfElection->getCandidatesList() as $candidate_key_r => $candidate_id_r )
            {
                if ($candidate_key_r != $candidate_key)
                {
                    $this->_StrongestPaths[$candidate_key][$candidate_key_r]    = 0 ;
                }
            }
        }               
    }


    // Calculate the Strongest Paths
    protected function makeStrongestPaths ()
    {
        foreach ($this->_selfElection->getCandidatesList() as $i => $i_value)
        {
            foreach ($this->_selfElection->getCandidatesList() as $j => $j_value)
            {
                if ($i !== $j)
                {
                    if ( $this->_selfElection->getPairwise(false)[$i]['win'][$j] > $this->_selfElection->getPairwise(false)[$j]['win'][$i] )
                    {
                        $this->_StrongestPaths[$i][$j] = $this->schulzeVariant($i,$j);                      
                    }
                    else
                    {
                        $this->_StrongestPaths[$i][$j] = 0 ;
                    }

                }
            }
        }
         

        foreach ($this->_selfElection->getCandidatesList() as $i => $i_value)
        {
            foreach ($this->_selfElection->getCandidatesList() as $j => $j_value)
            {
                if ($i !== $j)
                {
                    foreach ($this->_selfElection->getCandidatesList() as $k => $k_value)
                    {
                        if ($i !== $k && $j !== $k)
                        {
                            $this->_StrongestPaths[$j][$k] = 
                                            max( 
                                                    $this->_StrongestPaths[$j][$k], 
                                                    min( $this->_StrongestPaths[$j][$i], $this->_StrongestPaths[$i][$k] )
                                                ) ;
                        }
                    }
                }
            }
        }
    }


    // Calculate && Format human readable ranking
    protected function makeRanking ()
    {       
        $this->_Result = array() ;

        // Calculate ranking
        $done = array () ;
        $rank = 1 ;

        while (count($done) < $this->_selfElection->countCandidates())
        {
            $to_done = array() ;

            foreach ( $this->_StrongestPaths as $candidate_key => $challengers_key )
            {
                if ( in_array($candidate_key, $done, true) )
                {
                    continue ;
                }

                $winner = true ;

                foreach ($challengers_key as $beaten_key => $beaten_value)
                {
                    if ( in_array($beaten_key, $done, true) )
                    {
                        continue ;
                    }

                    if ( $beaten_value < $this->_StrongestPaths[$beaten_key][$candidate_key] )
                    {
                        $winner = false ;
                    }
                }

                if ($winner)
                {
                    $this->_Result[$rank][] = $candidate_key ;

                    $to_done[] = $candidate_key ;
                }
            }

            $done = array_merge($done, $to_done);

            $rank++ ;
        }
    }

}


class Schulze extends namespace\Schulze_Core
{
    protected function schulzeVariant (&$i, &$j) {
        return $this->_selfElection->getPairwise(false)[$i]['win'][$j];
    }
}

class Schulze_Margin extends namespace\Schulze_Core
{
    protected function schulzeVariant (&$i, &$j) {
        return $this->_selfElection->getPairwise(false)[$i]['win'][$j] - $this->_selfElection->getPairwise(false)[$j]['win'][$i];
    }
}

class Schulze_Ratio extends namespace\Schulze_Core
{
    protected function schulzeVariant (&$i, &$j) {
        return ($this->_selfElection->getPairwise(false)[$j]['win'][$i] !== 0) ?
                                                                                    ($this->_selfElection->getPairwise(false)[$i]['win'][$j] / $this->_selfElection->getPairwise(false)[$j]['win'][$i]) : 0 ;
    }
}

// Registering algorithm
namespace\Condorcet::addAlgos( array('Schulze', 'Schulze_Margin', 'Schulze_Ratio') ) ;
