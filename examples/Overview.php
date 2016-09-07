<?php
declare(strict_types=1);

/* NO INTERFACE here, see html examples for it */

# Quick tour of the main features of Condorcet PHP

// I - Install
    use Condorcet\Condorcet;
    use Condorcet\Election;
    use Condorcet\Candidate;
    use Condorcet\Vote;

    require_once '../__CondorcetAutoload.php';

    $start_time = microtime(TRUE);

// II - Create Election

$election = new Election ();


// III - Manage Candidate

    # -A- Add candidates

        // A string
        $election->addCandidate('Debussy');
        $election->addCandidate('Caplet');
        $election->addCandidate('A');
        $myLutoCandidate = $election->addCandidate( 'Lutoslawski' );  // Return the Condorcet\Candidate object.

        // Automatic name
        $myAutomaticCandidate = $election->addCandidate(); // Return an automatic Condorcet\Candidate
        $myAutomaticCandidate->getName(); // return 'B'. Because we have already register the A candidate above.

        // An objet
        $myMessiaenCandidate = new Candidate ('Olivier Messiaen');

        $election->addCandidate($myMessiaenCandidate);
        $election->addCandidate( new Candidate ('Ligeti') );
        $election->addCandidate( new Candidate ('Koechlin') );


    # -B- Change your mind ?

        $election->removeCandidate('A');
        $election->removeCandidate($myAutomaticCandidate);

        // Lutoslawski change his name
        $myLutoCandidate->setName('Wiltod Lutoslawski'); # Done !

            # What was his old names?
            $myLutoCandidate->getHistory(); // return the full history with timestamp of this Candidate naming


    # -C- Check your candidate list, if you forget it
        $election->getCandidatesList(); // Return an array pupulate by each Candidate objet
        $election->getCandidatesList(true); // Return an array pupulate by each Candidate name as String.

        // OK, I need my Debussy (want his candidate object)
        $myDebussyCandidate = $election->getCandidateObjectByName('Debussy');


// IV - Manage Votes

    # -A- Add Votes

        $myVote1 = $election->addVote ( array (
            1 => 'Debussy',
            2 => ['Caplet', 'Olivier Messiaen'],
            3 => 'Ligeti',
            4 => ['Wiltod Lutoslawski','Koechlin']
        ) ); // Return the new Vote object

        $myVote2 = new Vote ( array(
            1 => 'Debussy',
            2 => 'Caplet', 
            3 => 'Olivier Messiaen',
            4 => 'Koechlin',
            5 => 'Wiltod Lutoslawski',
            6 => 'Ligeti'
        ) );

        $election->addVote ($myVote2);

        $myVote3 = $election->addVote('Debussy > Olivier Messiaen = Ligeti > Caplet'); // Last rank rank will be automatically deducted. A vote can not be expressed on a limited number of candidates. Non-expressed candidates are presumed to last Exaequo

        // Off course, you can vote by candidate object. Which is recommended. Or mix the two methods.
        $myVote4 = $election->addVote( array(
            $election->getCandidateObjectByName('Ligeti'),
            $myLutoCandidate,
            [$myMessiaenCandidate,'Koechlin']

        ) );

        // Finishing with another really nice example.
        $myVote5 = new Vote ( array (
            1 => $election->getCandidateObjectByName('Debussy'),
            2 => $election->getCandidateObjectByName('Olivier Messiaen'),
            3 => [ $election->getCandidateObjectByName('Wiltod Lutoslawski'), $election->getCandidateObjectByName('Ligeti') ],
            4 => $election->getCandidateObjectByName('Koechlin'),
            5 => $election->getCandidateObjectByName('Caplet')
        ) );

        $myVote5->addTags('jusGreatVote');

        $election->addVote($myVote5);

            // Please note that :
              $election->getCandidateObjectByName('Olivier Messiaen') === $myMessiaenCandidate; // Return TRUE


        // Add some nice tags to my Vote 1 & 2 & 3 (You can do this before or after add register into to the Election)

        $myVote1->addTags( ['strangeVote', 'greatFrenchVote'] ); // By Array
        $myVote2->addTags( 'greatFrenchVote,chauvinismVote' ); // By String
        $myVote3->addTags( $myVote1->getTags() ); // Copy & Past


        // Adding some random to this election
        $VoteModel = $myVote2->getRanking();

        for ($i = 0 ; $i < 95 ; $i++) {
            shuffle($VoteModel);
            $election->addVote( $VoteModel );
        }


            // How Many Vote could I Have now ?
            $election->countVotes(); // Return 95 (int)

            exit();


        // More fun way to add Vote from full string input !
        
        
        //Add some tags
        $myVote97 = $election->addVote(
                                            array($myDebussyCandidate, 'Koechlin'),
                                            ['greatFrenchVote','strangeVote','frenchies'] // You can also put your tags for this vote
        );

        // Parse multiple Votes
        $election->parseVotes("
            tag1,greatFrenchVote,tag3,strangeVote || Olivier Messiaen > Debussy = Caplet > Ligeti # Tags at first, vote at second, separated by '||'
            Ligeti > Caplet # Line break to start a new vote. Tags are optionals.
            strangeVote,tag3 || Debussy=Koechlin= Ligeti = Wiltod Lutoslawski = Olivier Messiaen>Caplet * 11 # This vote and his tag will be register 11 times
        ");

        // Creating self Vote object
        $myVote111 = new Vote ( [$myDebussyCandidate, $myLutoCandidate, 'Caplet'], 'customeVoteTag,AnAnotherTag' );
        $myVote112 = new Vote ( 'Olivier Messiaen = Caplet > Wiltod Lutoslawski', ['customVoteTag','AnAnotherTag'] );

        $election->addVote($myVote111);
        $election->addVote($myVote112);


    # -B- Manage Votes

        # 1- Get vote list
            // Get the vote list
            $election->getVotesList(); // Returns an array of all votes as object.

            // How many Vote with tag "strangeVote" ?
            $election->countVotes('strangeVote'); // Return 13 (int)

            // Return this 13 votes !
            $election->getVotesList('strangeVote');
            // Or without this tags and get the first of them
            $oneVoteToDelete = $election->getVotesList('strangeVote', false)[0] ;

        # 2- Vote objet
            var_dump($myVote111->getRanking()); // Return the current ranking
            var_dump($myVote111->getContextualVote($election)); // Return the full ranking in the context of election 1 (with 6 candidates)

            // Change the vote
            $myVote111->setRanking ( [
                                $myLutoCandidate,
                                $myDebussyCandidate,
                                $election->getCandidateObjectByName('Caplet'),
                                $election->getCandidateObjectByName('Ligeti')
                                ] );

            // Check the vote history
            $myVote111->getHistory();


        # 3- Delete Votes

            // Delete a specific vote object
            $election->removeVote( $oneVoteToDelete );

            // Delete all vote with tag "strangeVote" or "frenchies"
            $election->removeVote( ['strangeVote','frenchies'] );

            // Delete all vote without tag 'Wagnerian'
            # $election->removeVote( ['strangeVote','frenchies'], false ); // Here, if uncomment, all the vote will be deleted.


// V - Get Result

            // Natural Condorcet Winner
            $election->getWinner(); // Return NULL if there is not, else return the winner candidate object
            $election->getWinner('Schulze'); // Same thing, but try to resolve by Schulze method if there is not one. Can return an array of winners if there are multiple.

            // Natural Condorcet Loser
            $election->getLoser(); // Return NULL if there is not, else return the winner candidate object

            // Advanced Method
            $election->getResult(); // Result set for defaut method (Should be Schulze Winning)
            $election->getResult('Copeland'); // Do it with the Copeland method

            // Get an easy game outcome to read and understand (Table populated by string)
            $easyResult = Condorcet::format($election->getResult(),false);
            // Print it directly (vardump()) :
            # Condorcet::format($election->getResult());



print 'Success!  
Process in: '. (microtime(true) - $start_time) . 's';