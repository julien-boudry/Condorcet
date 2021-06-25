> **[Presentation](../README.md) | [Manual](https://github.com/julien-boudry/Condorcet/wiki) | Methods References | [Tests](../Tests)**

# Public API Index _(Not yet exhaustive, not yet....)*_
_Not including technical public methods which ones are used for very advanced use between components (typically if you extend Coondorcet or build your own modules)._

_*: I try to update and complete the documentation. See also [the manual](https://github.com/julien-boudry/Condorcet/wiki), [the tests](../Tests) also produce many examples. And create issues for questions or fixing documentation!_


### CondorcetPHP\Condorcet\Algo\Pairwise Class  

* [public Algo\Pairwise->getExplicitPairwise ()](Algo_Pairwise%20Class/public%20Algo_Pairwise--getExplicitPairwise.md) : array  
* [public Algo\Pairwise->getObjectVersion (...)](Algo_Pairwise%20Class/public%20Algo_Pairwise--getObjectVersion.md) : string  

### CondorcetPHP\Condorcet\Candidate Class  

* [public Candidate->__construct (...)](Candidate%20Class/public%20Candidate--__construct.md)  
* [public Candidate->countLinks ()](Candidate%20Class/public%20Candidate--countLinks.md) : int  
* [public Candidate->getCreateTimestamp ()](Candidate%20Class/public%20Candidate--getCreateTimestamp.md) : float  
* [public Candidate->getHistory ()](Candidate%20Class/public%20Candidate--getHistory.md) : array  
* [public Candidate->getLinks ()](Candidate%20Class/public%20Candidate--getLinks.md) : ?array  
* [public Candidate->getName ()](Candidate%20Class/public%20Candidate--getName.md) : string  
* [public Candidate->getObjectVersion (...)](Candidate%20Class/public%20Candidate--getObjectVersion.md) : string  
* [public Candidate->getProvisionalState ()](Candidate%20Class/public%20Candidate--getProvisionalState.md) : bool  
* [public Candidate->getTimestamp ()](Candidate%20Class/public%20Candidate--getTimestamp.md) : float  
* [public Candidate->haveLink (...)](Candidate%20Class/public%20Candidate--haveLink.md) : bool  
* [public Candidate->setName (...)](Candidate%20Class/public%20Candidate--setName.md) : bool  

### CondorcetPHP\Condorcet\Condorcet Class  

* [public static Condorcet::addMethod (...)](Condorcet%20Class/public%20static%20Condorcet--addMethod.md) : bool  
* [public static Condorcet::getAuthMethods (...)](Condorcet%20Class/public%20static%20Condorcet--getAuthMethods.md) : array  
* [public static Condorcet::getDefaultMethod ()](Condorcet%20Class/public%20static%20Condorcet--getDefaultMethod.md) : ?string  
* [public static Condorcet::getMethodClass (...)](Condorcet%20Class/public%20static%20Condorcet--getMethodClass.md) : ?string  
* [public static Condorcet::getVersion (...)](Condorcet%20Class/public%20static%20Condorcet--getVersion.md) : string  
* [public static Condorcet::isAuthMethod (...)](Condorcet%20Class/public%20static%20Condorcet--isAuthMethod.md) : bool  
* [public static Condorcet::setDefaultMethod (...)](Condorcet%20Class/public%20static%20Condorcet--setDefaultMethod.md) : bool  

### CondorcetPHP\Condorcet\CondorcetUtil Class  

* [public static CondorcetUtil::format (...)](CondorcetUtil%20Class/public%20static%20CondorcetUtil--format.md) : mixed  

### CondorcetPHP\Condorcet\Election Class  

* [public static Election::setMaxParseIteration (...)](Election%20Class/public%20static%20Election--setMaxParseIteration.md) : ?int  
* [public static Election::setMaxVoteNumber (...)](Election%20Class/public%20static%20Election--setMaxVoteNumber.md) : ?int  
* [public Election->__construct ()](Election%20Class/public%20Election--__construct.md)  
* [public Election->addCandidate (...)](Election%20Class/public%20Election--addCandidate.md) : CondorcetPHP\Condorcet\Candidate  
* [public Election->addCandidatesFromJson (...)](Election%20Class/public%20Election--addCandidatesFromJson.md) : array  
* [public Election->addConstraint (...)](Election%20Class/public%20Election--addConstraint.md) : bool  
* [public Election->addVote (...)](Election%20Class/public%20Election--addVote.md) : CondorcetPHP\Condorcet\Vote  
* [public Election->addVotesFromJson (...)](Election%20Class/public%20Election--addVotesFromJson.md) : int  
* [public Election->allowsVoteWeight (...)](Election%20Class/public%20Election--allowsVoteWeight.md) : bool  
* [public Election->canAddCandidate (...)](Election%20Class/public%20Election--canAddCandidate.md) : bool  
* [public Election->clearConstraints ()](Election%20Class/public%20Election--clearConstraints.md) : bool  
* [public Election->computeResult (...)](Election%20Class/public%20Election--computeResult.md) : void  
* [public Election->countCandidates ()](Election%20Class/public%20Election--countCandidates.md) : int  
* [public Election->countInvalidVoteWithConstraints ()](Election%20Class/public%20Election--countInvalidVoteWithConstraints.md) : int  
* [public Election->countValidVoteWithConstraints ()](Election%20Class/public%20Election--countValidVoteWithConstraints.md) : int  
* [public Election->countVotes (...)](Election%20Class/public%20Election--countVotes.md) : int  
* [public Election->getCandidateObjectFromName (...)](Election%20Class/public%20Election--getCandidateObjectFromName.md) : ?CondorcetPHP\Condorcet\Candidate  
* [public Election->getCandidatesList ()](Election%20Class/public%20Election--getCandidatesList.md) : array  
* [public Election->getCandidatesListAsString ()](Election%20Class/public%20Election--getCandidatesListAsString.md) : array  
* [public Election->getChecksum ()](Election%20Class/public%20Election--getChecksum.md) : string  
* [public Election->getCondorcetLoser ()](Election%20Class/public%20Election--getCondorcetLoser.md) : ?CondorcetPHP\Condorcet\Candidate  
* [public Election->getCondorcetWinner ()](Election%20Class/public%20Election--getCondorcetWinner.md) : ?CondorcetPHP\Condorcet\Candidate  
* [public Election->getConstraints ()](Election%20Class/public%20Election--getConstraints.md) : array  
* [public Election->getExplicitPairwise ()](Election%20Class/public%20Election--getExplicitPairwise.md) : array  
* [public Election->getGlobalTimer ()](Election%20Class/public%20Election--getGlobalTimer.md) : float  
* [public Election->getImplicitRankingRule ()](Election%20Class/public%20Election--getImplicitRankingRule.md) : bool  
* [public Election->getLastTimer ()](Election%20Class/public%20Election--getLastTimer.md) : float  
* [public Election->getLoser (...)](Election%20Class/public%20Election--getLoser.md) : CondorcetPHP\Condorcet\Candidate|array|null  
* [public Election->getNumberOfSeats ()](Election%20Class/public%20Election--getNumberOfSeats.md) : int  
* [public Election->getObjectVersion (...)](Election%20Class/public%20Election--getObjectVersion.md) : string  
* [public Election->getPairwise ()](Election%20Class/public%20Election--getPairwise.md) : CondorcetPHP\Condorcet\Algo\Pairwise  
* [public Election->getResult (...)](Election%20Class/public%20Election--getResult.md) : CondorcetPHP\Condorcet\Result  
* [public Election->getState ()](Election%20Class/public%20Election--getState.md) : int  
* [public Election->getTimerManager ()](Election%20Class/public%20Election--getTimerManager.md) : CondorcetPHP\Condorcet\Timer\Manager  
* [public Election->getVotesList (...)](Election%20Class/public%20Election--getVotesList.md) : array  
* [public Election->getVotesListAsString ()](Election%20Class/public%20Election--getVotesListAsString.md) : string  
* [public Election->getVotesListGenerator (...)](Election%20Class/public%20Election--getVotesListGenerator.md) : Generator  
* [public Election->getWinner (...)](Election%20Class/public%20Election--getWinner.md) : CondorcetPHP\Condorcet\Candidate|array|null  
* [public Election->isRegisteredCandidate (...)](Election%20Class/public%20Election--isRegisteredCandidate.md) : bool  
* [public Election->isVoteWeightAllowed ()](Election%20Class/public%20Election--isVoteWeightAllowed.md) : bool  
* [public Election->parseCandidates (...)](Election%20Class/public%20Election--parseCandidates.md) : array  
* [public Election->parseVotes (...)](Election%20Class/public%20Election--parseVotes.md) : int  
* [public Election->parseVotesWithoutFail (...)](Election%20Class/public%20Election--parseVotesWithoutFail.md) : int  
* [public Election->removeCandidates (...)](Election%20Class/public%20Election--removeCandidates.md) : array  
* [public Election->removeExternalDataHandler ()](Election%20Class/public%20Election--removeExternalDataHandler.md) : bool  
* [public Election->removeVote (...)](Election%20Class/public%20Election--removeVote.md) : bool  
* [public Election->removeVotesByTags (...)](Election%20Class/public%20Election--removeVotesByTags.md) : array  
* [public Election->setExternalDataHandler (...)](Election%20Class/public%20Election--setExternalDataHandler.md) : bool  
* [public Election->setImplicitRanking (...)](Election%20Class/public%20Election--setImplicitRanking.md) : bool  
* [public Election->setMethodOption (...)](Election%20Class/public%20Election--setMethodOption.md) : bool  
* [public Election->setNumberOfSeats (...)](Election%20Class/public%20Election--setNumberOfSeats.md) : int  
* [public Election->setStateToVote ()](Election%20Class/public%20Election--setStateToVote.md) : bool  
* [public Election->sumValidVotesWeightWithConstraints ()](Election%20Class/public%20Election--sumValidVotesWeightWithConstraints.md) : int  
* [public Election->sumVotesWeight ()](Election%20Class/public%20Election--sumVotesWeight.md) : int  
* [public Election->testIfVoteIsValidUnderElectionConstraints (...)](Election%20Class/public%20Election--testIfVoteIsValidUnderElectionConstraints.md) : bool  

### CondorcetPHP\Condorcet\Result Class  

* [public Result->getBuildTimeStamp ()](Result%20Class/public%20Result--getBuildTimeStamp.md) : float  
* [public Result->getClassGenerator ()](Result%20Class/public%20Result--getClassGenerator.md) : string  
* [public Result->getCondorcetElectionGeneratorVersion ()](Result%20Class/public%20Result--getCondorcetElectionGeneratorVersion.md) : string  
* [public Result->getCondorcetLoser ()](Result%20Class/public%20Result--getCondorcetLoser.md) : ?CondorcetPHP\Condorcet\Candidate  
* [public Result->getCondorcetWinner ()](Result%20Class/public%20Result--getCondorcetWinner.md) : ?CondorcetPHP\Condorcet\Candidate  
* [public Result->getLoser ()](Result%20Class/public%20Result--getLoser.md) : CondorcetPHP\Condorcet\Candidate|array|null  
* [public Result->getMethod ()](Result%20Class/public%20Result--getMethod.md) : string  
* [public Result->getMethodOptions ()](Result%20Class/public%20Result--getMethodOptions.md) : array  
* [public Result->getNumberOfSeats ()](Result%20Class/public%20Result--getNumberOfSeats.md) : ?int  
* [public Result->getObjectVersion (...)](Result%20Class/public%20Result--getObjectVersion.md) : string  
* [public Result->getOriginalResultArrayWithString ()](Result%20Class/public%20Result--getOriginalResultArrayWithString.md) : array  
* [public Result->getResultAsArray (...)](Result%20Class/public%20Result--getResultAsArray.md) : array  
* [public Result->getResultAsString ()](Result%20Class/public%20Result--getResultAsString.md) : string  
* [public Result->getStats ()](Result%20Class/public%20Result--getStats.md) : mixed  
* [public Result->getWarning (...)](Result%20Class/public%20Result--getWarning.md) : array  
* [public Result->getWinner ()](Result%20Class/public%20Result--getWinner.md) : CondorcetPHP\Condorcet\Candidate|array|null  
* [public Result->isProportional ()](Result%20Class/public%20Result--isProportional.md) : bool  

### CondorcetPHP\Condorcet\Timer\Manager Class  

* [public Timer\Manager->getHistory ()](Timer_Manager%20Class/public%20Timer_Manager--getHistory.md) : array  
* [public Timer\Manager->getObjectVersion (...)](Timer_Manager%20Class/public%20Timer_Manager--getObjectVersion.md) : string  

### CondorcetPHP\Condorcet\Vote Class  

* [public Vote->__construct (...)](Vote%20Class/public%20Vote--__construct.md)  
* [public Vote->addTags (...)](Vote%20Class/public%20Vote--addTags.md) : bool  
* [public Vote->countLinks ()](Vote%20Class/public%20Vote--countLinks.md) : int  
* [public Vote->countRankingCandidates ()](Vote%20Class/public%20Vote--countRankingCandidates.md) : int  
* [public Vote->getAllCandidates ()](Vote%20Class/public%20Vote--getAllCandidates.md) : array  
* [public Vote->getContextualRanking (...)](Vote%20Class/public%20Vote--getContextualRanking.md) : array  
* [public Vote->getContextualRankingAsString (...)](Vote%20Class/public%20Vote--getContextualRankingAsString.md) : array  
* [public Vote->getCreateTimestamp ()](Vote%20Class/public%20Vote--getCreateTimestamp.md) : float  
* [public Vote->getHashCode ()](Vote%20Class/public%20Vote--getHashCode.md) : string  
* [public Vote->getHistory ()](Vote%20Class/public%20Vote--getHistory.md) : array  
* [public Vote->getLinks ()](Vote%20Class/public%20Vote--getLinks.md) : ?array  
* [public Vote->getObjectVersion (...)](Vote%20Class/public%20Vote--getObjectVersion.md) : string  
* [public Vote->getRanking ()](Vote%20Class/public%20Vote--getRanking.md) : array  
* [public Vote->getSimpleRanking (...)](Vote%20Class/public%20Vote--getSimpleRanking.md) : string  
* [public Vote->getTags ()](Vote%20Class/public%20Vote--getTags.md) : array  
* [public Vote->getTagsAsString ()](Vote%20Class/public%20Vote--getTagsAsString.md) : string  
* [public Vote->getTimestamp ()](Vote%20Class/public%20Vote--getTimestamp.md) : float  
* [public Vote->getWeight (...)](Vote%20Class/public%20Vote--getWeight.md) : int  
* [public Vote->haveLink (...)](Vote%20Class/public%20Vote--haveLink.md) : bool  
* [public Vote->removeAllTags ()](Vote%20Class/public%20Vote--removeAllTags.md) : bool  
* [public Vote->removeCandidate (...)](Vote%20Class/public%20Vote--removeCandidate.md) : bool  
* [public Vote->removeTags (...)](Vote%20Class/public%20Vote--removeTags.md) : array  
* [public Vote->setRanking (...)](Vote%20Class/public%20Vote--setRanking.md) : bool  
* [public Vote->setWeight (...)](Vote%20Class/public%20Vote--setWeight.md) : int  



# Full Class & Methods References
_Including above methods from public API_


#### Abstract CondorcetPHP\Condorcet\Algo\Method   
```php
* public static setOption (string $optionName, mixed $optionValue) : bool  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __destruct ()  
* public getObjectVersion (bool $major = false) : string  
* public getResult () : CondorcetPHP\Condorcet\Result  
* protected createResult (array $result) : CondorcetPHP\Condorcet\Result  
* protected getStats () : array  
```

#### CondorcetPHP\Condorcet\Algo\Methods\Borda\BordaCount extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface  
```php
* public static setOption (string $optionName, mixed $optionValue) : bool  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __destruct ()  
* public getObjectVersion (bool $major = false) : string  
* public getResult () : CondorcetPHP\Condorcet\Result  
* protected compute () : void  
* protected createResult (array $result) : CondorcetPHP\Condorcet\Result  
* protected getScoreByCandidateRanking (int $CandidatesRanked) : float  
* protected getStats () : array  
```

#### CondorcetPHP\Condorcet\Algo\Methods\Borda\DowdallSystem extends CondorcetPHP\Condorcet\Algo\Methods\Borda\BordaCount implements CondorcetPHP\Condorcet\Algo\MethodInterface  
```php
* public static setOption (string $optionName, mixed $optionValue) : bool  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __destruct ()  
* public getObjectVersion (bool $major = false) : string  
* public getResult () : CondorcetPHP\Condorcet\Result  
* protected compute () : void  
* protected createResult (array $result) : CondorcetPHP\Condorcet\Result  
* protected getScoreByCandidateRanking (int $CandidatesRanked) : float  
* protected getStats () : array  
```

#### CondorcetPHP\Condorcet\Algo\Methods\CondorcetBasic extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface  
```php
* public static setOption (string $optionName, mixed $optionValue) : bool  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __destruct ()  
* public getLoser () : ?int  
* public getObjectVersion (bool $major = false) : string  
* public getResult () : CondorcetPHP\Condorcet\Result  
* public getWinner () : ?int  
* protected createResult (array $result) : CondorcetPHP\Condorcet\Result  
* protected getStats () : array  
```

#### CondorcetPHP\Condorcet\Algo\Methods\Copeland\Copeland extends CondorcetPHP\Condorcet\Algo\Methods\PairwiseStatsBased_Core implements CondorcetPHP\Condorcet\Algo\MethodInterface  
```php
* public static setOption (string $optionName, mixed $optionValue) : bool  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __destruct ()  
* public getObjectVersion (bool $major = false) : string  
* public getResult () : CondorcetPHP\Condorcet\Result  
* protected createResult (array $result) : CondorcetPHP\Condorcet\Result  
* protected getStats () : array  
* protected looking (array $challenge) : int  
* protected makeRanking () : void  
```

#### CondorcetPHP\Condorcet\Algo\Methods\Dodgson\DodgsonQuick extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface  
```php
* public static setOption (string $optionName, mixed $optionValue) : bool  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __destruct ()  
* public getObjectVersion (bool $major = false) : string  
* public getResult () : CondorcetPHP\Condorcet\Result  
* protected compute () : void  
* protected createResult (array $result) : CondorcetPHP\Condorcet\Result  
* protected getStats () : array  
```

#### CondorcetPHP\Condorcet\Algo\Methods\Dodgson\DodgsonTidemanApproximation extends CondorcetPHP\Condorcet\Algo\Methods\PairwiseStatsBased_Core implements CondorcetPHP\Condorcet\Algo\MethodInterface  
```php
* public static setOption (string $optionName, mixed $optionValue) : bool  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __destruct ()  
* public getObjectVersion (bool $major = false) : string  
* public getResult () : CondorcetPHP\Condorcet\Result  
* protected createResult (array $result) : CondorcetPHP\Condorcet\Result  
* protected getStats () : array  
* protected looking (array $challenge) : int  
* protected makeRanking () : void  
```

#### CondorcetPHP\Condorcet\Algo\Methods\InstantRunoff\InstantRunoff extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface  
```php
* public static setOption (string $optionName, mixed $optionValue) : bool  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __destruct ()  
* public getObjectVersion (bool $major = false) : string  
* public getResult () : CondorcetPHP\Condorcet\Result  
* protected compute () : void  
* protected createResult (array $result) : CondorcetPHP\Condorcet\Result  
* protected getStats () : array  
* protected makeScore (array $candidateDone) : array  
```

#### CondorcetPHP\Condorcet\Algo\Methods\KemenyYoung\KemenyYoung extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface  
```php
* public static setOption (string $optionName, mixed $optionValue) : bool  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __destruct ()  
* public getObjectVersion (bool $major = false) : string  
* public getResult () : CondorcetPHP\Condorcet\Result  
* protected calcPossibleRanking () : void  
* protected calcRankingScore () : void  
* protected conflictInfos () : void  
* protected createResult (array $result) : CondorcetPHP\Condorcet\Result  
* protected getStats () : array  
* protected makeRanking () : void  
```

#### CondorcetPHP\Condorcet\Algo\Methods\Majority\FirstPastThePost extends CondorcetPHP\Condorcet\Algo\Methods\Majority\Majority_Core implements CondorcetPHP\Condorcet\Algo\MethodInterface  
```php
* public static setOption (string $optionName, mixed $optionValue) : bool  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __destruct ()  
* public getObjectVersion (bool $major = false) : string  
* public getResult () : CondorcetPHP\Condorcet\Result  
* protected compute () : void  
* protected createResult (array $result) : CondorcetPHP\Condorcet\Result  
* protected doOneRound () : array  
* protected getStats () : array  
```

#### Abstract CondorcetPHP\Condorcet\Algo\Methods\Majority\Majority_Core extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface  
```php
* public static setOption (string $optionName, mixed $optionValue) : bool  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __destruct ()  
* public getObjectVersion (bool $major = false) : string  
* public getResult () : CondorcetPHP\Condorcet\Result  
* protected compute () : void  
* protected createResult (array $result) : CondorcetPHP\Condorcet\Result  
* protected doOneRound () : array  
* protected getStats () : array  
```

#### CondorcetPHP\Condorcet\Algo\Methods\Majority\MultipleRoundsSystem extends CondorcetPHP\Condorcet\Algo\Methods\Majority\Majority_Core implements CondorcetPHP\Condorcet\Algo\MethodInterface  
```php
* public static setOption (string $optionName, mixed $optionValue) : bool  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __destruct ()  
* public getObjectVersion (bool $major = false) : string  
* public getResult () : CondorcetPHP\Condorcet\Result  
* protected compute () : void  
* protected createResult (array $result) : CondorcetPHP\Condorcet\Result  
* protected doOneRound () : array  
* protected getStats () : array  
```

#### CondorcetPHP\Condorcet\Algo\Methods\Minimax\MinimaxMargin extends CondorcetPHP\Condorcet\Algo\Methods\PairwiseStatsBased_Core implements CondorcetPHP\Condorcet\Algo\MethodInterface  
```php
* public static setOption (string $optionName, mixed $optionValue) : bool  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __destruct ()  
* public getObjectVersion (bool $major = false) : string  
* public getResult () : CondorcetPHP\Condorcet\Result  
* protected createResult (array $result) : CondorcetPHP\Condorcet\Result  
* protected getStats () : array  
* protected looking (array $challenge) : int  
* protected makeRanking () : void  
```

#### CondorcetPHP\Condorcet\Algo\Methods\Minimax\MinimaxOpposition extends CondorcetPHP\Condorcet\Algo\Methods\PairwiseStatsBased_Core implements CondorcetPHP\Condorcet\Algo\MethodInterface  
```php
* public static setOption (string $optionName, mixed $optionValue) : bool  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __destruct ()  
* public getObjectVersion (bool $major = false) : string  
* public getResult () : CondorcetPHP\Condorcet\Result  
* protected createResult (array $result) : CondorcetPHP\Condorcet\Result  
* protected getStats () : array  
* protected looking (array $challenge) : int  
* protected makeRanking () : void  
```

#### CondorcetPHP\Condorcet\Algo\Methods\Minimax\MinimaxWinning extends CondorcetPHP\Condorcet\Algo\Methods\PairwiseStatsBased_Core implements CondorcetPHP\Condorcet\Algo\MethodInterface  
```php
* public static setOption (string $optionName, mixed $optionValue) : bool  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __destruct ()  
* public getObjectVersion (bool $major = false) : string  
* public getResult () : CondorcetPHP\Condorcet\Result  
* protected createResult (array $result) : CondorcetPHP\Condorcet\Result  
* protected getStats () : array  
* protected looking (array $challenge) : int  
* protected makeRanking () : void  
```

#### Abstract CondorcetPHP\Condorcet\Algo\Methods\PairwiseStatsBased_Core extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface  
```php
* public static setOption (string $optionName, mixed $optionValue) : bool  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __destruct ()  
* public getObjectVersion (bool $major = false) : string  
* public getResult () : CondorcetPHP\Condorcet\Result  
* protected createResult (array $result) : CondorcetPHP\Condorcet\Result  
* protected getStats () : array  
* protected looking (array $challenge) : int  
* protected makeRanking () : void  
```

#### CondorcetPHP\Condorcet\Algo\Methods\RankedPairs\RankedPairsMargin extends CondorcetPHP\Condorcet\Algo\Methods\RankedPairs\RankedPairs_Core implements CondorcetPHP\Condorcet\Algo\MethodInterface  
```php
* public static setOption (string $optionName, mixed $optionValue) : bool  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __destruct ()  
* public getObjectVersion (bool $major = false) : string  
* public getResult () : CondorcetPHP\Condorcet\Result  
* protected createResult (array $result) : CondorcetPHP\Condorcet\Result  
* protected followCycle (array $virtualArcs, int $startCandidateKey, int $searchCandidateKey, array $done = []) : array  
* protected getArcsInCycle (array $virtualArcs) : array  
* protected getStats () : array  
* protected getWinners (array $alreadyDone) : array  
* protected makeArcs () : void  
* protected makeResult () : array  
* protected pairwiseSort () : array  
```

#### CondorcetPHP\Condorcet\Algo\Methods\RankedPairs\RankedPairsWinning extends CondorcetPHP\Condorcet\Algo\Methods\RankedPairs\RankedPairs_Core implements CondorcetPHP\Condorcet\Algo\MethodInterface  
```php
* public static setOption (string $optionName, mixed $optionValue) : bool  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __destruct ()  
* public getObjectVersion (bool $major = false) : string  
* public getResult () : CondorcetPHP\Condorcet\Result  
* protected createResult (array $result) : CondorcetPHP\Condorcet\Result  
* protected followCycle (array $virtualArcs, int $startCandidateKey, int $searchCandidateKey, array $done = []) : array  
* protected getArcsInCycle (array $virtualArcs) : array  
* protected getStats () : array  
* protected getWinners (array $alreadyDone) : array  
* protected makeArcs () : void  
* protected makeResult () : array  
* protected pairwiseSort () : array  
```

#### CondorcetPHP\Condorcet\Algo\Methods\RankedPairs\RankedPairs_Core extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface  
```php
* public static setOption (string $optionName, mixed $optionValue) : bool  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __destruct ()  
* public getObjectVersion (bool $major = false) : string  
* public getResult () : CondorcetPHP\Condorcet\Result  
* protected createResult (array $result) : CondorcetPHP\Condorcet\Result  
* protected followCycle (array $virtualArcs, int $startCandidateKey, int $searchCandidateKey, array $done = []) : array  
* protected getArcsInCycle (array $virtualArcs) : array  
* protected getStats () : array  
* protected getWinners (array $alreadyDone) : array  
* protected makeArcs () : void  
* protected makeResult () : array  
* protected pairwiseSort () : array  
```

#### CondorcetPHP\Condorcet\Algo\Methods\STV\SingleTransferableVote extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface  
```php
* public static setOption (string $optionName, mixed $optionValue) : bool  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __destruct ()  
* public getObjectVersion (bool $major = false) : string  
* public getResult () : CondorcetPHP\Condorcet\Result  
* protected compute () : void  
* protected createResult (array $result) : CondorcetPHP\Condorcet\Result  
* protected getStats () : array  
* protected makeScore (array $surplus, array $candidateElected, array $candidateEliminated) : array  
```

#### CondorcetPHP\Condorcet\Algo\Methods\Schulze\SchulzeMargin extends CondorcetPHP\Condorcet\Algo\Methods\Schulze\Schulze_Core implements CondorcetPHP\Condorcet\Algo\MethodInterface  
```php
* public static setOption (string $optionName, mixed $optionValue) : bool  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __destruct ()  
* public getObjectVersion (bool $major = false) : string  
* public getResult () : CondorcetPHP\Condorcet\Result  
* protected createResult (array $result) : CondorcetPHP\Condorcet\Result  
* protected getStats () : array  
* protected makeRanking () : void  
* protected makeStrongestPaths () : void  
* protected prepareStrongestPath () : void  
* protected schulzeVariant (int $i, int $j) : int  
```

#### CondorcetPHP\Condorcet\Algo\Methods\Schulze\SchulzeRatio extends CondorcetPHP\Condorcet\Algo\Methods\Schulze\Schulze_Core implements CondorcetPHP\Condorcet\Algo\MethodInterface  
```php
* public static setOption (string $optionName, mixed $optionValue) : bool  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __destruct ()  
* public getObjectVersion (bool $major = false) : string  
* public getResult () : CondorcetPHP\Condorcet\Result  
* protected createResult (array $result) : CondorcetPHP\Condorcet\Result  
* protected getStats () : array  
* protected makeRanking () : void  
* protected makeStrongestPaths () : void  
* protected prepareStrongestPath () : void  
* protected schulzeVariant (int $i, int $j) : float  
```

#### CondorcetPHP\Condorcet\Algo\Methods\Schulze\SchulzeWinning extends CondorcetPHP\Condorcet\Algo\Methods\Schulze\Schulze_Core implements CondorcetPHP\Condorcet\Algo\MethodInterface  
```php
* public static setOption (string $optionName, mixed $optionValue) : bool  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __destruct ()  
* public getObjectVersion (bool $major = false) : string  
* public getResult () : CondorcetPHP\Condorcet\Result  
* protected createResult (array $result) : CondorcetPHP\Condorcet\Result  
* protected getStats () : array  
* protected makeRanking () : void  
* protected makeStrongestPaths () : void  
* protected prepareStrongestPath () : void  
* protected schulzeVariant (int $i, int $j) : int  
```

#### Abstract CondorcetPHP\Condorcet\Algo\Methods\Schulze\Schulze_Core extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface  
```php
* public static setOption (string $optionName, mixed $optionValue) : bool  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __destruct ()  
* public getObjectVersion (bool $major = false) : string  
* public getResult () : CondorcetPHP\Condorcet\Result  
* protected createResult (array $result) : CondorcetPHP\Condorcet\Result  
* protected getStats () : array  
* protected makeRanking () : void  
* protected makeStrongestPaths () : void  
* protected prepareStrongestPath () : void  
* protected schulzeVariant (int $i, int $j)  
```

#### CondorcetPHP\Condorcet\Algo\Pairwise implements ArrayAccess, Iterator, Traversable  
```php
* public __clone () : void  
* public __construct (CondorcetPHP\Condorcet\Election $link)  
* public __destruct ()  
* public addNewVote (int $key) : void  
* public current () : array  
* public getExplicitPairwise () : array  
* public getObjectVersion (bool $major = false) : string  
* public key () : ?int  
* public next () : void  
* public offsetExists ($offset) : bool  
* public offsetGet ($offset) : ?array  
* public offsetSet ($offset, $value) : void  
* public offsetUnset ($offset) : void  
* public removeVote (int $key) : void  
* public rewind () : void  
* public setElection (CondorcetPHP\Condorcet\Election $election) : void  
* public valid () : bool  
* protected computeOneVote (array $pairwise, CondorcetPHP\Condorcet\Vote $oneVote) : void  
* protected doPairwise () : void  
* protected formatNewpairwise () : void  
```

#### Abstract CondorcetPHP\Condorcet\Algo\Tools\PairwiseStats   
```php
* public static PairwiseComparison (CondorcetPHP\Condorcet\Algo\Pairwise $pairwise) : array  
```

#### CondorcetPHP\Condorcet\Algo\Tools\Permutation   
```php
* public static countPossiblePermutations (int $candidatesNumber) : int  
* public __construct (int $arr_count)  
* public getResults () : array  
* public writeResults (string $path) : void  
* protected createCandidates () : array  
* private _exec (array|int $a, array $i = []) : void  
* private _permute (array $arr) : array|int  
```

#### Abstract CondorcetPHP\Condorcet\Algo\Tools\StvQuotas   
```php
* public static getQuota (string $quota, int $votesWeight, int $seats) : float  
```

#### Abstract CondorcetPHP\Condorcet\Algo\Tools\TieBreakersCollection   
```php
* public static tieBreaker_1 (CondorcetPHP\Condorcet\Election $election, array $candidatesKeys) : array  
```

#### Abstract CondorcetPHP\Condorcet\Algo\Tools\VirtualVote   
```php
* public static removeCandidates (CondorcetPHP\Condorcet\Vote $vote, array $candidatesList) : CondorcetPHP\Condorcet\Vote  
```

#### CondorcetPHP\Condorcet\Candidate implements Stringable  
```php
* public __clone () : void  
* public __construct (string $name)  
* public __destruct ()  
* public __toString () : string  
* public countLinks () : int  
* public destroyLink (CondorcetPHP\Condorcet\Election $election) : bool  
* public getCreateTimestamp () : float  
* public getHistory () : array  
* public getLinks () : ?array  
* public getName () : string  
* public getObjectVersion (bool $major = false) : string  
* public getProvisionalState () : bool  
* public getTimestamp () : float  
* public haveLink (CondorcetPHP\Condorcet\Election $election) : bool  
* public registerLink (CondorcetPHP\Condorcet\Election $election) : void  
* public setName (string $name) : bool  
* public setProvisionalState (bool $provisional) : bool  
* protected destroyAllLink () : void  
* private checkNameInElectionContext (string $name) : bool  
```

#### Abstract CondorcetPHP\Condorcet\Condorcet   
```php
* public static addMethod (string $methodClass) : bool  
* public static condorcetBasicSubstitution (?string $substitution) : string  
* public static getAuthMethods (bool $basic = false) : array  
* public static getDefaultMethod () : ?string  
* public static getMethodClass (string $method) : ?string  
* public static getVersion (bool $major = false) : string  
* public static isAuthMethod (string $method) : bool  
* public static setDefaultMethod (string $method) : bool  
* protected static testMethod (string $method) : bool  
```

#### Abstract CondorcetPHP\Condorcet\CondorcetUtil   
```php
* public static format (mixed $input, bool $convertObject = true) : mixed  
* public static isJson (string $string) : bool  
* public static prepareJson (string $input) : mixed  
* public static prepareParse (string $input, bool $isFile) : array  
```

#### CondorcetPHP\Condorcet\Console\Commands\ElectionCommand extends Symfony\Component\Console\Command\Command   
```php
* public static getDefaultDescription () : ?string  
* public static getDefaultName ()  
* public __construct (?string $name = null)  
* public addArgument (string $name, ?int $mode = null, string $description = , $default = null)  
* public addOption (string $name, $shortcut = null, ?int $mode = null, string $description = , $default = null)  
* public addUsage (string $usage)  
* public displayPairwise (Symfony\Component\Console\Output\OutputInterface $output) : void  
* public displayVotesCount (Symfony\Component\Console\Output\OutputInterface $output) : void  
* public displayVotesList (Symfony\Component\Console\Output\OutputInterface $output) : void  
* public getAliases ()  
* public getApplication ()  
* public getDefinition ()  
* public getDescription ()  
* public getHelp ()  
* public getHelper (string $name)  
* public getHelperSet ()  
* public getName ()  
* public getNativeDefinition ()  
* public getProcessedHelp ()  
* public getSynopsis (bool $short = false)  
* public getUsages ()  
* public ignoreValidationErrors ()  
* public isEnabled ()  
* public isHidden ()  
* public mergeApplicationDefinition (bool $mergeArgs = true)  
* public run (Symfony\Component\Console\Input\InputInterface $input, Symfony\Component\Console\Output\OutputInterface $output)  
* public setAliases (iterable $aliases)  
* public setApplication (?Symfony\Component\Console\Application $application = null)  
* public setCode (callable $code)  
* public setDefinition ($definition)  
* public setDescription (string $description)  
* public setHelp (string $help)  
* public setHelperSet (Symfony\Component\Console\Helper\HelperSet $helperSet)  
* public setHidden (bool $hidden)  
* public setName (string $name)  
* public setProcessTitle (string $title)  
* protected configure () : void  
* protected displayCandidatesList (Symfony\Component\Console\Output\OutputInterface $output) : void  
* protected execute (Symfony\Component\Console\Input\InputInterface $input, Symfony\Component\Console\Output\OutputInterface $output) : int  
* protected formatResultTable (CondorcetPHP\Condorcet\Result $result) : array  
* protected getFilePath (string $path) : ?string  
* protected initialize (Symfony\Component\Console\Input\InputInterface $input, Symfony\Component\Console\Output\OutputInterface $output) : void  
* protected interact (Symfony\Component\Console\Input\InputInterface $input, Symfony\Component\Console\Output\OutputInterface $output) : void  
* protected isAbsolute (string $path) : bool  
* protected prepareMethods (array $methodArgument) : array  
* protected sectionVerbose (Symfony\Component\Console\Style\SymfonyStyle $io, Symfony\Component\Console\Input\InputInterface $input, Symfony\Component\Console\Output\OutputInterface $output) : void  
* protected useDataHandler (Symfony\Component\Console\Input\InputInterface $input) : ?Closure  
```

#### Abstract CondorcetPHP\Condorcet\Console\CondorcetApplication   
```php
* public static create () : bool  
* public static run () : void  
```

#### CondorcetPHP\Condorcet\Constraints\NoTie extends CondorcetPHP\Condorcet\VoteConstraint   
```php
* public static isVoteAllow (CondorcetPHP\Condorcet\Election $election, CondorcetPHP\Condorcet\Vote $vote) : bool  
* protected static evaluateVote (array $vote) : bool  
```

#### Abstract CondorcetPHP\Condorcet\DataManager\ArrayManager implements ArrayAccess, Countable, Iterator, Traversable  
```php
* public __clone () : void  
* public __construct ()  
* public __destruct ()  
* public __serialize () : array  
* public __unserialize (array $data) : void  
* public checkRegularize () : bool  
* public clearCache () : void  
* public closeHandler () : void  
* public count () : int  
* public current () : mixed  
* public debugGetCache () : array  
* public getCacheSize () : int  
* public getContainerSize () : int  
* public getFirstKey () : int  
* public getFullDataSet () : array  
* public getObjectVersion (bool $major = false) : string  
* public importHandler (CondorcetPHP\Condorcet\DataManager\DataHandlerDrivers\DataHandlerDriverInterface $handler) : bool  
* public isUsingHandler () : bool  
* public key () : ?int  
* public keyExist ($offset) : bool  
* public next () : void  
* public offsetExists ($offset) : bool  
* public offsetGet ($offset) : mixed  
* public offsetSet ($offset, $value) : void  
* public offsetUnset ($offset) : void  
* public regularize () : bool  
* public resetCounter () : int  
* public resetMaxKey () : ?int  
* public rewind () : void  
* public valid () : bool  
* protected decodeManyEntities (array $entities) : array  
* protected decodeOneEntity (string $data) : CondorcetPHP\Condorcet\Vote  
* protected encodeManyEntities (array $entities) : array  
* protected encodeOneEntity (CondorcetPHP\Condorcet\Vote $data) : string  
* protected populateCache () : void  
* protected preDeletedTask ($object) : void  
* protected setCursorOnNextKeyInArray (array $array) : void  
```

#### CondorcetPHP\Condorcet\DataManager\DataHandlerDrivers\PdoDriver\PdoHandlerDriver implements CondorcetPHP\Condorcet\DataManager\DataHandlerDrivers\DataHandlerDriverInterface  
```php
* public __construct (PDO $bdd, bool $tryCreateTable = false, array $struct = [Entities,id,data])  
* public closeTransaction () : void  
* public countEntities () : int  
* public createTable () : void  
* public deleteOneEntity (int $key, bool $justTry) : ?int  
* public getObjectVersion (bool $major = false) : string  
* public insertEntities (array $input) : void  
* public selectMaxKey () : ?int  
* public selectMinKey () : int  
* public selectOneEntity (int $key) : string|bool  
* public selectRangeEntities (int $key, int $limit) : array  
* protected checkStructureTemplate (array $struct) : bool  
* protected initPrepareQuery () : void  
* protected initTransaction () : void  
* protected sliceInput (array $input) : void  
```

#### CondorcetPHP\Condorcet\DataManager\VotesManager extends CondorcetPHP\Condorcet\DataManager\ArrayManager implements Traversable, Iterator, Countable, ArrayAccess  
```php
* public UpdateAndResetComputing (int $key, int $type) : void  
* public __clone () : void  
* public __construct (CondorcetPHP\Condorcet\Election $election)  
* public __destruct ()  
* public __serialize () : array  
* public __unserialize (array $data) : void  
* public checkRegularize () : bool  
* public clearCache () : void  
* public closeHandler () : void  
* public count () : int  
* public countInvalidVoteWithConstraints () : int  
* public countVotes (?array $tag, bool $with) : int  
* public current () : mixed  
* public debugGetCache () : array  
* public destroyElection () : void  
* public getCacheSize () : int  
* public getContainerSize () : int  
* public getElection () : CondorcetPHP\Condorcet\Election  
* public getFirstKey () : int  
* public getFullDataSet () : array  
* public getObjectVersion (bool $major = false) : string  
* public getVoteKey (CondorcetPHP\Condorcet\Vote $vote) : ?int  
* public getVotesList (?array $tags = null, bool $with = true) : array  
* public getVotesListAsString () : string  
* public getVotesListGenerator (?array $tags = null, bool $with = true) : Generator  
* public getVotesValidUnderConstraintGenerator (?array $tags = null, bool $with = true) : Generator  
* public importHandler (CondorcetPHP\Condorcet\DataManager\DataHandlerDrivers\DataHandlerDriverInterface $handler) : bool  
* public isUsingHandler () : bool  
* public key () : ?int  
* public keyExist ($offset) : bool  
* public next () : void  
* public offsetExists ($offset) : bool  
* public offsetGet ($offset) : ?CondorcetPHP\Condorcet\Vote  
* public offsetSet ($offset, $value) : void  
* public offsetUnset ($offset) : void  
* public regularize () : bool  
* public resetCounter () : int  
* public resetMaxKey () : ?int  
* public rewind () : void  
* public setElection (CondorcetPHP\Condorcet\Election $election) : void  
* public sumVotesWeight (bool $constraint = false) : int  
* public valid () : bool  
* protected decodeManyEntities (array $entities) : array  
* protected decodeOneEntity (string $data) : CondorcetPHP\Condorcet\Vote  
* protected encodeManyEntities (array $entities) : array  
* protected encodeOneEntity (CondorcetPHP\Condorcet\Vote $data) : string  
* protected getFullVotesListGenerator () : Generator  
* protected getPartialVotesListGenerator (array $tags, bool $with) : Generator  
* protected populateCache () : void  
* protected preDeletedTask ($object) : void  
* protected setCursorOnNextKeyInArray (array $array) : void  
```

#### CondorcetPHP\Condorcet\Election   
```php
* public static setMaxParseIteration (?int $maxParseIterations) : ?int  
* public static setMaxVoteNumber (?int $maxVotesNumber) : ?int  
* protected static formatResultOptions (array $arg) : array  
* public __clone () : void  
* public __construct ()  
* public __destruct ()  
* public __serialize () : array  
* public __unserialize (array $data) : void  
* public addCandidate (CondorcetPHP\Condorcet\Candidate|string|null $candidate = null) : CondorcetPHP\Condorcet\Candidate  
* public addCandidatesFromJson (string $input) : array  
* public addConstraint (string $constraintClass) : bool  
* public addVote (CondorcetPHP\Condorcet\Vote|array|string $vote, array|string|null $tags = null) : CondorcetPHP\Condorcet\Vote  
* public addVotesFromJson (string $input) : int  
* public allowsVoteWeight (bool $rule = true) : bool  
* public canAddCandidate (CondorcetPHP\Condorcet\Candidate|string $candidate) : bool  
* public checkVoteCandidate (CondorcetPHP\Condorcet\Vote $vote) : bool  
* public cleanupCalculator () : void  
* public clearConstraints () : bool  
* public computeResult (?string $method = null) : void  
* public convertRankingCandidates (array $ranking) : bool  
* public countCandidates () : int  
* public countInvalidVoteWithConstraints () : int  
* public countValidVoteWithConstraints () : int  
* public countVotes (mixed $tags = null, bool $with = true) : int  
* public finishUpdateVote (CondorcetPHP\Condorcet\Vote $existVote) : void  
* public getCandidateKey (CondorcetPHP\Condorcet\Candidate|string $candidate) : ?int  
* public getCandidateObjectFromKey (int $candidate_key) : ?CondorcetPHP\Condorcet\Candidate  
* public getCandidateObjectFromName (string $candidateName) : ?CondorcetPHP\Condorcet\Candidate  
* public getCandidatesList () : array  
* public getCandidatesListAsString () : array  
* public getChecksum () : string  
* public getCondorcetLoser () : ?CondorcetPHP\Condorcet\Candidate  
* public getCondorcetWinner () : ?CondorcetPHP\Condorcet\Candidate  
* public getConstraints () : array  
* public getExplicitPairwise () : array  
* public getGlobalTimer () : float  
* public getImplicitRankingRule () : bool  
* public getLastTimer () : float  
* public getLoser (?string $method = null) : CondorcetPHP\Condorcet\Candidate|array|null  
* public getNumberOfSeats () : int  
* public getObjectVersion (bool $major = false) : string  
* public getPairwise () : CondorcetPHP\Condorcet\Algo\Pairwise  
* public getResult (?string $method = null, array $options = []) : CondorcetPHP\Condorcet\Result  
* public getState () : int  
* public getTimerManager () : CondorcetPHP\Condorcet\Timer\Manager  
* public getVoteKey (CondorcetPHP\Condorcet\Vote $vote) : ?int  
* public getVotesList (mixed $tags = null, bool $with = true) : array  
* public getVotesListAsString () : string  
* public getVotesListGenerator (mixed $tags = null, bool $with = true) : Generator  
* public getVotesManager () : CondorcetPHP\Condorcet\DataManager\VotesManager  
* public getVotesValidUnderConstraintGenerator ($tags = null, bool $with = true) : Generator  
* public getWinner (?string $method = null) : CondorcetPHP\Condorcet\Candidate|array|null  
* public isRegisteredCandidate (CondorcetPHP\Condorcet\Candidate|string $candidate, bool $strictMode = true) : bool  
* public isVoteWeightAllowed () : bool  
* public parseCandidates (string $input, bool $isFile = false) : array  
* public parseVotes (string $input, bool $isFile = false) : int  
* public parseVotesWithoutFail (string $input, bool $isFile = false, ?Closure $callBack = null) : int  
* public prepareUpdateVote (CondorcetPHP\Condorcet\Vote $existVote) : void  
* public removeCandidates (mixed $candidates_input) : array  
* public removeExternalDataHandler () : bool  
* public removeVote (CondorcetPHP\Condorcet\Vote $vote) : bool  
* public removeVotesByTags (array|string $tags, bool $with = true) : array  
* public setExternalDataHandler (CondorcetPHP\Condorcet\DataManager\DataHandlerDrivers\DataHandlerDriverInterface $driver) : bool  
* public setImplicitRanking (bool $rule = true) : bool  
* public setMethodOption (string $method, string $optionName, mixed $optionValue) : bool  
* public setNumberOfSeats (int $seats) : int  
* public setStateToVote () : bool  
* public sumValidVotesWeightWithConstraints () : int  
* public sumVotesWeight () : int  
* public testIfVoteIsValidUnderElectionConstraints (CondorcetPHP\Condorcet\Vote $vote) : bool  
* protected cleanupCompute () : void  
* protected destroyAllLink () : void  
* protected doAddVotesFromParse (array $adding) : void  
* protected initResult (string $class) : void  
* protected makePairwise () : void  
* protected preparePairwiseAndCleanCompute () : bool  
* protected prepareVoteInput (CondorcetPHP\Condorcet\Vote|array|string $vote, array|string|null $tags = null) : void  
* protected registerAllLinks () : void  
* protected registerVote (CondorcetPHP\Condorcet\Vote $vote, array|string|null $tags) : CondorcetPHP\Condorcet\Vote  
* protected synthesisVoteFromParse (int $count, int $multiple, array $adding, CondorcetPHP\Condorcet\Vote|array|string $vote, array|string|null $tags, int $weight) : void  
```

#### Abstract CondorcetPHP\Condorcet\ElectionProcess\VoteUtil   
```php
* public static convertVoteInput (string $formula) : array  
* public static getRankingAsString (array $ranking) : string  
* public static parseAnalysingOneLine (int|bool $searchCharacter, string $line) : int  
* public static tagsConvert (array|string|null $tags) : ?array  
```

#### CondorcetPHP\Condorcet\Result implements ArrayAccess, Countable, Iterator, Traversable  
```php
* public __construct (string $fromMethod, string $byClass, CondorcetPHP\Condorcet\Election $election, array $result, $stats, ?int $seats = null, array $methodOptions = [])  
* public __destruct ()  
* public addWarning (int $type, ?string $msg = null) : bool  
* public count () : int  
* public current ()  
* public getBuildTimeStamp () : float  
* public getClassGenerator () : string  
* public getCondorcetElectionGeneratorVersion () : string  
* public getCondorcetLoser () : ?CondorcetPHP\Condorcet\Candidate  
* public getCondorcetWinner () : ?CondorcetPHP\Condorcet\Candidate  
* public getLoser () : CondorcetPHP\Condorcet\Candidate|array|null  
* public getMethod () : string  
* public getMethodOptions () : array  
* public getNumberOfSeats () : ?int  
* public getObjectVersion (bool $major = false) : string  
* public getOriginalResultArrayWithString () : array  
* public getResultAsArray (bool $convertToString = false) : array  
* public getResultAsInternalKey () : array  
* public getResultAsString () : string  
* public getStats () : mixed  
* public getWarning (?int $type = null) : array  
* public getWinner () : CondorcetPHP\Condorcet\Candidate|array|null  
* public isProportional () : bool  
* public key () : int  
* public next () : void  
* public offsetExists ($offset) : bool  
* public offsetGet ($offset) : CondorcetPHP\Condorcet\Candidate|array|null  
* public offsetSet ($offset, $value) : void  
* public offsetUnset ($offset) : void  
* public rewind () : void  
* public valid () : bool  
* protected makeUserResult (CondorcetPHP\Condorcet\Election $election) : array  
```

#### CondorcetPHP\Condorcet\Throwable\CondorcetException extends Exception implements Throwable, Stringable  
```php
* public __construct (int $code = 0, string $infos)  
* public __toString () : string  
* public __wakeup ()  
* public getCode ()  
* public getFile () : string  
* public getLine () : int  
* public getMessage () : string  
* public getObjectVersion (bool $major = false) : string  
* public getPrevious () : ?Throwable  
* public getTrace () : array  
* public getTraceAsString () : string  
* protected correspondence (int $code) : string  
```

#### CondorcetPHP\Condorcet\Throwable\CondorcetInternalError extends Error implements Stringable, Throwable  
```php
* public __construct (string $message)  
* public __toString () : string  
* public __wakeup ()  
* public getCode ()  
* public getFile () : string  
* public getLine () : int  
* public getMessage () : string  
* public getObjectVersion (bool $major = false) : string  
* public getPrevious () : ?Throwable  
* public getTrace () : array  
* public getTraceAsString () : string  
```

#### CondorcetPHP\Condorcet\Throwable\CondorcetInternalException extends Exception implements Stringable, Throwable  
```php
* public __construct (string $message = , int $code = 0, ?Throwable $previous = null)  
* public __toString () : string  
* public __wakeup ()  
* public getCode ()  
* public getFile () : string  
* public getLine () : int  
* public getMessage () : string  
* public getPrevious () : ?Throwable  
* public getTrace () : array  
* public getTraceAsString () : string  
```

#### CondorcetPHP\Condorcet\Timer\Chrono   
```php
* public __construct (CondorcetPHP\Condorcet\Timer\Manager $timer, ?string $role = null)  
* public __destruct ()  
* public getObjectVersion (bool $major = false) : string  
* public getRole () : ?string  
* public getStart () : float  
* public getTimerManager () : CondorcetPHP\Condorcet\Timer\Manager  
* public setRole (?string $role) : void  
* protected managerStartDeclare () : void  
* protected resetStart () : void  
```

#### CondorcetPHP\Condorcet\Timer\Manager   
```php
* public addTime (CondorcetPHP\Condorcet\Timer\Chrono $chrono) : void  
* public getGlobalTimer () : float  
* public getHistory () : array  
* public getLastTimer () : float  
* public getObjectVersion (bool $major = false) : string  
* public startDeclare (CondorcetPHP\Condorcet\Timer\Chrono $chrono) : void  
```

#### CondorcetPHP\Condorcet\Vote implements Iterator, Stringable, Traversable  
```php
* public __clone () : void  
* public __construct (array|string $ranking, array|string|null $tags = null, ?float $ownTimestamp = null, ?CondorcetPHP\Condorcet\Election $electionContext = null)  
* public __destruct ()  
* public __serialize () : array  
* public __toString () : string  
* public addTags (array|string $tags) : bool  
* public countLinks () : int  
* public countRankingCandidates () : int  
* public current () : array  
* public destroyLink (CondorcetPHP\Condorcet\Election $election) : bool  
* public getAllCandidates () : array  
* public getContextualRanking (CondorcetPHP\Condorcet\Election $election) : array  
* public getContextualRankingAsString (CondorcetPHP\Condorcet\Election $election) : array  
* public getCreateTimestamp () : float  
* public getHashCode () : string  
* public getHistory () : array  
* public getLinks () : ?array  
* public getObjectVersion (bool $major = false) : string  
* public getRanking () : array  
* public getSimpleRanking (?CondorcetPHP\Condorcet\Election $context = null, bool $displayWeight = true) : string  
* public getTags () : array  
* public getTagsAsString () : string  
* public getTimestamp () : float  
* public getWeight (?CondorcetPHP\Condorcet\Election $context = null) : int  
* public haveLink (CondorcetPHP\Condorcet\Election $election) : bool  
* public key () : int  
* public next () : void  
* public registerLink (CondorcetPHP\Condorcet\Election $election) : void  
* public removeAllTags () : bool  
* public removeCandidate (CondorcetPHP\Condorcet\Candidate|string $candidate) : bool  
* public removeTags (array|string $tags) : array  
* public rewind () : void  
* public setRanking (array|string $ranking, ?float $ownTimestamp = null) : bool  
* public setWeight (int $newWeight) : int  
* public valid () : bool  
* protected computeContextualRankingWithoutImplicit (array $ranking, CondorcetPHP\Condorcet\Election $election, int $countContextualCandidate = 0) : array  
* protected destroyAllLink () : void  
* private archiveRanking () : void  
* private formatRanking (array|string $ranking) : int  
* private setHashCode () : string  
```

#### Abstract CondorcetPHP\Condorcet\VoteConstraint   
```php
* public static isVoteAllow (CondorcetPHP\Condorcet\Election $election, CondorcetPHP\Condorcet\Vote $vote) : bool  
* protected static evaluateVote (array $vote) : bool  
```
