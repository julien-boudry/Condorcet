## public Vote::getContextualRanking

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Vote.php#L320)

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

* [Vote::getContextualRankingAsString](/Docs/ApiReferences/Vote%20Class/public%20Vote--getContextualRankingAsString.md)    
* [Vote::getRanking](/Docs/ApiReferences/Vote%20Class/public%20Vote--getRanking.md)    
