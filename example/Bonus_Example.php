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

<br><hr style="clear:both;">

<h2>Get pairwise :</h2>

	 <pre>
	<?php var_dump($calculator->getPairwise()); ?>
	 </pre> 
	<br>
	<em style="color:green;">computed in <?php echo $calculator->getLastTimer() ; ?> second(s).</em>

<br><br><hr style="clear:both;">

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

<h2>Some pratices about default method :</h2>

	<h3>Use default method :</h3>
	
	<strong>Defaut:</strong> <?php echo $calculator->getMethod() ; ?> <br>

	 <pre>
	<?php var_dump($calculator->getResult()); ?>
	 </pre>

	<h3>Change it to MiniMax_Margin :</h3>

	<strong>Defaut:</strong> <?php echo $calculator->setMethod('Minimax_Margin') ; ?> <br>

	 <pre>
	<?php var_dump($calculator->getResult()); ?>
	 </pre>


	<h3>Come back to the Class default method :</h3>

	<strong>Defaut:</strong> <?php echo $calculator->setMethod(Condorcet::getClassDefaultMethod()) ; ?> <br>

	 <pre>
	<?php var_dump($calculator->getResult()); ?>
	 </pre>


	<h3>Force all object from Condorcet to use by default Kemeny-Young:</h3>

	<?php 
		Condorcet::setClassMethod('KemenyYoung', true); 
		$calculator->setMethod('Copeland') ;

	?>

	<strong>Defaut:</strong> <?php echo $calculator->getMethod() ; ?> <br>

	 <pre>
	<?php var_dump($calculator->getResult()); ?>
	 </pre>


	<h3>Unforce it:</h3>
	
	<?php 
		Condorcet::forceMethod(false) ;
		$calculator->setMethod('Minimax_Opposition') ;
	?>


	<strong>Defaut:</strong> <?php echo $calculator->getMethod(); ?> <br>

	 <pre>
	<?php var_dump($calculator->getResult()); ?>
	 </pre>


<br><br><hr>

<h2>Vote manipulation :</h2>

	<h3>Display votes with tag "custom_tag_One"</h3>
<?php
	foreach ($calculator->getVotesList('custom_tag_One', true) as $vote)
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
<div style="clear:both;"></div>

	<h3>Or without with tag "custom_tag_Two"</h3>
<?php
	foreach ($calculator->getVotesList('custom_tag_Two', false) as $vote)
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
<div style="clear:both;"></div>

	<h3>Get a ranking without "custom_tag_One" & "custom_tag_Two" tags and display Kemeny-Young result but don't delete it</h3>

	 <pre>
	<?php var_dump($calculator->getResult('KemenyYoung', null, array('custom_tag_One', 'custom_tag_Two'), false)); ?>
	 </pre>
<div style="clear:both;"></div>

	<h3>Delete vote with "custom_tag_One" & "custom_tag_Two" tags and display Kemeny-Young  result</h3> <?php // you can also delete vote without this tag, read the doc ( tips: removeVote('custom_tag_One', false) ) ?>

	<?php 
		$calculator->removeVote(array('custom_tag_One', 'custom_tag_Two')) ;
	?>


	 <pre>
	<?php var_dump($calculator->getResult('KemenyYoung')); ?>
	 </pre>


	<h3>Check the new vote list</h3>
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
<div style="clear:both;"></div>



<br><br><hr>

<h2>Debug Data :</h2>

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