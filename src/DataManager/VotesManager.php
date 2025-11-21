<?php declare(strict_types=1);
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

namespace CondorcetPHP\Condorcet\DataManager;

use CondorcetPHP\Condorcet\Vote;
use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\Throws;
use CondorcetPHP\Condorcet\ElectionProcess\ElectionState;
use CondorcetPHP\Condorcet\Throwable\Internal\AlreadyLinkedException;
use CondorcetPHP\Condorcet\Throwable\{TagsFilterException, VoteException, VoteManagerException};
use CondorcetPHP\Condorcet\Tools\Converters\CEF\CondorcetElectionFormat;

/**
 * Manages vote storage with optional external data handler support.
 */
class VotesManager extends ArrayManager
{
    /////////// Data CallBack for external drivers ///////////

    #[\Override]
    protected function decodeOneEntity(string $data): Vote
    {
        $election = $this->getElectionOrFail();

        $vote = new Vote($data);

        $vote->registerLink($election);
        $vote->notUpdate = true;
        $election->checkVoteCandidate($vote);
        $vote->notUpdate = false;

        return $vote;
    }

    #[\Override]
    protected function encodeOneEntity(Vote $data): string
    {
        $election = $this->getElection();

        if ($election !== null) {
            $data->destroyLink($election);
        }

        return str_replace([' > ', ' = '], ['>', '='], (string) $data);
    }

    #[\Override]
    protected function preDeletedTask(Vote $object): void
    {
        $object->destroyLink($this->getElectionOrFail());
    }

    /////////// Array Access - Specials improvements ///////////

    #[\Override]
    public function offsetGet(mixed $offset): Vote
    {
        return parent::offsetGet($offset);
    }

    /**
     * @throws VoteManagerException
     */
    #[\Override]
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($value instanceof Vote) {
            try {
                $value->registerLink($this->getElectionOrFail());
            } catch (AlreadyLinkedException) {
                // Security : Check if vote object is not already registered and not present at offset
                if (!is_int($offset) || ($this->Cache[$offset] ?? $this->Container[$offset] ?? false) !== $value) {
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
            $this->offsetGet($offset)->destroyLink($this->getElectionOrFail());
            parent::offsetUnset($offset);
        }
    }

    /////////// Internal Election related methods ///////////

    public function UpdateAndResetComputing(int $key, VotesManagerEvent $type): void
    {
        $election = $this->getElectionOrFail();

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
        $election = $this->getElectionOrFail();

        foreach ($this->getVotesListGenerator($tags, $with) as $voteKey => $oneVote) {
            if (!$election->isVoteValidUnderConstraints($oneVote)) {
                continue;
            }

            yield $voteKey => $oneVote;
        }
    }

    public function getVotesListAsString(bool $withContext): string
    {
        $election = $this->getElectionOrFail();

        $simpleList = '';

        $weight = [];
        $nb = [];

        foreach ($this as $oneVote) {
            $oneVoteString = $oneVote->getRankingAsString($withContext ? $election : null);

            if (!\array_key_exists(key: $oneVoteString, array: $weight)) {
                $weight[$oneVoteString] = 0;
            }
            if (!\array_key_exists(key: $oneVoteString, array: $nb)) {
                $nb[$oneVoteString] = 0;
            }

            if ($election->authorizeVoteWeight) {
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
        $election = $this->getElectionOrFail();
        $count = 0;

        foreach ($this->getVotesListGenerator($tags, $with) as $oneVote) {
            if ($election->isVoteValidUnderConstraints($oneVote)) {
                $count++;
            }
        }

        return $count;
    }

    public function countInvalidVoteWithConstraints(): int
    {
        $election = $this->getElectionOrFail();
        $count = 0;

        foreach ($this as $oneVote) {
            if (!$election->isVoteValidUnderConstraints($oneVote)) {
                $count++;
            }
        }

        return $count;
    }

    public function sumVoteWeights(?array $tags, bool|int $with): int
    {
        return $this->processsumVoteWeights(tags: $tags, with: $with, constraints: false);
    }

    public function sumVoteWeightsWithConstraints(?array $tags, bool|int $with): int
    {
        return $this->processsumVoteWeights(tags: $tags, with: $with, constraints: true);
    }

    protected function processsumVoteWeights(?array $tags, bool|int $with, bool $constraints): int
    {
        $election = $this->getElectionOrFail();
        $sum = 0;

        foreach ($this->getVotesListGenerator($tags, $with) as $oneVote) {
            if (!$constraints || $election->isVoteValidUnderConstraints($oneVote)) { # They are no constraints check OR the vote is valid.
                $sum += $election->authorizeVoteWeight ? $oneVote->getWeight() : 1;
            }
        }

        return $sum;
    }
}
