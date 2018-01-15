<?php
/*
    Condorcet PHP Class, with Schulze Methods and others !

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace Condorcet\ElectionProcess;

use Condorcet\Candidate;
use Condorcet\Condorcet;
use Condorcet\Election;
use Condorcet\CondorcetException;
use Condorcet\CondorcetVersion;
use Condorcet\Result;
use Condorcet\Algo\Pairwise;
use Condorcet\DataManager\VotesManager;
use Condorcet\Timer\Chrono as Timer_Chrono;


// Base Condorcet class
class ResultManager
{
    /////////// STATIC ///////////

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


/////////// CONSTRUCTOR ///////////

    use CondorcetVersion;

    protected $_Election;

    // Result
    protected $_Pairwise;
    protected $_Calculator;

        //////

    public function __construct (Election $election)
    {
        $this->setElection($election);
    }

    public function __clone ()
    {
        if ($this->_Pairwise !== null) :
            $this->_Pairwise = clone $this->_Pairwise;
            $this->_Pairwise->setElection($this->_Election);
        endif;
    }

    public function setElection (Election $election) : void
    {
        $this->_Election = $election;
    }

/////////// PUBLIC ///////////

    // Generic function for default result with ability to change default object method
    public function getResult ($method, array $options = []) : Result
    {
        $options = self::formatResultOptions($options);

        // Filter if tag is provided & return
        if ($options['%tagFilter']) :
            $chrono = new Timer_Chrono ($this->_Election->getTimerManager(), 'GetResult with filter');

            $filter = new Election;

            foreach ($this->_Election->getCandidatesList() as $candidate) :
                $filter->addCandidate($candidate);
            endforeach;

            foreach ($this->_Election->getVotesList($options['tags'], $options['withTag']) as $vote) :
                $filter->addVote($vote);
            endforeach;

            unset($chrono);

            return $filter->getResult($method);
        endif;

            ////// Start //////

        // Prepare
        $this->makePairwise();

            //////

        $chrono = new Timer_Chrono ($this->_Election->getTimerManager());

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


    public function getWinner (?string $substitution)
    {
        $algo = Condorcet::condorcetBasicSubstitution($substitution);

            //////

        if ($algo === Condorcet::CONDORCET_BASIC_CLASS) :
            new Timer_Chrono ($this->_Election->getTimerManager(), 'GetWinner for CondorcetBasic');
            $this->initResult($algo);
            $result = $this->_Calculator[$algo]->getWinner();

            return $this->formatWinner($result);
        else :
            return $this->getResult($algo)->getWinner();
        endif;
    }


    public function getLoser (?string $substitution)
    {
        $algo = Condorcet::condorcetBasicSubstitution($substitution);

            //////

        if ($algo === Condorcet::CONDORCET_BASIC_CLASS) :
            new Timer_Chrono ($this->_Election->getTimerManager(), 'GetLoser for CondorcetBasic');
            $this->initResult($algo);
            $result = $this->_Calculator[$algo]->getLoser();

            return $this->formatWinner($result);
        else :
            return $this->getResult($algo)->getLoser();
        endif;
    }


    public function computeResult ($method) : void
    {
        $this->getResult($method);
    }


    //:: INTERNAL :://


    // Prepare to compute results & caching system
    protected function makePairwise () : void
    {
        if ($this->_Pairwise === null) :
            $this->_Pairwise = new Pairwise ($this->_Election);
        endif;
    }


    protected function initResult (string $class) : void
    {
        if ( !isset($this->_Calculator[$class]) ) :
            $this->_Calculator[$class] = new $class($this->_Election);
        endif;
    }

    protected function formatWinner (?int $result)
    {
        return ($result === null) ? null : $this->_Election->getCandidateId($result);
    }

    //:: GET RAW DATA :://

    public function getPairwise () : Pairwise
    {
        $this->makePairwise();

        return $this->_Pairwise;
    }

}
