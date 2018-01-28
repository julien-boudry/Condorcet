<?php
declare(strict_types=1);

/* NO INTERFACE here, see html examples for it */

# Quick tour of the main features of Condorcet PHP

// I - Install
    use Condorcet\Condorcet;
    use Condorcet\Election;
    use Condorcet\Candidate;
    use Condorcet\CondorcetUtil;
    use Condorcet\Vote;

    $firstPart = '';

/* THE FOLLOWING CODE IS LIVE FOLLOWING THE FIRST PART EXPRESSED IN THE PREVIOUS FILE "1. Overview.php" */
    require_once __DIR__.'/1. Overview.php';


// VI - Play with Condorcet objects (Advanced)

        // Create a second election
        $election2 = new Election ();

         // Create three candidate : 'A', 'B' and 'C'
        for ($i = 0 ; $i < 3 ; $i++) {
            $election2->addCandidate();
        }


    # Same candidate in multiple elections

        // Add two participating candidates from $election1
        $election2->addCandidate( $election1->getCandidateObjectByName('Debussy') );
        $election2->addCandidate( $myLutoCandidate );

        // And, I can change again theirs name. The new name is now applied in the two elections and their votes. If namesake in another election, an exception is throw.
        $myLutoCandidate->setName('W.Lutoslawski');

        // Have a look on $myLutoCandidate history
        $myLutoCandidate->getHistory();

        // Have a look to a vote from $election1. The candidate name have changed.
        $myVote4->getContextualRanking($election1, true);

        // In what elections, this candidates have a part ?
            $myLutoCandidate->getLinks(); // Get his the two Election objects
            $myLutoCandidate->countLinks(); // Or just count it. return (int) 2.


    # The same vote applied to multiple elections.

        $myNewVote = new Vote ( array (
            1 => $election1->getCandidateObjectByName('Debussy'),
            2 => $election2->getCandidateObjectByName('A'),
            3 => $election1->getCandidateObjectByName('Olivier Messiaen'),
            4 => $election2->getCandidateObjectByName('B'),
            5 => $election1->getCandidateObjectByName('Koechlin'),
            6 => $election1->getCandidateObjectByName('W.Lutoslawski'),
            7 => new Candidate ('Another candidate'), // This one does not takes part in any of two elections.
            8 => $election1->getCandidateObjectByName('Caplet'),
            9 => $election2->getCandidateObjectByName('C')
        ) );

        // Add it on election 1 and 2
        $election1->addVote( $myNewVote );
        $election2->addVote( $myNewVote );

        // Check ranking
        $myNewVote->getRanking(); // Here you get the original 9 ranks.
        // Check ranking
        $myNewVote->getContextualRanking($election1); // Here you get the vote applicable to $election 1
        $myNewVote->getContextualRanking($election2); // Here you get the vote applicable to $election 2

        // In what election, this candidates have a part ?
            $myNewVote->getLinks(); // Get Condorcet objects
            $myNewVote->countLinks(); // Or just count it

        // Now we can change vote ranking. result from all election will be affected.

            $myNewVote->setRanking( array (
                1 => $election2->getCandidateObjectByName('B'),
                2 => $election1->getCandidateObjectByName('Koechlin'),
                3 => $election1->getCandidateObjectByName('W.Lutoslawski')
            ) );

            # Get Ranking history
            $myNewVote->getHistory();


print 'Success!  
Process in: '. round(microtime(true) - $start_time,3) . 's';
