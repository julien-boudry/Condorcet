## public Election::getWinner

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/ElectionProcess/ResultsProcess.php#L114)

### Description    

```php
public Election->getWinner ( [?string $method = null] ): CondorcetPHP\Condorcet\Candidate|array|null
```

Get the natural Condorcet winner if there is one. Alternatively you can get the winner(s) from an advanced Condorcet algorithm.
    

#### **method:** *`?string`*   
Only if not null the winner will be provided by an advanced algorithm of an available advanced Condorcet method. For most of them, it will be the same as the Condorcet Marquis there. But if it does not exist, it may be different; and in some cases they may be multiple. If null, Natural Condorcet algorithm will be use.    


### Return value   

*(`CondorcetPHP\Condorcet\Candidate|array|null`)* Candidate object given. Null if there are no available winner or loser.

If you use an advanced method instead of Natural, you can get an array with multiples winners.

Throw an exception on error.


---------------------------------------

### Related method(s)      

* [Election::getCondorcetWinner](/Docs/api-reference/Election%20Class/Election--getCondorcetWinner.md)    
* [Election::getLoser](/Docs/api-reference/Election%20Class/Election--getLoser.md)    
* [Election::getResult](/Docs/api-reference/Election%20Class/Election--getResult.md)    

---------------------------------------

### Tutorial

* **[This method has explanations and examples in the Documentation Book](https://www.condorcet.io/3.AsPhpLibrary/6.Results/1.WinnerAndLoser)**    
