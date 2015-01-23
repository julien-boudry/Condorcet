<?php

// Candidates
	$calculator->addCandidate('A');
	$calculator->addCandidate('C');
	$calculator->addCandidate('B');
	$calculator->addCandidate('E');
	$calculator->addCandidate('D');


// Votes
	$vote = array() ;
	$vote[1] = 'A';
	$vote[2] = 'C';
	$vote[3] = 'B';
	$vote[4] = 'E' ;
	$vote[5] = 'D' ;

	for ($i = 1 ; $i <= 5 ; $i++ )
	{
		$calculator->addVote($vote, 'coucou') ;
	}

	$vote = "A>D>E>C>B" ;

	for ($i = 1 ; $i <= 5 ; $i++ )
	{
		$calculator->addVote($vote) ;
	}


	$vote = 'B>E>D>A' ; // It is not mandatory to indicate the role or last. It will be automatically deducted from the previous.

	for ($i = 1 ; $i <= 8 ; $i++ )
	{
		$calculator->addVote($vote) ;
	}

	$vote = array() ;
	$vote[1] = 'C';
	$vote[2] = 'A';
	$vote[3] = 'B';
	$vote[4] = 'E' ;
	// It is not mandatory to indicate the role or last. It will be automatically deducted from the previous.

	for ($i = 1 ; $i <= 3 ; $i++ )
	{
		$calculator->addVote($vote) ;
	}

	$vote = array() ;
	$vote[1] = 'C';
	$vote[2] = 'A';
	$vote[3] = 'E';
	$vote[4] = 'B' ;
	$vote[5] = 'D' ;

	for ($i = 1 ; $i <= 7 ; $i++ )
	{
		$calculator->addVote($vote) ;
	}

	$vote = array() ;
	$vote[1] = 'C';
	$vote[2] = 'B';
	$vote[3] = 'A';
	$vote[4] = 'D' ;
	$vote[5] = 'E' ;

	for ($i = 1 ; $i <= 2 ; $i++ )
	{
		$calculator->addVote($vote) ;
	}

	$vote = array() ;
	$vote[1] = 'D';
	$vote[2] = 'C';
	$vote[3] = 'E';
	$vote[4] = 'B' ;
	$vote[5] = 'A' ;

	for ($i = 1 ; $i <= 7 ; $i++ )
	{
		$calculator->addVote($vote) ;
	}

	$vote = array() ;
	$vote[1] = 'E';
	$vote[2] = 'B';
	$vote[3] = 'A';
	$vote[4] = 'D' ;
	$vote[5] = 'C' ;

	for ($i = 1 ; $i <= 8 ; $i++ )
	{
		$calculator->addVote($vote) ;
	}