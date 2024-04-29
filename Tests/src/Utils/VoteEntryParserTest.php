<?php

declare(strict_types=1);
use CondorcetPHP\Condorcet\Throwable\VoteInvalidFormatException;
use CondorcetPHP\Condorcet\Tools\Converters\CEF\CondorcetElectionFormat;
use CondorcetPHP\Condorcet\Utils\VoteEntryParser;

dataset('voteBadNumericValueProvider', function () {
    yield [
        'entry' => 'A>B ^g',
        'message' => "the value 'g' is not an integer.",
    ];

    yield [
        'entry' => 'A>B ^a*b',
        'message' => "the value 'b' is not an integer.",
    ];

    yield [
        'entry' => 'A>B*b',
        'message' => "the value 'b' is not an integer.",
    ];

    yield [
        'entry' => 'A>B ^4Y ',
        'message' => "the value '4Y' is not an integer.",
    ];
});
dataset('voteEntriesProvider', function () {
    yield [
        'entry' => 'A >B = C>D',
        'expected' => [
            'comment' => null,
            'multiple' => 1,
            'ranking' => [
                ['A'],
                ['B', 'C'],
                ['D'],
            ],
            'tags' => null,
            'weight' => 1,
        ],
    ];

    yield [
        'entry' => 'tag1,     tag2   ||    A >B = C>D ^ 7 * 42 #      One Comment',
        'expected' => [
            'comment' => 'One Comment',
            'multiple' => 42,
            'ranking' => [
                ['A'],
                ['B', 'C'],
                ['D'],
            ],
            'tags' => ['tag1', 'tag2'],
            'weight' => 7,
        ],
    ];

    yield [
        'entry' => 'A >B = C>D *42#One Comment',
        'expected' => [
            'comment' => 'One Comment',
            'multiple' => 42,
            'ranking' => [
                ['A'],
                ['B', 'C'],
                ['D'],
            ],
            'tags' => null,
            'weight' => 1,
        ],
    ];

    yield [
        'entry' => 'A^     7#',
        'expected' => [
            'comment' => '',
            'multiple' => 1,
            'ranking' => [
                ['A'],
            ],
            'tags' => null,
            'weight' => 7,
        ],
    ];

    yield [
        'entry' => '   ',
        'expected' => [
            'comment' => null,
            'multiple' => 1,
            'ranking' => null,
            'tags' => null,
            'weight' => 1,
        ],
    ];

    yield [
        'entry' => ' tag1,tag2||  ',
        'expected' => [
            'comment' => null,
            'multiple' => 1,
            'ranking' => null,
            'tags' => ['tag1', 'tag2'],
            'weight' => 1,
        ],
    ];

    yield [
        'entry' => '^7*4  ',
        'expected' => [
            'comment' => null,
            'multiple' => 4,
            'ranking' => null,
            'tags' => null,
            'weight' => 7,
        ],
    ];

    yield [
        'entry' => ' #One Comment',
        'expected' => [
            'comment' => 'One Comment',
            'multiple' => 1,
            'ranking' => null,
            'tags' => null,
            'weight' => 1,
        ],
    ];

    yield [
        'entry' => 'tag1,tag2||' . CondorcetElectionFormat::SPECIAL_KEYWORD_EMPTY_RANKING . '^7*42#FeteDuDindon',
        'expected' => [
            'comment' => 'FeteDuDindon',
            'multiple' => 42,
            'ranking' => [],
            'tags' => ['tag1', 'tag2'],
            'weight' => 7,
        ],
    ];

    yield [
        'entry' => ' ' . CondorcetElectionFormat::SPECIAL_KEYWORD_EMPTY_RANKING . ' ',
        'expected' => [
            'comment' => null,
            'multiple' => 1,
            'ranking' => [],
            'tags' => null,
            'weight' => 1,
        ],
    ];
});

test('bad numeric value', function (string $entry, string $message): void {
    $this->expectException(VoteInvalidFormatException::class);
    $this->expectExceptionMessage($message);

    new VoteEntryParser($entry);
})->with('voteBadNumericValueProvider');

test('votes entries', function (string $entry, array $expected): void {
    $parser = new VoteEntryParser($entry);

    expect($parser->originalEntry)->toBe($entry);

    expect($parser->comment)->toBe($expected['comment']);
    expect($parser->multiple)->toBe($expected['multiple']);
    expect($parser->ranking)->toBe($expected['ranking']);
    expect($parser->tags)->toBe($expected['tags']);
    expect($parser->weight)->toBe($expected['weight']);
})->with('voteEntriesProvider');
