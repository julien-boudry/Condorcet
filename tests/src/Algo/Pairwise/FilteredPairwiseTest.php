<?php declare(strict_types=1);

use CondorcetPHP\Condorcet\Election;

beforeEach(function (): void {
    $this->election1 = new Election;

    $this->election1->addCandidate('A');
    $this->election1->addCandidate('B');
    $this->election1->addCandidate('C');

    $this->election1->addVote('A>B>C');
    $this->election1->parseVotes('tag1 || C>B>A');
});

test('filtered pairwise results 1', function (): void {
    $this->election1->removeAllVotes();
    $this->election1->allowsVoteWeight(true);

    $this->election1->parseVotes('
            A > B > C
            tag1 || C > B > A
            tag2 || A > B > C ^2
        ');

    $filteredwithoutTag1 = $this->election1->getExplicitFilteredPairwiseByTags('tag1', false);
    $filteredWithTag2 = $this->election1->getExplicitFilteredPairwiseByTags('tag2');
    $filteredWithTag2AndTag1 = $this->election1->getExplicitFilteredPairwiseByTags('tag2,tag1');
    $normalPairwise = $this->election1->getExplicitPairwise();

    // Test $filteredwithoutTag1
    expect($filteredwithoutTag1)->toBe([
        'A' => [
            'win' => [
                'B' => 3,
                'C' => 3,
            ],
            'null' => [
                'B' => 0,
                'C' => 0,
            ],
            'lose' => [
                'B' => 0,
                'C' => 0,
            ],
        ],
        'B' => [
            'win' => [
                'A' => 0,
                'C' => 3,
            ],
            'null' => [
                'A' => 0,
                'C' => 0,
            ],
            'lose' => [
                'A' => 3,
                'C' => 0,
            ],
        ],
        'C' => [
            'win' => [
                'A' => 0,
                'B' => 0,
            ],
            'null' => [
                'A' => 0,
                'B' => 0,
            ],
            'lose' => [
                'A' => 3,
                'B' => 3,
            ],
        ]]);

    // Test $filteredwithTag2
    expect($filteredWithTag2)->toBe([
        'A' => [
            'win' => [
                'B' => 2,
                'C' => 2,
            ],
            'null' => [
                'B' => 0,
                'C' => 0,
            ],
            'lose' => [
                'B' => 0,
                'C' => 0,
            ],
        ],
        'B' => [
            'win' => [
                'A' => 0,
                'C' => 2,
            ],
            'null' => [
                'A' => 0,
                'C' => 0,
            ],
            'lose' => [
                'A' => 2,
                'C' => 0,
            ],
        ],
        'C' => [
            'win' => [
                'A' => 0,
                'B' => 0,
            ],
            'null' => [
                'A' => 0,
                'B' => 0,
            ],
            'lose' => [
                'A' => 2,
                'B' => 2,
            ],
        ]]);

    // Test $filteredwithTag2AndTag1
    expect($filteredWithTag2AndTag1)->toBe([
        'A' => [
            'win' => [
                'B' => 2,
                'C' => 2,
            ],
            'null' => [
                'B' => 0,
                'C' => 0,
            ],
            'lose' => [
                'B' => 1,
                'C' => 1,
            ],
        ],
        'B' => [
            'win' => [
                'A' => 1,
                'C' => 2,
            ],
            'null' => [
                'A' => 0,
                'C' => 0,
            ],
            'lose' => [
                'A' => 2,
                'C' => 1,
            ],
        ],
        'C' => [
            'win' => [
                'A' => 1,
                'B' => 1,
            ],
            'null' => [
                'A' => 0,
                'B' => 0,
            ],
            'lose' => [
                'A' => 2,
                'B' => 2,
            ],
        ]]);

    // Test NormalPairwise
    expect($normalPairwise)->toBe([
        'A' => [
            'win' => [
                'B' => 3,
                'C' => 3,
            ],
            'null' => [
                'B' => 0,
                'C' => 0,
            ],
            'lose' => [
                'B' => 1,
                'C' => 1,
            ],
        ],
        'B' => [
            'win' => [
                'A' => 1,
                'C' => 3,
            ],
            'null' => [
                'A' => 0,
                'C' => 0,
            ],
            'lose' => [
                'A' => 3,
                'C' => 1,
            ],
        ],
        'C' => [
            'win' => [
                'A' => 1,
                'B' => 1,
            ],
            'null' => [
                'A' => 0,
                'B' => 0,
            ],
            'lose' => [
                'A' => 3,
                'B' => 3,
            ],
        ]]);
});

test('serialize filtered pairwise', function (): void {
    $filteredPairwise = $this->election1->getFilteredPairwiseByTags('tag1');

    expect($filteredPairwise[2]['win'][0])->toBe(1);

    $explicitOriginalFromFilteredPairwise = $filteredPairwise->getExplicitPairwise();

    $serializedFilteredPairwise = serialize($filteredPairwise);
    unset($filteredPairwise);

    $filteredPairwise = unserialize($serializedFilteredPairwise);

    expect($filteredPairwise->getExplicitPairwise())->toBe($explicitOriginalFromFilteredPairwise);
    expect($filteredPairwise->tags)->toBe(['tag1']);
    expect($filteredPairwise->withTags)->toBeTrue();
});

test('modify filtered pairwise', function (): void {
    $this->election1->parseVotes('tag1 || C>B>A');

    $filteredPairwise = $this->election1->getFilteredPairwiseByTags('tag1');

    expect($filteredPairwise[2]['win'][0])->toBe(2);

    $filteredPairwise->removeVote(1);
    expect($filteredPairwise[2]['win'][0])->toBe(1);

    $newVote = $this->election1->addVote('A>B>C');
    $newVote->addTags('tag1');

    expect($filteredPairwise[0]['win'][2])->toBe(0);
    $filteredPairwise->addNewVote($this->election1->getVoteKey($newVote));
    expect($filteredPairwise[0]['win'][2])->toBe(1);

    $this->election1 = null;

    // destroy reference and weak reference
    // Explicit pairwise must continue to find original candidates names
    expect($filteredPairwise->getExplicitPairwise())->toHaveKey('A');
    expect($filteredPairwise->getExplicitPairwise())->toHaveKey('B');
    expect($filteredPairwise->getExplicitPairwise())->toHaveKey('C');
});

test('filtered pairwise results 2', function (): void {
    $this->election1->removeAllVotes();
    $this->election1->allowsVoteWeight(true);

    $this->election1->parseVotes('
            A > B > C
            tag1 || B > C > A
            tag2 || A > B > C
            tag1, tag2 || C > B > A *2
        ');

    $filteredWithBothTags = $this->election1->getExplicitFilteredPairwiseByTags(['tag1', 'tag2'], 2);

    // Test $filteredwithBothTags
    expect($filteredWithBothTags)->toBe([
        'A' => [
            'win' => [
                'B' => 0,
                'C' => 0,
            ],
            'null' => [
                'B' => 0,
                'C' => 0,
            ],
            'lose' => [
                'B' => 2,
                'C' => 2,
            ],
        ],
        'B' => [
            'win' => [
                'A' => 2,
                'C' => 0,
            ],
            'null' => [
                'A' => 0,
                'C' => 0,
            ],
            'lose' => [
                'A' => 0,
                'C' => 2,
            ],
        ],
        'C' => [
            'win' => [
                'A' => 2,
                'B' => 2,
            ],
            'null' => [
                'A' => 0,
                'B' => 0,
            ],
            'lose' => [
                'A' => 0,
                'B' => 0,
            ],
        ]]);
});
