<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests;

use CondorcetPHP\Condorcet\{CondorcetUtil, Vote};
use PHPUnit\Framework\TestCase;

class CondorcetUtilTest extends TestCase
{
    public function testFormatVote(): void
    {
        $vote = new Vote('A>B>C');

        $this->assertSame('A > B > C', CondorcetUtil::format($vote, true));
    }

    public function testDeleteComments(): void
    {
        $result = CondorcetUtil::prepareParse("A > B # This is a comment", false);

        $this->assertSame(['A > B'], $result);
    }
}
