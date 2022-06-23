<?php

// Candidates
    $election->addCandidate('Memphis');
    $election->addCandidate('Nashville');
    $election->addCandidate('Chatta');
    $election->addCandidate('Knoxville');


// Votes
    $vote[] = 'Memphis';
    $vote[] = 'Nashville';
    $vote[] = 'Chatta';
    $vote[] = 'Knoxville' ;

    for ($i = 1 ; $i <= 42 ; $i++) {
        $election->addVote($vote, 'custom_tag_Two') ;
    }
    $vote = [] ;

    $vote[] = 'Nashville';
    $vote[] = 'Chatta';
    $vote[] = 'Knoxville' ;
    $vote[] = 'Memphis';

    for ($i = 1 ; $i <= 26 ; $i++) {
        $election->addVote($vote, 'custom_tag_Two') ;
    }
    $vote = [] ;

    $vote[] = 'Chatta';
    $vote[] = 'Knoxville' ;
    $vote[] = 'Nashville';
    $vote[] = 'Memphis';

    for ($i = 1 ; $i <= 12 ; $i++) {
        $election->addVote($vote) ;
    }
    for ($i = 1 ; $i <= 3 ; $i++) {
        $election->addVote($vote, 'custom_tag_One') ;
    }
    $vote = [] ;

    $vote[] = 'Knoxville' ;
    $vote[] = 'Chatta';
    $vote[] = 'Nashville';
    $vote[] = 'Memphis';

    for ($i = 1 ; $i <= 17 ; $i++) {
        $election->addVote($vote, 'custom_tag_Two') ;
    }
    $vote = [] ;
