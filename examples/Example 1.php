<?php

// Inclus les dépendances
ini_set('xdebug.var_display_max_depth', -1);
ini_set('xdebug.var_display_max_children', -1);
ini_set('xdebug.var_display_max_data', -1);
ini_set('display_errors', 1);
error_reporting(E_ALL); 


use Condorcet\Condorcet ;

require_once '..'.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'Condorcet'.DIRECTORY_SEPARATOR.'Condorcet.php' ;

$calculator = new Condorcet () ;



// §§§ EN AVANT §§§ EDITER LES LIGNES SUIVANTES

define('GROUPE', 'Tour 2 - Poule 4');

	
	// Versions en lice
	$calculator->addCandidate('C4');
	$calculator->addCandidate('D2');
	$calculator->addCandidate('F1');
	$calculator->addCandidate('G3');
	$calculator->addCandidate('H2');


	// Votants
	$calculator->addVote('D2=G3>H2=F1', 'Siegmund');
	$calculator->addVote('D2>G3>C4=F1', 'Draffin');
	$calculator->addVote('G3>C4>D2>F1', 'Resigned');
	$calculator->addVote('D2>C4>H2>G3>F1', 'Aurele');
	$calculator->addVote('G3>F1>D2>C4', 'Asinius Pollion');

		// Note, le renseignement du dernier rang de chaque vote est optionnel. Il sera automatiquement déduit si absent, j'ai d'ailleur procédé ainsi ci-dessus.


// Lance l'affichage §§§ On ne touche plus aux suivantes
?>
<!doctype html>
 <html lang="fr">
 <head>
 	<meta charset="UTF-8">
 	<title><?php echo GROUPE ;?></title>

 	<style>
		.votant {
		  float: left;
		  margin-right: 2cm;
		}
 	</style>
 </head>
 <body>

	<h1><?php echo GROUPE ;?></h1>

	<em>
		Nombre de versions candidates : 
		<?php echo $calculator->countCandidates() ;?>
		|
		Nombre de votants :
		<?php echo $calculator->countVotes() ;?>
	</em>

	<h2>Liste des candidats :</h2>

	<pre>
	<?php print_r($calculator->getCandidatesList()); ?>
	</pre>


	<h2>Détail des votes enregistrés :</h2>
<?php
	foreach ($calculator->getVotesList() as $vote)
	{
		echo '<div class="votant">';

		echo '<strong style="color:green;">'.$vote['tag'][0].'</strong><br>';

		echo "<ol>";

		foreach ($vote as $rank => $value)
		{
			if ($rank == 'tag') {continue ;}
		?>

			<li><?php echo implode(',',$value) ; ?></li>

		<?php
		}

		echo '</ol><br></div>' ;
	}
?>

<hr style="clear:both;">

	<h2>Gagnant de <a target="blank" href="http://en.wikipedia.org/wiki/Condorcet_method">Condorcet naturel</a> :</h2>

	<strong style="color:green;">
		<?php
		if ( !is_null($calculator->getWinner()) )
			{ echo $calculator->getWinner() ;}
		else
			{ echo '<span style="color:red;">Les votes de ce groupe ne permettent pas de gagnant naturel de Condorcet en cause du <a href="http://fr.wikipedia.org/wiki/Paradoxe_de_Condorcet" target="_blank">paradoxe de Condorcet</a>.</span>'; }
		?>
	</strong>

	<h2>Perdant de <a target="blank" href="http://en.wikipedia.org/wiki/Condorcet_method">Condorcet naturel</a> :</h2>

	<strong style="color:green;">
		<?php
		if ( !is_null($calculator->getLoser()) )
			{ echo $calculator->getLoser() ;}
		else
			{ echo '<span style="color:red;">Les votes de ce groupe ne permettent pas de perdant naturel de Condorcet en cause du <a href="http://fr.wikipedia.org/wiki/Paradoxe_de_Condorcet" target="_blank">paradoxe de Condorcet</a>.</span>'; }
		?>
	</strong>

<br><br><hr>

<?php 
	foreach (Condorcet::getAuthMethods() as $method)
	{ 
		if ($method !== 'Condorcet_Basic') :?>

		<h2>Ranking by <?php echo $method ?>:</h2>

		<?php 
			if ( $method === 'KemenyYoung' && is_string( $calculator->getResult( $method, array('noConflict' => true) ) )  )
			{
				echo '<strong style="color:red;">Arbitrary results: '.'Kemeny-Young has '.explode(';',$calculator->getResult($method, array('noConflict' => true)))[0].' possible solutions at score '.explode(';',$calculator->getResult($method, array('noConflict' => true)))[1].'</strong>' ;
			}

		 ?>

		<pre>
		<?php print_r($calculator->getResult($method)); ?>
		</pre>
	
	<?php endif; }

?>

 <br><br><hr>
 
 <h2>Statistiques de calcul :</h2>

	<h3>Duels de pairs (Pairwise), commun à toutes les méthodes :</h3>

	<pre>
	<?php print_r($calculator->getPairwise()); ?>
	</pre>
	
	
	<h3>Schulze, strongest pasths :</h3>

	<pre>
	<?php print_r($calculator->getResultStats()); ?>
	</pre>
	
	
	<h3>Copeland :</h3>

	<pre>
	<?php print_r($calculator->getResultStats('Copeland')); ?>
	</pre>
	
	
	<h3>Tableau Minimax :</h3>

	<pre>
	<?php print_r($calculator->getResultStats('Minimax_Margin')); ?>
	</pre>
	
	
	<h3>Kemeny-Young, simulation de tous les classements possibles :</h3>

	<pre>
	<?php print_r($calculator->getResultStats('KemenyYoung')); ?>
	</pre>

 <br><br><hr>
 
<h2>Debug Data :</h2>

 <h4>Configuration de l'objet :</h4>

 <pre>
<?php var_dump($calculator->getConfig()); ?>
 </pre>

<h4>Dump de l'objet :</h4>

 <pre>
<?php print_r($calculator); ?>
 </pre>

 </body>
 </html> 

?>
