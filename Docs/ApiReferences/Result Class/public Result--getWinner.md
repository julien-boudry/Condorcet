## public Result::getWinner

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Result.php#L271)

### Description    

```php
public Result->getWinner ( ): CondorcetPHP\Condorcet\Candidate|array|null
```

('Get the election winner if any')
    

### Return value:   

*(`CondorcetPHP\Condorcet\Candidate|array|null`)* Candidate object given. Null if there are no available winner.
You can get an array with multiples winners.


---------------------------------------

### Related method(s)      

* [Result::getLoser](/Docs/ApiReferences/Result%20Class/public%20Result--getLoser.md)    
* [Election::getWinner](/Docs/ApiReferences/Election%20Class/public%20Election--getWinner.md)    
