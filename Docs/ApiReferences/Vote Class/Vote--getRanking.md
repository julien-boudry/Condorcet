## public Vote::getRanking

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Vote.php#L255)

### Description    

```php
public Vote->getRanking ( [bool $sortCandidatesInRank = true] ): array
```

Get the actual Ranking of this Vote.
    

#### **sortCandidatesInRank:** *`bool`*   
Sort Candidate in a Rank by name. Useful for performant internal calls from methods.    


### Return value   

*(`array`)* Multidimenssionnal array populated by Candidate object.


---------------------------------------

### Related method(s)      

* [Vote::setRanking](/Docs/ApiReferences/Vote%20Class/Vote--setRanking.md)    
