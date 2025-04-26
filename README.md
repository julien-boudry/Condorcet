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

Condorcet is a powerful engine for managing electoral processes and calculating election results. It handles all aspects from configuration and vote collection to result calculation. The library natively implements over 20 voting methods compatible with preferential voting ballots, including Condorcet methods, Alternative Voting, STV, and many others.
=> [**See all supported voting methods**](#supported-voting-methods)

**Two ways to use Condorcet:**
* **[Command Line Application](#use-condorcet-as-a-command-line-application)**: For quick access to essential features without technical expertise. Easily compute election results and statistics.
* **[PHP Library](#use-condorcet-as-a-php-library)**: Integrate into your code to access all advanced features (custom manipulations, extensions, performance optimizations, advanced I/O methods, etc.).

Both approaches can handle massive numbers of votes (hundreds of millions) on modest hardware.


## Summary
- [Condorcet PHP](#condorcet-php)
  - [Project State and Specifications](#project-state-and-specifications)
  - [Supported Voting Methods](#supported-voting-methods)
    - [Single-Winner Methods provided natively](#single-winner-methods-provided-natively)
      - [Deterministic](#deterministic)
      - [Lotteries](#lotteries)
    - [Proportional Methods provided natively](#proportional-methods-provided-natively)
    - [Add your own voting method as a module](#add-your-own-voting-method-as-a-module)
  - [Main Features](#main-features)
  - [Use Condorcet as a command line application](#use-condorcet-as-a-command-line-application)
    - [Install as a command line application](#install-as-a-command-line-application)
    - [Condorcet Book - Command Line](#condorcet-book---command-line)
  - [Use Condorcet as a PHP Library](#use-condorcet-as-a-php-library)
    - [Install / Autoloading](#install--autoloading)
    - [Library Manual](#library-manual)
    - [Class & API References](#class--api-references)
    - [PHP Library - Examples](#php-library---examples)
  - [Performance & Coding Style Considerations](#performance--coding-style-considerations)
    - [Coding Standards](#coding-standards)
    - [Performance](#performance)
  - [Roadmap for Further Releases](#roadmap-for-further-releases)
  - [Related projects / They use Condorcet](#related-projects--they-use-condorcet)

## Project State and Specifications

> [**Release Notes**](CHANGELOG.md)

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

_All versions require Json and Mbstring extensions (or polyfill). Pdo-Sqlite is recommended for elections with hundreds of thousands of votes or more._

## Supported Voting Methods
Condorcet supports both "single-winner" methods _(with full ranking extrapolation)_ and proportional representation systems.

[**Full details on voting methods, options, and implementation choices**](Docs/VotingMethods.md)

### Single-Winner Methods provided natively
Single-winner methods return a complete ranking of all candidates.

#### Deterministic
> Condorcet / Borda (+ Nauru variant) / Copeland / Dodgson (2 Approximations) / FPTP / Instant-runoff (alternative vote) / Kemeny–Young / Minimax (+ variants) / Ranked Pairs (+ variants) / Schulze (+ variants)

#### Lotteries
> Random Ballot / Random Candidates

### Proportional Methods provided natively
Methods designed for electing multiple candidates to an assembly.

> Single Transferable Vote *(STV)* / Comparison of Pairs of Outcomes by the Single Transferable Vote *(CPO-STV)* / Highest Averages Methods *(Sainte-Laguë, Jefferson/D'Hondt, and variants)* / Largest Remainder Methods _(with different quotas)_

### Add your own voting method as a module
Condorcet features a modular architecture allowing easy extension with new algorithms:
- [Documentation on creating voting methods](https://docs.condorcet.io/book/3.AsPhpLibrary/9.ExtendingCondorcet/1.CreateNewVoteMethod)
- [Module Skeleton Template](https://github.com/CondorcetVote/Condorcet_Modules_Skeletons)

## Main Features
* **Complete Election Management**
  * Handle the full election cycle: candidate registration, vote collection, result calculation
  * Support for vote ordering, tagging, deletion, and result simulation
  * Multiple input formats (string, JSON, objects)
  * Import/export support for Condorcet Election Format, Debian Format, David Hill Format
  * Integrity verification through checksumming
  * Serialization and data export for storage
  * User-friendly features (vote validation, anti-spam, human-readable results)

* **Comprehensive Results and Statistics**
  * Identify Condorcet Winners, Losers, and Paradoxes
  * Generate complete rankings using various [voting methods](Docs/VotingMethods.md)
  * Detailed statistical analysis of results
  * Support for both complete and partial ballot rankings
  * Vote weighting capabilities

* **Advanced Capabilities**
  * Object-oriented design for flexibility and extendability
  * Dynamic candidate and vote management across multiple elections _(simulate election easily)_
  * External storage drivers for handling massive elections
  * Intelligent caching system _(performance optimization)_
  * Vote constraints

* **Extensibility and Configuration**
  * Extend functionality without modifying core code
  * Create custom vote methods, constraints, and storage drivers
  * Extensive configuration options

Although not primarily designed for maximum performance, Condorcet delivers predictable, linear scaling even with very large elections. While not formally certified, comprehensive test coverage ensures reliable results.

---------------------------------------
## Use Condorcet as a command line application

### Install as a command line application

Available installation methods:
- Native installation from source with Composer
- Standalone PHAR executable file
- Docker image (build or pull)

> **[Installation instructions for command line usage](https://docs.condorcet.io/book/3.AsPhpLibrary/1.Installation)**

### Condorcet Book - Command Line

* [**Usage Examples**](https://docs.condorcet.io/book/2.AsCommandLineApplication/2.QuickExample)
* [**Command Reference**](https://docs.condorcet.io/book/2.AsCommandLineApplication/4.ManPage)

## Use Condorcet as a PHP Library

### Install / Autoloading
Uses namespace `\CondorcetPHP\Condorcet`

Installation options:
- Composer (recommended) or any native PSR-4 compatible autoloader
- Native autoloader (included)

> **[Installation instructions for PHP library usage](https://docs.condorcet.io/book/3.AsPhpLibrary/1.Installation)**

### Library Manual

> **[Complete Documentation Book](https://docs.condorcet.io)**

The documentation includes comprehensive examples illustrating the library's capabilities.

### Class & API References
> **[API Reference](/Docs/api-reference/README.md)**

### PHP Library - Examples

**Overview Examples:**
* [General Overview](Examples/1.%20Overview.php) (tour of main features)
* [Advanced Object Management](Examples/1.%20AdvancedObjectManagement.php)

**HTML Output Examples:**
* [Visual examples with HTML output](Examples/Examples-with-html/)

**Specific Examples:**
* [Documentation Book](https://docs.condorcet.io/) contains numerous code examples
* [Managing millions of votes with external database drivers](Examples/Specifics_Examples/use_large_election_external_database_drivers.php)

## Performance & Coding Style Considerations

### Coding Standards:
The codebase follows PSR-12 with some flexibility, enforced via [CS-Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer) through[Laravel Pint](https://github.com/laravel/pint).

### Performance:
* **Typical Use Case:** Complex scenario with all voting methods, 6 candidates, 2 seats, 1,000 votes
  * Memory usage: under 3MB
  * Execution time (with JIT): under 160ms
  * Execution time (without JIT): under 250ms

Performance varies significantly between voting methods. See [method benchmarks](Benchmarks/History/MethodsBench.md).

**Kemeny-Young Performance:**
* 7 candidates: ~5MB memory, 10ms
* 8 candidates: ~6MB memory, 10ms
* 9 candidates: ~7MB memory, 1.1s
* 10 candidates: ~7MB memory, 14s
* 11 candidates: ~8MB memory, 193s

**Large Elections:**
For elections with 50,000+ votes, consider external storage to avoid memory constraints. Condorcet includes a PDO driver that works with standard relational databases to handle hundreds of millions of votes. A simple SQLite implementation is provided.

Custom storage drivers can be implemented for NoSQL or other storage systems.

[See the documentation on handling large elections](https://docs.condorcet.io/book/3.AsPhpLibrary/8.GoFurther/5.GetStartedToHandleMillionsOfVotes)

_Benchmarks run on a modern Linux x64 system with PHP 8.1 (CLI)._

## Roadmap for Further Releases
* Future developments to be announced

## Related projects / They use Condorcet
> **[List of known projects, add yours!](https://github.com/julien-boudry/Condorcet/discussions/categories/your-condorcet-projects)**
