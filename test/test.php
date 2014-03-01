<?php

// UTF 8
header('Content-Type: text/html; charset=utf-8') ;

require_once '../Condorcet.class.php' ;

$condorcet = new Condorcet () ;

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

echo '<br><br><hr><br><br>' ;

var_dump($condorcet);


