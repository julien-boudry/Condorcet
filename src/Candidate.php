<?php declare(strict_types=1);
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

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
     * Get the candidate name or change the candidate name.
     * @api
     */
    public string $name {
        get => end($this->nameHistory)['name']; // @phpstan-ignore offsetAccess.nonOffsetAccessible
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

            $this->nameHistory[] =  ['name' => $name, 'timestamp' => microtime(true)];
        }
    }

    /**
     * Return an history of each naming change, with timestamp.
     * @var array<int,array>
     * @api
     * @see Candidate::createdAt, Candidate::updatedAt
     */
    public protected(set) array $nameHistory = [];

    /**
     * When you create yourself the vote object, without use the Election::addVote or other native election method. And if you use string input (or array of string).
     * Then, these string input will be converted to into temporary candidate objects, marked as "provisional". Because you don't create the candidate yourself, they have a provisional status true.
     * When the vote will be added for the first time to an election, provisional candidate object with a name that matches an election candidate, will be converted into the election candidate. And first ranking will be save into Vote history (Vote::nameHistory).
     *
     * See VoteTest::testVoteHistory() test for a demonstration. In principle this is transparent from a usage point of view. If you want to avoid any non-strict comparisons, however, you should prefer to create your votes with the Election object, or with Candidate Objects in input. But, you must never getback a candidate marked as provisional in an another election in the same time, it's will not working.
     * @internal
     */
    public private(set) bool $provisionalState = false;

    // -------
    /**
     * Build a candidate.
     * @api
     * @book \CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\BookLibrary::Candidates
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
        unset($r['createdAt']); // Virtual property
        unset($r['updatedAt']); // Virtual property

        return $r;
    }

    // -------

    // SETTERS
    /**
     * Change the candidate name.
     * *If this will not cause conflicts if the candidate is already participating in elections and would namesake. This situation will throw an exception.*
     *
     * @api
     * @throws CandidateInvalidNameException If the name exceeds the maximum allowed length, contains invalid characters, or is already taken by another candidate in the election context.
     * - Exceeds maximum length: The name length exceeds `Election::MAX_CANDIDATE_NAME_LENGTH`.
     * - Contains invalid characters: The name contains prohibited characters such as `<`, `>`, `\n`, `\t`, `\0`, `^`, `*`, `$`, `:`, `;`, `||`, `"`, or `#`.
     * - Name conflict: The name is already taken by another candidate in the election context.
     *
     * @param $name Candidate Name.
     */
    public function setName(
        string $name
    ): static {
        $this->name = $name;

        return $this;
    }

    /** @internal */
    public function setProvisionalState(bool $provisional): void
    {
        $this->provisionalState = $provisional;
    }

    // GETTERS

    /**
     * The timestamp corresponding of the creation of this candidate.
     * @api
     * @see Candidate::updatedAt, Candidate::nameHistory
     */
    public float $createdAt {
        get => $this->nameHistory[0]['timestamp'];
    }

    /**
     * The timestamp corresponding of the last naming change.
     * @api
     * @see Candidate::createdAt, Candidate::nameHistory
     */
    public float $updatedAt {
        get => end($this->nameHistory)['timestamp']; // @phpstan-ignore offsetAccess.nonOffsetAccessible
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
