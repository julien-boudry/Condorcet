<?php declare(strict_types=1);

use CondorcetPHP\Condorcet\DataManager\ArrayManager;
use CondorcetPHP\Condorcet\{Election, Vote};

beforeEach(function (): void {
    $this->ArrayManager = new class (new Election) extends ArrayManager {
        public function preDeletedTask($object): void {}

        public function decodeOneEntity(string $data): Vote
        {
            $vote = new Vote($data);
            $this->getElection()->checkVoteCandidate($vote);
            $vote->registerLink($this->getElection());

            return $vote;
        }

        public function encodeOneEntity(Vote $data): string
        {
            $data->destroyLink($this->getElection());

            return str_replace([' > ', ' = '], ['>', '='], (string) $data);
        }
    };
});

test('offset set and offetset get', function (): void {
    expect($this->ArrayManager->key())->toBeNull();
    $this->ArrayManager[42] = 'foo';

    expect($this->ArrayManager[42])->toBe('foo');

    expect($this->ArrayManager[43])->toBeNull();
});
