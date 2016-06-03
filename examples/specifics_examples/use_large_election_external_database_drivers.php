<?php

/* NO INTERFACE here, see html examples for it */

# Quick tour of the main features of Condorcet PHP

// I - Install
    use Condorcet\Condorcet;
    use Condorcet\Election;
    use Condorcet\Candidate;
    use Condorcet\Vote;
    use Condorcet\DataManager\DataHandlerDrivers\PdoBddHandler;

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

    $pdo_object = new \PDO ('sqlite:'.__DIR__.'/bdd.sqlite');

    $driver = new PdoBddHandler ($pdo_object, true); // true = Try to create table

    $myElection->setExternalVotesDatabase($driver);

// III - Add hundred of thousands votes

    set_time_limit ( 60 * 5 );

    $howMany = 200000;

    $voteModel = $myElection->getCandidatesList();

    for ($i = 0 ; $i < $howMany ; $i++) {
        shuffle($voteModel);
        $myElection->addVote( $voteModel );
    }





print 'Success!  
Process in: '. (microtime(true) - $start_time) . 's';