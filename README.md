<p align="center">
  <img src="Assets/Logos/condorcet-logo.png" alt="Condorcet" width="40%">
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

Condorcet manages the stages of an electoral process (configuration, votes ingestion & manipulation, integrity) and calculates the results. It offers natively the implementation of more than 20 voting methods compatible with preferential voting ballots, including Condorcet methods, Alternative Voting, STV, and many others.  
=> [**Supported Voting Methods**](#supported-voting-methods) 

_Two different ways to use Condorcet:_
* A [**command line application**](#condorcet-wiki---command-line-manual), for quick use of essential features without complicated technical knowledge. Allowing you to easily compute your election results and stats.  
* A [**PHP library**](#use-condorcet-as-a-php-library) that you can include in your code to take advantage of 100% of the advanced features (advanced manipulations & configurations, extensions & modularity, cache & high performances simulations, advanced input and output methods...).  

_Both approaches can handle up to hundreds of millions of votes (or more) on modest hardware. Although using it as a library will allow more configuration and control over this advanced usage._


## Summary
1. [Project State and Specifications](#project-state-and-specifications)  
1. [Supported Voting Methods](#supported-voting-methods)  
1. [Main features](#main-features)     
  a. [Methods provided natively](#methods-provided-natively)     
  b. [Add your own method](#add-your-own-method-as-module)  
1. [Use Condorcet as a command line application](#use-condorcet-as-a-command-line-application)  
  a. [Install as a command line application](#install-as-a-command-line-application)  
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

| Version | PHP Requirements | State | Support
| --- | --- | --- | --- |
| 4.2 | 8.1 | Dev | ✔ _support provided_
| 4.1 | 8.1 | Stable | ✔ _support provided_
| 3.x | 8.1 | Old Stable | ❌ _not any support_ 
| 2.2 | 7.4 | Old Stable | ❌ _support requiring some bait_ 
| 2.0 | 7.1 | Old Stable | ❌ _support requiring some bait_ 
| 1.0 | 5.6 | Old Stable | ❌ _support requiring some bait_ 
| 0.97 | 5.5 | Old Stable | ❌ _support requiring some bait_<br>ℹ _Since v0.90, you should consider then it's a new project (api, engine)._ 
| 0.14 | 5.5 | Old Stable | ❌ _ready for the museum_ 

_All versions require Json and Mbstring extensions. Pdo-Sqlite is recommended if you need to activate the default provided driver for bigs elections (hundred of thousands of votes or more)_

## Supported Voting Methods
Support both single-winner methods _(with or without the Condorcet criterion)_ and proportional methods.

[**Complete list of natively implemented methods, their options (variants), and implementation choices**](VOTING_METHODS.md)

### Single-Winner Methods provided natively
Single Winner returns a full ranking of all candidates, even though they are generally more designed to designate only one.

> Condorcet / Borda (+ Nauru variant) / Copeland / Dodgson (2 Approximations) / FTPT / Instant-runoff (alternative vote) / Kemeny–Young / Minimax (+ variants) / Ranked Pairs (+ variants) / Schulze (+ variants)

### Proportional Methods provided natively
Designed for electing assembly, return a full ranking of elected candidates.

> Single Transferable Vote *(STV)* / Comparison of Pairs of Outcomes by the Single Transferable Vote *(CPO-STV)* / Highest Averages Methods *(Sainte-Laguë, Jefferson/D'Hondt, and variants)* / Largest Remainder Methods _(with different quotas)_

### Add your own method as module
Condorcet is designed to be easily extensible with new algorithms (they don't need to share the same namespace).  
[*More explanations in this documentation*](https://github.com/julien-boudry/Condorcet/wiki/III-%23-B.-Extending-Condorcet-%23-1.-Add-your-own-ranking-algorithm-%28library%29)  

## Main features
* __Manage an election__
  * Respect an election cycle: Registering candidates, registers votes, gets results from many algorithms.
  * Ordered votes, tags votes, delete votes, simulates partial results.
  * Many input types available _(string, Json, objects...)_
  * Import from Condorcet Election Format _(and export to)_, Debian Format, David Hill Format
  * Integrity check (checksumming)
  * Support for storing elections (serializing Election object, export data...)
  * Some methods can be used nearly front final user (vote constraints, anti-spam check, parsing input, human-friendly results and stats...)
* __Get election results and stats__
  * Get the natural Condorcet Winner, Loser, Pairwise, Paradox...
  * Get full ranking from advanced [voting methods](VOTING_METHODS.md)
  * Get some additional stats from these methods
  * Force ranking all candidates implicitly _(default)_ or allow voters to not rank all candidates.
  * Put weight on a certain vote, and give more importance to certain voters.
* __Be more powerful__
  * All are objects, all are abstract _(But there are many higher-level functions and inputs types)_.
  * Candidates and Votes are objects which can take part in multiple elections at the same time and change their name or ranking dynamically. That allows powerful tools to simulate elections.
  * Manage hundred of billions of votes by activating an external driver to store (instead of RAM) an unlimited number of votes during the computation phase. A PDO driver is provided by default, an example is provided with SQLite, an interface that allows you to design other drivers.
  * Smart cache system, allows calling multiple time computation methods without performance issues.
* __Extend it! Configure it!__
  * Modular architecture to extend it without fork Condorcet PHP! Just make your module on your own namespace.
    * Election, Candidate, and Vote classes are extensible.
    * Add your own ranking algorithm.
    * Create your own vote constraints.
    * Use your own datastore driver to manage very large elections on your way without ram limit.
  * Many configuration options and methods.

_Condorcet PHP is not designed for high performance. But can handle virtually unlimited voting without limit or degrading performance, it's a linear and predictable scheme._  
_And has no certification or proven implementation that would guarantee a very high level of reliability. However, there are many written tests for each voting method and feature. This ensures an excellent level of confidence in the results._   


---------------------------------------
## Use Condorcet as a command line application

### Install as a command line application

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
The code is very close to the respect of PSR-12 (lacks only the naming of methods) when it is not unnecessarily authoritarian or conservative and follows some additional rules. Code is checked and fixed with [CS-Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer) custom rules through [Laravel Pint](https://github.com/laravel/pint).

#### Performance:  
* Complete and huge use case with all voting methods chained, 6 candidates, 2 seats, and one thousand votes (many with implicit ranking).
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
Extending PHP memory_limit allows you to manage hundreds of thousands of votes, but it can be a bit slower than outsourcing this data (PHP doesn't like that) and it's not extensive to infinity.   

If you need to manage an election with more than 50 000 votes. You should consider externalizing your data, Condorcet provides a simple PDO driver to store data outside RAM between processing steps, this driver stores it into a classical relational database system, and it supports hundreds of millions of votes _(or more)_. A very simple example with Sqlite is provided and very easy to activate.   

You can also develop your homemade datastore driver (to store into NoSQL... all your fantasy), the modular architecture allows you to link it easily.

[Have a look at the manual](https://github.com/julien-boudry/Condorcet/wiki/III-%23-A.-Avanced-features---Configuration-%23-3.-Get-started-to-handle-millions-of-votes-%28library%29)     

_Benchmark on a modern machine (linux - x64 - php 8.1 - cli)._ 


## Roadmap for further releases 
* ...

## Related projects / They use Condorcet
* From August 2014: [Condorcet.Vote](http://www.condorcet.vote) Web services to create and store online Condorcet election. Including interactive and collaborative features.  
It is based in large part on this project and uses the library as a real election manager for computing, storage & stats.        
* [Mahler-S2-BlindTest-Condorcet
](https://github.com/julien-boudry/Mahler-S2-BlindTest-Condorcet) (French interface) Web wrapper to compute and show the result for classical music blind challenge with the Condorcet Class full potential (can also be used and adapted for any elections).    
Look like the examples provided here, but better: [Gustav Mahler blind listening test](http://classik.forumactif.com/t7244-ecoute-comparee-mahler-2e-symphonie-la-suite)    
