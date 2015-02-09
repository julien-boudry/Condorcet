## public Condorcet::addCandidate

### Description    

```php
addCandidate ( [ mixed candidate = automatic] )
```

Add one Candidate to an election.    
- **candidate:** *mixed* Alphanumeric string or Condorcet\Candidate objet. Your candidate name will be trim().



### Return value:   

The new candidate name (your or automatic one). Throw an exception on error (existing candidate...).


---------------------------------------

### Related method(s)      

* [Condorcet::parseCandidates](../Condorcet Class/public Condorcet::parseCandidates.md)    
* [Condorcet::jsonCandidates](../Condorcet Class/public Condorcet::jsonCandidates.md)    
* [Condorcet::removeCandidate](../Condorcet Class/public Condorcet::removeCandidate.md)    
* [Condorcet::getCandidatesList](../Condorcet Class/public Condorcet::getCandidatesList.md)    
* [Condorcet::canAddCandidate](../Condorcet Class/public Condorcet::canAddCandidate.md)    

---------------------------------------

### Examples and explanation

* **[Manual - Manage Candidate](https://github.com/julien-boudry/Condorcet/wiki/II-%23-A.-Create-an-Election-%23-2.-Create-Candidates)**    
