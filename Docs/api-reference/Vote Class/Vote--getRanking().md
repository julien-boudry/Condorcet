# public Vote::getRanking

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Vote.php#L247)

## Description    

```php
public Vote->getRanking ( [?CondorcetPHP\Condorcet\Election $context = null , bool $sortCandidatesInRank = true] ): array
```

Get the actual Ranking of this Vote.

## Parameters

### **context:** *`?CondorcetPHP\Condorcet\Election`*   
An election already linked to the Vote.    

### **sortCandidatesInRank:** *`bool`*   
Sort Candidate in a Rank by name. Useful for performant internal calls from methods.    


## Return value   

*(`array`)* Multidimenssionnal array populated by Candidate object.


---------------------------------------

## Related

* [Vote::setRanking()](/Docs/api-reference/Vote%20Class/Vote--setRanking().md)    
