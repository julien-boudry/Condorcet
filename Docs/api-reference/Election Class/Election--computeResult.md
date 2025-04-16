## public Election::computeResult

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/ElectionProcess/ResultsProcess.php#L302)

### Description    

```php
public Election->computeResult ( [?string $method = null] ): void
```

Really similar to Election::getResult() but not return anything. Just calculates silently and fill the cache.
    

#### **method:** *`?string`*   
Not requiered for use object default method. Set the string name of the algorithm for use an specific one.    

---------------------------------------

### Related method(s)      

* [Election::getWinner](/Docs/api-reference/Election%20Class/Election--getWinner.md)    
* [Election::getResult](/Docs/api-reference/Election%20Class/Election--getResult.md)    
* [Condorcet::getDefaultMethod](/Docs/api-reference/Condorcet%20Class/Condorcet--getDefaultMethod.md)    
