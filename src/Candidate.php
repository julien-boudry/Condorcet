<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet;

use CondorcetPHP\Condorcet\Throwable\CandidateExistsException;
use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{Description, Example, FunctionParameter, FunctionReturn, PublicAPI, Related, Throws};
use CondorcetPHP\Condorcet\Throwable\CandidateInvalidNameException;

class Candidate implements \Stringable
{
    use Linkable, CondorcetVersion;

    private array $_name = [];
    private bool $_provisional = false;

        ///

    #[PublicAPI]
    #[Description("Build a candidate.")]
    #[Example("Manual - Create Candidates","https://github.com/julien-boudry/Condorcet/wiki/II-%23-A.-Create-an-Election-%23-2.-Create-Candidates")]
    #[Related("Candidate::setName")]
    public function __construct (
        #[FunctionParameter('Candidate Name')]
        string $name
    )
    {
        $this->setName($name);
    }

    public function __toString (): string
    {
        return $this->getName();
    }

    public function __serialize (): array
    {
        $this->_link = null;

        $r = \get_object_vars($this);
        unset($r['_link']);

        return $r;
    }

        ///

    // SETTERS

    #[PublicAPI]
    #[Description("Change the candidate name.\n*If this will not cause conflicts if the candidate is already participating in elections and would namesake. This situation will throw an exception.*")]
    #[FunctionReturn("In case of success, return TRUE")]
    #[Throws(CandidateInvalidNameException::class)]
    public function setName (
        #[FunctionParameter('Candidate Name')]
        string $name
    ): bool
    {
        $name = \trim($name);

        if (\mb_strlen($name) > Election::MAX_CANDIDATE_NAME_LENGTH ) :
            throw new CandidateInvalidNameException($name);
        endif;

        if ( \preg_match('/<|>|\n|\t|\0|\^|\*|\$|:|;|(\|\|)|"|#/',$name) === 1 ) :
            throw new CandidateInvalidNameException($name);
        endif;

        if (!$this->checkNameInElectionContext($name)) :
            throw new CandidateExistsException("the name '$name' is taken by another candidate");
        endif;

        $this->_name[] =  [ 'name' => $name, 'timestamp' => \microtime(true) ];

        return true;
    }

    public function setProvisionalState (bool $provisional): void
    {
        $this->_provisional = $provisional;
    }

    // GETTERS

    #[PublicAPI]
    #[Description("Get the candidate name.")]
    #[FunctionReturn("Candidate name.")]
    #[Related("Candidate::getHistory", "Candidate::setName")]
    public function getName (): string
    {
        return \end($this->_name)['name'];
    }

    #[PublicAPI]
    #[Description("Return an history of each namming change, with timestamp.")]
    #[FunctionReturn("An explicit multi-dimenssional array.")]
    #[Related("Candidate::getCreateTimestamp")]
    public function getHistory (): array
    {
        return $this->_name;
    }

    #[PublicAPI]
    #[Description("Get the timestamp corresponding of the creation of this candidate.")]
    #[FunctionReturn("Timestamp")]
    #[Related("Candidate::getTimestamp")]
    public function getCreateTimestamp (): float
    {
        return $this->_name[0]['timestamp'];
    }

    #[PublicAPI]
    #[Description("Get the timestamp corresponding of the last namming change.")]
    #[FunctionReturn("Timestamp")]
    #[Related("Candidate::getCreateTimestamp")]
    public function getTimestamp (): float
    {
        return \end($this->_name)['timestamp'];
    }

    #[PublicAPI]
    #[Description("When you create yourself the vote object, without use the Election::addVote or other native election method. And if you use string input (or array of string).\nThen, these string input will be converted to into temporary candidate objects, named \"provisional\". because you don't create the candidate yourself. They have a provisonal statut true.\nWhen the vote will be added for the first time to an election, provisional candidate object with a name that matches an election candidate, will be converted into the election candidate. And first ranking will be save into Vote history (Vote::getHistory).\n\nSee VoteTest::testVoteHistory() test for a demonstration. In principle this is transparent from a usage point of view. If you want to avoid any non-strict comparisons, however, you should prefer to create your votes with the Election object, or with Candidate Objects in input. But, you must never getback a candidate marked as provisional in an another election in the same time, it's will not working.")]
    #[FunctionReturn("True if candidate object is in a provisional state, false else.")]
    public function getProvisionalState (): bool
    {
        return $this->_provisional;
    }

        ///

    // INTERNAL

    private function checkNameInElectionContext (string $name): bool
    {
        foreach ($this->getLinks() as $link => $value) :
            if (!$link->canAddCandidate($name)) :
                return false;
            endif;
        endforeach;

        return true;
    }
}
