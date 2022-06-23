<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\ElectionProcess;

use CondorcetPHP\Condorcet\ElectionProcess\VoteUtil;
use CondorcetPHP\Condorcet\Throwable\VoteInvalidFormatException;
use PHPUnit\Framework\TestCase;

class VoteUtilTest extends TestCase
{
    public function testTypeMismatchTagsThrowAnException(): never
    {
        $this->expectException(VoteInvalidFormatException::class);
        $this->expectExceptionMessage('The format of the vote is invalid: every tag must be of type string, array given');
        VoteUtil::tagsConvert(['not', 'a', 'string:', []]);
    }

    public function testEmptyTagsThrowAnException(): never
    {
        $this->expectException(VoteInvalidFormatException::class);
        $this->expectExceptionMessage('The format of the vote is invalid: found empty tag');
        VoteUtil::tagsConvert('an , empty, tag , , in, the, middle');
    }

    /**
     * @dataProvider tagsProvider()
     */
    public function testTagsGetConverted($tags, $expected): void
    {
        $this->assertSame($expected, VoteUtil::tagsConvert($tags));
    }

    public function testGetRankingAsString(): void
    {
        // Empty ranking
        $this->assertEquals('', VoteUtil::getRankingAsString([]));

        // String ranking
        $this->assertEquals('A > B > C', VoteUtil::getRankingAsString(['A', 'B', 'C']));

        // Array ranking
        $this->assertEquals('A = B > C', VoteUtil::getRankingAsString([['A', 'B'], 'C']));

        // Unsorted array ranking
        $this->assertEquals('A = B > C', VoteUtil::getRankingAsString([['B', 'A'], 'C']));
    }

    public function testConvertVoteInput(): void
    {
        // Empty vote
        $this->assertEquals(
            [],
            VoteUtil::convertVoteInput('')
        );

        $this->assertEquals(
            [],
            VoteUtil::convertVoteInput('')
        );

        $this->assertEquals(
            [],
            VoteUtil::convertVoteInput('  >  ')
        );

        // Normal vote
        $this->assertEquals(
            [
                ['A'],
                ['D', 'B'],
                ['C'],
            ],
            VoteUtil::convertVoteInput('A   > D =    B > C ')
        );
    }

    public function tagsProvider(): iterable
    {
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
    }
}
