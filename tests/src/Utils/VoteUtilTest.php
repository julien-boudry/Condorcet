<?php declare(strict_types=1);

use CondorcetPHP\Condorcet\Utils\VoteUtil;
use CondorcetPHP\Condorcet\Throwable\VoteInvalidFormatException;

dataset('tagsProvider', function () {
    yield 'null tags' => [
        'tags' => null,
        'expected' => null,
    ];

    yield 'empty string' => [
        'tags' => '',
        'expected' => null,
    ];

    yield 'empty array' => [
        'tags' => [],
        'expected' => null,
    ];

    yield 'tags as string' => [
        'tags' => 'these, are,   some   , tags',
        'expected' => ['these', 'are', 'some', 'tags'],
    ];

    yield 'tags as array' => [
        'tags' => ['these', 'are', 'some', 'more', 'tags'],
        'expected' => ['these', 'are', 'some', 'more', 'tags'],
    ];
});

test('type mismatch tags throw an exception', function (): void {
    $this->expectException(VoteInvalidFormatException::class);
    $this->expectExceptionMessage('The format of the vote is invalid: every tag must be of type string, array given');

    VoteUtil::tagsConvert(['not', 'a', 'string:', []]);
});

test('empty tags throw an exception', function (): void {
    $this->expectException(VoteInvalidFormatException::class);
    $this->expectExceptionMessage('The format of the vote is invalid: found empty tag');

    VoteUtil::tagsConvert('an , empty, tag , , in, the, middle');
});

test('tags get converted', function ($tags, $expected): void {
    expect(VoteUtil::tagsConvert($tags))->toBe($expected);
})->with('tagsProvider');

test('get ranking as string', function (): void {
    // Empty ranking
    expect(VoteUtil::getRankingAsString([]))->toBe('');

    // String ranking
    expect(VoteUtil::getRankingAsString(['A', 'B', 'C']))->toBe('A > B > C');

    // Array ranking
    expect(VoteUtil::getRankingAsString([['A', 'B'], 'C']))->toBe('A = B > C');

    // Unsorted array ranking
    expect(VoteUtil::getRankingAsString([['B', 'A'], 'C']))->toBe('A = B > C');
});
