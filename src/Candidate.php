<?php

/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

declare(strict_types=1);

namespace CondorcetPHP\Condorcet;

use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\BookLibrary;
use CondorcetPHP\Condorcet\Throwable\{CandidateExistsException, CandidateInvalidNameException};
use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{Book, Description, FunctionParameter, FunctionReturn, PublicAPI, Related, Throws};
use CondorcetPHP\Condorcet\Relations\Linkable;
use Deprecated;

class Candidate implements \Stringable
{
    use Linkable;
    use CondorcetVersion;

    #[PublicAPI]
    public string $name {
        get => end($this->namesHistory)['name'];
        set {
            $name = mb_trim($value);

            if (mb_strlen($name) > Election::MAX_CANDIDATE_NAME_LENGTH) {
                throw new CandidateInvalidNameException($name);
            }

            if (preg_match('/<|>|\n|\t|\0|\^|\*|\$|:|;|(\|\|)|"|#/', $name) === 1) {
                throw new CandidateInvalidNameException($name);
            }

            if (!$this->checkNameInElectionContext($name)) {
                throw new CandidateExistsException("the name '{$name}' is taken by another candidate");
            }

            $this->namesHistory[] =  ['name' => $name, 'timestamp' => microtime(true)];
        }
    }

    private array $namesHistory = [];
    private bool $provisional = false;

    // -------

    #[PublicAPI]
    #[Description('Build a candidate.')]
    #[Book(BookLibrary::Candidates)]
    #[Related('Candidate::setName')]
    public function __construct(
        #[FunctionParameter('Candidate Name')]
        string $name
    ) {
        $this->name = $name;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function __serialize(): array
    {
        $this->link = null;

        $r = get_object_vars($this);
        unset($r['link']);
        unset($r['name']); // Virtual property

        return $r;
    }

    // -------

    // SETTERS

    #[PublicAPI]
    #[Description("Change the candidate name.\n*If this will not cause conflicts if the candidate is already participating in elections and would namesake. This situation will throw an exception.*")]
    #[FunctionReturn('In case of success, return TRUE')]
    #[Throws(CandidateInvalidNameException::class)]
    public function setName(
        #[FunctionParameter('Candidate Name')]
        string $name
    ): self {
        $this->name = $name;

        return $this;
    }

    public function setProvisionalState(bool $provisional): void
    {
        $this->provisional = $provisional;
    }

    // GETTERS

    #[Deprecated]
    #[PublicAPI]
    #[Description('Get the candidate name.')]
    #[FunctionReturn('Candidate name.')]
    #[Related('Candidate::getHistory', 'Candidate::setName')]
    public function getName(): string
    {
        return $this->name;
    }

    #[PublicAPI]
    #[Description('Return an history of each namming change, with timestamp.')]
    #[FunctionReturn('An explicit multi-dimenssional array.')]
    #[Related('Candidate::getCreateTimestamp')]
    public function getHistory(): array
    {
        return $this->namesHistory;
    }

    #[PublicAPI]
    #[Description('Get the timestamp corresponding of the creation of this candidate.')]
    #[FunctionReturn('Timestamp')]
    #[Related('Candidate::getTimestamp')]
    public function getCreateTimestamp(): float
    {
        return $this->namesHistory[0]['timestamp'];
    }

    #[PublicAPI]
    #[Description('Get the timestamp corresponding of the last namming change.')]
    #[FunctionReturn('Timestamp')]
    #[Related('Candidate::getCreateTimestamp')]
    public function getTimestamp(): float
    {
        return end($this->namesHistory)['timestamp'];
    }

    #[PublicAPI]
    #[Description("When you create yourself the vote object, without use the Election::addVote or other native election method. And if you use string input (or array of string).\nThen, these string input will be converted to into temporary candidate objects, named \"provisional\". because you don't create the candidate yourself. They have a provisonal statut true.\nWhen the vote will be added for the first time to an election, provisional candidate object with a name that matches an election candidate, will be converted into the election candidate. And first ranking will be save into Vote history (Vote::getHistory).\n\nSee VoteTest::testVoteHistory() test for a demonstration. In principle this is transparent from a usage point of view. If you want to avoid any non-strict comparisons, however, you should prefer to create your votes with the Election object, or with Candidate Objects in input. But, you must never getback a candidate marked as provisional in an another election in the same time, it's will not working.")]
    #[FunctionReturn('True if candidate object is in a provisional state, false else.')]
    public function getProvisionalState(): bool
    {
        return $this->provisional;
    }

    // -------

    // INTERNAL

    private function checkNameInElectionContext(string $name): bool
    {
        foreach ($this->getLinks() as $link) {
            if (!$link->canAddCandidate($name)) {
                return false;
            }
        }

        return true;
    }
}
