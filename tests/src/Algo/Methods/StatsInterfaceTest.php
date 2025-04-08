<?php

declare(strict_types=1);

use CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats;
use CondorcetPHP\Condorcet\Condorcet;
use CondorcetPHP\Condorcet\Election;

test('BaseMethodStats Are closed', function (): void {
    $election = new Election;

    $election->parseCandidates('A;B;C');
    $election->addVote('A > B > C');

    foreach(Condorcet::getAuthMethods() as $method) {
        $stats = $election->getResult($method)->stats;

        if ($stats instanceof BaseMethodStats)
        {
            expect($stats->closed)->toBe(true);
        }
    }
});