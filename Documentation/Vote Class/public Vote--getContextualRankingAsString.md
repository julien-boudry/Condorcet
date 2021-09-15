## public Vote::getContextualRankingAsString

### Description    

```php
public Vote->getContextualRankingAsString ( CondorcetPHP\Condorcet\Election election ): array
```

Return the vote actual ranking complete for the contexte of the provide election. Election must be linked to the Vote object.
    

##### **election:** *CondorcetPHP\Condorcet\Election*   
An election already linked to the Vote.    


### Return value:   

*(array)* Contextual full ranking, with string instead Candidate object.


---------------------------------------

### Related method(s)      

* [Vote::getContextualRanking](../Vote%20Class/public%20Vote--getContextualRanking.md)    
* [Vote::getRanking](../Vote%20Class/public%20Vote--getRanking.md)    
