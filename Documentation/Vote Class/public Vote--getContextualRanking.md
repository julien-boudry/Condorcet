## public Vote::getContextualRanking

### Description    

```php
public $Vote -> getContextualRanking ( CondorcetPHP\Election election [, bool stringMode = false] )
```

Return the vote actual ranking complete for the contexte of the provide election. Election must be linked to the Vote object.    


##### **election:** *CondorcetPHP\Election*   
An election is already linked to Vote.    



##### **stringMode:** *bool*   
If true. Will return string name instead of Candidate object.    



### Return value:   

*(array)* Contextual full ranking.


---------------------------------------

### Related method(s)      

* [Vote::getRanking](../Vote%20Class/public%20Vote--getRanking.md)    
