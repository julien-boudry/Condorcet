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

echo $condorcet->add_option('coucou').'<br>' ;
echo $condorcet->add_option('kikoo').'<br>';
echo $condorcet->add_option('coucou').'<br>';
echo $condorcet->add_option(TRUE).'<br>';
echo $condorcet->add_option(array('coucou')).'<br>';
echo $condorcet->add_option().'<br>';
echo $condorcet->add_option().'<br>';
echo $condorcet->add_option().'<br>';
echo $condorcet->add_option('B').'<br>';
echo $condorcet->add_option(2).'<br>';

echo '<br><br>' ;

var_dump($condorcet);

echo '<br><br><hr><br>';

/////////////// REGISTER VOTES

echo '<h2>Check Register Options</h2>';

$vote_1[1] = 'A';
$vote_1[2] = 'B,2,C';
$vote_1[3] = 'coucou';


echo $condorcet->add_vote($vote_1).'<br>' ;


echo '<br><br>' ;

var_dump($condorcet);

echo '<br><br><hr><br>';

