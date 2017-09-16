<?php

/* NO INTERFACE here, see html examples for it */

# Quick tour of the main features of Condorcet PHP

// I - Install
    use Condorcet\Condorcet;
    use Condorcet\Election;
    use Condorcet\Candidate;
    use Condorcet\Vote;
    use Condorcet\DataManager\DataHandlerDrivers\PdoHandlerDriver;

    require_once '../../__CondorcetAutoload.php';

    $start_time = microtime(TRUE);


 // II - Create Election

    $myElection = new Election ();

    $myElection->addCandidate( new Candidate ('A') );
    $myElection->addCandidate( new Candidate ('B') );
    $myElection->addCandidate( new Candidate ('C') );
    $myElection->addCandidate( new Candidate ('D') );
    $myElection->addCandidate( new Candidate ('E') );
    $myElection->addCandidate( new Candidate ('F') );

 // II - Setup external drivers

    /* We will use PDO SQLITE, but can be MySQL or else */

    if (file_exists(__DIR__.'/bdd.sqlite')) :
        unlink(__DIR__.'/bdd.sqlite');
    endif;

    $pdo_object = new \PDO ('sqlite:'.__DIR__.'/bdd.sqlite');
    $database_map = ['tableName' => 'Entitys', 'primaryColumnName' => 'id', 'dataColumnName' => 'data'];

    $driver = new PdoHandlerDriver ($pdo_object, true, $database_map); // true = Try to create table

    $myElection->setExternalDataHandler($driver);

// III - Add hundred of thousands votes

    set_time_limit ( 60 * 5 );

    $howMany = 100000;

    $voteModel = $myElection->getCandidatesList();

    for ($i = 0 ; $i < $howMany ; $i++) {
        shuffle($voteModel);
        $myElection->addVote( $voteModel );
    }


// IV - Get somes results

    $myElection->getWinner();

    $myElection->getResult('Schulze');


print 'Success!  
Process in: '. round(microtime(true) - $start_time,2) . "s\n";

echo ' Peak of memory allocated : '.round(memory_get_peak_usage()/pow(1024,($i=floor(log(memory_get_peak_usage(),1024)))),2).' '.['b','kb','mb','gb','tb','pb'][$i].'<br>';


// Close external driver and and retrieve data into classical internal RAM memory.
/* $myElection->closeHandler(); */