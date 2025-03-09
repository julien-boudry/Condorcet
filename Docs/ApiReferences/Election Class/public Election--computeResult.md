## public Election::computeResult

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/ElectionProcess/ResultsProcess.php#L262)

### Description    

```php
public Election->computeResult ( [?string $method = null] ): void
```

Really similar to Election::getResult() but not return anything. Just calculates silently and fill the cache.
    

#### **method:** *`?string`*   
Not requiered for use object default method. Set the string name of the algorithm for use an specific one.    

---------------------------------------

### Related method(s)      

* [Election::getWinner](/Docs/ApiReferences/Election%20Class/public%20Election--getWinner.md)    
* [Election::getResult](/Docs/ApiReferences/Election%20Class/public%20Election--getResult.md)    
* [Condorcet::getDefaultMethod](/Docs/ApiReferences/Condorcet%20Class/public%20Condorcet--getDefaultMethod.md)    
