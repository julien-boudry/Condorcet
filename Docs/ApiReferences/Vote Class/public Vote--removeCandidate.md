## public Vote::removeCandidate

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Vote.php#L570)

### Description    

```php
public Vote->removeCandidate ( CondorcetPHP\Condorcet\Candidate|string $candidate ): true
```

Remove candidate from ranking. Set a new ranking and archive the old ranking.
    

#### **candidate:** *`CondorcetPHP\Condorcet\Candidate|string`*   
Candidate object or string.    


### Return value:   

*(`true`)* True on success.



### Throws:   

* ```CondorcetPHP\Condorcet\Throwable\CandidateDoesNotExistException```

---------------------------------------

### Related method(s)      

* [Vote::setRanking](/Docs/ApiReferences/Vote%20Class/public%20Vote--setRanking.md)    
