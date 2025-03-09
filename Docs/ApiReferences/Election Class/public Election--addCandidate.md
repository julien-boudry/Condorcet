## public Election::addCandidate

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/ElectionProcess/CandidatesProcess.php#L125)

### Description    

```php
public Election->addCandidate ( [CondorcetPHP\Condorcet\Candidate|string|null $candidate = null] ): CondorcetPHP\Condorcet\Candidate
```

Add one candidate to an election.
    

#### **candidate:** *`CondorcetPHP\Condorcet\Candidate|string|null`*   
Alphanumeric string or CondorcetPHP\Condorcet\Candidate object. The whitespace of your candidate name will be trimmed. If null, this function will create a new candidate with an automatic name.    


### Return value:   

*(`CondorcetPHP\Condorcet\Candidate`)* The new candidate object (your or automatic one). Throws an exception on error (existing candidate...).



### Throws:   

* ```CondorcetPHP\Condorcet\Throwable\CandidateExistsException```
* ```CondorcetPHP\Condorcet\Throwable\VotingHasStartedException```

---------------------------------------

### Related method(s)      

* [Election::parseCandidates](/Docs/ApiReferences/Election%20Class/public%20Election--parseCandidates.md)    
* [Election::addCandidatesFromJson](/Docs/ApiReferences/Election%20Class/public%20Election--addCandidatesFromJson.md)    
* [Election::removeCandidate](/Docs/ApiReferences/Election%20Class/public%20Election--removeCandidate.md)    
* [Election::getCandidatesList](/Docs/ApiReferences/Election%20Class/public%20Election--getCandidatesList.md)    
* [Election::canAddCandidate](/Docs/ApiReferences/Election%20Class/public%20Election--canAddCandidate.md)    

---------------------------------------

### Tutorial

* **[This method has explanations and examples in the Documentation Book](https://www.condorcet.io/3.AsPhpLibrary/4.Candidates)**    
