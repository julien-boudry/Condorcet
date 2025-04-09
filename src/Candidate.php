<?php

/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

declare(strict_types=1);

namespace CondorcetPHP\Condorcet;

use CondorcetPHP\Condorcet\Throwable\{CandidateExistsException, CandidateInvalidNameException};
use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{Book, Throws};
use CondorcetPHP\Condorcet\Relations\Linkable;
use Deprecated;

class Candidate implements \Stringable
{
    use Linkable;
    use CondorcetVersion;

    /**
     * @api
     */
    public string $name {
        get => end($this->namesHistory)['name']; // @phpstan-ignore offsetAccess.nonOffsetAccessible
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

    /** @var array<int,array> */
    private array $namesHistory = [];
    private bool $provisional = false;

    // -------
    /**
     * Build a candidate.
     * @api
     * @book \CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\BookLibrary::Candidates
     * @see Candidate::setName
     * @param $name Candidate Name.
     */
    public function __construct(
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
    /**
     * Change the candidate name.
     * *If this will not cause conflicts if the candidate is already participating in elections and would namesake. This situation will throw an exception.*
     * @api
     * @return mixed In case of success, return TRUE
     * @throws CandidateInvalidNameException
     * @param $name Candidate Name.
     */
    public function setName(
        string $name
    ): static {
        $this->name = $name;

        return $this;
    }

    public function setProvisionalState(bool $provisional): void
    {
        $this->provisional = $provisional;
    }

    // GETTERS
    /**
     * Get the candidate name.
     * @api
     * @return mixed Candidate name.
     * @see Candidate::getHistory, Candidate::setName
     */
    #[Deprecated]
    public function getName(): string
    {
        return $this->name;
    }
    /**
     * Return an history of each naming change, with timestamp.
     * @api
     * @return mixed An explicit multi-dimensional array.
     * @see Candidate::getCreateTimestamp
     */
    public function getHistory(): array
    {
        return $this->namesHistory;
    }
    /**
     * Get the timestamp corresponding of the creation of this candidate.
     * @api
     * @return mixed Timestamp
     * @see Candidate::getTimestamp
     */
    public function getCreateTimestamp(): float
    {
        return $this->namesHistory[0]['timestamp'];
    }
    /**
     * Get the timestamp corresponding of the last naming change.
     * @api
     * @return mixed Timestamp
     * @see Candidate::getCreateTimestamp
     */
    public function getTimestamp(): float
    {
        return end($this->namesHistory)['timestamp']; // @phpstan-ignore offsetAccess.nonOffsetAccessible
    }
    /**
     * When you create yourself the vote object, without use the Election::addVote or other native election method. And if you use string input (or array of string).
     * Then, these string input will be converted to into temporary candidate objects, marked as "provisional". Because you don't create the candidate yourself, they have a provisional status true.
     * When the vote will be added for the first time to an election, provisional candidate object with a name that matches an election candidate, will be converted into the election candidate. And first ranking will be save into Vote history (Vote::getHistory).
     *
     * See VoteTest::testVoteHistory() test for a demonstration. In principle this is transparent from a usage point of view. If you want to avoid any non-strict comparisons, however, you should prefer to create your votes with the Election object, or with Candidate Objects in input. But, you must never getback a candidate marked as provisional in an another election in the same time, it's will not working.
     * @api
     * @return mixed True if candidate object is in a provisional state, false else.
     */
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
