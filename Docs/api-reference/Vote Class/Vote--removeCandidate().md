# public Vote::removeCandidate

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Vote.php#L565)

## Description    

```php
public Vote->removeCandidate ( CondorcetPHP\Condorcet\Candidate|string $candidate ): true
```

Remove candidate from ranking. Set a new ranking and archive the old ranking.

## Parameter

### **candidate:** *`CondorcetPHP\Condorcet\Candidate|string`*   
Candidate object or string.    


## Return value   

*(`true`)* True on success.



## Throws:   

* ```CondorcetPHP\Condorcet\Throwable\CandidateDoesNotExistException``` 

---------------------------------------

## Related

* [Vote::setRanking](/Docs/api-reference/Vote%20Class/Vote--setRanking().md)    
