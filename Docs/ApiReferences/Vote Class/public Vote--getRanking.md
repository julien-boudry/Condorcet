## public Vote::getRanking

### Description    

```php
public Vote->getRanking ( [bool $sortCandidatesInRank = true] ): array
```

Get the actual Ranking of this Vote.
    

#### **sortCandidatesInRank:** *```bool```*   
Sort Candidate in a Rank by name. Useful for performant internal calls from methods.    


### Return value:   

*(```array```)* Multidimenssionnal array populated by Candidate object.


---------------------------------------

### Related method(s)      

* [Vote::setRanking](/Docs/ApiReferences/Vote%20Class/public%20Vote--setRanking.md)    
