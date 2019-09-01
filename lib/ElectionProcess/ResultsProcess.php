<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\ElectionProcess;

use CondorcetPHP\Condorcet\{Candidate, Condorcet, Result};
use CondorcetPHP\Condorcet\Algo\Pairwise;
use CondorcetPHP\Condorcet\Throwable\CondorcetException;
use CondorcetPHP\Condorcet\Timer\Chrono as Timer_Chrono;

// Manage Results for Election class
trait ResultsProcess
{

/////////// CONSTRUCTOR ///////////

    // Result
    protected ?Pairwise $_Pairwise = null;
    protected ?array $_Calculator = null;


/////////// GET RESULTS ///////////

    // Generic function for default result with ability to change default object method
    public function getResult (?string $method = null, array $options = []) : Result
    {
        $options = self::formatResultOptions($options);

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

        if ($method === null) :
            $this->initResult(Condorcet::getDefaultMethod());
            $result = $this->_Calculator[Condorcet::getDefaultMethod()]->getResult();
        elseif ($method = Condorcet::getMethodClass((string) $method)) :
            $this->initResult($method);
            $result = $this->_Calculator[$method]->getResult();
        else :
            throw new CondorcetException(8);
        endif;

        $chrono->setRole('GetResult for '.$method);

        return $result;
    }


    public function getWinner (?string $method = null)
    {
        $algo = Condorcet::condorcetBasicSubstitution($method);

            //////

        if ($algo === Condorcet::CONDORCET_BASIC_CLASS) :
            new Timer_Chrono ($this->_timer, 'GetWinner for CondorcetBasic');
            $this->initResult($algo);
            $result = $this->_Calculator[$algo]->getWinner();

            return ($result === null) ? null : $this->getCandidateObjectFromKey($result);
        else :
            return $this->getResult($algo)->getWinner();
        endif;
    }


    public function getLoser (?string $method = null)
    {
        $algo = Condorcet::condorcetBasicSubstitution($method);

            //////

        if ($algo === Condorcet::CONDORCET_BASIC_CLASS) :
            new Timer_Chrono ($this->_timer, 'GetLoser for CondorcetBasic');
            $this->initResult($algo);
            $result = $this->_Calculator[$algo]->getLoser();

            return ($result === null) ? null : $this->getCandidateObjectFromKey($result);
        else :
            return $this->getResult($algo)->getLoser();
        endif;
    }

    public function getCondorcetWinner () : ?Candidate
    {
        return $this->getWinner(null);
    }

    public function getCondorcetLoser () : ?Candidate
    {
        return $this->getLoser(null);
    }

    public function getPairwise () : Pairwise
    {
        $this->prepareResult();
        return $this->_Pairwise;
    }

    public function getExplicitPairwise () : array
    {
        return $this->getPairwise()->getExplicitPairwise();
    }



/////////// MAKE RESULTS ///////////

    public function computeResult (?string $method = null) : void
    {
        $this->getResult($method);
    }

    protected function makePairwise () : void
    {
        $this->_Pairwise = new Pairwise ($this);
    }

    protected function initResult (string $class) : void
    {
        if ( !isset($this->_Calculator[$class]) ) :
            $this->_Calculator[$class] = new $class($this);
        endif;
    }

    // Cleanup results to compute again with new votes
    protected function cleanupCompute () : void
    {
        // Clean pairwise
        $this->cleanupPairwise();

        // Algos
        $this->cleanupCalculator();
    }

    public function cleanupPairwise () : void
    {
        // Reset state
        if ($this->_State > 2) : 
            $this->_State = 2;
        endif;

        $this->_Pairwise = null;
        $this->cleanupCalculator();
    }

    public function cleanupCalculator () : void
    {
        $this->_Calculator = null;
    }


/////////// UTILS ///////////

    protected static function formatResultOptions (array $arg) : array
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
}
