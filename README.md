<p align="center">
  <img src="condorcet-logo.png" alt="Condorcet" width="40%">
</p>

[![Packagist](https://d3g33cz5i5omk9.cloudfront.net/packagist/v/julien-boudry/condorcet.svg?style=for-the-badge)](https://packagist.org/packages/julien-boudry/condorcet)
[![License](https://d3g33cz5i5omk9.cloudfront.net/github/license/mashape/apistatus.svg?style=for-the-badge)](LICENSE.txt)
[![GitHub contributors](https://d3g33cz5i5omk9.cloudfront.net/github/contributors/julien-boudry/Condorcet.svg?style=for-the-badge)](https://github.com/julien-boudry/Condorcet/graphs/contributors)
[![Packagist Download](https://d3g33cz5i5omk9.cloudfront.net/packagist/dt/julien-boudry/condorcet.svg?style=for-the-badge)](https://packagist.org/packages/julien-boudry/condorcet)
![GitHub code size in bytes](https://d3g33cz5i5omk9.cloudfront.net/github/languages/code-size/julien-boudry/Condorcet.svg?style=for-the-badge)

[![Codacy Badge](https://d3g33cz5i5omk9.cloudfront.net/codacy/grade/f34e354703514ab68248a0c995a4913a?style=for-the-badge)](https://app.codacy.com/gh/julien-boudry/Condorcet/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=julien-boudry/Condorcet&amp;utm_campaign=Badge_Grade)
[![Codacy Badge](https://d3g33cz5i5omk9.cloudfront.net/codacy/coverage/f34e354703514ab68248a0c995a4913a?style=for-the-badge)](https://app.codacy.com/gh/julien-boudry/Condorcet/dashboard?utm_source=github.com&amp;utm_medium=referral&amp)
[![Build Status](https://d3g33cz5i5omk9.cloudfront.net/github/workflow/status/julien-boudry/Condorcet/Execute%20All%20Tests?style=for-the-badge&label=Tests)](https://github.com/julien-boudry/Condorcet/actions)
[![Docker Hub](https://d3g33cz5i5omk9.cloudfront.net/docker/cloud/automated/julienboudry/condorcet?style=for-the-badge&logo=Docker%20Hub%20Condorcet)](https://hub.docker.com/r/julienboudry/condorcet)

> Main Author: [Julien Boudry](https://www.linkedin.com/in/julienboudry/)   
> License: [MIT](LICENSE.txt) _- Please say hello if you like or use this code!_  
> Contribute: [Contribute File](CONTRIBUTING.md)   
> Donation: **₿ [bc1qesj74vczsetqjkfcwqymyns9hl6k220dhl0sr9](https://blockchair.com/bitcoin/address/bc1qesj74vczsetqjkfcwqymyns9hl6k220dhl0sr9)** or **[Github Sponsor Page](https://github.com/sponsors/julien-boudry)**  
> _You can also offer me a bottle of good wine._  


Condorcet PHP
===========================
> **Presentation | [Manual](https://github.com/julien-boudry/Condorcet/wiki) | [Methods References](Documentation/README.md) | [Tests](Tests/)**  

Condorcet is an application implementing the Condorcet voting system and many other methods like the Schulze, Tideman, Borda, Alternative Voting or STV. And also a powerful election manager allowing the logical management of a phased election, with many natives options and elections methodes included.  

_Two different ways to use Condorcet:_
* A [**command line application**](#condorcet-wiki---command-line-manual), for quick use of essential features without complicated technical knowledge. Allowing you to easily compute your elections results and stats.  
* A [**PHP library**](#use-condorcet-as-a-php-library)   that you can include in your code to take advantage of 100% of the advanced features (abstraction, control, interaction, extensions).  


*Read more about alternative voting methods >> https://en.wikipedia.org/wiki/Condorcet_method*  

## Summary
1. [Project State and Specifications](#project-state-and-specifications)  
1. [Supported Methods](#supported-methods)  
1. [Main features](#main-features)     
  a. [Methods provided natively](#methods-provided-natively)     
  b. [Add your own method](#add-your-own-method-as-module)  
1. [Use Condorcet as a command line application](#use-condorcet-as-a-command-line-application)  
  a. [Install as an application](#install-as-an-application)  
  b. [Condorcet Wiki - Command Line Manual](#condorcet-wiki---command-line-manual)  
  c. [Command Line - Examples](#command-line---some-quick-examples)
1. [Use Condorcet as a PHP Library](#use-condorcet-as-a-php-library)  
  a. [Install / Autoloading](#install--autoloading)  
  b. [Condorcet Wiki Manual](#condorcet-wiki-manual)     
  c. [Class & Methods reference](#class--methods-reference)     
  d. [PHP Library - Examples](#php-library---examples) *Have a look on Examples!*     
  e. [Really quick and simple example](#really-quick-and-simple-example)
1. [Performance & Coding style considerations](#roadmap-for-further-releases)
1. [Roadmap for further releases](#roadmap-for-further-releases)
1. [Related projects / They use Condorcet](#related-projects--they-use-condorcet)  

## Project State and Specifications    

> [**Releases Notes**](CHANGELOG.md)

* **Stable Version: 4.0.x**  _support provided_  
  * * *PHP Requirement:* PHP 8.1
* **Stable Version: 2.2.x**  _support provided_  
  * * *PHP Requirement:* PHP 7.4 with Json PHP extension. _(tested up to PHP 8.0)_
* **Old Stable: 3.x** _limited support_  
    * *PHP Requirement:* PHP 8.1 _(tested up to PHP 8.1)_
* **Old Stable: 2.0.x** _support NOT provided_  
    * *PHP Requirement:* PHP 7.1 with Ctype, MB_String, Json common extensions. _(tested up to PHP 7.4)_
* **Very Old Stable: 1.0.x** _Support requiring some bait._  
    * *PHP Requirement:* PHP 5.6 with Ctype, MB_String, Json common extensions. _(tested up to PHP 7.1)_

_Some support and fix can be done for 0.14 version on demand. Since v0.90, you should consider then it's a new project (api, engine)._  

## Supported Methods
Support both single-winner methods (with or without Condorcet criterion) and proportional methods.

[**Complete list of natively implemented methods and implementation choices**](VOTING_METHODS.md)

### Single-Winner Methods provided natively
Single Winner return a full ranking. But they were designed for elected one. Inputs are ordered ranking.

> Condorcet / Borda (+ Nauru variant) / Copeland / Dodgson (2 Approximations) / FTPT / Instant-runoff (alternative vote) / Kemeny–Young / Minimax (+ variants) / Ranked Pairs (+ variants) / Schulze (+ variants)

### Proportional Methods provided natively
Designed for electing assembly. Inputs are ordered ranking, but most of these methods, don't support tie on a rank.

> Single Transferable Vote *(STV)* / Comparison of Pairs of Outcomes by the Single Transferable Vote *(CPO-STV)*

### Add your own method as module
Condorcet is designed to be easily extensible with new algorithms (they don't need to share the same namespace).  
[*More explanations in this documentation*](https://github.com/julien-boudry/Condorcet/wiki/III-%23-B.-Extending-Condorcet-%23-1.-Add-your-own-ranking-algorithm-%28library%29)  

## Main features
* __Manage an election__
  * Respect an election cycle: Registering candidate, registering a vote, get results from many algorithms.
  * Ordered votes, delete it, simulate partials results.
  * Many input type available (String, Json, Parse text, Objects...)
  * Integrity check (checksumming) and logs.
  * Support for storing elections (serializing Election object, export data...)
  * Some methods can be used nearly front final user (anti-spam check, parsing input, human-friendly results and stats, vote constraints...)
* __Get election results and stats__
  * Get the natural Condorcet Winner, Loser, Pairwise, Paradox...
  * Get full ranking from advanced Condorcet-like methods (Schulze, Copeland, Ranked Pairs, Kemeny–Young, Minimax...)
  * Get full ranking from others methods like Alternative Vote
  * Get full ranking from proportional methods (STV...)
  * Get some additional stats from these methods
  * Force ranking all candidate implicitly _(default)_ or allow voters to not rank all candidates.
  * Put weight for each vote, give more importance to certain voters.
* __Be more powerful__
  * All are objects, all are abstract _(But there are many higher-level functions and inputs types)_.
  * Candidates and Votes are objects which can take part to multiples elections at the same time and change her name or ranking dynamically. That allows powerful tools to simulate elections.
  * Manage hundred billions of votes by activating an external driver to store (instead of RAM) an unlimited number of votes during the computation phase. A PDO driver is provided by default, an example is provided with SQLite, an interface allows you to design other drivers.
* __Extend it! Configure it!__
  * Modular architecture to extend it without fork Condorcet PHP! Just make your own module on your own namespace.
    * Election, Candidate and Vote class are extensible.
    * Add your own ranking algorithm.
    * Create your own votes constraints.
  * Allow you to use your own datastore driver to manage very large elections at your way without ram limit. A first driver for PDO (and Sqlite example) is provided.
  * Many configurations options and methods.

_Condorcet PHP is not designed for high performances. But can handle virtually unlimited voting without limit or degrading performance, it's a linear and predictable scheme._  
_And has no certification or proven implementation that would guarantee a very high level of reliability. However, there are many written tests for each voting methods and features. This ensures an excellent level of confidence in the results._   


---------------------------------------
## Use Condorcet as a command line application

### Install as an application

Can be installed natively from source (with composer), from PHAR file, from Docker image (build or pull).

> **[Condorcet as a command line application, installation instructions](https://github.com/julien-boudry/Condorcet/wiki/I-%23-Installation-%28command-line%29)**

### Condorcet Wiki - Command Line Manual

* [**Examples**](https://github.com/julien-boudry/Condorcet/wiki/III-%23-Usage-%28command-line%29)
* [**Man Page**](https://github.com/julien-boudry/Condorcet/wiki/II-%23-Man-Page-%28command-line%29)

## Use Condorcet as a PHP Library

### Install / Autoloading  
Namespace ```\CondorcetPHP\Condorcet``` is used. 

Can be installed as you prefer with: Composer / Natively provided autoloader / Any PSR-4 compatible autoloader.

> **[Condorcet as a PHP library, installation instruction](https://github.com/julien-boudry/Condorcet/wiki/I-%23-Installation---Basic-Configuration-%23-1.-Installation-%28library%29)**  

### Library Manual

> **[Visit the Manual](https://github.com/julien-boudry/Condorcet/wiki)**

Living and learning examples, giving an overview but not exhaustive of the possibilities of the library.

### Class & Methods reference

The precise documentation of methods is not a wiki. It can be found in the form of Markdown in the "Documentation" folder for each release.   
> **[Class & Methods documentation](Documentation/README.md)**

### PHP Library - Examples

### Overview

* [General Overview](Examples/1.%20Overview.php) _(not exhaustive and partial, but just a great tour.)_
* [Advanced Object Management](Examples/1.%20AdvancedObjectManagement.php)

### With Html output basics examples

* [Visual simple & advanced script examples with HTML output](Examples/Examples-with-html/)

### Specifics examples

* [Condorcet Wiki Manual](https://github.com/julien-boudry/Condorcet/wiki) provides many code example
* [Manage millions of votes with an external database drive](Examples/Specifics_Examples/use_large_election_external_database_drivers.php) Your own driver, or the provided simple driver for PDO.


## Performance & Coding style considerations

#### Coding standards:  
The code is close to the respect of PSR-1 (lacks only the naming of methods), and freely influenced by PSR-2 when it is not unnecessarily authoritarian.  

#### Performance:  
* Complete and huge use case with all voting methods chained, 6 candidates, 2 seats and one thousand votes (many with implicit ranking).
  * _Memory usage: less than 3M_    
  * _Execution time (after Jit compiling): less than 160ms_  
  * _Execution time (without JIT): less than 250ms_  

But essentially because some voting methods are slow by design and others (like Schulze) are very fast. Have a look on [methods benchmarks](Benchmarks/History/MethodsBench.md).

###### Kemeny-Youg case:   
_1 000 randoms votes. Memory consumption comes from votes more than combinations._

* use Kemeny-Young 7 candidates: ~5MB - 10ms    
* use Kemeny-Young 8 candidates: ~6MB - 10ms    
* use Kemeny-Young 9 candidates: ~7MB - 1.1s    
* use Kemeny-Young 10 candidates: ~7MB - 14s   
* use Kemeny-Young 11 candidates: ~8MB - 193s   

###### Massive election case:  
Extending PHP memory_limit allows you to manage hundreds of thousands of votes, but it can be a bit slower than outsource this data (PHP don't like that) and it's not extensive to infinity.   

If you need to manage an election with more than 50 000 votes. You should consider externalizing your data, Condorcet provides a simple PDO driver to store data outside RAM between processing steps, this driver stores it into a classical relational database system, it supports hundreds millions votes _(or more)_. A very simple example with Sqlite is provided and very easy to activate.   

You can also develop your own datastore driver (to store into NoSQL... all your fantasy), the modular architecture allows you to link it easily.

[Have a look to the manual](https://github.com/julien-boudry/Condorcet/wiki/III-%23-A.-Avanced-features---Configuration-%23-3.-Get-started-to-handle-millions-of-votes-%28library%29)     

_Benchmark on a modern machine (linux - x64 - php 8.0 - cli)._ 


## Roadmap for further releases 
* ...

## Related projects / They use Condorcet
* From August 2014: [Condorcet.Vote](http://www.condorcet.vote) Web services to create and store online Condorcet election. Including interactives and collaborative features.    
It is based in large part on this project and uses the library as a real election manager for computing, storage & stats.        
* [Mahler-S2-BlindTest-Condorcet
](https://github.com/julien-boudry/Mahler-S2-BlindTest-Condorcet) (French interface) Web wrapper to compute and show the result for classical music blind challenge with the Condorcet Class full potential (can also be used and adapted for any elections).    
Look like the examples provided here, but better: [Gustav Mahler blind listening test](http://classik.forumactif.com/t7244-ecoute-comparee-mahler-2e-symphonie-la-suite)    
