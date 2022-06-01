<?php
declare(strict_types=1);

use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Algo\Methods\KemenyYoung\KemenyYoung;

require_once __DIR__.'/../__CondorcetAutoload.php';

///
    $max_candidates_count = KemenyYoung::$MaxCandidates;
///

    $election = new Election;

    KemenyYoung::$devWriteCache = true;

    KemenyYoung::$MaxCandidates = $max_candidates_count;


    for ($i=1 ; $i <= $max_candidates_count ; $i++) :
        $election = new Election;

        for ($j=0 ; $j < $i ; $j++) :
            $election->addCandidate();
        endfor;

        $election->addVote('A');
        $election->getResult('KemenyYoung');
    endfor;
