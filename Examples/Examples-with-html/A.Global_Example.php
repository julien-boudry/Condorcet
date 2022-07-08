<?php
declare(strict_types=1);

use CondorcetPHP\Condorcet\{Condorcet, CondorcetUtil, Election};

require_once __DIR__.'/../../__CondorcetAutoload.php';

Condorcet::$UseTimer = true;
$election = new Election;

// Inluding Data

require_once 'vote_data'.\DIRECTORY_SEPARATOR.'ComplexeVoteConf.php';

\define('TEST_NAME', 'Condorcet Global Example');

// View :
?><!doctype html>
 <html>
 <head>
 	<meta charset="UTF-8">
 	<title><?php echo TEST_NAME; ?></title>

 	<style>
		.votant {
		  display: inline-block;
		  margin-right: 2cm;
		}
 	</style>
 </head>
 <body>

	<header style="text-align:center;">
		<img src="../../condorcet-logo.png" alt="Condorcet Class" style="width:15%;">
	</header>

	<h1><?php echo TEST_NAME; ?></h1>

	<em style="font-weight:bold;"><a href="https://github.com/julien-boudry/Condorcet" target="_blank">Condorcet Class</a> version : <?php echo Condorcet::getVersion(); ?></em><br>

	<em>
		Number of Candidates :
		<?php echo $election->countCandidates(); ?>
		|
		Number of votes :
		<?php echo $election->countVotes(); ?>
	</em>

	<h2>Candidates list :</h2>

	<ul>
	<?php
    foreach ($election->getCandidatesList() as $candidatName) {
        echo '<li>'.$candidatName.'</li>';
    }
    ?>
	</ul>


	<h2>Registered votes details :</h2>
<?php
    foreach ($election->getVotesList() as $vote) {
        echo '<div class="votant">';

        echo '<strong style="color:green;">'.implode(' / ', $vote->getTags()).'</strong><br>';

        echo '<ol>';

        foreach ($vote as $rank => $value) {
            if ($rank === 'tag') {
                continue;
            } ?>

			<li><?php echo implode(',', $value); ?></li>

		<?php
        }

        echo '</ol><br></div>';
    }
?>

<hr style="clear:both;">

	<h2>Winner by <a target="blank" href="http://en.wikipedia.org/wiki/Condorcet_method">natural Condorcet</a> :</h2>

	<strong style="color:green;">
		<?php
        if ($election->getWinner() !== null) {
            echo $election->getWinner();
        } else {
            echo '<span style="color:red;">The votes of this group do not allow natural Condorcet winner because of <a href="http://fr.wikipedia.org/wiki/Paradoxe_de_Condorcet" target="_blank">Condorcet paradox</a>.</span>';
        }
        ?>
		<br>
		<em style="color:green;">computed in <?php echo number_format($election->getLastTimer(), 5); ?> second(s).</em>	</strong>

	<h2>Loser by <a target="blank" href="http://en.wikipedia.org/wiki/Condorcet_method">natural Condorcet</a> :</h2>

	<strong style="color:green;">
		<?php
        if ($election->getLoser() !== null) {
            echo $election->getLoser();
        } else {
            echo '<span style="color:red;">The votes of this group do not allow natural Condorcet loser because of <a href="http://fr.wikipedia.org/wiki/Paradoxe_de_Condorcet" target="_blank">Condorcet paradox</a>.</span>';
        }
        ?>
		<br>
		<em style="color:green;">computed in <?php echo number_format($election->getLastTimer(), 5); ?> second(s).</em>	</strong>
	</strong>

<br><br><hr>

<?php
    foreach (Condorcet::getAuthMethods() as $method) { ?>

		<h2>Ranking by <?php echo $method; ?>:</h2>

		<?php

            $result = $election->getResult($method);
            $lastTimer = $election->getLastTimer();

            if ($method === 'Kemeny–Young' && !empty($result->getWarning(\CondorcetPHP\Condorcet\Algo\Methods\KemenyYoung\KemenyYoung::CONFLICT_WARNING_CODE))) {
                $kemeny_conflicts = explode(';', $result->getWarning(\CondorcetPHP\Condorcet\Algo\Methods\KemenyYoung\KemenyYoung::CONFLICT_WARNING_CODE)[0]['msg']);

                echo '<strong style="color:red;">Arbitrary results: Kemeny-Young has '.$kemeny_conflicts[0].' possible solutions at score '.$kemeny_conflicts[1].'</strong>';
            }
         ?>

		<pre>
		<?php var_dump(CondorcetUtil::format($result)); ?>
		</pre>

		<em style="color:green;">computed in <?php echo $lastTimer; ?> second(s).</em>

	<?php }

?>
<br><br><hr><br>
<strong style="color:green;">Total computed in <?php echo number_format($election->getGlobalTimer(), 5); ?> second(s).</strong>
<br>
<?php var_dump($election->getTimerManager()->getHistory()); ?>
<br><br><hr>

<h2>Computing statistics :</h2>

	<h3>Pairwise :</h3>

	<pre>
	<?php var_dump(CondorcetUtil::format($election->getPairwise())); ?>
	</pre>

	<?php
    foreach (Condorcet::getAuthMethods() as $method) { ?>
		<h3>Stats for <?php echo $method; ?>:</h3>

		<pre>
		<?php var_dump(CondorcetUtil::format($election->getResult($method)->getStats())); ?>
		</pre>

	<?php } ?>

 <br><br><hr>

<h2>Debug Data :</h2>

 <h4>Defaut method (not used explicitly before) :</h4>

 <pre>
<?php var_dump(CondorcetUtil::format(Condorcet::getDefaultMethod())); ?>
 </pre>

<!-- <h4>CondorcetUtil::format (for debug only) :</h4>

 <pre>
<?php // CondorcetUtil::format($election);?>
 </pre> -->

 </body>
 </html>