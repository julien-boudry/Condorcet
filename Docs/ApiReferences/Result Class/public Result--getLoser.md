## public Result::getLoser

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Result.php#L282)

### Description    

```php
public Result->getLoser ( ): CondorcetPHP\Condorcet\Candidate|array|null
```

('Get the election loser if any')
    

### Return value:   

*(`CondorcetPHP\Condorcet\Candidate|array|null`)* Candidate object given. Null if there are no available loser.
You can get an array with multiples losers.


---------------------------------------

### Related method(s)      

* [Result::getWinner](/Docs/ApiReferences/Result%20Class/public%20Result--getWinner.md)    
* [Election::getLoser](/Docs/ApiReferences/Election%20Class/public%20Election--getLoser.md)    
