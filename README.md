<p align="center">
  <img src="https://raw.githubusercontent.com/julien-boudry/Condorcet/master/Assets/Logos/condorcet-logo.png" alt="Condorcet" width="40%">
</p>

[![Open in GitHub Codespaces](https://github.com/codespaces/badge.svg)](https://github.com/codespaces/new?hide_repo_select=true&ref=master&repo=17303525&devcontainer_path=.devcontainer%2Fdevcontainer.json)

[![License](https://d3g33cz5i5omk9.cloudfront.net/github/license/julien-boudry/condorcet?style=for-the-badge)](LICENSE.txt)
[![Packagist](https://d3g33cz5i5omk9.cloudfront.net/packagist/v/julien-boudry/condorcet.svg?style=for-the-badge)](https://packagist.org/packages/julien-boudry/condorcet)
[![Docker Pulls](https://d3g33cz5i5omk9.cloudfront.net/docker/pulls/julienboudry/condorcet?style=for-the-badge)](https://hub.docker.com/r/julienboudry/condorcet)
[![Packagist Download](https://d3g33cz5i5omk9.cloudfront.net/packagist/dt/julien-boudry/condorcet.svg?style=for-the-badge&label=Packagist%20Download)](https://packagist.org/packages/julien-boudry/condorcet)
[![GitHub contributors](https://d3g33cz5i5omk9.cloudfront.net/github/contributors/julien-boudry/Condorcet.svg?style=for-the-badge)](https://github.com/julien-boudry/Condorcet/graphs/contributors)

[![Codacy Badge](https://d3g33cz5i5omk9.cloudfront.net/codacy/grade/f34e354703514ab68248a0c995a4913a?style=for-the-badge&label=Codacy%20Code%20Quality)](https://app.codacy.com/gh/julien-boudry/Condorcet/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=julien-boudry/Condorcet&amp;utm_campaign=Badge_Grade)
![GitHub code size in bytes](https://d3g33cz5i5omk9.cloudfront.net/github/languages/code-size/julien-boudry/Condorcet.svg?style=for-the-badge)
[![Codacy Badge](https://d3g33cz5i5omk9.cloudfront.net/codacy/coverage/f34e354703514ab68248a0c995a4913a?style=for-the-badge)](https://app.codacy.com/gh/julien-boudry/Condorcet/dashboard?utm_source=github.com&amp;utm_medium=referral&amp)
[![Build Status](https://d3g33cz5i5omk9.cloudfront.net/github/actions/workflow/status/julien-boudry/Condorcet/execute_all_tests.yml?branch=master&style=for-the-badge&label=Tests)](https://github.com/julien-boudry/Condorcet/actions)
  

> Main Author: [Julien Boudry](https://www.linkedin.com/in/julienboudry/)   
> License: [MIT](LICENSE.txt) - _Please [say hello](https://github.com/julien-boudry/Condorcet/discussions/categories/your-condorcet-projects) if you like or use this code!_  
> Contribute: [Contribution File](CONTRIBUTING.md)   
> Donation: **₿ [bc1q3jllk3qd9fjvvuqy07tawkv7t6h7qjf55fc2gh](https://blockchair.com/bitcoin/address/bc1q3jllk3qd9fjvvuqy07tawkv7t6h7qjf55fc2gh)** or **[GitHub Sponsor Page](https://github.com/sponsors/julien-boudry)**  
> _You can also offer me a bottle of good wine._  


Condorcet PHP<!-- {docsify-ignore-all} -->
===========================
> **Presentation | [Documentation Book](https://docs.condorcet.io) | [API Reference](/Docs/api-reference/README.md) | [Voting Methods](/Docs/VotingMethods.md) | [Tests](https://github.com/julien-boudry/Condorcet/tree/master/tests)**

Condorcet manages the stages of an electoral process (configuration, votes ingestion & manipulation, integrity) and calculates the results. It offers natively the implementation of more than 20 voting methods compatible with preferential voting ballots, including Condorcet methods, Alternative Voting, STV, and many others.
=> [**Supported Voting Methods**](#supported-voting-methods)

_Two different ways to use Condorcet:_
* A [**command line application**](#use-condorcet-as-a-command-line-application), for quick use of essential features without complicated technical knowledge. Allowing you to easily compute your election results and stats.
* A [**PHP library**](#use-condorcet-as-a-php-library) that you can include in your code to take advantage of 100% of the advanced features (advanced manipulations & configurations, extensions & modularity, cache & high performances simulations, advanced input and output methods...).

_Both approaches can handle up to hundreds of millions of votes (or more) on modest hardware. Although using it as a library allows more configuration and control over this advanced usage._


## Summary <!-- {docsify-ignore-all} -->
- [Condorcet PHP](#condorcet-php)
  - [Summary](#summary)
  - [Project State and Specifications](#project-state-and-specifications)
  - [Supported Voting Methods](#supported-voting-methods)
    - [Single-Winner Methods provided natively](#single-winner-methods-provided-natively)
      - [Deterministic](#deterministic)
      - [Lotteries](#lotteries)
    - [Proportional Methods provided natively](#proportional-methods-provided-natively)
    - [Add your own voting method as a module](#add-your-own-voting-method-as-a-module)
  - [Main features](#main-features)
  - [Use Condorcet as a command line application](#use-condorcet-as-a-command-line-application)
    - [Install as a command line application](#install-as-a-command-line-application)
    - [Condorcet Book - Command Line](#condorcet-book---command-line)
  - [Use Condorcet as a PHP Library](#use-condorcet-as-a-php-library)
    - [Install / Autoloading](#install--autoloading)
    - [Library Manual](#library-manual)
    - [Class \& API References](#class--api-references)
    - [PHP Library - Examples](#php-library---examples)
    - [Overview](#overview)
    - [With Html output basics examples](#with-html-output-basics-examples)
    - [Specifics examples](#specifics-examples)
  - [Performance \& Coding style considerations](#performance--coding-style-considerations)
      - [Coding standards:](#coding-standards)
      - [Performance:](#performance)
          - [Kemeny-Young case:](#kemeny-young-case)
          - [Massive election case:](#massive-election-case)
  - [Roadmap for further releases](#roadmap-for-further-releases)
  - [Related projects / They use Condorcet](#related-projects--they-use-condorcet)

## Project State and Specifications

> [**Releases Notes**](CHANGELOG.md)

| Version | PHP Requirements | State | Support
| --- | --- | --- | --- |
| 5.0 | 8.4 | Stable | ✔ _support provided_
| 4.7 | 8.3 | Old Stable | ✔ _support provided_
| 4.6 | 8.2 | Old Stable | ❌ _not any support_
| 3.x | 8.1 | Old Stable | ❌ _not any support_
| 2.2 | 7.4 | Old Stable | ❌ _support requiring some incentive_
| 2.0 | 7.1 | Old Stable | ❌ _not any support_
| 1.0 | 5.6 | Old Stable | ❌ _not any support_
| 0.9x | 5.5 | Old Stable | ❌ ℹ _Since v0.90, you should consider then it's a new project (api, engine)._
| 0.14 | 5.5 | Old Stable | ❌ _ready for the museum_

_All versions require Json and Mbstring extensions (or polyfill). Pdo-Sqlite is recommended if you need to activate the default provided driver for big elections (hundreds of thousands of votes or more)._

## Supported Voting Methods
Support both single-winner methods _(with or without the Condorcet criterion)_ and proportional methods.

[**Complete list of natively implemented methods, their options (variants), and implementation choices**](Docs/VotingMethods.md)

### Single-Winner Methods provided natively
Single-winner methods return a full ranking of all candidates, even though they are generally designed to designate only one winner.

#### Deterministic
> Condorcet / Borda (+ Nauru variant) / Copeland / Dodgson (2 Approximations) / FPTP / Instant-runoff (alternative vote) / Kemeny–Young / Minimax (+ variants) / Ranked Pairs (+ variants) / Schulze (+ variants)

#### Lotteries
> Random Ballot / Random Candidates

### Proportional Methods provided natively
Designed for electing an assembly, return a full ranking of elected candidates.

> Single Transferable Vote *(STV)* / Comparison of Pairs of Outcomes by the Single Transferable Vote *(CPO-STV)* / Highest Averages Methods *(Sainte-Laguë, Jefferson/D'Hondt, and variants)* / Largest Remainder Methods _(with different quotas)_

### Add your own voting method as a module
Condorcet is designed to be easily extensible with new algorithms (they don't need to share the same namespace).
- [*More explanations in this documentation*](https://docs.condorcet.io/book/3.AsPhpLibrary/9.ExtendingCondorcet/1.CreateNewVoteMethod)
- [Modules Skeleton](https://github.com/CondorcetVote/Condorcet_Modules_Skeletons): A template for starting development quickly and following best practices.

## Main features
* __Manage an election__
  * Respect an election cycle: Register candidates, register votes, get results from many algorithms.
  * Ordered votes, tag votes, delete votes, simulate partial results.
  * Many input types available _(string, JSON, objects...)_
  * Import from Condorcet Election Format _(and export to)_, Debian Format, David Hill Format
  * Integrity check (checksumming)
  * Support for storing elections (serializing Election object, export data...)
  * Some methods can be used nearly by end users (vote constraints, anti-spam check, parsing input, human-friendly results and stats...)
* __Get election results and stats__
  * Get the natural Condorcet Winner, Loser, Pairwise, Paradox...
  * Get full ranking from advanced [voting methods](Docs/VotingMethods.md)
  * Get additional stats from these methods
  * Force ranking of all candidates implicitly _(default)_ or allow voters to not rank all candidates.
  * Put weight on certain votes, and give more importance to certain voters.
* __Be more powerful__
  * All are objects, all are abstract _(But there are many higher-level functions and input types)_.
  * Candidates and Votes are objects which can take part in multiple elections at the same time and change their name or ranking dynamically. This allows powerful tools to simulate elections.
  * Manage hundreds of billions of votes by activating an external driver to store (instead of RAM) an unlimited number of votes during the computation phase. A PDO driver is provided by default, an example is provided with SQLite, and an interface that allows you to design other drivers.
  * Smart cache system, allows calling computation methods multiple times without performance issues.
* __Extend it! Configure it!__
  * Modular architecture to extend it without forking Condorcet PHP! Just make your module in your own namespace.
    * Election, Candidate, and Vote classes are extensible.
    * Add your own ranking algorithm.
    * Create your own vote constraints.
    * Use your own datastore driver to manage very large elections in your way without RAM limit.
  * Many configuration options and methods.

_Condorcet PHP is not designed for high performance. But it can handle virtually unlimited voting without limit or degrading performance, it's a linear and predictable scheme._  
_It has no certification or proven implementation that would guarantee a very high level of reliability. However, there are many written tests for each voting method and feature. This ensures an excellent level of confidence in the results._   


---------------------------------------
## Use Condorcet as a command line application

### Install as a command line application

Can be installed natively from source (with composer), from PHAR file, from Docker image (build or pull).

> **[Condorcet as a command line application, installation instructions](https://docs.condorcet.io/book/3.AsPhpLibrary/1.Installation)**

### Condorcet Book - Command Line

* [**Examples**](https://docs.condorcet.io/book/2.AsCommandLineApplication/2.QuickExample)
* [**Man Page**](https://docs.condorcet.io/book/2.AsCommandLineApplication/4.ManPage)

## Use Condorcet as a PHP Library

### Install / Autoloading
Namespace ```\CondorcetPHP\Condorcet``` is used.

Can be installed as you prefer with: Composer / Natively provided autoloader / Any PSR-4 compatible autoloader.

> **[Condorcet as a PHP library, installation instructions](https://docs.condorcet.io/book/3.AsPhpLibrary/1.Installation)**

### Library Manual

> **[Visit the Documentation Book](https://docs.condorcet.io)**

Living and learning examples, giving an overview but not exhaustive of the possibilities of the library.

### Class & API References

The precise documentation of methods can be found in Markdown format in the "Documentation" folder for each release.
> **[Class & API References](/Docs/api-reference/README.md)**

### PHP Library - Examples

### Overview

* [General Overview](Examples/1.%20Overview.php) _(not exhaustive and partial, but just a great tour.)_
* [Advanced Object Management](Examples/1.%20AdvancedObjectManagement.php)

### With Html output basics examples

* [Visual simple & advanced script examples with HTML output](Examples/Examples-with-html/)

### Specifics examples

* [Condorcet Documentation Book](https://docs.condorcet.io/) provides many code examples
* [Manage millions of votes with an external database driver](Examples/Specifics_Examples/use_large_election_external_database_drivers.php) Your own driver, or the provided simple driver for PDO.


## Performance & Coding style considerations

#### Coding standards:
The code closely follows PSR-12 standards (only method naming conventions might differ) when they are not unnecessarily authoritarian or conservative, and follows some additional rules. Code is checked and fixed with [CS-Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer) custom rules through [Laravel Pint](https://github.com/laravel/pint).

#### Performance:
* Complete and complex use case with all voting methods chained, 6 candidates, 2 seats, and one thousand votes (many with implicit ranking).
  * _Memory usage: less than 3M_
  * _Execution time (after JIT compiling): less than 160ms_
  * _Execution time (without JIT): less than 250ms_

This varies because some voting methods are slow by design and others (like Schulze) are very fast. Have a look at the [methods benchmarks](Benchmarks/History/MethodsBench.md).

###### Kemeny-Young case:
_1 000 random votes. Memory consumption comes from votes more than combinations._

* use Kemeny-Young 7 candidates: ~5MB - 10ms
* use Kemeny-Young 8 candidates: ~6MB - 10ms
* use Kemeny-Young 9 candidates: ~7MB - 1.1s
* use Kemeny-Young 10 candidates: ~7MB - 14s
* use Kemeny-Young 11 candidates: ~8MB - 193s

###### Massive election case:
Extending PHP memory_limit allows you to manage hundreds of thousands of votes, but it can be slower than outsourcing this data (PHP doesn't like that) and it's not extensible to infinity.

If you need to manage an election with more than 50,000 votes, you should consider externalizing your data. Condorcet provides a simple PDO driver to store data outside RAM between processing steps. This driver stores it into a classical relational database system and supports hundreds of millions of votes _(or more)_. A very simple example with SQLite is provided and very easy to activate.

You can also develop your own custom datastore driver (to store in NoSQL... or whatever you fancy), the modular architecture allows you to link it easily.

[Have a look at the documentation book](https://docs.condorcet.io/book/3.AsPhpLibrary/8.GoFurther/5.GetStartedToHandleMillionsOfVotes)

_Benchmark on a modern machine (Linux - x64 - PHP 8.1 - CLI)._


## Roadmap for further releases
* ...

## Related projects / They use Condorcet
**[See the list of known projects, add yours!](https://github.com/julien-boudry/Condorcet/discussions/categories/your-condorcet-projects)**
