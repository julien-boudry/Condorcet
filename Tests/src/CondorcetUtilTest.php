<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests;

use CondorcetPHP\Condorcet\Utils\CondorcetUtil;
use CondorcetPHP\Condorcet\Vote;
use PHPUnit\Framework\TestCase;

class CondorcetUtilTest extends TestCase
{
    public function testFormatVote(): void
    {
        $vote = new Vote('A>B>C');

        expect(CondorcetUtil::format($vote, true))->toBe('A > B > C');
    }

    public function testDeleteComments(): void
    {
        $result = CondorcetUtil::prepareParse('A > B # This is a comment', false);

        expect($result)->toBe(['A > B']);
    }
}
