<?php
declare(strict_types=1);

use Condorcet\Condorcet;
use Condorcet\Election;

ini_set('xdebug.var_display_max_depth', '-1');
ini_set('xdebug.var_display_max_children', '-1');
ini_set('xdebug.var_display_max_data', '-1');
ini_set('display_errors', '1');
error_reporting(E_ALL); 

// Exeptions Handler
function exception_handler($exception) {
  trigger_error($exception, E_USER_ERROR);
}
set_exception_handler('exception_handler');

require_once	'..'.DIRECTORY_SEPARATOR.
				'..'.DIRECTORY_SEPARATOR.'__CondorcetAutoload.php';

$election = new Election () ;



// Inluding Data

require_once 'vote_data'.DIRECTORY_SEPARATOR.'BasicVoteConf.php' ;

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

	<header style="text-align:center;">
		<img src="../../condorcet-logo.png" alt="Condorcet Class" style="width:15%;">
	</header>

	<h1><?php echo TEST_NAME ;?></h1>
	
	<em style="font-weight:bold;"><a href="https://github.com/julien-boudry/Condorcet" target="_blank">Condorcet Class</a> version : <?php echo Condorcet::getVersion(); ?></em><br>

	<em>
		Number of Candidates : 
		<?php echo $election->countCandidates() ;?>
		|
		Number of votes :
		<?php echo $election->countVotes() ;?>
	</em>

	<h2>Candidates list :</h2>

	<ul>
	<?php 
	foreach ($election->getCandidatesList() as $candidatName)
	{ 
		echo '<li>'.$candidatName.'</li>' ;
	}
	?>
	</ul>


	<h2>Registered votes details :</h2>
<?php
	foreach ($election->getVotesList() as $vote)
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

<br><hr style="clear:both;">

<h2>Get pairwise :</h2>

	 <pre>
	<?php Condorcet::format($election->getPairwise()); ?>
	 </pre> 
	<br>
	<em style="color:green;">computed in <?php echo $election->getLastTimer() ; ?> second(s).</em>

<br><br><hr style="clear:both;">

	<h2>Winner by <a target="blank" href="http://en.wikipedia.org/wiki/Condorcet_method">natural Condorcet</a> :</h2>

	<strong style="color:green;">
		<?php
		if ( !is_null($election->getWinner()) )
			{ echo $election->getWinner() ;}
		else
			{ echo '<span style="color:red;">The votes of this group do not allow natural Condorcet winner because of <a href="http://fr.wikipedia.org/wiki/Paradoxe_de_Condorcet" target="_blank">Condorcet paradox</a>.</span>'; }
		?>
	</strong>

	<h2>Loser by <a target="blank" href="http://en.wikipedia.org/wiki/Condorcet_method">natural Condorcet</a> :</h2>

	<strong style="color:green;">
		<?php
		if ( !is_null($election->getLoser()) )
			{ echo $election->getLoser() ;}
		else
			{ echo '<span style="color:red;">The votes of this group do not allow natural Condorcet loser because of <a href="http://fr.wikipedia.org/wiki/Paradoxe_de_Condorcet" target="_blank">Condorcet paradox</a>.</span>'; }
		?>
	</strong>

<br><br><hr>

<h2>Some pratices about default method :</h2>

	<h3>Use default method :</h3>
	
	<strong>Defaut:</strong> <?php echo Condorcet::getDefaultMethod() ; ?> <br>

	 <pre>
	<?php Condorcet::format($election->getResult()); ?>
	 </pre>

	<h3>Change it to MiniMax_Margin :</h3>
	<?php Condorcet::setDefaultMethod('Minimax_Margin') ; ?>

	<strong>Defaut:</strong> <?php echo Condorcet::getDefaultMethod() ; ?> <br>

	 <pre>
	<?php Condorcet::format($election->getResult()); ?>
	 </pre>


<br><br><hr>

<h2>Vote manipulation :</h2>

	<h3>Display votes with tag "custom_tag_One"</h3>
<?php
	foreach ($election->getVotesList('custom_tag_One', true) as $vote)
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
<div style="clear:both;"></div>

	<h3>Or without with tag "custom_tag_Two"</h3>
<?php
	foreach ($election->getVotesList('custom_tag_Two', false) as $vote)
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
<div style="clear:both;"></div>

	<h3>Get a ranking without "custom_tag_One" & "custom_tag_Two" tags and display Kemeny-Young result but don't delete it</h3>

	 <pre>
	<?php
	$options =	array(
					'tags' => array('custom_tag_One', 'custom_tag_Two'),
					'withTag' => false
				);

	Condorcet::format($election->getResult('KemenyYoung', $options)); ?>
	 </pre>
<div style="clear:both;"></div>

	<h3>Delete vote with "custom_tag_One" & "custom_tag_Two" tags and display Kemeny-Young  result</h3> <?php // you can also delete vote without this tag, read the doc ( tips: removeVote('custom_tag_One', false) ) ?>

	<?php 
		$election->removeVote(array('custom_tag_One', 'custom_tag_Two')) ;
	?>


	 <pre>
	<?php Condorcet::format($election->getResult('KemenyYoung')); ?>
	 </pre>


	<h3>Check the new vote list</h3>
<?php
	foreach ($election->getVotesList() as $vote)
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
<div style="clear:both;"></div>



<br><br><hr>

<h2>Debug Data :</h2>

 <h4>About object :</h4>

 <pre>
<?php Condorcet::format($election->getConfig()); ?>
 </pre>

<!-- <h4>Condorcet::format (for debug only) :</h4>

 <pre>
<?php // Condorcet::format($election); ?>
 </pre> -->

 </body>
 </html> 