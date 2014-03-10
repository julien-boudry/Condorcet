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
// $condorcet->add_option(TRUE).'<br>';
// echo $condorcet->add_option(array('coucou')).'<br>';
// echo $condorcet->add_option().'<br>';
// echo $condorcet->add_option().'<br>';
// echo $condorcet->add_option().'<br>';
// echo $condorcet->add_option('B').'<br>';
// echo $condorcet->add_option('2').'<br>';
// echo $condorcet->add_option(2).'<br>';

// $condorcet->add_option('E').'<br>';
$condorcet->add_option('A').'<br>';
$condorcet->add_option('B').'<br>';
$condorcet->add_option('C').'<br>';
$condorcet->add_option('D').'<br>';

// echo $condorcet->remove_option('kikoo').'<br>';

echo '<br><br>' ;


echo '<br><br><hr><br>';

/////////////// REGISTER VOTES

echo '<h2>Check Register Votes</h2>';


// Example 1

// $vote[1] = 'A';
// $vote[2] = 'C';
// $vote[3] = 'B';
// $vote[4] = 'E' ;
// $vote[5] = 'D' ;

// for ($i = 1 ; $i <= 5 ; $i++ )
// {
// 	$condorcet->add_vote($vote).'<br>' ;
// }

// $vote[1] = 'A';
// $vote[2] = 'E';
// $vote[3] = 'D';
// $vote[4] = 'C' ;
// $vote[5] = 'B' ;

// for ($i = 1 ; $i <= 5 ; $i++ )
// {
// 	$condorcet->add_vote($vote).'<br>' ;
// }

// $vote[1] = 'B';
// $vote[2] = 'E';
// $vote[3] = 'D';
// $vote[4] = 'A' ;
// $vote[5] = 'C' ;

// for ($i = 1 ; $i <= 8 ; $i++ )
// {
// 	$condorcet->add_vote($vote).'<br>' ;
// }

// $vote[1] = 'C';
// $vote[2] = 'A';
// $vote[3] = 'B';
// $vote[4] = 'E' ;
// $vote[5] = 'D' ;

// for ($i = 1 ; $i <= 3 ; $i++ )
// {
// 	$condorcet->add_vote($vote).'<br>' ;
// }


// $vote[1] = 'C';
// $vote[2] = 'A';
// $vote[3] = 'E';
// $vote[4] = 'B' ;
// $vote[5] = 'D' ;

// for ($i = 1 ; $i <= 7 ; $i++ )
// {
// 	$condorcet->add_vote($vote).'<br>' ;
// }

// $vote[1] = 'C';
// $vote[2] = 'B';
// $vote[3] = 'A';
// $vote[4] = 'D' ;
// $vote[5] = 'E' ;

// for ($i = 1 ; $i <= 2 ; $i++ )
// {
// 	$condorcet->add_vote($vote).'<br>' ;
// }

// $vote[1] = 'D';
// $vote[2] = 'C';
// $vote[3] = 'E';
// $vote[4] = 'B' ;
// $vote[5] = 'A' ;

// for ($i = 1 ; $i <= 7 ; $i++ )
// {
// 	$condorcet->add_vote($vote).'<br>' ;
// }

// $vote[1] = 'E';
// $vote[2] = 'B';
// $vote[3] = 'A';
// $vote[4] = 'D' ;
// $vote[5] = 'C' ;

// for ($i = 1 ; $i <= 8 ; $i++ )
// {
// 	$condorcet->add_vote($vote).'<br>' ;
// }


// Example 2

$vote[1] = 'A';
$vote[2] = 'B';
$vote[3] = 'C';
$vote[4] = 'D' ;

for ($i = 1 ; $i <= 3 ; $i++ )
{
	$condorcet->add_vote($vote).'<br>' ;
}

$vote[1] = 'D';
$vote[2] = 'A';
$vote[3] = 'B';
$vote[4] = 'C' ;

for ($i = 1 ; $i <= 2 ; $i++ )
{
	$condorcet->add_vote($vote).'<br>' ;
}


$vote[1] = 'D';
$vote[2] = 'B';
$vote[3] = 'C';
$vote[4] = 'A' ;

for ($i = 1 ; $i <= 2 ; $i++ )
{
	$condorcet->add_vote($vote).'<br>' ;
}


$vote[1] = 'C';
$vote[2] = 'B';
$vote[3] = 'D';
$vote[4] = 'A' ;

for ($i = 1 ; $i <= 2 ; $i++ )
{
	$condorcet->add_vote($vote).'<br>' ;
}





echo '<br><br>' ;

echo '<br><br><hr><br>';


/////////////// Result

echo '<h2>Calc result</h2>';

echo '<strong> Condorcet Winner :</strong>' ;
var_dump( $condorcet->get_winner_Condorcet() ) ;
echo '<br><br>' ;

echo '<strong> Schulze Result :</strong>' ;
var_dump( $condorcet->get_result() ) ;

echo '<br><br>' ;
echo '<strong>Var_dump Condorcet :</strong>' ;

var_dump($condorcet);

var_dump($condorcet->get_Strongest_Paths());
