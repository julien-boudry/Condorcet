<?php

// Candidates
	$calculator->addCandidate('Memphis');
	$calculator->addCandidate('Nashville');
	$calculator->addCandidate('Chatta');
	$calculator->addCandidate('Knoxville');


// Votes
	$vote[] = 'Memphis';
	$vote[] = 'Nashville';
	$vote[] = 'Chatta';
	$vote[] = 'Knoxville' ;

	for ($i = 1 ; $i <= 42 ; $i++ )
	{
		$calculator->addVote($vote, "custom_tag_Two") ;
	}
	$vote = array() ;

	$vote[] = 'Nashville';
	$vote[] = 'Chatta';
	$vote[] = 'Knoxville' ;
	$vote[] = 'Memphis';

	for ($i = 1 ; $i <= 26 ; $i++ )
	{
		$calculator->addVote($vote, "custom_tag_Two") ;
	}
	$vote = array() ;

	$vote[] = 'Chatta';
	$vote[] = 'Knoxville' ;
	$vote[] = 'Nashville';
	$vote[] = 'Memphis';

	for ($i = 1 ; $i <= 12 ; $i++ )
	{
		$calculator->addVote($vote) ;
	}
	for ($i = 1 ; $i <= 3 ; $i++ )
	{
		$calculator->addVote($vote, "custom_tag_One") ;
	}
	$vote = array() ;

	$vote[] = 'Knoxville' ;
	$vote[] = 'Chatta';
	$vote[] = 'Nashville';
	$vote[] = 'Memphis';

	for ($i = 1 ; $i <= 17 ; $i++ )
	{
		$calculator->addVote($vote, "custom_tag_Two") ;
	}
	$vote = array() ;