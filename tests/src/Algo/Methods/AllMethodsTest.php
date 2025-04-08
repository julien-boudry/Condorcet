<?php

declare(strict_types=1);

use CondorcetPHP\Condorcet\Condorcet;
use CondorcetPHP\Condorcet\Election;

test('empty relevant votes, one candidate', function (string $method): void {
    $election = new Election;
    $election->setNumberOfSeats(1);

    $candidateD = $election->addCandidate('D');
    $election->addVote('A > B > C');

    expect($election->getResult($method)->ranking)->toBe([1 => [$candidateD]]);
})->with(Condorcet::getAuthMethods());