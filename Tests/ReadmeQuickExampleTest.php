<?php
declare(strict_types=1);
namespace Condorcet;

use Condorcet\Condorcet;
use Condorcet\Election;
use Condorcet\Candidate;
use Condorcet\Vote;

use PHPUnit\Framework\TestCase;


class ReadmeQuickExampleTest extends TestCase
{
    public function testReadmeQuickExample()
    {
          $myElection1 = new Election () ;

          // Create your own candidate object
          $candidate1 = new Candidate ('Candidate 1'); 
          $candidate2 = new Candidate ('Candidate 2');
          $candidate3 = new Candidate ('Candidate 3');

          // Register your candidates
          $myElection1->addCandidate($candidate1);
          $myElection1->addCandidate($candidate2);
          $myElection1->addCandidate($candidate3);
          $candidate4 = $myElection1->addCandidate('Candidate 4');

          // Add some votes, by some ways
          $myElection1->addVote( array(
                                      $candidate2, // 1
                                      [$candidate1, $candidate4] // 2 - Tie
                                      // Last rank is optionnal. Here it's : $candidate3
          ));

          $myElection1->addVote('Candidate 2 > Candidate 3 > Candidate 4 = Candidate 1'); // last rank can also be omitted

          $myElection1->parseVotes(
                      'tagX || Candidate 1 > Candidate 2 = Candidate 4 > Candidate 3 * 4
                      tagX, tagY || Candidate 3 > Candidate 1 * 3'
          ); // Powerfull, it add 7 votes

          $myElection1->addVote( new Vote ( array(
                                                $candidate4,
                                                $candidate2
                                                // You can ignore the over. They will be at the last rank in the contexte of each election.
          )  ));


          // Get Result

            // Natural Condorcet Winner
            $myWinner = $myElection1->getWinner(); // Return a candidate object
            $this->assertEquals('My winner is Candidate 1<br>', 'My winner is ' . $myWinner->getName() . '<br>');

            // Natural Condorcet Loser
            $myLoser = $myElection1->getLoser(); // Return a candidate object
            $this->assertEquals('My loser is Candidate 3', 'My loser is ' . $myLoser->getName());

            // Schulze Ranking
            $myResultBySchulze = $myElection1->getResult('Schulze'); // Return a multi-dimensional array, filled with objects Candidate (multi-dimensional if tie on a rank)
              # Echo it easily 
            $this->assertEquals([1=>'Candidate 1',2=>'Candidate 2',3=>'Candidate 4',4=>'Candidate 3']  ,Condorcet::format($myResultBySchulze));

            // Get Schulze advanced computing data & stats
            $mySchulzeStats = $myElection1->getResult('Schulze')->getStats();

            // Get Copeland Ranking
            $myResultByCopeland = $myElection1->getResult('Copeland');

            // Get Pairwise
            $myPairwise = $myElection1->getPairwise();

          // How long computation time behind us?
          $timer = $myElection1->getGlobalTimer();

          // SHA-2 checksum and sleep
          $myChecksum = $myElection1->getChecksum();
          $toStore = serialize($myElection1);  // You can now unset your $candidate1 & co. On wake up, Condorcet election will build distinct with new reference.
    }

}
