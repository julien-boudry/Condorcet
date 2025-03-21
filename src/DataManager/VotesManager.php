<?php

/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\DataManager;

use CondorcetPHP\Condorcet\{Vote};
use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{Throws};
use CondorcetPHP\Condorcet\ElectionProcess\ElectionState;
use CondorcetPHP\Condorcet\Throwable\Internal\AlreadyLinkedException;
use CondorcetPHP\Condorcet\Throwable\{TagsFilterException, VoteException, VoteManagerException};
use CondorcetPHP\Condorcet\Tools\Converters\CEF\CondorcetElectionFormat;

class VotesManager extends ArrayManager
{
    /////////// Data CallBack for external drivers ///////////

    #[\Override]
    protected function decodeOneEntity(string $data): Vote
    {
        $vote = new Vote($data);
        $vote->registerLink($this->getElection());
        $vote->notUpdate = true;
        $this->getElection()->checkVoteCandidate($vote);
        $vote->notUpdate = false;

        return $vote;
    }

    #[\Override]
    protected function encodeOneEntity(Vote $data): string
    {
        if (($election = $this->getElection()) !== null) {
            $data->destroyLink($election);
        }

        return str_replace([' > ', ' = '], ['>', '='], (string) $data);
    }

    #[\Override]
    protected function preDeletedTask(Vote $object): void
    {
        $object->destroyLink($this->getElection());
    }

    /////////// Array Access - Specials improvements ///////////

    #[\Override]
    public function offsetGet(mixed $offset): Vote
    {
        return parent::offsetGet($offset);
    }

    #[Throws(VoteManagerException::class)]
    #[\Override]
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($value instanceof Vote) {
            try {
                $value->registerLink($this->getElection());
            } catch (AlreadyLinkedException $e) {
                // Security : Check if vote object is not already registered and not present at offset
                if (($this->Cache[$offset] ?? $this->Container[$offset] ?? false) !== $value) {
                    throw new VoteException('This vote is already linked to the election');
                }
            }

            parent::offsetSet((\is_int($offset) ? $offset : null), $value);
            $this->UpdateAndResetComputing(key: $this->maxKey, type: VotesManagerEvent::NewVote);
        } else {
            throw new VoteManagerException('value must be a Vote object.');
        }

        $this->checkRegularize();
    }

    #[\Override]
    public function offsetUnset(mixed $offset): void
    {
        if ($this->offsetExists($offset)) {
            $this->UpdateAndResetComputing(key: $offset, type: VotesManagerEvent::RemoveVote);
            $this->offsetGet($offset)->destroyLink($this->getElection());
            parent::offsetUnset($offset);
        }
    }

    /////////// Internal Election related methods ///////////

    public function UpdateAndResetComputing(int $key, VotesManagerEvent $type): void
    {
        $election = $this->getElection();

        if ($election->state === ElectionState::VOTES_REGISTRATION) {
            match ($type) {
                VotesManagerEvent::NewVote, VotesManagerEvent::FinishUpdateVote => $election->getPairwise()->addNewVote($key),
                VotesManagerEvent::RemoveVote, VotesManagerEvent::VoteUpdateInProgress => $election->getPairwise()->removeVote($key),
            };

            $election->resetMethodsComputation();
        } else {
            $election->setStateToVote();
        }
    }


    /////////// Get Votes Methods ///////////

    public function getVoteKey(Vote $vote): ?int
    {
        ($r = array_search(needle: $vote, haystack: $this->Container, strict: true)) !== false || ($r = array_search(needle: $vote, haystack: $this->Cache, strict: true));

        return ($r !== false) ? $r : null;
    }

    protected function getFullVotesListGenerator(): \Generator
    {
        foreach ($this as $voteKey => $vote) {
            yield $voteKey => $vote;
        }
    }

    protected function getPartialVotesListGenerator(array $tags, bool|int $with): \Generator
    {
        if (\is_bool($with)) {
            $with = ($with) ? 1 : 0;
        } elseif ($with < 0) {
            throw new TagsFilterException('Value of $with cannot be less than 0. Actual value is ' . $with);
        }

        foreach ($this as $voteKey => $vote) {
            $tagsfound = 0;
            foreach ($tags as $oneTag) {
                if (\in_array(needle: $oneTag, haystack: $vote->tags, strict: true)) {
                    if (++$tagsfound === $with) {
                        yield $voteKey => $vote;
                        break;
                    }
                }
            }

            if ($with === 0 && $tagsfound === 0) {
                yield $voteKey => $vote;
            }
        }
    }

    // Get the votes list
    /** @return array<int, Vote> */
    public function getVotesList(?array $tags = null, bool|int $with = true): array
    {
        if ($tags === null) {
            return $this->getFullDataSet();
        } else {
            $search = [];

            foreach ($this->getPartialVotesListGenerator($tags, $with) as $voteKey => $vote) {
                $search[$voteKey] = $vote;
            }

            return $search;
        }
    }

    // Get the votes list as a generator object
    public function getVotesListGenerator(?array $tags = null, bool|int $with = true): \Generator
    {
        return ($tags === null) ? $this->getFullVotesListGenerator() : $this->getPartialVotesListGenerator($tags, $with);
    }

    public function getVotesValidUnderConstraintGenerator(?array $tags = null, bool|int $with = true): \Generator
    {
        $election = $this->getElection();

        foreach ($this->getVotesListGenerator($tags, $with) as $voteKey => $oneVote) {
            if (!$election->testIfVoteIsValidUnderElectionConstraints($oneVote)) {
                continue;
            }

            yield $voteKey => $oneVote;
        }
    }

    public function getVotesListAsString(bool $withContext): string
    {
        $election = $this->getElection();

        $simpleList = '';

        $weight = [];
        $nb = [];

        foreach ($this as $oneVote) {
            $oneVoteString = $oneVote->getSimpleRanking($withContext ? $election : null);

            if (!\array_key_exists(key: $oneVoteString, array: $weight)) {
                $weight[$oneVoteString] = 0;
            }
            if (!\array_key_exists(key: $oneVoteString, array: $nb)) {
                $nb[$oneVoteString] = 0;
            }

            if ($election->isVoteWeightAllowed()) {
                $weight[$oneVoteString] += $oneVote->getWeight();
            } else {
                $weight[$oneVoteString]++;
            }

            $nb[$oneVoteString]++;
        }

        ksort($weight, \SORT_NATURAL);
        arsort($weight);

        $isFirst = true;
        foreach ($weight as $key => $value) {
            if (!$isFirst) {
                $simpleList .= "\n";
            }
            $voteString = ($key === '') ? CondorcetElectionFormat::SPECIAL_KEYWORD_EMPTY_RANKING : $key;
            $simpleList .= $voteString . ' * ' . $nb[$key];
            $isFirst = false;
        }

        return $simpleList;
    }

    public function countVotes(?array $tags, bool|int $with): int
    {
        if ($tags === null) {
            return \count($this);
        } else {
            $count = 0;

            foreach ($this->getPartialVotesListGenerator($tags, $with) as $vote) {
                $count++;
            }

            return $count;
        }
    }

    public function countValidVotesWithConstraints(?array $tags, bool|int $with): int
    {
        $election = $this->getElection();
        $count = 0;

        foreach ($this->getVotesListGenerator($tags, $with) as $oneVote) {
            if ($election->testIfVoteIsValidUnderElectionConstraints($oneVote)) {
                $count++;
            }
        }

        return $count;
    }

    public function countInvalidVoteWithConstraints(): int
    {
        $election = $this->getElection();
        $count = 0;

        foreach ($this as $oneVote) {
            if (!$election->testIfVoteIsValidUnderElectionConstraints($oneVote)) {
                $count++;
            }
        }

        return $count;
    }

    public function sumVotesWeight(?array $tags, bool|int $with): int
    {
        return $this->processSumVotesWeight(tags: $tags, with: $with, constraints: false);
    }

    public function sumVotesWeightWithConstraints(?array $tags, bool|int $with): int
    {
        return $this->processSumVotesWeight(tags: $tags, with: $with, constraints: true);
    }

    protected function processSumVotesWeight(?array $tags, bool|int $with, bool $constraints): int
    {
        $election = $this->getElection();
        $sum = 0;

        foreach ($this->getVotesListGenerator($tags, $with) as $oneVote) {
            if (!$constraints || $election->testIfVoteIsValidUnderElectionConstraints($oneVote)) { # They are no constraints check OR the vote is valid.
                $sum += $election->isVoteWeightAllowed() ? $oneVote->getWeight() : 1;
            }
        }

        return $sum;
    }
}
