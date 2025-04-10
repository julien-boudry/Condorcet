> **[Presentation](../README.md) | [Documentation Book](https://www.condorcet.io) | API References | [Voting Methods](/Docs/VotingMethods.md) | [Tests](https://github.com/julien-boudry/Condorcet/tree/master/Tests)**

# API References
## Public API Index *
_*: I try to update and complete the documentation. See also [the documentation book](https://www.condorcet.io), [the tests](../Tests) also produce many examples. And create issues for questions or fixing documentation!_


### CondorcetPHP\Condorcet\Candidate Class  

* `public string $name`  
* `public array $nameHistory`  
* `public float $createdAt`  
* `public float $updatedAt`  

* [public Candidate->__construct (...)](/Docs/ApiReferences/Candidate%20Class/public%20Candidate--__construct.md)  
* [public Candidate->countLinks ()](/Docs/ApiReferences/Candidate%20Class/public%20Candidate--countLinks.md): `int`  
* [public Candidate->getLinks ()](/Docs/ApiReferences/Candidate%20Class/public%20Candidate--getLinks.md): `array`  
* [public Candidate->getObjectVersion (...)](/Docs/ApiReferences/Candidate%20Class/public%20Candidate--getObjectVersion.md): `string`  
* [public Candidate->haveLink (...)](/Docs/ApiReferences/Candidate%20Class/public%20Candidate--haveLink.md): `bool`  
* [public Candidate->setName (...)](/Docs/ApiReferences/Candidate%20Class/public%20Candidate--setName.md): `static`  

### CondorcetPHP\Condorcet\Condorcet Class  

* `final public const VERSION: (string)`  
* `final public const CONDORCET_BASIC_CLASS: (string)`  

* `public static bool $UseTimer`  

* [public static Condorcet::addMethod (...)](/Docs/ApiReferences/Condorcet%20Class/public%20static%20Condorcet--addMethod.md): `bool`  
* [public static Condorcet::getAuthMethods (...)](/Docs/ApiReferences/Condorcet%20Class/public%20static%20Condorcet--getAuthMethods.md): `array`  
* [public static Condorcet::getDefaultMethod ()](/Docs/ApiReferences/Condorcet%20Class/public%20static%20Condorcet--getDefaultMethod.md): `?string`  
* [public static Condorcet::getMethodClass (...)](/Docs/ApiReferences/Condorcet%20Class/public%20static%20Condorcet--getMethodClass.md): `?string`  
* [public static Condorcet::getVersion (...)](/Docs/ApiReferences/Condorcet%20Class/public%20static%20Condorcet--getVersion.md): `string`  
* [public static Condorcet::isAuthMethod (...)](/Docs/ApiReferences/Condorcet%20Class/public%20static%20Condorcet--isAuthMethod.md): `bool`  
* [public static Condorcet::setDefaultMethod (...)](/Docs/ApiReferences/Condorcet%20Class/public%20static%20Condorcet--setDefaultMethod.md): `bool`  

### CondorcetPHP\Condorcet\Election Class  

* `public const MAX_CANDIDATE_NAME_LENGTH: (integer)`  

* `public static ?int $maxParseIteration`  
* `public static ?int $maxVotePerElection`  
* `public CondorcetPHP\Condorcet\ElectionProcess\ElectionState $state`  
* `public bool $implicitRankingRule`  
* `public bool $voteWeightAllowed`  
* `public int $electionSeats`  
* `public array $votesConstraints`  
* `public string $hash`  
* `public CondorcetPHP\Condorcet\Algo\StatsVerbosity $statsVerbosity`  

* [public Election->__construct ()](/Docs/ApiReferences/Election%20Class/public%20Election--__construct.md)  
* [public Election->addCandidate (...)](/Docs/ApiReferences/Election%20Class/public%20Election--addCandidate.md): `CondorcetPHP\Condorcet\Candidate`  
* [public Election->addCandidatesFromJson (...)](/Docs/ApiReferences/Election%20Class/public%20Election--addCandidatesFromJson.md): `array`  
* [public Election->addConstraint (...)](/Docs/ApiReferences/Election%20Class/public%20Election--addConstraint.md): `true`  
* [public Election->addVote (...)](/Docs/ApiReferences/Election%20Class/public%20Election--addVote.md): `CondorcetPHP\Condorcet\Vote`  
* [public Election->addVotesFromJson (...)](/Docs/ApiReferences/Election%20Class/public%20Election--addVotesFromJson.md): `int`  
* [public Election->allowsVoteWeight (...)](/Docs/ApiReferences/Election%20Class/public%20Election--allowsVoteWeight.md): `static`  
* [public Election->canAddCandidate (...)](/Docs/ApiReferences/Election%20Class/public%20Election--canAddCandidate.md): `bool`  
* [public Election->clearConstraints ()](/Docs/ApiReferences/Election%20Class/public%20Election--clearConstraints.md): `bool`  
* [public Election->computeResult (...)](/Docs/ApiReferences/Election%20Class/public%20Election--computeResult.md): `void`  
* [public Election->countCandidates ()](/Docs/ApiReferences/Election%20Class/public%20Election--countCandidates.md): `int`  
* [public Election->countInvalidVoteWithConstraints ()](/Docs/ApiReferences/Election%20Class/public%20Election--countInvalidVoteWithConstraints.md): `int`  
* [public Election->countValidVoteWithConstraints (...)](/Docs/ApiReferences/Election%20Class/public%20Election--countValidVoteWithConstraints.md): `int`  
* [public Election->countVotes (...)](/Docs/ApiReferences/Election%20Class/public%20Election--countVotes.md): `int`  
* [public Election->getCandidateObjectFromName (...)](/Docs/ApiReferences/Election%20Class/public%20Election--getCandidateObjectFromName.md): `?CondorcetPHP\Condorcet\Candidate`  
* [public Election->getCandidatesList ()](/Docs/ApiReferences/Election%20Class/public%20Election--getCandidatesList.md): `array`  
* [public Election->getCandidatesListAsString ()](/Docs/ApiReferences/Election%20Class/public%20Election--getCandidatesListAsString.md): `array`  
* [public Election->getChecksum ()](/Docs/ApiReferences/Election%20Class/public%20Election--getChecksum.md): `string`  
* [public Election->getCondorcetLoser ()](/Docs/ApiReferences/Election%20Class/public%20Election--getCondorcetLoser.md): `?CondorcetPHP\Condorcet\Candidate`  
* [public Election->getCondorcetWinner ()](/Docs/ApiReferences/Election%20Class/public%20Election--getCondorcetWinner.md): `?CondorcetPHP\Condorcet\Candidate`  
* [public Election->getConstraints ()](/Docs/ApiReferences/Election%20Class/public%20Election--getConstraints.md): `array`  
* [public Election->getExplicitFilteredPairwiseByTags (...)](/Docs/ApiReferences/Election%20Class/public%20Election--getExplicitFilteredPairwiseByTags.md): `array`  
* [public Election->getExplicitPairwise ()](/Docs/ApiReferences/Election%20Class/public%20Election--getExplicitPairwise.md): `array`  
* [public Election->getGlobalTimer ()](/Docs/ApiReferences/Election%20Class/public%20Election--getGlobalTimer.md): `float`  
* [public Election->getImplicitRankingRule ()](/Docs/ApiReferences/Election%20Class/public%20Election--getImplicitRankingRule.md): `bool`  
* [public Election->getLastTimer ()](/Docs/ApiReferences/Election%20Class/public%20Election--getLastTimer.md): `float`  
* [public Election->getLoser (...)](/Docs/ApiReferences/Election%20Class/public%20Election--getLoser.md): `CondorcetPHP\Condorcet\Candidate|array|null`  
* [public Election->getNumberOfSeats ()](/Docs/ApiReferences/Election%20Class/public%20Election--getNumberOfSeats.md): `int`  
* [public Election->getObjectVersion (...)](/Docs/ApiReferences/Election%20Class/public%20Election--getObjectVersion.md): `string`  
* [public Election->getPairwise ()](/Docs/ApiReferences/Election%20Class/public%20Election--getPairwise.md): `CondorcetPHP\Condorcet\Algo\Pairwise\Pairwise`  
* [public Election->getResult (...)](/Docs/ApiReferences/Election%20Class/public%20Election--getResult.md): `CondorcetPHP\Condorcet\Result`  
* [public Election->getTimerManager ()](/Docs/ApiReferences/Election%20Class/public%20Election--getTimerManager.md): `CondorcetPHP\Condorcet\Timer\Manager`  
* [public Election->getVotesList (...)](/Docs/ApiReferences/Election%20Class/public%20Election--getVotesList.md): `array`  
* [public Election->getVotesListAsString (...)](/Docs/ApiReferences/Election%20Class/public%20Election--getVotesListAsString.md): `string`  
* [public Election->getVotesListGenerator (...)](/Docs/ApiReferences/Election%20Class/public%20Election--getVotesListGenerator.md): `Generator`  
* [public Election->getVotesValidUnderConstraintGenerator (...)](/Docs/ApiReferences/Election%20Class/public%20Election--getVotesValidUnderConstraintGenerator.md): `Generator`  
* [public Election->getWinner (...)](/Docs/ApiReferences/Election%20Class/public%20Election--getWinner.md): `CondorcetPHP\Condorcet\Candidate|array|null`  
* [public Election->hasCandidate (...)](/Docs/ApiReferences/Election%20Class/public%20Election--hasCandidate.md): `bool`  
* [public Election->isVoteValidUnderConstraints (...)](/Docs/ApiReferences/Election%20Class/public%20Election--isVoteValidUnderConstraints.md): `bool`  
* [public Election->isVoteWeightAllowed ()](/Docs/ApiReferences/Election%20Class/public%20Election--isVoteWeightAllowed.md): `bool`  
* [public Election->parseCandidates (...)](/Docs/ApiReferences/Election%20Class/public%20Election--parseCandidates.md): `array`  
* [public Election->parseVotes (...)](/Docs/ApiReferences/Election%20Class/public%20Election--parseVotes.md): `int`  
* [public Election->parseVotesSafe (...)](/Docs/ApiReferences/Election%20Class/public%20Election--parseVotesSafe.md): `int`  
* [public Election->removeAllVotes ()](/Docs/ApiReferences/Election%20Class/public%20Election--removeAllVotes.md): `true`  
* [public Election->removeCandidates (...)](/Docs/ApiReferences/Election%20Class/public%20Election--removeCandidates.md): `array`  
* [public Election->removeExternalDataHandler ()](/Docs/ApiReferences/Election%20Class/public%20Election--removeExternalDataHandler.md): `bool`  
* [public Election->removeVote (...)](/Docs/ApiReferences/Election%20Class/public%20Election--removeVote.md): `true`  
* [public Election->removeVotesByTags (...)](/Docs/ApiReferences/Election%20Class/public%20Election--removeVotesByTags.md): `array`  
* [public Election->setExternalDataHandler (...)](/Docs/ApiReferences/Election%20Class/public%20Election--setExternalDataHandler.md): `static`  
* [public Election->setImplicitRanking (...)](/Docs/ApiReferences/Election%20Class/public%20Election--setImplicitRanking.md): `static`  
* [public Election->setMethodOption (...)](/Docs/ApiReferences/Election%20Class/public%20Election--setMethodOption.md): `static`  
* [public Election->setNumberOfSeats (...)](/Docs/ApiReferences/Election%20Class/public%20Election--setNumberOfSeats.md): `static`  
* [public Election->setStateToVote ()](/Docs/ApiReferences/Election%20Class/public%20Election--setStateToVote.md): `true`  
* [public Election->setStatsVerbosity (...)](/Docs/ApiReferences/Election%20Class/public%20Election--setStatsVerbosity.md): `static`  
* [public Election->sumValidVoteWeightsWithConstraints (...)](/Docs/ApiReferences/Election%20Class/public%20Election--sumValidVoteWeightsWithConstraints.md): `int`  
* [public Election->sumVoteWeights (...)](/Docs/ApiReferences/Election%20Class/public%20Election--sumVoteWeights.md): `int`  

### CondorcetPHP\Condorcet\Result Class  

* `readonly public CondorcetPHP\Condorcet\Algo\StatsVerbosity $statsVerbosity`  
* `readonly public string $fromMethod`  
* `readonly public string $byClass`  
* `readonly public CondorcetPHP\Condorcet\Algo\Stats\StatsInterface $stats`  
* `readonly public ?int $seats`  
* `public array $methodOptions`  
* `readonly public array $ranking`  
* `public array $rankingAsArray`  
* `public array $rankingAsArrayString`  
* `public string $rankingAsString`  
* `readonly public ?CondorcetPHP\Condorcet\Candidate $CondorcetWinner`  
* `readonly public ?CondorcetPHP\Condorcet\Candidate $CondorcetLoser`  
* `readonly public array $originalRankingAsArrayString`  
* `public string $originalRankingAsString`  
* `readonly public array $pairwise`  
* `public CondorcetPHP\Condorcet\Candidate|array|null $winner`  
* `public CondorcetPHP\Condorcet\Candidate|array|null $loser`  
* `readonly public float $buildTimestamp`  
* `readonly public string $electionCondorcetVersion`  
* `public bool $isProportional`  

* [public Result->getObjectVersion (...)](/Docs/ApiReferences/Result%20Class/public%20Result--getObjectVersion.md): `string`  
* [public Result->getWarning (...)](/Docs/ApiReferences/Result%20Class/public%20Result--getWarning.md): `array`  

### CondorcetPHP\Condorcet\Vote Class  

* `public float $createdAt`  
* `public float $updatedAt`  
* `public int $countCandidates`  
* `public array $rankingHistory`  
* `public array $tags`  
* `public string $hash`  

* [public Vote->__construct (...)](/Docs/ApiReferences/Vote%20Class/public%20Vote--__construct.md)  
* [public Vote->addTags (...)](/Docs/ApiReferences/Vote%20Class/public%20Vote--addTags.md): `bool`  
* [public Vote->countLinks ()](/Docs/ApiReferences/Vote%20Class/public%20Vote--countLinks.md): `int`  
* [public Vote->countRanks ()](/Docs/ApiReferences/Vote%20Class/public%20Vote--countRanks.md): `int`  
* [public Vote->getAllCandidates (...)](/Docs/ApiReferences/Vote%20Class/public%20Vote--getAllCandidates.md): `array`  
* [public Vote->getContextualRanking (...)](/Docs/ApiReferences/Vote%20Class/public%20Vote--getContextualRanking.md): `array`  
* [public Vote->getContextualRankingAsString (...)](/Docs/ApiReferences/Vote%20Class/public%20Vote--getContextualRankingAsString.md): `array`  
* [public Vote->getLinks ()](/Docs/ApiReferences/Vote%20Class/public%20Vote--getLinks.md): `array`  
* [public Vote->getObjectVersion (...)](/Docs/ApiReferences/Vote%20Class/public%20Vote--getObjectVersion.md): `string`  
* [public Vote->getRanking (...)](/Docs/ApiReferences/Vote%20Class/public%20Vote--getRanking.md): `array`  
* [public Vote->getSimpleRanking (...)](/Docs/ApiReferences/Vote%20Class/public%20Vote--getSimpleRanking.md): `string`  
* [public Vote->getTagsAsString ()](/Docs/ApiReferences/Vote%20Class/public%20Vote--getTagsAsString.md): `string`  
* [public Vote->getWeight (...)](/Docs/ApiReferences/Vote%20Class/public%20Vote--getWeight.md): `int`  
* [public Vote->haveLink (...)](/Docs/ApiReferences/Vote%20Class/public%20Vote--haveLink.md): `bool`  
* [public Vote->removeAllTags ()](/Docs/ApiReferences/Vote%20Class/public%20Vote--removeAllTags.md): `true`  
* [public Vote->removeCandidate (...)](/Docs/ApiReferences/Vote%20Class/public%20Vote--removeCandidate.md): `true`  
* [public Vote->removeTags (...)](/Docs/ApiReferences/Vote%20Class/public%20Vote--removeTags.md): `array`  
* [public Vote->setRanking (...)](/Docs/ApiReferences/Vote%20Class/public%20Vote--setRanking.md): `static`  
* [public Vote->setWeight (...)](/Docs/ApiReferences/Vote%20Class/public%20Vote--setWeight.md): `int`  

### CondorcetPHP\Condorcet\Algo\Pairwise\FilteredPairwise Class  

* [public Algo\Pairwise\Pairwise->getExplicitPairwise ()](/Docs/ApiReferences/Algo_Pairwise_Pairwise%20Class/public%20Algo_Pairwise_Pairwise--getExplicitPairwise.md): `array`  

### CondorcetPHP\Condorcet\Algo\Pairwise\Pairwise Class  

* [public Algo\Pairwise\Pairwise->getExplicitPairwise ()](/Docs/ApiReferences/Algo_Pairwise_Pairwise%20Class/public%20Algo_Pairwise_Pairwise--getExplicitPairwise.md): `array`  

### CondorcetPHP\Condorcet\Algo\Tools\Combinations Class  

* `public static bool $useBigIntegerIfAvailable`  


### CondorcetPHP\Condorcet\Algo\Tools\Permutations Class  

* `public static bool $useBigIntegerIfAvailable`  


### CondorcetPHP\Condorcet\Algo\Tools\StvQuotas Enum  

* `case Algo\Tools\StvQuotas::DROOP`  
* `case Algo\Tools\StvQuotas::HARE`  
* `case Algo\Tools\StvQuotas::HAGENBACH_BISCHOFF`  
* `case Algo\Tools\StvQuotas::IMPERIALI`  

* [public static Algo\Tools\StvQuotas::fromString (...)](/Docs/ApiReferences/Algo_Tools_StvQuotas%20Class/public%20static%20Algo_Tools_StvQuotas--fromString.md): `self`  

### CondorcetPHP\Condorcet\DataManager\DataHandlerDrivers\PdoDriver\PdoHandlerDriver Class  

* `public const SEGMENT: (array)`  

* `public static bool $preferBlobInsteadVarchar`  


### CondorcetPHP\Condorcet\Timer\Manager Class  

* [public Timer\Manager->getHistory ()](/Docs/ApiReferences/Timer_Manager%20Class/public%20Timer_Manager--getHistory.md): `array`  
* [public Timer\Manager->getObjectVersion (...)](/Docs/ApiReferences/Timer_Manager%20Class/public%20Timer_Manager--getObjectVersion.md): `string`  

### CondorcetPHP\Condorcet\Tools\Converters\CivsFormat Class  

* [public static Tools\Converters\CivsFormat::createFromElection (...)](/Docs/ApiReferences/Tools_Converters_CivsFormat%20Class/public%20static%20Tools_Converters_CivsFormat--createFromElection.md): `string|true`  

### CondorcetPHP\Condorcet\Tools\Converters\DavidHillFormat Class  

* `readonly public array $candidates`  
* `readonly public int $NumberOfSeats`  

* [public Tools\Converters\DavidHillFormat->__construct (...)](/Docs/ApiReferences/Tools_Converters_DavidHillFormat%20Class/public%20Tools_Converters_DavidHillFormat--__construct.md)  
* [public Tools\Converters\DavidHillFormat->setDataToAnElection (...)](/Docs/ApiReferences/Tools_Converters_DavidHillFormat%20Class/public%20Tools_Converters_DavidHillFormat--setDataToAnElection.md): `CondorcetPHP\Condorcet\Election`  

### CondorcetPHP\Condorcet\Tools\Converters\DebianFormat Class  

* `readonly public array $candidates`  
* `readonly public array $votes`  

* [public Tools\Converters\DebianFormat->__construct (...)](/Docs/ApiReferences/Tools_Converters_DebianFormat%20Class/public%20Tools_Converters_DebianFormat--__construct.md)  
* [public Tools\Converters\DebianFormat->setDataToAnElection (...)](/Docs/ApiReferences/Tools_Converters_DebianFormat%20Class/public%20Tools_Converters_DebianFormat--setDataToAnElection.md): `CondorcetPHP\Condorcet\Election`  

### CondorcetPHP\Condorcet\Tools\Converters\CEF\CondorcetElectionFormat Class  

* `readonly public array $parameters`  
* `readonly public array $candidates`  
* `readonly public int $numberOfSeats`  
* `readonly public bool $implicitRanking`  
* `readonly public bool $voteWeight`  
* `readonly public bool $CandidatesParsedFromVotes`  
* `readonly public int $invalidBlocksCount`  

* [public static Tools\Converters\CEF\CondorcetElectionFormat::createFromElection (...)](/Docs/ApiReferences/Tools_Converters_CEF_CondorcetElectionFormat%20Class/public%20static%20Tools_Converters_CEF_CondorcetElectionFormat--createFromElection.md): `?string`  
* [public static Tools\Converters\CEF\CondorcetElectionFormat::createFromString (...)](/Docs/ApiReferences/Tools_Converters_CEF_CondorcetElectionFormat%20Class/public%20static%20Tools_Converters_CEF_CondorcetElectionFormat--createFromString.md): `self`  
* [public Tools\Converters\CEF\CondorcetElectionFormat->__construct (...)](/Docs/ApiReferences/Tools_Converters_CEF_CondorcetElectionFormat%20Class/public%20Tools_Converters_CEF_CondorcetElectionFormat--__construct.md)  
* [public Tools\Converters\CEF\CondorcetElectionFormat->setDataToAnElection (...)](/Docs/ApiReferences/Tools_Converters_CEF_CondorcetElectionFormat%20Class/public%20Tools_Converters_CEF_CondorcetElectionFormat--setDataToAnElection.md): `CondorcetPHP\Condorcet\Election`  

### CondorcetPHP\Condorcet\Tools\Randomizers\ArrayRandomizer Class  

* `public ?int $maxCandidatesRanked`  
* `public int|false $minCandidatesRanked`  
* `public ?int $maxRanksCount`  
* `public int|float $tiesProbability`  

* [public Tools\Randomizers\ArrayRandomizer->__construct (...)](/Docs/ApiReferences/Tools_Randomizers_ArrayRandomizer%20Class/public%20Tools_Randomizers_ArrayRandomizer--__construct.md)  
* [public Tools\Randomizers\ArrayRandomizer->countCandidates ()](/Docs/ApiReferences/Tools_Randomizers_ArrayRandomizer%20Class/public%20Tools_Randomizers_ArrayRandomizer--countCandidates.md): `int`  
* [public Tools\Randomizers\ArrayRandomizer->setCandidates (...)](/Docs/ApiReferences/Tools_Randomizers_ArrayRandomizer%20Class/public%20Tools_Randomizers_ArrayRandomizer--setCandidates.md): `void`  
* [public Tools\Randomizers\ArrayRandomizer->shuffle ()](/Docs/ApiReferences/Tools_Randomizers_ArrayRandomizer%20Class/public%20Tools_Randomizers_ArrayRandomizer--shuffle.md): `array`  

### CondorcetPHP\Condorcet\Tools\Randomizers\VoteRandomizer Class  

* `public ?int $maxCandidatesRanked`  
* `public int|false $minCandidatesRanked`  
* `public ?int $maxRanksCount`  
* `public int|float $tiesProbability`  

* [public Tools\Randomizers\ArrayRandomizer->__construct (...)](/Docs/ApiReferences/Tools_Randomizers_ArrayRandomizer%20Class/public%20Tools_Randomizers_ArrayRandomizer--__construct.md)  
* [public Tools\Randomizers\ArrayRandomizer->countCandidates ()](/Docs/ApiReferences/Tools_Randomizers_ArrayRandomizer%20Class/public%20Tools_Randomizers_ArrayRandomizer--countCandidates.md): `int`  
* [public Tools\Randomizers\VoteRandomizer->getNewVote ()](/Docs/ApiReferences/Tools_Randomizers_VoteRandomizer%20Class/public%20Tools_Randomizers_VoteRandomizer--getNewVote.md): `CondorcetPHP\Condorcet\Vote`  
* [public Tools\Randomizers\ArrayRandomizer->setCandidates (...)](/Docs/ApiReferences/Tools_Randomizers_ArrayRandomizer%20Class/public%20Tools_Randomizers_ArrayRandomizer--setCandidates.md): `void`  
* [public Tools\Randomizers\ArrayRandomizer->shuffle ()](/Docs/ApiReferences/Tools_Randomizers_ArrayRandomizer%20Class/public%20Tools_Randomizers_ArrayRandomizer--shuffle.md): `array`  

### CondorcetPHP\Condorcet\Utils\CondorcetUtil Class  

* [public static Utils\CondorcetUtil::format (...)](/Docs/ApiReferences/Utils_CondorcetUtil%20Class/public%20static%20Utils_CondorcetUtil--format.md): `mixed`  



## Full Class & API References
_Including above methods from public API_


#### `Abstract CondorcetPHP\Condorcet\Algo\Method `  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Method.php#L26)

```php
* public const IS_PROPORTIONAL: (boolean)
* public const IS_DETERMINISTIC: (boolean)
* public const DECIMAL_PRECISION: (integer)

* public static ?int $MaxCandidates
* protected ?CondorcetPHP\Condorcet\Result $Result
* protected WeakReference $selfElection
* protected string $objectVersion

* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getObjectVersion (bool $major = false): string  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected compute (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\StatsInterface  
```

#### `CondorcetPHP\Condorcet\Algo\Methods\Borda\BordaCount extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/Borda/BordaCount.php#L21)

```php
* public const METHOD_NAME: (array)
* public const IS_PROPORTIONAL: (boolean)
* public const IS_DETERMINISTIC: (boolean)
* public const DECIMAL_PRECISION: (integer)

* public static int $optionStarting
* readonly protected array $Stats
* public static ?int $MaxCandidates
* protected ?CondorcetPHP\Condorcet\Result $Result
* protected WeakReference $selfElection
* protected string $objectVersion

* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getObjectVersion (bool $major = false): string  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected compute (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getScoreByCandidateRanking (int $CandidatesRanked, CondorcetPHP\Condorcet\Election $election): float  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
```

#### `CondorcetPHP\Condorcet\Algo\Methods\Borda\DowdallSystem extends CondorcetPHP\Condorcet\Algo\Methods\Borda\BordaCount implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/Borda/DowdallSystem.php#L19)

```php
* public const METHOD_NAME: (array)
* public const IS_PROPORTIONAL: (boolean)
* public const IS_DETERMINISTIC: (boolean)
* public const DECIMAL_PRECISION: (integer)

* public static int $optionStarting
* readonly protected array $Stats
* public static ?int $MaxCandidates
* protected ?CondorcetPHP\Condorcet\Result $Result
* protected WeakReference $selfElection
* protected string $objectVersion

* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getObjectVersion (bool $major = false): string  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected compute (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getScoreByCandidateRanking (int $CandidatesRanked, CondorcetPHP\Condorcet\Election $election): float  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
```

#### `CondorcetPHP\Condorcet\Algo\Methods\CondorcetBasic extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/CondorcetBasic.php#L21)

```php
* public const METHOD_NAME: (array)
* public const IS_PROPORTIONAL: (boolean)
* public const IS_DETERMINISTIC: (boolean)
* public const DECIMAL_PRECISION: (integer)

* protected ?int $CondorcetWinner
* protected ?int $CondorcetLoser
* public static ?int $MaxCandidates
* protected ?CondorcetPHP\Condorcet\Result $Result
* protected WeakReference $selfElection
* protected string $objectVersion

* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getLoser (): ?int  
* public getObjectVersion (bool $major = false): string  
* public getResult (): never  
* public getWinner (): ?int  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected compute (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\StatsInterface  
```

#### `CondorcetPHP\Condorcet\Algo\Methods\Copeland\Copeland extends CondorcetPHP\Condorcet\Algo\Methods\PairwiseStatsBased_Core implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/Copeland/Copeland.php#L20)

```php
* public const METHOD_NAME: (array)
* protected const COUNT_TYPE: (string)
* public const IS_PROPORTIONAL: (boolean)
* public const IS_DETERMINISTIC: (boolean)
* public const DECIMAL_PRECISION: (integer)

* readonly protected array $Comparison
* public static ?int $MaxCandidates
* protected ?CondorcetPHP\Condorcet\Result $Result
* protected WeakReference $selfElection
* protected string $objectVersion

* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getObjectVersion (bool $major = false): string  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected compute (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
* protected looking (array $challenge): int  
* protected makeRanking (): void  
```

#### `CondorcetPHP\Condorcet\Algo\Methods\Dodgson\DodgsonQuick extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/Dodgson/DodgsonQuick.php#L21)

```php
* public const METHOD_NAME: (array)
* public const IS_PROPORTIONAL: (boolean)
* public const IS_DETERMINISTIC: (boolean)
* public const DECIMAL_PRECISION: (integer)

* readonly protected array $Stats
* public static ?int $MaxCandidates
* protected ?CondorcetPHP\Condorcet\Result $Result
* protected WeakReference $selfElection
* protected string $objectVersion

* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getObjectVersion (bool $major = false): string  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected compute (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
```

#### `CondorcetPHP\Condorcet\Algo\Methods\Dodgson\DodgsonTidemanApproximation extends CondorcetPHP\Condorcet\Algo\Methods\PairwiseStatsBased_Core implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/Dodgson/DodgsonTidemanApproximation.php#L21)

```php
* public const METHOD_NAME: (array)
* protected const COUNT_TYPE: (string)
* public const IS_PROPORTIONAL: (boolean)
* public const IS_DETERMINISTIC: (boolean)
* public const DECIMAL_PRECISION: (integer)

* readonly protected array $Comparison
* public static ?int $MaxCandidates
* protected ?CondorcetPHP\Condorcet\Result $Result
* protected WeakReference $selfElection
* protected string $objectVersion

* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getObjectVersion (bool $major = false): string  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected compute (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
* protected looking (array $challenge): int  
* protected makeRanking (): void  
```

#### `Abstract CondorcetPHP\Condorcet\Algo\Methods\HighestAverages\HighestAverages_Core extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/HighestAverages/HighestAverages_Core.php#L20)

```php
* final public const IS_PROPORTIONAL: (boolean)
* public const IS_DETERMINISTIC: (boolean)
* public const DECIMAL_PRECISION: (integer)
* public const METHOD_NAME: (array)

* protected array $candidatesVotes
* protected array $candidatesSeats
* protected array $rounds
* public static ?int $MaxCandidates
* protected ?CondorcetPHP\Condorcet\Result $Result
* protected WeakReference $selfElection
* protected string $objectVersion

* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getObjectVersion (bool $major = false): string  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected compute (): void  
* protected computeQuotient (int $votesWeight, int $seats): float  
* protected countVotesPerCandidates (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
* protected makeRounds (): array  
```

#### `CondorcetPHP\Condorcet\Algo\Methods\HighestAverages\Jefferson extends CondorcetPHP\Condorcet\Algo\Methods\HighestAverages\HighestAverages_Core implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/HighestAverages/Jefferson.php#L20)

```php
* public const METHOD_NAME: (array)
* final public const IS_PROPORTIONAL: (boolean)
* public const IS_DETERMINISTIC: (boolean)
* public const DECIMAL_PRECISION: (integer)

* protected array $candidatesVotes
* protected array $candidatesSeats
* protected array $rounds
* public static ?int $MaxCandidates
* protected ?CondorcetPHP\Condorcet\Result $Result
* protected WeakReference $selfElection
* protected string $objectVersion

* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getObjectVersion (bool $major = false): string  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected compute (): void  
* protected computeQuotient (int $votesWeight, int $seats): float  
* protected countVotesPerCandidates (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
* protected makeRounds (): array  
```

#### `CondorcetPHP\Condorcet\Algo\Methods\HighestAverages\SainteLague extends CondorcetPHP\Condorcet\Algo\Methods\HighestAverages\HighestAverages_Core implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/HighestAverages/SainteLague.php#L20)

```php
* public const METHOD_NAME: (array)
* final public const IS_PROPORTIONAL: (boolean)
* public const IS_DETERMINISTIC: (boolean)
* public const DECIMAL_PRECISION: (integer)

* public static int|float $optionFirstDivisor
* protected array $candidatesVotes
* protected array $candidatesSeats
* protected array $rounds
* public static ?int $MaxCandidates
* protected ?CondorcetPHP\Condorcet\Result $Result
* protected WeakReference $selfElection
* protected string $objectVersion

* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getObjectVersion (bool $major = false): string  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected compute (): void  
* protected computeQuotient (int $votesWeight, int $seats): float  
* protected countVotesPerCandidates (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
* protected makeRounds (): array  
```

#### `CondorcetPHP\Condorcet\Algo\Methods\InstantRunoff\InstantRunoff extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/InstantRunoff/InstantRunoff.php#L21)

```php
* public const METHOD_NAME: (array)
* public const IS_PROPORTIONAL: (boolean)
* public const IS_DETERMINISTIC: (boolean)
* public const DECIMAL_PRECISION: (integer)

* readonly protected array $Stats
* readonly public float $majority
* public static ?int $MaxCandidates
* protected ?CondorcetPHP\Condorcet\Result $Result
* protected WeakReference $selfElection
* protected string $objectVersion

* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getObjectVersion (bool $major = false): string  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected compute (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
* protected makeScore (array $candidateDone): array  
```

#### `CondorcetPHP\Condorcet\Algo\Methods\KemenyYoung\KemenyYoung extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/KemenyYoung/KemenyYoung.php#L24)

```php
* public const METHOD_NAME: (array)
* final public const CONFLICT_WARNING_CODE: (integer)
* public const IS_PROPORTIONAL: (boolean)
* public const IS_DETERMINISTIC: (boolean)
* public const DECIMAL_PRECISION: (integer)

* public static ?int $MaxCandidates
* readonly protected int $countElectionCandidates
* readonly protected array $candidatesKey
* readonly protected int $countPossibleRanking
* protected int $MaxScore
* protected int $Conflits
* protected int $bestRankingKey
* protected array $bestRankingTab
* protected ?CondorcetPHP\Condorcet\Result $Result
* protected WeakReference $selfElection
* protected string $objectVersion

* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getObjectVersion (bool $major = false): string  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected compute (): void  
* protected computeMaxAndConflicts (): void  
* protected computeOneScore (array $ranking, CondorcetPHP\Condorcet\Algo\Pairwise\Pairwise $pairwise): int  
* protected conflictInfos (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getPossibleRankingIterator (): Generator  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
* protected makeRanking (): void  
```

#### `CondorcetPHP\Condorcet\Algo\Methods\LargestRemainder\LargestRemainder extends CondorcetPHP\Condorcet\Algo\Methods\HighestAverages\HighestAverages_Core implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/LargestRemainder/LargestRemainder.php#L22)

```php
* public const METHOD_NAME: (array)
* final public const IS_PROPORTIONAL: (boolean)
* public const IS_DETERMINISTIC: (boolean)
* public const DECIMAL_PRECISION: (integer)

* public static CondorcetPHP\Condorcet\Algo\Tools\StvQuotas $optionQuota
* protected array $candidatesVotes
* protected array $candidatesSeats
* protected array $rounds
* public static ?int $MaxCandidates
* protected ?CondorcetPHP\Condorcet\Result $Result
* protected WeakReference $selfElection
* protected string $objectVersion

* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getObjectVersion (bool $major = false): string  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected compute (): void  
* protected computeQuotient (int $votesWeight, int $seats): float  
* protected countVotesPerCandidates (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
* protected makeRounds (): array  
```

#### `CondorcetPHP\Condorcet\Algo\Methods\Lotteries\RandomBallot extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/Lotteries/RandomBallot.php#L21)

```php
* public const METHOD_NAME: (array)
* public const IS_DETERMINISTIC: (boolean)
* public const IS_PROPORTIONAL: (boolean)
* public const DECIMAL_PRECISION: (integer)

* public static ?Random\Randomizer $optionRandomizer
* readonly protected int $totalElectionWeight
* readonly protected int $electedWeightLevel
* readonly protected int $electedBallotKey
* public static ?int $MaxCandidates
* protected ?CondorcetPHP\Condorcet\Result $Result
* protected WeakReference $selfElection
* protected string $objectVersion

* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public compute (): void  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getObjectVersion (bool $major = false): string  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
```

#### `CondorcetPHP\Condorcet\Algo\Methods\Lotteries\RandomCandidates extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/Lotteries/RandomCandidates.php#L23)

```php
* public const METHOD_NAME: (array)
* public const IS_DETERMINISTIC: (boolean)
* public const IS_PROPORTIONAL: (boolean)
* public const DECIMAL_PRECISION: (integer)

* public static ?Random\Randomizer $optionRandomizer
* public static int|float $optionTiesProbability
* public static ?int $MaxCandidates
* protected ?CondorcetPHP\Condorcet\Result $Result
* protected WeakReference $selfElection
* protected string $objectVersion

* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public compute (): void  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getObjectVersion (bool $major = false): string  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
```

#### `CondorcetPHP\Condorcet\Algo\Methods\Majority\FirstPastThePost extends CondorcetPHP\Condorcet\Algo\Methods\Majority\MajorityCore implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/Majority/FirstPastThePost.php#L17)

```php
* public const METHOD_NAME: (array)
* public const IS_PROPORTIONAL: (boolean)
* public const IS_DETERMINISTIC: (boolean)
* public const DECIMAL_PRECISION: (integer)

* protected int $maxRound
* protected int $targetNumberOfCandidatesForTheNextRound
* protected int $numberOfTargetedCandidatesAfterEachRound
* protected array $admittedCandidates
* readonly protected array $Stats
* public static ?int $MaxCandidates
* protected ?CondorcetPHP\Condorcet\Result $Result
* protected WeakReference $selfElection
* protected string $objectVersion

* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getObjectVersion (bool $major = false): string  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected compute (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected doOneRound (): array  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
```

#### `Abstract CondorcetPHP\Condorcet\Algo\Methods\Majority\MajorityCore extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/Majority/MajorityCore.php#L20)

```php
* public const IS_PROPORTIONAL: (boolean)
* public const IS_DETERMINISTIC: (boolean)
* public const DECIMAL_PRECISION: (integer)
* public const METHOD_NAME: (array)

* protected int $maxRound
* protected int $targetNumberOfCandidatesForTheNextRound
* protected int $numberOfTargetedCandidatesAfterEachRound
* protected array $admittedCandidates
* readonly protected array $Stats
* public static ?int $MaxCandidates
* protected ?CondorcetPHP\Condorcet\Result $Result
* protected WeakReference $selfElection
* protected string $objectVersion

* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getObjectVersion (bool $major = false): string  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected compute (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected doOneRound (): array  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
```

#### `CondorcetPHP\Condorcet\Algo\Methods\Majority\MultipleRoundsSystem extends CondorcetPHP\Condorcet\Algo\Methods\Majority\MajorityCore implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/Majority/MultipleRoundsSystem.php#L19)

```php
* public const METHOD_NAME: (array)
* public const IS_PROPORTIONAL: (boolean)
* public const IS_DETERMINISTIC: (boolean)
* public const DECIMAL_PRECISION: (integer)

* protected static int $optionMAX_ROUND
* protected static int $optionTARGET_NUMBER_OF_CANDIDATES_FOR_THE_NEXT_ROUND
* protected static int $optionNUMBER_OF_TARGETED_CANDIDATES_AFTER_EACH_ROUND
* protected int $maxRound
* protected int $targetNumberOfCandidatesForTheNextRound
* protected int $numberOfTargetedCandidatesAfterEachRound
* protected array $admittedCandidates
* readonly protected array $Stats
* public static ?int $MaxCandidates
* protected ?CondorcetPHP\Condorcet\Result $Result
* protected WeakReference $selfElection
* protected string $objectVersion

* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getObjectVersion (bool $major = false): string  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected compute (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected doOneRound (): array  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
```

#### `CondorcetPHP\Condorcet\Algo\Methods\Minimax\MinimaxMargin extends CondorcetPHP\Condorcet\Algo\Methods\PairwiseStatsBased_Core implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/Minimax/MinimaxMargin.php#L20)

```php
* public const METHOD_NAME: (array)
* protected const COUNT_TYPE: (string)
* public const IS_PROPORTIONAL: (boolean)
* public const IS_DETERMINISTIC: (boolean)
* public const DECIMAL_PRECISION: (integer)

* readonly protected array $Comparison
* public static ?int $MaxCandidates
* protected ?CondorcetPHP\Condorcet\Result $Result
* protected WeakReference $selfElection
* protected string $objectVersion

* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getObjectVersion (bool $major = false): string  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected compute (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
* protected looking (array $challenge): int  
* protected makeRanking (): void  
```

#### `CondorcetPHP\Condorcet\Algo\Methods\Minimax\MinimaxOpposition extends CondorcetPHP\Condorcet\Algo\Methods\PairwiseStatsBased_Core implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/Minimax/MinimaxOpposition.php#L20)

```php
* public const METHOD_NAME: (array)
* protected const COUNT_TYPE: (string)
* public const IS_PROPORTIONAL: (boolean)
* public const IS_DETERMINISTIC: (boolean)
* public const DECIMAL_PRECISION: (integer)

* readonly protected array $Comparison
* public static ?int $MaxCandidates
* protected ?CondorcetPHP\Condorcet\Result $Result
* protected WeakReference $selfElection
* protected string $objectVersion

* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getObjectVersion (bool $major = false): string  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected compute (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
* protected looking (array $challenge): int  
* protected makeRanking (): void  
```

#### `CondorcetPHP\Condorcet\Algo\Methods\Minimax\MinimaxWinning extends CondorcetPHP\Condorcet\Algo\Methods\PairwiseStatsBased_Core implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/Minimax/MinimaxWinning.php#L20)

```php
* public const METHOD_NAME: (array)
* protected const COUNT_TYPE: (string)
* public const IS_PROPORTIONAL: (boolean)
* public const IS_DETERMINISTIC: (boolean)
* public const DECIMAL_PRECISION: (integer)

* readonly protected array $Comparison
* public static ?int $MaxCandidates
* protected ?CondorcetPHP\Condorcet\Result $Result
* protected WeakReference $selfElection
* protected string $objectVersion

* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getObjectVersion (bool $major = false): string  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected compute (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
* protected looking (array $challenge): int  
* protected makeRanking (): void  
```

#### `Abstract CondorcetPHP\Condorcet\Algo\Methods\PairwiseStatsBased_Core extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/PairwiseStatsBased_Core.php#L20)

```php
* protected const COUNT_TYPE: (string)
* public const IS_PROPORTIONAL: (boolean)
* public const IS_DETERMINISTIC: (boolean)
* public const DECIMAL_PRECISION: (integer)
* public const METHOD_NAME: (array)

* readonly protected array $Comparison
* public static ?int $MaxCandidates
* protected ?CondorcetPHP\Condorcet\Result $Result
* protected WeakReference $selfElection
* protected string $objectVersion

* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getObjectVersion (bool $major = false): string  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected compute (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
* protected looking (array $challenge): int  
* protected makeRanking (): void  
```

#### `CondorcetPHP\Condorcet\Algo\Methods\RankedPairs\RP_VARIANT implements UnitEnum`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/RankedPairs/RP_VARIANT.php#L17)

```php
* `case RP_VARIANT::UNDEFINED`  
* `case RP_VARIANT::WINNING`  
* `case RP_VARIANT::MARGIN`  
* `case RP_VARIANT::MINORITY`  

* readonly public string $name

```

#### `Abstract CondorcetPHP\Condorcet\Algo\Methods\RankedPairs\RankedPairsCore extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/RankedPairs/RankedPairsCore.php#L22)

```php
* protected const VARIANT: (object)
* public const IS_PROPORTIONAL: (boolean)
* public const IS_DETERMINISTIC: (boolean)
* public const DECIMAL_PRECISION: (integer)
* public const METHOD_NAME: (array)

* public static ?int $MaxCandidates
* readonly protected array $PairwiseSort
* protected array $Arcs
* protected array $Stats
* protected bool $StatsDone
* protected ?CondorcetPHP\Condorcet\Result $Result
* protected WeakReference $selfElection
* protected string $objectVersion

* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getObjectVersion (bool $major = false): string  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected compute (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected followCycle (array $virtualArcs, int $startCandidateKey, int $searchCandidateKey, array $done = []): array  
* protected getArcsInCycle (array $virtualArcs): array  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
* protected getWinners (array $alreadyDone): array  
* protected makeArcs (): void  
* protected makeResult (): array  
* protected pairwiseSort (): array  
```

#### `CondorcetPHP\Condorcet\Algo\Methods\RankedPairs\RankedPairsMargin extends CondorcetPHP\Condorcet\Algo\Methods\RankedPairs\RankedPairsCore implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/RankedPairs/RankedPairsMargin.php#L18)

```php
* public const METHOD_NAME: (array)
* protected const VARIANT: (object)
* public const IS_PROPORTIONAL: (boolean)
* public const IS_DETERMINISTIC: (boolean)
* public const DECIMAL_PRECISION: (integer)

* public static ?int $MaxCandidates
* readonly protected array $PairwiseSort
* protected array $Arcs
* protected array $Stats
* protected bool $StatsDone
* protected ?CondorcetPHP\Condorcet\Result $Result
* protected WeakReference $selfElection
* protected string $objectVersion

* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getObjectVersion (bool $major = false): string  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected compute (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected followCycle (array $virtualArcs, int $startCandidateKey, int $searchCandidateKey, array $done = []): array  
* protected getArcsInCycle (array $virtualArcs): array  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
* protected getWinners (array $alreadyDone): array  
* protected makeArcs (): void  
* protected makeResult (): array  
* protected pairwiseSort (): array  
```

#### `CondorcetPHP\Condorcet\Algo\Methods\RankedPairs\RankedPairsWinning extends CondorcetPHP\Condorcet\Algo\Methods\RankedPairs\RankedPairsCore implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/RankedPairs/RankedPairsWinning.php#L18)

```php
* public const METHOD_NAME: (array)
* protected const VARIANT: (object)
* public const IS_PROPORTIONAL: (boolean)
* public const IS_DETERMINISTIC: (boolean)
* public const DECIMAL_PRECISION: (integer)

* public static ?int $MaxCandidates
* readonly protected array $PairwiseSort
* protected array $Arcs
* protected array $Stats
* protected bool $StatsDone
* protected ?CondorcetPHP\Condorcet\Result $Result
* protected WeakReference $selfElection
* protected string $objectVersion

* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getObjectVersion (bool $major = false): string  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected compute (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected followCycle (array $virtualArcs, int $startCandidateKey, int $searchCandidateKey, array $done = []): array  
* protected getArcsInCycle (array $virtualArcs): array  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
* protected getWinners (array $alreadyDone): array  
* protected makeArcs (): void  
* protected makeResult (): array  
* protected pairwiseSort (): array  
```

#### `CondorcetPHP\Condorcet\Algo\Methods\STV\CPO_STV extends CondorcetPHP\Condorcet\Algo\Methods\STV\SingleTransferableVote implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/STV/CPO_STV.php#L33)

```php
* public const METHOD_NAME: (array)
* public const DEFAULT_METHODS_CHAINING: (array)
* final public const IS_PROPORTIONAL: (boolean)
* public const IS_DETERMINISTIC: (boolean)
* public const DECIMAL_PRECISION: (integer)

* public static ?int $MaxOutcomeComparisons
* public static CondorcetPHP\Condorcet\Algo\Tools\StvQuotas $optionQuota
* public static array $optionCondorcetCompletionMethod
* public static array $optionTieBreakerMethods
* protected SplFixedArray $outcomes
* protected SplFixedArray $outcomeComparisonTable
* readonly protected array $initialScoreTable
* protected array $candidatesElectedFromFirstRound
* readonly protected array $candidatesEliminatedFromFirstRound
* readonly protected int $condorcetWinnerOutcome
* readonly protected array $completionMethodPairwise
* readonly protected CondorcetPHP\Condorcet\Result $completionMethodResult
* readonly protected array $Stats
* protected float $votesNeededToWin
* public static ?int $MaxCandidates
* protected ?CondorcetPHP\Condorcet\Result $Result
* protected WeakReference $selfElection
* protected string $objectVersion

* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getObjectVersion (bool $major = false): string  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected compareOutcomes (): void  
* protected compute (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getOutcomesComparisonKey (int $MainOutcomeKey, int $ComparedOutcomeKey): string  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
* protected makeScore (array $surplus = [], array $candidateElected = [], array $candidateEliminated = []): array  
* protected selectBestOutcome (): void  
* protected sortResultBeforeCut (array $result): void  
```

#### `CondorcetPHP\Condorcet\Algo\Methods\STV\SingleTransferableVote extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/STV/SingleTransferableVote.php#L23)

```php
* final public const IS_PROPORTIONAL: (boolean)
* public const METHOD_NAME: (array)
* public const IS_DETERMINISTIC: (boolean)
* public const DECIMAL_PRECISION: (integer)

* public static CondorcetPHP\Condorcet\Algo\Tools\StvQuotas $optionQuota
* readonly protected array $Stats
* protected float $votesNeededToWin
* public static ?int $MaxCandidates
* protected ?CondorcetPHP\Condorcet\Result $Result
* protected WeakReference $selfElection
* protected string $objectVersion

* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getObjectVersion (bool $major = false): string  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected compute (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
* protected makeScore (array $surplus = [], array $candidateElected = [], array $candidateEliminated = []): array  
```

#### `Abstract CondorcetPHP\Condorcet\Algo\Methods\Schulze\SchulzeCore extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/Schulze/SchulzeCore.php#L22)

```php
* public const IS_PROPORTIONAL: (boolean)
* public const IS_DETERMINISTIC: (boolean)
* public const DECIMAL_PRECISION: (integer)
* public const METHOD_NAME: (array)

* protected array $StrongestPaths
* public static ?int $MaxCandidates
* protected ?CondorcetPHP\Condorcet\Result $Result
* protected WeakReference $selfElection
* protected string $objectVersion

* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getObjectVersion (bool $major = false): string  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected compute (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
* protected makeRanking (): void  
* protected makeStrongestPaths (): void  
* protected prepareStrongestPath (): void  
* protected schulzeVariant (int $i, int $j, CondorcetPHP\Condorcet\Election $election): int|float  
```

#### `CondorcetPHP\Condorcet\Algo\Methods\Schulze\SchulzeMargin extends CondorcetPHP\Condorcet\Algo\Methods\Schulze\SchulzeCore implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/Schulze/SchulzeMargin.php#L19)

```php
* public const METHOD_NAME: (array)
* public const IS_PROPORTIONAL: (boolean)
* public const IS_DETERMINISTIC: (boolean)
* public const DECIMAL_PRECISION: (integer)

* protected array $StrongestPaths
* public static ?int $MaxCandidates
* protected ?CondorcetPHP\Condorcet\Result $Result
* protected WeakReference $selfElection
* protected string $objectVersion

* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getObjectVersion (bool $major = false): string  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected compute (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
* protected makeRanking (): void  
* protected makeStrongestPaths (): void  
* protected prepareStrongestPath (): void  
* protected schulzeVariant (int $i, int $j, CondorcetPHP\Condorcet\Election $election): int  
```

#### `CondorcetPHP\Condorcet\Algo\Methods\Schulze\SchulzeRatio extends CondorcetPHP\Condorcet\Algo\Methods\Schulze\SchulzeCore implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/Schulze/SchulzeRatio.php#L19)

```php
* public const METHOD_NAME: (array)
* public const IS_PROPORTIONAL: (boolean)
* public const IS_DETERMINISTIC: (boolean)
* public const DECIMAL_PRECISION: (integer)

* protected array $StrongestPaths
* public static ?int $MaxCandidates
* protected ?CondorcetPHP\Condorcet\Result $Result
* protected WeakReference $selfElection
* protected string $objectVersion

* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getObjectVersion (bool $major = false): string  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected compute (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
* protected makeRanking (): void  
* protected makeStrongestPaths (): void  
* protected prepareStrongestPath (): void  
* protected schulzeVariant (int $i, int $j, CondorcetPHP\Condorcet\Election $election): float  
```

#### `CondorcetPHP\Condorcet\Algo\Methods\Schulze\SchulzeWinning extends CondorcetPHP\Condorcet\Algo\Methods\Schulze\SchulzeCore implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/Schulze/SchulzeWinning.php#L19)

```php
* public const METHOD_NAME: (array)
* public const IS_PROPORTIONAL: (boolean)
* public const IS_DETERMINISTIC: (boolean)
* public const DECIMAL_PRECISION: (integer)

* protected array $StrongestPaths
* public static ?int $MaxCandidates
* protected ?CondorcetPHP\Condorcet\Result $Result
* protected WeakReference $selfElection
* protected string $objectVersion

* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getObjectVersion (bool $major = false): string  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected compute (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
* protected makeRanking (): void  
* protected makeStrongestPaths (): void  
* protected prepareStrongestPath (): void  
* protected schulzeVariant (int $i, int $j, CondorcetPHP\Condorcet\Election $election): int  
```

#### `CondorcetPHP\Condorcet\Algo\Methods\Smith\SchwartzSet extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/Smith/SchwartzSet.php#L25)

```php
* public const METHOD_NAME: (array)
* public const IS_PROPORTIONAL: (boolean)
* public const IS_DETERMINISTIC: (boolean)
* public const DECIMAL_PRECISION: (integer)

* readonly protected array $SchwartzSet
* public static ?int $MaxCandidates
* protected ?CondorcetPHP\Condorcet\Result $Result
* protected WeakReference $selfElection
* protected string $objectVersion

* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getObjectVersion (bool $major = false): string  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected compute (): void  
* protected computeSchwartzSet (): array  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
* private consolidateStrongComponents (array $graph, array $sccs): array  
* private dfs (array $graph, int $node, array $visited, array $stack): void  
* private dfsReverse (array $graph, int $node, array $visited, array $scc): void  
* private findStronglyConnectedComponents (array $graph, array $reversedGraph, array $nodes): array  
```

#### `CondorcetPHP\Condorcet\Algo\Methods\Smith\SmithSet extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/Smith/SmithSet.php#L25)

```php
* public const METHOD_NAME: (array)
* public const IS_PROPORTIONAL: (boolean)
* public const IS_DETERMINISTIC: (boolean)
* public const DECIMAL_PRECISION: (integer)

* readonly protected array $SmithSet
* public static ?int $MaxCandidates
* protected ?CondorcetPHP\Condorcet\Result $Result
* protected WeakReference $selfElection
* protected string $objectVersion

* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getObjectVersion (bool $major = false): string  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected compute (): void  
* protected computeSmithSet (): array  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
```

#### `CondorcetPHP\Condorcet\Algo\Pairwise\FilteredPairwise extends CondorcetPHP\Condorcet\Algo\Pairwise\Pairwise implements Traversable, Iterator, ArrayAccess`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Pairwise/FilteredPairwise.php#L18)

```php
* readonly protected array $candidates
* readonly public ?array $tags
* readonly public int|bool $withTags
* readonly protected array $Pairwise_Model
* protected array $Pairwise
* protected ?array $explicitPairwise
* protected WeakReference $selfElection
* protected string $objectVersion

* public __construct (CondorcetPHP\Condorcet\Election $link, array|string|null $tags = null, int|bool $withTags = true)  
* public __serialize (): array  
* public addNewVote (int $key): void  
* public current (): array  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
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
* protected clearExplicitPairwiseCache (): void  
* protected computeOneVote (array $pairwise, CondorcetPHP\Condorcet\Vote $oneVote): void  
* protected doPairwise (): void  
* protected formatNewpairwise (): void  
* protected getCandidateNameFromKey (int $candidateKey): string  
* protected getVotesManagerGenerator (): Generator  
```

#### `CondorcetPHP\Condorcet\Algo\Pairwise\Pairwise implements ArrayAccess, Iterator, Traversable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Pairwise/Pairwise.php#L23)

```php
* private bool $valid
* readonly protected array $Pairwise_Model
* protected array $Pairwise
* protected ?array $explicitPairwise
* protected WeakReference $selfElection
* protected string $objectVersion

* public __construct (CondorcetPHP\Condorcet\Election $link)  
* public __serialize (): array  
* public addNewVote (int $key): void  
* public current (): array  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
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
* protected clearExplicitPairwiseCache (): void  
* protected computeOneVote (array $pairwise, CondorcetPHP\Condorcet\Vote $oneVote): void  
* protected doPairwise (): void  
* protected formatNewpairwise (): void  
* protected getCandidateNameFromKey (int $candidateKey): string  
* protected getVotesManagerGenerator (): Generator  
```

#### `CondorcetPHP\Condorcet\Algo\StatsVerbosity implements UnitEnum, BackedEnum`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/StatsVerbosity.php#L16)

```php
* `case StatsVerbosity::NONE`  
* `case StatsVerbosity::LOW`  
* `case StatsVerbosity::STD`  
* `case StatsVerbosity::HIGH`  
* `case StatsVerbosity::FULL`  
* `case StatsVerbosity::DEBUG`  

* readonly public string $name
* readonly public int $value

```

#### `CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats implements CondorcetPHP\Condorcet\Algo\Stats\StatsInterface, Traversable, IteratorAggregate, ArrayAccess`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Stats/BaseMethodStats.php#L19)

```php
* readonly public string $buildByMethod
* protected array $data
* public array $asArray
* public bool $closed

* public __construct (?array $data = null, bool $closed = true)  
* public close (): static  
* public getEntry (string $entryName): mixed  
* public getIterator (): Traversable  
* public offsetExists (mixed $offset): bool  
* public offsetGet (mixed $offset): mixed  
* public offsetSet (mixed $offset, mixed $value): void  
* public offsetUnset (mixed $offset): void  
* public setEntry (string $key, mixed $entry): static  
* public unsetEntry (string $key): static  
* protected throwAlreadyBuild (): never  
```

#### `CondorcetPHP\Condorcet\Algo\Stats\EmptyStats implements CondorcetPHP\Condorcet\Algo\Stats\StatsInterface, Traversable, IteratorAggregate, ArrayAccess`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Stats/EmptyStats.php#L17)

```php
* public array $asArray

* public getEntry (string $entryName): never  
* public getIterator (): Traversable  
* public offsetExists (mixed $offset): bool  
* public offsetGet (mixed $offset): never  
* public offsetSet (mixed $offset, mixed $value): void  
* public offsetUnset (mixed $offset): void  
```

#### `CondorcetPHP\Condorcet\Algo\Tools\Combinations `  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Tools/Combinations.php#L23)

```php
* public static bool $useBigIntegerIfAvailable

* public static compute (array $values, int $length, array $append_before = []): SplFixedArray  
* public static computeGenerator (array $values, int $length, array $append_before = []): Generator  
* public static getPossibleCountOfCombinations (int $count, int $length): int  
```

#### `Abstract CondorcetPHP\Condorcet\Algo\Tools\PairwiseStats `  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Tools/PairwiseStats.php#L20)

```php
* public static PairwiseComparison (CondorcetPHP\Condorcet\Algo\Pairwise\Pairwise $pairwise): array  
```

#### `CondorcetPHP\Condorcet\Algo\Tools\Permutations `  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Tools/Permutations.php#L24)

```php
* public static bool $useBigIntegerIfAvailable
* readonly public array $candidates

* public static getPossibleCountOfPermutations (int $candidatesNumber): int  
* public __construct (array $candidates)  
* public getPermutationGenerator (): Generator  
* public getResults (): SplFixedArray  
* protected permutationGenerator (array $elements): Generator  
```

#### `CondorcetPHP\Condorcet\Algo\Tools\StvQuotas implements UnitEnum, BackedEnum`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Tools/StvQuotas.php#L21)

```php
* `case StvQuotas::DROOP`  
* `case StvQuotas::HARE`  
* `case StvQuotas::HAGENBACH_BISCHOFF`  
* `case StvQuotas::IMPERIALI`  

* readonly public string $name
* readonly public string $value

* public static fromString (string $quota): self  
* public getQuota (int $votesWeight, int $seats): float  
```

#### `Abstract CondorcetPHP\Condorcet\Algo\Tools\TieBreakersCollection `  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Tools/TieBreakersCollection.php#L23)

```php
* public static electSomeLosersbasedOnPairwiseComparaison (CondorcetPHP\Condorcet\Election $election, array $candidatesKeys): array  
* public static tieBreakerWithAnotherMethods (CondorcetPHP\Condorcet\Election $election, array $methods, array $candidatesKeys): array  
```

#### `Abstract CondorcetPHP\Condorcet\Algo\Tools\VirtualVote `  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Tools/VirtualVote.php#L17)

```php
* public static removeCandidates (CondorcetPHP\Condorcet\Vote $vote, array $candidatesList): CondorcetPHP\Condorcet\Vote  
```

#### `CondorcetPHP\Condorcet\Algo\Tools\VotesDeductedApprovals implements Countable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Tools/VotesDeductedApprovals.php#L22)

```php
* protected array $combinationsScore
* readonly public int $subsetSize
* protected WeakReference $selfElection

* protected static getCombinationsScoreKey (array $oneCombination): string  
* protected static voteHasCandidates (array $voteCandidatesKey, array $combination): bool  
* public __construct (int $subsetSize, CondorcetPHP\Condorcet\Election $election)  
* public count (): int  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* public sumWeightIfVotesIncludeCandidates (array $candidatesKeys): int  
```

#### `CondorcetPHP\Condorcet\Candidate implements Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Candidate.php#L20)

```php
* public string $name
* public array $nameHistory
* public bool $provisionalState
* public float $createdAt
* public float $updatedAt
* private ?WeakMap $link
* protected string $objectVersion

* public __clone (): void  
* public __construct (string $name)  
* public __serialize (): array  
* public __toString (): string  
* public countLinks (): int  
* public destroyLink (CondorcetPHP\Condorcet\Election $election): bool  
* public getLinks (): array  
* public getObjectVersion (bool $major = false): string  
* public haveLink (CondorcetPHP\Condorcet\Election $election): bool  
* public registerLink (CondorcetPHP\Condorcet\Election $election): void  
* public setName (string $name): static  
* public setProvisionalState (bool $provisional): void  
* protected destroyAllLink (): void  
* protected initWeakMap (): void  
* private checkNameInElectionContext (string $name): bool  
```

#### `Abstract CondorcetPHP\Condorcet\Condorcet `  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Condorcet.php#L53)

```php
* final public const AUTHOR: (string)
* final public const HOMEPAGE: (string)
* final public const VERSION: (string)
* final public const CONDORCET_BASIC_CLASS: (string)

* protected static ?string $defaultMethod
* protected static array $authMethods
* public static bool $UseTimer

* public static addMethod (string $methodClass): bool  
* public static getAuthMethods (bool $basic = false, bool $withNonDeterministicMethods = true): array  
* public static getDefaultMethod (): ?string  
* public static getMethodClass (string $method): ?string  
* public static getVersion (bool $major = false): string  
* public static isAuthMethod (string $method): bool  
* public static setDefaultMethod (string $method): bool  
* public static validateAlternativeWinnerLoserMethod (?string $substitution): string  
* protected static testMethod (string $method): bool  
```

#### `CondorcetPHP\Condorcet\Console\Commands\ConvertCommand extends Symfony\Component\Console\Command\Command `  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Console/Commands/ConvertCommand.php#L33)

```php
* public const SUCCESS: (integer)
* public const FAILURE: (integer)
* public const INVALID: (integer)

* public static array $converters
* readonly protected string $fromConverter
* readonly protected string $toConverter
* readonly protected CondorcetPHP\Condorcet\Election $election
* protected string $input
* protected ?SplFileObject $output

* public static getDefaultDescription (): ?string  
* public static getDefaultName (): ?string  
* public __construct (?string $name = null)  
* public addArgument (string $name, ?int $mode = null, string $description = , mixed $default = null, Closure|array $suggestedValues = []): static  
* public addOption (string $name, array|string|null $shortcut = null, ?int $mode = null, string $description = , mixed $default = null, Closure|array $suggestedValues = []): static  
* public addUsage (string $usage): static  
* public complete (Symfony\Component\Console\Completion\CompletionInput $input, Symfony\Component\Console\Completion\CompletionSuggestions $suggestions): void  
* public execute (Symfony\Component\Console\Input\InputInterface $input, Symfony\Component\Console\Output\OutputInterface $output): int  
* public getAliases (): array  
* public getApplication (): ?Symfony\Component\Console\Application  
* public getDefinition (): Symfony\Component\Console\Input\InputDefinition  
* public getDescription (): string  
* public getHelp (): string  
* public getHelper (string $name): Symfony\Component\Console\Helper\HelperInterface  
* public getHelperSet (): ?Symfony\Component\Console\Helper\HelperSet  
* public getName (): ?string  
* public getNativeDefinition (): Symfony\Component\Console\Input\InputDefinition  
* public getProcessedHelp (): string  
* public getSynopsis (bool $short = false): string  
* public getUsages (): array  
* public ignoreValidationErrors (): void  
* public initialize (Symfony\Component\Console\Input\InputInterface $input, Symfony\Component\Console\Output\OutputInterface $output): void  
* public isEnabled (): bool  
* public isHidden (): bool  
* public mergeApplicationDefinition (bool $mergeArgs = true): void  
* public run (Symfony\Component\Console\Input\InputInterface $input, Symfony\Component\Console\Output\OutputInterface $output): int  
* public setAliases (iterable $aliases): static  
* public setApplication (?Symfony\Component\Console\Application $application): void  
* public setCode (callable $code): static  
* public setDefinition (Symfony\Component\Console\Input\InputDefinition|array $definition): static  
* public setDescription (string $description): static  
* public setHelp (string $help): static  
* public setHelperSet (Symfony\Component\Console\Helper\HelperSet $helperSet): void  
* public setHidden (bool $hidden = true): static  
* public setName (string $name): static  
* public setProcessTitle (string $title): static  
* protected configure (): void  
* protected interact (Symfony\Component\Console\Input\InputInterface $input, Symfony\Component\Console\Output\OutputInterface $output)  
```

#### `CondorcetPHP\Condorcet\Console\Commands\ElectionCommand extends Symfony\Component\Console\Command\Command `  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Console/Commands/ElectionCommand.php#L43)

```php
* public const SUCCESS: (integer)
* public const FAILURE: (integer)
* public const INVALID: (integer)

* protected ?CondorcetPHP\Condorcet\Election $election
* protected ?string $candidates
* protected ?string $votes
* protected bool $displayMethodsStats
* protected ?string $CondorcetElectionFormatPath
* protected ?string $DebianFormatPath
* protected ?string $DavidHillFormatPath
* public static int $VotesPerMB
* protected string $iniMemoryLimit
* protected int $maxVotesInMemory
* protected bool $candidatesListIsWrite
* protected bool $votesCountIsWrite
* protected bool $pairwiseIsWrite
* public ?string $SQLitePath
* protected Symfony\Component\Console\Terminal $terminal
* protected CondorcetPHP\Condorcet\Console\Style\CondorcetStyle $io
* public static ?string $forceIniMemoryLimitTo
* protected CondorcetPHP\Condorcet\Timer\Manager $timer

* public static getDefaultDescription (): ?string  
* public static getDefaultName (): ?string  
* public __construct (?string $name = null)  
* public addArgument (string $name, ?int $mode = null, string $description = , mixed $default = null, Closure|array $suggestedValues = []): static  
* public addOption (string $name, array|string|null $shortcut = null, ?int $mode = null, string $description = , mixed $default = null, Closure|array $suggestedValues = []): static  
* public addUsage (string $usage): static  
* public complete (Symfony\Component\Console\Completion\CompletionInput $input, Symfony\Component\Console\Completion\CompletionSuggestions $suggestions): void  
* public getAliases (): array  
* public getApplication (): ?Symfony\Component\Console\Application  
* public getDefinition (): Symfony\Component\Console\Input\InputDefinition  
* public getDescription (): string  
* public getHelp (): string  
* public getHelper (string $name): Symfony\Component\Console\Helper\HelperInterface  
* public getHelperSet (): ?Symfony\Component\Console\Helper\HelperSet  
* public getName (): ?string  
* public getNativeDefinition (): Symfony\Component\Console\Input\InputDefinition  
* public getProcessedHelp (): string  
* public getSynopsis (bool $short = false): string  
* public getUsages (): array  
* public ignoreValidationErrors (): void  
* public isEnabled (): bool  
* public isHidden (): bool  
* public mergeApplicationDefinition (bool $mergeArgs = true): void  
* public run (Symfony\Component\Console\Input\InputInterface $input, Symfony\Component\Console\Output\OutputInterface $output): int  
* public setAliases (iterable $aliases): static  
* public setApplication (?Symfony\Component\Console\Application $application): void  
* public setCode (callable $code): static  
* public setDefinition (Symfony\Component\Console\Input\InputDefinition|array $definition): static  
* public setDescription (string $description): static  
* public setHelp (string $help): static  
* public setHelperSet (Symfony\Component\Console\Helper\HelperSet $helperSet): void  
* public setHidden (bool $hidden = true): static  
* public setName (string $name): static  
* public setProcessTitle (string $title): static  
* protected configure (): void  
* protected displayCandidatesList (Symfony\Component\Console\Output\OutputInterface $output): void  
* protected displayConfigurationSection (): void  
* protected displayDebugSection (): void  
* protected displayDetailedElectionInputsSection (Symfony\Component\Console\Input\InputInterface $input, Symfony\Component\Console\Output\OutputInterface $output): void  
* protected displayInputsSection (): void  
* protected displayMethodsResultSection (Symfony\Component\Console\Input\InputInterface $input, Symfony\Component\Console\Output\OutputInterface $output): void  
* protected displayNaturalCondorcet (Symfony\Component\Console\Input\InputInterface $input, Symfony\Component\Console\Output\OutputInterface $output): void  
* protected displayPairwiseSection (Symfony\Component\Console\Output\OutputInterface $output): void  
* protected displayTimerSection (): void  
* protected displayVerbose (Symfony\Component\Console\Output\OutputInterface $output): void  
* protected displayVotesCount (Symfony\Component\Console\Output\OutputInterface $output): void  
* protected displayVotesList (Symfony\Component\Console\Output\OutputInterface $output): void  
* protected execute (Symfony\Component\Console\Input\InputInterface $input, Symfony\Component\Console\Output\OutputInterface $output): int  
* protected importInputsData (Symfony\Component\Console\Input\InputInterface $input): void  
* protected initialize (Symfony\Component\Console\Input\InputInterface $input, Symfony\Component\Console\Output\OutputInterface $output): void  
* protected interact (Symfony\Component\Console\Input\InputInterface $input, Symfony\Component\Console\Output\OutputInterface $output): void  
* protected parseFromCandidatesArguments (): void  
* protected parseFromCondorcetElectionFormat (?Closure $callBack): void  
* protected parseFromDavidHillFormat (): void  
* protected parseFromDebianFormat (): void  
* protected parseFromVotesArguments (?Closure $callBack): void  
* protected setUpParameters (Symfony\Component\Console\Input\InputInterface $input): void  
* protected useDataHandler (Symfony\Component\Console\Input\InputInterface $input): ?Closure  
```

#### `Abstract CondorcetPHP\Condorcet\Console\CondorcetApplication `  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Console/CondorcetApplication.php#L21)

```php
* public static Symfony\Component\Console\Application $SymfonyConsoleApplication

* public static create (): true  
* public static getVersionWithGitParsing (): string  
* public static run (): void  
```

#### `Abstract CondorcetPHP\Condorcet\Console\Helper\CommandInputHelper `  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Console/Helper/CommandInputHelper.php#L15)

```php
* public static getFilePath (string $path): ?string  
* public static isAbsoluteAndExist (string $path): bool  
* public static pathIsAbsolute (string $path): bool  
```

#### `Abstract CondorcetPHP\Condorcet\Console\Helper\FormaterHelper `  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Console/Helper/FormaterHelper.php#L18)

```php
* public static formatResultTable (CondorcetPHP\Condorcet\Result $result): array  
* public static prepareMethods (array $methodArgument): array  
```

#### `CondorcetPHP\Condorcet\Console\Style\CondorcetStyle extends Symfony\Component\Console\Style\SymfonyStyle implements Symfony\Component\Console\Output\OutputInterface, Symfony\Component\Console\Style\StyleInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Console/Style/CondorcetStyle.php#L22)

```php
* public const CONDORCET_MAIN_COLOR: (string)
* public const CONDORCET_SECONDARY_COLOR: (string)
* public const CONDORCET_THIRD_COLOR: (string)
* public const CONDORCET_WINNER_SYMBOL: (string)
* public const CONDORCET_LOSER_SYMBOL: (string)
* public const CONDORCET_WINNER_SYMBOL_FORMATED: (string)
* public const CONDORCET_LOSER_SYMBOL_FORMATED: (string)
* public const MAX_LINE_LENGTH: (integer)
* public const VERBOSITY_SILENT: (integer)
* public const VERBOSITY_QUIET: (integer)
* public const VERBOSITY_NORMAL: (integer)
* public const VERBOSITY_VERBOSE: (integer)
* public const VERBOSITY_VERY_VERBOSE: (integer)
* public const VERBOSITY_DEBUG: (integer)
* public const OUTPUT_NORMAL: (integer)
* public const OUTPUT_RAW: (integer)
* public const OUTPUT_PLAIN: (integer)

* readonly public Symfony\Component\Console\Helper\TableStyle $MainTableStyle
* readonly public Symfony\Component\Console\Helper\TableStyle $FirstColumnStyle

* public __construct (Symfony\Component\Console\Input\InputInterface $input, Symfony\Component\Console\Output\OutputInterface $output)  
* public ask (string $question, ?string $default = null, ?callable $validator = null): mixed  
* public askHidden (string $question, ?callable $validator = null): mixed  
* public askQuestion (Symfony\Component\Console\Question\Question $question): mixed  
* public author (string $author): void  
* public block (array|string $messages, ?string $type = null, ?string $style = null, string $prefix =  , bool $padding = false, bool $escape = true): void  
* public caution (array|string $message): void  
* public choice (string $question, array $choices, mixed $default = null, bool $multiSelect = false): mixed  
* public comment (array|string $message): void  
* public confirm (string $question, bool $default = true): bool  
* public createProgressBar (int $max = 0): Symfony\Component\Console\Helper\ProgressBar  
* public createTable (): Symfony\Component\Console\Helper\Table  
* public definitionList (Symfony\Component\Console\Helper\TableSeparator|array|string $list): void  
* public error (array|string $message): void  
* public getErrorStyle (): self  
* public getFormatter (): Symfony\Component\Console\Formatter\OutputFormatterInterface  
* public getVerbosity (): int  
* public homepage (string $homepage): void  
* public horizontalTable (array $headers, array $rows): void  
* public info (array|string $message): void  
* public inlineSeparator (): void  
* public instruction (string $prefix, string $message): void  
* public isDebug (): bool  
* public isDecorated (): bool  
* public isQuiet (): bool  
* public isSilent (): bool  
* public isVerbose (): bool  
* public isVeryVerbose (): bool  
* public listing (array $elements): void  
* public logo (int $terminalSize): void  
* public methodResultSection (string $message): void  
* public newLine (int $count = 1): void  
* public note (array|string $message): void  
* public progressAdvance (int $step = 1): void  
* public progressFinish (): void  
* public progressIterate (iterable $iterable, ?int $max = null): iterable  
* public progressStart (int $max = 0): void  
* public section (string $message): void  
* public setDecorated (bool $decorated): void  
* public setFormatter (Symfony\Component\Console\Formatter\OutputFormatterInterface $formatter): void  
* public setVerbosity (int $level): void  
* public success (array|string $message): void  
* public table (array $headers, array $rows): void  
* public text (array|string $message): void  
* public title (string $message): void  
* public version (): void  
* public warning (array|string $message): void  
* public write (Traversable|array|string $messages, bool $newline = false, int $type = 1): void  
* public writeln (Traversable|array|string $messages, int $type = 1): void  
* protected getErrorOutput (): Symfony\Component\Console\Output\OutputInterface  
```

#### `CondorcetPHP\Condorcet\Constraints\NoTie implements CondorcetPHP\Condorcet\VoteConstraintInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Constraints/NoTie.php#L17)

```php
* public static isVoteAllowed (CondorcetPHP\Condorcet\Election $election, CondorcetPHP\Condorcet\Vote $vote): bool  
```

#### `Abstract CondorcetPHP\Condorcet\DataManager\ArrayManager implements ArrayAccess, Countable, Iterator, Traversable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/DataManager/ArrayManager.php#L30)

```php
* public static int $CacheSize
* public static int $MaxContainerLength
* protected array $Container
* protected ?CondorcetPHP\Condorcet\DataManager\DataHandlerDrivers\DataHandlerDriverInterface $DataHandler
* protected array $Cache
* protected int $CacheMaxKey
* protected int $CacheMinKey
* protected ?int $cursor
* protected int $counter
* protected int $maxKey
* protected bool $valid
* protected WeakReference $selfElection
* protected string $objectVersion

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
* public getCacheSize (): int  
* public getContainerSize (): int  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getFirstKey (): int  
* public getFullDataSet (): array  
* public getObjectVersion (bool $major = false): string  
* public hasExternalHandler (): bool  
* public importHandler (CondorcetPHP\Condorcet\DataManager\DataHandlerDrivers\DataHandlerDriverInterface $handler): true  
* public key (): ?int  
* public keyExist (int $offset): bool  
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
* protected containerKeyExist (int $offset): bool  
* protected dataHandlerKeyExist (int $offset): bool  
* protected decodeManyEntities (array $entities): array  
* protected decodeOneEntity (string $data): CondorcetPHP\Condorcet\Vote  
* protected encodeManyEntities (array $entities): array  
* protected encodeOneEntity (CondorcetPHP\Condorcet\Vote $data): string  
* protected populateCache (): void  
* protected preDeletedTask (CondorcetPHP\Condorcet\Vote $object): void  
* protected setCursorOnNextKeyInArray (array $array): void  
```

#### `CondorcetPHP\Condorcet\DataManager\DataHandlerDrivers\PdoDriver\PdoHandlerDriver implements CondorcetPHP\Condorcet\DataManager\DataHandlerDrivers\DataHandlerDriverInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/DataManager/DataHandlerDrivers/PdoDriver/PdoHandlerDriver.php#L22)

```php
* public const SEGMENT: (array)

* readonly protected PDO $handler
* protected bool $transaction
* protected bool $queryError
* public static bool $preferBlobInsteadVarchar
* protected array $struct
* protected array $prepare
* protected string $objectVersion

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

#### `CondorcetPHP\Condorcet\DataManager\DataHandlerDrivers\WoollyDriver\WoollyDriver implements CondorcetPHP\Condorcet\DataManager\DataHandlerDrivers\DataHandlerDriverInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/DataManager/DataHandlerDrivers/WoollyDriver/WoollyDriver.php#L22)

```php
* public string $voteColumnName
* readonly public MammothPHP\WoollyM\DataFrame $df

* public __construct (MammothPHP\WoollyM\DataFrame $df = new MammothPHP\WoollyM\DataFrame)  
* public countEntities (): int  
* public deleteOneEntity (int $key, bool $justTry): ?int  
* public insertEntities (array $input): void  
* public selectMaxKey (): ?int  
* public selectMinKey (): ?int  
* public selectOneEntity (int $key): string|bool  
* public selectRangeEntities (int $key, int $limit): array  
```

#### `CondorcetPHP\Condorcet\DataManager\VotesManager extends CondorcetPHP\Condorcet\DataManager\ArrayManager implements Traversable, Iterator, Countable, ArrayAccess`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/DataManager/VotesManager.php#L22)

```php
* public static int $CacheSize
* public static int $MaxContainerLength
* protected array $Container
* protected ?CondorcetPHP\Condorcet\DataManager\DataHandlerDrivers\DataHandlerDriverInterface $DataHandler
* protected array $Cache
* protected int $CacheMaxKey
* protected int $CacheMinKey
* protected ?int $cursor
* protected int $counter
* protected int $maxKey
* protected bool $valid
* protected WeakReference $selfElection
* protected string $objectVersion

* public UpdateAndResetComputing (int $key, CondorcetPHP\Condorcet\DataManager\VotesManagerEvent $type): void  
* public __construct (CondorcetPHP\Condorcet\Election $election)  
* public __destruct ()  
* public __serialize (): array  
* public __unserialize (array $data): void  
* public checkRegularize (): bool  
* public clearCache (): void  
* public closeHandler (): void  
* public count (): int  
* public countInvalidVoteWithConstraints (): int  
* public countValidVotesWithConstraints (?array $tags, int|bool $with): int  
* public countVotes (?array $tags, int|bool $with): int  
* public current (): mixed  
* public debugGetCache (): array  
* public getCacheSize (): int  
* public getContainerSize (): int  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getFirstKey (): int  
* public getFullDataSet (): array  
* public getObjectVersion (bool $major = false): string  
* public getVoteKey (CondorcetPHP\Condorcet\Vote $vote): ?int  
* public getVotesList (?array $tags = null, int|bool $with = true): array  
* public getVotesListAsString (bool $withContext): string  
* public getVotesListGenerator (?array $tags = null, int|bool $with = true): Generator  
* public getVotesValidUnderConstraintGenerator (?array $tags = null, int|bool $with = true): Generator  
* public hasExternalHandler (): bool  
* public importHandler (CondorcetPHP\Condorcet\DataManager\DataHandlerDrivers\DataHandlerDriverInterface $handler): true  
* public key (): ?int  
* public keyExist (int $offset): bool  
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
* public sumVoteWeights (?array $tags, int|bool $with): int  
* public sumVoteWeightsWithConstraints (?array $tags, int|bool $with): int  
* public valid (): bool  
* protected containerKeyExist (int $offset): bool  
* protected dataHandlerKeyExist (int $offset): bool  
* protected decodeManyEntities (array $entities): array  
* protected decodeOneEntity (string $data): CondorcetPHP\Condorcet\Vote  
* protected encodeManyEntities (array $entities): array  
* protected encodeOneEntity (CondorcetPHP\Condorcet\Vote $data): string  
* protected getFullVotesListGenerator (): Generator  
* protected getPartialVotesListGenerator (array $tags, int|bool $with): Generator  
* protected populateCache (): void  
* protected preDeletedTask (CondorcetPHP\Condorcet\Vote $object): void  
* protected processsumVoteWeights (?array $tags, int|bool $with, bool $constraints): int  
* protected setCursorOnNextKeyInArray (array $array): void  
```

#### `CondorcetPHP\Condorcet\DataManager\VotesManagerEvent implements UnitEnum`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/DataManager/VotesManagerEvent.php#L15)

```php
* `case VotesManagerEvent::NewVote`  
* `case VotesManagerEvent::RemoveVote`  
* `case VotesManagerEvent::VoteUpdateInProgress`  
* `case VotesManagerEvent::FinishUpdateVote`  

* readonly public string $name

```

#### `CondorcetPHP\Condorcet\Election `  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Election.php#L25)

```php
* public const MAX_CANDIDATE_NAME_LENGTH: (integer)

* public static ?int $maxParseIteration
* public static ?int $maxVotePerElection
* protected static bool $checksumMode
* public CondorcetPHP\Condorcet\ElectionProcess\ElectionState $state
* readonly protected CondorcetPHP\Condorcet\Timer\Manager $timer
* public bool $implicitRankingRule
* public bool $voteWeightAllowed
* public int $electionSeats
* public array $votesConstraints
* public string $hash
* protected string $objectVersion
* public array $candidates
* public string $nextAutomaticCandidateName
* readonly protected CondorcetPHP\Condorcet\DataManager\VotesManager $Votes
* protected CondorcetPHP\Condorcet\ElectionProcess\VotesFastMode $votesFastMode
* protected ?CondorcetPHP\Condorcet\Algo\Pairwise\Pairwise $Pairwise
* protected ?array $MethodsComputation
* public CondorcetPHP\Condorcet\Algo\StatsVerbosity $statsVerbosity

* protected static formatResultOptions (array $arg): array  
* public __clone (): void  
* public __construct ()  
* public __serialize (): array  
* public __unserialize (array $data): void  
* public addCandidate (CondorcetPHP\Condorcet\Candidate|string|null $candidate = null): CondorcetPHP\Condorcet\Candidate  
* public addCandidatesFromJson (string $input): array  
* public addConstraint (string $constraintClass): true  
* public addVote (CondorcetPHP\Condorcet\Vote|array|string $vote, array|string|null $tags = null): CondorcetPHP\Condorcet\Vote  
* public addVotesFromJson (string $input): int  
* public allowsVoteWeight (bool $rule = true): static  
* public beginVoteUpdate (CondorcetPHP\Condorcet\Vote $existVote): void  
* public canAddCandidate (CondorcetPHP\Condorcet\Candidate|string $candidate): bool  
* public checkVoteCandidate (CondorcetPHP\Condorcet\Vote $vote): bool  
* public clearConstraints (): bool  
* public computeResult (?string $method = null): void  
* public convertRankingCandidates (array $ranking): bool  
* public countCandidates (): int  
* public countInvalidVoteWithConstraints (): int  
* public countValidVoteWithConstraints (array|string|null $tags = null, int|bool $with = true): int  
* public countVotes (array|string|null $tags = null, int|bool $with = true): int  
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
* public getExplicitFilteredPairwiseByTags (array|string $tags, int|bool $with = 1): array  
* public getExplicitPairwise (): array  
* public getFilteredPairwiseByTags (array|string $tags, int|bool $with = true): CondorcetPHP\Condorcet\Algo\Pairwise\FilteredPairwise  
* public getGlobalTimer (): float  
* public getImplicitRankingRule (): bool  
* public getLastTimer (): float  
* public getLoser (?string $method = null): CondorcetPHP\Condorcet\Candidate|array|null  
* public getNumberOfSeats (): int  
* public getObjectVersion (bool $major = false): string  
* public getPairwise (): CondorcetPHP\Condorcet\Algo\Pairwise\Pairwise  
* public getResult (?string $method = null, array $methodOptions = []): CondorcetPHP\Condorcet\Result  
* public getTimerManager (): CondorcetPHP\Condorcet\Timer\Manager  
* public getVoteKey (CondorcetPHP\Condorcet\Vote $vote): ?int  
* public getVotesList (array|string|null $tags = null, bool $with = true): array  
* public getVotesListAsString (bool $withContext = true): string  
* public getVotesListGenerator (array|string|null $tags = null, bool $with = true): Generator  
* public getVotesManager (): CondorcetPHP\Condorcet\DataManager\VotesManager  
* public getVotesValidUnderConstraintGenerator (array|string|null $tags = null, bool $with = true): Generator  
* public getWinner (?string $method = null): CondorcetPHP\Condorcet\Candidate|array|null  
* public hasCandidate (CondorcetPHP\Condorcet\Candidate|string $candidate, bool $strictMode = true): bool  
* public isVoteValidUnderConstraints (CondorcetPHP\Condorcet\Vote $vote): bool  
* public isVoteWeightAllowed (): bool  
* public parseCandidates (string $input, bool $isFile = false): array  
* public parseVotes (string $input, bool $isFile = false): int  
* public parseVotesSafe (SplFileInfo|string $input, bool $isFile = false, ?Closure $callBack = null): int  
* public removeAllVotes (): true  
* public removeCandidates (CondorcetPHP\Condorcet\Candidate|array|string $candidates_input): array  
* public removeExternalDataHandler (): bool  
* public removeVote (CondorcetPHP\Condorcet\Vote $vote): true  
* public removeVotesByTags (array|string $tags, bool $with = true): array  
* public resetMethodsComputation (): void  
* public setExternalDataHandler (CondorcetPHP\Condorcet\DataManager\DataHandlerDrivers\DataHandlerDriverInterface $driver): static  
* public setImplicitRanking (bool $rule = true): static  
* public setMethodOption (string $method, string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): static  
* public setNumberOfSeats (int $seats): static  
* public setStateToVote (): true  
* public setStatsVerbosity (CondorcetPHP\Condorcet\Algo\StatsVerbosity $StatsVerbosity): static  
* public sumValidVoteWeightsWithConstraints (array|string|null $tags = null, int|bool $with = true): int  
* public sumVoteWeights (array|string|null $tags = null, int|bool $with = true): int  
* protected aggregateVotesFromParse (int $count, int $multiple, array $adding, CondorcetPHP\Condorcet\Vote|array|string $vote, array|string|null $tags, int $weight): void  
* protected bulkAddVotes (array $adding): void  
* protected computePairwise (): void  
* protected initResult (string $class): void  
* protected normalizeVoteInput (CondorcetPHP\Condorcet\Vote|array|string $vote, array|string|null $tags = null): CondorcetPHP\Condorcet\Vote  
* protected preparePairwiseAndCleanCompute (): bool  
* protected registerAllLinks (): void  
* protected registerVote (CondorcetPHP\Condorcet\Vote $vote, array|string|null $tags): CondorcetPHP\Condorcet\Vote  
* protected resetComputation (): void  
```

#### `CondorcetPHP\Condorcet\ElectionProcess\ElectionState implements UnitEnum, BackedEnum`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/ElectionProcess/ElectionState.php#L18)

```php
* `case ElectionState::CANDIDATES_REGISTRATION`  
* `case ElectionState::VOTES_REGISTRATION`  

* readonly public string $name
* readonly public int $value

```

#### `CondorcetPHP\Condorcet\ElectionProcess\VotesFastMode implements UnitEnum`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/ElectionProcess/VotesFastMode.php#L18)

```php
* `case VotesFastMode::NONE`  
* `case VotesFastMode::BYPASS_CANDIDATES_CHECK`  
* `case VotesFastMode::BYPASS_RANKING_UPDATE`  

* readonly public string $name

```

#### `CondorcetPHP\Condorcet\Result implements ArrayAccess, Countable, Iterator, Traversable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Result.php#L26)

```php
* readonly public array $rawRanking
* protected array $ResultIterator
* protected array $warning
* readonly public CondorcetPHP\Condorcet\Algo\StatsVerbosity $statsVerbosity
* readonly public string $fromMethod
* readonly public string $byClass
* readonly public CondorcetPHP\Condorcet\Algo\Stats\StatsInterface $stats
* readonly public ?int $seats
* public array $methodOptions
* readonly public array $ranking
* public array $rankingAsArray
* public array $rankingAsArrayString
* public string $rankingAsString
* readonly public ?CondorcetPHP\Condorcet\Candidate $CondorcetWinner
* readonly public ?CondorcetPHP\Condorcet\Candidate $CondorcetLoser
* readonly public array $originalRankingAsArrayString
* public string $originalRankingAsString
* readonly public array $pairwise
* public CondorcetPHP\Condorcet\Candidate|array|null $winner
* public CondorcetPHP\Condorcet\Candidate|array|null $loser
* readonly public float $buildTimestamp
* readonly public string $electionCondorcetVersion
* public bool $isProportional
* protected string $objectVersion

* public __construct (string $fromMethod, string $byClass, CondorcetPHP\Condorcet\Election $election, array $rawRanking, CondorcetPHP\Condorcet\Algo\Stats\StatsInterface $stats, ?int $seats = null, array $methodOptions = [])  
* public addWarning (int $type, ?string $msg = null): true  
* public count (): int  
* public current (): CondorcetPHP\Condorcet\Candidate|array  
* public getObjectVersion (bool $major = false): string  
* public getWarning (?int $type = null): array  
* public key (): int  
* public next (): void  
* public offsetExists (mixed $offset): bool  
* public offsetGet (mixed $offset): CondorcetPHP\Condorcet\Candidate|array|null  
* public offsetSet (mixed $offset, mixed $value): void  
* public offsetUnset (mixed $offset): void  
* public rewind (): void  
* public valid (): bool  
* protected makeUserRanking (CondorcetPHP\Condorcet\Election $election): array  
```

#### `CondorcetPHP\Condorcet\Throwable\AlgorithmWithoutRankingFeatureException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/AlgorithmWithoutRankingFeatureException.php#L15)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* protected string $objectVersion

* public __construct (string|int|null $message = null)  
* public getObjectVersion (bool $major = false): string  
```

#### `CondorcetPHP\Condorcet\Throwable\CandidateDoesNotExistException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/CandidateDoesNotExistException.php#L15)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* protected string $objectVersion

* public __construct (string|int|null $message = null)  
* public getObjectVersion (bool $major = false): string  
```

#### `CondorcetPHP\Condorcet\Throwable\CandidateExistsException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/CandidateExistsException.php#L15)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* protected string $objectVersion

* public __construct (string|int|null $message = null)  
* public getObjectVersion (bool $major = false): string  
```

#### `CondorcetPHP\Condorcet\Throwable\CandidateInvalidNameException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/CandidateInvalidNameException.php#L15)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* protected string $objectVersion

* public __construct (string|int|null $message = null)  
* public getObjectVersion (bool $major = false): string  
```

#### `CondorcetPHP\Condorcet\Throwable\CandidatesMaxNumberReachedException extends CondorcetPHP\Condorcet\Throwable\MethodLimitReachedException implements Stringable, Throwable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/CandidatesMaxNumberReachedException.php#L15)

```php
* protected  $message
* readonly public string $method
* protected  $code
* protected string $file
* protected int $line
* protected string $objectVersion

* public __construct (string $method, int $maxCandidates)  
* public getObjectVersion (bool $major = false): string  
```

#### `Abstract CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException extends Exception implements Stringable, Throwable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/CondorcetPublicApiException.php#L18)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* protected string $objectVersion

* public __construct (string|int|null $message = null)  
* public getObjectVersion (bool $major = false): string  
```

#### `CondorcetPHP\Condorcet\Throwable\ConsoleInputException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/ConsoleInputException.php#L15)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* protected string $objectVersion

* public __construct (string|int|null $message = null)  
* public getObjectVersion (bool $major = false): string  
```

#### `CondorcetPHP\Condorcet\Throwable\DataHandlerException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/DataHandlerException.php#L15)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* protected string $objectVersion

* public __construct (string|int|null $message = null)  
* public getObjectVersion (bool $major = false): string  
```

#### `CondorcetPHP\Condorcet\Throwable\ElectionFileFormatParseException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/ElectionFileFormatParseException.php#L15)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* protected string $objectVersion

* public __construct (string|int|null $message = null)  
* public getObjectVersion (bool $major = false): string  
```

#### `CondorcetPHP\Condorcet\Throwable\ElectionObjectVersionMismatchException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/ElectionObjectVersionMismatchException.php#L17)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* protected string $objectVersion

* public __construct (string $message = )  
* public getObjectVersion (bool $major = false): string  
```

#### `CondorcetPHP\Condorcet\Throwable\FileDoesNotExistException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/FileDoesNotExistException.php#L15)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* protected string $objectVersion

* public __construct (string|int|null $message = null)  
* public getObjectVersion (bool $major = false): string  
```

#### `CondorcetPHP\Condorcet\Throwable\Internal\AlreadyLinkedException extends CondorcetPHP\Condorcet\Throwable\Internal\CondorcetInternalException implements Stringable, Throwable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/Internal/AlreadyLinkedException.php#L16)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line

```

#### `CondorcetPHP\Condorcet\Throwable\Internal\CondorcetInternalError extends Error implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/Internal/CondorcetInternalError.php#L18)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* protected string $objectVersion

* public getObjectVersion (bool $major = false): string  
```

#### `CondorcetPHP\Condorcet\Throwable\Internal\CondorcetInternalException extends Exception implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/Internal/CondorcetInternalException.php#L16)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line

```

#### `CondorcetPHP\Condorcet\Throwable\Internal\IntegerOverflowException extends CondorcetPHP\Condorcet\Throwable\Internal\CondorcetInternalException implements Stringable, Throwable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/Internal/IntegerOverflowException.php#L16)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line

```

#### `CondorcetPHP\Condorcet\Throwable\Internal\NoGitShellException extends CondorcetPHP\Condorcet\Throwable\Internal\CondorcetInternalException implements Stringable, Throwable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/Internal/NoGitShellException.php#L15)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line

```

#### `CondorcetPHP\Condorcet\Throwable\MethodLimitReachedException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/MethodLimitReachedException.php#L15)

```php
* protected  $message
* readonly public string $method
* protected  $code
* protected string $file
* protected int $line
* protected string $objectVersion

* public __construct (string $method, ?string $message = null)  
* public getObjectVersion (bool $major = false): string  
```

#### `CondorcetPHP\Condorcet\Throwable\NoCandidatesException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/NoCandidatesException.php#L15)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* protected string $objectVersion

* public __construct (string|int|null $message = null)  
* public getObjectVersion (bool $major = false): string  
```

#### `CondorcetPHP\Condorcet\Throwable\NoSeatsException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/NoSeatsException.php#L15)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* protected string $objectVersion

* public __construct (string|int|null $message = null)  
* public getObjectVersion (bool $major = false): string  
```

#### `CondorcetPHP\Condorcet\Throwable\ParseVotesMaxNumberReachedException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/ParseVotesMaxNumberReachedException.php#L15)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* protected string $objectVersion

* public __construct (string|int|null $message = null)  
* public getObjectVersion (bool $major = false): string  
```

#### `CondorcetPHP\Condorcet\Throwable\ResultException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/ResultException.php#L15)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* protected string $objectVersion

* public __construct (string|int|null $message = null)  
* public getObjectVersion (bool $major = false): string  
```

#### `CondorcetPHP\Condorcet\Throwable\ResultRequestedWithoutVotesException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/ResultRequestedWithoutVotesException.php#L15)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* protected string $objectVersion

* public __construct (string|int|null $message = null)  
* public getObjectVersion (bool $major = false): string  
```

#### `CondorcetPHP\Condorcet\Throwable\StatsEntryDoNotExistException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/StatsEntryDoNotExistException.php#L15)

```php
* readonly public string $entryName
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* protected string $objectVersion

* public __construct (string $entryName)  
* public getObjectVersion (bool $major = false): string  
```

#### `CondorcetPHP\Condorcet\Throwable\StvQuotaNotImplementedException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/StvQuotaNotImplementedException.php#L15)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* protected string $objectVersion

* public __construct (string|int|null $message = null)  
* public getObjectVersion (bool $major = false): string  
```

#### `CondorcetPHP\Condorcet\Throwable\TagsFilterException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/TagsFilterException.php#L15)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* protected string $objectVersion

* public __construct (string|int|null $message = null)  
* public getObjectVersion (bool $major = false): string  
```

#### `CondorcetPHP\Condorcet\Throwable\TimerException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/TimerException.php#L15)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* protected string $objectVersion

* public __construct (string|int|null $message = null)  
* public getObjectVersion (bool $major = false): string  
```

#### `CondorcetPHP\Condorcet\Throwable\VoteConstraintException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/VoteConstraintException.php#L15)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* protected string $objectVersion

* public __construct (string|int|null $message = null)  
* public getObjectVersion (bool $major = false): string  
```

#### `CondorcetPHP\Condorcet\Throwable\VoteException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/VoteException.php#L15)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* protected string $objectVersion

* public __construct (string|int|null $message = null)  
* public getObjectVersion (bool $major = false): string  
```

#### `CondorcetPHP\Condorcet\Throwable\VoteInvalidFormatException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/VoteInvalidFormatException.php#L15)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* protected string $objectVersion

* public __construct (string|int|null $message = null)  
* public getObjectVersion (bool $major = false): string  
```

#### `CondorcetPHP\Condorcet\Throwable\VoteManagerException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/VoteManagerException.php#L15)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* protected string $objectVersion

* public __construct (string|int|null $message = null)  
* public getObjectVersion (bool $major = false): string  
```

#### `CondorcetPHP\Condorcet\Throwable\VoteMaxNumberReachedException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/VoteMaxNumberReachedException.php#L15)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* protected string $objectVersion

* public __construct (string|int|null $message = null)  
* public getObjectVersion (bool $major = false): string  
```

#### `CondorcetPHP\Condorcet\Throwable\VoteNotLinkedException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/VoteNotLinkedException.php#L15)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* protected string $objectVersion

* public __construct (string|int|null $message = null)  
* public getObjectVersion (bool $major = false): string  
```

#### `CondorcetPHP\Condorcet\Throwable\VotingHasStartedException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/VotingHasStartedException.php#L15)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* protected string $objectVersion

* public __construct (string|int|null $message = null)  
* public getObjectVersion (bool $major = false): string  
```

#### `CondorcetPHP\Condorcet\Throwable\VotingMethodIsNotImplemented extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/VotingMethodIsNotImplemented.php#L15)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* protected string $objectVersion

* public __construct (string|int|null $message = null)  
* public getObjectVersion (bool $major = false): string  
```

#### `CondorcetPHP\Condorcet\Timer\Chrono `  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Timer/Chrono.php#L17)

```php
* readonly protected CondorcetPHP\Condorcet\Timer\Manager $manager
* readonly public float $start
* protected ?string $role
* protected string $objectVersion

* public __construct (CondorcetPHP\Condorcet\Timer\Manager $timer, ?string $role = null)  
* public __destruct ()  
* public getObjectVersion (bool $major = false): string  
* public getRole (): ?string  
* public getStart (): float  
* public getTimerManager (): CondorcetPHP\Condorcet\Timer\Manager  
* public setRole (?string $role): void  
* protected managerStartDeclare (): void  
```

#### `CondorcetPHP\Condorcet\Timer\Manager `  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Timer/Manager.php#L19)

```php
* protected float $globalTimer
* protected ?float $lastTimer
* protected ?float $lastChronoTimestamp
* protected ?float $startDeclare
* protected array $history
* protected string $objectVersion

* public addTime (CondorcetPHP\Condorcet\Timer\Chrono $chrono): void  
* public getGlobalTimer (): float  
* public getHistory (): array  
* public getLastTimer (): ?float  
* public getObjectVersion (bool $major = false): string  
* public startDeclare (CondorcetPHP\Condorcet\Timer\Chrono $chrono): static  
```

#### `CondorcetPHP\Condorcet\Tools\Converters\CEF\CondorcetElectionFormat implements CondorcetPHP\Condorcet\Tools\Converters\Interface\ConverterExport, CondorcetPHP\Condorcet\Tools\Converters\Interface\ConverterImport`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Tools/Converters/CEF/CondorcetElectionFormat.php#L21)

```php
* public const SPECIAL_KEYWORD_EMPTY_RANKING: (string)

* protected SplFileObject $file
* readonly public array $parameters
* readonly public array $candidates
* readonly public int $numberOfSeats
* readonly public bool $implicitRanking
* readonly public bool $voteWeight
* readonly public bool $CandidatesParsedFromVotes
* readonly public int $invalidBlocksCount

* public static boolParser (string $parse): bool  
* public static createFromElection (CondorcetPHP\Condorcet\Election $election, bool $aggregateVotes = true, bool $includeNumberOfSeats = true, bool $includeTags = true, bool $inContext = false, ?SplFileObject $file = null): ?string  
* public static createFromString (string $input): self  
* public __construct (SplFileInfo|string $input)  
* public setDataToAnElection (CondorcetPHP\Condorcet\Election $election = new CondorcetPHP\Condorcet\Election, ?Closure $callBack = null): CondorcetPHP\Condorcet\Election  
* protected addCandidates (array $candidates): void  
* protected interpretStandardParameters (): void  
* protected parseCandidatesFromVotes (): void  
* protected readParameters (): void  
```

#### `CondorcetPHP\Condorcet\Tools\Converters\CEF\StandardParameter implements UnitEnum, BackedEnum`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Tools/Converters/CEF/StandardParameter.php#L18)

```php
* `case StandardParameter::CANDIDATES`  
* `case StandardParameter::SEATS`  
* `case StandardParameter::IMPLICIT`  
* `case StandardParameter::WEIGHT`  

* readonly public string $name
* readonly public string $value

* public formatValue (string $parameterValue): mixed  
```

#### `CondorcetPHP\Condorcet\Tools\Converters\CivsFormat implements CondorcetPHP\Condorcet\Tools\Converters\Interface\ConverterExport`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Tools/Converters/CivsFormat.php#L18)

```php
* public static createFromElection (CondorcetPHP\Condorcet\Election $election, ?SplFileObject $file = null): string|true  
```

#### `CondorcetPHP\Condorcet\Tools\Converters\DavidHillFormat implements CondorcetPHP\Condorcet\Tools\Converters\Interface\ConverterImport`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Tools/Converters/DavidHillFormat.php#L19)

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

#### `CondorcetPHP\Condorcet\Tools\Converters\DebianFormat implements CondorcetPHP\Condorcet\Tools\Converters\Interface\ConverterImport`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Tools/Converters/DebianFormat.php#L20)

```php
* protected array $lines
* readonly public array $candidates
* readonly public array $votes

* public __construct (string $filePath)  
* public setDataToAnElection (?CondorcetPHP\Condorcet\Election $election = null): CondorcetPHP\Condorcet\Election  
* protected readCandidatesNames (): void  
* protected readVotes (): void  
```

#### `CondorcetPHP\Condorcet\Tools\Randomizers\ArrayRandomizer `  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Tools/Randomizers/ArrayRandomizer.php#L17)

```php
* protected Random\Randomizer $randomizer
* public array $candidates
* public ?int $maxCandidatesRanked
* public int|false $minCandidatesRanked
* public ?int $maxRanksCount
* public int|float $tiesProbability

* public __construct (array $candidates, Random\Randomizer|string|null $seed = null)  
* public countCandidates (): int  
* public setCandidates (array $candidates): void  
* public shuffle (): array  
* protected makeTies (array $randomizedCandidates): array  
```

#### `CondorcetPHP\Condorcet\Tools\Randomizers\VoteRandomizer extends CondorcetPHP\Condorcet\Tools\Randomizers\ArrayRandomizer `  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Tools/Randomizers/VoteRandomizer.php#L17)

```php
* protected Random\Randomizer $randomizer
* public array $candidates
* public ?int $maxCandidatesRanked
* public int|false $minCandidatesRanked
* public ?int $maxRanksCount
* public int|float $tiesProbability

* public __construct (array $candidates, Random\Randomizer|string|null $seed = null)  
* public countCandidates (): int  
* public getNewVote (): CondorcetPHP\Condorcet\Vote  
* public setCandidates (array $candidates): void  
* public shuffle (): array  
* protected makeTies (array $randomizedCandidates): array  
```

#### `Abstract CondorcetPHP\Condorcet\Utils\CondorcetUtil `  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Utils/CondorcetUtil.php#L18)

```php
* public static format (mixed $input, bool $convertObject = true): mixed  
* public static isValidJsonForCondorcet (string $string): void  
* public static prepareJson (string $input): mixed  
* public static prepareParse (string $input, bool $isFile): array  
```

#### `CondorcetPHP\Condorcet\Utils\VoteEntryParser `  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Utils/VoteEntryParser.php#L20)

```php
* readonly public ?string $comment
* readonly public int $multiple
* readonly public ?array $ranking
* readonly public ?array $tags
* readonly public int $weight
* readonly public string $originalEntry

* public static convertRankingFromString (string $formula): ?array  
* public static convertTagsFromVoteString (string $voteString, bool $cut = false): ?array  
* public static getComment (string $voteString, bool $cut = false): ?string  
* public static parseIntValueFromVoteStringOffset (string $character, string $entry, bool $cut = false): int  
* public __construct (string $originalEntry)  
```

#### `Abstract CondorcetPHP\Condorcet\Utils\VoteUtil `  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Utils/VoteUtil.php#L20)

```php
* public static convertRankingFromCandidateObjectToInternalKeys (CondorcetPHP\Condorcet\Election $election, array $ranking): void  
* public static getRankingAsString (array $ranking): string  
* public static tagsConvert (array|string|null $tags): ?array  
```

#### `CondorcetPHP\Condorcet\Vote implements Iterator, Stringable, ArrayAccess, Traversable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Vote.php#L26)

```php
* private int $position
* private array $ranking
* public float $createdAt
* public float $updatedAt
* public int $countCandidates
* public array $rankingHistory
* private int $weight
* public array $tags
* public string $hash
* private ?CondorcetPHP\Condorcet\Election $electionContext
* public bool $notUpdate
* protected static ?stdClass $cacheKey
* protected WeakMap $cacheMap
* private ?WeakMap $link
* protected string $objectVersion

* public static clearCache (): void  
* public static initCache (): stdClass  
* public __clone (): void  
* public __construct (array|string $ranking, array|string|null $tags = null, ?float $ownTimestamp = null, ?CondorcetPHP\Condorcet\Election $electionContext = null)  
* public __serialize (): array  
* public __toString (): string  
* public __wakeup (): void  
* public addTags (array|string $tags): bool  
* public countLinks (): int  
* public countRanks (): int  
* public current (): array  
* public destroyLink (CondorcetPHP\Condorcet\Election $election): bool  
* public getAllCandidates (?CondorcetPHP\Condorcet\Election $context = null): array  
* public getContextualRanking (CondorcetPHP\Condorcet\Election $election): array  
* public getContextualRankingAsString (CondorcetPHP\Condorcet\Election $election): array  
* public getContextualRankingWithCandidateKeys (CondorcetPHP\Condorcet\Election $election): array  
* public getContextualRankingWithoutSort (CondorcetPHP\Condorcet\Election $election): array  
* public getLinks (): array  
* public getObjectVersion (bool $major = false): string  
* public getRanking (bool $sortCandidatesInRank = true): array  
* public getSimpleRanking (?CondorcetPHP\Condorcet\Election $context = null, bool $displayWeight = true): string  
* public getTagsAsString (): string  
* public getWeight (?CondorcetPHP\Condorcet\Election $context = null): int  
* public haveLink (CondorcetPHP\Condorcet\Election $election): bool  
* public key (): int  
* public next (): void  
* public offsetExists (mixed $offset): bool  
* public offsetGet (mixed $offset): CondorcetPHP\Condorcet\Candidate|array|null  
* public offsetSet (mixed $offset, mixed $value): void  
* public offsetUnset (mixed $offset): void  
* public registerLink (CondorcetPHP\Condorcet\Election $election): void  
* public removeAllTags (): true  
* public removeCandidate (CondorcetPHP\Condorcet\Candidate|string $candidate): true  
* public removeTags (array|string $tags): array  
* public rewind (): void  
* public setRanking (array|string $ranking, ?float $ownTimestamp = null): static  
* public setWeight (int $newWeight): int  
* public valid (): bool  
* protected computeContextualRanking (CondorcetPHP\Condorcet\Election $election, bool $sortLastRankByName): array  
* protected computeContextualRankingWithoutImplicit (array $ranking, CondorcetPHP\Condorcet\Election $election, int $countContextualCandidate = 0): array  
* protected destroyAllLink (): void  
* protected initWeakMap (): void  
* private archiveRanking (): void  
* private computeHashCode (): string  
* private formatRanking (array|string $ranking): array  
```
