> **[Presentation](README.md) | [Documentation Book](https://www.condorcet.io) | [API References](Docs/ApiReferences/README.md) | Voting Method | [Tests](https://github.com/julien-boudry/Condorcet/tree/master/Tests)**

Voting Methods (natively implemented)
===========================
> **[Implementation philosophy](#implementation-philophy)**

# Natively implemented methods
*The modular architecture allows you to import new methods as external classes. These are preloaded into the distribution.*

## Single winner methods
_Designed for electing a single winner. But return a full ranking._

* **Condorcet Basic** Give you the natural winner or loser of Condorcet if there is one.
* **Borda count**
    * **[Borda System](#borda-count)**
    * **[Dowdall system (Nauru)](#dowdall-system-nauru)**
* **[Copeland](#copeland)**
* **Dodgson Approximations**
    * **[Dodgson Quick](#dodgson-quick)**
    * **[Dodgson Tideman approximation](#dodgson-tideman-approximation)**
* **[Instant-runoff](#instant-runoff-alternative-vote)** *(Alternative Vote / Preferential Voting)*
* **[Kemeny–Young](#kemenyyoung)**
* **Majority Family**
    * **[First-past-the-post](#first-past-the-post)**
    * **[Multiple Rounds system](#multiple-rounds-system)**
* **Minimax Family**
    * **[Minimax Winning](#minimax-winning)**
    * **[Minimax Margin](#minimax-margin)**
    * **[Minimax Opposition](#minimax-opposition)**
* **Lotteries Family**
    * **[Random Ballot](#random-ballot)**
    * **[Random Candidates](#random-candidates)**
* **Ranked Pairs Family** *(Tideman method)*
    * **[Ranked Pairs Margin](#ranked-pairs-margin)**
    * **[Ranked Pairs Winning](#ranked-pairs-winning)**
* **Schulze Method**
    * **[Schulze Winning](#schulze-winning)** *(recommended)*
    * **[Schulze Margin](#schulze-margin)**
    * **[Schulze Ratio](#schulze-ratio)**

## Proportional methods
_Designed for electing an assembly. Return a ranking of elected candidates._

* **STV Family**
    * **[Single Transferable Vote](#single-transferable-vote)** *(The classical STV method)*
    * **[CPO-STV](#cpo-stv)** *Comparison of Pairs of Outcomes by the Single Transferable Vote, by Nicolaus Tideman*
* **Highest Average / Largest Remainder Family**
    * **[Highest Average: Sainte-Laguë](#sainte-laguë--webster-method)** *Include Variants like Norway ou Sweden methods*
    * **[Highest Average: Thomas Jefferson / D'Hondt](#jefferson--dhondt-method)**
    * **[Largest Remainder: Hare-LR / Droop-LR / Imperiali-LR / Hagenbach-Bischoff-LR](#hare-lr--droop-lr--imperiali-lr--hagenbach-bischoff-lr)**



---------------------------------------

# Implementation Philophy

### Result tie-breaking<!-- {docsify-ignore} -->
Unless explicitly stated otherwise in the details below, no tie-breaking is added to methods, we kept them pure.
The results are therefore likely to contain ties in some ranks. Which according to the algorithms is more or less frequent, but always tends to become less likely in proportion to the size of the election.

### Tie into a vote rank<!-- {docsify-ignore} -->
Unless you have prohibited ties yourself or via a filter (CondorcetPHP >= 1.8), the votes are therefore likely to contain ties on certain ranks. In principle, this does not particularly disturb Condorcet's methods, since they are based on the Pairwise.
This is more annoying for other methods like Borda, Instant-runoff or Ftpt. These methods being based on the rank assigned. How each handles these cases is specified below. Keep in mind that it can vary depending on the implementations. Some choices had to be made for each of them.

### Implicit vs Explicit Ranking<!-- {docsify-ignore} -->
Please read the manual [about explicit and implicit ranking](https://www.condorcet.io/#/3.AsPhpLibrary/6.Results/4.ImplicitOrExplicitMod) modes.
In terms of implementation, what you have to understand is that algorithms and pairwise are blind. And see votes in their implicit or explicit context, which can significantly change the results of some of them.

---------------------------------------

# Single Winner methods - Details & Implementation
## Condorcet Basic

> **Family:** Condorcet  
> **Variant used:** *None*  
> **Wikipedia:** https://en.wikipedia.org/wiki/Condorcet_method  

### Implementation Comments<!-- {docsify-ignore} -->
*None*

```php
// Will return the strict natural Condorcet Winner candidate. Or Null if there is not.
$election->getCondorcetWinner() ;
// Will return the strict natural Condorcet Loser candidate. Or Null if there is not.
$election->getCondorcetLoser() ;
```


## Borda Count

> **Family:** Borda Count  
> **Variant used:** *Starting at 1*  
> **Wikipedia:** https://en.wikipedia.org/wiki/Borda_count  
> ***  
> **Methods alias available (for function call)**: "BordaCount","Borda Count","Borda","Méthode Borda"  

### Implementation Comments<!-- {docsify-ignore} -->
By default the option is to start the count at n - 1. You can change it with BordaCount::setOption(), see below.

In case of tie into a vote rank, follow this example:
```
A>B=C=D=E>F
A: 6 points
B/C/D/E: (5+4+3+2) / 4 = 3.5 points each
F: 1 point
```

In case of explicit voting is disabled. Missing rank does not earn points, but the existing rank are not penalized.

### Code example<!-- {docsify-ignore} -->
```php
// Get Full Ranking
$election->getResult('BordaCount') ;

// Just get Winner or Loser
$election->getWinner('BordaCount') ;
$election->getLoser('BordaCount') ;

// Get Stats
$election->getResult('BordaCount')->getStats() ;

// Chante the staring point to n - 0
$election->setMethodOption('BordaCount', 'Starting', 0) ;
$election->getResult('BordaCount') ;
```


## Dowdall system (Nauru)

> **Family:** Borda Count  
> **Variant used:** *Dowdall System*  
> **Wikipedia:** https://en.wikipedia.org/wiki/Borda_count  
> ***  
> **Methods alias available (for function call)**: "DowdallSystem","Dowdall System","Nauru", "Borda Nauru"  

### Implementation Comments<!-- {docsify-ignore} -->
 *See comments on the original Borda method above.*

### Code example<!-- {docsify-ignore} -->
```php
// Get Full Ranking
$election->getResult('DowdallSystem') ;

// Just get Winner or Loser
$election->getWinner('DowdallSystem') ;
$election->getLoser('DowdallSystem') ;

// Get Stats
$election->getResult('DowdallSystem')->getStats() ;
```


## Copeland

> **Family:** Copeland method  
> **Variant used:** *None*  
> **Wikipedia:** http://en.wikipedia.org/wiki/Copeland%27s_method  
> ***  
> **Methods alias available (for function call)**: "Copeland"  

### Implementation Comments<!-- {docsify-ignore} -->
 *None*

### Code example<!-- {docsify-ignore} -->

```php
// Get Full Ranking
$election->getResult('Copeland') ;

// Just get Winner or Loser
$election->getWinner('Copeland') ;
$election->getLoser('Copeland') ;

// Get Stats
$election->getResult('Copeland')->getStats() ;
```


## Dodgson Quick

> **Family:** Dodgson method  
> **Variant used:** Approximation for Dodgson method called "Dodgson Quick" from https://www.maa.org/sites/default/files/pdf/cmj_ftp/CMJ/September%202010/3%20Articles/6%2009-229%20Ratliff/Dodgson_CMJ_Final.pdf  
> **Wikipedia:** https://en.wikipedia.org/wiki/Dodgson%27s_method  
> ***  
> **Methods alias available (for function call)**: "Dodgson Quick" / "DodgsonQuick" / "Dodgson Quick Winner"  

### Implementation Comments<!-- {docsify-ignore} -->
 *None*

### Code example<!-- {docsify-ignore} -->
```php
// Get Full Ranking
$election->getResult('Dodgson Quick') ;

// Just get Winner or Loser
$election->getWinner('Dodgson Quick') ;
$election->getLoser('Dodgson Quick') ;

// Get Stats
$election->getResult('Dodgson Quick')->getStats() ;
```


## Dodgson Tideman Approximation

> **Family:** Dodgson method  
> **Variant used:** Approximation for Dodgson method called "Tideman approximation" from _[Lewis  Carroll,  voting,  and  the  taxicab  metric](https://www.maa.org/sites/default/files/pdf/cmj_ftp/CMJ/September%202010/3%20Articles/6%2009-229%20Ratliff/Dodgson_CMJ_Final.pdf)_  
> **Wikipedia:** https://en.wikipedia.org/wiki/Dodgson%27s_method  
> ***  
> **Methods alias available (for function call)**: "Dodgson Tideman Approximation" / "DodgsonTidemanApproximation" / "Dodgson Tideman" / "DodgsonTideman"  

### Implementation Comments<!-- {docsify-ignore} -->
 *None*

### Code example<!-- {docsify-ignore} -->
```php
// Get Full Ranking
$election->getResult('Dodgson Tideman') ;

// Just get Winner or Loser
$election->getWinner('Dodgson Tideman') ;
$election->getLoser('Dodgson Tideman') ;

// Get Stats
$election->getResult('Dodgson Tideman')->getStats() ;
```


## Instant-runoff (Alternative Vote)

> **Family:** Instant-runoff  
> **Variant used:** *None*  
> **Wikipedia:** https://en.wikipedia.org/wiki/Instant-runoff_voting  
> ***  
> **Methods alias available (for function call)**: "Instant-runoff", "InstantRunoff", "IRV", "preferential voting", "ranked-choice voting", "alternative vote", "AlternativeVote", "transferable vote", "Vote alternatif"  

### Implementation Comments<!-- {docsify-ignore} -->
In case of tie into a vote rank, rank is ignored like he never existed.

An additional tie-breaking tentative is added in case of tie into the preliminary result set. First, comparing candidate pairwise, in a second attempt compare the total number of pairwise wins (global context), and in a third desperate attempt, compare the balance of their victory/defeat in a global Pairwise context.

### Code example<!-- {docsify-ignore} -->

```php
// Get Full Ranking
$election->getResult('Instant-runoff') ;

// Just get Winner or Loser
$election->getWinner('Instant-runoff') ;
$election->getLoser('Instant-runoff') ;

// Get Stats
$election->getResult('Instant-runoff')->getStats() ;
```


## Kemeny–Young

> **Family:** Kemeny–Young method  
> **Variant used:** *None*  
> **Wikipedia:** http://en.wikipedia.org/wiki/Kemeny-Young_method _Kemeny-Young  
> ***  
> **Methods alias available (for function call)**: "Kemeny–Young" / "Kemeny-Young" / "Kemeny Young" / "KemenyYoung" / "Kemeny rule" / "VoteFair popularity ranking" / "Maximum Likelihood Method" / "Median Relation"  

### Implementation Comments<!-- {docsify-ignore} -->
Kemeny-Young is currently limited up to 10 candidates. It is very fast up to 9. At 10, this should remain under 30 seconds of processing even under a very modest system. Beyond that, it is certainly playable at least up to 12, but with a much higher processing time, but a constantly low memory. But you must not ask for the `FULL` stats verbosity.

### Code example<!-- {docsify-ignore} -->
```php
// Get Full Ranking
$election->getResult('Kemeny-Young') ;

// Just get Winner or Loser
$election->getWinner('Kemeny-Young'') ;
$election->getLoser('Kemeny-Young') ;

// Get Stats
$election->getResult('Kemeny-Young')->getStats() ;

// Get all the stats (can slow down and use a lot of memory starting 9 candidates)
$election->->setStatsVerbosity(StatsVerbosity::FULL);
$election->getResult('Kemeny-Young')->getStats() ;
```


## First-past-the-post

> **Family:** Majority  
> **Variant used:** *See implementation comment*  
> **Wikipedia:** https://en.wikipedia.org/wiki/First-past-the-post_voting  
> ***  
> **Methods alias available (for function call)**: "First-past-the-post voting", "First-past-the-post", "First Choice", "FirstChoice", "FPTP", "FPP", "SMP"  

### Implementation Comments<!-- {docsify-ignore} -->
In case of tie into the first rank. All non-commissioned candidates earn points, but only a fraction. But not 1 point, the result of this computation: 1/(candidate-in-rank).

For example: ```A = B > C```
A/B earn each 0.5 points

### Code example<!-- {docsify-ignore} -->
```php
// Get Full Ranking
$election->getResult('FPTP') ;

// Just get Winner or Loser
$election->getWinner('FPTP') ;
$election->getLoser('FPTP') ;

// Get Stats
$election->getResult('FPTP')->getStats() ;
```


## Multiple Rounds system

> **Family:** Majority  
> **Variant used:** *See implementation comment*  
> **Wikipedia:** https://en.wikipedia.org/wiki/Two-round_system  
> ***  
> **Methods alias available (for function call)**: "Multiple Rounds System", "MultipleRoundsSystem", "Multiple Rounds", "Majority", "Majority System", "Two-round system", "second ballot", "runoff voting", "ballotage", "two round system", "two round", "two rounds", "two rounds system", "runoff voting"  

### Implementation Comments<!-- {docsify-ignore} -->
In case of tie into the first rank. All non-commissioned candidates earn points, but only a fraction. But not 1 point, the result of this computation: 1/(candidate-in-rank).
For example: ```A = B > C```
A/B earn each 0.5 points

Method is trying to keep only two candidates for the next round. But that may be more in the event of a perfect tie.

### Code example<!-- {docsify-ignore} -->
```php
// Get Full Ranking
$election->getResult('Multiple Rounds System') ;

// Just get Winner or Loser
$election->getWinner('Multiple Rounds System') ;
$election->getLoser('Multiple Rounds System') ;

// Get Stats
$election->getResult('Multiple Rounds System')->getStats() ;
```


## Minimax Winning

> **Family:** Minimax method  
> **Variant used:** Winning *(Does not satisfy the Condorcet loser criterion)*  
> **Wikipedia:** https://en.wikipedia.org/wiki/Minimax_Condorcet  
> ***  
> **Methods alias available (for function call)**: "Minimax Winning" / "MinimaxWinning" / "Minimax" / "Minimax_Winning" / "Simpson" / "Simpson-Kramer" / "Simpson-Kramer Method" / "Simpson Method"  

### Implementation Comments<!-- {docsify-ignore} -->
 *None*

### Code example<!-- {docsify-ignore} -->
```php
// Get Full Ranking
$election->getResult('Minimax Winning') ;

// Just get Winner or Loser
$election->getWinner('Minimax Winning') ;
$election->getLoser('Minimax Winning') ;

// Get Stats
$election->getResult('Minimax Winning')->getStats() ;
```


## Minimax Margin

> **Family:** Minimax method  
> **Variant used:** Margin *(Does not satisfy the Condorcet loser criterion)*  
> **Wikipedia:** https://en.wikipedia.org/wiki/Minimax_Condorcet  
> ***  
> **Methods alias available (for function call)**: "Minimax Margin" / "MinimaxMargin" / "MinimaxMargin" / "Minimax_Margin"  

### Implementation Comments<!-- {docsify-ignore} -->
 *None*

### Code example<!-- {docsify-ignore} -->
```php
// Get Full Ranking
$election->getResult('Minimax Margin') ;

// Just get Winner or Loser
$election->getWinner('Minimax Margin') ;
$election->getLoser('Minimax Margin') ;

// Get Stats
$election->getResult('Minimax Margin')->getStats() ;
```


## Minimax Opposition

> **Family:** Minimax method  
> **Variant used:** Opposition *(By nature, this alternative does not meet any criterion of Condorcet)*  
> **Wikipedia:** https://en.wikipedia.org/wiki/Minimax_Condorcet  
> ***  
> **Methods alias available (for function call)**: "Minimax Opposition" / "MinimaxOpposition" / "Minimax_Opposition"  

### Implementation Comments<!-- {docsify-ignore} -->
 *None*

### Code example<!-- {docsify-ignore} -->
```php
// Get Full Ranking
$election->getResult('Minimax Opposition') ;

// Just get Winner or Loser
$election->getWinner('Minimax Opposition') ;
$election->getLoser('Minimax Opposition') ;

// Get Stats
$election->getResult('Minimax Opposition')->getStats() ;
```


## Random Ballot

> **Family:** Lotteries 
> **Variant used:** *None*  
**Wikipedia:** https://en.wikipedia.org/wiki/Random_ballot
> ***  
> **Methods alias available (for function call)**: Random Ballot / Single Stochastic Vote / Lottery Voting  

### Implementation Comments<!-- {docsify-ignore} -->
If vote weight is activated and used, the probabilities are calculated taking into account the weight of each ballot.  
By default, a cryptographic level generator is used to perform the random lottery. It is also possible to use an option in the method to obtain a reproducible lottery.

### Code example<!-- {docsify-ignore} -->
```php
// Get Full Ranking
$election->getResult('Random Ballot') ;

// Just get Winner or Loser
$election->getWinner('Random Ballot') ;
$election->getLoser('Random Ballot') ;

// Get Stats
$election->getResult('Random Ballot')->getStats() ;

// Use a custom randomizer engine
use Random\Engine\Xoshiro256StarStar;
use Random\Randomizer;

$election->setMethodOption(
    method: 'Random Ballot',
    optionName: 'Randomizer',
    optionValue: new Randomizer( new Xoshiro256StarStar( hash('sha256', 'My Seed)', true) ) )
);

$election->getResult('Random Ballot') ;
```


## Random Candidates

> **Family:** Lotteries 
> **Variant used:** *None*  
**Wikipedia:** https://en.wikipedia.org/wiki/Random_Candidates
> ***  
> **Methods alias available (for function call)**: Random Candidates / Single Stochastic Vote / Lottery Voting  

### Implementation Comments<!-- {docsify-ignore} -->
If vote weight is activated and used, the probabilities are calculated taking into account the weight of each Candidates.  
By default, a cryptographic level generator is used to perform the random lottery. It is also possible to use an option in the method to obtain a reproducible lottery.

### Code example<!-- {docsify-ignore} -->
```php
// Get Full Ranking
$election->getResult('Random Candidates') ;

// Just get Winner or Loser
$election->getWinner('Random Candidates') ;
$election->getLoser('Random Candidates') ;

// Get Stats
$election->getResult('Random Candidates')->getStats() ;

// Use a custom randomizer engine
use Random\Engine\Xoshiro256StarStar;
use Random\Randomizer;

$election->setMethodOption(
    method: 'Random Candidates',
    optionName: 'Randomizer',
    optionValue: new Randomizer( new Xoshiro256StarStar( hash('sha256', 'My Seed)', true) ) )
);

$election->getResult('Random Candidates') ;
```


## Ranked Pairs Margin

> **Family:** Ranked Pairs  
> **Variant used:** Margin *(Ranked Pairs Margin is used by Nicolaus Tideman himself from originals papers. But it's not necessarily the most common. Most other documentation preferring the Winning variant. Even Wikipedia is the different from one language to another.)*  
**Wikipedia:** https://en.wikipedia.org/wiki/Ranked_pairs  
> ***  
> **Methods alias available (for function call)**: "Ranked Pairs Margin" / "Tideman Margin" / "RP Margin" / "Ranked Pairs" / "RankedPairs" / "Tideman method"  

### Implementation Comments<!-- {docsify-ignore} -->
In the event of the impossibility of ordering a pair by their margin of victory. Try to separate them when possible by their smaller minority opposition.  
In case of a tie in the ranking result. No advanced methods are used. It is, therefore, an implementation following the first paper published in 1987. Markus Schulze advice a tie-breaking method, but it brings unnecessary complexity and is partly based on randomness. this method can, therefore, come out ties on some ranks. Even if that is very unlikely on an honest election of good size.

### Code example<!-- {docsify-ignore} -->
```php
// Get Full Ranking
$election->getResult('Ranked Pairs Margin') ;

// Just get Winner or Loser
$election->getWinner('Ranked Pairs Margin') ;
$election->getLoser('Ranked Pairs Margin') ;

// Get Stats
$election->getResult('Ranked Pairs Margin')->getStats() ;
```


## Ranked Pairs Winning

> **Family:** Ranked Pairs  
> **Variant used:** Winning  
> **Wikipedia:** https://en.wikipedia.org/wiki/Ranked_pairs  
> ***  
> **Methods alias available (for function call)**: "Ranked Pairs Winning" / "Tideman Winning" / "RP Winning"  

### Implementation Comments<!-- {docsify-ignore} -->
In the event of the impossibility of ordering a pair by their margin of victory. Try to separate them when possible by their smaller minority opposition.  
In case of a tie in the ranking result. No advanced methods are used. It is, therefore, an implementation following the first paper published in 1987. Markus Schulze advice a tie-breaking method, but it brings unnecessary complexity and is partly based on randomness. this method can, therefore, come out ties on some ranks. Even if that is very unlikely on an honest election of good size.  

### Code example<!-- {docsify-ignore} -->
```php
// Get Full Ranking
$election->getResult('Ranked Pairs Winning') ;

// Just get Winner or Loser
$election->getWinner('Ranked Pairs Winning') ;
$election->getLoser('Ranked Pairs Winning') ;

// Get Stats
$election->getResult('Ranked Pairs Winning')->getStats() ;
```


## Schulze Winning

> **Family:** Schulze method  
> **Variant used:** Winning *(Schulze Winning is recommended by Markus Schulze himself. This is the default choice. This variant is also known as Schulze Method.)*  
> **Wikipedia:** https://en.wikipedia.org/wiki/Schulze_method  
> ***  
> **Methods alias available (for function call)**: "Schulze Winning" / "Schulze" / "SchulzeWinning" / "Schulze_Winning" / "Schwartz Sequential Dropping" / "SSD" / "Cloneproof Schwartz Sequential Dropping" / "CSSD" / "Beatpath" / "Beatpath Method" / "Beatpath Winner" / "Path Voting" / "Path Winner"  

### Implementation Comments<!-- {docsify-ignore} -->
 *None*

### Code example<!-- {docsify-ignore} -->
```php
// Get Full Ranking
$election->getResult('Schulze') ;

// Just get Winner or Loser
$election->getWinner('Schulze') ;
$election->getLoser('Schulze') ;

// Get Stats
$election->getResult('Schulze')->getStats() ;
```


## Schulze Margin

> **Family:** Schulze method  
> **Variant used:** Margin  
> **Wikipedia:** https://en.wikipedia.org/wiki/Schulze_method  
> ***  
> **Methods alias available (for function call)**: "Schulze Margin" / "SchulzeMargin" / "Schulze_Margin"  

### Implementation Comments<!-- {docsify-ignore} -->
 *None*

### Code example<!-- {docsify-ignore} -->
```php
// Get Full Ranking
$election->getResult('Schulze Margin') ;

// Just get Winner or Loser
$election->getWinner('Schulze Margin') ;
$election->getLoser('Schulze Margin') ;

// Get Stats
$election->getResult('Schulze Margin')->getStats() ;
```


## Schulze Ratio

> **Family:** Schulze method  
> **Variant used:** Ratio  
> **Wikipedia:** https://en.wikipedia.org/wiki/Schulze_method  
> ***  
> **Methods alias available (for function call)**: "Schulze Ratio" / "SchulzeRatio" / "Schulze_Ratio"  

### Implementation Comments<!-- {docsify-ignore} -->
The original specification is incomplete. She says to compute the ratio as follow:  
```$candidateA_versus_CandidateB['pairwise_win'] / $candidateA_versus_CandidateB ['pairwise_lose'] = Ratio```  
We don't know how to manage division by zero when it's happened, which is very unlikely on large elections but can happen. Actually, but it can change to a better solution, we add 1 on left and right, only in this case.

### Code example<!-- {docsify-ignore} -->
```php
// Get Full Ranking
$election->getResult('Schulze Ratio') ;

// Just get Winner or Loser
$election->getWinner('Schulze Ratio') ;
$election->getLoser('Schulze Ratio') ;

// Get Stats
$election->getResult('Schulze Ratio')->getStats() ;
```


# Proportional Methods - Details & Implementation

## Single Transferable Vote (family)
### Single Transferable Vote

> **Family:** Single Transferable Vote  
> **Proportional type:** Individual _(A candidate can be elected only once (1 seat). Ranking will never return many time the same candidate.)_  
> **Default STV Quota:** Droop  
> **Variant used:** *None*  
> **Wikipedia:** https://en.wikipedia.org/wiki/Single_transferable_vote  
> ***  
> **Methods alias available (for function call)**: "STV" / "Single Transferable Vote" / "SingleTransferableVote"  

#### Implementation Comments<!-- {docsify-ignore} -->
###### Fundamentals
- In case of tie into a vote rank, rank is ignored like he never existed. It's recommended to use the native vote constraint `NoTie` if you are not sure of your inputs: `$election->addConstraint(NoTie::class)`.
- The implementation of this method does not support parties. A candidate is elected only once, whatever the number of seats.
- Non-elected candidates are not included in the ranking. The ranking is therefore that of the elected. But you can use ```getStats()``` to get all initial scores table and outcomes scores.

###### Quotas
Default quota is the Droop quota. Three others are available using the method options system _(see example below)_: Hare, Hagenbach-Bischoff, Imperiali.

#### Code example<!-- {docsify-ignore} -->

```php
use CondorcetPHP\Condorcet\Algo\Tools\StvQuotas;

// Change the number of seats
$election->setNumberOfSeats(42); # Default is 100

// Get the elected candidates with ranking
$election->getResult('STV');

// Check the number of seats
$election->getResult('STV')->getNumberOfSeats();

// Get Stats (votes needed to win, rounds detailsd)
$election->getResult('STV')->getStats();

// Change the Quota
$election->setMethodOption('STV', 'Quota', StvQuotas::HAGENBACH_BISCHOFF) ;
$election->getResult('STV') ;
$election->setMethodOption('STV', 'Quota', StvQuotas::IMPERIALI) ;
$election->getResult('STV') ;
$election->setMethodOption('STV', 'Quota', StvQuotas::HARE) ;
$election->getResult('STV') ;
$election->setMethodOption('STV', 'Quota', StvQuotas::DROOP) ;
$election->getResult('STV') ;
```


### CPO-STV

> **Family:** Single Transferable Vote  
> **Proportional type:** Individual _(A candidate can be elected only once (1 seat). Ranking will never return many time the same candidate.)_  
> **Default STV Quota:** Hagenbach-Bischoff  
> **Variant used:** *Completion method is Schulze Margin (default) then chaining different others methods if necessary*  
> **Wikipedia:** https://en.wikipedia.org/wiki/CPO-STV  
> ***  
> **Methods alias available (for function call)**: "CPO STV" / "CPO_STV" / "CPO-STV" / "CPO" / "Comparison of Pairs of Outcomes by the Single Transferable Vote" / "Tideman STV"  

#### Implementation Comments<!-- {docsify-ignore} -->
##### Fundamentals
- In case of tie into a vote rank, rank is ignored like he never existed. It's recommended to use the native vote constraint `NoTie` if you are not sure of your inputs: `$election->addConstraint(NoTie::class)`.
- The implementation of this method does not support parties. A candidate is elected only once, whatever the number of seats.
- Non-elected candidates are not included in the ranking. The ranking is therefore that of the elected. But you can use ```getStats()``` to get all initial scores table and outcomes scores.
- Ranking is sort by the initial score table, the winners with the same initial score are put back on the same rank in the same spirit of implementation as all other voting methods.

###### Quotas
Default quota is the Hagenbach-Bischoff. Three others are available using the method options system _(see example below)_: Droop, Hare, Imperiali.

###### Completion method
The best outcome is selected chaining methods in that order (first to deliver a single winner): ```SchulzeMargin → SchulzeWinning  → SchulzeRatio  → BordaCount  → Copeland  → InstantRunoff  → MinimaxMargin  → MinimaxWinning  → DodgsonTidemanApproximation  → FirstPastThePost```
If none of them can deliver a single winner, the first one (default: ```SchulzeMargin```) is used, and one winner is arbitrarily chosen from the first rank.  

This order can be changed using option system _(see example below)_.

###### Sorting score before electing
If more candidates than seats fill the quotas directly before outcome comparaison, then the ranking of elected candidates is ordered using the initial score table. If a tie persists, tie-breaker chaining concerning rank by chaining single-winner methods and comparing candidates. If this is not enough, use the alphabetical order.  
Methods used to do it are the following in that order: ```SchulzeMargin → SchulzeWinning  → SchulzeRatio  → BordaCount  → Copeland  → InstantRunoff  → MinimaxMargin  → MinimaxWinning  → DodgsonTidemanApproximation  → FirstPastThePost```  
This can be changed by passing an option to the method, with an ordered array populated by method names. _(see example below)_  
Ranked-Pairs or Kemeny-Young are not used by default, because they are slow (or in practice impossible) for elections with many candidates, performance for them are not polynomials.  


#### Code example<!-- {docsify-ignore} -->

```php
use CondorcetPHP\Condorcet\Algo\Tools\StvQuotas;

// Change the number of seats
$election->setNumberOfSeats(7); # Default is 100

// Get the elected candidates with ranking
$election->getResult('CPO-STV');

// Check the number of seats
$election->getResult('CPO-STV')->getNumberOfSeats();

// Get Stats (votes needed to win, initial table score, candidate directly elected, outcomes score....)
$election->getResult('CPO-STV')->getStats(); // Resulting array can be really fat

// Get all the stats (can slow down and use a lot of memory in some case, often related to the number of candidate to elect)
$election->->setStatsVerbosity(StatsVerbosity::FULL);
$election->getResult('CPO-STV')->getStats() ;

// Change the Quota
$election->setMethodOption('CPO-STV', 'Quota', StvQuotas::HAGENBACH_BISCHOFF) ;
$election->getResult('CPO-STV') ;
$election->setMethodOption('CPO-STV', 'Quota', StvQuotas::IMPERIALI) ;
$election->getResult('CPO-STV') ;
$election->setMethodOption('CPO-STV', 'Quota', StvQuotas::HARE) ;
$election->getResult('CPO-STV') ;
$election->setMethodOption('CPO-STV', 'Quota', StvQuotas::DROOP) ;
$election->getResult('CPO-STV') ;

// Change the completion selection method
$election->setMethodOption('CPO-STV', 'CondorcetCompletionMethod', [1=> 'Ranked Pairs', 2=> 'Kemeny-Young']) ; // Never use Ranked-Pairs or Kemeny-Young, they are too many outcomes to choose from, and their performances aren't polynomials.
$election->getResult('CPO-STV') ;

// Change the sort method
$election->setMethodOption('CPO-STV', 'TieBreakerMethods', [1=> 'Ranked Pairs', 2=> 'Kemeny-Young']) ;
$election->getResult('CPO-STV') ;

```

## Highest Averages Methods

### Sainte-Laguë / Webster method

> **Family:** Highest Averages Methods  
> **Proportional type:** Party _(The same candidate can appear several times in the results.)_  
> **Variant used:** *Standard, but variants can be created with the options system as explained*  
> **Wikipedia:** https://en.wikipedia.org/wiki/Webster/Sainte-Lagu%C3%AB_method  
> ***  
> **Methods alias available (for function call)**: "Sainte-Laguë", "SainteLague", "Webster", "Major Fractions Method"  

### Implementation Comments<!-- {docsify-ignore} -->
- Accepts votes including full rankings, but only the first place will be evaluated. It's recommended to use the native vote constraint `NoTie` if you are not sure of your inputs: `$election->addConstraint(NoTie::class)`.
- In case of tie (more than one candidate) into the first vote rank, vote is ignored like he never existed.
- In case of a quotient tie in a round, candidates are selected arbitrarily. Not a problem most of the time, because unselected candidates will be chosen in the next round, except if a tie occurs on the last available seat.


### Code example<!-- {docsify-ignore} -->

```php
// Change the number of seats
$election->setNumberOfSeats(7); # Default is 100

// Get the elected candidates with ranking
$election->getResult('Sainte-Laguë'); # Return the usual ranking. Same candidate can appears several times, so it's a parties method.

// Check the number of seats
$election->getResult('Sainte-Laguë')->getNumberOfSeats();

// Get Stats (sumup ranks)
$election->getResult('Sainte-Laguë')->getStats(); # Summarizes the number of seats. And details about each round.

// Use Norwegian Variant
$this->election->setMethodOption('SainteLague', 'FirstDivisor', 1.4);
$election->getResult('Sainte-Laguë');

// Get back to the normal version
$this->election->setMethodOption('SainteLague', 'FirstDivisor', 1);
```

### Jefferson / D'Hondt method

> **Family:** Highest Averages Methods  
> **Proportional type:** Party _(The same candidate can appear several times in the results.)_  
> **Variant used:** *-*  
> **Wikipedia:** https://en.wikipedia.org/wiki/D%27Hondt_method  
> ***  
> **Methods alias available (for function call)**: "Jefferson", "D'Hondt",  "Thomas Jefferson"  

### Implementation Comments<!-- {docsify-ignore} -->
- Accepts votes including full rankings, but only the first place will be evaluated. It's recommended to use the native vote constraint `NoTie` if you are not sure of your inputs: `$election->addConstraint(NoTie::class)`.
- In case of tie (more than one candidate) into the first vote rank, vote is ignored like he never existed.
- In case of a quotient tie in a round, candidates are selected arbitrarily. Not a problem most of the time, because unselected candidates will be chosen in the next round, except if a tie occurs on the last available seat.

### Code example<!-- {docsify-ignore} -->

```php
// Change the number of seats
$election->setNumberOfSeats(7); # Default is 100

// Get the elected candidates with ranking
$election->getResult('Jefferson'); # Return the usual ranking. Same candidate can appears several times, so it's a parties method.

// Check the number of seats
$election->getResult('Jefferson')->getNumberOfSeats();

// Get Stats (sumup ranks)
$election->getResult('Jefferson')->getStats(); # Summarizes the number of seats. And details about each round.
```


## Largest Remainder Methods

### Hare-LR / Droop-LR / Imperiali-LR / Hagenbach-Bischoff-LR

> **Family:** Highest Averages Methods  
> **Proportional type:** Party _(The same candidate can appear several times in the results.)_  
> **Also known as (when properly set):** Hare-LR / Droop-LR / Imperiali-LR / Hagenbach-Bischoff-LR  
> **Variant used:** *-*  
> **Default Quota:** Hare  
> **Wikipedia:** https://en.wikipedia.org/wiki/Largest_remainder_method  
> ***  
> **Methods alias available (for function call)**: "Largest Remainder", "LargestRemainder", "LR", "Hare–Niemeyer method", "Hamilton method", "Vinton's method"  

### Implementation Comments<!-- {docsify-ignore} -->
- Accepts votes including full rankings, but only the first place will be evaluated. It's recommended to use the native vote constraint `NoTie` if you are not sure of your inputs: `$election->addConstraint(NoTie::class)`.
- In case of tie (more than one candidate) into the first vote rank, vote is ignored like he never existed.
- In case of a quotient tie in a round, candidates are selected arbitrarily. Not a problem most of the time, because unselected candidates will be chosen in the next round, except if a tie occurs on the last available seat.

### Code example<!-- {docsify-ignore} -->

```php
// Change the number of seats
$election->setNumberOfSeats(7); # Default is 100

// Get the elected candidates with ranking
$election->getResult('Largest Remainder'); # Return the usual ranking. Same candidate can appears several times, so it's a parties method.

// Check the number of seats
$election->getResult('Largest Remainder')->getNumberOfSeats();

// Get Stats (sumup ranks)
$election->getResult('Largest Remainder')->getStats(); # Summarizes the number of seats. And details about each round.

// Change the Quota
$election->setMethodOption('Largest Remainder', 'Quota', StvQuotas::HAGENBACH_BISCHOFF) ;
$election->getResult('Largest Remainder') ;
$election->setMethodOption('Largest Remainder', 'Quota', StvQuotas::IMPERIALI) ;
$election->getResult('Largest Remainder') ;
$election->setMethodOption('Largest Remainder', 'Quota', StvQuotas::HARE) ;
$election->getResult('Largest Remainder') ;
$election->setMethodOption('Largest Remainder', 'Quota', StvQuotas::DROOP) ;
$election->getResult('Largest Remainder') ;
```
