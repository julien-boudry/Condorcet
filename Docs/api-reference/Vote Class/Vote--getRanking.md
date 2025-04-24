# public Vote::getRanking

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Vote.php#L246)

## Description    

```php
public Vote->getRanking ( [?CondorcetPHP\Condorcet\Election $context = null , bool $sortCandidatesInRank = true] ): array
```

Get the actual Ranking of this Vote.

## Parameters

### **context:** *`?CondorcetPHP\Condorcet\Election`*   
    

### **sortCandidatesInRank:** *`bool`*   
Sort Candidate in a Rank by name. Useful for performant internal calls from methods.    


## Return value   

*(`array`)* Multidimenssionnal array populated by Candidate object.


---------------------------------------

## Related method(s)      

* [Vote::setRanking](/Docs/api-reference/Vote%20Class/Vote--setRanking.md)    
