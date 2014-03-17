<?php

// UTF 8
header('Content-Type: text/html; charset=utf-8') ;

require_once '..'. DIRECTORY_SEPARATOR .'Condorcet.class.php' ;


$condorcet = new Condorcet () ;


// Options

$condorcet->add_option('X1') ;
$condorcet->add_option('X2') ;
$condorcet->add_option('X3') ;

// Votes


// Siegmund
$vote = array() ;
$vote[1] = 'X2' ;
$vote[2] = 'X3' ;
$vote[3] = 'X1' ;

$condorcet->add_vote($vote);


// Draffin
$vote = array() ;
$vote[1] = 'X2' ;
$vote[2] = 'X3' ;
$vote[3] = 'X1' ;

$condorcet->add_vote($vote);


// Horatio
$vote = array() ;
$vote[1] = 'X1' ;
$vote[2] = 'X2' ;
$vote[3] = 'X3' ;

$condorcet->add_vote($vote);


// Asinius Pollion
$vote = array() ;
$vote[1] = 'X1' ;
$vote[2] = 'X2' ;
$vote[3] = 'X3' ;

$condorcet->add_vote($vote);


// Pipus
$vote = array() ;
$vote[1] = 'X2' ;
$vote[2] = 'X1' ;
$vote[3] = 'X3' ;

$condorcet->add_vote($vote);


// Warren 60
$vote = array() ;
$vote[1] = 'X3' ;
$vote[2] = 'X2' ;
$vote[3] = 'X1' ;

$condorcet->add_vote($vote);


// Resigned
$vote = array() ;
$vote[1] = 'X3' ;
$vote[2] = 'X2' ;
$vote[3] = 'X1' ;

$condorcet->add_vote($vote);


// Aurele
$vote = array() ;
$vote[1] = 'X2' ;
$vote[2] = 'X1' ;
$vote[3] = 'X3' ;

$condorcet->add_vote($vote);


// Saegel
$vote = array() ;
$vote[1] = 'X3' ;
$vote[2] = 'X2' ;
$vote[3] = 'X1' ;

$condorcet->add_vote($vote);


// eleanore-clo
$vote = array() ;
$vote[1] = 'X1' ;
$vote[2] = 'X2' ;
$vote[3] = 'X3' ;

$condorcet->add_vote($vote);


// Schmürz
$vote = array() ;
$vote[1] = 'X2' ;
$vote[2] = 'X1' ;
$vote[3] = 'X3' ;

$condorcet->add_vote($vote);


?>

<h2> Candidats : </h2>


	<?php var_dump($condorcet->get_options_list ()) ; ?> <br>


<h2> Votes : </h2>

	<strong> Nombre de votes :</strong> <?php var_dump($condorcet->count_votes ()) ; ?> <br>
	<?php var_dump($condorcet->get_votes_list ()) ; ?> <br>



<h2> Résultats : </h2>


	<strong> Gagnant de Condorcet naturel :</strong> <?php var_dump($condorcet->get_winner ()) ; ?> <br><br>
	<strong> Perdant de Condorcet naturel :</strong> <?php var_dump($condorcet->get_loser ()) ; ?> <br><br>


	<strong> Classement de Schulze :</strong> 
	<?php var_dump($condorcet->get_result ()) ; ?> <br><br>


<h2> Duel de Paires : </h2>

	<?php var_dump($condorcet->get_Pairwise()) ; ?> <br><br>


<h2> Plus forts chemins (Schulze) : </h2>

	<?php var_dump($condorcet->get_result_stats()) ; ?> <br><br>