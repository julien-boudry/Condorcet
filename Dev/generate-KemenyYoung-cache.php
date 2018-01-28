<?php
declare(strict_types=1);

use Condorcet\Election;
use Condorcet\Algo\Methods\KemenyYoung;

require_once __DIR__.'/../__CondorcetAutoload.php';

///

    $election = new Election;

    KemenyYoung::$devWriteCache = true;

    for ($i=1 ; $i < 9 ; $i++) :
        $election = new Election;

        for ($j=0 ; $j < $i ; $j++) :
            $election->addCandidate();
        endfor;

        $election->addVote('A');
        $election->getResult('KemenyYoung');
    endfor;
