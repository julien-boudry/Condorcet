<?php

declare(strict_types=1);

use CondorcetPHP\Condorcet\Tools\Randomizers\VoteRandomizer;

require_once __DIR__.'/../__CondorcetAutoload.php';

///
$number_of_votes = 100_000_000;
$number_of_candidates = 8;
///

$candidateName = 'A';

$candidates = [];

for ($i = 0; $i < $number_of_candidates; $i++) {
    $candidates[] = $candidateName++;
}

$randomizer = new VoteRandomizer($candidates, 'CondorcetSeed');

$file = new \SplFileObject(__DIR__.'/large.votes', 'w+');
$cache = '';

for ($i = 0; $i < $number_of_votes; $i++) {

    $cache .= $randomizer->getNewVote()."\n";

    if ($i % 5_000_000 === 0) {
        $file->fwrite($cache);
        $cache = '';
    }
}

$file->fwrite($cache);
