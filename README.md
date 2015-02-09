<p align="center">
  <img src="condorcet-logo.png" alt="Condorcet Class" width="40%">
</p>   

Condorcet Class for PHP
===========================

A PHP class implementing the Condorcet voting system and other Condorcet methods like the Schulze method.  
_This class is not designed for high performances, very high fiability exigence, massive voters or candidates_   

This library allows both the calculation of results according to the original method of the Marquis de Condorcet that algortihmes implementing more complex way its criteria.   
But Condorcet Class allows much more than this, and is actually a real manager of election and voting; providing you with powerful management features and your elections storage facilities.


### Summary
1. Project Overview     
  a. [Contributors and License](#contributors-and-license)     
  b. [Specifications and standards ](#specifications-and-standards)     
  c. [Project State](#project-state)     
  d. [Related projects / They use Condorcet](#related-projects--they-use-condorcet)    
  e. [Key Features](#key-features)     
2. [How to use it?](#how-to-use-it)    
  a. [Condorcet Wiki Manual](#condorcet-wiki-manual)     
  b. [Class & Methods reference](#class--methods-reference)     
  c. [Examples](#examples)     
  d. [Really quick and simple example](#really-quick-and-simple-example)


### Contributors and License

**Create by:** Julien Boudry (born 22/10/1988 - France) [@JulienBoudry](https://twitter.com/JulienBoudry) - _([complete list of contributors](https://github.com/julien-boudry/Condorcet_Schulze-PHP_Class/graphs/contributors))_     
**License:** MIT _(read de LICENSE file at the root folder)_  Including code, examples, logo and documentation     

As a courtesy, I will thank you to inform me about your project wearing this code, produced with love and selflessness. **You can also offer me a bottle of good wine**.   
**Or finance my studies:** *1FavAXcAU5rNkfDDTgMs4xx1FNzDztwYV6*


### Specifications and standards  
**Stable Version: 0.15**  
**PHP Requirement:** PHP 5.5 with Ctype, MB_String, Json common extensions. _(tested up to PHP 5.6)_

**Autoloading:** This project is consistent with the standard-PSR 0 and can be loaded easily and without modification in most frameworks. Namespace \Condorcet is used. 
The literature also provides easy example of free implementation with or without autoloader.

**Coding standards:** The code is very close to the respect of PSR-1 (lacks only the naming of methods), and freely influenced by PSR-2 when it is not unnecessarily authoritarian.  

**Performance:** *Benchmark on a modern machine (linux - x64 - php 5.6) of a large use of methods and algorithms. With relatively complex voting scenarios. For simpler use, Condorcet knows proceed more aggresive (cache, partial computation ...).*    
Memory usage : less than 2M    
Execution time : less than 120ms  

use Kemeny-Young 6 candidates : 5MB - 220ms    
use Kemeny-Young 7 candidates : 32MB - 900ms    
use Kemeny-Young 8 candidates : 135MB - 3500ms    


#### Project State
To date, we have a stable version.  
- Since version 0.9, an important work of code review and testing was conducted by the creator.
- Since version 0.90, significant structural changes have strong evolutionary implementation of the API. Including a full object management of the Votes and Candidate, in addition to the old and easier string conceptualization.

**External testers are more than welcome**.   


#### Related projects / They use Condorcet
* From August 2014: [Condorcet-Vote.org](http://www.condorcet-vote.org) Web services to create and store online Condorcet election. Including intérractives and collaborative features.    
It is based in large part on this project, and uses the library as a real election manager for computing, storage & stats.        
* [Condorcet API](https://github.com/julien-boudry/Condorcet_API) Very basic and simple http API for Condorcet class (json or text i/o)
* [Mahler-S2-BlindTest-Condorcet
](https://github.com/julien-boudry/Mahler-S2-BlindTest-Condorcet) (french interface) Web wrapper to compute and show result for classical music blind challenge with the Condorcet Class full potential (can also be used and adapted for any elections).    
Look like the examples provided here, but better : [Gustav Mahler blind listening test](http://classik.forumactif.com/t7244-ecoute-comparee-mahler-2e-symphonie-la-suite)    

---------------------------------------

## Key Features

### Presentation

- Advanced Management for votes and candidates. Support both simple calls or full object management.
- Voting management and inter-elections candidates. Many input supported (array, Json, object, string representation...)
- Computing Pairwise, Natural Condorcet Winner or Loser
- Algorithms implements (fully or partially) the criteria of the Marquis de Condorcet.
- Support for storing elections
- Ready to be extend, or support your own algorithms.
- [...]
  

### Supported Condorcet Methods

*[More information on Condorcet Wiki](https://github.com/julien-boudry/Condorcet/wiki/I-%23-Installation-%26-Basic-Configuration-%23-2.-Condorcet-Methods)*

* **Condorcet Basic** Give you the natural winner or looser of Condorcet, if there is one.  
*(This method is the only core method, you cannot remove it)*

* **Copeland** http://en.wikipedia.org/wiki/Copeland%27s_method

* **Kemeny-Young** http://en.wikipedia.org/wiki/Kemeny-Young_method   
*Kemeny-Young is currently limited up to 8 candidats. Note that, for 8 candidates, you must provide into php.ini a memory_limit upper than 160MB.
* **Minimax Family** http://en.wikipedia.org/wiki/Minimax_Condorcet
    * **Minimax Winning** *(Does not satisfy the Condorcet loser criterion)*  
    * **Minimax Margin** *(Does not satisfy the Condorcet loser criterion)*
    * **Minimax Opposition**:warning: *By nature, this alternative does not meet any criterion of Condorcet.*

* **RankedPairs *(Since v0.10, EXPERIMENTAL)*** https://en.wikipedia.org/wiki/Ranked_pairs  

* **Schulze Family** http://en.wikipedia.org/wiki/Schulze_method
    * **Schulze** Schulze Winning is recommended by Markus Schulze himself. ***This is the default choice.***
    * **Schulze Margin**
    * **Schulze Ratio**


#### Add new one?	
This class is designed to be easily extensible with new algorithms. A modular schematic is already used for all algorithms provided, so you can easily help, do not forget to make a pull request!  
[*More explanations in the documentation below*](#newAlgo)  


### Roadmap for futher releases 
  
  - Better cache system to prevent any full computing of the Pairwise on new vote / remove vote
  - Ability to add Candidate on Vote
  - Improve & test Ranked pair implementation *(help needed!)*
  - **Looking for testers!**   
 

---------------------------------------
## How to use it?

### Condorcet Wiki Manual

* **[Visit the Manual](https://github.com/julien-boudry/Condorcet/wiki)**

Living and learning examples, giving an overview but not exhaustive of the possibilities of the library.

### Class & Methods reference

The precise documentation of methods is not a wiki. It can be found in the form of Markdown in the "doc" folder for each release.   
* [Class & Methods documentation](doc/)


### Examples

#### Quick overview

* [Non-visual quick tour of opportunities without interface](examples/Quick_Overview.php) (not exhaustive and partial)


#### Officials examples

* [Visual simple & advanced script examples with HTML output](examples/examples-with-html/)


#### Condorcet Class Implementation

_This example of implementation in others project can very nice or strange... They can be current, or otherwise affect older versions of Condorcet._   

* [An extremely minimalist HTTP API calculating the results of Condorcet.](https://github.com/julien-boudry/Condorcet_API)
* [Gustav Mahler fans, making comparative blind test](https://github.com/julien-boudry/Mahler-S2-BlindTest-Condorcet)

#### Really quick and simple example

_OK : sacrifice to the local tradition of lazy._    

```php
  use Condorcet\Condorcet, Condorcet\Candidate, Condorcet\Vote ;

  $myElection1 = new Condorcet () ;

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
          echo 'My winner is ' . $myWinner->getName() . '<br>' ;

    // Natural Condorcet Loser
    $myLoser = $myElection1->getLoser(); // Return a candidate object
          echo 'My loser is ' . $myLoser->getName() ;

    // Schulze Ranking
    $myResultBySchulze = $myElection1->getResult('Schulze'); // Return a multi-dimensional array, filled with objects Candidate (multi-dimensional if tie on a rank)
      # Echo it easily 
      Condorcet::format($myResultBySchulze);

    // Get Schulze advanced computing data & stats
    $mySchulzeStats = $myElection1->getResultStats('Schulze');

    // Get Copeland Ranking
    $myResultByCopeland = $myElection1->getResult('Copeland');

    // Get Pairwise
    $myPairwise = $myElection1->getPairwise();

  // How long computation time behind us?
  $timer = $myElection1->getGlobalTimeer();

  // SHA-2 checksum and sleep
  $myChecksum = $myElection1->getChecksum();
  $toStore = serialize($myElection1);  // You can now unset you $candidate1 & co. On wake up, Condorcet election will build distinct with new reference.


  # And many many more than that. Read the doc. & look advanced examples.
```
