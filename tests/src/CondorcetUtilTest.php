<?php declare(strict_types=1);

use CondorcetPHP\Condorcet\Utils\CondorcetUtil;
use CondorcetPHP\Condorcet\Vote;

test('format vote', function (): void {
    $vote = new Vote('A>B>C');

    expect(CondorcetUtil::format($vote, true))->toBe('A > B > C');
});

test('delete comments', function (): void {
    $result = CondorcetUtil::prepareParse('A > B # This is a comment', false);

    expect($result)->toBe(['A > B']);
});
