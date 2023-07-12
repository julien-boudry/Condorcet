## public Vote::getAllCandidates

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Vote.php#L290)

### Description    

```php
public Vote->getAllCandidates ( [?CondorcetPHP\Condorcet\Election $context = null] ): array
```

Get all the candidates object set in the last ranking of this Vote.
    

#### **context:** *`?CondorcetPHP\Condorcet\Election`*   
An election already linked to the Vote.    


### Return value:   

*(`array`)* Candidates list.


---------------------------------------

### Related method(s)      

* [Vote::getRanking](/Docs/ApiReferences/Vote%20Class/public%20Vote--getRanking.md)    
* [Vote::countRankingCandidates](/Docs/ApiReferences/Vote%20Class/public%20Vote--countRankingCandidates.md)    
