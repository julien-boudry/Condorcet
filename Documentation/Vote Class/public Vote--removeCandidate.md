## public Vote::removeCandidate

### Description    

```php
public Vote->removeCandidate ( CondorcetPHP\Condorcet\Candidate|string candidate ): bool
```

Remove candidate from ranking. Set a new ranking and archive the old ranking.
    

##### **candidate:** *CondorcetPHP\Condorcet\Candidate|string*   
Candidate object or string.    


### Return value:   

*(bool)* True on success.



### Throws:   

* CondorcetPHP\Condorcet\Throwable\CandidateDoesNotExistException

---------------------------------------

### Related method(s)      

* [Vote::setRanking](../Vote%20Class/public%20Vote--setRanking.md)    
