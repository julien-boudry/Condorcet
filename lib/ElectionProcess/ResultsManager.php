<?php
/*
    Condorcet PHP Class, with Schulze Methods and others !

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace Condorcet\ElectionProcess;

use Condorcet\Condorcet;
use Condorcet\CondorcetException;
use Condorcet\Result;
use Condorcet\Algo\Pairwise;
use Condorcet\Timer\Chrono as Timer_Chrono;

// Manage Results for Election class
trait ResultsManager
{

/////////// CONSTRUCTOR ///////////

    // Params
    protected $_ImplicitRanking = true;
    protected $_VoteWeightRule = false;

    // Result
    protected $_Pairwise;
    protected $_Calculator;


/////////// RESULTS TYPE ///////////


    public function getImplicitRankingRule () : bool
    {
        return $this->_ImplicitRanking;
    }

    public function setImplicitRanking (bool $rule = true) : bool
    {
        $this->_ImplicitRanking = $rule;
        $this->cleanupResult();
        return $this->getImplicitRankingRule();
    }


/////////// RESULTS ///////////


    //:: PUBLIC FUNCTIONS :://

    // Generic function for default result with ability to change default object method
    public function getResult ($method = true, array $options = []) : Result
    {
        $options = $this->formatResultOptions($options);

        // Filter if tag is provided & return
        if ($options['%tagFilter']) :
            $chrono = new Timer_Chrono ($this->_timer, 'GetResult with filter');

            $filter = new self;

            foreach ($this->getCandidatesList() as $candidate) :
                $filter->addCandidate($candidate);
            endforeach;

            foreach ($this->getVotesList($options['tags'], $options['withTag']) as $vote) :
                $filter->addVote($vote);
            endforeach;

            unset($chrono);

            return $filter->getResult($method);
        endif;

            ////// Start //////

        // Prepare
        $this->prepareResult();

            //////

        $chrono = new Timer_Chrono ($this->_timer);

        if ($method === true) :
            $this->initResult(Condorcet::getDefaultMethod());
            $result = $this->_Calculator[Condorcet::getDefaultMethod()]->getResult();
        elseif ($method = Condorcet::isAuthMethod((string) $method)) :
            $this->initResult($method);
            $result = $this->_Calculator[$method]->getResult();
        else :
            throw new CondorcetException(8,$method);
        endif;

        $chrono->setRole('GetResult for '.$method);

        return $result;
    }


    public function getWinner (?string $substitution = null)
    {
        $algo = Condorcet::condorcetBasicSubstitution($substitution);

            //////

        if ($algo === Condorcet::CONDORCET_BASIC_CLASS) :
            new Timer_Chrono ($this->_timer, 'GetWinner for CondorcetBasic');
            $this->initResult($algo);
            $result = $this->_Calculator[$algo]->getWinner();

            return ($result === null) ? null : $this->getCandidateId($result);
        else :
            return $this->getResult($algo)->getWinner();
        endif;
    }


    public function getLoser (?string $substitution = null)
    {
        $algo = Condorcet::condorcetBasicSubstitution($substitution);

            //////

        if ($algo === Condorcet::CONDORCET_BASIC_CLASS) :
            new Timer_Chrono ($this->_timer, 'GetLoser for CondorcetBasic');
            $this->initResult($algo);
            $result = $this->_Calculator[$algo]->getLoser();

            return ($result === null) ? null : $this->getCandidateId($result);
        else :
            return $this->getResult($algo)->getLoser();
        endif;
    }

    public function computeResult ($method = true) : void
    {
        $this->getResult($method);
    }


    //:: TOOLS FOR RESULT PROCESS :://


    // Prepare to compute results & caching system
    protected function prepareResult () : bool
    {
        if ($this->_State > 2) :
            return false;
        elseif ($this->_State === 2) :
            $this->cleanupResult();

            // Do Pairewise
            $this->_Pairwise = new Pairwise ($this);

            // Change state to result
            $this->_State = 3;

            // Return
            return true;
        else :
            throw new CondorcetException(6);
        endif;
    }


    protected function initResult (string $class) : void
    {
        if ( !isset($this->_Calculator[$class]) ) :
            $this->_Calculator[$class] = new $class($this);
        endif;
    }


    // Cleanup results to compute again with new votes
    protected function cleanupResult () : void
    {
        // Reset state
        if ($this->_State > 2) : 
            $this->_State = 2;
        endif;

            //////

        // Clean pairwise
        $this->_Pairwise = null;

        // Algos
        $this->_Calculator = null;
    }


    protected function formatResultOptions (array $arg) : array
    {
        // About tag filter
        if (isset($arg['tags'])):
            $arg['%tagFilter'] = true;

            if ( !isset($arg['withTag']) || !is_bool($arg['withTag']) ) :
                $arg['withTag'] = true;
            endif;
        else:
            $arg['%tagFilter'] = false;
        endif;

        return $arg;
    }


    //:: GET RAW DATA :://

    public function getPairwise (bool $explicit = true)
    {
        $this->prepareResult();

        return (!$explicit) ? $this->_Pairwise : $this->_Pairwise->getExplicitPairwise();
    }
}
