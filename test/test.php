<?php

ini_set('xdebug.var_display_max_depth', -1);
ini_set('xdebug.var_display_max_children', -1);
ini_set('xdebug.var_display_max_data', -1);

// UTF 8
header('Content-Type: text/html; charset=utf-8') ;

require_once '../Condorcet.class.php' ;

$condorcet = new Condorcet () ;


/////////////// REGISTER OPTIONS

echo '<h2>Check Register Options</h2>';

// echo $condorcet->add_option('coucou').'<br>' ;
// echo $condorcet->add_option('kikoo').'<br>';
// echo $condorcet->add_option('coucou').'<br>';
// echo $condorcet->add_option(TRUE).'<br>';
// echo $condorcet->add_option(array('coucou')).'<br>';
// echo $condorcet->add_option().'<br>';
// echo $condorcet->add_option().'<br>';
// echo $condorcet->add_option().'<br>';
// echo $condorcet->add_option('B').'<br>';
// echo $condorcet->add_option('2').'<br>';
// echo $condorcet->add_option(2).'<br>';

$condorcet->add_option('E').'<br>';
$condorcet->add_option('A').'<br>';
$condorcet->add_option('B').'<br>';
$condorcet->add_option('C').'<br>';
$condorcet->add_option('D').'<br>';

// echo $condorcet->remove_option('kikoo').'<br>';

echo '<br><br>' ;

var_dump($condorcet);

echo '<br><br><hr><br>';

/////////////// REGISTER VOTES

echo '<h2>Check Register Options</h2>';

// $vote_1[1] = 'A';
// $vote_1[2] = 'B,2,C';
// $vote_1[3] = 'coucou';

// $vote_2[1] = 'C';
// $vote_2[3] = 'A';

// $vote_3[1] = 2 ;
// $vote_3[2] = 'coucou' ;
// $vote_3[3] = 'B,C';

// $vote_4[1] = 'B';
// $vote_4[2] = '2';
// $vote_4[3] = 'C';
// $vote_4[4] = 'A';
// $vote_4[5] = 'coucou';

// $vote_5[1] = 'A';

$vote[1] = 'A';
$vote[2] = 'C';
$vote[3] = 'B';
$vote[4] = 'E' ;
$vote[5] = 'D' ;

for ($i = 1 ; $i <= 5 ; $i++ )
{
	echo $condorcet->add_vote($vote).'<br>' ;
}

$vote[1] = 'A';
$vote[2] = 'D';
$vote[3] = 'E';
$vote[4] = 'C' ;
$vote[5] = 'B' ;

for ($i = 1 ; $i <= 5 ; $i++ )
{
	echo $condorcet->add_vote($vote).'<br>' ;
}

$vote[1] = 'B';
$vote[2] = 'E';
$vote[3] = 'D';
$vote[4] = 'A' ;
$vote[5] = 'C' ;

for ($i = 1 ; $i <= 8 ; $i++ )
{
	echo $condorcet->add_vote($vote).'<br>' ;
}

$vote[1] = 'C';
$vote[2] = 'A';
$vote[3] = 'B';
$vote[4] = 'E' ;
$vote[5] = 'D' ;

for ($i = 1 ; $i <= 3 ; $i++ )
{
	echo $condorcet->add_vote($vote).'<br>' ;
}


$vote[1] = 'C';
$vote[2] = 'A';
$vote[3] = 'E';
$vote[4] = 'B' ;
$vote[5] = 'D' ;

for ($i = 1 ; $i <= 7 ; $i++ )
{
	echo $condorcet->add_vote($vote).'<br>' ;
}

$vote[1] = 'C';
$vote[2] = 'B';
$vote[3] = 'A';
$vote[4] = 'D' ;
$vote[5] = 'E' ;

for ($i = 1 ; $i <= 2 ; $i++ )
{
	echo $condorcet->add_vote($vote).'<br>' ;
}

$vote[1] = 'D';
$vote[2] = 'C';
$vote[3] = 'E';
$vote[4] = 'B' ;
$vote[5] = 'A' ;

for ($i = 1 ; $i <= 7 ; $i++ )
{
	echo $condorcet->add_vote($vote).'<br>' ;
}

$vote[1] = 'E';
$vote[2] = 'B';
$vote[3] = 'A';
$vote[4] = 'D' ;
$vote[5] = 'C' ;

for ($i = 1 ; $i <= 8 ; $i++ )
{
	echo $condorcet->add_vote($vote).'<br>' ;
}




echo '<br><br>' ;


echo '<br><br><hr><br>';


/////////////// Result

echo '<h2>Calc result</h2>';

echo '<strong> Condorcet Winner :</strong>' ;
var_dump( $condorcet->get_condorcet_winner() ) ;

echo '<br><br>' ;

echo '<strong> Condorcet Winner :</strong>' ;
var_dump( $condorcet->get_complete_result() ) ;

var_dump($condorcet);
