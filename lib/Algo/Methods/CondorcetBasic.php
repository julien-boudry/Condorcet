<?php
/*
    Basic Condorcet Winner & Loser core part of the Condorcet PHP Class

    Last modified at: Condorcet Class v0.95

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
namespace Condorcet\Algo\Methods;

use Condorcet\Algo\Method;
use Condorcet\Algo\MethodInterface;
use Condorcet\CondorcetException;

// Condorcet Basic Class, provide natural Condorcet winner or looser
class CondorcetBasic extends Method implements MethodInterface
{
    // Method Name
    const METHOD_NAME = 'CondorcetBasic';

    // Basic Condorcet
    protected $_CondorcetWinner;
    protected $_CondorcetLoser;


/////////// PUBLIC ///////////


    public function getResult ($options = null)
    {
        throw new CondorcetException (102);
    }


    public function getStats ()
    {
        return $this->_selfElection->getPairwise();
    }


    // Get a Condorcet certified winner. If there is none = null. You can force a winner choice with alternative supported methods ($substitution)
    public function getWinner ()
    {
        // Cache
        if ( $this->_CondorcetWinner !== null )
        {
            return $this->_CondorcetWinner;
        }

            //////

        // Basic Condorcet calculation
        foreach ( $this->_selfElection->getPairwise(false) as $candidate_key => $candidat_detail )
        {
            $winner = true;

            foreach ($candidat_detail['win'] as $challenger_key => $win_count )
            {
                if  ( $win_count <= $candidat_detail['lose'][$challenger_key] )
                {
                    $winner = false;
                    break;
                }
            }

            if ($winner)
            {
                $this->_CondorcetWinner = $candidate_key;

                return $this->_CondorcetWinner;
            }
        }

            return null;
    }

    // Get a Condorcet certified loser. If there is none = null. You can force a winner choice with alternative supported methods ($substitution)
    public function getLoser ()
    {
        // Cache
        if ( $this->_CondorcetLoser !== null )
        {
            return $this->_CondorcetLoser;
        }

            //////

        // Basic Condorcet calculation
        foreach ( $this->_selfElection->getPairwise(false) as $candidate_key => $candidat_detail )
        {
            $loser = true;

            foreach ( $candidat_detail['lose'] as $challenger_key => $lose_count )
            {
                if  ( $lose_count <= $candidat_detail['win'][$challenger_key] )
                {  
                    $loser = false;
                    break;
                }
            }

            if ($loser)
            { 
                $this->_CondorcetLoser = $candidate_key;

                return $this->_CondorcetLoser;
            }
        }

            return null;
    }

}
