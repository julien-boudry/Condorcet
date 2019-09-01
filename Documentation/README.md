> **[Presentation](../README.md) | [Manual](https://github.com/julien-boudry/Condorcet/wiki) | Methods References | [Tests](../Tests)**

# Public Methods Index _(Not yet exhaustive, not yet....)*_
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

* [public static CondorcetUtil::format (...)](CondorcetUtil%20Class/public%20static%20CondorcetUtil--format.md)  

### CondorcetPHP\Condorcet\DataManager\VotesManager Class  

* [public DataManager\VotesManager->getObjectVersion (...)](DataManager_VotesManager%20Class/public%20DataManager_VotesManager--getObjectVersion.md) : string  

### CondorcetPHP\Condorcet\Election Class  

* [public static Election::setMaxParseIteration (...)](Election%20Class/public%20static%20Election--setMaxParseIteration.md) : ?int  
* [public static Election::setMaxVoteNumber (...)](Election%20Class/public%20static%20Election--setMaxVoteNumber.md) : ?int  
* [public Election->__construct ()](Election%20Class/public%20Election--__construct.md)  
* [public Election->addCandidate (...)](Election%20Class/public%20Election--addCandidate.md) : CondorcetPHP\Condorcet\Candidate  
* [public Election->addCandidatesFromJson (...)](Election%20Class/public%20Election--addCandidatesFromJson.md) : array  
* [public Election->addConstraint (...)](Election%20Class/public%20Election--addConstraint.md) : bool  
* [public Election->addVote (...)](Election%20Class/public%20Election--addVote.md) : CondorcetPHP\Condorcet\Vote  
* [public Election->addVotesFromJson (...)](Election%20Class/public%20Election--addVotesFromJson.md) : int  
* [public Election->allowVoteWeight (...)](Election%20Class/public%20Election--allowVoteWeight.md) : bool  
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
* [public Election->getLoser (...)](Election%20Class/public%20Election--getLoser.md)  
* [public Election->getObjectVersion (...)](Election%20Class/public%20Election--getObjectVersion.md) : string  
* [public Election->getPairwise ()](Election%20Class/public%20Election--getPairwise.md) : CondorcetPHP\Condorcet\Algo\Pairwise  
* [public Election->getResult (...)](Election%20Class/public%20Election--getResult.md) : CondorcetPHP\Condorcet\Result  
* [public Election->getState ()](Election%20Class/public%20Election--getState.md) : int  
* [public Election->getTimerManager ()](Election%20Class/public%20Election--getTimerManager.md) : CondorcetPHP\Condorcet\Timer\Manager  
* [public Election->getVotesList (...)](Election%20Class/public%20Election--getVotesList.md) : array  
* [public Election->getVotesListAsString ()](Election%20Class/public%20Election--getVotesListAsString.md) : string  
* [public Election->getVotesListGenerator (...)](Election%20Class/public%20Election--getVotesListGenerator.md) : Generator  
* [public Election->getWinner (...)](Election%20Class/public%20Election--getWinner.md)  
* [public Election->isRegisteredCandidate (...)](Election%20Class/public%20Election--isRegisteredCandidate.md) : bool  
* [public Election->isVoteWeightAllowed ()](Election%20Class/public%20Election--isVoteWeightAllowed.md) : bool  
* [public Election->parseCandidates (...)](Election%20Class/public%20Election--parseCandidates.md) : array  
* [public Election->parseVotes (...)](Election%20Class/public%20Election--parseVotes.md) : int  
* [public Election->removeCandidates (...)](Election%20Class/public%20Election--removeCandidates.md) : array  
* [public Election->removeExternalDataHandler ()](Election%20Class/public%20Election--removeExternalDataHandler.md) : bool  
* [public Election->removeVote (...)](Election%20Class/public%20Election--removeVote.md) : bool  
* [public Election->removeVotesByTags (...)](Election%20Class/public%20Election--removeVotesByTags.md) : array  
* [public Election->setExternalDataHandler (...)](Election%20Class/public%20Election--setExternalDataHandler.md) : bool  
* [public Election->setImplicitRanking (...)](Election%20Class/public%20Election--setImplicitRanking.md) : bool  
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
* [public Result->getLoser ()](Result%20Class/public%20Result--getLoser.md)  
* [public Result->getMethod ()](Result%20Class/public%20Result--getMethod.md) : string  
* [public Result->getObjectVersion (...)](Result%20Class/public%20Result--getObjectVersion.md) : string  
* [public Result->getOriginalResultArrayWithString ()](Result%20Class/public%20Result--getOriginalResultArrayWithString.md) : array  
* [public Result->getResultAsArray (...)](Result%20Class/public%20Result--getResultAsArray.md) : array  
* [public Result->getResultAsString ()](Result%20Class/public%20Result--getResultAsString.md) : string  
* [public Result->getStats ()](Result%20Class/public%20Result--getStats.md)  
* [public Result->getWarning (...)](Result%20Class/public%20Result--getWarning.md) : array  
* [public Result->getWinner ()](Result%20Class/public%20Result--getWinner.md)  

### CondorcetPHP\Condorcet\Throwable\CondorcetException Class  

* [public Throwable\CondorcetException->getObjectVersion (...)](Throwable_CondorcetException%20Class/public%20Throwable_CondorcetException--getObjectVersion.md) : string  

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
* [public Vote->getHistory ()](Vote%20Class/public%20Vote--getHistory.md) : array  
* [public Vote->getLinks ()](Vote%20Class/public%20Vote--getLinks.md) : ?array  
* [public Vote->getObjectVersion (...)](Vote%20Class/public%20Vote--getObjectVersion.md) : string  
* [public Vote->getRanking ()](Vote%20Class/public%20Vote--getRanking.md) : array  
* [public Vote->getSimpleRanking (...)](Vote%20Class/public%20Vote--getSimpleRanking.md) : string  
* [public Vote->getTags ()](Vote%20Class/public%20Vote--getTags.md) : array  
* [public Vote->getTagsAsString ()](Vote%20Class/public%20Vote--getTagsAsString.md) : string  
* [public Vote->getTimestamp ()](Vote%20Class/public%20Vote--getTimestamp.md) : float  
* [public Vote->getWeight ()](Vote%20Class/public%20Vote--getWeight.md) : int  
* [public Vote->haveLink (...)](Vote%20Class/public%20Vote--haveLink.md) : bool  
* [public Vote->removeAllTags ()](Vote%20Class/public%20Vote--removeAllTags.md) : bool  
* [public Vote->removeCandidate (...)](Vote%20Class/public%20Vote--removeCandidate.md) : bool  
* [public Vote->removeTags (...)](Vote%20Class/public%20Vote--removeTags.md) : array  
* [public Vote->setRanking (...)](Vote%20Class/public%20Vote--setRanking.md) : bool  
* [public Vote->setWeight (...)](Vote%20Class/public%20Vote--setWeight.md) : int  
.  
.  
.  
# Method for internal API use && Non-public methods

#### Abstract CondorcetPHP\Condorcet\Algo\Method   
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public getObjectVersion (bool $major = false) : string  
* public getResult () : CondorcetPHP\Condorcet\Result  
* protected createResult (array $result) : CondorcetPHP\Condorcet\Result  
* protected getStats () : array  

#### CondorcetPHP\Condorcet\Algo\Methods\Borda\BordaCount extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public getObjectVersion (bool $major = false) : string  
* public getResult () : CondorcetPHP\Condorcet\Result  
* protected compute () : void  
* protected createResult (array $result) : CondorcetPHP\Condorcet\Result  
* protected getScoreByCandidateRanking (int $CandidatesRanked) : float  
* protected getStats () : array  

#### CondorcetPHP\Condorcet\Algo\Methods\Borda\DowdallSystem extends CondorcetPHP\Condorcet\Algo\Methods\Borda\BordaCount implements CondorcetPHP\Condorcet\Algo\MethodInterface  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public getObjectVersion (bool $major = false) : string  
* public getResult () : CondorcetPHP\Condorcet\Result  
* protected compute () : void  
* protected createResult (array $result) : CondorcetPHP\Condorcet\Result  
* protected getScoreByCandidateRanking (int $CandidatesRanked) : float  
* protected getStats () : array  

#### CondorcetPHP\Condorcet\Algo\Methods\CondorcetBasic extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public getLoser () : ?int  
* public getObjectVersion (bool $major = false) : string  
* public getResult () : CondorcetPHP\Condorcet\Result  
* public getWinner () : ?int  
* protected createResult (array $result) : CondorcetPHP\Condorcet\Result  
* protected getStats () : array  

#### CondorcetPHP\Condorcet\Algo\Methods\Copeland\Copeland extends CondorcetPHP\Condorcet\Algo\Methods\PairwiseStatsBased_Core implements CondorcetPHP\Condorcet\Algo\MethodInterface  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public getObjectVersion (bool $major = false) : string  
* public getResult () : CondorcetPHP\Condorcet\Result  
* protected createResult (array $result) : CondorcetPHP\Condorcet\Result  
* protected getStats () : array  
* protected looking (array $challenge) : int  
* protected makeRanking () : void  

#### CondorcetPHP\Condorcet\Algo\Methods\Dodgson\DodgsonQuick extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public getObjectVersion (bool $major = false) : string  
* public getResult () : CondorcetPHP\Condorcet\Result  
* protected compute () : void  
* protected createResult (array $result) : CondorcetPHP\Condorcet\Result  
* protected getStats () : array  

#### CondorcetPHP\Condorcet\Algo\Methods\Dodgson\DodgsonTidemanApproximation extends CondorcetPHP\Condorcet\Algo\Methods\PairwiseStatsBased_Core implements CondorcetPHP\Condorcet\Algo\MethodInterface  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public getObjectVersion (bool $major = false) : string  
* public getResult () : CondorcetPHP\Condorcet\Result  
* protected createResult (array $result) : CondorcetPHP\Condorcet\Result  
* protected getStats () : array  
* protected looking (array $challenge) : int  
* protected makeRanking () : void  

#### CondorcetPHP\Condorcet\Algo\Methods\Ftpt\Ftpt extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public getObjectVersion (bool $major = false) : string  
* public getResult () : CondorcetPHP\Condorcet\Result  
* protected compute () : void  
* protected createResult (array $result) : CondorcetPHP\Condorcet\Result  
* protected getStats () : array  

#### CondorcetPHP\Condorcet\Algo\Methods\InstantRunoff\InstantRunoff extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public getObjectVersion (bool $major = false) : string  
* public getResult () : CondorcetPHP\Condorcet\Result  
* protected compute () : void  
* protected createResult (array $result) : CondorcetPHP\Condorcet\Result  
* protected getStats () : array  
* protected makeScore (array $candidateDone) : array  
* protected tieBreaking (array $candidatesKeys) : array  

#### CondorcetPHP\Condorcet\Algo\Methods\KemenyYoung\KemenyYoung extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public getObjectVersion (bool $major = false) : string  
* public getResult () : CondorcetPHP\Condorcet\Result  
* protected calcPossibleRanking () : void  
* protected calcRankingScore () : void  
* protected conflictInfos () : void  
* protected createResult (array $result) : CondorcetPHP\Condorcet\Result  
* protected doPossibleRanking (?string $path = null)  
* protected getStats () : array  
* protected makeRanking () : void  

#### CondorcetPHP\Condorcet\Algo\Methods\Minimax\MinimaxMargin extends CondorcetPHP\Condorcet\Algo\Methods\PairwiseStatsBased_Core implements CondorcetPHP\Condorcet\Algo\MethodInterface  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public getObjectVersion (bool $major = false) : string  
* public getResult () : CondorcetPHP\Condorcet\Result  
* protected createResult (array $result) : CondorcetPHP\Condorcet\Result  
* protected getStats () : array  
* protected looking (array $challenge) : int  
* protected makeRanking () : void  

#### CondorcetPHP\Condorcet\Algo\Methods\Minimax\MinimaxOpposition extends CondorcetPHP\Condorcet\Algo\Methods\PairwiseStatsBased_Core implements CondorcetPHP\Condorcet\Algo\MethodInterface  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public getObjectVersion (bool $major = false) : string  
* public getResult () : CondorcetPHP\Condorcet\Result  
* protected createResult (array $result) : CondorcetPHP\Condorcet\Result  
* protected getStats () : array  
* protected looking (array $challenge) : int  
* protected makeRanking () : void  

#### CondorcetPHP\Condorcet\Algo\Methods\Minimax\MinimaxWinning extends CondorcetPHP\Condorcet\Algo\Methods\PairwiseStatsBased_Core implements CondorcetPHP\Condorcet\Algo\MethodInterface  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public getObjectVersion (bool $major = false) : string  
* public getResult () : CondorcetPHP\Condorcet\Result  
* protected createResult (array $result) : CondorcetPHP\Condorcet\Result  
* protected getStats () : array  
* protected looking (array $challenge) : int  
* protected makeRanking () : void  

#### Abstract CondorcetPHP\Condorcet\Algo\Methods\PairwiseStatsBased_Core extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public getObjectVersion (bool $major = false) : string  
* public getResult () : CondorcetPHP\Condorcet\Result  
* protected createResult (array $result) : CondorcetPHP\Condorcet\Result  
* protected getStats () : array  
* protected looking (array $challenge) : int  
* protected makeRanking () : void  

#### CondorcetPHP\Condorcet\Algo\Methods\RankedPairs\RankedPairsMargin extends CondorcetPHP\Condorcet\Algo\Methods\RankedPairs\RankedPairs_Core implements CondorcetPHP\Condorcet\Algo\MethodInterface  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public getObjectVersion (bool $major = false) : string  
* public getResult () : CondorcetPHP\Condorcet\Result  
* protected createResult (array $result) : CondorcetPHP\Condorcet\Result  
* protected followCycle (array $virtualArcs, int $startCandidateKey, int $searchCandidateKey, array $done = [])  
* protected getArcsInCycle (array $virtualArcs) : array  
* protected getStats () : array  
* protected getWinners (array $alreadyDone) : array  
* protected makeArcs () : void  
* protected makeResult () : array  
* protected pairwiseSort () : array  

#### CondorcetPHP\Condorcet\Algo\Methods\RankedPairs\RankedPairsWinning extends CondorcetPHP\Condorcet\Algo\Methods\RankedPairs\RankedPairs_Core implements CondorcetPHP\Condorcet\Algo\MethodInterface  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public getObjectVersion (bool $major = false) : string  
* public getResult () : CondorcetPHP\Condorcet\Result  
* protected createResult (array $result) : CondorcetPHP\Condorcet\Result  
* protected followCycle (array $virtualArcs, int $startCandidateKey, int $searchCandidateKey, array $done = [])  
* protected getArcsInCycle (array $virtualArcs) : array  
* protected getStats () : array  
* protected getWinners (array $alreadyDone) : array  
* protected makeArcs () : void  
* protected makeResult () : array  
* protected pairwiseSort () : array  

#### CondorcetPHP\Condorcet\Algo\Methods\RankedPairs\RankedPairs_Core extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public getObjectVersion (bool $major = false) : string  
* public getResult () : CondorcetPHP\Condorcet\Result  
* protected createResult (array $result) : CondorcetPHP\Condorcet\Result  
* protected followCycle (array $virtualArcs, int $startCandidateKey, int $searchCandidateKey, array $done = [])  
* protected getArcsInCycle (array $virtualArcs) : array  
* protected getStats () : array  
* protected getWinners (array $alreadyDone) : array  
* protected makeArcs () : void  
* protected makeResult () : array  
* protected pairwiseSort () : array  

#### CondorcetPHP\Condorcet\Algo\Methods\Schulze\SchulzeMargin extends CondorcetPHP\Condorcet\Algo\Methods\Schulze\Schulze_Core implements CondorcetPHP\Condorcet\Algo\MethodInterface  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public getObjectVersion (bool $major = false) : string  
* public getResult () : CondorcetPHP\Condorcet\Result  
* protected createResult (array $result) : CondorcetPHP\Condorcet\Result  
* protected getStats () : array  
* protected makeRanking () : void  
* protected makeStrongestPaths () : void  
* protected prepareStrongestPath () : void  
* protected schulzeVariant (int $i, int $j) : int  

#### CondorcetPHP\Condorcet\Algo\Methods\Schulze\SchulzeRatio extends CondorcetPHP\Condorcet\Algo\Methods\Schulze\Schulze_Core implements CondorcetPHP\Condorcet\Algo\MethodInterface  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public getObjectVersion (bool $major = false) : string  
* public getResult () : CondorcetPHP\Condorcet\Result  
* protected createResult (array $result) : CondorcetPHP\Condorcet\Result  
* protected getStats () : array  
* protected makeRanking () : void  
* protected makeStrongestPaths () : void  
* protected prepareStrongestPath () : void  
* protected schulzeVariant (int $i, int $j) : float  

#### CondorcetPHP\Condorcet\Algo\Methods\Schulze\SchulzeWinning extends CondorcetPHP\Condorcet\Algo\Methods\Schulze\Schulze_Core implements CondorcetPHP\Condorcet\Algo\MethodInterface  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public getObjectVersion (bool $major = false) : string  
* public getResult () : CondorcetPHP\Condorcet\Result  
* protected createResult (array $result) : CondorcetPHP\Condorcet\Result  
* protected getStats () : array  
* protected makeRanking () : void  
* protected makeStrongestPaths () : void  
* protected prepareStrongestPath () : void  
* protected schulzeVariant (int $i, int $j) : int  

#### Abstract CondorcetPHP\Condorcet\Algo\Methods\Schulze\Schulze_Core extends CondorcetPHP\Condorcet\Algo\Method implements CondorcetPHP\Condorcet\Algo\MethodInterface  
* public __construct (CondorcetPHP\Condorcet\Election $mother)  
* public getObjectVersion (bool $major = false) : string  
* public getResult () : CondorcetPHP\Condorcet\Result  
* protected createResult (array $result) : CondorcetPHP\Condorcet\Result  
* protected getStats () : array  
* protected makeRanking () : void  
* protected makeStrongestPaths () : void  
* protected prepareStrongestPath () : void  
* protected schulzeVariant (int $i, int $j)  

#### CondorcetPHP\Condorcet\Algo\Pairwise implements ArrayAccess, Iterator, Traversable  
* public __clone ()  
* public __construct (CondorcetPHP\Condorcet\Election $link)  
* public addNewVote (int $key) : void  
* public current () : array  
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

#### Abstract CondorcetPHP\Condorcet\Algo\Tools\PairwiseStats   
* public static PairwiseComparison (CondorcetPHP\Condorcet\Algo\Pairwise $pairwise) : array  

#### CondorcetPHP\Condorcet\Algo\Tools\Permutation   
* public static countPossiblePermutations (int $candidatesNumber) : int  
* public __construct ($arr)  
* public getResults (bool $serialize = false)  
* public writeResults (string $path) : void  
* protected createCandidates (int $numberOfCandidates) : array  
* private _exec ($a, array $i = []) : void  
* private _permute (array $arr)  

#### CondorcetPHP\Condorcet\Algo\Tools\VirtualVote   
* public static removeCandidates (CondorcetPHP\Condorcet\Vote $vote, array $candidatesList) : CondorcetPHP\Condorcet\Vote  

#### CondorcetPHP\Condorcet\Candidate   
* public __clone ()  
* public __toString () : string  
* public destroyLink (CondorcetPHP\Condorcet\Election $election) : bool  
* public registerLink (CondorcetPHP\Condorcet\Election $election) : void  
* public setProvisionalState (bool $provisional) : bool  
* protected destroyAllLink () : void  
* private checkName (string $name) : bool  

#### Abstract CondorcetPHP\Condorcet\Condorcet   
* public static condorcetBasicSubstitution (?string $substitution) : string  
* protected static testMethod (string $method) : bool  

#### Abstract CondorcetPHP\Condorcet\CondorcetUtil   
* public static isJson (string $string) : bool  
* public static prepareJson (string $input)  
* public static prepareParse (string $input, bool $isFile) : array  

#### CondorcetPHP\Condorcet\Constraints\NoTie extends CondorcetPHP\Condorcet\VoteConstraint   
* public static isVoteAllow (CondorcetPHP\Condorcet\Election $election, CondorcetPHP\Condorcet\Vote $vote) : bool  
* protected static evaluateVote (array $vote) : bool  

#### Abstract CondorcetPHP\Condorcet\DataManager\ArrayManager implements ArrayAccess, Countable, Iterator, Traversable  
* public __clone ()  
* public __construct ()  
* public __destruct ()  
* public __sleep () : array  
* public __wakeup ()  
* public checkRegularize () : bool  
* public clearCache () : void  
* public closeHandler () : void  
* public count () : int  
* public current ()  
* public debugGetCache () : array  
* public getCacheSize () : int  
* public getContainerSize () : int  
* public getDataContextObject () : CondorcetPHP\Condorcet\DataManager\DataContextInterface  
* public getFirstKey () : int  
* public getFullDataSet () : array  
* public getObjectVersion (bool $major = false) : string  
* public importHandler (CondorcetPHP\Condorcet\DataManager\DataHandlerDrivers\DataHandlerDriverInterface $handler) : bool  
* public isUsingHandler ()  
* public key () : ?int  
* public keyExist ($offset) : bool  
* public next () : void  
* public offsetExists ($offset) : bool  
* public offsetGet ($offset)  
* public offsetSet ($offset, $value) : void  
* public offsetUnset ($offset) : void  
* public regularize () : bool  
* public resetCounter () : int  
* public resetMaxKey () : ?int  
* public rewind () : void  
* public valid () : bool  
* protected populateCache () : void  
* protected preDeletedTask ($object) : void  
* protected setCursorOnNextKeyInArray (array $array) : void  

#### CondorcetPHP\Condorcet\DataManager\DataHandlerDrivers\PdoHandlerDriver implements CondorcetPHP\Condorcet\DataManager\DataHandlerDrivers\DataHandlerDriverInterface  
* public __construct (PDO $bdd, bool $tryCreateTable = false, array $struct = [Entitys,id,data])  
* public __destruct ()  
* public closeTransaction () : void  
* public countEntitys () : int  
* public createTable () : void  
* public deleteOneEntity (int $key, bool $justTry) : ?int  
* public getObjectVersion (bool $major = false) : string  
* public insertEntitys (array $input) : void  
* public selectMaxKey () : ?int  
* public selectMinKey () : int  
* public selectOneEntity (int $key)  
* public selectRangeEntitys (int $key, int $limit) : array  
* protected checkStructureTemplate (array $struct) : bool  
* protected initPrepareQuery () : void  
* protected initTransaction () : void  
* protected sliceInput (array $input) : void  

#### CondorcetPHP\Condorcet\DataManager\VotesManager extends CondorcetPHP\Condorcet\DataManager\ArrayManager implements Traversable, Iterator, Countable, ArrayAccess  
* public UpdateAndResetComputing (int $key, int $type) : void  
* public __clone ()  
* public __construct (CondorcetPHP\Condorcet\Election $election)  
* public __destruct ()  
* public __sleep () : array  
* public __wakeup ()  
* public checkRegularize () : bool  
* public clearCache () : void  
* public closeHandler () : void  
* public count () : int  
* public countInvalidVoteWithConstraints () : int  
* public countVotes (?array $tag, bool $with) : int  
* public current ()  
* public debugGetCache () : array  
* public getCacheSize () : int  
* public getContainerSize () : int  
* public getDataContextObject () : CondorcetPHP\Condorcet\DataManager\DataContextInterface  
* public getElection () : CondorcetPHP\Condorcet\Election  
* public getFirstKey () : int  
* public getFullDataSet () : array  
* public getVoteKey (CondorcetPHP\Condorcet\Vote $vote) : ?int  
* public getVotesList ($tag = null, bool $with = true) : array  
* public getVotesListAsString () : string  
* public getVotesListGenerator ($tag = null, bool $with = true) : Generator  
* public getVotesValidUnderConstraintGenerator () : Generator  
* public importHandler (CondorcetPHP\Condorcet\DataManager\DataHandlerDrivers\DataHandlerDriverInterface $handler) : bool  
* public isUsingHandler ()  
* public key () : ?int  
* public keyExist ($offset) : bool  
* public next () : void  
* public offsetExists ($offset) : bool  
* public offsetGet ($offset)  
* public offsetSet ($offset, $value) : void  
* public offsetUnset ($offset) : void  
* public regularize () : bool  
* public resetCounter () : int  
* public resetMaxKey () : ?int  
* public rewind () : void  
* public setElection (CondorcetPHP\Condorcet\Election $election) : void  
* public sumVotesWeight (bool $constraint = false) : int  
* public valid () : bool  
* protected getFullVotesListGenerator () : Generator  
* protected getPartialVotesListGenerator (array $tag, bool $with) : Generator  
* protected populateCache () : void  
* protected preDeletedTask ($object) : void  
* protected setCursorOnNextKeyInArray (array $array) : void  
* protected setStateToVote () : void  

#### CondorcetPHP\Condorcet\Election   
* protected static formatResultOptions (array $arg) : array  
* public __clone ()  
* public __destruct ()  
* public __sleep () : array  
* public __wakeup ()  
* public checkVoteCandidate (CondorcetPHP\Condorcet\Vote $vote) : bool  
* public cleanupCalculator () : void  
* public cleanupPairwise () : void  
* public convertRankingCandidates (array $ranking) : bool  
* public finishUpdateVote (CondorcetPHP\Condorcet\Vote $existVote) : void  
* public getCandidateKey ($candidate) : ?int  
* public getCandidateObjectFromKey (int $candidate_key) : ?CondorcetPHP\Condorcet\Candidate  
* public getVoteKey (CondorcetPHP\Condorcet\Vote $vote) : ?int  
* public getVotesManager () : CondorcetPHP\Condorcet\DataManager\VotesManager  
* public prepareUpdateVote (CondorcetPHP\Condorcet\Vote $existVote) : void  
* protected cleanupCompute () : void  
* protected destroyAllLink () : void  
* protected initResult (string $class) : void  
* protected makePairwise () : void  
* protected prepareResult () : bool  
* protected prepareVoteInput ($vote, $tag = null) : void  
* protected registerAllLinks () : void  
* protected registerVote (CondorcetPHP\Condorcet\Vote $vote, $tag = null) : CondorcetPHP\Condorcet\Vote  

#### Abstract CondorcetPHP\Condorcet\ElectionProcess\VoteUtil   
* public static convertVoteInput (string $formula) : array  
* public static getRankingAsString (array $ranking) : string  
* public static parseAnalysingOneLine ($searchCharacter, string $line) : int  
* public static tagsConvert ($tags) : ?array  

#### CondorcetPHP\Condorcet\Result implements ArrayAccess, Countable, Iterator, Traversable  
* public __construct (string $fromMethod, string $byClass, CondorcetPHP\Condorcet\Election $election, array $result, $stats)  
* public addWarning (int $type, ?string $msg = null) : bool  
* public count () : int  
* public current ()  
* public getResultAsInternalKey () : array  
* public key () : int  
* public next () : void  
* public offsetExists ($offset) : bool  
* public offsetGet ($offset)  
* public offsetSet ($offset, $value) : void  
* public offsetUnset ($offset) : void  
* public rewind () : void  
* public valid () : bool  
* protected makeUserResult (CondorcetPHP\Condorcet\Election $election) : array  

#### CondorcetPHP\Condorcet\Throwable\CondorcetException extends Exception implements Throwable  
* public __construct (int $code = 0, string $infos)  
* public __toString () : string  
* public __wakeup ()  
* public getCode ()  
* public getFile ()  
* public getLine ()  
* public getMessage ()  
* public getPrevious ()  
* public getTrace ()  
* public getTraceAsString ()  
* protected correspondence (int $code) : string  
* private __clone ()  

#### CondorcetPHP\Condorcet\Throwable\CondorcetInternalError extends Error implements Throwable  
* public __construct (string $message)  
* public __toString ()  
* public __wakeup ()  
* public getCode ()  
* public getFile ()  
* public getLine ()  
* public getMessage ()  
* public getObjectVersion (bool $major = false) : string  
* public getPrevious ()  
* public getTrace ()  
* public getTraceAsString ()  
* private __clone ()  

#### CondorcetPHP\Condorcet\Throwable\CondorcetInternalException extends Exception implements Throwable  
* public __construct ($message, $code, $previous)  
* public __toString ()  
* public __wakeup ()  
* public getCode ()  
* public getFile ()  
* public getLine ()  
* public getMessage ()  
* public getPrevious ()  
* public getTrace ()  
* public getTraceAsString ()  
* private __clone ()  

#### CondorcetPHP\Condorcet\Timer\Chrono   
* public __construct (CondorcetPHP\Condorcet\Timer\Manager $timer, ?string $role = null)  
* public __destruct ()  
* public getObjectVersion (bool $major = false) : string  
* public getRole () : ?string  
* public getStart () : float  
* public getTimerManager () : CondorcetPHP\Condorcet\Timer\Manager  
* public setRole (?string $role) : void  
* protected managerStartDeclare () : void  
* protected resetStart () : void  

#### CondorcetPHP\Condorcet\Timer\Manager   
* public addTime (CondorcetPHP\Condorcet\Timer\Chrono $chrono) : void  
* public getGlobalTimer () : float  
* public getLastTimer () : float  
* public startDeclare (CondorcetPHP\Condorcet\Timer\Chrono $chrono) : void  

#### CondorcetPHP\Condorcet\Vote implements Iterator, Traversable  
* public __clone ()  
* public __sleep () : array  
* public __toString () : string  
* public current () : array  
* public destroyLink (CondorcetPHP\Condorcet\Election $election) : bool  
* public getHashCode () : string  
* public key () : int  
* public next () : void  
* public registerLink (CondorcetPHP\Condorcet\Election $election) : void  
* public rewind () : void  
* public valid () : bool  
* protected computeContextualRankingWithoutImplicit (array $ranking, CondorcetPHP\Condorcet\Election $election, int $countContextualCandidate = 0) : array  
* protected destroyAllLink () : void  
* private archiveRanking () : void  
* private formatRanking ($ranking) : int  
* private setHashCode () : string  

#### Abstract CondorcetPHP\Condorcet\VoteConstraint   
* public static isVoteAllow (CondorcetPHP\Condorcet\Election $election, CondorcetPHP\Condorcet\Vote $vote) : bool  
* protected static evaluateVote (array $vote) : bool  
