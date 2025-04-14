## public static Election::maxVotePerElection

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Election.php#L25)

### Description    

```php
public static ?int Election::maxVotePerElection 
```

Add a limitation on Election::addVote and related methods. You can't add new votes if the number of registered votes is equal or superior to this limit.
Null will deactivate this functionality. An integer will set the limit.
    
---------------------------------------

### Related method(s)      

* [Election::maxParseIteration](/Docs/api-reference/Election%20Class/Election--maxParseIteration.md)    
