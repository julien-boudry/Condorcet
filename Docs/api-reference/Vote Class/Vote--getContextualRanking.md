## public Vote::getContextualRanking

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Vote.php#L317)

### Description    

```php
public Vote->getContextualRanking ( CondorcetPHP\Condorcet\Election $election ): array
```

Return the vote actual ranking complete for the contexte of the provide election. Election must be linked to the Vote object.
    

#### **election:** *`CondorcetPHP\Condorcet\Election`*   
An election already linked to the Vote.    


### Return value   

*(`array`)* Contextual full ranking.



### Throws:   

* ```CondorcetPHP\Condorcet\Throwable\VoteNotLinkedException``` 

---------------------------------------

### Related method(s)      

* [Vote::getContextualRankingAsString](/Docs/api-reference/Vote%20Class/Vote--getContextualRankingAsString.md)    
* [Vote::getRanking](/Docs/api-reference/Vote%20Class/Vote--getRanking.md)    
