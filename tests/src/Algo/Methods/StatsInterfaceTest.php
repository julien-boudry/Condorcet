<?php declare(strict_types=1);


use CondorcetPHP\Condorcet\{Condorcet, Election};
use CondorcetPHP\Condorcet\Algo\Stats\{BaseMethodStats, StatsInterface};

test('BaseMethodStats Are closed', function (string $method): void {
    $election = new Election;

    $election->parseCandidates('A;B;C');
    $election->addVote('A > B > C');

    $stats = $election->getResult($method)->stats;

    expect($stats)->toBeInstanceOf(StatsInterface::class);

    if ($stats instanceof BaseMethodStats) {
        expect($stats->closed)->toBe(true);
    }

})->with(Condorcet::getAuthMethods());
