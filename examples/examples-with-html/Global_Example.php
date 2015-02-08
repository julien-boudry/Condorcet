<?php
ini_set('xdebug.var_display_max_depth', -1);
ini_set('xdebug.var_display_max_children', -1);
ini_set('xdebug.var_display_max_data', -1);
ini_set('display_errors', 1);
error_reporting(E_ALL); 

// Exeptions Handler
function exception_handler($exception) {
  trigger_error($exception, E_USER_ERROR);
}
set_exception_handler('exception_handler');

use Condorcet\Condorcet ;

require_once	'..'.DIRECTORY_SEPARATOR.
				'..'.DIRECTORY_SEPARATOR.'lib'.
				DIRECTORY_SEPARATOR.'Condorcet'.
				DIRECTORY_SEPARATOR.'Condorcet.php' ;


$calculator = new Condorcet () ;



// Inluding Data

require_once 'vote_data'.DIRECTORY_SEPARATOR.'ComplexeVoteConf.php' ;

define('TEST_NAME', 'Condorcet Global Example');

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

	<header style="text-align:center;">
		<img src="../../condorcet-logo.png" alt="Condorcet Class" style="width:15%;">
	</header>

	<h1><?php echo TEST_NAME ;?></h1>
	
	<em style="font-weight:bold;"><a href="https://github.com/julien-boudry/Condorcet_Schulze-PHP_Class" target="_blank">Condorcet Class</a> version : <?php echo $calculator->getClassVersion(); ?></em><br>

	<em>
		Number of Candidates : 
		<?php echo $calculator->countCandidates() ;?>
		|
		Number of votes :
		<?php echo $calculator->countVotes() ;?>
	</em>

	<h2>Candidates list :</h2>

	<ul>
	<?php 
	foreach ($calculator->getCandidatesList() as $candidatName)
	{ 
		echo '<li>'.$candidatName.'</li>' ;
	}
	?>
	</ul>


	<h2>Registered votes details :</h2>
<?php
	foreach ($calculator->getVotesList() as $vote)
	{
		echo '<div class="votant">';

		echo '<strong style="color:green;">'.implode(' / ',$vote->getTags()).'</strong><br>';

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
		<br>
		<em style="color:green;">computed in <?php echo $calculator->getLastTimer() ; ?> second(s).</em>	</strong>

	<h2>Loser by <a target="blank" href="http://en.wikipedia.org/wiki/Condorcet_method">natural Condorcet</a> :</h2>

	<strong style="color:green;">
		<?php
		if ( !is_null($calculator->getLoser()) )
			{ echo $calculator->getLoser() ;}
		else
			{ echo '<span style="color:red;">The votes of this group do not allow natural Condorcet loser because of <a href="http://fr.wikipedia.org/wiki/Paradoxe_de_Condorcet" target="_blank">Condorcet paradox</a>.</span>'; }
		?>
		<br>
		<em style="color:green;">computed in <?php echo $calculator->getLastTimer() ; ?> second(s).</em>	</strong>
	</strong>

<br><br><hr>

<?php 
	foreach (Condorcet::getAuthMethods() as $method)
	{ ?>

		<h2>Ranking by <?php echo $method ?>:</h2>

		<?php 

			$result = $calculator->getResult($method) ;
			$lastTimer = $calculator->getLastTimer() ;

			$KemenyYoung_Specials_options = array('algoOptions' => ['noConflict' => true]);
			if ( $method === 'KemenyYoung' && is_string( $calculator->getResult( $method, $KemenyYoung_Specials_options ) )  )
			{
				$kemeny_conflicts = explode( ';', $calculator->getResult( $method,$KemenyYoung_Specials_options ) ) ;

				echo '<strong style="color:red;">Arbitrary results: Kemeny-Young has '.$kemeny_conflicts[0].' possible solutions at score '.$kemeny_conflicts[1].'</strong>' ;
			}

		 ?>

		<pre>
		<?php Condorcet::format($result); ?>
		</pre>

		<em style="color:green;">computed in <?php echo $lastTimer ; ?> second(s).</em>
	
	<?php }

?>
<br><br><hr><br>
<strong style="color:green;">Total computed in <?php echo $calculator->getGlobalTimer() ; ?> second(s).</strong>
<br><br><hr>
 
<h2>Computing statistics :</h2>

	<h3>Pairwise :</h3>

	<pre>
	<?php Condorcet::format($calculator->getPairwise()); ?>
	</pre>
	
	<?php
	foreach (Condorcet::getAuthMethods() as $method)
	{ 
		if ($method !== 'Condorcet_Basic') :?>

		<h3>Stats for <?php echo $method ?>:</h3>

		<pre>
		<?php Condorcet::format($calculator->getResultStats($method)); ?>
		</pre>
	
	<?php endif; } ?>

 <br><br><hr>
 
<h2>Debug Data :</h2>

 <h4>Defaut method (not used explicitly before) :</h4>

 <pre>
<?php Condorcet::format($calculator->getMethod()); ?>
 </pre>


 <h4>About object :</h4>

 <pre>
<?php Condorcet::format($calculator->getConfig()); ?>
 </pre>

<!-- <h4>Condorcet::format (for debug only) :</h4>

 <pre>
<?php // Condorcet::format($calculator); ?>
 </pre> -->

 </body>
 </html> 