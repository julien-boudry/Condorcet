> **[Presentation](../README.md) | [Manual](https://github.com/julien-boudry/Condorcet/wiki) | Methods References | [Tests](../Tests)**

# Public Methods Index _(Not yet exhaustive, not yet....)*_
_Not including technical public methods which ones are used for very advanced use between components (typically if you extend Coondorcet or build your own modules)._    

_*: I try to update and complete the documentation. See also [the manual](https://github.com/julien-boudry/Condorcet/wiki), [the tests](../Tests) also produce many examples. And create issues for questions or fixing documentation!_   

## CondorcetPHP\Condorcet\Algo\Pairwise Class  

* [public Algo\Pairwise->getExplicitPairwise()](Algo_Pairwise%20Class/public%20Algo_Pairwise--getExplicitPairwise.md)  
* [public Algo\Pairwise->getObjectVersion()](Algo_Pairwise%20Class/public%20Algo_Pairwise--getObjectVersion.md)  
## CondorcetPHP\Condorcet\Candidate Class  

* [public Candidate->__construct()](Candidate%20Class/public%20Candidate--__construct.md)  
* [public Candidate->countLinks()](Candidate%20Class/public%20Candidate--countLinks.md)  
* [public Candidate->getCreateTimestamp()](Candidate%20Class/public%20Candidate--getCreateTimestamp.md)  
* [public Candidate->getHistory()](Candidate%20Class/public%20Candidate--getHistory.md)  
* [public Candidate->getLinks()](Candidate%20Class/public%20Candidate--getLinks.md)  
* [public Candidate->getName()](Candidate%20Class/public%20Candidate--getName.md)  
* [public Candidate->getObjectVersion()](Candidate%20Class/public%20Candidate--getObjectVersion.md)  
* [public Candidate->getProvisionalState()](Candidate%20Class/public%20Candidate--getProvisionalState.md)  
* [public Candidate->getTimestamp()](Candidate%20Class/public%20Candidate--getTimestamp.md)  
* [public Candidate->haveLink()](Candidate%20Class/public%20Candidate--haveLink.md)  
* [public Candidate->setName()](Candidate%20Class/public%20Candidate--setName.md)  
## CondorcetPHP\Condorcet\Condorcet Class  

* [public static Condorcet::addMethod()](Condorcet%20Class/public%20static%20Condorcet--addMethod.md)  
* [public static Condorcet::getAuthMethods()](Condorcet%20Class/public%20static%20Condorcet--getAuthMethods.md)  
* [public static Condorcet::getDefaultMethod()](Condorcet%20Class/public%20static%20Condorcet--getDefaultMethod.md)  
* [public static Condorcet::getVersion()](Condorcet%20Class/public%20static%20Condorcet--getVersion.md)  
* [public static Condorcet::isAuthMethod()](Condorcet%20Class/public%20static%20Condorcet--isAuthMethod.md)  
* [public static Condorcet::setDefaultMethod()](Condorcet%20Class/public%20static%20Condorcet--setDefaultMethod.md)  
## CondorcetPHP\Condorcet\CondorcetUtil Class  

* [public static CondorcetUtil::format()](CondorcetUtil%20Class/public%20static%20CondorcetUtil--format.md)  
## CondorcetPHP\Condorcet\DataManager\VotesManager Class  

* [public DataManager\VotesManager->getObjectVersion()](DataManager_VotesManager%20Class/public%20DataManager_VotesManager--getObjectVersion.md)  
## CondorcetPHP\Condorcet\Election Class  

* [public static Election::setMaxParseIteration()](Election%20Class/public%20static%20Election--setMaxParseIteration.md)  
* [public static Election::setMaxVoteNumber()](Election%20Class/public%20static%20Election--setMaxVoteNumber.md)  
* [public Election->__construct()](Election%20Class/public%20Election--__construct.md)  
* [public Election->addCandidate()](Election%20Class/public%20Election--addCandidate.md)  
* [public Election->addCandidatesFromJson()](Election%20Class/public%20Election--addCandidatesFromJson.md)  
* [public Election->addConstraint()](Election%20Class/public%20Election--addConstraint.md)  
* [public Election->addVote()](Election%20Class/public%20Election--addVote.md)  
* [public Election->addVotesFromJson()](Election%20Class/public%20Election--addVotesFromJson.md)  
* [public Election->allowVoteWeight()](Election%20Class/public%20Election--allowVoteWeight.md)  
* [public Election->canAddCandidate()](Election%20Class/public%20Election--canAddCandidate.md)  
* [public Election->clearConstraints()](Election%20Class/public%20Election--clearConstraints.md)  
* [public Election->computeResult()](Election%20Class/public%20Election--computeResult.md)  
* [public Election->countCandidates()](Election%20Class/public%20Election--countCandidates.md)  
* [public Election->countInvalidVoteWithConstraints()](Election%20Class/public%20Election--countInvalidVoteWithConstraints.md)  
* [public Election->countValidVoteWithConstraints()](Election%20Class/public%20Election--countValidVoteWithConstraints.md)  
* [public Election->countVotes()](Election%20Class/public%20Election--countVotes.md)  
* [public Election->getCandidateObjectFromName()](Election%20Class/public%20Election--getCandidateObjectFromName.md)  
* [public Election->getCandidatesList()](Election%20Class/public%20Election--getCandidatesList.md)  
* [public Election->getCandidatesListAsString()](Election%20Class/public%20Election--getCandidatesListAsString.md)  
* [public Election->getChecksum()](Election%20Class/public%20Election--getChecksum.md)  
* [public Election->getConstraints()](Election%20Class/public%20Election--getConstraints.md)  
* [public Election->getExplicitPairwise()](Election%20Class/public%20Election--getExplicitPairwise.md)  
* [public Election->getGlobalTimer()](Election%20Class/public%20Election--getGlobalTimer.md)  
* [public Election->getImplicitRankingRule()](Election%20Class/public%20Election--getImplicitRankingRule.md)  
* [public Election->getLastTimer()](Election%20Class/public%20Election--getLastTimer.md)  
* [public Election->getLoser()](Election%20Class/public%20Election--getLoser.md)  
* [public Election->getObjectVersion()](Election%20Class/public%20Election--getObjectVersion.md)  
* [public Election->getPairwise()](Election%20Class/public%20Election--getPairwise.md)  
* [public Election->getResult()](Election%20Class/public%20Election--getResult.md)  
* [public Election->getState()](Election%20Class/public%20Election--getState.md)  
* [public Election->getTimerManager()](Election%20Class/public%20Election--getTimerManager.md)  
* [public Election->getVotesList()](Election%20Class/public%20Election--getVotesList.md)  
* [public Election->getVotesListAsString()](Election%20Class/public%20Election--getVotesListAsString.md)  
* [public Election->getVotesListGenerator()](Election%20Class/public%20Election--getVotesListGenerator.md)  
* [public Election->getWinner()](Election%20Class/public%20Election--getWinner.md)  
* [public Election->isRegisteredCandidate()](Election%20Class/public%20Election--isRegisteredCandidate.md)  
* [public Election->isVoteWeightAllowed()](Election%20Class/public%20Election--isVoteWeightAllowed.md)  
* [public Election->parseCandidates()](Election%20Class/public%20Election--parseCandidates.md)  
* [public Election->parseVotes()](Election%20Class/public%20Election--parseVotes.md)  
* [public Election->removeCandidates()](Election%20Class/public%20Election--removeCandidates.md)  
* [public Election->removeExternalDataHandler()](Election%20Class/public%20Election--removeExternalDataHandler.md)  
* [public Election->removeVotes()](Election%20Class/public%20Election--removeVotes.md)  
* [public Election->removeVotesByTags()](Election%20Class/public%20Election--removeVotesByTags.md)  
* [public Election->setExternalDataHandler()](Election%20Class/public%20Election--setExternalDataHandler.md)  
* [public Election->setImplicitRanking()](Election%20Class/public%20Election--setImplicitRanking.md)  
* [public Election->setStateToVote()](Election%20Class/public%20Election--setStateToVote.md)  
* [public Election->sumValidVotesWeightWithConstraints()](Election%20Class/public%20Election--sumValidVotesWeightWithConstraints.md)  
* [public Election->sumVotesWeight()](Election%20Class/public%20Election--sumVotesWeight.md)  
* [public Election->testIfVoteIsValidUnderElectionConstraints()](Election%20Class/public%20Election--testIfVoteIsValidUnderElectionConstraints.md)  
## CondorcetPHP\Condorcet\Result Class  

* [public Result->getBuildTimeStamp()](Result%20Class/public%20Result--getBuildTimeStamp.md)  
* [public Result->getClassGenerator()](Result%20Class/public%20Result--getClassGenerator.md)  
* [public Result->getCondorcetElectionGeneratorVersion()](Result%20Class/public%20Result--getCondorcetElectionGeneratorVersion.md)  
* [public Result->getCondorcetLoser()](Result%20Class/public%20Result--getCondorcetLoser.md)  
* [public Result->getCondorcetWinner()](Result%20Class/public%20Result--getCondorcetWinner.md)  
* [public Result->getLoser()](Result%20Class/public%20Result--getLoser.md)  
* [public Result->getMethod()](Result%20Class/public%20Result--getMethod.md)  
* [public Result->getObjectVersion()](Result%20Class/public%20Result--getObjectVersion.md)  
* [public Result->getOriginalResultArrayWithString()](Result%20Class/public%20Result--getOriginalResultArrayWithString.md)  
* [public Result->getResultAsArray()](Result%20Class/public%20Result--getResultAsArray.md)  
* [public Result->getResultAsString()](Result%20Class/public%20Result--getResultAsString.md)  
* [public Result->getStats()](Result%20Class/public%20Result--getStats.md)  
* [public Result->getWarning()](Result%20Class/public%20Result--getWarning.md)  
* [public Result->getWinner()](Result%20Class/public%20Result--getWinner.md)  
## CondorcetPHP\Condorcet\Throwable\CondorcetException Class  

* [public Throwable\CondorcetException->getObjectVersion()](Throwable_CondorcetException%20Class/public%20Throwable_CondorcetException--getObjectVersion.md)  
## CondorcetPHP\Condorcet\Timer\Manager Class  

* [public Timer\Manager->getHistory()](Timer_Manager%20Class/public%20Timer_Manager--getHistory.md)  
* [public Timer\Manager->getObjectVersion()](Timer_Manager%20Class/public%20Timer_Manager--getObjectVersion.md)  
## CondorcetPHP\Condorcet\Vote Class  

* [public Vote->__construct()](Vote%20Class/public%20Vote--__construct.md)  
* [public Vote->addTags()](Vote%20Class/public%20Vote--addTags.md)  
* [public Vote->countLinks()](Vote%20Class/public%20Vote--countLinks.md)  
* [public Vote->countRankingCandidates()](Vote%20Class/public%20Vote--countRankingCandidates.md)  
* [public Vote->getAllCandidates()](Vote%20Class/public%20Vote--getAllCandidates.md)  
* [public Vote->getContextualRanking()](Vote%20Class/public%20Vote--getContextualRanking.md)  
* [public Vote->getContextualRankingAsString()](Vote%20Class/public%20Vote--getContextualRankingAsString.md)  
* [public Vote->getCreateTimestamp()](Vote%20Class/public%20Vote--getCreateTimestamp.md)  
* [public Vote->getHistory()](Vote%20Class/public%20Vote--getHistory.md)  
* [public Vote->getLinks()](Vote%20Class/public%20Vote--getLinks.md)  
* [public Vote->getObjectVersion()](Vote%20Class/public%20Vote--getObjectVersion.md)  
* [public Vote->getRanking()](Vote%20Class/public%20Vote--getRanking.md)  
* [public Vote->getSimpleRanking()](Vote%20Class/public%20Vote--getSimpleRanking.md)  
* [public Vote->getTags()](Vote%20Class/public%20Vote--getTags.md)  
* [public Vote->getTagsAsString()](Vote%20Class/public%20Vote--getTagsAsString.md)  
* [public Vote->getTimestamp()](Vote%20Class/public%20Vote--getTimestamp.md)  
* [public Vote->getWeight()](Vote%20Class/public%20Vote--getWeight.md)  
* [public Vote->haveLink()](Vote%20Class/public%20Vote--haveLink.md)  
* [public Vote->removeAllTags()](Vote%20Class/public%20Vote--removeAllTags.md)  
* [public Vote->removeCandidate()](Vote%20Class/public%20Vote--removeCandidate.md)  
* [public Vote->removeTags()](Vote%20Class/public%20Vote--removeTags.md)  
* [public Vote->setRanking()](Vote%20Class/public%20Vote--setRanking.md)  
* [public Vote->setWeight()](Vote%20Class/public%20Vote--setWeight.md)  
