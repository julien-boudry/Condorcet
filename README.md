<p align="center">
  <img src="condorcet-logo.png" alt="Condorcet Class" width="40%">
</p>

[![Build Status](https://travis-ci.org/julien-boudry/Condorcet.svg?branch=master)](https://travis-ci.org/julien-boudry/Condorcet)   
> Main Author: [Julien Boudry](https://www.linkedin.com/in/julienboudry/)   
> License: [MIT](License.md) _- Please say hello if you like or use this code!_  
> Contribute: [Contribute File](CONTRIBUTE.md)   
> Donation: ₿ [1LhZZVxmNCTPWftKFTUKbRiUKzA67RPWez](https://blockchain.info/address/1LhZZVxmNCTPWftKFTUKbRiUKzA67RPWez) _You can also offer me a bottle of good wine._  

> Methods provided natively: Condorcet / Copeland / Dodgson / Kemeny-Young / Minimax & Variants / Ranked Pairs / Schulze & Variants   

Condorcet PHP
===========================
A PHP library implementing the Condorcet voting system and others Condorcet methods like the Schulze method. And also a powerful election manager.  

## Summary
1. [Project State and Specifications](#project-state-and-specifications)  
1. [Main features](#main-features)     
1. [Supported Condorcet Methods](#supported-condorcet-methods)  
  a. [Methods provided natively](#methods-provided-natively)     
  b. [Add your own method](#add-your-own-method-as-module)  
1. [How to use it?](#how-to-use-it)  
  a. [Install](#install)  
  b. [Condorcet Wiki Manual](#condorcet-wiki-manual)     
  c. [Class & Methods reference](#class--methods-reference)     
  d. [Examples](#examples) *Have a look on Examples!*     
  e. [Really quick and simple example](#really-quick-and-simple-example)
1. [Performance & Coding style considerations](#roadmap-for-further-releases)
1. [Roadmap for further releases](#roadmap-for-further-releases)
1. [Related projects / They use Condorcet](#related-projects--they-use-condorcet)  

## Project State and Specifications    

> [**Releases Notes**](release)

* **Stable Version: 1.3.x**  
  * *PHP Requirement:* PHP 7.1 with Ctype, MB_String, Json common extensions.  _(tested up to PHP 7.2)_
* **Old Stable : 1.0.x** _support provided_  
    * *PHP Requirement:* PHP 5.6 with Ctype, MB_String, Json common extensions. _(tested up to PHP 7.1)_
* **Development Version: 1.4.x**  
  * *PHP Requirement:* PHP 7.1 with Ctype, MB_String, Json common extensions.

_v0.9x series is no longer supported. But bug report are welcomes, code base can be close to v1.x series._    
_Some support and fix can be done for 0.14 version on demand. Since v0.90, you should consider than it's a new project (api, engine)._  

## Main features
* __Manage an election__
  * Respect an election cycle: Registering candidate, registering vote, get results from many algorithms.
  * Ordered votes, delete it, simulate partials results.
  * Many input type available (String, Json, Parse text, Objects...)
  * Integrity check (checksumming) and logs.
  * Support for storing elections (serializing Election object, exports datas...)
  * Some methods can be use nearly front final user (anti-spam check, parsing input, human friendly results and stats...)
* __Get election results and stats__
  * Get the natural Condorcet Winner, Loser, Pairwise, Paradox...
  * Get full ranking from advanced methods (Schulze, Copeland, Ranked Paris, Kemeny-Young, Minimax...)
  * Get some additional stats from these methods
* __Be more powerful__
  * All are objects, all are abstract _(But there is many higher level functions and inputs types)_.
  * Candidates and Votes are object which can take part to multiples elections on the same time and change her name or content dynamically. That allow powerful tools to simulate elections.
  * Manage hundreds of billions votes by enable external datastore system for votes between process.
* __Extend it! Configure it!__
  * Modular architecture allow you to registered additional methods of Condorcet (or not Condorcet) without fork Condorcet PHP! Just make your own module.
  * Allow you to use your own datastore driver to manage very large elections at your way.
  * Many configurations options and methods.

_This class is not designed for high performances or very high reliability exigence._   

## Supported Condorcet Methods
### Methods provided natively

*[More information on Condorcet Wiki](https://github.com/julien-boudry/Condorcet/wiki/I-%23-Installation-%26-Basic-Configuration-%23-2.-Condorcet-Methods)*

* **Condorcet Basic** Give you the natural winner or loser of Condorcet, if there is one.  
* **Copeland** http://en.wikipedia.org/wiki/Copeland%27s_method
* **Dodgson** https://en.wikipedia.org/wiki/Dodgson%27s_method
* **Kemeny-Young** http://en.wikipedia.org/wiki/Kemeny-Young_method _Kemeny-Young is currently limited up to 8 candidats. Note that, for 8 candidates, you must provide into php.ini a memory_limit upper than 160MB._
* **Minimax Family** http://en.wikipedia.org/wiki/Minimax_Condorcet
    * **Minimax Winning** *(Does not satisfy the Condorcet loser criterion)*  
    * **Minimax Margin** *(Does not satisfy the Condorcet loser criterion)*
    * **Minimax Opposition** :warning: *By nature, this alternative does not meet any criterion of Condorcet.*
* **RankedPairs *(Since v0.10, EXPERIMENTAL)*** https://en.wikipedia.org/wiki/Ranked_pairs  
* **Schulze Family** http://en.wikipedia.org/wiki/Schulze_method  
    * **Schulze Winning** Schulze Winning is recommended by Markus Schulze himself. ***This is the default choice.*** *This method is also known as Schulze Method.*
    * **Schulze Margin** Variant from Markus Schulze himself.
    * **Schulze Ratio** Markus Schulze himself.

### Add your own method as module
This class is designed to be easily extensible with new algorithms (they don't need share the same namespace). A modular schematic is already used for all algorithms provided, so you can easily help, do not forget to make a pull request!  
[*More explanations in the documentation below*](https://github.com/julien-boudry/Condorcet/wiki/III-%23-C.-Extending-Condorcet-%23-1.-Add-your-own-ranking-algorithm)      

---------------------------------------
## How to use it?

_I have undertaken and continues to undertake efforts to reform and improve the documentation. Thereof is not yet satisfactory and perfectly updated. Your help is welcome!_

## Install

#### Autoloading:   
This project is consistent with the standard PSR-4 and can be loaded easily and without modification in most frameworks or Composer autoloader. Namespace \Condorcet is used. 
The examples also provide easy example of implementation using an optional Condorcet autoloader. If you don't want to use composer or PSR-4 autoloader.

#### Composer, Vanilla PHP, others Frameworks...

> [**Please visit the install section from the wiki**](https://github.com/julien-boudry/Condorcet/wiki/I-%23-Installation-%26-Basic-Configuration-%23-1.-Installation)    

## Condorcet Wiki Manual

* **[Visit the Manual](https://github.com/julien-boudry/Condorcet/wiki)**

Living and learning examples, giving an overview but not exhaustive of the possibilities of the library.

## Class & Methods reference

The precise documentation of methods is not a wiki. It can be found in the form of Markdown in the "Documentation" folder for each release.   
* [Class & Methods documentation](Documentation/)

## Examples

### Great overview

* [Non-visual quick tour of opportunities without interface](Examples/1.%20Overview.php) (not exhaustive and partial, but just a great tour.)

### With html output basics examples

* [Visual simple & advanced script examples with HTML output](Examples/Examples-with-html/)

### Specifics examples

* [Condorcet Wiki Manual](https://github.com/julien-boudry/Condorcet/wiki) provide many code example
* [Manage millions of votes with an external database drive](Examples/Specifics_Examples/use_large_election_external_database_drivers.php) Your own driver, or the provided simple driver for PDO.


### Really quick and simple example

_OK: sacrifice to the local tradition of lazy._    

```php
  use Condorcet\Condorcet;
  use Condorcet\Election;
  use Condorcet\Candidate;
  use Condorcet\Vote;

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
          echo 'My winner is ' . $myWinner->getName() . '<br>' ;

    // Natural Condorcet Loser
    $myLoser = $myElection1->getLoser(); // Return a candidate object
          echo 'My loser is ' . $myLoser->getName() ;

    // Schulze Ranking
    $myResultBySchulze = $myElection1->getResult('Schulze'); // Return a multi-dimensional array, filled with objects Candidate (multi-dimensional if tie on a rank)
      # Echo it easily 
      Condorcet::format($myResultBySchulze);

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


  # And many many more than that. Read the doc. & look advanced examples.
```

## Performance & Coding style considerations

#### Coding standards:  
The code is very close to the respect of PSR-1 (lacks only the naming of methods), and freely influenced by PSR-2 when it is not unnecessarily authoritarian.  

#### Performance:  
* Complex use case with three algorithms chained (Natural Condorcet, Schulze, Copeland), multiple elections sharing votes & candidates and hundreds of votes.
  * _Memory usage: less than 2M_    
  * _Execution time: less than 30ms_  
###### Kemeny-Youg case:   
* use Kemeny-Young 6 candidates: 5MB - 150ms    
* use Kemeny-Young 7 candidates: 32MB - 600ms    
* use Kemeny-Young 8 candidates: 135MB - 2500ms    
###### Massive election case:  
Extending PHP memory_limit allows you to manage hundreds of thousands of votes, but it can be a bit slower than outsource this data (PHP don't like that) and it's not extensive to infinity.   

If you need to manage election with more than 50 000 votes. You should consider externalize your data, Condorcet provide a simple PDO driver to store data outside RAM between processing steps, this driver store it into classical relational database system, it's support hundreds millions votes _(or more)_.
You can too develop your own datastore driver (to store into NoSQL... all yours fantasy), the modular architecture allows you to link it easily.

[Have a look to the manual](https://github.com/julien-boudry/Condorcet/wiki/III-%23-A.-Avanced-features-%26-Configuration-%23-3.-Get-started-to-handle-millions-of-votes)     

_Benchmark on a modern machine (linux - x64 - php 7.0 - cli)._ 

## Roadmap for further releases 
  
  - Better cache system to prevent any full computing of the Pairwise on new vote / remove vote
  - Improve & test Ranked pair implementation *(help needed!)*
  - Rebuild Exception System
  - **Looking for testers!**   
  - **Research reference librarians !!**  


## Related projects / They use Condorcet
* From August 2014: [Condorcet.Vote](http://www.condorcet.vote) Web services to create and store online Condorcet election. Including interactives and collaborative features.    
It is based in large part on this project, and uses the library as a real election manager for computing, storage & stats.        
* [Mahler-S2-BlindTest-Condorcet
](https://github.com/julien-boudry/Mahler-S2-BlindTest-Condorcet) (French interface) Web wrapper to compute and show result for classical music blind challenge with the Condorcet Class full potential (can also be used and adapted for any elections).    
Look like the examples provided here, but better: [Gustav Mahler blind listening test](http://classik.forumactif.com/t7244-ecoute-comparee-mahler-2e-symphonie-la-suite)    
