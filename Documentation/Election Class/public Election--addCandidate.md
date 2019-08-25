## public Election::addCandidate

### Description    

```php
public $Election -> addCandidate ( [ mixed candidate = automatic] ) : CondorcetPHP\Condorcet\Candidate
```

Add one Candidate to an election.
    

##### **candidate:** *mixed*   
Alphanumeric string or CondorcetPHP\Condorcet\Candidate objet. Your candidate name will be trim().    


### Return value:   

*(CondorcetPHP\Condorcet\Candidate)* The new candidate object (your or automatic one). Throw an exception on error (existing candidate...).


---------------------------------------

### Related method(s)      

* [Election::parseCandidates](../Election%20Class/public%20Election--parseCandidates.md)    
* [Election::addCandidatesFromJson](../Election%20Class/public%20Election--addCandidatesFromJson.md)    
* [Election::removeCandidate](../Election%20Class/public%20Election--removeCandidate.md)    
* [Election::getCandidatesList](../Election%20Class/public%20Election--getCandidatesList.md)    
* [Election::canAddCandidate](../Election%20Class/public%20Election--canAddCandidate.md)    

---------------------------------------

### Examples and explanation

* **[Manual - Manage Candidate](https://github.com/julien-boudry/Condorcet/wiki/II-%23-A.-Create-an-Election-%23-2.-Create-Candidates)**    
