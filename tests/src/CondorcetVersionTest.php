<?php declare(strict_types=1);

use CondorcetPHP\Condorcet\{Condorcet, Election};

test('object version', function (): void {
    $election = new Election;

    expect($election->getCondorcetBuilderVersion())->toBe(Condorcet::getVersion());
    expect($election->getCondorcetBuilderVersion(true))->toBe(Condorcet::getVersion(true));
});
