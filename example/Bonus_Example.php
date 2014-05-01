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



// Inluding Data

require_once 'voteConf.php' ;

define('TEST_NAME', 'Condorcet Bonus Example');

// View :
?><!doctype html>
 <html>
 <head>
 	<meta charset="UTF-8">
 	<title><?php echo TEST_NAME ;?></title>

 	<style>
		.votant {
		  float: left;
		  margin-right: 2cm;
		}
 	</style>
 </head>
 <body>

	<h1><?php echo TEST_NAME ;?></h1>
	
	<em style="font-weight:bold;"><a href="https://github.com/julien-boudry/Condorcet_Schulze-PHP_Class" target="_blank">Condorcet Class</a> version : <?php echo $calculator->getObjectVersion(); ?></em><br>

	<em>
		Number of Candidates : 
		<?php echo $calculator->countCandidates() ;?>
		|
		Number of votes :
		<?php echo $calculator->countVotes() ;?>
	</em>

	<h2>Candidates list :</h2>

	<pre>
	<?php print_r($calculator->getCandidatesList()); ?>
	</pre>


	<h2>Registered votes details :</h2>
<?php
	foreach ($calculator->getVotesList() as $vote)
	{
		echo '<div class="votant">';

		echo '<strong style="color:green;">'.implode(' / ',$vote['tag']).'</strong><br>';

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

	<h2>Winner by <a target="blank" href="http://en.wikipedia.org/wiki/Condorcet_method">natural Condorcet</a> :</h2>

	<strong style="color:green;">
		<?php
		if ( !is_null($calculator->getWinner()) )
			{ echo $calculator->getWinner() ;}
		else
			{ echo '<span style="color:red;">The votes of this group do not allow natural Condorcet winner because of <a href="http://fr.wikipedia.org/wiki/Paradoxe_de_Condorcet" target="_blank">Condorcet paradox</a>.</span>'; }
		?>
	</strong>

	<h2>Loser by <a target="blank" href="http://en.wikipedia.org/wiki/Condorcet_method">natural Condorcet</a> :</h2>

	<strong style="color:green;">
		<?php
		if ( !is_null($calculator->getLoser()) )
			{ echo $calculator->getLoser() ;}
		else
			{ echo '<span style="color:red;">The votes of this group do not allow natural Condorcet loser because of <a href="http://fr.wikipedia.org/wiki/Paradoxe_de_Condorcet" target="_blank">Condorcet paradox</a>.</span>'; }
		?>
	</strong>

<br><br><hr>



 <h4>About object :</h4>

 <pre>
<?php var_dump($calculator->getConfig()); ?>
 </pre>

<!-- <h4>Var_Dump (for debug only) :</h4>

 <pre>
<?php // var_dump($calculator); ?>
 </pre> -->

 </body>
 </html> 