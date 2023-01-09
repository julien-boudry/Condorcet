## public Result::getCondorcetLoser

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Result.php#L234)

### Description    

```php
public Result->getCondorcetLoser ( ): ?CondorcetPHP\Condorcet\Candidate
```

Get the Condorcet loser, if exist, at the result time.
    

### Return value:   

*(`?CondorcetPHP\Condorcet\Candidate`)* Condorcet/Candidate object if there is a Condorcet loser or NULL instead.


---------------------------------------

### Related method(s)      

* [Result::getCondorcetWinner](/Docs/ApiReferences/Result%20Class/public%20Result--getCondorcetWinner.md)    
* [Election::getLoser](/Docs/ApiReferences/Election%20Class/public%20Election--getLoser.md)    
