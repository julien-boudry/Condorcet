> **[Presentation](../README.md) | [Documentation Book](https://docs.condorcet.io) | API References | [Voting Methods](/Docs/VotingMethods.md) | [Tests](../../tests)**

# API References
## Public API Index *

### CondorcetPHP\Condorcet\Candidate Class  

* `public string` [$name](/Docs/api-reference/Candidate%20Class/Candidate--name.md)
* `public array` [$nameHistory](/Docs/api-reference/Candidate%20Class/Candidate--nameHistory.md)
* `public float` [$createdAt](/Docs/api-reference/Candidate%20Class/Candidate--createdAt.md)
* `public float` [$updatedAt](/Docs/api-reference/Candidate%20Class/Candidate--updatedAt.md)
* `public string` [$buildByCondorcetVersion](/Docs/api-reference/Candidate%20Class/Candidate--buildByCondorcetVersion.md)

* `public` [Candidate->__construct (...)](/Docs/api-reference/Candidate%20Class/Candidate--__construct().md)  
* `final public` [Candidate->buildByCondorcetVersion](/Docs/api-reference/Candidate%20Class/Candidate--buildByCondorcetVersion.md)  
* `public` [Candidate->countLinks ()](/Docs/api-reference/Candidate%20Class/Candidate--countLinks().md): `int`  
* `virtual public` [Candidate->createdAt](/Docs/api-reference/Candidate%20Class/Candidate--createdAt.md)  
* `public` [Candidate->getCondorcetBuilderVersion (...)](/Docs/api-reference/Candidate%20Class/Candidate--getCondorcetBuilderVersion().md): `string`  
* `public` [Candidate->getLinks ()](/Docs/api-reference/Candidate%20Class/Candidate--getLinks().md): `array`  
* `public` [Candidate->haveLink (...)](/Docs/api-reference/Candidate%20Class/Candidate--haveLink().md): `bool`  
* `virtual public` [Candidate->name](/Docs/api-reference/Candidate%20Class/Candidate--name.md)  
* `public` [Candidate->nameHistory](/Docs/api-reference/Candidate%20Class/Candidate--nameHistory.md)  
* `public` [Candidate->setName (...)](/Docs/api-reference/Candidate%20Class/Candidate--setName().md): `static`  
* `virtual public` [Candidate->updatedAt](/Docs/api-reference/Candidate%20Class/Candidate--updatedAt.md)  

### CondorcetPHP\Condorcet\Condorcet Class  

* `final public const VERSION: (string)`  
* `final public const CONDORCET_BASIC_CLASS: (string)`  

* `public static bool` [$UseTimer](/Docs/api-reference/Condorcet%20Class/Condorcet--UseTimer.md)

* `public static` [Condorcet::UseTimer](/Docs/api-reference/Condorcet%20Class/Condorcet--UseTimer.md)  
* `public static` [Condorcet::addMethod (...)](/Docs/api-reference/Condorcet%20Class/Condorcet--addMethod().md): `bool`  
* `public static` [Condorcet::getAuthMethods (...)](/Docs/api-reference/Condorcet%20Class/Condorcet--getAuthMethods().md): `array`  
* `public static` [Condorcet::getDefaultMethod ()](/Docs/api-reference/Condorcet%20Class/Condorcet--getDefaultMethod().md): `?string`  
* `public static` [Condorcet::getMethodClass (...)](/Docs/api-reference/Condorcet%20Class/Condorcet--getMethodClass().md): `?string`  
* `public static` [Condorcet::getVersion (...)](/Docs/api-reference/Condorcet%20Class/Condorcet--getVersion().md): `string`  
* `public static` [Condorcet::isAuthMethod (...)](/Docs/api-reference/Condorcet%20Class/Condorcet--isAuthMethod().md): `bool`  
* `public static` [Condorcet::setDefaultMethod (...)](/Docs/api-reference/Condorcet%20Class/Condorcet--setDefaultMethod().md): `bool`  

### CondorcetPHP\Condorcet\Election Class  

* `public const MAX_CANDIDATE_NAME_LENGTH: (integer)`  

* `public static ?int` [$maxParseIteration](/Docs/api-reference/Election%20Class/Election--maxParseIteration.md)
* `public static ?int` [$maxVotePerElection](/Docs/api-reference/Election%20Class/Election--maxVotePerElection.md)
* `public CondorcetPHP\Condorcet\ElectionProcess\ElectionState` [$state](/Docs/api-reference/Election%20Class/Election--state.md)
* `public bool` [$authorizeVoteWeight](/Docs/api-reference/Election%20Class/Election--authorizeVoteWeight.md)
* `public int` [$seatsToElect](/Docs/api-reference/Election%20Class/Election--seatsToElect.md)
* `public array` [$votesConstraints](/Docs/api-reference/Election%20Class/Election--votesConstraints.md)
* `public string` [$hash](/Docs/api-reference/Election%20Class/Election--hash.md)
* `public bool` [$implicitRankingRule](/Docs/api-reference/Election%20Class/Election--implicitRankingRule.md)
* `public string` [$buildByCondorcetVersion](/Docs/api-reference/Election%20Class/Election--buildByCondorcetVersion.md)
* `public CondorcetPHP\Condorcet\Algo\StatsVerbosity` [$statsVerbosity](/Docs/api-reference/Election%20Class/Election--statsVerbosity.md)

* `public static` [Election::maxParseIteration](/Docs/api-reference/Election%20Class/Election--maxParseIteration.md)  
* `public static` [Election::maxVotePerElection](/Docs/api-reference/Election%20Class/Election--maxVotePerElection.md)  
* `public` [Election->__construct ()](/Docs/api-reference/Election%20Class/Election--__construct().md)  
* `public` [Election->addCandidate (...)](/Docs/api-reference/Election%20Class/Election--addCandidate().md): `CondorcetPHP\Condorcet\Candidate`  
* `public` [Election->addCandidatesFromJson (...)](/Docs/api-reference/Election%20Class/Election--addCandidatesFromJson().md): `array`  
* `public` [Election->addConstraint (...)](/Docs/api-reference/Election%20Class/Election--addConstraint().md): `static`  
* `public` [Election->addVote (...)](/Docs/api-reference/Election%20Class/Election--addVote().md): `CondorcetPHP\Condorcet\Vote`  
* `public` [Election->addVotesFromJson (...)](/Docs/api-reference/Election%20Class/Election--addVotesFromJson().md): `int`  
* `public` [Election->authorizeVoteWeight (...)](/Docs/api-reference/Election%20Class/Election--authorizeVoteWeight().md): `static`  
* `final public` [Election->buildByCondorcetVersion](/Docs/api-reference/Election%20Class/Election--buildByCondorcetVersion.md)  
* `public` [Election->canAddCandidate (...)](/Docs/api-reference/Election%20Class/Election--canAddCandidate().md): `bool`  
* `public` [Election->clearConstraints ()](/Docs/api-reference/Election%20Class/Election--clearConstraints().md): `static`  
* `public` [Election->computeResult (...)](/Docs/api-reference/Election%20Class/Election--computeResult().md): `void`  
* `public` [Election->countCandidates ()](/Docs/api-reference/Election%20Class/Election--countCandidates().md): `int`  
* `public` [Election->countInvalidVoteWithConstraints ()](/Docs/api-reference/Election%20Class/Election--countInvalidVoteWithConstraints().md): `int`  
* `public` [Election->countValidVoteWithConstraints (...)](/Docs/api-reference/Election%20Class/Election--countValidVoteWithConstraints().md): `int`  
* `public` [Election->countVotes (...)](/Docs/api-reference/Election%20Class/Election--countVotes().md): `int`  
* `public` [Election->getCandidateObjectFromName (...)](/Docs/api-reference/Election%20Class/Election--getCandidateObjectFromName().md): `?CondorcetPHP\Condorcet\Candidate`  
* `public` [Election->getCandidatesList ()](/Docs/api-reference/Election%20Class/Election--getCandidatesList().md): `array`  
* `public` [Election->getCandidatesListAsString ()](/Docs/api-reference/Election%20Class/Election--getCandidatesListAsString().md): `array`  
* `public` [Election->getChecksum ()](/Docs/api-reference/Election%20Class/Election--getChecksum().md): `string`  
* `public` [Election->getCondorcetBuilderVersion (...)](/Docs/api-reference/Election%20Class/Election--getCondorcetBuilderVersion().md): `string`  
* `public` [Election->getCondorcetLoser ()](/Docs/api-reference/Election%20Class/Election--getCondorcetLoser().md): `?CondorcetPHP\Condorcet\Candidate`  
* `public` [Election->getCondorcetWinner ()](/Docs/api-reference/Election%20Class/Election--getCondorcetWinner().md): `?CondorcetPHP\Condorcet\Candidate`  
* `public` [Election->getConstraints ()](/Docs/api-reference/Election%20Class/Election--getConstraints().md): `array`  
* `public` [Election->getExplicitFilteredPairwiseByTags (...)](/Docs/api-reference/Election%20Class/Election--getExplicitFilteredPairwiseByTags().md): `array`  
* `public` [Election->getExplicitPairwise ()](/Docs/api-reference/Election%20Class/Election--getExplicitPairwise().md): `array`  
* `public` [Election->getGlobalTimer ()](/Docs/api-reference/Election%20Class/Election--getGlobalTimer().md): `float`  
* `public` [Election->getLastTimer ()](/Docs/api-reference/Election%20Class/Election--getLastTimer().md): `?float`  
* `public` [Election->getLoser (...)](/Docs/api-reference/Election%20Class/Election--getLoser().md): `CondorcetPHP\Condorcet\Candidate|array|null`  
* `public` [Election->getPairwise ()](/Docs/api-reference/Election%20Class/Election--getPairwise().md): `CondorcetPHP\Condorcet\Algo\Pairwise\Pairwise`  
* `public` [Election->getResult (...)](/Docs/api-reference/Election%20Class/Election--getResult().md): `CondorcetPHP\Condorcet\Result`  
* `public` [Election->getTimerManager ()](/Docs/api-reference/Election%20Class/Election--getTimerManager().md): `CondorcetPHP\Condorcet\Timer\Manager`  
* `public` [Election->getVotesList (...)](/Docs/api-reference/Election%20Class/Election--getVotesList().md): `array`  
* `public` [Election->getVotesListAsString (...)](/Docs/api-reference/Election%20Class/Election--getVotesListAsString().md): `string`  
* `public` [Election->getVotesListGenerator (...)](/Docs/api-reference/Election%20Class/Election--getVotesListGenerator().md): `Generator`  
* `public` [Election->getVotesValidUnderConstraintGenerator (...)](/Docs/api-reference/Election%20Class/Election--getVotesValidUnderConstraintGenerator().md): `Generator`  
* `public` [Election->getWinner (...)](/Docs/api-reference/Election%20Class/Election--getWinner().md): `CondorcetPHP\Condorcet\Candidate|array|null`  
* `public` [Election->hasCandidate (...)](/Docs/api-reference/Election%20Class/Election--hasCandidate().md): `bool`  
* `virtual public` [Election->hash](/Docs/api-reference/Election%20Class/Election--hash.md)  
* `public` [Election->implicitRankingRule (...)](/Docs/api-reference/Election%20Class/Election--implicitRankingRule().md): `static`  
* `public` [Election->isVoteValidUnderConstraints (...)](/Docs/api-reference/Election%20Class/Election--isVoteValidUnderConstraints().md): `bool`  
* `public` [Election->parseCandidates (...)](/Docs/api-reference/Election%20Class/Election--parseCandidates().md): `array`  
* `public` [Election->parseVotes (...)](/Docs/api-reference/Election%20Class/Election--parseVotes().md): `int`  
* `public` [Election->parseVotesSafe (...)](/Docs/api-reference/Election%20Class/Election--parseVotesSafe().md): `int`  
* `public` [Election->removeAllVotes ()](/Docs/api-reference/Election%20Class/Election--removeAllVotes().md): `true`  
* `public` [Election->removeCandidates (...)](/Docs/api-reference/Election%20Class/Election--removeCandidates().md): `array`  
* `public` [Election->removeExternalDataHandler ()](/Docs/api-reference/Election%20Class/Election--removeExternalDataHandler().md): `bool`  
* `public` [Election->removeVote (...)](/Docs/api-reference/Election%20Class/Election--removeVote().md): `true`  
* `public` [Election->removeVotesByTags (...)](/Docs/api-reference/Election%20Class/Election--removeVotesByTags().md): `array`  
* `public` [Election->seatsToElect](/Docs/api-reference/Election%20Class/Election--seatsToElect.md)  
* `public` [Election->setExternalDataHandler (...)](/Docs/api-reference/Election%20Class/Election--setExternalDataHandler().md): `static`  
* `public` [Election->setMethodOption (...)](/Docs/api-reference/Election%20Class/Election--setMethodOption().md): `static`  
* `public` [Election->setSeatsToElect (...)](/Docs/api-reference/Election%20Class/Election--setSeatsToElect().md): `static`  
* `public` [Election->setStateToVote ()](/Docs/api-reference/Election%20Class/Election--setStateToVote().md): `true`  
* `public` [Election->setStatsVerbosity (...)](/Docs/api-reference/Election%20Class/Election--setStatsVerbosity().md): `static`  
* `public` [Election->state](/Docs/api-reference/Election%20Class/Election--state.md)  
* `public` [Election->statsVerbosity](/Docs/api-reference/Election%20Class/Election--statsVerbosity.md)  
* `public` [Election->sumValidVoteWeightsWithConstraints (...)](/Docs/api-reference/Election%20Class/Election--sumValidVoteWeightsWithConstraints().md): `int`  
* `public` [Election->sumVoteWeights (...)](/Docs/api-reference/Election%20Class/Election--sumVoteWeights().md): `int`  
* `public` [Election->votesConstraints](/Docs/api-reference/Election%20Class/Election--votesConstraints.md)  

### CondorcetPHP\Condorcet\Result Class  

* `readonly public CondorcetPHP\Condorcet\Algo\StatsVerbosity` [$statsVerbosity](/Docs/api-reference/Result%20Class/Result--statsVerbosity.md)
* `readonly public string` [$fromMethod](/Docs/api-reference/Result%20Class/Result--fromMethod.md)
* `readonly public string` [$byClass](/Docs/api-reference/Result%20Class/Result--byClass.md)
* `readonly public CondorcetPHP\Condorcet\Algo\Stats\StatsInterface` [$stats](/Docs/api-reference/Result%20Class/Result--stats.md)
* `readonly public ?int` [$seats](/Docs/api-reference/Result%20Class/Result--seats.md)
* `public array` [$methodOptions](/Docs/api-reference/Result%20Class/Result--methodOptions.md)
* `readonly public array` [$ranking](/Docs/api-reference/Result%20Class/Result--ranking.md)
* `public array` [$rankingAsArray](/Docs/api-reference/Result%20Class/Result--rankingAsArray.md)
* `public array` [$rankingAsArrayString](/Docs/api-reference/Result%20Class/Result--rankingAsArrayString.md)
* `public string` [$rankingAsString](/Docs/api-reference/Result%20Class/Result--rankingAsString.md)
* `readonly public ?CondorcetPHP\Condorcet\Candidate` [$CondorcetWinner](/Docs/api-reference/Result%20Class/Result--CondorcetWinner.md)
* `readonly public ?CondorcetPHP\Condorcet\Candidate` [$CondorcetLoser](/Docs/api-reference/Result%20Class/Result--CondorcetLoser.md)
* `readonly public array` [$originalRankingAsArrayString](/Docs/api-reference/Result%20Class/Result--originalRankingAsArrayString.md)
* `public string` [$originalRankingAsString](/Docs/api-reference/Result%20Class/Result--originalRankingAsString.md)
* `readonly public array` [$pairwise](/Docs/api-reference/Result%20Class/Result--pairwise.md)
* `public CondorcetPHP\Condorcet\Candidate|array|null` [$Winner](/Docs/api-reference/Result%20Class/Result--Winner.md)
* `public CondorcetPHP\Condorcet\Candidate|array|null` [$Loser](/Docs/api-reference/Result%20Class/Result--Loser.md)
* `readonly public float` [$buildTimestamp](/Docs/api-reference/Result%20Class/Result--buildTimestamp.md)
* `readonly public string` [$electionCondorcetVersion](/Docs/api-reference/Result%20Class/Result--electionCondorcetVersion.md)
* `public bool` [$isProportional](/Docs/api-reference/Result%20Class/Result--isProportional.md)
* `public string` [$buildByCondorcetVersion](/Docs/api-reference/Result%20Class/Result--buildByCondorcetVersion.md)

* `final public readonly` [Result->CondorcetLoser](/Docs/api-reference/Result%20Class/Result--CondorcetLoser.md)  
* `final public readonly` [Result->CondorcetWinner](/Docs/api-reference/Result%20Class/Result--CondorcetWinner.md)  
* `virtual public` [Result->Loser](/Docs/api-reference/Result%20Class/Result--Loser.md)  
* `virtual public` [Result->Winner](/Docs/api-reference/Result%20Class/Result--Winner.md)  
* `final public` [Result->buildByCondorcetVersion](/Docs/api-reference/Result%20Class/Result--buildByCondorcetVersion.md)  
* `final public readonly` [Result->buildTimestamp](/Docs/api-reference/Result%20Class/Result--buildTimestamp.md)  
* `final public readonly` [Result->byClass](/Docs/api-reference/Result%20Class/Result--byClass.md)  
* `final public readonly` [Result->electionCondorcetVersion](/Docs/api-reference/Result%20Class/Result--electionCondorcetVersion.md)  
* `final public readonly` [Result->fromMethod](/Docs/api-reference/Result%20Class/Result--fromMethod.md)  
* `public` [Result->getCondorcetBuilderVersion (...)](/Docs/api-reference/Result%20Class/Result--getCondorcetBuilderVersion().md): `string`  
* `public` [Result->getWarning (...)](/Docs/api-reference/Result%20Class/Result--getWarning().md): `array`  
* `virtual public` [Result->isProportional](/Docs/api-reference/Result%20Class/Result--isProportional.md)  
* `final public` [Result->methodOptions](/Docs/api-reference/Result%20Class/Result--methodOptions.md)  
* `public readonly` [Result->originalRankingAsArrayString](/Docs/api-reference/Result%20Class/Result--originalRankingAsArrayString.md)  
* `virtual public` [Result->originalRankingAsString](/Docs/api-reference/Result%20Class/Result--originalRankingAsString.md)  
* `final public readonly` [Result->pairwise](/Docs/api-reference/Result%20Class/Result--pairwise.md)  
* `final public readonly` [Result->ranking](/Docs/api-reference/Result%20Class/Result--ranking.md)  
* `virtual public` [Result->rankingAsArray](/Docs/api-reference/Result%20Class/Result--rankingAsArray.md)  
* `virtual public` [Result->rankingAsArrayString](/Docs/api-reference/Result%20Class/Result--rankingAsArrayString.md)  
* `virtual public` [Result->rankingAsString](/Docs/api-reference/Result%20Class/Result--rankingAsString.md)  
* `final public readonly` [Result->seats](/Docs/api-reference/Result%20Class/Result--seats.md)  
* `public readonly` [Result->stats](/Docs/api-reference/Result%20Class/Result--stats.md)  
* `final public readonly` [Result->statsVerbosity](/Docs/api-reference/Result%20Class/Result--statsVerbosity.md)  

### CondorcetPHP\Condorcet\Vote Class  

* `public float` [$createdAt](/Docs/api-reference/Vote%20Class/Vote--createdAt.md)
* `public float` [$updatedAt](/Docs/api-reference/Vote%20Class/Vote--updatedAt.md)
* `public int` [$countCandidates](/Docs/api-reference/Vote%20Class/Vote--countCandidates.md)
* `public array` [$rankingHistory](/Docs/api-reference/Vote%20Class/Vote--rankingHistory.md)
* `public array` [$tags](/Docs/api-reference/Vote%20Class/Vote--tags.md)
* `public string` [$hash](/Docs/api-reference/Vote%20Class/Vote--hash.md)
* `public string` [$buildByCondorcetVersion](/Docs/api-reference/Vote%20Class/Vote--buildByCondorcetVersion.md)

* `public` [Vote->__construct (...)](/Docs/api-reference/Vote%20Class/Vote--__construct().md)  
* `public` [Vote->addTags (...)](/Docs/api-reference/Vote%20Class/Vote--addTags().md): `bool`  
* `final public` [Vote->buildByCondorcetVersion](/Docs/api-reference/Vote%20Class/Vote--buildByCondorcetVersion.md)  
* `final public` [Vote->countCandidates](/Docs/api-reference/Vote%20Class/Vote--countCandidates.md)  
* `public` [Vote->countLinks ()](/Docs/api-reference/Vote%20Class/Vote--countLinks().md): `int`  
* `public` [Vote->countRanks ()](/Docs/api-reference/Vote%20Class/Vote--countRanks().md): `int`  
* `virtual public` [Vote->createdAt](/Docs/api-reference/Vote%20Class/Vote--createdAt.md)  
* `public` [Vote->getAllCandidates (...)](/Docs/api-reference/Vote%20Class/Vote--getAllCandidates().md): `array`  
* `public` [Vote->getCondorcetBuilderVersion (...)](/Docs/api-reference/Vote%20Class/Vote--getCondorcetBuilderVersion().md): `string`  
* `public` [Vote->getLinks ()](/Docs/api-reference/Vote%20Class/Vote--getLinks().md): `array`  
* `public` [Vote->getRanking (...)](/Docs/api-reference/Vote%20Class/Vote--getRanking().md): `array`  
* `public` [Vote->getRankingAsArrayString (...)](/Docs/api-reference/Vote%20Class/Vote--getRankingAsArrayString().md): `array`  
* `public` [Vote->getRankingAsString (...)](/Docs/api-reference/Vote%20Class/Vote--getRankingAsString().md): `string`  
* `public` [Vote->getTagsAsString ()](/Docs/api-reference/Vote%20Class/Vote--getTagsAsString().md): `string`  
* `public` [Vote->getWeight (...)](/Docs/api-reference/Vote%20Class/Vote--getWeight().md): `int`  
* `final public` [Vote->hash](/Docs/api-reference/Vote%20Class/Vote--hash.md)  
* `public` [Vote->haveLink (...)](/Docs/api-reference/Vote%20Class/Vote--haveLink().md): `bool`  
* `final public` [Vote->rankingHistory](/Docs/api-reference/Vote%20Class/Vote--rankingHistory.md)  
* `public` [Vote->removeAllTags ()](/Docs/api-reference/Vote%20Class/Vote--removeAllTags().md): `true`  
* `public` [Vote->removeCandidate (...)](/Docs/api-reference/Vote%20Class/Vote--removeCandidate().md): `true`  
* `public` [Vote->removeTags (...)](/Docs/api-reference/Vote%20Class/Vote--removeTags().md): `array`  
* `public` [Vote->setRanking (...)](/Docs/api-reference/Vote%20Class/Vote--setRanking().md): `static`  
* `public` [Vote->setWeight (...)](/Docs/api-reference/Vote%20Class/Vote--setWeight().md): `int`  
* `final public` [Vote->tags](/Docs/api-reference/Vote%20Class/Vote--tags.md)  
* `final public` [Vote->updatedAt](/Docs/api-reference/Vote%20Class/Vote--updatedAt.md)  
* `final public` [Algo\Method->buildByCondorcetVersion](/Docs/api-reference/Algo_Method%20Class/Algo_Method--buildByCondorcetVersion.md)  
* `public` [Algo\Method->getCondorcetBuilderVersion (...)](/Docs/api-reference/Algo_Method%20Class/Algo_Method--getCondorcetBuilderVersion().md): `string`  
* `final public` [Algo\Method->buildByCondorcetVersion](/Docs/api-reference/Algo_Method%20Class/Algo_Method--buildByCondorcetVersion.md)  
* `public` [Algo\Method->getCondorcetBuilderVersion (...)](/Docs/api-reference/Algo_Method%20Class/Algo_Method--getCondorcetBuilderVersion().md): `string`  
* `final public` [Algo\Method->buildByCondorcetVersion](/Docs/api-reference/Algo_Method%20Class/Algo_Method--buildByCondorcetVersion.md)  
* `public` [Algo\Method->getCondorcetBuilderVersion (...)](/Docs/api-reference/Algo_Method%20Class/Algo_Method--getCondorcetBuilderVersion().md): `string`  
* `final public` [Algo\Method->buildByCondorcetVersion](/Docs/api-reference/Algo_Method%20Class/Algo_Method--buildByCondorcetVersion.md)  
* `public` [Algo\Method->getCondorcetBuilderVersion (...)](/Docs/api-reference/Algo_Method%20Class/Algo_Method--getCondorcetBuilderVersion().md): `string`  
* `final public` [Algo\Method->buildByCondorcetVersion](/Docs/api-reference/Algo_Method%20Class/Algo_Method--buildByCondorcetVersion.md)  
* `public` [Algo\Method->getCondorcetBuilderVersion (...)](/Docs/api-reference/Algo_Method%20Class/Algo_Method--getCondorcetBuilderVersion().md): `string`  
* `final public` [Algo\Method->buildByCondorcetVersion](/Docs/api-reference/Algo_Method%20Class/Algo_Method--buildByCondorcetVersion.md)  
* `public` [Algo\Method->getCondorcetBuilderVersion (...)](/Docs/api-reference/Algo_Method%20Class/Algo_Method--getCondorcetBuilderVersion().md): `string`  
* `final public` [Algo\Method->buildByCondorcetVersion](/Docs/api-reference/Algo_Method%20Class/Algo_Method--buildByCondorcetVersion.md)  
* `public` [Algo\Method->getCondorcetBuilderVersion (...)](/Docs/api-reference/Algo_Method%20Class/Algo_Method--getCondorcetBuilderVersion().md): `string`  
* `final public` [Algo\Method->buildByCondorcetVersion](/Docs/api-reference/Algo_Method%20Class/Algo_Method--buildByCondorcetVersion.md)  
* `public` [Algo\Method->getCondorcetBuilderVersion (...)](/Docs/api-reference/Algo_Method%20Class/Algo_Method--getCondorcetBuilderVersion().md): `string`  
* `final public` [Algo\Method->buildByCondorcetVersion](/Docs/api-reference/Algo_Method%20Class/Algo_Method--buildByCondorcetVersion.md)  
* `public` [Algo\Method->getCondorcetBuilderVersion (...)](/Docs/api-reference/Algo_Method%20Class/Algo_Method--getCondorcetBuilderVersion().md): `string`  
* `final public` [Algo\Method->buildByCondorcetVersion](/Docs/api-reference/Algo_Method%20Class/Algo_Method--buildByCondorcetVersion.md)  
* `public` [Algo\Method->getCondorcetBuilderVersion (...)](/Docs/api-reference/Algo_Method%20Class/Algo_Method--getCondorcetBuilderVersion().md): `string`  
* `final public` [Algo\Method->buildByCondorcetVersion](/Docs/api-reference/Algo_Method%20Class/Algo_Method--buildByCondorcetVersion.md)  
* `public` [Algo\Method->getCondorcetBuilderVersion (...)](/Docs/api-reference/Algo_Method%20Class/Algo_Method--getCondorcetBuilderVersion().md): `string`  
* `final public` [Algo\Method->buildByCondorcetVersion](/Docs/api-reference/Algo_Method%20Class/Algo_Method--buildByCondorcetVersion.md)  
* `public` [Algo\Method->getCondorcetBuilderVersion (...)](/Docs/api-reference/Algo_Method%20Class/Algo_Method--getCondorcetBuilderVersion().md): `string`  
* `final public` [Algo\Method->buildByCondorcetVersion](/Docs/api-reference/Algo_Method%20Class/Algo_Method--buildByCondorcetVersion.md)  
* `public` [Algo\Method->getCondorcetBuilderVersion (...)](/Docs/api-reference/Algo_Method%20Class/Algo_Method--getCondorcetBuilderVersion().md): `string`  
* `final public` [Algo\Method->buildByCondorcetVersion](/Docs/api-reference/Algo_Method%20Class/Algo_Method--buildByCondorcetVersion.md)  
* `public` [Algo\Method->getCondorcetBuilderVersion (...)](/Docs/api-reference/Algo_Method%20Class/Algo_Method--getCondorcetBuilderVersion().md): `string`  
* `final public` [Algo\Method->buildByCondorcetVersion](/Docs/api-reference/Algo_Method%20Class/Algo_Method--buildByCondorcetVersion.md)  
* `public` [Algo\Method->getCondorcetBuilderVersion (...)](/Docs/api-reference/Algo_Method%20Class/Algo_Method--getCondorcetBuilderVersion().md): `string`  
* `final public` [Algo\Method->buildByCondorcetVersion](/Docs/api-reference/Algo_Method%20Class/Algo_Method--buildByCondorcetVersion.md)  
* `public` [Algo\Method->getCondorcetBuilderVersion (...)](/Docs/api-reference/Algo_Method%20Class/Algo_Method--getCondorcetBuilderVersion().md): `string`  
* `final public` [Algo\Method->buildByCondorcetVersion](/Docs/api-reference/Algo_Method%20Class/Algo_Method--buildByCondorcetVersion.md)  
* `public` [Algo\Method->getCondorcetBuilderVersion (...)](/Docs/api-reference/Algo_Method%20Class/Algo_Method--getCondorcetBuilderVersion().md): `string`  
* `final public` [Algo\Method->buildByCondorcetVersion](/Docs/api-reference/Algo_Method%20Class/Algo_Method--buildByCondorcetVersion.md)  
* `public` [Algo\Method->getCondorcetBuilderVersion (...)](/Docs/api-reference/Algo_Method%20Class/Algo_Method--getCondorcetBuilderVersion().md): `string`  
* `final public` [Algo\Method->buildByCondorcetVersion](/Docs/api-reference/Algo_Method%20Class/Algo_Method--buildByCondorcetVersion.md)  
* `public` [Algo\Method->getCondorcetBuilderVersion (...)](/Docs/api-reference/Algo_Method%20Class/Algo_Method--getCondorcetBuilderVersion().md): `string`  
* `final public` [Algo\Method->buildByCondorcetVersion](/Docs/api-reference/Algo_Method%20Class/Algo_Method--buildByCondorcetVersion.md)  
* `public` [Algo\Method->getCondorcetBuilderVersion (...)](/Docs/api-reference/Algo_Method%20Class/Algo_Method--getCondorcetBuilderVersion().md): `string`  
* `final public` [Algo\Method->buildByCondorcetVersion](/Docs/api-reference/Algo_Method%20Class/Algo_Method--buildByCondorcetVersion.md)  
* `public` [Algo\Method->getCondorcetBuilderVersion (...)](/Docs/api-reference/Algo_Method%20Class/Algo_Method--getCondorcetBuilderVersion().md): `string`  
* `final public` [Algo\Method->buildByCondorcetVersion](/Docs/api-reference/Algo_Method%20Class/Algo_Method--buildByCondorcetVersion.md)  
* `public` [Algo\Method->getCondorcetBuilderVersion (...)](/Docs/api-reference/Algo_Method%20Class/Algo_Method--getCondorcetBuilderVersion().md): `string`  
* `final public` [Algo\Method->buildByCondorcetVersion](/Docs/api-reference/Algo_Method%20Class/Algo_Method--buildByCondorcetVersion.md)  
* `public` [Algo\Method->getCondorcetBuilderVersion (...)](/Docs/api-reference/Algo_Method%20Class/Algo_Method--getCondorcetBuilderVersion().md): `string`  
* `final public` [Algo\Method->buildByCondorcetVersion](/Docs/api-reference/Algo_Method%20Class/Algo_Method--buildByCondorcetVersion.md)  
* `public` [Algo\Method->getCondorcetBuilderVersion (...)](/Docs/api-reference/Algo_Method%20Class/Algo_Method--getCondorcetBuilderVersion().md): `string`  
* `final public` [Algo\Method->buildByCondorcetVersion](/Docs/api-reference/Algo_Method%20Class/Algo_Method--buildByCondorcetVersion.md)  
* `public` [Algo\Method->getCondorcetBuilderVersion (...)](/Docs/api-reference/Algo_Method%20Class/Algo_Method--getCondorcetBuilderVersion().md): `string`  
* `final public` [Algo\Method->buildByCondorcetVersion](/Docs/api-reference/Algo_Method%20Class/Algo_Method--buildByCondorcetVersion.md)  
* `public` [Algo\Method->getCondorcetBuilderVersion (...)](/Docs/api-reference/Algo_Method%20Class/Algo_Method--getCondorcetBuilderVersion().md): `string`  
* `final public` [Algo\Method->buildByCondorcetVersion](/Docs/api-reference/Algo_Method%20Class/Algo_Method--buildByCondorcetVersion.md)  
* `public` [Algo\Method->getCondorcetBuilderVersion (...)](/Docs/api-reference/Algo_Method%20Class/Algo_Method--getCondorcetBuilderVersion().md): `string`  
* `final public` [Algo\Method->buildByCondorcetVersion](/Docs/api-reference/Algo_Method%20Class/Algo_Method--buildByCondorcetVersion.md)  
* `public` [Algo\Method->getCondorcetBuilderVersion (...)](/Docs/api-reference/Algo_Method%20Class/Algo_Method--getCondorcetBuilderVersion().md): `string`  
* `final public` [Algo\Method->buildByCondorcetVersion](/Docs/api-reference/Algo_Method%20Class/Algo_Method--buildByCondorcetVersion.md)  
* `public` [Algo\Method->getCondorcetBuilderVersion (...)](/Docs/api-reference/Algo_Method%20Class/Algo_Method--getCondorcetBuilderVersion().md): `string`  
* `final public` [Algo\Method->buildByCondorcetVersion](/Docs/api-reference/Algo_Method%20Class/Algo_Method--buildByCondorcetVersion.md)  
* `public` [Algo\Method->getCondorcetBuilderVersion (...)](/Docs/api-reference/Algo_Method%20Class/Algo_Method--getCondorcetBuilderVersion().md): `string`  
* `final public` [Algo\Method->buildByCondorcetVersion](/Docs/api-reference/Algo_Method%20Class/Algo_Method--buildByCondorcetVersion.md)  
* `public` [Algo\Method->getCondorcetBuilderVersion (...)](/Docs/api-reference/Algo_Method%20Class/Algo_Method--getCondorcetBuilderVersion().md): `string`  
* `final public` [Algo\Method->buildByCondorcetVersion](/Docs/api-reference/Algo_Method%20Class/Algo_Method--buildByCondorcetVersion.md)  
* `public` [Algo\Method->getCondorcetBuilderVersion (...)](/Docs/api-reference/Algo_Method%20Class/Algo_Method--getCondorcetBuilderVersion().md): `string`  
* `final public` [Algo\Method->buildByCondorcetVersion](/Docs/api-reference/Algo_Method%20Class/Algo_Method--buildByCondorcetVersion.md)  
* `public` [Algo\Method->getCondorcetBuilderVersion (...)](/Docs/api-reference/Algo_Method%20Class/Algo_Method--getCondorcetBuilderVersion().md): `string`  

### CondorcetPHP\Condorcet\Algo\Pairwise\FilteredPairwise Class  

* `public string` [$buildByCondorcetVersion](/Docs/api-reference/Algo_Pairwise_Pairwise%20Class/Algo_Pairwise_Pairwise--buildByCondorcetVersion.md)

* `final public` [Algo\Pairwise\Pairwise->buildByCondorcetVersion](/Docs/api-reference/Algo_Pairwise_Pairwise%20Class/Algo_Pairwise_Pairwise--buildByCondorcetVersion.md)  
* `public` [Algo\Pairwise\Pairwise->candidateWinVersus (...)](/Docs/api-reference/Algo_Pairwise_Pairwise%20Class/Algo_Pairwise_Pairwise--candidateWinVersus().md): `bool`  
* `public` [Algo\Pairwise\Pairwise->compareCandidates (...)](/Docs/api-reference/Algo_Pairwise_Pairwise%20Class/Algo_Pairwise_Pairwise--compareCandidates().md): `int`  
* `public` [Algo\Pairwise\Pairwise->getCondorcetBuilderVersion (...)](/Docs/api-reference/Algo_Pairwise_Pairwise%20Class/Algo_Pairwise_Pairwise--getCondorcetBuilderVersion().md): `string`  
* `public` [Algo\Pairwise\Pairwise->getExplicitPairwise ()](/Docs/api-reference/Algo_Pairwise_Pairwise%20Class/Algo_Pairwise_Pairwise--getExplicitPairwise().md): `array`  

### CondorcetPHP\Condorcet\Algo\Pairwise\Pairwise Class  

* `public string` [$buildByCondorcetVersion](/Docs/api-reference/Algo_Pairwise_Pairwise%20Class/Algo_Pairwise_Pairwise--buildByCondorcetVersion.md)

* `final public` [Algo\Pairwise\Pairwise->buildByCondorcetVersion](/Docs/api-reference/Algo_Pairwise_Pairwise%20Class/Algo_Pairwise_Pairwise--buildByCondorcetVersion.md)  
* `public` [Algo\Pairwise\Pairwise->candidateWinVersus (...)](/Docs/api-reference/Algo_Pairwise_Pairwise%20Class/Algo_Pairwise_Pairwise--candidateWinVersus().md): `bool`  
* `public` [Algo\Pairwise\Pairwise->compareCandidates (...)](/Docs/api-reference/Algo_Pairwise_Pairwise%20Class/Algo_Pairwise_Pairwise--compareCandidates().md): `int`  
* `public` [Algo\Pairwise\Pairwise->getCondorcetBuilderVersion (...)](/Docs/api-reference/Algo_Pairwise_Pairwise%20Class/Algo_Pairwise_Pairwise--getCondorcetBuilderVersion().md): `string`  
* `public` [Algo\Pairwise\Pairwise->getExplicitPairwise ()](/Docs/api-reference/Algo_Pairwise_Pairwise%20Class/Algo_Pairwise_Pairwise--getExplicitPairwise().md): `array`  
* `public static` [Algo\Tools\Combinations::useBigIntegerIfAvailable](/Docs/api-reference/Algo_Tools_Combinations%20Class/Algo_Tools_Combinations--useBigIntegerIfAvailable.md)  
* `public static` [Algo\Tools\Permutations::useBigIntegerIfAvailable](/Docs/api-reference/Algo_Tools_Permutations%20Class/Algo_Tools_Permutations--useBigIntegerIfAvailable.md)  

### CondorcetPHP\Condorcet\Algo\Tools\StvQuotas Enum  

* case Algo\Tools\StvQuotas::DROOP  
* case Algo\Tools\StvQuotas::HARE  
* case Algo\Tools\StvQuotas::HAGENBACH_BISCHOFF  
* case Algo\Tools\StvQuotas::IMPERIALI  

* `public static` [Algo\Tools\StvQuotas::fromString (...)](/Docs/api-reference/Algo_Tools_StvQuotas%20Class/Algo_Tools_StvQuotas--fromString().md): `self`  
* `final public` [DataManager\ArrayManager->buildByCondorcetVersion](/Docs/api-reference/DataManager_ArrayManager%20Class/DataManager_ArrayManager--buildByCondorcetVersion.md)  
* `public` [DataManager\ArrayManager->getCondorcetBuilderVersion (...)](/Docs/api-reference/DataManager_ArrayManager%20Class/DataManager_ArrayManager--getCondorcetBuilderVersion().md): `string`  

### CondorcetPHP\Condorcet\DataManager\VotesManager Class  

* `public string` [$buildByCondorcetVersion](/Docs/api-reference/DataManager_ArrayManager%20Class/DataManager_ArrayManager--buildByCondorcetVersion.md)

* `final public` [DataManager\ArrayManager->buildByCondorcetVersion](/Docs/api-reference/DataManager_ArrayManager%20Class/DataManager_ArrayManager--buildByCondorcetVersion.md)  
* `public` [DataManager\ArrayManager->getCondorcetBuilderVersion (...)](/Docs/api-reference/DataManager_ArrayManager%20Class/DataManager_ArrayManager--getCondorcetBuilderVersion().md): `string`  

### CondorcetPHP\Condorcet\DataManager\DataHandlerDrivers\PdoDriver\PdoHandlerDriver Class  

* `public const SEGMENT: (array)`  

* `public static bool` [$preferBlobInsteadVarchar](/Docs/api-reference/DataManager_DataHandlerDrivers_PdoDriver_PdoHandlerDriver%20Class/DataManager_DataHandlerDrivers_PdoDriver_PdoHandlerDriver--preferBlobInsteadVarchar.md)
* `public string` [$buildByCondorcetVersion](/Docs/api-reference/DataManager_DataHandlerDrivers_PdoDriver_PdoHandlerDriver%20Class/DataManager_DataHandlerDrivers_PdoDriver_PdoHandlerDriver--buildByCondorcetVersion.md)

* `public static` [DataManager\DataHandlerDrivers\PdoDriver\PdoHandlerDriver::preferBlobInsteadVarchar](/Docs/api-reference/DataManager_DataHandlerDrivers_PdoDriver_PdoHandlerDriver%20Class/DataManager_DataHandlerDrivers_PdoDriver_PdoHandlerDriver--preferBlobInsteadVarchar.md)  
* `final public` [DataManager\DataHandlerDrivers\PdoDriver\PdoHandlerDriver->buildByCondorcetVersion](/Docs/api-reference/DataManager_DataHandlerDrivers_PdoDriver_PdoHandlerDriver%20Class/DataManager_DataHandlerDrivers_PdoDriver_PdoHandlerDriver--buildByCondorcetVersion.md)  
* `public` [DataManager\DataHandlerDrivers\PdoDriver\PdoHandlerDriver->getCondorcetBuilderVersion (...)](/Docs/api-reference/DataManager_DataHandlerDrivers_PdoDriver_PdoHandlerDriver%20Class/DataManager_DataHandlerDrivers_PdoDriver_PdoHandlerDriver--getCondorcetBuilderVersion().md): `string`  

### CondorcetPHP\Condorcet\Throwable\AlgorithmWithoutRankingFeatureException Class  

* `public string` [$buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)

* `final public` [Throwable\CondorcetPublicApiException->buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)  
* `public` [Throwable\CondorcetPublicApiException->getCondorcetBuilderVersion (...)](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--getCondorcetBuilderVersion().md): `string`  

### CondorcetPHP\Condorcet\Throwable\CandidateDoesNotExistException Class  

* `public string` [$buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)

* `final public` [Throwable\CondorcetPublicApiException->buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)  
* `public` [Throwable\CondorcetPublicApiException->getCondorcetBuilderVersion (...)](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--getCondorcetBuilderVersion().md): `string`  

### CondorcetPHP\Condorcet\Throwable\CandidateExistsException Class  

* `public string` [$buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)

* `final public` [Throwable\CondorcetPublicApiException->buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)  
* `public` [Throwable\CondorcetPublicApiException->getCondorcetBuilderVersion (...)](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--getCondorcetBuilderVersion().md): `string`  

### CondorcetPHP\Condorcet\Throwable\CandidateInvalidNameException Class  

* `public string` [$buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)

* `final public` [Throwable\CondorcetPublicApiException->buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)  
* `public` [Throwable\CondorcetPublicApiException->getCondorcetBuilderVersion (...)](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--getCondorcetBuilderVersion().md): `string`  

### CondorcetPHP\Condorcet\Throwable\CandidatesMaxNumberReachedException Class  

* `public string` [$buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)

* `final public` [Throwable\CondorcetPublicApiException->buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)  
* `public` [Throwable\CondorcetPublicApiException->getCondorcetBuilderVersion (...)](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--getCondorcetBuilderVersion().md): `string`  

### CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException Class  

* `public string` [$buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)

* `final public` [Throwable\CondorcetPublicApiException->buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)  
* `public` [Throwable\CondorcetPublicApiException->getCondorcetBuilderVersion (...)](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--getCondorcetBuilderVersion().md): `string`  

### CondorcetPHP\Condorcet\Throwable\ConsoleInputException Class  

* `public string` [$buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)

* `final public` [Throwable\CondorcetPublicApiException->buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)  
* `public` [Throwable\CondorcetPublicApiException->getCondorcetBuilderVersion (...)](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--getCondorcetBuilderVersion().md): `string`  

### CondorcetPHP\Condorcet\Throwable\DataHandlerException Class  

* `public string` [$buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)

* `final public` [Throwable\CondorcetPublicApiException->buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)  
* `public` [Throwable\CondorcetPublicApiException->getCondorcetBuilderVersion (...)](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--getCondorcetBuilderVersion().md): `string`  

### CondorcetPHP\Condorcet\Throwable\ElectionFileFormatParseException Class  

* `public string` [$buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)

* `final public` [Throwable\CondorcetPublicApiException->buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)  
* `public` [Throwable\CondorcetPublicApiException->getCondorcetBuilderVersion (...)](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--getCondorcetBuilderVersion().md): `string`  

### CondorcetPHP\Condorcet\Throwable\ElectionObjectVersionMismatchException Class  

* `public string` [$buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)

* `final public` [Throwable\CondorcetPublicApiException->buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)  
* `public` [Throwable\CondorcetPublicApiException->getCondorcetBuilderVersion (...)](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--getCondorcetBuilderVersion().md): `string`  

### CondorcetPHP\Condorcet\Throwable\FileDoesNotExistException Class  

* `public string` [$buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)

* `final public` [Throwable\CondorcetPublicApiException->buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)  
* `public` [Throwable\CondorcetPublicApiException->getCondorcetBuilderVersion (...)](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--getCondorcetBuilderVersion().md): `string`  

### CondorcetPHP\Condorcet\Throwable\MethodLimitReachedException Class  

* `public string` [$buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)

* `final public` [Throwable\CondorcetPublicApiException->buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)  
* `public` [Throwable\CondorcetPublicApiException->getCondorcetBuilderVersion (...)](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--getCondorcetBuilderVersion().md): `string`  

### CondorcetPHP\Condorcet\Throwable\NoCandidatesException Class  

* `public string` [$buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)

* `final public` [Throwable\CondorcetPublicApiException->buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)  
* `public` [Throwable\CondorcetPublicApiException->getCondorcetBuilderVersion (...)](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--getCondorcetBuilderVersion().md): `string`  

### CondorcetPHP\Condorcet\Throwable\NoSeatsException Class  

* `public string` [$buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)

* `final public` [Throwable\CondorcetPublicApiException->buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)  
* `public` [Throwable\CondorcetPublicApiException->getCondorcetBuilderVersion (...)](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--getCondorcetBuilderVersion().md): `string`  

### CondorcetPHP\Condorcet\Throwable\ParseVotesMaxNumberReachedException Class  

* `public string` [$buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)

* `final public` [Throwable\CondorcetPublicApiException->buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)  
* `public` [Throwable\CondorcetPublicApiException->getCondorcetBuilderVersion (...)](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--getCondorcetBuilderVersion().md): `string`  

### CondorcetPHP\Condorcet\Throwable\ResultException Class  

* `public string` [$buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)

* `final public` [Throwable\CondorcetPublicApiException->buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)  
* `public` [Throwable\CondorcetPublicApiException->getCondorcetBuilderVersion (...)](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--getCondorcetBuilderVersion().md): `string`  

### CondorcetPHP\Condorcet\Throwable\ResultRequestedWithoutVotesException Class  

* `public string` [$buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)

* `final public` [Throwable\CondorcetPublicApiException->buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)  
* `public` [Throwable\CondorcetPublicApiException->getCondorcetBuilderVersion (...)](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--getCondorcetBuilderVersion().md): `string`  

### CondorcetPHP\Condorcet\Throwable\StatsEntryDoNotExistException Class  

* `public string` [$buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)

* `final public` [Throwable\CondorcetPublicApiException->buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)  
* `public` [Throwable\CondorcetPublicApiException->getCondorcetBuilderVersion (...)](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--getCondorcetBuilderVersion().md): `string`  

### CondorcetPHP\Condorcet\Throwable\StvQuotaNotImplementedException Class  

* `public string` [$buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)

* `final public` [Throwable\CondorcetPublicApiException->buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)  
* `public` [Throwable\CondorcetPublicApiException->getCondorcetBuilderVersion (...)](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--getCondorcetBuilderVersion().md): `string`  

### CondorcetPHP\Condorcet\Throwable\TagsFilterException Class  

* `public string` [$buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)

* `final public` [Throwable\CondorcetPublicApiException->buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)  
* `public` [Throwable\CondorcetPublicApiException->getCondorcetBuilderVersion (...)](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--getCondorcetBuilderVersion().md): `string`  

### CondorcetPHP\Condorcet\Throwable\TimerException Class  

* `public string` [$buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)

* `final public` [Throwable\CondorcetPublicApiException->buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)  
* `public` [Throwable\CondorcetPublicApiException->getCondorcetBuilderVersion (...)](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--getCondorcetBuilderVersion().md): `string`  

### CondorcetPHP\Condorcet\Throwable\VoteConstraintException Class  

* `public string` [$buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)

* `final public` [Throwable\CondorcetPublicApiException->buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)  
* `public` [Throwable\CondorcetPublicApiException->getCondorcetBuilderVersion (...)](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--getCondorcetBuilderVersion().md): `string`  

### CondorcetPHP\Condorcet\Throwable\VoteException Class  

* `public string` [$buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)

* `final public` [Throwable\CondorcetPublicApiException->buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)  
* `public` [Throwable\CondorcetPublicApiException->getCondorcetBuilderVersion (...)](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--getCondorcetBuilderVersion().md): `string`  

### CondorcetPHP\Condorcet\Throwable\VoteInvalidFormatException Class  

* `public string` [$buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)

* `final public` [Throwable\CondorcetPublicApiException->buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)  
* `public` [Throwable\CondorcetPublicApiException->getCondorcetBuilderVersion (...)](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--getCondorcetBuilderVersion().md): `string`  

### CondorcetPHP\Condorcet\Throwable\VoteManagerException Class  

* `public string` [$buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)

* `final public` [Throwable\CondorcetPublicApiException->buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)  
* `public` [Throwable\CondorcetPublicApiException->getCondorcetBuilderVersion (...)](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--getCondorcetBuilderVersion().md): `string`  

### CondorcetPHP\Condorcet\Throwable\VoteMaxNumberReachedException Class  

* `public string` [$buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)

* `final public` [Throwable\CondorcetPublicApiException->buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)  
* `public` [Throwable\CondorcetPublicApiException->getCondorcetBuilderVersion (...)](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--getCondorcetBuilderVersion().md): `string`  

### CondorcetPHP\Condorcet\Throwable\VoteNotLinkedException Class  

* `public string` [$buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)

* `final public` [Throwable\CondorcetPublicApiException->buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)  
* `public` [Throwable\CondorcetPublicApiException->getCondorcetBuilderVersion (...)](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--getCondorcetBuilderVersion().md): `string`  

### CondorcetPHP\Condorcet\Throwable\VotingHasStartedException Class  

* `public string` [$buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)

* `final public` [Throwable\CondorcetPublicApiException->buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)  
* `public` [Throwable\CondorcetPublicApiException->getCondorcetBuilderVersion (...)](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--getCondorcetBuilderVersion().md): `string`  

### CondorcetPHP\Condorcet\Throwable\VotingMethodIsNotImplemented Class  

* `public string` [$buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)

* `final public` [Throwable\CondorcetPublicApiException->buildByCondorcetVersion](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--buildByCondorcetVersion.md)  
* `public` [Throwable\CondorcetPublicApiException->getCondorcetBuilderVersion (...)](/Docs/api-reference/Throwable_CondorcetPublicApiException%20Class/Throwable_CondorcetPublicApiException--getCondorcetBuilderVersion().md): `string`  

### CondorcetPHP\Condorcet\Throwable\Internal\CondorcetInternalError Class  

* `public string` [$buildByCondorcetVersion](/Docs/api-reference/Throwable_Internal_CondorcetInternalError%20Class/Throwable_Internal_CondorcetInternalError--buildByCondorcetVersion.md)

* `final public` [Throwable\Internal\CondorcetInternalError->buildByCondorcetVersion](/Docs/api-reference/Throwable_Internal_CondorcetInternalError%20Class/Throwable_Internal_CondorcetInternalError--buildByCondorcetVersion.md)  
* `public` [Throwable\Internal\CondorcetInternalError->getCondorcetBuilderVersion (...)](/Docs/api-reference/Throwable_Internal_CondorcetInternalError%20Class/Throwable_Internal_CondorcetInternalError--getCondorcetBuilderVersion().md): `string`  

### CondorcetPHP\Condorcet\Timer\Chrono Class  

* `public string` [$buildByCondorcetVersion](/Docs/api-reference/Timer_Chrono%20Class/Timer_Chrono--buildByCondorcetVersion.md)

* `final public` [Timer\Chrono->buildByCondorcetVersion](/Docs/api-reference/Timer_Chrono%20Class/Timer_Chrono--buildByCondorcetVersion.md)  
* `public` [Timer\Chrono->getCondorcetBuilderVersion (...)](/Docs/api-reference/Timer_Chrono%20Class/Timer_Chrono--getCondorcetBuilderVersion().md): `string`  

### CondorcetPHP\Condorcet\Timer\Manager Class  

* `public string` [$buildByCondorcetVersion](/Docs/api-reference/Timer_Manager%20Class/Timer_Manager--buildByCondorcetVersion.md)

* `final public` [Timer\Manager->buildByCondorcetVersion](/Docs/api-reference/Timer_Manager%20Class/Timer_Manager--buildByCondorcetVersion.md)  
* `public` [Timer\Manager->getCondorcetBuilderVersion (...)](/Docs/api-reference/Timer_Manager%20Class/Timer_Manager--getCondorcetBuilderVersion().md): `string`  
* `public` [Timer\Manager->getHistory ()](/Docs/api-reference/Timer_Manager%20Class/Timer_Manager--getHistory().md): `array`  

### CondorcetPHP\Condorcet\Tools\Converters\CivsFormat Class  

* `public static` [Tools\Converters\CivsFormat::createFromElection (...)](/Docs/api-reference/Tools_Converters_CivsFormat%20Class/Tools_Converters_CivsFormat--createFromElection().md): `string|true`  

### CondorcetPHP\Condorcet\Tools\Converters\DavidHillFormat Class  

* `readonly public array` [$candidates](/Docs/api-reference/Tools_Converters_DavidHillFormat%20Class/Tools_Converters_DavidHillFormat--candidates.md)
* `readonly public int` [$seatsToElect](/Docs/api-reference/Tools_Converters_DavidHillFormat%20Class/Tools_Converters_DavidHillFormat--seatsToElect.md)

* `public` [Tools\Converters\DavidHillFormat->__construct (...)](/Docs/api-reference/Tools_Converters_DavidHillFormat%20Class/Tools_Converters_DavidHillFormat--__construct().md)  
* `final public readonly` [Tools\Converters\DavidHillFormat->candidates](/Docs/api-reference/Tools_Converters_DavidHillFormat%20Class/Tools_Converters_DavidHillFormat--candidates.md)  
* `final public readonly` [Tools\Converters\DavidHillFormat->seatsToElect](/Docs/api-reference/Tools_Converters_DavidHillFormat%20Class/Tools_Converters_DavidHillFormat--seatsToElect.md)  
* `public` [Tools\Converters\DavidHillFormat->setDataToAnElection (...)](/Docs/api-reference/Tools_Converters_DavidHillFormat%20Class/Tools_Converters_DavidHillFormat--setDataToAnElection().md): `CondorcetPHP\Condorcet\Election`  

### CondorcetPHP\Condorcet\Tools\Converters\DebianFormat Class  

* `readonly public array` [$candidates](/Docs/api-reference/Tools_Converters_DebianFormat%20Class/Tools_Converters_DebianFormat--candidates.md)
* `readonly public array` [$votes](/Docs/api-reference/Tools_Converters_DebianFormat%20Class/Tools_Converters_DebianFormat--votes.md)

* `public` [Tools\Converters\DebianFormat->__construct (...)](/Docs/api-reference/Tools_Converters_DebianFormat%20Class/Tools_Converters_DebianFormat--__construct().md)  
* `final public readonly` [Tools\Converters\DebianFormat->candidates](/Docs/api-reference/Tools_Converters_DebianFormat%20Class/Tools_Converters_DebianFormat--candidates.md)  
* `public` [Tools\Converters\DebianFormat->setDataToAnElection (...)](/Docs/api-reference/Tools_Converters_DebianFormat%20Class/Tools_Converters_DebianFormat--setDataToAnElection().md): `CondorcetPHP\Condorcet\Election`  
* `final public readonly` [Tools\Converters\DebianFormat->votes](/Docs/api-reference/Tools_Converters_DebianFormat%20Class/Tools_Converters_DebianFormat--votes.md)  

### CondorcetPHP\Condorcet\Tools\Converters\CEF\CondorcetElectionFormat Class  

* `readonly public array` [$parameters](/Docs/api-reference/Tools_Converters_CEF_CondorcetElectionFormat%20Class/Tools_Converters_CEF_CondorcetElectionFormat--parameters.md)
* `readonly public array` [$candidates](/Docs/api-reference/Tools_Converters_CEF_CondorcetElectionFormat%20Class/Tools_Converters_CEF_CondorcetElectionFormat--candidates.md)
* `readonly public int` [$seatsToElect](/Docs/api-reference/Tools_Converters_CEF_CondorcetElectionFormat%20Class/Tools_Converters_CEF_CondorcetElectionFormat--seatsToElect.md)
* `readonly public bool` [$implicitRanking](/Docs/api-reference/Tools_Converters_CEF_CondorcetElectionFormat%20Class/Tools_Converters_CEF_CondorcetElectionFormat--implicitRanking.md)
* `readonly public bool` [$voteWeight](/Docs/api-reference/Tools_Converters_CEF_CondorcetElectionFormat%20Class/Tools_Converters_CEF_CondorcetElectionFormat--voteWeight.md)
* `readonly public bool` [$CandidatesParsedFromVotes](/Docs/api-reference/Tools_Converters_CEF_CondorcetElectionFormat%20Class/Tools_Converters_CEF_CondorcetElectionFormat--CandidatesParsedFromVotes.md)
* `readonly public int` [$invalidBlocksCount](/Docs/api-reference/Tools_Converters_CEF_CondorcetElectionFormat%20Class/Tools_Converters_CEF_CondorcetElectionFormat--invalidBlocksCount.md)

* `public static` [Tools\Converters\CEF\CondorcetElectionFormat::createFromElection (...)](/Docs/api-reference/Tools_Converters_CEF_CondorcetElectionFormat%20Class/Tools_Converters_CEF_CondorcetElectionFormat--createFromElection().md): `?string`  
* `public static` [Tools\Converters\CEF\CondorcetElectionFormat::createFromString (...)](/Docs/api-reference/Tools_Converters_CEF_CondorcetElectionFormat%20Class/Tools_Converters_CEF_CondorcetElectionFormat--createFromString().md): `self`  
* `final public readonly` [Tools\Converters\CEF\CondorcetElectionFormat->CandidatesParsedFromVotes](/Docs/api-reference/Tools_Converters_CEF_CondorcetElectionFormat%20Class/Tools_Converters_CEF_CondorcetElectionFormat--CandidatesParsedFromVotes.md)  
* `public` [Tools\Converters\CEF\CondorcetElectionFormat->__construct (...)](/Docs/api-reference/Tools_Converters_CEF_CondorcetElectionFormat%20Class/Tools_Converters_CEF_CondorcetElectionFormat--__construct().md)  
* `final public readonly` [Tools\Converters\CEF\CondorcetElectionFormat->candidates](/Docs/api-reference/Tools_Converters_CEF_CondorcetElectionFormat%20Class/Tools_Converters_CEF_CondorcetElectionFormat--candidates.md)  
* `final public readonly` [Tools\Converters\CEF\CondorcetElectionFormat->implicitRanking](/Docs/api-reference/Tools_Converters_CEF_CondorcetElectionFormat%20Class/Tools_Converters_CEF_CondorcetElectionFormat--implicitRanking.md)  
* `final public readonly` [Tools\Converters\CEF\CondorcetElectionFormat->invalidBlocksCount](/Docs/api-reference/Tools_Converters_CEF_CondorcetElectionFormat%20Class/Tools_Converters_CEF_CondorcetElectionFormat--invalidBlocksCount.md)  
* `final public readonly` [Tools\Converters\CEF\CondorcetElectionFormat->parameters](/Docs/api-reference/Tools_Converters_CEF_CondorcetElectionFormat%20Class/Tools_Converters_CEF_CondorcetElectionFormat--parameters.md)  
* `final public readonly` [Tools\Converters\CEF\CondorcetElectionFormat->seatsToElect](/Docs/api-reference/Tools_Converters_CEF_CondorcetElectionFormat%20Class/Tools_Converters_CEF_CondorcetElectionFormat--seatsToElect.md)  
* `public` [Tools\Converters\CEF\CondorcetElectionFormat->setDataToAnElection (...)](/Docs/api-reference/Tools_Converters_CEF_CondorcetElectionFormat%20Class/Tools_Converters_CEF_CondorcetElectionFormat--setDataToAnElection().md): `CondorcetPHP\Condorcet\Election`  
* `final public readonly` [Tools\Converters\CEF\CondorcetElectionFormat->voteWeight](/Docs/api-reference/Tools_Converters_CEF_CondorcetElectionFormat%20Class/Tools_Converters_CEF_CondorcetElectionFormat--voteWeight.md)  

### CondorcetPHP\Condorcet\Tools\Randomizers\ArrayRandomizer Class  

* `public ?int` [$maxCandidatesRanked](/Docs/api-reference/Tools_Randomizers_ArrayRandomizer%20Class/Tools_Randomizers_ArrayRandomizer--maxCandidatesRanked.md)
* `public int|false` [$minCandidatesRanked](/Docs/api-reference/Tools_Randomizers_ArrayRandomizer%20Class/Tools_Randomizers_ArrayRandomizer--minCandidatesRanked.md)
* `public ?int` [$maxRanksCount](/Docs/api-reference/Tools_Randomizers_ArrayRandomizer%20Class/Tools_Randomizers_ArrayRandomizer--maxRanksCount.md)
* `public int|float` [$tiesProbability](/Docs/api-reference/Tools_Randomizers_ArrayRandomizer%20Class/Tools_Randomizers_ArrayRandomizer--tiesProbability.md)

* `public` [Tools\Randomizers\ArrayRandomizer->__construct (...)](/Docs/api-reference/Tools_Randomizers_ArrayRandomizer%20Class/Tools_Randomizers_ArrayRandomizer--__construct().md)  
* `public` [Tools\Randomizers\ArrayRandomizer->countCandidates ()](/Docs/api-reference/Tools_Randomizers_ArrayRandomizer%20Class/Tools_Randomizers_ArrayRandomizer--countCandidates().md): `int`  
* `public` [Tools\Randomizers\ArrayRandomizer->maxCandidatesRanked](/Docs/api-reference/Tools_Randomizers_ArrayRandomizer%20Class/Tools_Randomizers_ArrayRandomizer--maxCandidatesRanked.md)  
* `public` [Tools\Randomizers\ArrayRandomizer->maxRanksCount](/Docs/api-reference/Tools_Randomizers_ArrayRandomizer%20Class/Tools_Randomizers_ArrayRandomizer--maxRanksCount.md)  
* `public` [Tools\Randomizers\ArrayRandomizer->minCandidatesRanked](/Docs/api-reference/Tools_Randomizers_ArrayRandomizer%20Class/Tools_Randomizers_ArrayRandomizer--minCandidatesRanked.md)  
* `public` [Tools\Randomizers\ArrayRandomizer->setCandidates (...)](/Docs/api-reference/Tools_Randomizers_ArrayRandomizer%20Class/Tools_Randomizers_ArrayRandomizer--setCandidates().md): `void`  
* `public` [Tools\Randomizers\ArrayRandomizer->shuffle ()](/Docs/api-reference/Tools_Randomizers_ArrayRandomizer%20Class/Tools_Randomizers_ArrayRandomizer--shuffle().md): `array`  
* `public` [Tools\Randomizers\ArrayRandomizer->tiesProbability](/Docs/api-reference/Tools_Randomizers_ArrayRandomizer%20Class/Tools_Randomizers_ArrayRandomizer--tiesProbability.md)  

### CondorcetPHP\Condorcet\Tools\Randomizers\VoteRandomizer Class  

* `public ?int` [$maxCandidatesRanked](/Docs/api-reference/Tools_Randomizers_ArrayRandomizer%20Class/Tools_Randomizers_ArrayRandomizer--maxCandidatesRanked.md)
* `public int|false` [$minCandidatesRanked](/Docs/api-reference/Tools_Randomizers_ArrayRandomizer%20Class/Tools_Randomizers_ArrayRandomizer--minCandidatesRanked.md)
* `public ?int` [$maxRanksCount](/Docs/api-reference/Tools_Randomizers_ArrayRandomizer%20Class/Tools_Randomizers_ArrayRandomizer--maxRanksCount.md)
* `public int|float` [$tiesProbability](/Docs/api-reference/Tools_Randomizers_ArrayRandomizer%20Class/Tools_Randomizers_ArrayRandomizer--tiesProbability.md)

* `public` [Tools\Randomizers\ArrayRandomizer->__construct (...)](/Docs/api-reference/Tools_Randomizers_ArrayRandomizer%20Class/Tools_Randomizers_ArrayRandomizer--__construct().md)  
* `public` [Tools\Randomizers\ArrayRandomizer->countCandidates ()](/Docs/api-reference/Tools_Randomizers_ArrayRandomizer%20Class/Tools_Randomizers_ArrayRandomizer--countCandidates().md): `int`  
* `public` [Tools\Randomizers\VoteRandomizer->getNewVote ()](/Docs/api-reference/Tools_Randomizers_VoteRandomizer%20Class/Tools_Randomizers_VoteRandomizer--getNewVote().md): `CondorcetPHP\Condorcet\Vote`  
* `public` [Tools\Randomizers\ArrayRandomizer->maxCandidatesRanked](/Docs/api-reference/Tools_Randomizers_ArrayRandomizer%20Class/Tools_Randomizers_ArrayRandomizer--maxCandidatesRanked.md)  
* `public` [Tools\Randomizers\ArrayRandomizer->maxRanksCount](/Docs/api-reference/Tools_Randomizers_ArrayRandomizer%20Class/Tools_Randomizers_ArrayRandomizer--maxRanksCount.md)  
* `public` [Tools\Randomizers\ArrayRandomizer->minCandidatesRanked](/Docs/api-reference/Tools_Randomizers_ArrayRandomizer%20Class/Tools_Randomizers_ArrayRandomizer--minCandidatesRanked.md)  
* `public` [Tools\Randomizers\ArrayRandomizer->setCandidates (...)](/Docs/api-reference/Tools_Randomizers_ArrayRandomizer%20Class/Tools_Randomizers_ArrayRandomizer--setCandidates().md): `void`  
* `public` [Tools\Randomizers\ArrayRandomizer->shuffle ()](/Docs/api-reference/Tools_Randomizers_ArrayRandomizer%20Class/Tools_Randomizers_ArrayRandomizer--shuffle().md): `array`  
* `public` [Tools\Randomizers\ArrayRandomizer->tiesProbability](/Docs/api-reference/Tools_Randomizers_ArrayRandomizer%20Class/Tools_Randomizers_ArrayRandomizer--tiesProbability.md)  

### CondorcetPHP\Condorcet\Utils\CondorcetUtil Class  

* `public static` [Utils\CondorcetUtil::format (...)](/Docs/api-reference/Utils_CondorcetUtil%20Class/Utils_CondorcetUtil--format().md): `mixed`  



## Full Class & API Reference
_Including above methods from public API_


#### `Abstract CondorcetPHP\Condorcet\Algo\Method `  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Method.php#L23)

```php
* public const IS_PROPORTIONAL: (boolean)
* public const IS_DETERMINISTIC: (boolean)
* public const DECIMAL_PRECISION: (integer)

* public static ?int $MaxCandidates
* protected ?CondorcetPHP\Condorcet\Result $Result
* protected WeakReference $selfElection
* public string $buildByCondorcetVersion

* public static MaxCandidates ()  
* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected Result ()  
* protected compute (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\StatsInterface  
* protected selfElection ()  
```

#### `CondorcetPHP\Condorcet\Algo\Methods\Borda\BordaCount extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/Borda/BordaCount.php#L19)

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
* public string $buildByCondorcetVersion

* public static MaxCandidates ()  
* public static optionStarting ()  
* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected Result ()  
* protected Stats ()  
* protected compute (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getScoreByCandidateRanking (int $CandidatesRanked, CondorcetPHP\Condorcet\Election $election): float  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
* protected selfElection ()  
```

#### `CondorcetPHP\Condorcet\Algo\Methods\Borda\DowdallSystem extends CondorcetPHP\Condorcet\Algo\Methods\Borda\BordaCount implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/Borda/DowdallSystem.php#L17)

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
* public string $buildByCondorcetVersion

* public static MaxCandidates ()  
* public static optionStarting ()  
* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected Result ()  
* protected Stats ()  
* protected compute (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getScoreByCandidateRanking (int $CandidatesRanked, CondorcetPHP\Condorcet\Election $election): float  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
* protected selfElection ()  
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
* public string $buildByCondorcetVersion

* public static MaxCandidates ()  
* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getLoser (): ?int  
* public getResult (): never  
* public getWinner (): ?int  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected CondorcetLoser ()  
* protected CondorcetWinner ()  
* protected Result ()  
* protected compute (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\StatsInterface  
* protected selfElection ()  
```

#### `CondorcetPHP\Condorcet\Algo\Methods\Copeland\Copeland extends CondorcetPHP\Condorcet\Algo\Methods\PairwiseStatsBased_Core implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/Copeland/Copeland.php#L18)

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
* public string $buildByCondorcetVersion

* public static MaxCandidates ()  
* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected Comparison ()  
* protected Result ()  
* protected compute (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
* protected looking (array $challenge): int  
* protected makeRanking (): void  
* protected selfElection ()  
```

#### `CondorcetPHP\Condorcet\Algo\Methods\Dodgson\DodgsonQuick extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/Dodgson/DodgsonQuick.php#L19)

```php
* public const METHOD_NAME: (array)
* public const IS_PROPORTIONAL: (boolean)
* public const IS_DETERMINISTIC: (boolean)
* public const DECIMAL_PRECISION: (integer)

* readonly protected array $Stats
* public static ?int $MaxCandidates
* protected ?CondorcetPHP\Condorcet\Result $Result
* protected WeakReference $selfElection
* public string $buildByCondorcetVersion

* public static MaxCandidates ()  
* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected Result ()  
* protected Stats ()  
* protected compute (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
* protected selfElection ()  
```

#### `CondorcetPHP\Condorcet\Algo\Methods\Dodgson\DodgsonTidemanApproximation extends CondorcetPHP\Condorcet\Algo\Methods\PairwiseStatsBased_Core implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/Dodgson/DodgsonTidemanApproximation.php#L19)

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
* public string $buildByCondorcetVersion

* public static MaxCandidates ()  
* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected Comparison ()  
* protected Result ()  
* protected compute (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
* protected looking (array $challenge): int  
* protected makeRanking (): void  
* protected selfElection ()  
```

#### `Abstract CondorcetPHP\Condorcet\Algo\Methods\HighestAverages\HighestAverages_Core extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/HighestAverages/HighestAverages_Core.php#L18)

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
* public string $buildByCondorcetVersion

* public static MaxCandidates ()  
* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected Result ()  
* protected candidatesSeats ()  
* protected candidatesVotes ()  
* protected compute (): void  
* protected computeQuotient (int $votesWeight, int $seats): float  
* protected countVotesPerCandidates (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
* protected makeRounds (): array  
* protected rounds ()  
* protected selfElection ()  
```

#### `CondorcetPHP\Condorcet\Algo\Methods\HighestAverages\Jefferson extends CondorcetPHP\Condorcet\Algo\Methods\HighestAverages\HighestAverages_Core implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/HighestAverages/Jefferson.php#L18)

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
* public string $buildByCondorcetVersion

* public static MaxCandidates ()  
* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected Result ()  
* protected candidatesSeats ()  
* protected candidatesVotes ()  
* protected compute (): void  
* protected computeQuotient (int $votesWeight, int $seats): float  
* protected countVotesPerCandidates (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
* protected makeRounds (): array  
* protected rounds ()  
* protected selfElection ()  
```

#### `CondorcetPHP\Condorcet\Algo\Methods\HighestAverages\SainteLague extends CondorcetPHP\Condorcet\Algo\Methods\HighestAverages\HighestAverages_Core implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/HighestAverages/SainteLague.php#L18)

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
* public string $buildByCondorcetVersion

* public static MaxCandidates ()  
* public static optionFirstDivisor ()  
* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected Result ()  
* protected candidatesSeats ()  
* protected candidatesVotes ()  
* protected compute (): void  
* protected computeQuotient (int $votesWeight, int $seats): float  
* protected countVotesPerCandidates (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
* protected makeRounds (): array  
* protected rounds ()  
* protected selfElection ()  
```

#### `CondorcetPHP\Condorcet\Algo\Methods\InstantRunoff\InstantRunoff extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/InstantRunoff/InstantRunoff.php#L19)

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
* public string $buildByCondorcetVersion

* public static MaxCandidates ()  
* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public majority ()  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected Result ()  
* protected Stats ()  
* protected compute (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
* protected makeScore (array $candidateDone): array  
* protected selfElection ()  
```

#### `CondorcetPHP\Condorcet\Algo\Methods\KemenyYoung\KemenyYoung extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/KemenyYoung/KemenyYoung.php#L23)

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
* public string $buildByCondorcetVersion

* public static MaxCandidates ()  
* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected Conflits ()  
* protected MaxScore ()  
* protected Result ()  
* protected bestRankingKey ()  
* protected bestRankingTab ()  
* protected candidatesKey ()  
* protected compute (): void  
* protected computeMaxAndConflicts (): void  
* protected computeOneScore (array $ranking, CondorcetPHP\Condorcet\Algo\Pairwise\Pairwise $pairwise): int  
* protected conflictInfos (): void  
* protected countElectionCandidates ()  
* protected countPossibleRanking ()  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getPossibleRankingIterator (): Generator  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
* protected makeRanking (): void  
* protected selfElection ()  
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
* public string $buildByCondorcetVersion

* public static MaxCandidates ()  
* public static optionQuota ()  
* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected Result ()  
* protected candidatesSeats ()  
* protected candidatesVotes ()  
* protected compute (): void  
* protected computeQuotient (int $votesWeight, int $seats): float  
* protected countVotesPerCandidates (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
* protected makeRounds (): array  
* protected rounds ()  
* protected selfElection ()  
```

#### `CondorcetPHP\Condorcet\Algo\Methods\Lotteries\RandomBallot extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/Lotteries/RandomBallot.php#L19)

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
* public string $buildByCondorcetVersion

* public static MaxCandidates ()  
* public static optionRandomizer ()  
* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public buildByCondorcetVersion ()  
* public compute (): void  
* public getCondorcetBuilderVersion (bool $major = false): string  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected Result ()  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected electedBallotKey ()  
* protected electedWeightLevel ()  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
* protected selfElection ()  
* protected totalElectionWeight ()  
```

#### `CondorcetPHP\Condorcet\Algo\Methods\Lotteries\RandomCandidates extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/Lotteries/RandomCandidates.php#L21)

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
* public string $buildByCondorcetVersion

* public static MaxCandidates ()  
* public static optionRandomizer ()  
* public static optionTiesProbability ()  
* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public buildByCondorcetVersion ()  
* public compute (): void  
* public getCondorcetBuilderVersion (bool $major = false): string  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected Result ()  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
* protected selfElection ()  
```

#### `CondorcetPHP\Condorcet\Algo\Methods\Majority\FirstPastThePost extends CondorcetPHP\Condorcet\Algo\Methods\Majority\MajorityCore implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/Majority/FirstPastThePost.php#L15)

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
* public string $buildByCondorcetVersion

* public static MaxCandidates ()  
* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected Result ()  
* protected Stats ()  
* protected admittedCandidates ()  
* protected compute (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected doOneRound (): array  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
* protected maxRound ()  
* protected numberOfTargetedCandidatesAfterEachRound ()  
* protected selfElection ()  
* protected targetNumberOfCandidatesForTheNextRound ()  
```

#### `Abstract CondorcetPHP\Condorcet\Algo\Methods\Majority\MajorityCore extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/Majority/MajorityCore.php#L18)

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
* public string $buildByCondorcetVersion

* public static MaxCandidates ()  
* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected Result ()  
* protected Stats ()  
* protected admittedCandidates ()  
* protected compute (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected doOneRound (): array  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
* protected maxRound ()  
* protected numberOfTargetedCandidatesAfterEachRound ()  
* protected selfElection ()  
* protected targetNumberOfCandidatesForTheNextRound ()  
```

#### `CondorcetPHP\Condorcet\Algo\Methods\Majority\MultipleRoundsSystem extends CondorcetPHP\Condorcet\Algo\Methods\Majority\MajorityCore implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/Majority/MultipleRoundsSystem.php#L17)

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
* public string $buildByCondorcetVersion

* public static MaxCandidates ()  
* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* protected static optionMAX_ROUND ()  
* protected static optionNUMBER_OF_TARGETED_CANDIDATES_AFTER_EACH_ROUND ()  
* protected static optionTARGET_NUMBER_OF_CANDIDATES_FOR_THE_NEXT_ROUND ()  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected Result ()  
* protected Stats ()  
* protected admittedCandidates ()  
* protected compute (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected doOneRound (): array  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
* protected maxRound ()  
* protected numberOfTargetedCandidatesAfterEachRound ()  
* protected selfElection ()  
* protected targetNumberOfCandidatesForTheNextRound ()  
```

#### `CondorcetPHP\Condorcet\Algo\Methods\Minimax\MinimaxMargin extends CondorcetPHP\Condorcet\Algo\Methods\PairwiseStatsBased_Core implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/Minimax/MinimaxMargin.php#L18)

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
* public string $buildByCondorcetVersion

* public static MaxCandidates ()  
* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected Comparison ()  
* protected Result ()  
* protected compute (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
* protected looking (array $challenge): int  
* protected makeRanking (): void  
* protected selfElection ()  
```

#### `CondorcetPHP\Condorcet\Algo\Methods\Minimax\MinimaxOpposition extends CondorcetPHP\Condorcet\Algo\Methods\PairwiseStatsBased_Core implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/Minimax/MinimaxOpposition.php#L18)

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
* public string $buildByCondorcetVersion

* public static MaxCandidates ()  
* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected Comparison ()  
* protected Result ()  
* protected compute (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
* protected looking (array $challenge): int  
* protected makeRanking (): void  
* protected selfElection ()  
```

#### `CondorcetPHP\Condorcet\Algo\Methods\Minimax\MinimaxWinning extends CondorcetPHP\Condorcet\Algo\Methods\PairwiseStatsBased_Core implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/Minimax/MinimaxWinning.php#L18)

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
* public string $buildByCondorcetVersion

* public static MaxCandidates ()  
* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected Comparison ()  
* protected Result ()  
* protected compute (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
* protected looking (array $challenge): int  
* protected makeRanking (): void  
* protected selfElection ()  
```

#### `Abstract CondorcetPHP\Condorcet\Algo\Methods\PairwiseStatsBased_Core extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/PairwiseStatsBased_Core.php#L18)

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
* public string $buildByCondorcetVersion

* public static MaxCandidates ()  
* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected Comparison ()  
* protected Result ()  
* protected compute (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
* protected looking (array $challenge): int  
* protected makeRanking (): void  
* protected selfElection ()  
```

#### `CondorcetPHP\Condorcet\Algo\Methods\RankedPairs\RP_VARIANT implements UnitEnum`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/RankedPairs/RP_VARIANT.php#L15)

```php
* case RP_VARIANT::UNDEFINED  
* case RP_VARIANT::WINNING  
* case RP_VARIANT::MARGIN  
* case RP_VARIANT::MINORITY  

* readonly public string $name

* public name ()  
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
* public string $buildByCondorcetVersion

* public static MaxCandidates ()  
* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected Arcs ()  
* protected PairwiseSort ()  
* protected Result ()  
* protected Stats ()  
* protected StatsDone ()  
* protected compute (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected followCycle (array $virtualArcs, int $startCandidateKey, int $searchCandidateKey, array $done = []): array  
* protected getArcsInCycle (array $virtualArcs): array  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
* protected getWinners (array $alreadyDone): array  
* protected makeArcs (): void  
* protected makeResult (): array  
* protected pairwiseSort (): array  
* protected selfElection ()  
```

#### `CondorcetPHP\Condorcet\Algo\Methods\RankedPairs\RankedPairsMargin extends CondorcetPHP\Condorcet\Algo\Methods\RankedPairs\RankedPairsCore implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/RankedPairs/RankedPairsMargin.php#L15)

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
* public string $buildByCondorcetVersion

* public static MaxCandidates ()  
* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected Arcs ()  
* protected PairwiseSort ()  
* protected Result ()  
* protected Stats ()  
* protected StatsDone ()  
* protected compute (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected followCycle (array $virtualArcs, int $startCandidateKey, int $searchCandidateKey, array $done = []): array  
* protected getArcsInCycle (array $virtualArcs): array  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
* protected getWinners (array $alreadyDone): array  
* protected makeArcs (): void  
* protected makeResult (): array  
* protected pairwiseSort (): array  
* protected selfElection ()  
```

#### `CondorcetPHP\Condorcet\Algo\Methods\RankedPairs\RankedPairsWinning extends CondorcetPHP\Condorcet\Algo\Methods\RankedPairs\RankedPairsCore implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/RankedPairs/RankedPairsWinning.php#L16)

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
* public string $buildByCondorcetVersion

* public static MaxCandidates ()  
* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected Arcs ()  
* protected PairwiseSort ()  
* protected Result ()  
* protected Stats ()  
* protected StatsDone ()  
* protected compute (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected followCycle (array $virtualArcs, int $startCandidateKey, int $searchCandidateKey, array $done = []): array  
* protected getArcsInCycle (array $virtualArcs): array  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
* protected getWinners (array $alreadyDone): array  
* protected makeArcs (): void  
* protected makeResult (): array  
* protected pairwiseSort (): array  
* protected selfElection ()  
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
* public string $buildByCondorcetVersion

* public static MaxCandidates ()  
* public static MaxOutcomeComparisons ()  
* public static optionCondorcetCompletionMethod ()  
* public static optionQuota ()  
* public static optionTieBreakerMethods ()  
* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected Result ()  
* protected Stats ()  
* protected candidatesElectedFromFirstRound ()  
* protected candidatesEliminatedFromFirstRound ()  
* protected compareOutcomes (): void  
* protected completionMethodPairwise ()  
* protected completionMethodResult ()  
* protected compute (): void  
* protected condorcetWinnerOutcome ()  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getOutcomesComparisonKey (int $MainOutcomeKey, int $ComparedOutcomeKey): string  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
* protected initialScoreTable ()  
* protected makeScore (array $surplus = [], array $candidateElected = [], array $candidateEliminated = []): array  
* protected outcomeComparisonTable ()  
* protected outcomes ()  
* protected selectBestOutcome (): void  
* protected selfElection ()  
* protected sortResultBeforeCut (array $result): void  
* protected votesNeededToWin ()  
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
* public string $buildByCondorcetVersion

* public static MaxCandidates ()  
* public static optionQuota ()  
* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected Result ()  
* protected Stats ()  
* protected compute (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
* protected makeScore (array $surplus = [], array $candidateElected = [], array $candidateEliminated = []): array  
* protected selfElection ()  
* protected votesNeededToWin ()  
```

#### `Abstract CondorcetPHP\Condorcet\Algo\Methods\Schulze\SchulzeCore extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/Schulze/SchulzeCore.php#L23)

```php
* public const IS_PROPORTIONAL: (boolean)
* public const IS_DETERMINISTIC: (boolean)
* public const DECIMAL_PRECISION: (integer)
* public const METHOD_NAME: (array)

* protected array $StrongestPaths
* public static ?int $MaxCandidates
* protected ?CondorcetPHP\Condorcet\Result $Result
* protected WeakReference $selfElection
* public string $buildByCondorcetVersion

* public static MaxCandidates ()  
* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected Result ()  
* protected StrongestPaths ()  
* protected compute (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
* protected makeRanking (): void  
* protected makeStrongestPaths (): void  
* protected prepareStrongestPath (): void  
* protected schulzeVariant (int $i, int $j, CondorcetPHP\Condorcet\Algo\Pairwise\Pairwise $pairwise): int|float  
* protected selfElection ()  
```

#### `CondorcetPHP\Condorcet\Algo\Methods\Schulze\SchulzeMargin extends CondorcetPHP\Condorcet\Algo\Methods\Schulze\SchulzeCore implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/Schulze/SchulzeMargin.php#L17)

```php
* public const METHOD_NAME: (array)
* public const IS_PROPORTIONAL: (boolean)
* public const IS_DETERMINISTIC: (boolean)
* public const DECIMAL_PRECISION: (integer)

* protected array $StrongestPaths
* public static ?int $MaxCandidates
* protected ?CondorcetPHP\Condorcet\Result $Result
* protected WeakReference $selfElection
* public string $buildByCondorcetVersion

* public static MaxCandidates ()  
* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected Result ()  
* protected StrongestPaths ()  
* protected compute (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
* protected makeRanking (): void  
* protected makeStrongestPaths (): void  
* protected prepareStrongestPath (): void  
* protected schulzeVariant (int $i, int $j, CondorcetPHP\Condorcet\Algo\Pairwise\Pairwise $pairwise): int  
* protected selfElection ()  
```

#### `CondorcetPHP\Condorcet\Algo\Methods\Schulze\SchulzeRatio extends CondorcetPHP\Condorcet\Algo\Methods\Schulze\SchulzeCore implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/Schulze/SchulzeRatio.php#L17)

```php
* public const METHOD_NAME: (array)
* public const IS_PROPORTIONAL: (boolean)
* public const IS_DETERMINISTIC: (boolean)
* public const DECIMAL_PRECISION: (integer)

* protected array $StrongestPaths
* public static ?int $MaxCandidates
* protected ?CondorcetPHP\Condorcet\Result $Result
* protected WeakReference $selfElection
* public string $buildByCondorcetVersion

* public static MaxCandidates ()  
* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected Result ()  
* protected StrongestPaths ()  
* protected compute (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
* protected makeRanking (): void  
* protected makeStrongestPaths (): void  
* protected prepareStrongestPath (): void  
* protected schulzeVariant (int $i, int $j, CondorcetPHP\Condorcet\Algo\Pairwise\Pairwise $pairwise): float  
* protected selfElection ()  
```

#### `CondorcetPHP\Condorcet\Algo\Methods\Schulze\SchulzeWinning extends CondorcetPHP\Condorcet\Algo\Methods\Schulze\SchulzeCore implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/Schulze/SchulzeWinning.php#L17)

```php
* public const METHOD_NAME: (array)
* public const IS_PROPORTIONAL: (boolean)
* public const IS_DETERMINISTIC: (boolean)
* public const DECIMAL_PRECISION: (integer)

* protected array $StrongestPaths
* public static ?int $MaxCandidates
* protected ?CondorcetPHP\Condorcet\Result $Result
* protected WeakReference $selfElection
* public string $buildByCondorcetVersion

* public static MaxCandidates ()  
* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected Result ()  
* protected StrongestPaths ()  
* protected compute (): void  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
* protected makeRanking (): void  
* protected makeStrongestPaths (): void  
* protected prepareStrongestPath (): void  
* protected schulzeVariant (int $i, int $j, CondorcetPHP\Condorcet\Algo\Pairwise\Pairwise $pairwise): int  
* protected selfElection ()  
```

#### `CondorcetPHP\Condorcet\Algo\Methods\Smith\SchwartzSet extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/Smith/SchwartzSet.php#L24)

```php
* public const METHOD_NAME: (array)
* public const IS_PROPORTIONAL: (boolean)
* public const IS_DETERMINISTIC: (boolean)
* public const DECIMAL_PRECISION: (integer)

* readonly protected array $SchwartzSet
* public static ?int $MaxCandidates
* protected ?CondorcetPHP\Condorcet\Result $Result
* protected WeakReference $selfElection
* public string $buildByCondorcetVersion

* public static MaxCandidates ()  
* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected Result ()  
* protected SchwartzSet ()  
* protected compute (): void  
* protected computeSchwartzSet (): array  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
* protected selfElection ()  
* private consolidateStrongComponents (array $graph, array $sccs): array  
* private dfs (array $graph, int $node, array $visited, array $stack): void  
* private dfsReverse (array $graph, int $node, array $visited, array $scc): void  
* private findStronglyConnectedComponents (array $graph, array $reversedGraph, array $nodes): array  
```

#### `CondorcetPHP\Condorcet\Algo\Methods\Smith\SmithSet extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Methods/Smith/SmithSet.php#L23)

```php
* public const METHOD_NAME: (array)
* public const IS_PROPORTIONAL: (boolean)
* public const IS_DETERMINISTIC: (boolean)
* public const DECIMAL_PRECISION: (integer)

* readonly protected array $SmithSet
* public static ?int $MaxCandidates
* protected ?CondorcetPHP\Condorcet\Result $Result
* protected WeakReference $selfElection
* public string $buildByCondorcetVersion

* public static MaxCandidates ()  
* public static setOption (string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): true  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public __serialize (): array  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getResult (): CondorcetPHP\Condorcet\Result  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* protected Result ()  
* protected SmithSet ()  
* protected compute (): void  
* protected computeSmithSet (): array  
* protected createResult (array $result): CondorcetPHP\Condorcet\Result  
* protected getStats (): CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats  
* protected selfElection ()  
```

#### `CondorcetPHP\Condorcet\Algo\Pairwise\FilteredPairwise extends CondorcetPHP\Condorcet\Algo\Pairwise\Pairwise implements Traversable, Iterator, ArrayAccess`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Pairwise/FilteredPairwise.php#L15)

```php
* readonly protected array $candidates
* readonly public ?array $tags
* readonly public int|bool $withTags
* readonly protected array $Pairwise_Model
* protected array $Pairwise
* protected ?array $explicitPairwise
* protected WeakReference $selfElection
* public string $buildByCondorcetVersion

* public __construct (CondorcetPHP\Condorcet\Election $link, array|string|null $tags = null, int|bool $withTags = true)  
* public __serialize (): array  
* public addNewVote (int $key): void  
* public buildByCondorcetVersion ()  
* public candidateKeyWinVersus (int $candidateKey, int $opponentKey): bool  
* public candidateWinVersus (CondorcetPHP\Condorcet\Candidate|string $candidate, CondorcetPHP\Condorcet\Candidate|string $opponent): bool  
* public compareCandidates (CondorcetPHP\Condorcet\Candidate|string $a, CondorcetPHP\Condorcet\Candidate|string $b): int  
* public compareCandidatesKeys (int $aKey, int $bKey): int  
* public current (): array  
* public getCondorcetBuilderVersion (bool $major = false): string  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getExplicitPairwise (): array  
* public key (): ?int  
* public next (): void  
* public offsetExists (mixed $offset): bool  
* public offsetGet (mixed $offset): ?array  
* public offsetSet (mixed $offset, mixed $value): void  
* public offsetUnset (mixed $offset): void  
* public removeVote (int $key): void  
* public rewind (): void  
* public setElection (CondorcetPHP\Condorcet\Election $election): void  
* public tags ()  
* public valid (): bool  
* public withTags ()  
* protected Pairwise ()  
* protected Pairwise_Model ()  
* protected candidates ()  
* protected clearExplicitPairwiseCache (): void  
* protected computeOneVote (array $pairwise, CondorcetPHP\Condorcet\Vote $oneVote): void  
* protected doPairwise (): void  
* protected explicitPairwise ()  
* protected formatNewpairwise (): void  
* protected getCandidateNameFromKey (int $candidateKey): string  
* protected getVotesManagerGenerator (): Generator  
* protected prepareComparaison (CondorcetPHP\Condorcet\Candidate|string $a, CondorcetPHP\Condorcet\Candidate|string $b): array  
* protected selfElection ()  
```

#### `CondorcetPHP\Condorcet\Algo\Pairwise\Pairwise implements ArrayAccess, Iterator, Traversable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Pairwise/Pairwise.php#L21)

```php
* private bool $valid
* readonly protected array $Pairwise_Model
* protected array $Pairwise
* protected ?array $explicitPairwise
* protected WeakReference $selfElection
* public string $buildByCondorcetVersion

* public __construct (CondorcetPHP\Condorcet\Election $link)  
* public __serialize (): array  
* public addNewVote (int $key): void  
* public buildByCondorcetVersion ()  
* public candidateKeyWinVersus (int $candidateKey, int $opponentKey): bool  
* public candidateWinVersus (CondorcetPHP\Condorcet\Candidate|string $candidate, CondorcetPHP\Condorcet\Candidate|string $opponent): bool  
* public compareCandidates (CondorcetPHP\Condorcet\Candidate|string $a, CondorcetPHP\Condorcet\Candidate|string $b): int  
* public compareCandidatesKeys (int $aKey, int $bKey): int  
* public current (): array  
* public getCondorcetBuilderVersion (bool $major = false): string  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getExplicitPairwise (): array  
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
* protected Pairwise ()  
* protected Pairwise_Model ()  
* protected clearExplicitPairwiseCache (): void  
* protected computeOneVote (array $pairwise, CondorcetPHP\Condorcet\Vote $oneVote): void  
* protected doPairwise (): void  
* protected explicitPairwise ()  
* protected formatNewpairwise (): void  
* protected getCandidateNameFromKey (int $candidateKey): string  
* protected getVotesManagerGenerator (): Generator  
* protected prepareComparaison (CondorcetPHP\Condorcet\Candidate|string $a, CondorcetPHP\Condorcet\Candidate|string $b): array  
* protected selfElection ()  
```

#### `CondorcetPHP\Condorcet\Algo\StatsVerbosity implements UnitEnum, BackedEnum`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/StatsVerbosity.php#L13)

```php
* case StatsVerbosity::NONE  
* case StatsVerbosity::LOW  
* case StatsVerbosity::STD  
* case StatsVerbosity::HIGH  
* case StatsVerbosity::FULL  
* case StatsVerbosity::DEBUG  

* readonly public string $name
* readonly public int $value

* public name ()  
* public value ()  
```

#### `CondorcetPHP\Condorcet\Algo\Stats\BaseMethodStats implements CondorcetPHP\Condorcet\Algo\Stats\StatsInterface, Traversable, IteratorAggregate, ArrayAccess`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Stats/BaseMethodStats.php#L16)

```php
* readonly public string $buildByMethod
* protected array $data
* public array $asArray
* public bool $closed

* public __construct (?array $data = null, bool $closed = true)  
* public asArray ()  
* public buildByMethod ()  
* public close (): static  
* public closed ()  
* public getEntry (string $entryName): mixed  
* public getIterator (): Traversable  
* public offsetExists (mixed $offset): bool  
* public offsetGet (mixed $offset): mixed  
* public offsetSet (mixed $offset, mixed $value): void  
* public offsetUnset (mixed $offset): void  
* public setEntry (string $key, mixed $entry): static  
* public unsetEntry (string $key): static  
* protected data ()  
* protected throwAlreadyBuild (): never  
```

#### `CondorcetPHP\Condorcet\Algo\Stats\EmptyStats implements CondorcetPHP\Condorcet\Algo\Stats\StatsInterface, Traversable, IteratorAggregate, ArrayAccess`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Stats/EmptyStats.php#L14)

```php
* public array $asArray

* public asArray ()  
* public getEntry (string $entryName): never  
* public getIterator (): Traversable  
* public offsetExists (mixed $offset): bool  
* public offsetGet (mixed $offset): never  
* public offsetSet (mixed $offset, mixed $value): void  
* public offsetUnset (mixed $offset): void  
```

#### `CondorcetPHP\Condorcet\Algo\Tools\Combinations `  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Tools/Combinations.php#L20)

```php
* public static bool $useBigIntegerIfAvailable

* public static compute (array $values, int $length, array $append_before = []): SplFixedArray  
* public static computeGenerator (array $values, int $length, array $append_before = []): Generator  
* public static getPossibleCountOfCombinations (int $count, int $length): int  
* public static useBigIntegerIfAvailable ()  
```

#### `Abstract CondorcetPHP\Condorcet\Algo\Tools\PairwiseStats `  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Tools/PairwiseStats.php#L19)

```php
* public static PairwiseComparison (CondorcetPHP\Condorcet\Algo\Pairwise\Pairwise $pairwise): array  
```

#### `CondorcetPHP\Condorcet\Algo\Tools\Permutations `  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Tools/Permutations.php#L21)

```php
* public static bool $useBigIntegerIfAvailable
* readonly public array $candidates

* public static getPossibleCountOfPermutations (int $candidatesNumber): int  
* public static useBigIntegerIfAvailable ()  
* public __construct (array $candidates)  
* public candidates ()  
* public getPermutationGenerator (): Generator  
* public getResults (): SplFixedArray  
* protected permutationGenerator (array $elements): Generator  
```

#### `CondorcetPHP\Condorcet\Algo\Tools\StvQuotas implements UnitEnum, BackedEnum`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Tools/StvQuotas.php#L20)

```php
* case StvQuotas::DROOP  
* case StvQuotas::HARE  
* case StvQuotas::HAGENBACH_BISCHOFF  
* case StvQuotas::IMPERIALI  

* readonly public string $name
* readonly public string $value

* public static fromString (string $quota): self  
* public getQuota (int $votesWeight, int $seats): float  
* public name ()  
* public value ()  
```

#### `Abstract CondorcetPHP\Condorcet\Algo\Tools\TieBreakersCollection `  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Tools/TieBreakersCollection.php#L22)

```php
* public static electSomeLosersbasedOnPairwiseComparaison (CondorcetPHP\Condorcet\Election $election, array $candidatesKeys): array  
* public static tieBreakerWithAnotherMethods (CondorcetPHP\Condorcet\Election $election, array $methods, array $candidatesKeys): array  
```

#### `Abstract CondorcetPHP\Condorcet\Algo\Tools\VirtualVote `  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Tools/VirtualVote.php#L14)

```php
* public static removeCandidates (CondorcetPHP\Condorcet\Vote $vote, array $candidatesList): CondorcetPHP\Condorcet\Vote  
```

#### `CondorcetPHP\Condorcet\Algo\Tools\VotesDeductedApprovals implements Countable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Tools/VotesDeductedApprovals.php#L21)

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
* public subsetSize ()  
* public sumWeightIfVotesIncludeCandidates (array $candidatesKeys): int  
* protected combinationsScore ()  
* protected selfElection ()  
```

#### `CondorcetPHP\Condorcet\Candidate implements Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Candidate.php#L16)

```php
* public string $name
* public array $nameHistory
* public bool $provisionalState
* public float $createdAt
* public float $updatedAt
* private ?WeakMap $link
* public string $buildByCondorcetVersion

* public __clone (): void  
* public __construct (string $name)  
* public __serialize (): array  
* public __toString (): string  
* public buildByCondorcetVersion ()  
* public countLinks (): int  
* public createdAt ()  
* public destroyLink (CondorcetPHP\Condorcet\Election $election): bool  
* public getCondorcetBuilderVersion (bool $major = false): string  
* public getLinks (): array  
* public haveLink (CondorcetPHP\Condorcet\Election $election): bool  
* public name ()  
* public nameHistory ()  
* public provisionalState ()  
* public registerLink (CondorcetPHP\Condorcet\Election $election): void  
* public setName (string $name): static  
* public setProvisionalState (bool $provisional): void  
* public updatedAt ()  
* protected destroyAllLink (): void  
* protected initWeakMap (): void  
* private checkNameInElectionContext (string $name): bool  
* private link ()  
```

#### `Abstract CondorcetPHP\Condorcet\Condorcet `  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Condorcet.php#L50)

```php
* final public const AUTHOR: (string)
* final public const HOMEPAGE: (string)
* final public const VERSION: (string)
* final public const CONDORCET_BASIC_CLASS: (string)

* protected static ?string $defaultMethod
* protected static array $authMethods
* public static bool $UseTimer

* public static UseTimer ()  
* public static addMethod (string $methodClass): bool  
* public static getAuthMethods (bool $basic = false, bool $withNonDeterministicMethods = true): array  
* public static getDefaultMethod (): ?string  
* public static getMethodClass (string $method): ?string  
* public static getVersion (bool $major = false): string  
* public static isAuthMethod (string $method): bool  
* public static setDefaultMethod (string $method): bool  
* public static validateAlternativeWinnerLoserMethod (?string $substitution): string  
* protected static authMethods ()  
* protected static defaultMethod ()  
* protected static testMethod (string $method): bool  
```

#### `CondorcetPHP\Condorcet\Console\Commands\ConvertCommand extends Symfony\Component\Console\Command\Command `  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Console/Commands/ConvertCommand.php#L30)

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

* public static converters ()  
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
* protected election ()  
* protected fromConverter ()  
* protected input ()  
* protected interact (Symfony\Component\Console\Input\InputInterface $input, Symfony\Component\Console\Output\OutputInterface $output)  
* protected output ()  
* protected toConverter ()  
```

#### `CondorcetPHP\Condorcet\Console\Commands\ElectionCommand extends Symfony\Component\Console\Command\Command `  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Console/Commands/ElectionCommand.php#L40)

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

* public static VotesPerMB ()  
* public static forceIniMemoryLimitTo ()  
* public static getDefaultDescription (): ?string  
* public static getDefaultName (): ?string  
* public SQLitePath ()  
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
* protected CondorcetElectionFormatPath ()  
* protected DavidHillFormatPath ()  
* protected DebianFormatPath ()  
* protected candidates ()  
* protected candidatesListIsWrite ()  
* protected configure (): void  
* protected displayCandidatesList (Symfony\Component\Console\Output\OutputInterface $output): void  
* protected displayConfigurationSection (): void  
* protected displayDebugSection (): void  
* protected displayDetailedElectionInputsSection (Symfony\Component\Console\Input\InputInterface $input, Symfony\Component\Console\Output\OutputInterface $output): void  
* protected displayInputsSection (): void  
* protected displayMethodsResultSection (Symfony\Component\Console\Input\InputInterface $input, Symfony\Component\Console\Output\OutputInterface $output): void  
* protected displayMethodsStats ()  
* protected displayNaturalCondorcet (Symfony\Component\Console\Input\InputInterface $input, Symfony\Component\Console\Output\OutputInterface $output): void  
* protected displayPairwiseSection (Symfony\Component\Console\Output\OutputInterface $output): void  
* protected displayTimerSection (): void  
* protected displayVerbose (Symfony\Component\Console\Output\OutputInterface $output): void  
* protected displayVotesCount (Symfony\Component\Console\Output\OutputInterface $output): void  
* protected displayVotesList (Symfony\Component\Console\Output\OutputInterface $output): void  
* protected election ()  
* protected execute (Symfony\Component\Console\Input\InputInterface $input, Symfony\Component\Console\Output\OutputInterface $output): int  
* protected importInputsData (Symfony\Component\Console\Input\InputInterface $input): void  
* protected iniMemoryLimit ()  
* protected initialize (Symfony\Component\Console\Input\InputInterface $input, Symfony\Component\Console\Output\OutputInterface $output): void  
* protected interact (Symfony\Component\Console\Input\InputInterface $input, Symfony\Component\Console\Output\OutputInterface $output): void  
* protected io ()  
* protected maxVotesInMemory ()  
* protected pairwiseIsWrite ()  
* protected parseFromCandidatesArguments (): void  
* protected parseFromCondorcetElectionFormat (?Closure $callBack): void  
* protected parseFromDavidHillFormat (): void  
* protected parseFromDebianFormat (): void  
* protected parseFromVotesArguments (?Closure $callBack): void  
* protected setUpParameters (Symfony\Component\Console\Input\InputInterface $input): void  
* protected terminal ()  
* protected timer ()  
* protected useDataHandler (Symfony\Component\Console\Input\InputInterface $input): ?Closure  
* protected votes ()  
* protected votesCountIsWrite ()  
```

#### `Abstract CondorcetPHP\Condorcet\Console\CondorcetApplication `  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Console/CondorcetApplication.php#L18)

```php
* public static Symfony\Component\Console\Application $SymfonyConsoleApplication

* public static SymfonyConsoleApplication ()  
* public static create (): true  
* public static getVersionWithGitParsing (): string  
* public static run (): void  
```

#### `Abstract CondorcetPHP\Condorcet\Console\Helper\CommandInputHelper `  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Console/Helper/CommandInputHelper.php#L12)

```php
* public static getFilePath (string $path): ?string  
* public static isAbsoluteAndExist (string $path): bool  
* public static pathIsAbsolute (string $path): bool  
```

#### `Abstract CondorcetPHP\Condorcet\Console\Helper\FormaterHelper `  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Console/Helper/FormaterHelper.php#L15)

```php
* public static formatResultTable (CondorcetPHP\Condorcet\Result $result): array  
* public static prepareMethods (array $methodArgument): array  
```

#### `CondorcetPHP\Condorcet\Console\Style\CondorcetStyle extends Symfony\Component\Console\Style\SymfonyStyle implements Symfony\Component\Console\Output\OutputInterface, Symfony\Component\Console\Style\StyleInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Console/Style/CondorcetStyle.php#L19)

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

* public FirstColumnStyle ()  
* public MainTableStyle ()  
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
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Constraints/NoTie.php#L14)

```php
* public static isVoteAllowed (CondorcetPHP\Condorcet\Election $election, CondorcetPHP\Condorcet\Vote $vote): bool  
```

#### `Abstract CondorcetPHP\Condorcet\DataManager\ArrayManager implements ArrayAccess, Countable, Iterator, Traversable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/DataManager/ArrayManager.php#L27)

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
* public string $buildByCondorcetVersion

* public static CacheSize ()  
* public static MaxContainerLength ()  
* public __construct (CondorcetPHP\Condorcet\Election $election)  
* public __destruct ()  
* public __serialize (): array  
* public __unserialize (array $data): void  
* public buildByCondorcetVersion ()  
* public checkRegularize (): bool  
* public clearCache (): void  
* public closeHandler (): void  
* public count (): int  
* public current (): mixed  
* public debugGetCache (): array  
* public getCacheSize (): int  
* public getCondorcetBuilderVersion (bool $major = false): string  
* public getContainerSize (): int  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getFirstKey (): int  
* public getFullDataSet (): array  
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
* protected Cache ()  
* protected CacheMaxKey ()  
* protected CacheMinKey ()  
* protected Container ()  
* protected DataHandler ()  
* protected containerKeyExist (int $offset): bool  
* protected counter ()  
* protected cursor ()  
* protected dataHandlerKeyExist (int $offset): bool  
* protected decodeManyEntities (array $entities): array  
* protected decodeOneEntity (string $data): CondorcetPHP\Condorcet\Vote  
* protected encodeManyEntities (array $entities): array  
* protected encodeOneEntity (CondorcetPHP\Condorcet\Vote $data): string  
* protected maxKey ()  
* protected populateCache (): void  
* protected preDeletedTask (CondorcetPHP\Condorcet\Vote $object): void  
* protected selfElection ()  
* protected setCursorOnNextKeyInArray (array $array): void  
```

#### `CondorcetPHP\Condorcet\DataManager\DataHandlerDrivers\PdoDriver\PdoHandlerDriver implements CondorcetPHP\Condorcet\DataManager\DataHandlerDrivers\DataHandlerDriverInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/DataManager/DataHandlerDrivers/PdoDriver/PdoHandlerDriver.php#L19)

```php
* public const SEGMENT: (array)

* readonly protected PDO $handler
* protected bool $transaction
* protected bool $queryError
* public static bool $preferBlobInsteadVarchar
* protected array $struct
* protected array $prepare
* public string $buildByCondorcetVersion

* public static preferBlobInsteadVarchar ()  
* public __construct (PDO $bdd, bool $tryCreateTable = false, array $struct = [Entities,id,data])  
* public buildByCondorcetVersion ()  
* public closeTransaction (): void  
* public countEntities (): int  
* public createTable (): void  
* public deleteOneEntity (int $key, bool $justTry): ?int  
* public getCondorcetBuilderVersion (bool $major = false): string  
* public insertEntities (array $input): void  
* public selectMaxKey (): ?int  
* public selectMinKey (): int  
* public selectOneEntity (int $key): string|bool  
* public selectRangeEntities (int $key, int $limit): array  
* protected checkStructureTemplate (array $struct): bool  
* protected handler ()  
* protected initPrepareQuery (): void  
* protected initTransaction (): void  
* protected prepare ()  
* protected queryError ()  
* protected sliceInput (array $input): void  
* protected struct ()  
* protected transaction ()  
```

#### `CondorcetPHP\Condorcet\DataManager\DataHandlerDrivers\WoollyDriver\WoollyDriver implements CondorcetPHP\Condorcet\DataManager\DataHandlerDrivers\DataHandlerDriverInterface`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/DataManager/DataHandlerDrivers/WoollyDriver/WoollyDriver.php#L19)

```php
* public string $voteColumnName
* readonly public MammothPHP\WoollyM\DataFrame $df

* public __construct (MammothPHP\WoollyM\DataFrame $df = new MammothPHP\WoollyM\DataFrame)  
* public countEntities (): int  
* public deleteOneEntity (int $key, bool $justTry): ?int  
* public df ()  
* public insertEntities (array $input): void  
* public selectMaxKey (): ?int  
* public selectMinKey (): ?int  
* public selectOneEntity (int $key): string|bool  
* public selectRangeEntities (int $key, int $limit): array  
* public voteColumnName ()  
```

#### `CondorcetPHP\Condorcet\DataManager\VotesManager extends CondorcetPHP\Condorcet\DataManager\ArrayManager implements Traversable, Iterator, Countable, ArrayAccess`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/DataManager/VotesManager.php#L19)

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
* public string $buildByCondorcetVersion

* public static CacheSize ()  
* public static MaxContainerLength ()  
* public UpdateAndResetComputing (int $key, CondorcetPHP\Condorcet\DataManager\VotesManagerEvent $type): void  
* public __construct (CondorcetPHP\Condorcet\Election $election)  
* public __destruct ()  
* public __serialize (): array  
* public __unserialize (array $data): void  
* public buildByCondorcetVersion ()  
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
* public getCondorcetBuilderVersion (bool $major = false): string  
* public getContainerSize (): int  
* public getElection (): ?CondorcetPHP\Condorcet\Election  
* public getFirstKey (): int  
* public getFullDataSet (): array  
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
* protected Cache ()  
* protected CacheMaxKey ()  
* protected CacheMinKey ()  
* protected Container ()  
* protected DataHandler ()  
* protected containerKeyExist (int $offset): bool  
* protected counter ()  
* protected cursor ()  
* protected dataHandlerKeyExist (int $offset): bool  
* protected decodeManyEntities (array $entities): array  
* protected decodeOneEntity (string $data): CondorcetPHP\Condorcet\Vote  
* protected encodeManyEntities (array $entities): array  
* protected encodeOneEntity (CondorcetPHP\Condorcet\Vote $data): string  
* protected getFullVotesListGenerator (): Generator  
* protected getPartialVotesListGenerator (array $tags, int|bool $with): Generator  
* protected maxKey ()  
* protected populateCache (): void  
* protected preDeletedTask (CondorcetPHP\Condorcet\Vote $object): void  
* protected processsumVoteWeights (?array $tags, int|bool $with, bool $constraints): int  
* protected selfElection ()  
* protected setCursorOnNextKeyInArray (array $array): void  
```

#### `CondorcetPHP\Condorcet\DataManager\VotesManagerEvent implements UnitEnum`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/DataManager/VotesManagerEvent.php#L12)

```php
* case VotesManagerEvent::NewVote  
* case VotesManagerEvent::RemoveVote  
* case VotesManagerEvent::VoteUpdateInProgress  
* case VotesManagerEvent::FinishUpdateVote  

* readonly public string $name

* public name ()  
```

#### `CondorcetPHP\Condorcet\Election `  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Election.php#L20)

```php
* public const MAX_CANDIDATE_NAME_LENGTH: (integer)

* public static ?int $maxParseIteration
* public static ?int $maxVotePerElection
* protected static bool $checksumMode
* public CondorcetPHP\Condorcet\ElectionProcess\ElectionState $state
* readonly protected CondorcetPHP\Condorcet\Timer\Manager $timer
* public bool $authorizeVoteWeight
* public int $seatsToElect
* public array $votesConstraints
* public string $hash
* public bool $implicitRankingRule
* public string $buildByCondorcetVersion
* public array $candidates
* public string $nextAutomaticCandidateName
* readonly protected CondorcetPHP\Condorcet\DataManager\VotesManager $Votes
* protected CondorcetPHP\Condorcet\ElectionProcess\VotesFastMode $votesFastMode
* protected ?CondorcetPHP\Condorcet\Algo\Pairwise\Pairwise $Pairwise
* protected ?array $MethodsComputation
* public CondorcetPHP\Condorcet\Algo\StatsVerbosity $statsVerbosity

* public static maxParseIteration ()  
* public static maxVotePerElection ()  
* protected static checksumMode ()  
* protected static formatResultOptions (array $arg): array  
* public __clone (): void  
* public __construct ()  
* public __serialize (): array  
* public __unserialize (array $data): void  
* public addCandidate (CondorcetPHP\Condorcet\Candidate|string|null $candidate = null): CondorcetPHP\Condorcet\Candidate  
* public addCandidatesFromJson (string $input): array  
* public addConstraint (string $constraintClass): static  
* public addVote (CondorcetPHP\Condorcet\Vote|array|string $vote, array|string|null $tags = null): CondorcetPHP\Condorcet\Vote  
* public addVotesFromJson (string $input): int  
* public authorizeVoteWeight (bool $authorized = true): static  
* public beginVoteUpdate (CondorcetPHP\Condorcet\Vote $existVote): void  
* public buildByCondorcetVersion ()  
* public canAddCandidate (CondorcetPHP\Condorcet\Candidate|string $candidate): bool  
* public candidates ()  
* public checkVoteCandidate (CondorcetPHP\Condorcet\Vote $vote): bool  
* public clearConstraints (): static  
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
* public getCondorcetBuilderVersion (bool $major = false): string  
* public getCondorcetLoser (): ?CondorcetPHP\Condorcet\Candidate  
* public getCondorcetWinner (): ?CondorcetPHP\Condorcet\Candidate  
* public getConstraints (): array  
* public getExplicitFilteredPairwiseByTags (array|string $tags, int|bool $with = 1): array  
* public getExplicitPairwise (): array  
* public getFilteredPairwiseByTags (array|string $tags, int|bool $with = true): CondorcetPHP\Condorcet\Algo\Pairwise\FilteredPairwise  
* public getGlobalTimer (): float  
* public getLastTimer (): ?float  
* public getLoser (?string $method = null): CondorcetPHP\Condorcet\Candidate|array|null  
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
* public hash ()  
* public implicitRankingRule (bool $rule): static  
* public isVoteValidUnderConstraints (CondorcetPHP\Condorcet\Vote $vote): bool  
* public nextAutomaticCandidateName ()  
* public parseCandidates (string $input, bool $isFile = false): array  
* public parseVotes (string $input, bool $isFile = false): int  
* public parseVotesSafe (SplFileInfo|string $input, bool $isFile = false, ?Closure $callBack = null): int  
* public removeAllVotes (): true  
* public removeCandidates (CondorcetPHP\Condorcet\Candidate|array|string $candidates_input): array  
* public removeExternalDataHandler (): bool  
* public removeVote (CondorcetPHP\Condorcet\Vote $vote): true  
* public removeVotesByTags (array|string $tags, bool $with = true): array  
* public resetMethodsComputation (): void  
* public seatsToElect ()  
* public setExternalDataHandler (CondorcetPHP\Condorcet\DataManager\DataHandlerDrivers\DataHandlerDriverInterface $driver): static  
* public setMethodOption (string $method, string $optionName, BackedEnum|Random\Randomizer|array|string|int|float $optionValue): static  
* public setSeatsToElect (int $seats): static  
* public setStateToVote (): true  
* public setStatsVerbosity (CondorcetPHP\Condorcet\Algo\StatsVerbosity $StatsVerbosity): static  
* public state ()  
* public statsVerbosity ()  
* public sumValidVoteWeightsWithConstraints (array|string|null $tags = null, int|bool $with = true): int  
* public sumVoteWeights (array|string|null $tags = null, int|bool $with = true): int  
* public votesConstraints ()  
* protected MethodsComputation ()  
* protected Pairwise ()  
* protected Votes ()  
* protected aggregateVotesFromParse (int $count, int $multiple, array $adding, CondorcetPHP\Condorcet\Vote|array|string $vote, array|string|null $tags, int $weight): void  
* protected bulkAddVotes (array $adding): void  
* protected computePairwise (): void  
* protected initResult (string $class): void  
* protected normalizeVoteInput (CondorcetPHP\Condorcet\Vote|array|string $vote, array|string|null $tags = null): CondorcetPHP\Condorcet\Vote  
* protected preparePairwiseAndCleanCompute (): bool  
* protected registerAllLinks (): void  
* protected registerVote (CondorcetPHP\Condorcet\Vote $vote, array|string|null $tags): CondorcetPHP\Condorcet\Vote  
* protected resetComputation (): void  
* protected timer ()  
* protected votesFastMode ()  
```

#### `CondorcetPHP\Condorcet\ElectionProcess\ElectionState implements UnitEnum, BackedEnum`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/ElectionProcess/ElectionState.php#L15)

```php
* case ElectionState::CANDIDATES_REGISTRATION  
* case ElectionState::VOTES_REGISTRATION  

* readonly public string $name
* readonly public int $value

* public name ()  
* public value ()  
```

#### `CondorcetPHP\Condorcet\ElectionProcess\VotesFastMode implements UnitEnum`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/ElectionProcess/VotesFastMode.php#L15)

```php
* case VotesFastMode::NONE  
* case VotesFastMode::BYPASS_CANDIDATES_CHECK  
* case VotesFastMode::BYPASS_RANKING_UPDATE  

* readonly public string $name

* public name ()  
```

#### `CondorcetPHP\Condorcet\Result implements ArrayAccess, Countable, Iterator, Traversable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Result.php#L22)

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
* public CondorcetPHP\Condorcet\Candidate|array|null $Winner
* public CondorcetPHP\Condorcet\Candidate|array|null $Loser
* readonly public float $buildTimestamp
* readonly public string $electionCondorcetVersion
* public bool $isProportional
* public string $buildByCondorcetVersion

* public CondorcetLoser ()  
* public CondorcetWinner ()  
* public Loser ()  
* public Winner ()  
* public __construct (string $fromMethod, string $byClass, CondorcetPHP\Condorcet\Election $election, array $rawRanking, CondorcetPHP\Condorcet\Algo\Stats\StatsInterface $stats, ?int $seats = null, array $methodOptions = [])  
* public addWarning (int $type, ?string $msg = null): true  
* public buildByCondorcetVersion ()  
* public buildTimestamp ()  
* public byClass ()  
* public count (): int  
* public current (): CondorcetPHP\Condorcet\Candidate|array  
* public electionCondorcetVersion ()  
* public fromMethod ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* public getWarning (?int $type = null): array  
* public isProportional ()  
* public key (): int  
* public methodOptions ()  
* public next (): void  
* public offsetExists (mixed $offset): bool  
* public offsetGet (mixed $offset): CondorcetPHP\Condorcet\Candidate|array|null  
* public offsetSet (mixed $offset, mixed $value): void  
* public offsetUnset (mixed $offset): void  
* public originalRankingAsArrayString ()  
* public originalRankingAsString ()  
* public pairwise ()  
* public ranking ()  
* public rankingAsArray ()  
* public rankingAsArrayString ()  
* public rankingAsString ()  
* public rawRanking ()  
* public rewind (): void  
* public seats ()  
* public stats ()  
* public statsVerbosity ()  
* public valid (): bool  
* protected ResultIterator ()  
* protected makeUserRanking (CondorcetPHP\Condorcet\Election $election): array  
* protected warning ()  
```

#### `CondorcetPHP\Condorcet\Throwable\AlgorithmWithoutRankingFeatureException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/AlgorithmWithoutRankingFeatureException.php#L12)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* public string $buildByCondorcetVersion

* public __construct (string|int|null $message = null)  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* protected code ()  
* protected file ()  
* protected line ()  
* protected message ()  
```

#### `CondorcetPHP\Condorcet\Throwable\CandidateDoesNotExistException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/CandidateDoesNotExistException.php#L12)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* public string $buildByCondorcetVersion

* public __construct (string|int|null $message = null)  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* protected code ()  
* protected file ()  
* protected line ()  
* protected message ()  
```

#### `CondorcetPHP\Condorcet\Throwable\CandidateExistsException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/CandidateExistsException.php#L12)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* public string $buildByCondorcetVersion

* public __construct (string|int|null $message = null)  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* protected code ()  
* protected file ()  
* protected line ()  
* protected message ()  
```

#### `CondorcetPHP\Condorcet\Throwable\CandidateInvalidNameException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/CandidateInvalidNameException.php#L12)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* public string $buildByCondorcetVersion

* public __construct (string|int|null $message = null)  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* protected code ()  
* protected file ()  
* protected line ()  
* protected message ()  
```

#### `CondorcetPHP\Condorcet\Throwable\CandidatesMaxNumberReachedException extends CondorcetPHP\Condorcet\Throwable\MethodLimitReachedException implements Stringable, Throwable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/CandidatesMaxNumberReachedException.php#L12)

```php
* protected  $message
* readonly public string $method
* protected  $code
* protected string $file
* protected int $line
* public string $buildByCondorcetVersion

* public __construct (string $method, int $maxCandidates)  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* public method ()  
* protected code ()  
* protected file ()  
* protected line ()  
* protected message ()  
```

#### `Abstract CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException extends Exception implements Stringable, Throwable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/CondorcetPublicApiException.php#L15)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* public string $buildByCondorcetVersion

* public __construct (string|int|null $message = null)  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* protected code ()  
* protected file ()  
* protected line ()  
* protected message ()  
```

#### `CondorcetPHP\Condorcet\Throwable\ConsoleInputException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/ConsoleInputException.php#L12)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* public string $buildByCondorcetVersion

* public __construct (string|int|null $message = null)  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* protected code ()  
* protected file ()  
* protected line ()  
* protected message ()  
```

#### `CondorcetPHP\Condorcet\Throwable\DataHandlerException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/DataHandlerException.php#L12)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* public string $buildByCondorcetVersion

* public __construct (string|int|null $message = null)  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* protected code ()  
* protected file ()  
* protected line ()  
* protected message ()  
```

#### `CondorcetPHP\Condorcet\Throwable\ElectionFileFormatParseException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/ElectionFileFormatParseException.php#L12)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* public string $buildByCondorcetVersion

* public __construct (string|int|null $message = null)  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* protected code ()  
* protected file ()  
* protected line ()  
* protected message ()  
```

#### `CondorcetPHP\Condorcet\Throwable\ElectionObjectVersionMismatchException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/ElectionObjectVersionMismatchException.php#L14)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* public string $buildByCondorcetVersion

* public __construct (string $message = )  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* protected code ()  
* protected file ()  
* protected line ()  
* protected message ()  
```

#### `CondorcetPHP\Condorcet\Throwable\FileDoesNotExistException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/FileDoesNotExistException.php#L12)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* public string $buildByCondorcetVersion

* public __construct (string|int|null $message = null)  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* protected code ()  
* protected file ()  
* protected line ()  
* protected message ()  
```

#### `CondorcetPHP\Condorcet\Throwable\Internal\AlreadyLinkedException extends CondorcetPHP\Condorcet\Throwable\Internal\CondorcetInternalException implements Stringable, Throwable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/Internal/AlreadyLinkedException.php#L13)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line

* protected code ()  
* protected file ()  
* protected line ()  
* protected message ()  
```

#### `CondorcetPHP\Condorcet\Throwable\Internal\CondorcetInternalError extends Error implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/Internal/CondorcetInternalError.php#L15)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* public string $buildByCondorcetVersion

* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* protected code ()  
* protected file ()  
* protected line ()  
* protected message ()  
```

#### `CondorcetPHP\Condorcet\Throwable\Internal\CondorcetInternalException extends Exception implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/Internal/CondorcetInternalException.php#L13)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line

* protected code ()  
* protected file ()  
* protected line ()  
* protected message ()  
```

#### `CondorcetPHP\Condorcet\Throwable\Internal\IntegerOverflowException extends CondorcetPHP\Condorcet\Throwable\Internal\CondorcetInternalException implements Stringable, Throwable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/Internal/IntegerOverflowException.php#L13)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line

* protected code ()  
* protected file ()  
* protected line ()  
* protected message ()  
```

#### `CondorcetPHP\Condorcet\Throwable\Internal\NoGitShellException extends CondorcetPHP\Condorcet\Throwable\Internal\CondorcetInternalException implements Stringable, Throwable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/Internal/NoGitShellException.php#L12)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line

* protected code ()  
* protected file ()  
* protected line ()  
* protected message ()  
```

#### `CondorcetPHP\Condorcet\Throwable\MethodLimitReachedException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/MethodLimitReachedException.php#L12)

```php
* protected  $message
* readonly public string $method
* protected  $code
* protected string $file
* protected int $line
* public string $buildByCondorcetVersion

* public __construct (string $method, ?string $message = null)  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* public method ()  
* protected code ()  
* protected file ()  
* protected line ()  
* protected message ()  
```

#### `CondorcetPHP\Condorcet\Throwable\NoCandidatesException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/NoCandidatesException.php#L12)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* public string $buildByCondorcetVersion

* public __construct (string|int|null $message = null)  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* protected code ()  
* protected file ()  
* protected line ()  
* protected message ()  
```

#### `CondorcetPHP\Condorcet\Throwable\NoSeatsException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/NoSeatsException.php#L12)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* public string $buildByCondorcetVersion

* public __construct (string|int|null $message = null)  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* protected code ()  
* protected file ()  
* protected line ()  
* protected message ()  
```

#### `CondorcetPHP\Condorcet\Throwable\ParseVotesMaxNumberReachedException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/ParseVotesMaxNumberReachedException.php#L12)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* public string $buildByCondorcetVersion

* public __construct (string|int|null $message = null)  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* protected code ()  
* protected file ()  
* protected line ()  
* protected message ()  
```

#### `CondorcetPHP\Condorcet\Throwable\ResultException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/ResultException.php#L12)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* public string $buildByCondorcetVersion

* public __construct (string|int|null $message = null)  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* protected code ()  
* protected file ()  
* protected line ()  
* protected message ()  
```

#### `CondorcetPHP\Condorcet\Throwable\ResultRequestedWithoutVotesException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/ResultRequestedWithoutVotesException.php#L12)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* public string $buildByCondorcetVersion

* public __construct (string|int|null $message = null)  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* protected code ()  
* protected file ()  
* protected line ()  
* protected message ()  
```

#### `CondorcetPHP\Condorcet\Throwable\StatsEntryDoNotExistException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/StatsEntryDoNotExistException.php#L12)

```php
* readonly public string $entryName
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* public string $buildByCondorcetVersion

* public __construct (string $entryName)  
* public buildByCondorcetVersion ()  
* public entryName ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* protected code ()  
* protected file ()  
* protected line ()  
* protected message ()  
```

#### `CondorcetPHP\Condorcet\Throwable\StvQuotaNotImplementedException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/StvQuotaNotImplementedException.php#L12)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* public string $buildByCondorcetVersion

* public __construct (string|int|null $message = null)  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* protected code ()  
* protected file ()  
* protected line ()  
* protected message ()  
```

#### `CondorcetPHP\Condorcet\Throwable\TagsFilterException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/TagsFilterException.php#L12)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* public string $buildByCondorcetVersion

* public __construct (string|int|null $message = null)  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* protected code ()  
* protected file ()  
* protected line ()  
* protected message ()  
```

#### `CondorcetPHP\Condorcet\Throwable\TimerException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/TimerException.php#L12)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* public string $buildByCondorcetVersion

* public __construct (string|int|null $message = null)  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* protected code ()  
* protected file ()  
* protected line ()  
* protected message ()  
```

#### `CondorcetPHP\Condorcet\Throwable\VoteConstraintException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/VoteConstraintException.php#L12)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* public string $buildByCondorcetVersion

* public __construct (string|int|null $message = null)  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* protected code ()  
* protected file ()  
* protected line ()  
* protected message ()  
```

#### `CondorcetPHP\Condorcet\Throwable\VoteException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/VoteException.php#L12)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* public string $buildByCondorcetVersion

* public __construct (string|int|null $message = null)  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* protected code ()  
* protected file ()  
* protected line ()  
* protected message ()  
```

#### `CondorcetPHP\Condorcet\Throwable\VoteInvalidFormatException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/VoteInvalidFormatException.php#L12)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* public string $buildByCondorcetVersion

* public __construct (string|int|null $message = null)  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* protected code ()  
* protected file ()  
* protected line ()  
* protected message ()  
```

#### `CondorcetPHP\Condorcet\Throwable\VoteManagerException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/VoteManagerException.php#L12)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* public string $buildByCondorcetVersion

* public __construct (string|int|null $message = null)  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* protected code ()  
* protected file ()  
* protected line ()  
* protected message ()  
```

#### `CondorcetPHP\Condorcet\Throwable\VoteMaxNumberReachedException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/VoteMaxNumberReachedException.php#L12)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* public string $buildByCondorcetVersion

* public __construct (string|int|null $message = null)  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* protected code ()  
* protected file ()  
* protected line ()  
* protected message ()  
```

#### `CondorcetPHP\Condorcet\Throwable\VoteNotLinkedException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/VoteNotLinkedException.php#L12)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* public string $buildByCondorcetVersion

* public __construct (string|int|null $message = null)  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* protected code ()  
* protected file ()  
* protected line ()  
* protected message ()  
```

#### `CondorcetPHP\Condorcet\Throwable\VotingHasStartedException extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/VotingHasStartedException.php#L12)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* public string $buildByCondorcetVersion

* public __construct (string|int|null $message = null)  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* protected code ()  
* protected file ()  
* protected line ()  
* protected message ()  
```

#### `CondorcetPHP\Condorcet\Throwable\VotingMethodIsNotImplemented extends CondorcetPHP\Condorcet\Throwable\CondorcetPublicApiException implements Throwable, Stringable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Throwable/VotingMethodIsNotImplemented.php#L12)

```php
* protected  $message
* protected  $code
* protected string $file
* protected int $line
* public string $buildByCondorcetVersion

* public __construct (string|int|null $message = null)  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* protected code ()  
* protected file ()  
* protected line ()  
* protected message ()  
```

#### `CondorcetPHP\Condorcet\Timer\Chrono `  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Timer/Chrono.php#L14)

```php
* readonly protected CondorcetPHP\Condorcet\Timer\Manager $manager
* readonly public float $start
* protected ?string $role
* public string $buildByCondorcetVersion

* public __construct (CondorcetPHP\Condorcet\Timer\Manager $timer, ?string $role = null)  
* public __destruct ()  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* public getRole (): ?string  
* public getStart (): float  
* public getTimerManager (): CondorcetPHP\Condorcet\Timer\Manager  
* public setRole (?string $role): void  
* public start ()  
* protected manager ()  
* protected managerStartDeclare (): void  
* protected role ()  
```

#### `CondorcetPHP\Condorcet\Timer\Manager `  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Timer/Manager.php#L16)

```php
* protected float $globalTimer
* protected ?float $lastTimer
* protected ?float $lastChronoTimestamp
* protected ?float $startDeclare
* protected array $history
* public string $buildByCondorcetVersion

* public addTime (CondorcetPHP\Condorcet\Timer\Chrono $chrono): void  
* public buildByCondorcetVersion ()  
* public getCondorcetBuilderVersion (bool $major = false): string  
* public getGlobalTimer (): float  
* public getHistory (): array  
* public getLastTimer (): ?float  
* public startDeclare (CondorcetPHP\Condorcet\Timer\Chrono $chrono): static  
* protected globalTimer ()  
* protected history ()  
* protected lastChronoTimestamp ()  
* protected lastTimer ()  
```

#### `CondorcetPHP\Condorcet\Tools\Converters\CEF\CondorcetElectionFormat implements CondorcetPHP\Condorcet\Tools\Converters\Interface\ConverterExport, CondorcetPHP\Condorcet\Tools\Converters\Interface\ConverterImport`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Tools/Converters/CEF/CondorcetElectionFormat.php#L17)

```php
* public const SPECIAL_KEYWORD_EMPTY_RANKING: (string)

* protected SplFileObject $file
* readonly public array $parameters
* readonly public array $candidates
* readonly public int $seatsToElect
* readonly public bool $implicitRanking
* readonly public bool $voteWeight
* readonly public bool $CandidatesParsedFromVotes
* readonly public int $invalidBlocksCount

* public static boolParser (string $parse): bool  
* public static createFromElection (CondorcetPHP\Condorcet\Election $election, bool $aggregateVotes = true, bool $includeSeatsToElect = true, bool $includeTags = true, bool $inContext = false, ?SplFileObject $file = null): ?string  
* public static createFromString (string $input): self  
* public CandidatesParsedFromVotes ()  
* public __construct (SplFileInfo|string $input)  
* public candidates ()  
* public implicitRanking ()  
* public invalidBlocksCount ()  
* public parameters ()  
* public seatsToElect ()  
* public setDataToAnElection (CondorcetPHP\Condorcet\Election $election = new CondorcetPHP\Condorcet\Election, ?Closure $callBack = null): CondorcetPHP\Condorcet\Election  
* public voteWeight ()  
* protected addCandidates (array $candidates): void  
* protected file ()  
* protected interpretStandardParameters (): void  
* protected parseCandidatesFromVotes (): void  
* protected readParameters (): void  
```

#### `CondorcetPHP\Condorcet\Tools\Converters\CEF\StandardParameter implements UnitEnum, BackedEnum`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Tools/Converters/CEF/StandardParameter.php#L15)

```php
* case StandardParameter::CANDIDATES  
* case StandardParameter::SEATS  
* case StandardParameter::IMPLICIT  
* case StandardParameter::WEIGHT  

* readonly public string $name
* readonly public string $value

* public formatValue (string $parameterValue): mixed  
* public name ()  
* public value ()  
```

#### `CondorcetPHP\Condorcet\Tools\Converters\CivsFormat implements CondorcetPHP\Condorcet\Tools\Converters\Interface\ConverterExport`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Tools/Converters/CivsFormat.php#L15)

```php
* public static createFromElection (CondorcetPHP\Condorcet\Election $election, ?SplFileObject $file = null): string|true  
```

#### `CondorcetPHP\Condorcet\Tools\Converters\DavidHillFormat implements CondorcetPHP\Condorcet\Tools\Converters\Interface\ConverterImport`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Tools/Converters/DavidHillFormat.php#L15)

```php
* protected array $lines
* readonly public array $candidates
* readonly public int $seatsToElect

* public __construct (string $filePath)  
* public candidates ()  
* public seatsToElect ()  
* public setDataToAnElection (?CondorcetPHP\Condorcet\Election $election = null): CondorcetPHP\Condorcet\Election  
* protected lines ()  
* protected parseSeatsToElect (): void  
* protected readCandidatesNames (): void  
* protected readVotes (): void  
```

#### `CondorcetPHP\Condorcet\Tools\Converters\DebianFormat implements CondorcetPHP\Condorcet\Tools\Converters\Interface\ConverterImport`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Tools/Converters/DebianFormat.php#L16)

```php
* protected array $lines
* readonly public array $candidates
* readonly public array $votes

* public __construct (string $filePath)  
* public candidates ()  
* public setDataToAnElection (?CondorcetPHP\Condorcet\Election $election = null): CondorcetPHP\Condorcet\Election  
* public votes ()  
* protected lines ()  
* protected readCandidatesNames (): void  
* protected readVotes (): void  
```

#### `CondorcetPHP\Condorcet\Tools\Randomizers\ArrayRandomizer `  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Tools/Randomizers/ArrayRandomizer.php#L14)

```php
* protected Random\Randomizer $randomizer
* public array $candidates
* public ?int $maxCandidatesRanked
* public int|false $minCandidatesRanked
* public ?int $maxRanksCount
* public int|float $tiesProbability

* public __construct (array $candidates, Random\Randomizer|string|null $seed = null)  
* public candidates ()  
* public countCandidates (): int  
* public maxCandidatesRanked ()  
* public maxRanksCount ()  
* public minCandidatesRanked ()  
* public setCandidates (array $candidates): void  
* public shuffle (): array  
* public tiesProbability ()  
* protected makeTies (array $randomizedCandidates): array  
* protected randomizer ()  
```

#### `CondorcetPHP\Condorcet\Tools\Randomizers\VoteRandomizer extends CondorcetPHP\Condorcet\Tools\Randomizers\ArrayRandomizer `  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Tools/Randomizers/VoteRandomizer.php#L14)

```php
* protected Random\Randomizer $randomizer
* public array $candidates
* public ?int $maxCandidatesRanked
* public int|false $minCandidatesRanked
* public ?int $maxRanksCount
* public int|float $tiesProbability

* public __construct (array $candidates, Random\Randomizer|string|null $seed = null)  
* public candidates ()  
* public countCandidates (): int  
* public getNewVote (): CondorcetPHP\Condorcet\Vote  
* public maxCandidatesRanked ()  
* public maxRanksCount ()  
* public minCandidatesRanked ()  
* public setCandidates (array $candidates): void  
* public shuffle (): array  
* public tiesProbability ()  
* protected makeTies (array $randomizedCandidates): array  
* protected randomizer ()  
```

#### `Abstract CondorcetPHP\Condorcet\Utils\CondorcetUtil `  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Utils/CondorcetUtil.php#L15)

```php
* public static format (mixed $input, bool $convertObject = true): mixed  
* public static isValidJsonForCondorcet (string $string): void  
* public static prepareJson (string $input): mixed  
* public static prepareParse (string $input, bool $isFile): array  
```

#### `CondorcetPHP\Condorcet\Utils\VoteEntryParser `  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Utils/VoteEntryParser.php#L17)

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
* public comment ()  
* public multiple ()  
* public originalEntry ()  
* public ranking ()  
* public tags ()  
* public weight ()  
```

#### `Abstract CondorcetPHP\Condorcet\Utils\VoteUtil `  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Utils/VoteUtil.php#L17)

```php
* public static convertRankingFromCandidateObjectToInternalKeys (CondorcetPHP\Condorcet\Election $election, array $ranking): void  
* public static getRankingAsString (array $ranking): string  
* public static tagsConvert (array|string|null $tags): ?array  
```

#### `CondorcetPHP\Condorcet\Vote implements ArrayAccess, Iterator, Stringable, Traversable`  
> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Vote.php#L21)

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
* public string $buildByCondorcetVersion

* public static clearCache (): void  
* public static initCache (): stdClass  
* protected static cacheKey ()  
* public __clone (): void  
* public __construct (array|string $ranking, array|string|null $tags = null, ?float $ownTimestamp = null, ?CondorcetPHP\Condorcet\Election $electionContext = null)  
* public __serialize (): array  
* public __toString (): string  
* public __wakeup (): void  
* public addTags (array|string $tags): bool  
* public buildByCondorcetVersion ()  
* public countCandidates ()  
* public countLinks (): int  
* public countRanks (): int  
* public createdAt ()  
* public current (): array  
* public destroyLink (CondorcetPHP\Condorcet\Election $election): bool  
* public getAllCandidates (?CondorcetPHP\Condorcet\Election $context = null): array  
* public getCondorcetBuilderVersion (bool $major = false): string  
* public getContextualRankingWithCandidateKeys (CondorcetPHP\Condorcet\Election $context): array  
* public getContextualRankingWithoutSort (CondorcetPHP\Condorcet\Election $context): array  
* public getLinks (): array  
* public getRanking (?CondorcetPHP\Condorcet\Election $context = null, bool $sortCandidatesInRank = true): array  
* public getRankingAsArrayString (?CondorcetPHP\Condorcet\Election $context = null): array  
* public getRankingAsString (?CondorcetPHP\Condorcet\Election $context = null, bool $displayWeight = true): string  
* public getTagsAsString (): string  
* public getWeight (?CondorcetPHP\Condorcet\Election $context = null): int  
* public hash ()  
* public haveLink (CondorcetPHP\Condorcet\Election $election): bool  
* public key (): int  
* public next (): void  
* public notUpdate ()  
* public offsetExists (mixed $offset): bool  
* public offsetGet (mixed $offset): CondorcetPHP\Condorcet\Candidate|array|null  
* public offsetSet (mixed $offset, mixed $value): void  
* public offsetUnset (mixed $offset): void  
* public rankingHistory ()  
* public registerLink (CondorcetPHP\Condorcet\Election $election): void  
* public removeAllTags (): true  
* public removeCandidate (CondorcetPHP\Condorcet\Candidate|string $candidate): true  
* public removeTags (array|string $tags): array  
* public rewind (): void  
* public setRanking (array|string $ranking, ?float $ownTimestamp = null): static  
* public setWeight (int $newWeight): int  
* public tags ()  
* public updatedAt ()  
* public valid (): bool  
* protected cacheMap ()  
* protected computeContextualRanking (CondorcetPHP\Condorcet\Election $context, bool $sortLastRankByName): array  
* protected computeContextualRankingWithoutImplicit (array $ranking, CondorcetPHP\Condorcet\Election $context, int $countContextualCandidate = 0): array  
* protected destroyAllLink (): void  
* protected initWeakMap (): void  
* private archiveRanking (): void  
* private computeHashCode (): string  
* private electionContext ()  
* private formatRanking (array|string $ranking): array  
* private link ()  
* private position ()  
* private ranking ()  
* private weight ()  
```
