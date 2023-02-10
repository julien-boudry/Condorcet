CHANGELOG
=========
All notable changes to this project will be documented in this file.

## [v4.3.0] - 2023-??-??
### Description
Require `PHP 8.2`. Include a new random vote generator API. Some devcontainers for dev and command line usage. And a new documentation book and others documentation improvements.

### Added

#### Public API
- New VotesRandomGenerator class providing random votes following either a reproducible or cryptographically secure scheme with many options.
- New `Election->removeAllVotes()` method.
- New `Election->countRanks()` method.

#### Core
- Upgrade to `PHP 8.2`and use new features.
- Add some `true` return type.
- Various refactoring & cleanup.

#### Distribution
- New Devcontainer for prod. Very easy to execute as a command line application directly in Github Codespace.

#### Docs
- New Docs directory structure.
- New www.condorcet.io docbook replacing Github Wiki.
- Improve Api Reference docs generator.
- Ongoing efforts to improve documentation.


### Changed
#### General

#### Voting Methods

### Internal changes
#### Engine

#### Dev
- New Dev Container for Dev

## [v4.2.2] - 2023-02-07

### Dev
- Upgrade to PHPUnit `^10`

## [v4.2.1] - 2022-11-17

### Changed
- [Console] Fix issue #110 - Crash on environnement without `PDO/Sqlite` or with the `--deactivate-file-cache` option.
- [Console] Fix a `memory_limit` crash despite dataHandler driver if one entry with a multiplier contains more votes than `memory_limit` allows.

## [v4.2.0] - 2022-08-23
### Description
Redesign & refactor the console. And internal improvements.

### Added
- Support for PHP 8.2
- [Console] Redesign console, add many styles, colors, and logo.
- [Console] Improve interactive mode.

### Changed
- [Methods] Fix a rare crash on HighestAverage methods.
- New namespace `CondorcetPHP\Condorcet\Utils`and move `CondorcetUtil` and `VoteUtil` classes to it.

### Internal
- Refactor Vote entry parser to new class `CondorcetPHP\Condorcet\Utils\VoteEntryParser`: Better architecture and reduced code duplication.

### Dev
- [Benchmarks] Use a random deterministic seed for generating votes. (Benchmarks now require PHP 8.2 at least)
- [Tests] Use a random deterministic if available (PHP >= 8.2) for generating votes.
- [Coding Style] Improve rules.

## [v4.1.1] - 2022-07-07
### Changed
- Composer: Fix ext-mbstring dependency.
- HighestAverage & LargestRemainder methods: Fix a bug with an empty ranking in context (or non-valid rank in a vote)

## [v4.1.0] - 2022-06-26

### Description
Implements new proportional methods

### Added
#### Voting Methods
- Sainte-Laguë / Webster method from HighestAverage method class.
- Thomas Jefferson / D'Hondt method from HighestAverage method class.
- Largest Remainder with all STV Quotas (Hare/Droop/Hagenbach-Bischof/Imperiali) as method options (like STV or CPO-STV).

### Changed
- Default max length of a candidate name is now 100 UTF-8 characters instead of 30

### Internal changes
- Migrates the entire code base to a strict application of PSR-12 standards (and many other rules). It's debatable because our alternative syntax was lovely, but it's now standard and no longer iconoclastic, it's the age of reason. The respect for coding standards is now controlled by Laravel Pint.

## [v4.0.0] - 2022-06-17
### Description
Implement the CPO-STV method, the second official module for a proportional method. Comes with many performance improvements for some methods and elections with a lot of candidates.

### Added
#### Core
- Adds `Election->setStatsVerbosity()` and `Election->getStatsVerbosity()` methods. This allows controlling the level of stats returned in the Result object by some methods. And to save memory and processing time if needed.
#### Voting Methods
- New proportional method: **CPO-STV** Look at the [/Docs/VotingMethods.md](/Docs/VotingMethods.md) for more details
#### TieBreaker
- New Tie Breaker method `TieBreaker::TieBreakerWithAnotherMethods`, a chaining method to break a tie
- Condorcet Election Format: Ability to parse candidates directly from votes, if not specified with the parameters first. According to the V1 specification of the format.

### Changed
#### General
- Main source code moving to `src` folder instead of `lib` folder. Must change nothing if autoloaders are used. (but forks, if any, can need vigilance)
- A candidate's name can be equal to the string "0"
- Methods options can technically be string or array, first use for the CPO-STV tie breaker
- Use the native `JsonException` instead of custom `JsonFormatException`.

#### Voting Methods
- Ranked Pairs default limit of candidates up from 40 to 60, thanks to performance optimizations
- Kemeny-Young now accepts 10 candidates by default thanks to performance optimizations. Up to 12 candidates without spending the night. And up to infinity without burning too much memory.
- `Throwable\CandidatesMaxNumberReachedException` used in Kemeny-Young and Ranked Pairs now extends `Throwable\MethodLimitReachedException`, and you should prefer to catch the second one.
- Some methods like Kemeny-Young or CPO-STV methods, send fewer stats than before to the `Result->getStats()` returning an array. To get back full details, you need to specify `Election->setStatsVerbosity(StatsVerbosity::FULL)` or `Election->setStatsVerbosity(StatsVerbosity::HIGH)`. This change speedup some methods and can use less memory.

### Internal changes
#### Engine
- `Permutation` class was renamed to `Permutations`
- Use of `\SplFixedArray` in some methods, improving memory and performances in some cases and for some methods.
- Extensive rewriting of the Kemeny-Young voting method engine, twice as fast as before, consuming 42X less memory and allowing easy use 10-candidate with good performances. Do not use any disk cache file on disk anymore. However, since it uses real-time generators, reading its complete statistics (excluding rankings) may require more memory (but less than before).
- Many performance improvements, especially for some methods and elections with a lot of candidates.
- Methods can use a `Vote->getContextualRankingWithoutSort()` cache at the vote level, with a new internal API based on a WeakMap.
- New `Combinations` class.
- `Permutations` asn the new `Combinations` class can work internally with integer bigger than `PHP_MAX_INT` if installed from composer. But will always return an interger <= `PHP_MAX_INT` or throw a new exception `CondorcetPHP\Condorcet\Throwable\Internal\IntegerOverflowException`

#### Dev
- Add Configuration for PHPStan
- Add and improve benchmarks
- Fix some env bugs for console tests
- New `InternalModulesAPI` attribute (yet unused in doc)

## [v3.3.3] - 2022-05-02

### Changed
- ```Election::getVotesListAsString()``` will be ordered in a strict and predictable manner.
- Some methods (based on Borda, Majority, STV) they now work with a lower level of decimal precision. This avoids the accumulation of rounding differences, leading in some cases to tiny differences in statistics and extremely rarely, in differences in results (in case of extremely close elections with ties on ballot allowed) depending on the order in which the votes were entered in the election. This can result in unpredictable behavior that is uncomfortable for testing or accurate reproducibility. However, the behavior in these extreme cases can not be guaranteed 100%, even if it becomes even more rare and impractical. Pairwise methods (typically Condorcet methods) are not affected by this change.

## [v3.3.2] - 2022-05-01
### Added
- Condorcet Election Format: Compatibility with specification of ```/EMPTY_RANKING/``` keyword. (import and export)
### Changed
- Vote ranking from string input, cannot have empty rank (will be skipped silently instead of "" as candidate name)

## [v3.3.1] - 2022-04-30
### Changed
- Fix a regression on Kemmeny-Young stats since v3.3.0 (should not affect the final ranking, but still dangerous and incorrect).

## [v3.3.0] - 2022-04-29
### Description
This release adds Converters classes able to parse and convert to a Condorcet Election object, the textual votes file (synthetic, with all votes) from David Hill format, Debian format, and a new (and better) Condorcet format. An Election can also be exported to this new Condorcet format.

Internally, circular references have been eliminated in favor of PHP8 weak references. It improve memory consumption before garbage collector call, prevents memory leaks from the PHP engine or Condorcet itself and removes some ugly code that was previously necessary to prevent them.

### Added
#### Converters classes
- New Condorcet Format (specifications, converter to an Election, converter to a file)
- David Hill format (converter to an Election)
- Debian Format (converter to an Election)

#### Console
- Options to import from Condorcet, David Hill, Debian formats.
- Bugfix: (display only) Wrong value for implicit voting

#### Others
- ```Election::getVotesListAsString($withContext = true)``` get this new optional parameter, to ignore election context (restituate vote as they are submited, without any interpretation).

### Internal changes
#### Structure
- Migration from circular references to PHP8 weak references. Improving memory consumption before garbage collector, and preventing memory leaks in the engine without any call (and sometimes successive call) to ```gc_collect_cycles()```. _Only complex users, creating simulations with many election objects and vote/candidates sharing between elections, in the same run, will experience factually the improvement. Or those who use some external data handler as Sqlite and needed to close connexion before the end of the script._

#### Internal API for methods modules or extended classes
- ```getLinks()``` internal API will now return a ```\Weakmap```
- About methods module extending the abstract class ```Algo\Method```, the ```$_selfElection``` property is now a ```\WeakReference```. And must to exclude it from serialization.
- ```Algo\MethodInterface``` now required the ```setElection(Election $election)``` method because she is a part of unserialize from Condorcet unserialize process.

=======
## [v3.2.3] - 2022-04-25
### Changed
- Update the dev. dependencies and in particular phploc (broken)
- Use Symfony Console attribute 'AsCommand' (before deprecation)
- Bugfix: Infinite loop in Instant-Runoff method (very rare)
## [v3.2.2] - 2021-12-14
### Changed
- Fix crash on ```Vote::addTags([])``` and add tests.
## [v3.2.1] - 2021-12-07
### Changed
- Console: Improve column width, prevent most of table cuts depending on the width of the terminal.
## [v3.2.0] - 2021-12-01
### Description
An important technical upgrade without many notable users changes.

### Added
- Use PHP 8.1 new features (and minimal requirements). Resulting in more modern code & performances improvements.
- Result class now offers some publics (PHP 8.1) readonly properties in addition to getter methods.

### Changed
- New Exceptions class and Exceptions hierarchies. Users must review all usage with exceptions captures. All new Exceptions extend the old CondorcetException class. But exceptions codes are no more used. (thanks @toddy15 for help and initiative)
- Improved documentation.
- Method::setOption() now only accept BackEnum or int parameter.
- StvQuotas parameters is now an Enum instead of a string.

### Internal changes
- Improved and refactored documentation generator tool.
- Add some dev & quality tools like Infection resulting in code quality improvements and news tests (thanks @toddy15 for help and initiative).
- Reduce Docker image size

## [v3.1.2] - 2021-11-23
### Changed
Bug fix: Command-line fatal error on NULL natural Condorcet winner / loser #72

## [v3.1.1] - 2021-08-03
### Added
- __CondorcetAutoloader check for the minimal PHP version #56
### Changed
- Bugfix: KemenyYoung wrong results when using Election::removeCandidates()
- Various documentation fixes and improvements

### Internal changes
- Docker: Upgrade Debian base image to Bullseye

## [v3.1.0] - 2021-08-03
### Description
Introduces support for proportional methods. And adds some.

### Added
- Minimal support for proportional methods
- New Single Transferable Vote (STV) method, a proportional one.
- New Options system for methods with parameters. Easier to use that extend and register class. Include new API methods.
- New methods Multiple Round system replace Two-round system and takes advantage of the method options. This change is backward compatible.
- Add IRV as an alias for Instant Runoff #48

### Changed
- getResult() Fix arbitrary candidate ordering on a rank. Now have consistency with getRanking and others.
- Some console improvements in style.
- Console: Shorten show-method-stats to method-stats #49
- Compatibility with PHP 8.1

### Internal changes
- Small optimizations for InstantRunoff
- Static class for tie-breaking. Used by InstantRunoff but can be useful for further uses.

### Dev changes
- CondorcetDocumentator now using PHP8 attribtues.

## [v3.0.2] - 2021-04-28

### Changed
- Fix typos in command line options
- Add command line option: --show-method-stats

## [v3.0.1] - 2020-12-08

### Changed
- Bugfix Issue #32

## [v3.0.0] - 2020-12-01
### Description
Use latest PHP 8.0 functionality and improvements.

### Added
- Documentation tools code is now included in this main repository instead of composer dependency. (and it's completely refactored)
- Most of the documentation use PHP8 Attributes instead of a big Yaml base file.

### Changed
- Refactoring Kemeny–Young code and cache system.
- PHP8 syntax and functionality.

## [v2.2.1] - 2020-10-17
### Changed
- Timer functionality is now deactivated by default for performance reason. Can be reactivated manually: ```php Condorcet:$UseTimer = true;```
- Code cleanup and update for PHP 8.0

## [v2.2.0] - 2020-05-18
### Description
Include the new vote method "Two-round voting System", and some minors fix and optimizations.

### Added
- Two-round voting system. Useful to compare Condorcet with traditional lections.
- New Majority_Core class is the parent class for First-Past-The-Post and two-round system. It can easily be extended with variant parameters or code for creating easily many new majority voting methods (number of rounds, number of candidates retained after each round...).

### Changed
- Complete rewrite of First-Past-The-Post code. No user changes.
- Provides the right acronyms for First-Past-The-Post voting method.

### Internal changes
- Developments dependency upgrades.

## [v2.1.0] - 2019-12-29
### Description
Move to PHP 7.4 version. And add a console application for command line usage!

### Added
- New Condorcet Application from the command line. Easy to use for non-technical users. And faster to use for everyone for most use cases.
- Docker file for building Condorcet image. For very easy access to the command line application.
- Official compiled PHAR files are now available for each version on the release page.
- Json input can include vote weight.
- New method ```Election::parseVotesWithoutFail()``` allowing to ignore the bad vote, and useful for low and constant memory usage.

### Changed
- PHP 7.4 is the new minimal PHP version required.
- MBSTRING PHP extension is not requiered anymore.
- ```Election::allowVoteWeight()``` renamed to Election::allowsVoteWeight()
- ```Vote::getSimpleRanking()``` allow a new optional parameter $displayWeight.
- ```Vote::getWeight()``` allow a new optional parameter $context and if providing returning ranking in context of an election instead theorical ranking.

### Internal changes
- Use PHP 7.4 new types on property. For security and performance reasons.

## [v2.0.1] - 2019-12-29
### Description
The 2.0 branch is now legacy.

### Changed
- Harmonize parseVotes & parseJson
- Use Github Actions instead of Travis CI.

## [v2.0.0] - 2019-09-07
### Description
Small but many API changes (renaming, rationalization), sometimes on main methods. Very important internal optimizations. Also, use a new namespace!

### Added
- Very significant efforts about the documentation. Still in progress.
- Add two distincts methods Election::getCondorcetWinner() & Election::getCondorcetLoser(). the result are strictly equivalent to ```Election::getWinner(null)``` & ```Election::getLoser(null)``` but it's more explicit. And it is consistent with the homonymous methods of the result object.

### Changed
- Use new vendor namespace ```\CondorcetPHP``` instead ```\Condorcet``` because we don't have the Github Condorcet ID for this last one. The new base namespace is ```\CondorcetPHP\Condorcet\```
- Adding votes or candidates from Json or string parsing, will not check if all of them are valid. And only if all are valid, then they are registered. Previously, an exception was sent to the first error, but the status remained partially recorded.
- Many methods have been renamed or divided for greater consistency and intelligibility. Some parameters may have changed or used a more strict type.
- CondorcertException class moves to a new namespace ```Throwable\CondorcetException```. Condorcet can now also throw Trowable\CondorcetInternalError error.
- Some change on "Schulze Ratio" method computation, can affect the result of some type of election. It's still imperfect due to a case that the theory doesn't take into account. However, very small or very typical elections will have more logical results. The more common Winning & Margin methods are not affected.
- Many other various fixes.
- New Condorcet logo.

### Removed
- Removes the slightly twisted method Election::ignoreMaxVote

### Performance
- Adding, changing or removing vote after requesting the firsts Result, will prevent recomputing all the pairwise and prefer to update it. Can be a small optimization for most use case, or a very huge performances improvements if you manage a very large number of votes or use an external data handler for storing them.
- Parse Vote (text or Json) is faster, save huge memory consumption on big input if you use the if use the multiplication symbol for equal votes. And memory is now predictable, linear, and as smart as other input vote methods.
- Overall, significant savings in memory usage.
- Many other performance optimizations.

### Internal changes
- Voting Method extending PairwiseStatsBased_Core must use static constant COUNT_TYPE instead of $\_countType property.
- Add many tests.
- Add benchmarks with phpbench.

## [v1.8.2] - 2019-02-16
### Description
Allow development environment for PHP 7.4.

### Changed
- Use PHP UNIT 8.x (compatible up to PHP 7.4) for PHP version >= 7.2.
- Compatibility with PHP Unit 8.x serie

## [v1.8.1] - 2019-01-05
### Description
Tests for PHP 7.3 and prevent PHP >=7.4 deprecations.

### Added
- Tests for PHP 7.3
- Prevent PHP 7.4 deprecations.

## [v1.8.0] - 2018-07-22
### Description
You can add a custom constraint to force votes to meet certain criteria.
Constraints are designed as free modules (external class to load into each election). A first module is officially proposed (disabled by default): Preventing votes from including ties.

### Added
- Vote constraints functionality
- First official vote constraint module: Disallow vote tie on any rank in the election context.
- Tested with PHP 7.3.
- Always better code!

## [v1.7.0] - 2018-05-26
### Description
Adds for the first time methods unrelated to Condorcet's criteria (Borda, Alternative Voting, FTPT).

### Added
- New voting method: Borda Count
- New voting method: Dowdall System (Nauru), a Borda Count variant.
- New voting method: Instant-runoff (also know as Alternative Voting or Preferential Voting)
- New voting method: First-past-the-post

### Changed
- Result object (iterator and his other methods) is now ordered by key from the first rank to the last rank. However, no method officially implemented before version 1.7 was affected by this theoretical problem. This never affected the correct rank allocation but could cause misinterpretation if carelessly repeated in a loop.

### Internal changes
- Code cleanup & test improvements

## [v1.6.0] - 2018-01-28
### Description
Publication centred on two axes:
Firstly, the improvement and finishing of the internal structure, often for the purpose of readability of the code and good practices. And an extension of the tests.
The second part concerns the continued improvement of the management of the very large elections.

### Added
- Add ```\CondorcetPHP\Election::getVotesListGenerator``` and ```\CondorcetPHP\DataManager\VotesManager::getVotesListGenerator``` methods. Same as getVotesList method, but output a PHP generator instead of full array. Useful only for working on vera large election with an external DataHandler.

### Changed
- ```\CondorcetPHP\Condorcet::format()``` method move to \CondorcetPHP\CondorcetUtil::format()
- ```\CondorcetPHP\CondorcetUtil::format()``` can no longer optionally produce a var_dump(). You must do it yourself.
- Remove ```\CondorcetPHP\Election::getVoteByKey(```) method
- Fix ```\CondorcetPHP\Election``` cloning issues
- Simply DataHandlerDriverInterface
- Optimize ```\CondorcetPHP\Election::countVotes``` and ```\CondorcetPHP\DataManager\VotesManager::countVotes``` methods performance and memory usage in case of using an external DataHandler.

### Internal changes
- Cut out some classes and functions into smaller sub-groups.
- New dev tools in a new dedicated folder (update documentation, generate Kemeny–Young cache)
- Documentation generator code move to a new repository
- Git force Unix style line-ending (This could cause unexpected behavior in some tests related to the export of votes in string output.)
- Various optimizations & bugfix
- Test coverage up to 95%
- Wonderful new files headers

## [v1.5.0] - 2018-01-08
### Description
This release focuses on the management of very large elections.
It more rigorously reviews the functioning of the DataHandler, which is an advanced way to manage a very large number of votes, which is more stable, mature and tested.
It adds, as an alternative and as a complement (both can be used in consort) the notion of the weight of a vote. This may be useful for elections in which voters are not equal. Or to emulate a big election (without too many possible combinations!) if you don't need to store the details of each vote at Condorcet level.

### Added
- It is now possible to add weight (integer >= 1) to each vote. If you enable this mode at the election level (deactivated by default) then the votes will be proportional to their weight when calculating all the algorithms. for example, if the weight is 2, then the vote will count double.
This is an alternative and complementary to adding multiple votes. Using this mode of operation can save you (for large elections) a high cost in RAM or the configuration/development of a DataHandler, which can be complex. However, if you need to keep the information of each elector at Condorcet level, this functionality will not satisfy you, it is useful if at this level the voting information is useless or if it makes no sense.
- Using a DataHandler to externalize vote data is now compatible with vote tags.

### Internal changes
- More mature and tested management of external DataHandler. Your custom drivers need to be updated.
- News tests
- Minors clean-up, changes & optimizations

## [v1.4.1] - 2017-12-21
### Changed
- Add some aliases for Ranked Pairs

## [v1.4.0] - 2017-12-11
### Description
Rewrite and extend old and experimental implementation with a new one. After a lot of reading and some exchanges with Nicolaus Tideman.

### Added
- Ranked Pairs method is no more experimental thanks to a completely new implementation. It has also been divided into two methods:  "Ranked Pairs Margin" which is favored by Nicolaus Tideman and Ranked Pairs Winning which is the variant most often mentioned in other documentation, including the English Wikipedia.
Regarding compatibility with the old version, "Ranked Pairs" or "RankedPairs" calls now use Ranked Pairs Margin. Note that the old implementation, although failing in other respects, used a Winning version. which in some very specific cases can change your results.
*[More information on Condorcet Wiki](/Docs/VotingMethods.md)*

- New method: [Election::getVotesListAsString](https://github.com/julien-boudry/Condorcet/blob/master/Documentation/Election%20Class/public%20Election--getVotesListAsString.md)
- New method: [Result::Election::getResultAsString](https://github.com/julien-boudry/Condorcet/blob/master/Documentation/Result%20Class/public%20Result--getResultAsString.md)

### Internal changes
- News tests
- Minors clean-up, changes & optimisations

## [v1.3.4] - 2017-11-29
### Fixed
- Potentially backward-incompatible change if you use  the rare and useless Dodgson method**
  Fix #19 (thanks to @janmotl) : Our implementation of Dodgson was wrong. Or rather: used Tideman's approximation without knowing it. This could potentially lead to false results in some cases on this method.
  Dodgson's original will not be implemented because of the work it would represent and strong performance limitations (number of candidates, number of votes).

  Instead, we kept "Tideman approximation" but renamed it. And we added another approximation: "Dodgson Quick". We recommend this second solution, being the best of both, even if the two may be right in different cases or wrong together.
  More explanations here: https://www.maa.org/sites/default/files/pdf/cmj_ftp/CMJ/September%202010/3%20Articles/6%2009-229%20Ratliff/Dodgson_CMJ_Final.pdf

  No longer any method use the call "Dodgson", please look at the [documentation](/Docs/VotingMethods.md) to know which calls to use for either one.

## [v1.3.3] - 2017-10-04
### Fixed
- Critical bugfix: Result cache was frozen after serializing/unserializing an Election object. You can register or remove votes, but each result eventually computed before serializing stays frozen and potentially false.
If you do not store the Election object by serializing it, you are not affected.

## [v1.3.2] - 2017-09-24
### Fixed
- Minor bugfix release. Fix PHP errors on very small elections (Ranked Pairs / Minimax).

## [v1.3.1] - 2017-09-18
### Fixed
- Bugfix : ```Vote::getSimpleVote()```
- ```Vote::getContextualVote()``` is renamed ```Vote::getContextualRanking()```

## [v1.3.0] - 2017-09-17
### Description
Optional management of a new mode: if a vote does not specify the last rank. Then there is no last rank. This can significantly change the outcome of an election.
Previously (and still by default), if the last rank was not specified, it was automatically deducted.
This functionality is managed at the level of an election, it is possible to switch from one to the other without affecting the votes. The latter may even participate simultaneously in several elections using different modes.

Also add Dodgson method.

### Added
- Adds [Dodgson method](https://en.wikipedia.org/wiki/Dodgson%27s_method)
- Previously, if within a ranking, you do not specify all candidates participating in an election. It was considered that you placed the missing ones implicitly on the last rank.
This is always the default behavior. Alternative behavior is now added so that the missing candidates are ignored when calculating the results, which can significantly change the results. Please refer to the documentation.
- Add Kemeny-Young 'bestScore' information on Result::getStats()

### Changed
- If you vote using Candidate object. They will no longer be converted into another Candidate object with the same name if this one exists into the target election. They will be two different candidates... with the same name. It's more strict and prevents error in this strange case.
However, it can strongly be advised to not to mix different candidates with the same name, so that you do not mislead yourself! However, Condorcet now manages this case correctly.
If you vote by string or Json input : Nothing changes. Either a new candidate will be created or they will be converted with the candidate object with the same name in the first election of the voting object in which they will participate.
- Improved Condorcet::format()

### Fixed
- Fix Result::getWinner && Result::getLoser()
* Vote:getContextualVote() return stricter rank array key, from 1 to n.

### Internals changes
- Adds [PHPUnit](https://phpunit.de/) with many tests in particular to monitor the results.
- Refactoring Copeland and Minimax code. With the new Dodgson method, they now all using the same lightweight code based on the improved PairwiseStats class.
- Many code refactoring.

## [v1.2.3] - 2017-09-03
### Changed
- The method ```Candidate::getName``` no longer takes an argument.

### Fixed
- More strict array key on ```Vote::getContextualVote``` method
- Various bugfix
- First PHP Unit tests

## [v1.2.2] - 2017-08-31
### Fixed
- Bugfix & Support for PHP 7.2
- Bugfix for KemenyYoung conflict detection API

## [v1.2.1] - 2017-07-28
### Fixed
- Bugfix Version ( thanks @hboomsma : https://github.com/julien-boudry/Condorcet/issues/15 )

## [v1.2.0] - 2016-12-11
### Changed
- Use the news PHP 7.1 syntax. PHP 7.1 is now require for 1.2.x branch and above.

## [v1.1.0] - 2016-09-11
### Added
- New Result class. The results are no longer provided as array but as a result object. Coming with many methods (get it as an array, check metadata, get others results from infos at the generation time...). It implements Iterator / Countable / ArrayAccess for excellent backward compatibility.
- Kemeny-Young paradox is now run more cleanly thanks to the new result object.

## Changed
- Using PHP new syntax and optimization. PHP 7.0 >= is now required for 1.1 branch.
- Improve multi-elections contexts. Now works in a manner consistent with what you want to do (and what yet allowed to imply the documentation).
- Efforts on documentation (wiki), and wide rewriting examples.
- Many internals refactoring and bugfix.


## [v1.0.0] - 2016-06-05
### Added
- **Experimental support for very large election**, it comes with critical internal changes. A functional driver for PDO is provided to be used with relational SQL databases, you will probably need to extend it or configure it (database structure...).      <br>
  The modular structure allows you to develop your own implementation to get outsourced datastore, for example, we can imagine a NoSQL database driver.     <br>
  Benchmark shows that on PHP 7 + SQLite, 200 000 votes can be registered and computed in less than 60 seconds on a little server, with ~60mb RAM use. However, the speed of the driver does not change much the performance. From a certain point: slowdowns are intrinsically linked to the internal processing side Condorcet engine. Major optimizations for speed can easily be done for further releases, but this would require a trade-off between speed and code complexity.     <br>
  _If you are interested by this feature, please have a look to the documentation. Consider that the functionality come in BETA stage._
- New method Vote::getSimpleRanking -> Provide vote ranking as a string in one line. (Ex: 'A>B=D>C')
- New CondorcetPHP\Algo\Tools\VirtualVote:: removeCandidates(Condorcet/Vote $vote, array $candidateToRemove) static method clone your vote and return this clone without specified candidates.

### Changed
- Requirement: PHP 5.6 is the new minimal PHP version. Full official support for PHP7 is now provided (and include some bug fixes).
- Internal Change: Many Cleans & Improvements

## [v0.97.0] - 2015-09-05
### Changed
- Internal change, for future management ability of billions of votes.
- Timer manager knows give an historic with CondorcetPHP\Timer\Manager->getHistory();

## [v0.96.0] - 2015-08-22
### Changed
- Condorcet autoloader move on Condorcet/__CondorcetAutoload.php folder, outside lib folder. If you don't have your owns PSR-4 autoloader, you must now explicitly include this file,
- Because of dramatically random performance beyond 7 Candidates, our flawed implementation of RankedPair method is now by default limited to 7 Candiates.
  More than ever, the implementation of RankedPair should be considered experimental; it's really the only method giving me serious problems.
- Some minor internal changes.

## [v0.95.1] - 2015-08-15
### Fixed
- Bugfix version

## [v0.95.0] - 2015-08-15
### Changed
The new class Election replaces Condorcet class and retains most of its methods, static or not.
The class Condorcet survives. And now takes care of core configuration, such as recording modules.

So you must now create an object Election instead of Condorcet. What is more explicit. The remaining methods related to class Condorcet are in the documentation and examples.
As the class keeps the Condorcet high static method (although specialized), code modification on your part will be very minor and generally involve changing the "new Condorcet" instruction to "new Election".

- Condorcet::getClassVersion is renamed Condorcet::getVersion

## [v0.94.0] - 2015-08-14
### Added
- Method name now have alias.
  So _Condorcet::getResult('Schulze')_ is now strictly equivalent to _Condorcet::getResult('Schulze Winning')_ or _Condorcet::getResult('Schulze_Winning')_ or the class namespace CondorcetPHP::getResult('CondorcetPHP\Algo\Methods\SchulzeWinning').

### Changed
- Condorcet:addAlgos is renamed to Condorcet::addMethod, it's more logic. Argument is now a fully-qualified class name. This class can now be outside of \Condocet namespace. Adding your own algorithm is now much cleaner.
- Condorcet::getPairwise(false) now return the new pairwise object instead of an abstract array. Condorcet::getPairwise(true) is unchanged and equivalent to Pairwise::getExplicitPairwise.
  The new pairwise object implement \Iterastor and \ArrayAccess interfaces, so change may be transparent for you.
- PSR-0 autoloader support is removed. Instead, Condorcet is compliant with any PSR-4 autoloader, and it is now the only way to use it with Composer.
  If you don't have PSR-4 autoloader or you don't want to use Composer, you can continue to just include lib\Condorcet.php, its use now his own fallback PSR-4 implementation instead of his old PSR-0 implementation.
  Be careful, path to Condorcet.php change cause of PSR-4. It's now lib/Condorcet.php and not the old lib/Condorcet/Condorcet.php old path.

# Internal changes
- Reorganization about namespacing.
- Methods are now loaded by the normal and common autoloader.
- Pairwise is now an independent object, distinct from Condorcet object.
- Timer functionality now works outside of Condorcet class. With Timer\Manager and Timer\Chrono class. More improvement coming later (full log for benchmarking). You can get the timer manager by Condorcet::getTimerManager, but then know what you do!
- And other little things and optimization (deleting code deduplication, new constants instead of hardcoding...).

## [v0.93.0] - 2015-08-02
### Changed
- Minor internals optimizations.
- Some coding conventions change (can affect many lines).

### Fixed
- If you try to get Kemeny-Young 9 candidates on a Stable branch, Kemeny-Young will not try to store generic cache data on disk. This usage (although not recommended in all cases) is slower than before. but does not attempt to do something that should not be possible.

## [v0.92.0] - 2015-07-26
### Changed
- Deleting concept of Condorcet object default algorithm, and Class force algorithm. Now, there is only a Class Default method.
  _Now the default method is managed as static. It is no longer possible to force a method. Any management of the default object-level method is deleted
  This results in a simple boost, and therefore easier to understand documentation without gadgets methods.
  A slight speed increase can also be observed, the code being rid of a complex management resulting in many internal calls._
- Support PSR-4 autoloading with composer.
- New implementation of PSR-0 autloader. _(Automatically used if you do not go through one provided by Composer or framework or other valid autoloaders. It acts only fallback of last resort)_.
- Some internal code cleanup (organizational change with sub-namespace, moving or rename some methods or class, better Schulze Family strategy).

## [v0.91.0] - 2015-03-28
### Changed
- Condorcet now use more usual PSR-0 class loading philosophy. And each Class and Interface has now her owns PHP file according to PSR-0 specifications.
  - As result, the new architecture from Condorcet 0.90 is now fully compatible with framework (composer autoloader), is case of you would to play with Candidate Class (for example) before creating first an election by Condorcet Class. It's was a serious issue from Condorcet 0.90.
  - You can too continue to include /lib/Condorcet/Condorcet.php file as loader, if there is no others compatible autoloader, Condorcet will now use a new and special PSR-0 like autoloader for himself.
- Documentation files now use filename compatible with Windows filesystem (thanks Bill ^^)
- Minor and unnecessary coding style change.

## [v0.90.0] - 2015-02-14
### Description
New internal architecture. very important code refactoring, often completely rewritten. Relatively new API.

### Added
- Algorithms are now more isolated than Condorcet  activities, they use them by their own API.

#### Vote & Candidate are now objects!
- Candidate and Vote are now objects. You can continue to provide string, but all will be converted into object and the return values of most methods will favor this new philosophy.
- Vote and candidate are independent objects. They can participate in various elections simultaneously. They have their own lives and historical (name change, change of vote, elections to which they are taking or no longer taking part ...).
  They can be cloned, serialized, analyzed...
- Like Candidate object, a Vote object can take part into multiple elections. He can change its ranking and it will automatically affect all its elections. You can also provide top ranking, and its election can have other candidates. Condorcet will intelligently reconstruct a context for each election even if they do not have the same list of candidates!
- Off course, you can extend them !

#### Kemeny-Young improvements
- Code review.
- New Permutation class. 9 maximum candidates instead of 6! So, for performance reasons, I suggest to stay at 8.
  Thanks to Jorge Gomes (@cyberkurumin) for his helpful commit!

#### Documentation

Documentation more consistent with the new size of the library has been established. It is not perfect yet, but the documentation work continues day after day to make up for the delay.

However, foundations and most contents are available now.
- New examples of codes meeting the latest revolutions.
- A new manual, in the form of a Wiki Github.
- Complete specifications for each of the public methods. Into the _doc_ directory.

### Changed
- **Condorcet::format** static method is a substitute to var_dump to print easily better human readable Condorcet data (Vote, Résult). It can also be used to return (and not print) more simple dataset.
- Many new methods or API change. But structure stay similar, and old simple scripts can continue to work without modifications or really minor changes.

## [v0.14.0] - 2014-08-10
### Description
The code will be very severely rewritten and restructured for the next major release.

### Added
- Added UNIX timestamp of the record of each vote time as a special tag.
- Ability to perform cryptographic checksum (SHA-2 256) the status of an election (candidates, votes, cache, library version).
- New static method setMaxVoteNumber, allow you to limit the number of votes in a election. And public method to ignore it (or not) for each object.
- Improvements and bugfixes around object serialization.
- New Options for getClassVersion method

### Changed
- Compatibility is now guaranteed from PHP 5.5.12 to PHP 5.6.x. But the vital methods seem functional with PHP (>=) 5.4.3
- Algorithm Kemeny-Young V2: more than 1000 times faster with a cache of pre-computed data. 6 candidates on an election is now very fast, and it is the new provisional limit.
  Next Condorcet version will allow more candidates for Kemeny-Young (7 or 8), with more pre-computed sets.
- Customized limitation of maximum candidate for Kemeny-Young is removed.
- Works around the presentation of the single primary tag of each vote

### Fixed
- Improvements and bugfixes around object serialization.
- Many bugfixes and minor internal adjustments. Mostly to satisfy the development of condorcet-vote.org, which uses the library as a real framework of election management.

## [v0.13.2] - 2014-07-29
### Fixed
- Bugfix on getVotesList() and all tag filter methods

## [v0.13.0] - 2014-07-06
### Added
- New logo by @Christelle-Radena
- Add getLastTimer() and getGlobalTimer() methods

### Changed
- isJson is now a static method, useful for Condorcet API project
- Add an exception handler into the examples

### Fixed
- Some minors bugfix about CondorcetException class

## [v0.12.0] - 2014-07-02
### Added
- Votes and candidates can now be defined by a json input. _( jsonVotes(), jsonCandidates() )_
- Candidates can now be defined by a text input _( string or file with parseCandidates() )_
- The input text or json can now take a parameter of anti-flood safety generating an exception, providing you foresee yourself.

### Changed
- (Git) The static method ::getClassVersion() returns 'DEV' entitle on developments branches.
- Candidate name are now trim()
- The old system of errors reporting is deleted.
- The class now throws exceptions of class 'CondorcetException'

### Fixed
- Various bugfix

## [v0.11.1] - 2014-07-01
### Fixed
- Minor bugfix for getMethod() & setMethod() methods.

## [v0.11.0] - 2014-06-14
### Added
- Added the ability to include a multitude of votes votes simultaneously from a text or a single wide string file.
- Adding an adjustable anti-flood on the previous method.

- More flexibility to register or claim tags. Use an array or a string separated by commas.
- The countVote() method can now act on specific tags.
- The getVoteList() method can now be used more accurate and extensive.
- The removeVotes() method can now be used more accurate and extensive.
- The getResult() method can now be used to gain a profit on a partial selection of the votes (using tags) without requiring the prior removal of votes.
- Added more specific error message on addVote()

### Changed
- Improved documentation
- Various optimizations

## [v0.10.1] - 2014-05-17
### Fixed
- Bugfix for Schulze_Ratio (division by 0)

## [v0.10.0] - 2014-05-13
### Added
- New variants for the method of Schulze
- Experimental Implementation of the Ranked Pairs method

### Changed
- Audited successfully Schulze with the example of Martin Schulze itself
- Various optimizations

## [v0.9.0] - 2014-05-01
### Added
- Ability to add options to the algorithm with getResult ()
- KemenyYoung is now able to detect its conflicts and inform through the use of an option on getResult () (see the documentation)

### Changed
- Multiple bug fixes, some important (but never affecting the results).
- Code harmonization
- On the benefit of public methods provide a return value with the meaning and utility.
- Isolation of static methods having intended to be used by algorithms to avoid duplication of one another.
- Very complete (but not 100% exhaustive) examples of uses are kindly provided.
- Global code review by the author.

## [v0.8.0] - 2014-04-24
### Added
- New algorithm: Kemeny-Young (http://en.wikipedia.org/wiki/Kemeny-Young_method)

### Changed
- Many internal improvements, code cleanup and improve modularity rules
- Candidates can now have long names (30 characters)

## [v0.7.0] - 2014-04-18
### Added
- Support for serialize and unserialize object.
  - Optimization of data to keep back.
  - Checking the version of the data to import.
  - WARNING : Resultat will be recalculated from voting data.

### Changed
- Complete harmonization of attribute names and methods. Compatibility with old API is completely broken on the form, but the operation remains exactly the same.
- Many code cleanup and optimizations

## [v0.6.0] - 2014-04-14
### Added
- Add new Condorcet algorithm: Minimax in its three variants (Winning, Margin, Opposition | The last one is not  Condorcet criterion compliant)
  http://en.wikipedia.org/wiki/Minimax_Condorcet
- Add the ability to record a new vote by using a format string like "A>B=C>D" rather than the use of an array, read the doc!

### Fixed
- Some bugfix & optimizations

## [v0.5.1] - 2014-04-13
### Fixed
- Bugfix about registering new algorihms by an array

## [v0.5.0] - 2014-04-13
### Added
- Support Composer

### Changed
- Important structural changes to meet the standard PSR-0, PSR-1 and PSR-2 (partial).

## [v0.4.0] - 2014-04-06
### Description
First version ready for production, with many API improvements and news features. And the new Condorcet Copeland method.

## [v0.3.0] - 2014-03-16
### Description
Considerable structural changes and redesign many parts of API.
The class can now support the easy addition of new algorithms.

The next release will be devoted to validate the apparent stability of this version 0.3 and enrich the API for more flexibility. And perhaps the arrival of a new algorithm.

## [v0.2.0] - 2014-03-12
### Description
Second release! Not really ready for production, please test it!

## [v0.1.0] - 2014-03-09
### Description
First release! Not really ready for production, please test it !
