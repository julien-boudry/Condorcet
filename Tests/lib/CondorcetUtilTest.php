<?php
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests;

use CondorcetPHP\Condorcet\CondorcetUtil;
use CondorcetPHP\Condorcet\Vote;

use PHPUnit\Framework\TestCase;

class CondorcetUtilTest extends TestCase
{
    public function testFormatVote (): void
    {
        $vote = new Vote ('A>B>C');

        self::assertSame('A > B > C',CondorcetUtil::format($vote,true));
    }
}