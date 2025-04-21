# public Vote::getContextualRankingAsString

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Vote.php#L420)

## Description    

```php
public Vote->getContextualRankingAsString ( CondorcetPHP\Condorcet\Election $election ): array
```

Return the vote actual ranking complete for the contexte of the provide election. Election must be linked to the Vote object.

## Parameter

### **election:** *`CondorcetPHP\Condorcet\Election`*   
An election already linked to the Vote.    


## Return value   

*(`array`)* Contextual full ranking, with string instead Candidate object.


---------------------------------------

## Related method(s)      

* [Vote::getContextualRanking](/Docs/api-reference/Vote%20Class/Vote--getContextualRanking.md)    
* [Vote::getRanking](/Docs/api-reference/Vote%20Class/Vote--getRanking.md)    
