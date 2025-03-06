<?php

declare(strict_types=1);

/* NO INTERFACE here, see html examples for it */

# Quick tour of the main features of Condorcet PHP

// I - Install
    use CondorcetPHP\Condorcet\{Candidate, Election, Vote};

    require_once __DIR__.'/../__CondorcetAutoload.php';

    $start_time = microtime(true);

// II - Create Election

    $election1 = new Election;


// III - Manage Candidate

    # -A- Add candidates

        // A string
        $election1->addCandidate('Debussy');
        $election1->addCandidate('Caplet');
        $election1->addCandidate('A');
        $myLutoCandidate = $election1->addCandidate('Lutoslawski');  // Return the CondorcetPHP\Candidate object.

        // Automatic name
        $myAutomaticCandidate = $election1->addCandidate(); // Return an automatic CondorcetPHP\Candidate
        $myAutomaticCandidate->name; // return 'B'. Because we have already register the A candidate above.

        // An objet
        $myMessiaenCandidate = new Candidate('Olivier Messiaen');

        $election1->addCandidate($myMessiaenCandidate);
        $election1->addCandidate(new Candidate('Ligeti'));
        $election1->addCandidate(new Candidate('Koechlin'));


    # -B- Change your mind ?

        $election1->removeCandidates('A');
        $election1->removeCandidates($myAutomaticCandidate);

        // Lutoslawski change his name
        $myLutoCandidate->setName('Wiltod Lutoslawski'); # Done !

            # What was his old names?
            $myLutoCandidate->getHistory(); // return the full history with timestamp of this Candidate naming


    # -C- Check your candidate list, if you forget it
        $election1->getCandidatesList(); // Return an array pupulate by each Candidate objet
        $election1->getCandidatesList(true); // Return an array pupulate by each Candidate name as String.

        // OK, I need my Debussy (want his candidate object)
        $myDebussyCandidate = $election1->getCandidateObjectFromName('Debussy');


// IV - Votes

    # -A- Add Votes

        $myVote1 = $election1->addVote([
            1 => 'Debussy',
            2 => ['Caplet', 'Olivier Messiaen'],
            3 => 'Ligeti',
            4 => ['Wiltod Lutoslawski', 'Koechlin'],
        ]); // Return the new Vote object

        $myVote2 = new Vote([
            1 => 'Debussy',
            2 => 'Caplet',
            3 => 'Olivier Messiaen',
            4 => 'Koechlin',
            5 => 'Wiltod Lutoslawski',
            6 => 'Ligeti',
        ]);

        $election1->addVote($myVote2);

        $myVote3 = $election1->addVote('Debussy > Olivier Messiaen = Ligeti > Caplet'); // Last rank rank will be automatically deducted. A vote can not be expressed on a limited number of candidates. Non-expressed candidates are presumed to last Exaequo

        // Off course, you can vote by candidate object. Which is recommended. Or mix the two methods.
        $myVote4 = $election1->addVote([
            $election1->getCandidateObjectFromName('Ligeti'),
            $myLutoCandidate,
            [$myMessiaenCandidate, 'Koechlin'],

        ]);

        // Finishing with another really nice example.
        $myVote5 = new Vote([
            1 => $election1->getCandidateObjectFromName('Debussy'),
            2 => $election1->getCandidateObjectFromName('Olivier Messiaen'),
            3 => [$election1->getCandidateObjectFromName('Wiltod Lutoslawski'), $election1->getCandidateObjectFromName('Ligeti')],
            4 => $election1->getCandidateObjectFromName('Koechlin'),
            5 => $election1->getCandidateObjectFromName('Caplet'),
        ]);

        $myVote5->addTags('jusGreatVote');

        $election1->addVote($myVote5);

            // Please note that :
              $election1->getCandidateObjectFromName('Olivier Messiaen') === $myMessiaenCandidate; // Return TRUE


        // Add some nice tags to my Vote 1 & 2 & 3 (You can do this before or after add register into to the Election)

        $myVote1->addTags(['strangeVote', 'greatFrenchVote']); // By Array
        $myVote2->addTags('greatFrenchVote,chauvinismVote'); // By String
        $myVote3->addTags($myVote1->getTags()); // Copy & Past


        // Parsing Vote
        $election1->parseVotes("
            Ligeti > Wiltod Lutoslawski > Olivier Messiaen = Debussy > Koechlin # A comment. A a line break for the next vote.
            greatFrenchVote,chauvinismVote || Olivier Messiaen > Debussy = Caplet > Ligeti # Tags at first, vote at second, separated by '||'
            strangeVote || Caplet > Koechlin * 8 # This vote and his tag will be register 8 times.
        ");


        // Adding some random to this election
        $VoteModel = $myVote2->getRanking();

        for ($i = 0; $i < 95; $i++) {
            shuffle($VoteModel);
            $election1->addVote($VoteModel);
        }

        // How Many Vote could I Have now ?
        $election1->countVotes(); // Return 110 (int)


    # -B- Manage Votes

        # 1- Get vote list
            // Get the vote list
            $election1->getVotesList(); // Returns an array of all votes as object.

            // How many Vote with tag "strangeVote" ?
            $election1->countVotes('strangeVote'); // Return 10 (int)
            // Or without
            $election1->countVotes('strangeVote', false); // Return 100 (int)
            // Or with this tag OR this tag
            $election1->countVotes(['greatFrenchVote', 'chauvinismVote']); // Return 4 (int)
            // Or without this tag AND this tag
            $election1->countVotes(['greatFrenchVote', 'chauvinismVote'], false); // Return 4 (int)

            // Return this 10 votes !
            $election1->getVotesList('strangeVote');
            // And the others 100 votes without this tags
            $election1->getVotesList('strangeVote', false);
            // Or with this tag OR this tag
            $election1->getVotesList(['greatFrenchVote', 'chauvinismVote']); // Return 4 (int)
            // Or without this tag AND this tag
            $election1->getVotesList(['greatFrenchVote', 'chauvinismVote'], false); // Return 4 (int)


        # 2- Vote objet
            $myVote3->getRanking(); // This vote specifies four candidates. Although the election comprises 6. This method return the original input.
            $myVote3->getContextualRanking($election1); // Return the full ranking in the context of election 1 (with 6 candidates). It is one that is taken into account when calculating the results of the election 1.


            // Change the vote
            $myVote1->setRanking([
                1 => 'Caplet',
                2 => 'Debussy',
                3=>  'Koechlin',
            ]);


            // Check the vote history
            $myVote1History = $myVote1->getHistory();


        # 3- Delete Votes

            // Delete a specific vote object
            $election1->removeVote($myVote3);

            // Delete all vote with tag "strangeVote" or "frenchies"
            $election1->removeVotesByTags(['strangeVote', 'chauvinismVote']);

            // Count vote
            $election1->countVotes(); // Return 98


// V - Get Result

    // Natural Condorcet Winner
    $election1->getWinner(); // Return NULL if there is not, else return the winner candidate object
    $election1->getWinner('Schulze'); // Same thing, but try to resolve winner by Schulze method that extends Condorcet method. Can return an array of winners if there are multiple.

    // Natural Condorcet Loser
    $election1->getLoser(); // Return NULL if there is not, else return the winner candidate object
    $election1->getLoser('Schulze'); // Same thing, but try to resolve loser by Schulze method that extends Condorcet method. Can return an array of winners if there are multiple.

    // Advanced Method -
    $election1->getResult(); // Result set for defaut method (Should be Schulze Winning). That return a Condorcet/Result object.
    $election1->getResult('Copeland'); // Do it with the Copeland method

    /* Please note that a Result object is fixed and independent. It does not change automatically when you change the election. You then have requested the production of a new object result. */

    // Get an easy game outcome to read and understand (Table populated by string)
    $election1->getResult('Schulze')->getResultAsArray(true);


print $firstPart ?? 'Success!
Process in: '. round(microtime(true) - $start_time, 3) . 's';
