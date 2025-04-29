# public Vote::getRankingAsArrayString

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Vote.php#L412)

## Description    

```php
public Vote->getRankingAsArrayString ( [?CondorcetPHP\Condorcet\Election $context = null] ): array
```

Return the vote actual ranking complete for the contexte of the provide election. Election must be linked to the Vote object.

## Parameter

### **context:** *`?CondorcetPHP\Condorcet\Election`*   
An election already linked to the Vote.    


## Return value   

*(`array`)* Contextual full ranking, with string instead Candidate object.


---------------------------------------

## Related

* [Vote::getRanking](/Docs/api-reference/Vote%20Class/Vote--getRanking().md)    
* [Vote::getRanking](/Docs/api-reference/Vote%20Class/Vote--getRanking().md)    
* [Vote::getRankingAsString](/Docs/api-reference/Vote%20Class/Vote--getRankingAsString().md)    
