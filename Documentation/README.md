> **[Presentation](../README.md) | [Manual](https://github.com/julien-boudry/Condorcet/wiki) | Methods References | [Tests](../Tests)**

# Public API Index*_
_*: I try to update and complete the documentation. See also [the manual](https://github.com/julien-boudry/Condorcet/wiki), [the tests](../Tests) also produce many examples. And create issues for questions or fixing documentation!_


### CondorcetPHP\Condorcet\Candidate Class  

* [public Candidate->__construct (...)](Candidate%20Class/public%20Candidate--__construct.md)  
* [public Candidate->countLinks ()](Candidate%20Class/public%20Candidate--countLinks.md): ```int```  
* [public Candidate->getCreateTimestamp ()](Candidate%20Class/public%20Candidate--getCreateTimestamp.md): ```float```  
* [public Candidate->getHistory ()](Candidate%20Class/public%20Candidate--getHistory.md): ```array```  
* [public Candidate->getLinks ()](Candidate%20Class/public%20Candidate--getLinks.md): ```WeakMap```  
* [public Candidate->getName ()](Candidate%20Class/public%20Candidate--getName.md): ```string```  
* [public Candidate->getObjectVersion (...)](Candidate%20Class/public%20Candidate--getObjectVersion.md): ```string```  
* [public Candidate->getProvisionalState ()](Candidate%20Class/public%20Candidate--getProvisionalState.md): ```bool```  
* [public Candidate->getTimestamp ()](Candidate%20Class/public%20Candidate--getTimestamp.md): ```float```  
* [public Candidate->haveLink (...)](Candidate%20Class/public%20Candidate--haveLink.md): ```bool```  
* [public Candidate->setName (...)](Candidate%20Class/public%20Candidate--setName.md): ```bool```  

### CondorcetPHP\Condorcet\Condorcet Class  

* ```final public const VERSION: (string)```  
* ```final public const CONDORCET_BASIC_CLASS: (string)```  

* [public static Condorcet::addMethod (...)](Condorcet%20Class/public%20static%20Condorcet--addMethod.md): ```bool```  
* [public static Condorcet::getAuthMethods (...)](Condorcet%20Class/public%20static%20Condorcet--getAuthMethods.md): ```array```  
* [public static Condorcet::getDefaultMethod ()](Condorcet%20Class/public%20static%20Condorcet--getDefaultMethod.md): ```?string```  
* [public static Condorcet::getMethodClass (...)](Condorcet%20Class/public%20static%20Condorcet--getMethodClass.md): ```?string```  
* [public static Condorcet::getVersion (...)](Condorcet%20Class/public%20static%20Condorcet--getVersion.md): ```string```  
* [public static Condorcet::isAuthMethod (...)](Condorcet%20Class/public%20static%20Condorcet--isAuthMethod.md): ```bool```  
* [public static Condorcet::setDefaultMethod (...)](Condorcet%20Class/public%20static%20Condorcet--setDefaultMethod.md): ```bool```  

### CondorcetPHP\Condorcet\CondorcetUtil Class  

* [public static CondorcetUtil::format (...)](CondorcetUtil%20Class/public%20static%20CondorcetUtil--format.md): ```mixed```  

### CondorcetPHP\Condorcet\Election Class  

* ```public const MAX_LENGTH_CANDIDATE_ID: (integer)```  

* [public static Election::setMaxParseIteration (...)](Election%20Class/public%20static%20Election--setMaxParseIteration.md): ```?int```  
* [public static Election::setMaxVoteNumber (...)](Election%20Class/public%20static%20Election--setMaxVoteNumber.md): ```?int```  
* [public Election->__construct ()](Election%20Class/public%20Election--__construct.md)  
* [public Election->addCandidate (...)](Election%20Class/public%20Election--addCandidate.md): ```CondorcetPHP\Condorcet\Candidate```  
* [public Election->addCandidatesFromJson (...)](Election%20Class/public%20Election--addCandidatesFromJson.md): ```array```  
* [public Election->addConstraint (...)](Election%20Class/public%20Election--addConstraint.md): ```bool```  
* [public Election->addVote (...)](Election%20Class/public%20Election--addVote.md): ```CondorcetPHP\Condorcet\Vote```  
* [public Election->addVotesFromJson (...)](Election%20Class/public%20Election--addVotesFromJson.md): ```int```  
* [public Election->allowsVoteWeight (...)](Election%20Class/public%20Election--allowsVoteWeight.md): ```bool```  
* [public Election->canAddCandidate (...)](Election%20Class/public%20Election--canAddCandidate.md): ```bool```  
* [public Election->clearConstraints ()](Election%20Class/public%20Election--clearConstraints.md): ```bool```  
* [public Election->computeResult (...)](Election%20Class/public%20Election--computeResult.md): ```void```  
* [public Election->countCandidates ()](Election%20Class/public%20Election--countCandidates.md): ```int```  
* [public Election->countInvalidVoteWithConstraints ()](Election%20Class/public%20Election--countInvalidVoteWithConstraints.md): ```int```  
* [public Election->countValidVoteWithConstraints ()](Election%20Class/public%20Election--countValidVoteWithConstraints.md): ```int```  
* [public Election->countVotes (...)](Election%20Class/public%20Election--countVotes.md): ```int```  
* [public Election->getCandidateObjectFromName (...)](Election%20Class/public%20Election--getCandidateObjectFromName.md): ```?CondorcetPHP\Condorcet\Candidate```  
* [public Election->getCandidatesList ()](Election%20Class/public%20Election--getCandidatesList.md): ```array```  
* [public Election->getCandidatesListAsString ()](Election%20Class/public%20Election--getCandidatesListAsString.md): ```array```  
* [public Election->getChecksum ()](Election%20Class/public%20Election--getChecksum.md): ```string```  
* [public Election->getCondorcetLoser ()](Election%20Class/public%20Election--getCondorcetLoser.md): ```?CondorcetPHP\Condorcet\Candidate```  
* [public Election->getCondorcetWinner ()](Election%20Class/public%20Election--getCondorcetWinner.md): ```?CondorcetPHP\Condorcet\Candidate```  
* [public Election->getConstraints ()](Election%20Class/public%20Election--getConstraints.md): ```array```  
* [public Election->getExplicitPairwise ()](Election%20Class/public%20Election--getExplicitPairwise.md): ```array```  
* [public Election->getGlobalTimer ()](Election%20Class/public%20Election--getGlobalTimer.md): ```float```  
* [public Election->getImplicitRankingRule ()](Election%20Class/public%20Election--getImplicitRankingRule.md): ```bool```  
* [public Election->getLastTimer ()](Election%20Class/public%20Election--getLastTimer.md): ```float```  
* [public Election->getLoser (...)](Election%20Class/public%20Election--getLoser.md): ```CondorcetPHP\Condorcet\Candidate|array|null```  
* [public Election->getNumberOfSeats ()](Election%20Class/public%20Election--getNumberOfSeats.md): ```int```  
* [public Election->getObjectVersion (...)](Election%20Class/public%20Election--getObjectVersion.md): ```string```  
* [public Election->getPairwise ()](Election%20Class/public%20Election--getPairwise.md): ```CondorcetPHP\Condorcet\Algo\Pairwise```  
* [public Election->getResult (...)](Election%20Class/public%20Election--getResult.md): ```CondorcetPHP\Condorcet\Result```  
* [public Election->getState ()](Election%20Class/public%20Election--getState.md): ```CondorcetPHP\Condorcet\ElectionProcess\ElectionState```  
* [public Election->getTimerManager ()](Election%20Class/public%20Election--getTimerManager.md): ```CondorcetPHP\Condorcet\Timer\Manager```  
* [public Election->getVotesList (...)](Election%20Class/public%20Election--getVotesList.md): ```array```  
* [public Election->getVotesListAsString (...)](Election%20Class/public%20Election--getVotesListAsString.md): ```string```  
* [public Election->getVotesListGenerator (...)](Election%20Class/public%20Election--getVotesListGenerator.md): ```Generator```  
* [public Election->getVotesValidUnderConstraintGenerator (...)](Election%20Class/public%20Election--getVotesValidUnderConstraintGenerator.md): ```Generator```  
* [public Election->getWinner (...)](Election%20Class/public%20Election--getWinner.md): ```CondorcetPHP\Condorcet\Candidate|array|null```  
* [public Election->isRegisteredCandidate (...)](Election%20Class/public%20Election--isRegisteredCandidate.md): ```bool```  
* [public Election->isVoteWeightAllowed ()](Election%20Class/public%20Election--isVoteWeightAllowed.md): ```bool```  
* [public Election->parseCandidates (...)](Election%20Class/public%20Election--parseCandidates.md): ```array```  
* [public Election->parseVotes (...)](Election%20Class/public%20Election--parseVotes.md): ```int```  
* [public Election->parseVotesWithoutFail (...)](Election%20Class/public%20Election--parseVotesWithoutFail.md): ```int```  
* [public Election->removeCandidates (...)](Election%20Class/public%20Election--removeCandidates.md): ```array```  
* [public Election->removeExternalDataHandler ()](Election%20Class/public%20Election--removeExternalDataHandler.md): ```bool```  
* [public Election->removeVote (...)](Election%20Class/public%20Election--removeVote.md): ```bool```  
* [public Election->removeVotesByTags (...)](Election%20Class/public%20Election--removeVotesByTags.md): ```array```  
* [public Election->setExternalDataHandler (...)](Election%20Class/public%20Election--setExternalDataHandler.md): ```bool```  
* [public Election->setImplicitRanking (...)](Election%20Class/public%20Election--setImplicitRanking.md): ```bool```  
* [public Election->setMethodOption (...)](Election%20Class/public%20Election--setMethodOption.md): ```bool```  
* [public Election->setNumberOfSeats (...)](Election%20Class/public%20Election--setNumberOfSeats.md): ```int```  
* [public Election->setStateToVote ()](Election%20Class/public%20Election--setStateToVote.md): ```bool```  
* [public Election->sumValidVotesWeightWithConstraints ()](Election%20Class/public%20Election--sumValidVotesWeightWithConstraints.md): ```int```  
* [public Election->sumVotesWeight ()](Election%20Class/public%20Election--sumVotesWeight.md): ```int```  
* [public Election->testIfVoteIsValidUnderElectionConstraints (...)](Election%20Class/public%20Election--testIfVoteIsValidUnderElectionConstraints.md): ```bool```  

### CondorcetPHP\Condorcet\Result Class  

* ```readonly public array $ranking```  
* ```readonly public array $rankingAsString```  
* ```readonly public ?int $seats```  
* ```readonly public array $methodOptions```  
* ```readonly public ?CondorcetPHP\Condorcet\Candidate $CondorcetWinner```  
* ```readonly public ?CondorcetPHP\Condorcet\Candidate $CondorcetLoser```  
* ```readonly public float $buildTimestamp```  
* ```readonly public string $fromMethod```  
* ```readonly public string $byClass```  
* ```readonly public string $electionCondorcetVersion```  

* [public Result->getBuildTimeStamp ()](Result%20Class/public%20Result--getBuildTimeStamp.md): ```float```  
* [public Result->getClassGenerator ()](Result%20Class/public%20Result--getClassGenerator.md): ```string```  
* [public Result->getCondorcetElectionGeneratorVersion ()](Result%20Class/public%20Result--getCondorcetElectionGeneratorVersion.md): ```string```  
* [public Result->getCondorcetLoser ()](Result%20Class/public%20Result--getCondorcetLoser.md): ```?CondorcetPHP\Condorcet\Candidate```  
* [public Result->getCondorcetWinner ()](Result%20Class/public%20Result--getCondorcetWinner.md): ```?CondorcetPHP\Condorcet\Candidate```  
* [public Result->getLoser ()](Result%20Class/public%20Result--getLoser.md): ```CondorcetPHP\Condorcet\Candidate|array|null```  
* [public Result->getMethod ()](Result%20Class/public%20Result--getMethod.md): ```string```  
* [public Result->getMethodOptions ()](Result%20Class/public%20Result--getMethodOptions.md): ```array```  
* [public Result->getNumberOfSeats ()](Result%20Class/public%20Result--getNumberOfSeats.md): ```?int```  
* [public Result->getObjectVersion (...)](Result%20Class/public%20Result--getObjectVersion.md): ```string```  
* [public Result->getOriginalResultArrayWithString ()](Result%20Class/public%20Result--getOriginalResultArrayWithString.md): ```array```  
* [public Result->getResultAsArray (...)](Result%20Class/public%20Result--getResultAsArray.md): ```array```  
* [public Result->getResultAsString ()](Result%20Class/public%20Result--getResultAsString.md): ```string```  
* [public Result->getStats ()](Result%20Class/public%20Result--getStats.md): ```mixed```  
* [public Result->getWarning (...)](Result%20Class/public%20Result--getWarning.md): ```array```  
* [public Result->getWinner ()](Result%20Class/public%20Result--getWinner.md): ```CondorcetPHP\Condorcet\Candidate|array|null```  
* [public Result->isProportional ()](Result%20Class/public%20Result--isProportional.md): ```bool```  

### CondorcetPHP\Condorcet\Vote Class  

* [public Vote->__construct (...)](Vote%20Class/public%20Vote--__construct.md)  
* [public Vote->addTags (...)](Vote%20Class/public%20Vote--addTags.md): ```bool```  
* [public Vote->countLinks ()](Vote%20Class/public%20Vote--countLinks.md): ```int```  
* [public Vote->countRankingCandidates ()](Vote%20Class/public%20Vote--countRankingCandidates.md): ```int```  
* [public Vote->getAllCandidates ()](Vote%20Class/public%20Vote--getAllCandidates.md): ```array```  
* [public Vote->getContextualRanking (...)](Vote%20Class/public%20Vote--getContextualRanking.md): ```array```  
* [public Vote->getContextualRankingAsString (...)](Vote%20Class/public%20Vote--getContextualRankingAsString.md): ```array```  
* [public Vote->getCreateTimestamp ()](Vote%20Class/public%20Vote--getCreateTimestamp.md): ```float```  
* [public Vote->getHashCode ()](Vote%20Class/public%20Vote--getHashCode.md): ```string```  
* [public Vote->getHistory ()](Vote%20Class/public%20Vote--getHistory.md): ```array```  
* [public Vote->getLinks ()](Vote%20Class/public%20Vote--getLinks.md): ```WeakMap```  
* [public Vote->getObjectVersion (...)](Vote%20Class/public%20Vote--getObjectVersion.md): ```string```  
* [public Vote->getRanking ()](Vote%20Class/public%20Vote--getRanking.md): ```array```  
* [public Vote->getSimpleRanking (...)](Vote%20Class/public%20Vote--getSimpleRanking.md): ```string```  
* [public Vote->getTags ()](Vote%20Class/public%20Vote--getTags.md): ```array```  
* [public Vote->getTagsAsString ()](Vote%20Class/public%20Vote--getTagsAsString.md): ```string```  
* [public Vote->getTimestamp ()](Vote%20Class/public%20Vote--getTimestamp.md): ```float```  
* [public Vote->getWeight (...)](Vote%20Class/public%20Vote--getWeight.md): ```int```  
* [public Vote->haveLink (...)](Vote%20Class/public%20Vote--haveLink.md): ```bool```  
* [public Vote->removeAllTags ()](Vote%20Class/public%20Vote--removeAllTags.md): ```bool```  
* [public Vote->removeCandidate (...)](Vote%20Class/public%20Vote--removeCandidate.md): ```bool```  
* [public Vote->removeTags (...)](Vote%20Class/public%20Vote--removeTags.md): ```array```  
* [public Vote->setRanking (...)](Vote%20Class/public%20Vote--setRanking.md): ```bool```  
* [public Vote->setWeight (...)](Vote%20Class/public%20Vote--setWeight.md): ```int```  

### CondorcetPHP\Condorcet\Algo\Pairwise Class  

* [public Algo\Pairwise->getExplicitPairwise ()](Algo_Pairwise%20Class/public%20Algo_Pairwise--getExplicitPairwise.md): ```array```  
* [public Algo\Pairwise->getObjectVersion (...)](Algo_Pairwise%20Class/public%20Algo_Pairwise--getObjectVersion.md): ```string```  

### CondorcetPHP\Condorcet\Algo\Tools\StvQuotas Enum  

* ```case Algo\Tools\StvQuotas::DROOP```  
* ```case Algo\Tools\StvQuotas::HARE```  
* ```case Algo\Tools\StvQuotas::HAGENBACH_BISCHOFF```  
* ```case Algo\Tools\StvQuotas::IMPERIALI```  

* [public static Algo\Tools\StvQuotas::make (...)](Algo_Tools_StvQuotas%20Class/public%20static%20Algo_Tools_StvQuotas--make.md): ```self```  

### CondorcetPHP\Condorcet\DataManager\DataHandlerDrivers\PdoDriver\PdoHandlerDriver Class  

* ```public const SEGMENT: (array)```  

* ```public static bool $preferBlobInsteadVarchar```  


### CondorcetPHP\Condorcet\Timer\Manager Class  

* [public Timer\Manager->getHistory ()](Timer_Manager%20Class/public%20Timer_Manager--getHistory.md): ```array```  
* [public Timer\Manager->getObjectVersion (...)](Timer_Manager%20Class/public%20Timer_Manager--getObjectVersion.md): ```string```  

### CondorcetPHP\Condorcet\Tools\Converters\CondorcetElectionFormat Class  

* ```readonly public array $candidates```  
* ```readonly public int $numberOfSeats```  
* ```readonly public bool $implicitRanking```  
* ```readonly public bool $voteWeight```  
* ```readonly public bool $CandidatesParsedFromVotes```  
* ```readonly public int $invalidBlocksCount```  

* [public static Tools\Converters\CondorcetElectionFormat::exportElectionToCondorcetElectionFormat (...)](Tools_Converters_CondorcetElectionFormat%20Class/public%20static%20Tools_Converters_CondorcetElectionFormat--exportElectionToCondorcetElectionFormat.md): ```?string```  
* [public Tools\Converters\CondorcetElectionFormat->__construct (...)](Tools_Converters_CondorcetElectionFormat%20Class/public%20Tools_Converters_CondorcetElectionFormat--__construct.md)  
* [public Tools\Converters\CondorcetElectionFormat->setDataToAnElection (...)](Tools_Converters_CondorcetElectionFormat%20Class/public%20Tools_Converters_CondorcetElectionFormat--setDataToAnElection.md): ```CondorcetPHP\Condorcet\Election```  

### CondorcetPHP\Condorcet\Tools\Converters\DavidHillFormat Class  

* ```readonly public array $candidates```  
* ```readonly public int $NumberOfSeats```  

* [public Tools\Converters\DavidHillFormat->__construct (...)](Tools_Converters_DavidHillFormat%20Class/public%20Tools_Converters_DavidHillFormat--__construct.md)  
* [public Tools\Converters\DavidHillFormat->setDataToAnElection (...)](Tools_Converters_DavidHillFormat%20Class/public%20Tools_Converters_DavidHillFormat--setDataToAnElection.md): ```CondorcetPHP\Condorcet\Election```  

### CondorcetPHP\Condorcet\Tools\Converters\DebianFormat Class  

* ```readonly public array $candidates```  
* ```readonly public array $votes```  

* [public Tools\Converters\DebianFormat->__construct (...)](Tools_Converters_DebianFormat%20Class/public%20Tools_Converters_DebianFormat--__construct.md)  
* [public Tools\Converters\DebianFormat->setDataToAnElection (...)](Tools_Converters_DebianFormat%20Class/public%20Tools_Converters_DebianFormat--setDataToAnElection.md): ```CondorcetPHP\Condorcet\Election```  



# Full Class & Methods References
_Including above methods from public API_


#### Abstract CondorcetPHP\Condorcet\Algo\Method   
```php
* public const IS_PROPORTIONAL: (boolean)
* public const DECIMAL_PRECISION: (integer)

* public static ?int $MaxCandidates
* readonly protected WeakReference $_selfElection
* protected ?CondorcetPHP\Condorcet\Result $_Result
* protected string $_objectVersion

* public static setOption (string $optionName, BackedEnum|int $optionValue): bool  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public getElection (): CondorcetPHP\Condorcet\Election  
* public getObjectVersion (bool $major = false): string  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): array  
```

#### CondorcetPHP\Condorcet\Algo\Methods\Borda\BordaCount extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface  
```php
* public const METHOD_NAME: (array)
* public const IS_PROPORTIONAL: (boolean)
* public const DECIMAL_PRECISION: (integer)

* public static int $optionStarting
* protected ?array $_Stats
* public static ?int $MaxCandidates
* readonly protected WeakReference $_selfElection
* protected ?CondorcetPHP\Condorcet\Result $_Result
* protected string $_objectVersion

* public static setOption (string $optionName, BackedEnum|int $optionValue): bool  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public getElection (): CondorcetPHP\Condorcet\Election  
* public getObjectVersion (bool $major = false): string  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected compute (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getScoreByCandidateRanking (int $CandidatesRanked): float  
* protected getStats (): array  
```

#### CondorcetPHP\Condorcet\Algo\Methods\Borda\DowdallSystem extends CondorcetPHP\Condorcet\Algo\Methods\Borda\BordaCount implements CondorcetPHP\Condorcet\Algo\MethodInterface  
```php
* public const METHOD_NAME: (array)
* public const IS_PROPORTIONAL: (boolean)
* public const DECIMAL_PRECISION: (integer)

* public static int $optionStarting
* protected ?array $_Stats
* public static ?int $MaxCandidates
* readonly protected WeakReference $_selfElection
* protected ?CondorcetPHP\Condorcet\Result $_Result
* protected string $_objectVersion

* public static setOption (string $optionName, BackedEnum|int $optionValue): bool  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public getElection (): CondorcetPHP\Condorcet\Election  
* public getObjectVersion (bool $major = false): string  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected compute (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getScoreByCandidateRanking (int $CandidatesRanked): float  
* protected getStats (): array  
```

#### CondorcetPHP\Condorcet\Algo\Methods\CondorcetBasic extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface  
```php
* public const METHOD_NAME: (array)
* public const IS_PROPORTIONAL: (boolean)
* public const DECIMAL_PRECISION: (integer)

* protected ?int $_CondorcetWinner
* protected ?int $_CondorcetLoser
* public static ?int $MaxCandidates
* readonly protected WeakReference $_selfElection
* protected ?CondorcetPHP\Condorcet\Result $_Result
* protected string $_objectVersion

* public static setOption (string $optionName, BackedEnum|int $optionValue): bool  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public getElection (): CondorcetPHP\Condorcet\Election  
* public getLoser (): ?int  
* public getObjectVersion (bool $major = false): string  
* public getResult (): never  
* public getWinner (): ?int  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): array  
```

#### CondorcetPHP\Condorcet\Algo\Methods\Copeland\Copeland extends CondorcetPHP\Condorcet\Algo\Methods\PairwiseStatsBased_Core implements CondorcetPHP\Condorcet\Algo\MethodInterface  
```php
* public const METHOD_NAME: (array)
* protected const COUNT_TYPE: (string)
* public const IS_PROPORTIONAL: (boolean)
* public const DECIMAL_PRECISION: (integer)

* readonly protected array $_Comparison
* public static ?int $MaxCandidates
* readonly protected WeakReference $_selfElection
* protected ?CondorcetPHP\Condorcet\Result $_Result
* protected string $_objectVersion

* public static setOption (string $optionName, BackedEnum|int $optionValue): bool  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public getElection (): CondorcetPHP\Condorcet\Election  
* public getObjectVersion (bool $major = false): string  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): array  
* protected looking (array $challenge): int  
* protected makeRanking (): void  
```

#### CondorcetPHP\Condorcet\Algo\Methods\Dodgson\DodgsonQuick extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface  
```php
* public const METHOD_NAME: (array)
* public const IS_PROPORTIONAL: (boolean)
* public const DECIMAL_PRECISION: (integer)

* protected ?array $_Stats
* public static ?int $MaxCandidates
* readonly protected WeakReference $_selfElection
* protected ?CondorcetPHP\Condorcet\Result $_Result
* protected string $_objectVersion

* public static setOption (string $optionName, BackedEnum|int $optionValue): bool  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public getElection (): CondorcetPHP\Condorcet\Election  
* public getObjectVersion (bool $major = false): string  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected compute (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): array  
```

#### CondorcetPHP\Condorcet\Algo\Methods\Dodgson\DodgsonTidemanApproximation extends CondorcetPHP\Condorcet\Algo\Methods\PairwiseStatsBased_Core implements CondorcetPHP\Condorcet\Algo\MethodInterface  
```php
* public const METHOD_NAME: (array)
* protected const COUNT_TYPE: (string)
* public const IS_PROPORTIONAL: (boolean)
* public const DECIMAL_PRECISION: (integer)

* readonly protected array $_Comparison
* public static ?int $MaxCandidates
* readonly protected WeakReference $_selfElection
* protected ?CondorcetPHP\Condorcet\Result $_Result
* protected string $_objectVersion

* public static setOption (string $optionName, BackedEnum|int $optionValue): bool  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public getElection (): CondorcetPHP\Condorcet\Election  
* public getObjectVersion (bool $major = false): string  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): array  
* protected looking (array $challenge): int  
* protected makeRanking (): void  
```

#### CondorcetPHP\Condorcet\Algo\Methods\InstantRunoff\InstantRunoff extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface  
```php
* public const METHOD_NAME: (array)
* public const IS_PROPORTIONAL: (boolean)
* public const DECIMAL_PRECISION: (integer)

* protected ?array $_Stats
* readonly public float $majority
* public static ?int $MaxCandidates
* readonly protected WeakReference $_selfElection
* protected ?CondorcetPHP\Condorcet\Result $_Result
* protected string $_objectVersion

* public static setOption (string $optionName, BackedEnum|int $optionValue): bool  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public getElection (): CondorcetPHP\Condorcet\Election  
* public getObjectVersion (bool $major = false): string  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected compute (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): array  
* protected makeScore (array $candidateDone): array  
```

#### CondorcetPHP\Condorcet\Algo\Methods\KemenyYoung\KemenyYoung extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface  
```php
* public const METHOD_NAME: (array)
* final public const CONFLICT_WARNING_CODE: (integer)
* public const IS_PROPORTIONAL: (boolean)
* public const DECIMAL_PRECISION: (integer)

* public static ?int $MaxCandidates
* public static bool $devWriteCache
* protected array $_PossibleRanking
* protected array $_RankingScore
* readonly protected WeakReference $_selfElection
* protected ?CondorcetPHP\Condorcet\Result $_Result
* protected string $_objectVersion

* public static setOption (string $optionName, BackedEnum|int $optionValue): bool  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public getElection (): CondorcetPHP\Condorcet\Election  
* public getObjectVersion (bool $major = false): string  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected calcPossibleRanking (): void  
* protected calcRankingScore (): void  
* protected conflictInfos (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): array  
* protected makeRanking (): void  
```

#### CondorcetPHP\Condorcet\Algo\Methods\Majority\FirstPastThePost extends CondorcetPHP\Condorcet\Algo\Methods\Majority\Majority_Core implements CondorcetPHP\Condorcet\Algo\MethodInterface  
```php
* public const METHOD_NAME: (array)
* public const IS_PROPORTIONAL: (boolean)
* public const DECIMAL_PRECISION: (integer)

* protected int $_maxRound
* protected int $_targetNumberOfCandidatesForTheNextRound
* protected int $_numberOfTargetedCandidatesAfterEachRound
* protected array $_admittedCandidates
* protected ?array $_Stats
* public static ?int $MaxCandidates
* readonly protected WeakReference $_selfElection
* protected ?CondorcetPHP\Condorcet\Result $_Result
* protected string $_objectVersion

* public static setOption (string $optionName, BackedEnum|int $optionValue): bool  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public getElection (): CondorcetPHP\Condorcet\Election  
* public getObjectVersion (bool $major = false): string  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected compute (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected doOneRound (): array  
* protected getStats (): array  
```

#### Abstract CondorcetPHP\Condorcet\Algo\Methods\Majority\Majority_Core extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface  
```php
* public const IS_PROPORTIONAL: (boolean)
* public const DECIMAL_PRECISION: (integer)

* protected int $_maxRound
* protected int $_targetNumberOfCandidatesForTheNextRound
* protected int $_numberOfTargetedCandidatesAfterEachRound
* protected array $_admittedCandidates
* protected ?array $_Stats
* public static ?int $MaxCandidates
* readonly protected WeakReference $_selfElection
* protected ?CondorcetPHP\Condorcet\Result $_Result
* protected string $_objectVersion

* public static setOption (string $optionName, BackedEnum|int $optionValue): bool  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public getElection (): CondorcetPHP\Condorcet\Election  
* public getObjectVersion (bool $major = false): string  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected compute (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected doOneRound (): array  
* protected getStats (): array  
```

#### CondorcetPHP\Condorcet\Algo\Methods\Majority\MultipleRoundsSystem extends CondorcetPHP\Condorcet\Algo\Methods\Majority\Majority_Core implements CondorcetPHP\Condorcet\Algo\MethodInterface  
```php
* public const METHOD_NAME: (array)
* public const IS_PROPORTIONAL: (boolean)
* public const DECIMAL_PRECISION: (integer)

* protected static int $optionMAX_ROUND
* protected static int $optionTARGET_NUMBER_OF_CANDIDATES_FOR_THE_NEXT_ROUND
* protected static int $optionNUMBER_OF_TARGETED_CANDIDATES_AFTER_EACH_ROUND
* protected int $_maxRound
* protected int $_targetNumberOfCandidatesForTheNextRound
* protected int $_numberOfTargetedCandidatesAfterEachRound
* protected array $_admittedCandidates
* protected ?array $_Stats
* public static ?int $MaxCandidates
* readonly protected WeakReference $_selfElection
* protected ?CondorcetPHP\Condorcet\Result $_Result
* protected string $_objectVersion

* public static setOption (string $optionName, BackedEnum|int $optionValue): bool  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public getElection (): CondorcetPHP\Condorcet\Election  
* public getObjectVersion (bool $major = false): string  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected compute (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected doOneRound (): array  
* protected getStats (): array  
```

#### CondorcetPHP\Condorcet\Algo\Methods\Minimax\MinimaxMargin extends CondorcetPHP\Condorcet\Algo\Methods\PairwiseStatsBased_Core implements CondorcetPHP\Condorcet\Algo\MethodInterface  
```php
* public const METHOD_NAME: (array)
* protected const COUNT_TYPE: (string)
* public const IS_PROPORTIONAL: (boolean)
* public const DECIMAL_PRECISION: (integer)

* readonly protected array $_Comparison
* public static ?int $MaxCandidates
* readonly protected WeakReference $_selfElection
* protected ?CondorcetPHP\Condorcet\Result $_Result
* protected string $_objectVersion

* public static setOption (string $optionName, BackedEnum|int $optionValue): bool  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public getElection (): CondorcetPHP\Condorcet\Election  
* public getObjectVersion (bool $major = false): string  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): array  
* protected looking (array $challenge): int  
* protected makeRanking (): void  
```

#### CondorcetPHP\Condorcet\Algo\Methods\Minimax\MinimaxOpposition extends CondorcetPHP\Condorcet\Algo\Methods\PairwiseStatsBased_Core implements CondorcetPHP\Condorcet\Algo\MethodInterface  
```php
* public const METHOD_NAME: (array)
* protected const COUNT_TYPE: (string)
* public const IS_PROPORTIONAL: (boolean)
* public const DECIMAL_PRECISION: (integer)

* readonly protected array $_Comparison
* public static ?int $MaxCandidates
* readonly protected WeakReference $_selfElection
* protected ?CondorcetPHP\Condorcet\Result $_Result
* protected string $_objectVersion

* public static setOption (string $optionName, BackedEnum|int $optionValue): bool  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public getElection (): CondorcetPHP\Condorcet\Election  
* public getObjectVersion (bool $major = false): string  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): array  
* protected looking (array $challenge): int  
* protected makeRanking (): void  
```

#### CondorcetPHP\Condorcet\Algo\Methods\Minimax\MinimaxWinning extends CondorcetPHP\Condorcet\Algo\Methods\PairwiseStatsBased_Core implements CondorcetPHP\Condorcet\Algo\MethodInterface  
```php
* public const METHOD_NAME: (array)
* protected const COUNT_TYPE: (string)
* public const IS_PROPORTIONAL: (boolean)
* public const DECIMAL_PRECISION: (integer)

* readonly protected array $_Comparison
* public static ?int $MaxCandidates
* readonly protected WeakReference $_selfElection
* protected ?CondorcetPHP\Condorcet\Result $_Result
* protected string $_objectVersion

* public static setOption (string $optionName, BackedEnum|int $optionValue): bool  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public getElection (): CondorcetPHP\Condorcet\Election  
* public getObjectVersion (bool $major = false): string  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): array  
* protected looking (array $challenge): int  
* protected makeRanking (): void  
```

#### Abstract CondorcetPHP\Condorcet\Algo\Methods\PairwiseStatsBased_Core extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface  
```php
* public const IS_PROPORTIONAL: (boolean)
* public const DECIMAL_PRECISION: (integer)

* readonly protected array $_Comparison
* public static ?int $MaxCandidates
* readonly protected WeakReference $_selfElection
* protected ?CondorcetPHP\Condorcet\Result $_Result
* protected string $_objectVersion

* public static setOption (string $optionName, BackedEnum|int $optionValue): bool  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public getElection (): CondorcetPHP\Condorcet\Election  
* public getObjectVersion (bool $major = false): string  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): array  
* protected looking (array $challenge): int  
* protected makeRanking (): void  
```

#### CondorcetPHP\Condorcet\Algo\Methods\RankedPairs\RankedPairsMargin extends CondorcetPHP\Condorcet\Algo\Methods\RankedPairs\RankedPairs_Core implements CondorcetPHP\Condorcet\Algo\MethodInterface  
```php
* public const METHOD_NAME: (array)
* protected const RP_VARIANT_1: (string)
* public const IS_PROPORTIONAL: (boolean)
* public const DECIMAL_PRECISION: (integer)

* public static ?int $MaxCandidates
* readonly protected array $_PairwiseSort
* protected array $_Arcs
* protected ?array $_Stats
* protected bool $_StatsDone
* readonly protected WeakReference $_selfElection
* protected ?CondorcetPHP\Condorcet\Result $_Result
* protected string $_objectVersion

* public static setOption (string $optionName, BackedEnum|int $optionValue): bool  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public getElection (): CondorcetPHP\Condorcet\Election  
* public getObjectVersion (bool $major = false): string  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected followCycle (array $virtualArcs, int $startCandidateKey, int $searchCandidateKey, array $done = []): array  
* protected getArcsInCycle (array $virtualArcs): array  
* protected getStats (): array  
* protected getWinners (array $alreadyDone): array  
* protected makeArcs (): void  
* protected makeResult (): array  
* protected pairwiseSort (): array  
```

#### CondorcetPHP\Condorcet\Algo\Methods\RankedPairs\RankedPairsWinning extends CondorcetPHP\Condorcet\Algo\Methods\RankedPairs\RankedPairs_Core implements CondorcetPHP\Condorcet\Algo\MethodInterface  
```php
* public const METHOD_NAME: (array)
* protected const RP_VARIANT_1: (string)
* public const IS_PROPORTIONAL: (boolean)
* public const DECIMAL_PRECISION: (integer)

* public static ?int $MaxCandidates
* readonly protected array $_PairwiseSort
* protected array $_Arcs
* protected ?array $_Stats
* protected bool $_StatsDone
* readonly protected WeakReference $_selfElection
* protected ?CondorcetPHP\Condorcet\Result $_Result
* protected string $_objectVersion

* public static setOption (string $optionName, BackedEnum|int $optionValue): bool  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public getElection (): CondorcetPHP\Condorcet\Election  
* public getObjectVersion (bool $major = false): string  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected followCycle (array $virtualArcs, int $startCandidateKey, int $searchCandidateKey, array $done = []): array  
* protected getArcsInCycle (array $virtualArcs): array  
* protected getStats (): array  
* protected getWinners (array $alreadyDone): array  
* protected makeArcs (): void  
* protected makeResult (): array  
* protected pairwiseSort (): array  
```

#### Abstract CondorcetPHP\Condorcet\Algo\Methods\RankedPairs\RankedPairs_Core extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface  
```php
* public const IS_PROPORTIONAL: (boolean)
* public const DECIMAL_PRECISION: (integer)

* public static ?int $MaxCandidates
* readonly protected array $_PairwiseSort
* protected array $_Arcs
* protected ?array $_Stats
* protected bool $_StatsDone
* readonly protected WeakReference $_selfElection
* protected ?CondorcetPHP\Condorcet\Result $_Result
* protected string $_objectVersion

* public static setOption (string $optionName, BackedEnum|int $optionValue): bool  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public getElection (): CondorcetPHP\Condorcet\Election  
* public getObjectVersion (bool $major = false): string  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected followCycle (array $virtualArcs, int $startCandidateKey, int $searchCandidateKey, array $done = []): array  
* protected getArcsInCycle (array $virtualArcs): array  
* protected getStats (): array  
* protected getWinners (array $alreadyDone): array  
* protected makeArcs (): void  
* protected makeResult (): array  
* protected pairwiseSort (): array  
```

#### CondorcetPHP\Condorcet\Algo\Methods\STV\SingleTransferableVote extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface  
```php
* final public const IS_PROPORTIONAL: (boolean)
* public const METHOD_NAME: (array)
* public const DECIMAL_PRECISION: (integer)

* public static CondorcetPHP\Condorcet\Algo\Tools\StvQuotas $optionQuota
* protected ?array $_Stats
* readonly protected float $votesNeededToWin
* public static ?int $MaxCandidates
* readonly protected WeakReference $_selfElection
* protected ?CondorcetPHP\Condorcet\Result $_Result
* protected string $_objectVersion

* public static setOption (string $optionName, BackedEnum|int $optionValue): bool  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public getElection (): CondorcetPHP\Condorcet\Election  
* public getObjectVersion (bool $major = false): string  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected compute (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): array  
* protected makeScore (array $surplus, array $candidateElected, array $candidateEliminated): array  
```

#### CondorcetPHP\Condorcet\Algo\Methods\Schulze\SchulzeMargin extends CondorcetPHP\Condorcet\Algo\Methods\Schulze\Schulze_Core implements CondorcetPHP\Condorcet\Algo\MethodInterface  
```php
* public const METHOD_NAME: (array)
* public const IS_PROPORTIONAL: (boolean)
* public const DECIMAL_PRECISION: (integer)

* protected array $_StrongestPaths
* public static ?int $MaxCandidates
* readonly protected WeakReference $_selfElection
* protected ?CondorcetPHP\Condorcet\Result $_Result
* protected string $_objectVersion

* public static setOption (string $optionName, BackedEnum|int $optionValue): bool  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public getElection (): CondorcetPHP\Condorcet\Election  
* public getObjectVersion (bool $major = false): string  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): array  
* protected makeRanking (): void  
* protected makeStrongestPaths (): void  
* protected prepareStrongestPath (): void  
* protected schulzeVariant (int $i, int $j): int  
```

#### CondorcetPHP\Condorcet\Algo\Methods\Schulze\SchulzeRatio extends CondorcetPHP\Condorcet\Algo\Methods\Schulze\Schulze_Core implements CondorcetPHP\Condorcet\Algo\MethodInterface  
```php
* public const METHOD_NAME: (array)
* public const IS_PROPORTIONAL: (boolean)
* public const DECIMAL_PRECISION: (integer)

* protected array $_StrongestPaths
* public static ?int $MaxCandidates
* readonly protected WeakReference $_selfElection
* protected ?CondorcetPHP\Condorcet\Result $_Result
* protected string $_objectVersion

* public static setOption (string $optionName, BackedEnum|int $optionValue): bool  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public getElection (): CondorcetPHP\Condorcet\Election  
* public getObjectVersion (bool $major = false): string  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): array  
* protected makeRanking (): void  
* protected makeStrongestPaths (): void  
* protected prepareStrongestPath (): void  
* protected schulzeVariant (int $i, int $j): float  
```

#### CondorcetPHP\Condorcet\Algo\Methods\Schulze\SchulzeWinning extends CondorcetPHP\Condorcet\Algo\Methods\Schulze\Schulze_Core implements CondorcetPHP\Condorcet\Algo\MethodInterface  
```php
* public const METHOD_NAME: (array)
* public const IS_PROPORTIONAL: (boolean)
* public const DECIMAL_PRECISION: (integer)

* protected array $_StrongestPaths
* public static ?int $MaxCandidates
* readonly protected WeakReference $_selfElection
* protected ?CondorcetPHP\Condorcet\Result $_Result
* protected string $_objectVersion

* public static setOption (string $optionName, BackedEnum|int $optionValue): bool  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public getElection (): CondorcetPHP\Condorcet\Election  
* public getObjectVersion (bool $major = false): string  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): array  
* protected makeRanking (): void  
* protected makeStrongestPaths (): void  
* protected prepareStrongestPath (): void  
* protected schulzeVariant (int $i, int $j): int  
```

#### Abstract CondorcetPHP\Condorcet\Algo\Methods\Schulze\Schulze_Core extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface  
```php
* public const IS_PROPORTIONAL: (boolean)
* public const DECIMAL_PRECISION: (integer)

* protected array $_StrongestPaths
* public static ?int $MaxCandidates
* readonly protected WeakReference $_selfElection
* protected ?CondorcetPHP\Condorcet\Result $_Result
* protected string $_objectVersion

* public static setOption (string $optionName, BackedEnum|int $optionValue): bool  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public getElection (): CondorcetPHP\Condorcet\Election  
* public getObjectVersion (bool $major = false): string  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): array  
* protected makeRanking (): void  
* protected makeStrongestPaths (): void  
* protected prepareStrongestPath (): void  
* protected schulzeVariant (int $i, int $j)  
```

#### CondorcetPHP\Condorcet\Algo\Pairwise implements ArrayAccess, Iterator, Traversable  
```php
* private bool $valid
* protected WeakReference $_Election
* protected array $_Pairwise_Model
* protected array $_Pairwise
* protected string $_objectVersion

* public __construct (CondorcetPHP\Condorcet\Election $link)  
* public __serialize (): array  
* public addNewVote (int $key): void  
* public current (): array  
* public getElection (): CondorcetPHP\Condorcet\Election  
* public getExplicitPairwise (): array  
* public getObjectVersion (bool $major = false): string  
* public key (): ?int  
* public next (): void  
* public offsetExists (mixed $offset): bool  
* public offsetGet (mixed $offset): ?array  
* public offsetSet (mixed $offset, mixed $value): void  
* public offsetUnset (mixed $offset): void  
* public removeVote (int $key): void  
* public rewind (): void  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* public valid (): bool  
* protected computeOneVote (array $pairwise, CondorcetPHP\Condorcet\Vote $oneVote): void  
* protected doPairwise (): void  
* protected formatNewpairwise (): void  
```

#### Abstract CondorcetPHP\Condorcet\Algo\Tools\PairwiseStats   
```php
* public static PairwiseComparison (CondorcetPHP\Condorcet\Algo\Pairwise $pairwise): array  
```

#### CondorcetPHP\Condorcet\Algo\Tools\Permutation   
```php
* readonly protected int $arr_count
* protected array $results

* public static countPossiblePermutations (int $candidatesNumber): int  
* public __construct (int $arr_count)  
* public getResults (): array  
* public writeResults (string $path): void  
* protected createCandidates (): array  
* private _exec (array|int $a, array $i = []): void  
* private _permute (array $arr): array|int  
```

#### CondorcetPHP\Condorcet\Algo\Tools\StvQuotas implements UnitEnum, BackedEnum  
```php
* ```case StvQuotas::DROOP```  
* ```case StvQuotas::HARE```  
* ```case StvQuotas::HAGENBACH_BISCHOFF```  
* ```case StvQuotas::IMPERIALI```  

* readonly public string $name
* readonly public string $value

* public static make (string $quota): self  
* public getQuota (int $votesWeight, int $seats): float  
```

#### Abstract CondorcetPHP\Condorcet\Algo\Tools\TieBreakersCollection   
```php
* public static tieBreaker_1 (CondorcetPHP\Condorcet\Election $election, array $candidatesKeys): array  
```

#### Abstract CondorcetPHP\Condorcet\Algo\Tools\VirtualVote   
```php
* public static removeCandidates (CondorcetPHP\Condorcet\Vote $vote, array $candidatesList): CondorcetPHP\Condorcet\Vote  
```

#### CondorcetPHP\Condorcet\Candidate implements Stringable  
```php
* private array $_name
* private bool $_provisional
* private ?WeakMap $_link
* protected string $_objectVersion

* public __clone (): void  
* public __construct (string $name)  
* public __serialize (): array  
* public __toString (): string  
* public countLinks (): int  
* public destroyLink (CondorcetPHP\Condorcet\Election $election): bool  
* public getCreateTimestamp (): float  
* public getHistory (): array  
* public getLinks (): WeakMap  
* public getName (): string  
* public getObjectVersion (bool $major = false): string  
* public getProvisionalState (): bool  
* public getTimestamp (): float  
* public haveLink (CondorcetPHP\Condorcet\Election $election): bool  
* public registerLink (CondorcetPHP\Condorcet\Election $election): void  
* public setName (string $name): bool  
* public setProvisionalState (bool $provisional): void  
* protected destroyAllLink (): void  
* protected initWeakMap (): void  
* private checkNameInElectionContext (string $name): bool  
```

#### Abstract CondorcetPHP\Condorcet\Condorcet   
```php
* final public const VERSION: (string)
* final public const CONDORCET_BASIC_CLASS: (string)

* protected static ?string $_defaultMethod
* protected static array $_authMethods
* public static bool $UseTimer

* public static addMethod (string $methodClass): bool  
* public static condorcetBasicSubstitution (?string $substitution): string  
* public static getAuthMethods (bool $basic = false): array  
* public static getDefaultMethod (): ?string  
* public static getMethodClass (string $method): ?string  
* public static getVersion (bool $major = false): string  
* public static isAuthMethod (string $method): bool  
* public static setDefaultMethod (string $method): bool  
* protected static testMethod (string $method): bool  
```

#### Abstract CondorcetPHP\Condorcet\CondorcetUtil   
```php
* public static format (mixed $input, bool $convertObject = true): mixed  
* public static isJson (string $string): bool  
* public static prepareJson (string $input): mixed  
* public static prepareParse (string $input, bool $isFile): array  
```

#### CondorcetPHP\Condorcet\Console\Commands\ElectionCommand extends Symfony\Component\Console\Command\Command   
```php
* public const SUCCESS: (integer)
* public const FAILURE: (integer)
* public const INVALID: (integer)

* protected CondorcetPHP\Condorcet\Election $election
* protected ?string $candidates
* protected ?string $votes
* protected ?string $CondorcetElectionFormatPath
* protected ?string $DebianFormatPath
* protected ?string $DavidHillFormatPath
* public static int $VotesPerMB
* protected bool $candidatesListIsWrite
* protected bool $votesCountIsWrite
* protected bool $pairwiseIsWrite
* public ?string $SQLitePath
* protected Symfony\Component\Console\Helper\TableStyle $centerPadTypeStyle
* protected Symfony\Component\Console\Terminal $terminal
* protected static  $defaultName
* protected static  $defaultDescription

* public static getDefaultDescription (): ?string  
* public static getDefaultName (): ?string  
* public __construct (?string $name = null)  
* public addArgument (string $name, ?int $mode = null, string $description = , mixed $default = null): static  
* public addOption (string $name, array|string|null $shortcut = null, ?int $mode = null, string $description = , mixed $default = null): static  
* public addUsage (string $usage): static  
* public complete (Symfony\Component\Console\Completion\CompletionInput $input, Symfony\Component\Console\Completion\CompletionSuggestions $suggestions): void  
* public displayPairwise (Symfony\Component\Console\Output\OutputInterface $output): void  
* public displayVotesCount (Symfony\Component\Console\Output\OutputInterface $output): void  
* public displayVotesList (Symfony\Component\Console\Output\OutputInterface $output): void  
* public getAliases (): array  
* public getApplication (): ?Symfony\Component\Console\Application  
* public getDefinition (): Symfony\Component\Console\Input\InputDefinition  
* public getDescription (): string  
* public getHelp (): string  
* public getHelper (string $name): mixed  
* public getHelperSet (): ?Symfony\Component\Console\Helper\HelperSet  
* public getName (): ?string  
* public getNativeDefinition (): Symfony\Component\Console\Input\InputDefinition  
* public getProcessedHelp (): string  
* public getSynopsis (bool $short = false): string  
* public getUsages (): array  
* public ignoreValidationErrors ()  
* public isEnabled ()  
* public isHidden (): bool  
* public mergeApplicationDefinition (bool $mergeArgs = true)  
* public run (Symfony\Component\Console\Input\InputInterface $input, Symfony\Component\Console\Output\OutputInterface $output): int  
* public setAliases (iterable $aliases): static  
* public setApplication (?Symfony\Component\Console\Application $application = null)  
* public setCode (callable $code): static  
* public setDefinition (Symfony\Component\Console\Input\InputDefinition|array $definition): static  
* public setDescription (string $description): static  
* public setHelp (string $help): static  
* public setHelperSet (Symfony\Component\Console\Helper\HelperSet $helperSet)  
* public setHidden (bool $hidden = true): static  
* public setName (string $name): static  
* public setProcessTitle (string $title): static  
* protected configure (): void  
* protected displayCandidatesList (Symfony\Component\Console\Output\OutputInterface $output): void  
* protected execute (Symfony\Component\Console\Input\InputInterface $input, Symfony\Component\Console\Output\OutputInterface $output): int  
* protected formatResultTable (CondorcetPHP\Condorcet\Result $result): array  
* protected getFilePath (string $path): ?string  
* protected initialize (Symfony\Component\Console\Input\InputInterface $input, Symfony\Component\Console\Output\OutputInterface $output): void  
* protected interact (Symfony\Component\Console\Input\InputInterface $input, Symfony\Component\Console\Output\OutputInterface $output): void  
* protected isAbsolute (string $path): bool  
* protected parseFromCandidatesArguments (): void  
* protected parseFromCondorcetElectionFormat (Closure $callBack): void  
* protected parseFromDavidHillFormat (): void  
* protected parseFromDebianFormat (): void  
* protected parseFromVotesArguments (Closure $callBack): void  
* protected prepareMethods (array $methodArgument): array  
* protected sectionVerbose (Symfony\Component\Console\Style\SymfonyStyle $io, Symfony\Component\Console\Output\OutputInterface $output): void  
* protected setUpParameters (Symfony\Component\Console\Input\InputInterface $input): void  
* protected useDataHandler (Symfony\Component\Console\Input\InputInterface $input): ?Closure  
```

#### Abstract CondorcetPHP\Condorcet\Console\CondorcetApplication   
```php
* public static Symfony\Component\Console\Application $SymfonyConsoleApplication

* public static create (): bool  
* public static run (): void  
```

#### CondorcetPHP\Condorcet\Constraints\NoTie extends CondorcetPHP\Condorcet\VoteConstraint   
```php
* public static isVoteAllow (CondorcetPHP\Condorcet\Election $election, CondorcetPHP\Condorcet\Vote $vote): bool  
* protected static evaluateVote (array $vote): bool  
```

#### Abstract CondorcetPHP\Condorcet\DataManager\ArrayManager implements ArrayAccess, Countable, Iterator, Traversable  
```php
* public static int $CacheSize
* public static int $MaxContainerLength
* protected array $_Container
* protected ?CondorcetPHP\Condorcet\DataManager\DataHandlerDrivers\DataHandlerDriverInterface $_DataHandler
* protected WeakReference $_Election
* protected array $_Cache
* protected int $_CacheMaxKey
* protected int $_CacheMinKey
* protected ?int $_cursor
* protected int $_counter
* protected int $_maxKey
* protected bool $valid
* protected string $_objectVersion

* public __construct (CondorcetPHP\Condorcet\Election $election)  
* public __destruct ()  
* public __serialize (): array  
* public __unserialize (array $data): void  
* public checkRegularize (): bool  
* public clearCache (): void  
* public closeHandler (): void  
* public count (): int  
* public current (): mixed  
* public debugGetCache (): array  
* public destroyElection (): void  
* public getCacheSize (): int  
* public getContainerSize (): int  
* public getElection (): CondorcetPHP\Condorcet\Election  
* public getFirstKey (): int  
* public getFullDataSet (): array  
* public getObjectVersion (bool $major = false): string  
* public importHandler (CondorcetPHP\Condorcet\DataManager\DataHandlerDrivers\DataHandlerDriverInterface $handler): bool  
* public isUsingHandler (): bool  
* public key (): ?int  
* public keyExist ($offset): bool  
* public next (): void  
* public offsetExists (mixed $offset): bool  
* public offsetGet (mixed $offset): mixed  
* public offsetSet (mixed $offset, mixed $value): void  
* public offsetUnset (mixed $offset): void  
* public regularize (): bool  
* public resetCounter (): int  
* public resetMaxKey (): ?int  
* public rewind (): void  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* public valid (): bool  
* protected decodeManyEntities (array $entities): array  
* protected decodeOneEntity (string $data): CondorcetPHP\Condorcet\Vote  
* protected encodeManyEntities (array $entities): array  
* protected encodeOneEntity (CondorcetPHP\Condorcet\Vote $data): string  
* protected populateCache (): void  
* protected preDeletedTask ($object): void  
* protected setCursorOnNextKeyInArray (array $array): void  
```

#### CondorcetPHP\Condorcet\DataManager\DataHandlerDrivers\PdoDriver\PdoHandlerDriver implements CondorcetPHP\Condorcet\DataManager\DataHandlerDrivers\DataHandlerDriverInterface  
```php
* public const SEGMENT: (array)

* protected PDO $_handler
* protected bool $_transaction
* protected bool $_queryError
* public static bool $preferBlobInsteadVarchar
* protected array $_struct
* protected array $_prepare
* protected string $_objectVersion

* public __construct (PDO $bdd, bool $tryCreateTable = false, array $struct = [Entities,id,data])  
* public closeTransaction (): void  
* public countEntities (): int  
* public createTable (): void  
* public deleteOneEntity (int $key, bool $justTry): ?int  
* public getObjectVersion (bool $major = false): string  
* public insertEntities (array $input): void  
* public selectMaxKey (): ?int  
* public selectMinKey (): int  
* public selectOneEntity (int $key): string|bool  
* public selectRangeEntities (int $key, int $limit): array  
* protected checkStructureTemplate (array $struct): bool  
* protected initPrepareQuery (): void  
* protected initTransaction (): void  
* protected sliceInput (array $input): void  
```

#### CondorcetPHP\Condorcet\DataManager\VotesManager extends CondorcetPHP\Condorcet\DataManager\ArrayManager implements Traversable, Iterator, Countable, ArrayAccess  
```php
* public static int $CacheSize
* public static int $MaxContainerLength
* protected array $_Container
* protected ?CondorcetPHP\Condorcet\DataManager\DataHandlerDrivers\DataHandlerDriverInterface $_DataHandler
* protected WeakReference $_Election
* protected array $_Cache
* protected int $_CacheMaxKey
* protected int $_CacheMinKey
* protected ?int $_cursor
* protected int $_counter
* protected int $_maxKey
* protected bool $valid
* protected string $_objectVersion

* public UpdateAndResetComputing (int $key, int $type): void  
* public __construct (CondorcetPHP\Condorcet\Election $election)  
* public __destruct ()  
* public __serialize (): array  
* public __unserialize (array $data): void  
* public checkRegularize (): bool  
* public clearCache (): void  
* public closeHandler (): void  
* public count (): int  
* public countInvalidVoteWithConstraints (): int  
* public countVotes (?array $tag, bool $with): int  
* public current (): mixed  
* public debugGetCache (): array  
* public destroyElection (): void  
* public getCacheSize (): int  
* public getContainerSize (): int  
* public getElection (): CondorcetPHP\Condorcet\Election  
* public getFirstKey (): int  
* public getFullDataSet (): array  
* public getObjectVersion (bool $major = false): string  
* public getVoteKey (CondorcetPHP\Condorcet\Vote $vote): ?int  
* public getVotesList (?array $tags = null, bool $with = true): array  
* public getVotesListAsString (bool $withContext): string  
* public getVotesListGenerator (?array $tags = null, bool $with = true): Generator  
* public getVotesValidUnderConstraintGenerator (?array $tags = null, bool $with = true): Generator  
* public importHandler (CondorcetPHP\Condorcet\DataManager\DataHandlerDrivers\DataHandlerDriverInterface $handler): bool  
* public isUsingHandler (): bool  
* public key (): ?int  
* public keyExist ($offset): bool  
* public next (): void  
* public offsetExists (mixed $offset): bool  
* public offsetGet (mixed $offset): CondorcetPHP\Condorcet\Vote  
* public offsetSet (mixed $offset, mixed $value): void  
* public offsetUnset (mixed $offset): void  
* public regularize (): bool  
* public resetCounter (): int  
* public resetMaxKey (): ?int  
* public rewind (): void  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* public sumVotesWeight (bool $constraint = false): int  
* public valid (): bool  
* protected decodeManyEntities (array $entities): array  
* protected decodeOneEntity (string $data): CondorcetPHP\Condorcet\Vote  
* protected encodeManyEntities (array $entities): array  
* protected encodeOneEntity (CondorcetPHP\Condorcet\Vote $data): string  
* protected getFullVotesListGenerator (): Generator  
* protected getPartialVotesListGenerator (array $tags, bool $with): Generator  
* protected populateCache (): void  
* protected preDeletedTask ($object): void  
* protected setCursorOnNextKeyInArray (array $array): void  
```

#### CondorcetPHP\Condorcet\Election   
```php
* public const MAX_LENGTH_CANDIDATE_ID: (integer)

* protected static ?int $_maxParseIteration
* protected static ?int $_maxVoteNumber
* protected static bool $_checksumMode
* protected CondorcetPHP\Condorcet\ElectionProcess\ElectionState $_State
* protected CondorcetPHP\Condorcet\Timer\Manager $_timer
* protected bool $_ImplicitRanking
* protected bool $_VoteWeightRule
* protected array $_Constraints
* protected int $_Seats
* protected string $_objectVersion
* protected array $_Candidates
* protected string $_AutomaticNewCandidateName
* protected CondorcetPHP\Condorcet\DataManager\VotesManager $_Votes
* protected int $_voteFastMode
* protected ?CondorcetPHP\Condorcet\Algo\Pairwise $_Pairwise
* protected ?array $_Calculator

* public static setMaxParseIteration (?int $maxParseIterations): ?int  
* public static setMaxVoteNumber (?int $maxVotesNumber): ?int  
* protected static formatResultOptions (array $arg): array  
* public __clone (): void  
* public __construct ()  
* public __serialize (): array  
* public __unserialize (array $data): void  
* public addCandidate (CondorcetPHP\Condorcet\Candidate|string|null $candidate = null): CondorcetPHP\Condorcet\Candidate  
* public addCandidatesFromJson (string $input): array  
* public addConstraint (string $constraintClass): bool  
* public addVote (CondorcetPHP\Condorcet\Vote|array|string $vote, array|string|null $tags = null): CondorcetPHP\Condorcet\Vote  
* public addVotesFromJson (string $input): int  
* public allowsVoteWeight (bool $rule = true): bool  
* public canAddCandidate (CondorcetPHP\Condorcet\Candidate|string $candidate): bool  
* public checkVoteCandidate (CondorcetPHP\Condorcet\Vote $vote): bool  
* public cleanupCalculator (): void  
* public clearConstraints (): bool  
* public computeResult (?string $method = null): void  
* public convertRankingCandidates (array $ranking): bool  
* public countCandidates (): int  
* public countInvalidVoteWithConstraints (): int  
* public countValidVoteWithConstraints (): int  
* public countVotes (array|string|null $tags = null, bool $with = true): int  
* public debugGetCalculator (): ?array  
* public finishUpdateVote (CondorcetPHP\Condorcet\Vote $existVote): void  
* public getCandidateKey (CondorcetPHP\Condorcet\Candidate|string $candidate): ?int  
* public getCandidateObjectFromKey (int $candidate_key): ?CondorcetPHP\Condorcet\Candidate  
* public getCandidateObjectFromName (string $candidateName): ?CondorcetPHP\Condorcet\Candidate  
* public getCandidatesList (): array  
* public getCandidatesListAsString (): array  
* public getChecksum (): string  
* public getCondorcetLoser (): ?CondorcetPHP\Condorcet\Candidate  
* public getCondorcetWinner (): ?CondorcetPHP\Condorcet\Candidate  
* public getConstraints (): array  
* public getExplicitPairwise (): array  
* public getGlobalTimer (): float  
* public getImplicitRankingRule (): bool  
* public getLastTimer (): float  
* public getLoser (?string $method = null): CondorcetPHP\Condorcet\Candidate|array|null  
* public getNumberOfSeats (): int  
* public getObjectVersion (bool $major = false): string  
* public getPairwise (): CondorcetPHP\Condorcet\Algo\Pairwise  
* public getResult (?string $method = null, array $options = []): CondorcetPHP\Condorcet\Result  
* public getState (): CondorcetPHP\Condorcet\ElectionProcess\ElectionState  
* public getTimerManager (): CondorcetPHP\Condorcet\Timer\Manager  
* public getVoteKey (CondorcetPHP\Condorcet\Vote $vote): ?int  
* public getVotesList (array|string|null $tags = null, bool $with = true): array  
* public getVotesListAsString (bool $withContext = true): string  
* public getVotesListGenerator (array|string|null $tags = null, bool $with = true): Generator  
* public getVotesManager (): CondorcetPHP\Condorcet\DataManager\VotesManager  
* public getVotesValidUnderConstraintGenerator (array|string|null $tags = null, bool $with = true): Generator  
* public getWinner (?string $method = null): CondorcetPHP\Condorcet\Candidate|array|null  
* public isRegisteredCandidate (CondorcetPHP\Condorcet\Candidate|string $candidate, bool $strictMode = true): bool  
* public isVoteWeightAllowed (): bool  
* public parseCandidates (string $input, bool $isFile = false): array  
* public parseVotes (string $input, bool $isFile = false): int  
* public parseVotesWithoutFail (SplFileInfo|string $input, bool $isFile = false, ?Closure $callBack = null): int  
* public prepareUpdateVote (CondorcetPHP\Condorcet\Vote $existVote): void  
* public removeCandidates (CondorcetPHP\Condorcet\Candidate|array|string $candidates_input): array  
* public removeExternalDataHandler (): bool  
* public removeVote (CondorcetPHP\Condorcet\Vote $vote): bool  
* public removeVotesByTags (array|string $tags, bool $with = true): array  
* public setExternalDataHandler (CondorcetPHP\Condorcet\DataManager\DataHandlerDrivers\DataHandlerDriverInterface $driver): bool  
* public setImplicitRanking (bool $rule = true): bool  
* public setMethodOption (string $method, string $optionName, BackedEnum|int $optionValue): bool  
* public setNumberOfSeats (int $seats): int  
* public setStateToVote (): bool  
* public sumValidVotesWeightWithConstraints (): int  
* public sumVotesWeight (): int  
* public testIfVoteIsValidUnderElectionConstraints (CondorcetPHP\Condorcet\Vote $vote): bool  
* protected cleanupCompute (): void  
* protected destroyAllLink (): void  
* protected doAddVotesFromParse (array $adding): void  
* protected initResult (string $class): void  
* protected makePairwise (): void  
* protected preparePairwiseAndCleanCompute (): bool  
* protected prepareVoteInput (CondorcetPHP\Condorcet\Vote|array|string $vote, array|string|null $tags = null): void  
* protected registerAllLinks (): void  
* protected registerVote (CondorcetPHP\Condorcet\Vote $vote, array|string|null $tags): CondorcetPHP\Condorcet\Vote  
* protected synthesisVoteFromParse (int $count, int $multiple, array $adding, CondorcetPHP\Condorcet\Vote|array|string $vote, array|string|null $tags, int $weight): void  
```

#### CondorcetPHP\Condorcet\ElectionProcess\ElectionState implements UnitEnum, BackedEnum  
```php
* ```case ElectionState::CANDIDATES_REGISTRATION```  
* ```case ElectionState::VOTES_REGISTRATION```  

* readonly public string $name
* readonly public int $value

```

#### Abstract CondorcetPHP\Condorcet\ElectionProcess\VoteUtil   
```php
* public static convertVoteInput (string $formula): array  
* public static getRankingAsString (array $ranking): string  
* public static parseAnalysingOneLine (int|bool $searchCharacter, string $line): int  
* public static tagsConvert (array|string|null $tags): ?array  
```

#### CondorcetPHP\Condorcet\Result implements ArrayAccess, Countable, Iterator, Traversable  
```php
* readonly protected array $_Result
* protected array $_ResultIterator
* protected  $_Stats
* protected array $_warning
* readonly public array $ranking
* readonly public array $rankingAsString
* readonly public ?int $seats
* readonly public array $methodOptions
* readonly public ?CondorcetPHP\Condorcet\Candidate $CondorcetWinner
* readonly public ?CondorcetPHP\Condorcet\Candidate $CondorcetLoser
* readonly public float $buildTimestamp
* readonly public string $fromMethod
* readonly public string $byClass
* readonly public string $electionCondorcetVersion
* protected string $_objectVersion

* public __construct (string $fromMethod, string $byClass, CondorcetPHP\Condorcet\Election $election, array $result, $stats, ?int $seats = null, array $methodOptions = [])  
* public addWarning (int $type, ?string $msg = null): bool  
* public count (): int  
* public current (): CondorcetPHP\Condorcet\Candidate|array  
* public getBuildTimeStamp (): float  
* public getClassGenerator (): string  
* public getCondorcetElectionGeneratorVersion (): string  
* public getCondorcetLoser (): ?CondorcetPHP\Condorcet\Candidate  
* public getCondorcetWinner (): ?CondorcetPHP\Condorcet\Candidate  
* public getLoser (): CondorcetPHP\Condorcet\Candidate|array|null  
* public getMethod (): string  
* public getMethodOptions (): array  
* public getNumberOfSeats (): ?int  
* public getObjectVersion (bool $major = false): string  
* public getOriginalResultArrayWithString (): array  
* public getResultAsArray (bool $convertToString = false): array  
* public getResultAsInternalKey (): array  
* public getResultAsString (): string  
* public getStats (): mixed  
* public getWarning (?int $type = null): array  
* public getWinner (): CondorcetPHP\Condorcet\Candidate|array|null  
* public isProportional (): bool  
* public key (): int  
* public next (): void  
* public offsetExists (mixed $offset): bool  
* public offsetGet (mixed $offset): CondorcetPHP\Condorcet\Candidate|array|null  
* public offsetSet (mixed $offset, mixed $value): void  
* public offsetUnset (mixed $offset): void  
* public rewind (): void  
* public valid (): bool  
* protected makeUserResult (CondorcetPHP\Condorcet\Election $election): array  
```

#### CondorcetPHP\Condorcet\Throwable\AlgorithmException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable  
```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* protected string $_objectVersion

* public __construct (string|int|null $message = null)  
* public getObjectVersion (bool $major = false): string  
```

#### CondorcetPHP\Condorcet\Throwable\AlgorithmWithoutRankingFeatureException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable  
```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* protected string $_objectVersion

* public __construct (string|int|null $message = null)  
* public getObjectVersion (bool $major = false): string  
```

#### CondorcetPHP\Condorcet\Throwable\CandidateDoesNotExistException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable  
```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* protected string $_objectVersion

* public __construct (string|int|null $message = null)  
* public getObjectVersion (bool $major = false): string  
```

#### CondorcetPHP\Condorcet\Throwable\CandidateExistsException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable  
```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* protected string $_objectVersion

* public __construct (string|int|null $message = null)  
* public getObjectVersion (bool $major = false): string  
```

#### CondorcetPHP\Condorcet\Throwable\CandidateInvalidNameException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable  
```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* protected string $_objectVersion

* public __construct (string|int|null $message = null)  
* public getObjectVersion (bool $major = false): string  
```

#### CondorcetPHP\Condorcet\Throwable\CandidatesMaxNumberReachedException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable  
```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* protected string $_objectVersion

* public __construct (string $method = , int $maxCandidates = 0)  
* public getObjectVersion (bool $major = false): string  
```

#### CondorcetPHP\Condorcet\Throwable\CondorcetInternalError extends Error implements Throwable, Stringable  
```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* protected string $_objectVersion

* public __construct (string $message)  
* public getObjectVersion (bool $major = false): string  
```

#### CondorcetPHP\Condorcet\Throwable\CondorcetInternalException extends Exception implements Throwable, Stringable  
```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line

```

#### Abstract CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException extends Exception implements Stringable, Throwable  
```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* protected string $_objectVersion

* public __construct (string|int|null $message = null)  
* public getObjectVersion (bool $major = false): string  
```

#### CondorcetPHP\Condorcet\Throwable\DataHandlerException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable  
```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* protected string $_objectVersion

* public __construct (string|int|null $message = null)  
* public getObjectVersion (bool $major = false): string  
```

#### CondorcetPHP\Condorcet\Throwable\ElectionObjectVersionMismatchException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable  
```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* protected string $_objectVersion

* public __construct (string $message = )  
* public getObjectVersion (bool $major = false): string  
```

#### CondorcetPHP\Condorcet\Throwable\FileDoesNotExistException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable  
```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* protected string $_objectVersion

* public __construct (string|int|null $message = null)  
* public getObjectVersion (bool $major = false): string  
```

#### CondorcetPHP\Condorcet\Throwable\JsonFormatException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable  
```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* protected string $_objectVersion

* public __construct (string|int|null $message = null)  
* public getObjectVersion (bool $major = false): string  
```

#### CondorcetPHP\Condorcet\Throwable\NoCandidatesException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable  
```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* protected string $_objectVersion

* public __construct (string|int|null $message = null)  
* public getObjectVersion (bool $major = false): string  
```

#### CondorcetPHP\Condorcet\Throwable\NoSeatsException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable  
```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* protected string $_objectVersion

* public __construct (string|int|null $message = null)  
* public getObjectVersion (bool $major = false): string  
```

#### CondorcetPHP\Condorcet\Throwable\ResultException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable  
```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* protected string $_objectVersion

* public __construct (string|int|null $message = null)  
* public getObjectVersion (bool $major = false): string  
```

#### CondorcetPHP\Condorcet\Throwable\ResultRequestedWithoutVotesException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable  
```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* protected string $_objectVersion

* public __construct (string|int|null $message = null)  
* public getObjectVersion (bool $major = false): string  
```

#### CondorcetPHP\Condorcet\Throwable\StvQuotaNotImplementedException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable  
```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* protected string $_objectVersion

* public __construct (string|int|null $message = null)  
* public getObjectVersion (bool $major = false): string  
```

#### CondorcetPHP\Condorcet\Throwable\TimerException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable  
```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* protected string $_objectVersion

* public __construct (string|int|null $message = null)  
* public getObjectVersion (bool $major = false): string  
```

#### CondorcetPHP\Condorcet\Throwable\VoteConstraintException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable  
```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* protected string $_objectVersion

* public __construct (string|int|null $message = null)  
* public getObjectVersion (bool $major = false): string  
```

#### CondorcetPHP\Condorcet\Throwable\VoteException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable  
```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* protected string $_objectVersion

* public __construct (string|int|null $message = null)  
* public getObjectVersion (bool $major = false): string  
```

#### CondorcetPHP\Condorcet\Throwable\VoteInvalidFormatException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable  
```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* protected string $_objectVersion

* public __construct (string|int|null $message = null)  
* public getObjectVersion (bool $major = false): string  
```

#### CondorcetPHP\Condorcet\Throwable\VoteManagerException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable  
```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* protected string $_objectVersion

* public __construct (string|int|null $message = null)  
* public getObjectVersion (bool $major = false): string  
```

#### CondorcetPHP\Condorcet\Throwable\VoteMaxNumberReachedException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable  
```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* protected string $_objectVersion

* public __construct (string|int|null $message = null)  
* public getObjectVersion (bool $major = false): string  
```

#### CondorcetPHP\Condorcet\Throwable\VoteNotLinkedException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable  
```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* protected string $_objectVersion

* public __construct (string|int|null $message = null)  
* public getObjectVersion (bool $major = false): string  
```

#### CondorcetPHP\Condorcet\Throwable\VotingHasStartedException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable  
```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* protected string $_objectVersion

* public __construct (string|int|null $message = null)  
* public getObjectVersion (bool $major = false): string  
```

#### CondorcetPHP\Condorcet\Timer\Chrono   
```php
* protected CondorcetPHP\Condorcet\Timer\Manager $_manager
* protected float $_start
* protected ?string $_role
* protected string $_objectVersion

* public __construct (CondorcetPHP\Condorcet\Timer\Manager $timer, ?string $role = null)  
* public __destruct ()  
* public getObjectVersion (bool $major = false): string  
* public getRole (): ?string  
* public getStart (): float  
* public getTimerManager (): CondorcetPHP\Condorcet\Timer\Manager  
* public setRole (?string $role): void  
* protected managerStartDeclare (): void  
* protected resetStart (): void  
```

#### CondorcetPHP\Condorcet\Timer\Manager   
```php
* protected float $_globalTimer
* protected ?float $_lastTimer
* protected ?float $_lastChronoTimestamp
* protected ?float $_startDeclare
* protected array $_history
* protected string $_objectVersion

* public addTime (CondorcetPHP\Condorcet\Timer\Chrono $chrono): void  
* public getGlobalTimer (): float  
* public getHistory (): array  
* public getLastTimer (): float  
* public getObjectVersion (bool $major = false): string  
* public startDeclare (CondorcetPHP\Condorcet\Timer\Chrono $chrono): void  
```

#### CondorcetPHP\Condorcet\Tools\Converters\CondorcetElectionFormat implements CondorcetPHP\Condorcet\Tools\Converters\ConverterInterface  
```php
* public const SPECIAL_KEYWORD_EMPTY_RANKING: (string)
* protected const CANDIDATES_PATTERN: (string)
* protected const SEATS_PATTERN: (string)
* protected const IMPLICIT_PATTERN: (string)
* protected const WEIGHT_PATTERN: (string)

* protected SplFileObject $file
* readonly public array $candidates
* readonly public int $numberOfSeats
* readonly public bool $implicitRanking
* readonly public bool $voteWeight
* readonly public bool $CandidatesParsedFromVotes
* readonly public int $invalidBlocksCount

* public static exportElectionToCondorcetElectionFormat (CondorcetPHP\Condorcet\Election $election, bool $aggregateVotes = true, bool $includeNumberOfSeats = true, bool $includeTags = true, bool $inContext = false, ?SplFileObject $file = null): ?string  
* public __construct (SplFileInfo|string $input)  
* public setDataToAnElection (?CondorcetPHP\Condorcet\Election $election = null, ?Closure $callBack = null): CondorcetPHP\Condorcet\Election  
* protected addCandidates (array $candidates): void  
* protected boolParser (string $parse): bool  
* protected parseCandidatesFromVotes (): void  
* protected readParameters (): void  
```

#### CondorcetPHP\Condorcet\Tools\Converters\DavidHillFormat implements CondorcetPHP\Condorcet\Tools\Converters\ConverterInterface  
```php
* protected array $lines
* readonly public array $candidates
* readonly public int $NumberOfSeats

* public __construct (string $filePath)  
* public setDataToAnElection (?CondorcetPHP\Condorcet\Election $election = null): CondorcetPHP\Condorcet\Election  
* protected readCandidatesNames (): void  
* protected readNumberOfSeats (): void  
* protected readVotes (): void  
```

#### CondorcetPHP\Condorcet\Tools\Converters\DebianFormat implements CondorcetPHP\Condorcet\Tools\Converters\ConverterInterface  
```php
* protected array $lines
* readonly public array $candidates
* readonly public array $votes

* public __construct (string $filePath)  
* public setDataToAnElection (?CondorcetPHP\Condorcet\Election $election = null): CondorcetPHP\Condorcet\Election  
* protected readCandidatesNames (): void  
* protected readVotes (): void  
```

#### CondorcetPHP\Condorcet\Vote implements Iterator, Stringable, Traversable  
```php
* private int $position
* private array $_ranking
* private float $_lastTimestamp
* private int $_counter
* private array $_ranking_history
* private int $_weight
* private array $_tags
* private string $_hashCode
* private ?CondorcetPHP\Condorcet\Election $_electionContext
* public bool $notUpdate
* private ?WeakMap $_link
* protected string $_objectVersion

* public __clone (): void  
* public __construct (array|string $ranking, array|string|null $tags = null, ?float $ownTimestamp = null, ?CondorcetPHP\Condorcet\Election $electionContext = null)  
* public __serialize (): array  
* public __toString (): string  
* public addTags (array|string $tags): bool  
* public countLinks (): int  
* public countRankingCandidates (): int  
* public current (): array  
* public destroyLink (CondorcetPHP\Condorcet\Election $election): bool  
* public getAllCandidates (): array  
* public getContextualRanking (CondorcetPHP\Condorcet\Election $election): array  
* public getContextualRankingAsString (CondorcetPHP\Condorcet\Election $election): array  
* public getCreateTimestamp (): float  
* public getHashCode (): string  
* public getHistory (): array  
* public getLinks (): WeakMap  
* public getObjectVersion (bool $major = false): string  
* public getRanking (): array  
* public getSimpleRanking (?CondorcetPHP\Condorcet\Election $context = null, bool $displayWeight = true): string  
* public getTags (): array  
* public getTagsAsString (): string  
* public getTimestamp (): float  
* public getWeight (?CondorcetPHP\Condorcet\Election $context = null): int  
* public haveLink (CondorcetPHP\Condorcet\Election $election): bool  
* public key (): int  
* public next (): void  
* public registerLink (CondorcetPHP\Condorcet\Election $election): void  
* public removeAllTags (): bool  
* public removeCandidate (CondorcetPHP\Condorcet\Candidate|string $candidate): bool  
* public removeTags (array|string $tags): array  
* public rewind (): void  
* public setRanking (array|string $ranking, ?float $ownTimestamp = null): bool  
* public setWeight (int $newWeight): int  
* public valid (): bool  
* protected computeContextualRankingWithoutImplicit (array $ranking, CondorcetPHP\Condorcet\Election $election, int $countContextualCandidate = 0): array  
* protected destroyAllLink (): void  
* protected initWeakMap (): void  
* private archiveRanking (): void  
* private formatRanking (array|string $ranking): int  
* private setHashCode (): string  
```

#### Abstract CondorcetPHP\Condorcet\VoteConstraint   
```php
* public static isVoteAllow (CondorcetPHP\Condorcet\Election $election, CondorcetPHP\Condorcet\Vote $vote): bool  
* protected static evaluateVote (array $vote): bool  
```
