# public Election::addCandidate

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/ElectionProcess/CandidatesProcess.php#L135)

## Description    

```php
public Election->addCandidate ( [CondorcetPHP\Condorcet\Candidate|string|null $candidate = null] ): CondorcetPHP\Condorcet\Candidate
```

Add a candidate to an election.

## Parameter

### **candidate:** *`CondorcetPHP\Condorcet\Candidate|string|null`*   
Alphanumeric string or CondorcetPHP\Condorcet\Candidate object. The candidate name's white spaces will be removed. If null, this function will create a new candidate with an automatic name.    


## Return value   

*(`CondorcetPHP\Condorcet\Candidate`)* The newly created candidate object (yours or automatically generated). Throws an exception in case of error (existing candidate...).



## Throws:   

* ```CandidateExistsException``` 
* ```VotingHasStartedException``` 

---------------------------------------

## Related

* [Election::parseCandidates()](/Docs/api-reference/Election%20Class/Election--parseCandidates().md)    
* [Election::addCandidatesFromJson()](/Docs/api-reference/Election%20Class/Election--addCandidatesFromJson().md)    
* [Election::removeCandidates()](/Docs/api-reference/Election%20Class/Election--removeCandidates().md)    
* [Election::getCandidatesList()](/Docs/api-reference/Election%20Class/Election--getCandidatesList().md)    
* [Election::canAddCandidate()](/Docs/api-reference/Election%20Class/Election--canAddCandidate().md)    

---------------------------------------

## Tutorial

* **[This method has explanations and examples in the Documentation Book](https://docs.condorcet.io/book/3.AsPhpLibrary/4.Candidates)**    
