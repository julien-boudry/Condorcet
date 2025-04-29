# public Election::getLoser

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/ElectionProcess/ResultsProcess.php#L145)

## Description    

```php
public Election->getLoser ( [?string $method = null] ): CondorcetPHP\Condorcet\Candidate|array|null
```

Get the natural Condorcet loser if there is one. Alternatively you can get the loser(s) from an advanced Condorcet algorithm.

## Parameter

### **method:** *`?string`*   
Only if not null the loser will be provided by an advanced algorithm of an available advanced Condorcet method. For most of them, it will be the same as the Condorcet Marquis there. But if it does not exist, it may be different; and in some cases they may be multiple. If null, Natural Condorcet algorithm will be use.    


## Return value   

*(`CondorcetPHP\Condorcet\Candidate|array|null`)* Candidate object given. Null if there are no available winner or loser.

If you use an advanced method instead of Natural, you can get an array with multiples losers.

Throw an exception on error.


---------------------------------------

## Related

* [Election::getWinner](/Docs/api-reference/Election%20Class/Election--getWinner.md)    
* [Election::getResult](/Docs/api-reference/Election%20Class/Election--getResult.md)    

---------------------------------------

## Tutorial

* **[This method has explanations and examples in the Documentation Book](https://docs.condorcet.io/book/3.AsPhpLibrary/6.Results/1.WinnerAndLoser)**    
