<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\ElectionProcess;

use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{Description, Example, FunctionReturn, PublicAPI, Related};
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
    #[PublicAPI]
    #[Description("Get a full ranking from an advanced Condorcet method.\n*Have a look on the [supported method](https://github.com/julien-boudry/Condorcet/wiki/I-%23-Installation-%26-Basic-Configuration-%23-2.-Condorcet-Methods), or create [your own algorithm](https://github.com/julien-boudry/Condorcet/wiki/III-%23-C.-Extending-Condorcet-%23-1.-Add-your-own-ranking-algorithm).*")]
    #[FunctionReturn("An Condorcet/Result Object (implementing ArrayAccess and Iterator, can be use like an array ordered by rank)")]
    #[Example("Manual - Ranking from Condorcet Method","https://github.com/julien-boudry/Condorcet/wiki/II-%23-C.-Result-%23-2.-Get-Ranking-from-Condorcet-advanced-Methods")]
    #[Related("Election::getWinner", "Election::getResult", "Condorcet::getDefaultMethod")]
    public function getResult (?string $method = null, array $options = []) : Result
    {
        $options = self::formatResultOptions($options);

        // Filter if tag is provided & return
        if ($options['%tagFilter']) :
            $chrono = (Condorcet::$UseTimer === true) ? new Timer_Chrono ($this->_timer, 'GetResult with filter') : null;

            $filter = new self;

            foreach ($this->getCandidatesList() as $candidate) :
                $filter->addCandidate($candidate);
            endforeach;

            foreach ($this->getVotesList(tags: $options['tags'], with: $options['withTag']) as $vote) :
                $filter->addVote($vote);
            endforeach;

            unset($chrono);

            return $filter->getResult($method);
        endif;

            ////// Start //////

        // Prepare
        $this->preparePairwiseAndCleanCompute();

            //////

        $chrono = (Condorcet::$UseTimer === true) ? new Timer_Chrono ($this->_timer) : null;

        if ($method === null) :
            $this->initResult(Condorcet::getDefaultMethod());
            $result = $this->_Calculator[Condorcet::getDefaultMethod()]->getResult();
        elseif ($method = Condorcet::getMethodClass((string) $method)) :
            $this->initResult($method);
            $result = $this->_Calculator[$method]->getResult();
        else :
            throw new CondorcetException(8);
        endif;

        ($chrono !== null) && $chrono->setRole('GetResult for '.$method);

        return $result;
    }


    #[PublicAPI]
    #[Description("Get the natural Condorcet winner if there is one. Alternatively you can get the winner(s) from an advanced Condorcet algorithm.")]
    #[FunctionReturn("Candidate object given. Null if there are no available winner or loser.\n\nIf you use an advanced method instead of Natural, you can get an array with multiples winners.\n\nThrow an exception on error.")]
    #[Example("Manual - Natural Condorcet","https://github.com/julien-boudry/Condorcet/wiki/II-%23-C.-Result-%23-1.-Natural-Condorcet")]
    #[Related("Election::getCondorcetWinner", "Election::getLoser", "Election::getResult")]
    public function getWinner (?string $method = null) : array|Candidate|null
    {
        $algo = Condorcet::condorcetBasicSubstitution($method);

            //////

        if ($algo === Condorcet::CONDORCET_BASIC_CLASS) :
            (Condorcet::$UseTimer === true) && new Timer_Chrono ($this->_timer, 'GetWinner for CondorcetBasic');
            $this->initResult($algo);
            $result = $this->_Calculator[$algo]->getWinner();

            return ($result === null) ? null : $this->getCandidateObjectFromKey($result);
        else :
            return $this->getResult($algo)->getWinner();
        endif;
    }


    #[PublicAPI]
    #[Description("Get the natural Condorcet loser if there is one. Alternatively you can get the loser(s) from an advanced Condorcet algorithm.")]
    #[FunctionReturn("Candidate object given. Null if there are no available winner or loser.\n\nIf you use an advanced method instead of Natural, you can get an array with multiples losers.\n\nThrow an exception on error.")]
    #[Example("Manual - Natural Condorcet","https://github.com/julien-boudry/Condorcet/wiki/II-%23-C.-Result-%23-1.-Natural-Condorcet")]
    #[Related("Election::getWinner", "Election::getResult")]
    public function getLoser (?string $method = null) : array|Candidate|null
    {
        $algo = Condorcet::condorcetBasicSubstitution($method);

            //////

        if ($algo === Condorcet::CONDORCET_BASIC_CLASS) :
            (Condorcet::$UseTimer === true) && new Timer_Chrono ($this->_timer, 'GetLoser for CondorcetBasic');
            $this->initResult($algo);
            $result = $this->_Calculator[$algo]->getLoser();

            return ($result === null) ? null : $this->getCandidateObjectFromKey($result);
        else :
            return $this->getResult($algo)->getLoser();
        endif;
    }

    #[PublicAPI]
    #[Description("Get the natural Condorcet winner if there is one.")]
    #[FunctionReturn("Candidate object given. Null if there are no available winner.")]
    #[Example("Manual - Natural Condorcet","https://github.com/julien-boudry/Condorcet/wiki/II-%23-C.-Result-%23-1.-Natural-Condorcet")]
    #[Related("Election::getCondorcetLoser", "Election::getWiner", "Election::getResult")]
    public function getCondorcetWinner () : ?Candidate
    {
        return $this->getWinner(null);
    }

    #[PublicAPI]
    #[Description("Get the natural Condorcet loser if there is one.")]
    #[FunctionReturn("Candidate object given. Null if there are no available loser.")]
    #[Example("Manual - Natural Condorcet","https://github.com/julien-boudry/Condorcet/wiki/II-%23-C.-Result-%23-1.-Natural-Condorcet")]
    #[Related("Election::getCondorcetWinner", "Election::getLoser", "Election::getResult")]
    public function getCondorcetLoser () : ?Candidate
    {
        return $this->getLoser(null);
    }

    #[PublicAPI]
    #[Description("Return the Pairwise.")]
    #[FunctionReturn("Pairwise object.")]
    #[Example("Manual - Advanced Results","https://github.com/julien-boudry/Condorcet/wiki/II-%23-C.-Result-%23-4.-Advanced-Results-Management")]
    #[Related("Election::getExplicitPairwise", "Election::getResult")]
    public function getPairwise () : Pairwise
    {
        if ($this->_Pairwise === null) :
            $this->preparePairwiseAndCleanCompute();
        endif;

        return $this->_Pairwise;
    }

    #[PublicAPI]
    #[Description("Return the Pairwise.")]
    #[FunctionReturn("Pairwise as an explicit array .")]
    #[Related("Election::getPairwise", "Election::getResult")]
    public function getExplicitPairwise () : array
    {
        return $this->getPairwise()->getExplicitPairwise();
    }

    // Generic function for default result with ability to change default object method
    #[PublicAPI]
    #[Description("Set an option to a method module and reset his cache for this election object. Be aware that this option applies to all election objects and remains in memory.")]
    #[FunctionReturn("True on success. Else False.")]
    #[Related("Result::getMethodOptions")]
    public function setMethodOption (string $method, string $optionName, mixed $optionValue) : bool
    {
        if ($method = Condorcet::getMethodClass($method)) :
            $method::setOption($optionName, $optionValue);
            unset($this->_Calculator[$method]);

            return true;
        else :
            return false;
        endif;
    }



/////////// MAKE RESULTS ///////////

    #[PublicAPI]
    #[Description("Really similar to Election::getResult() but not return anything. Just calculates silently and fill the cache.")]
    #[Related("Election::getWinner", "Election::getResult", "Condorcet::getDefaultMethod")]
    public function computeResult (?string $method = null) : void
    {
        $this->getResult($method);
    }

    protected function makePairwise () : void
    {
        $this->cleanupCalculator();
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
        // Algos
        $this->cleanupCalculator();

        // Clean pairwise
        $this->_Pairwise = null;
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

            if ( !isset($arg['withTag']) || !\is_bool($arg['withTag']) ) :
                $arg['withTag'] = true;
            endif;
        else:
            $arg['%tagFilter'] = false;
        endif;

        return $arg;
    }
}
