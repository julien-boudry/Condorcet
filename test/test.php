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

$condorcet->add_option('Z').'<br>';
$condorcet->add_option('A').'<br>';
$condorcet->add_option('B').'<br>';
$condorcet->add_option('C').'<br>';

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

$vote_1[1] = 'Z,B';
$vote_1[2] = 'C';

$vote_2[1] = 'Z';
$vote_2[3] = 'B';

$vote_3[1] = 'C' ;
$vote_3[2] = 'B,Z' ;
$vote_3[3] = 'A';

$vote_4[1] = 'C';
$vote_4[2] = 'B';
$vote_4[3] = 'A';
$vote_4[4] = 'Z' ;

$vote_5[1] = 'C';

echo $condorcet->add_vote($vote_1).'<br>' ;
echo $condorcet->add_vote($vote_2).'<br>' ;
echo $condorcet->add_vote($vote_3).'<br>' ;
echo $condorcet->add_vote($vote_4).'<br>' ;
echo $condorcet->add_vote($vote_5).'<br>' ;


echo '<br><br>' ;

var_dump($condorcet);

echo '<br><br><hr><br>';


/////////////// Result

echo '<h2>Calc result</h2>';

echo '<strong> Condorcet Winner :</strong>' ;
var_dump( $condorcet->get_condorcet_winner() ) ;

echo '<br><br>' ;

var_dump($condorcet);
