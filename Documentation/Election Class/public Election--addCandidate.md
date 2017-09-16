## public Election::addCandidate

### Description    

```php
public $Election -> addCandidate ( [ mixed candidate = automatic] )
```

Add one Candidate to an election.    


##### **candidate:** *mixed*   
Alphanumeric string or Condorcet\Candidate objet. Your candidate name will be trim().    



### Return value:   

The new candidate name (your or automatic one). Throw an exception on error (existing candidate...).


---------------------------------------

### Related method(s)      

* [Election::parseCandidates](../Election Class/public Election--parseCandidates.md)    
* [Election::jsonCandidates](../Election Class/public Election--jsonCandidates.md)    
* [Election::removeCandidate](../Election Class/public Election--removeCandidate.md)    
* [Election::getCandidatesList](../Election Class/public Election--getCandidatesList.md)    
* [Election::canAddCandidate](../Election Class/public Election--canAddCandidate.md)    

---------------------------------------

### Examples and explanation

* **[Manual - Manage Candidate](https://github.com/julien-boudry/Condorcet/wiki/II-%23-A.-Create-an-Election-%23-2.-Create-Candidates)**    
