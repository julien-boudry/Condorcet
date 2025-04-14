## public Election::getResult

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/ElectionProcess/ResultsProcess.php#L59)

### Description    

```php
public Election->getResult ( [?string $method = null , array $methodOptions = []] ): CondorcetPHP\Condorcet\Result
```

Get a full ranking from an advanced Condorcet method.
    

#### **method:** *`?string`*   
Not required for use election default method. Set the string name of the algorithm for use of a specific one.    


#### **methodOptions:** *`array`*   
Array of option for some methods. Look at each method documentation.    


### Return value   

*(`CondorcetPHP\Condorcet\Result`)* An Condorcet/Result Object (implementing ArrayAccess and Iterator, can be use like an array ordered by rank)



### Throws:   

* ```CondorcetPHP\Condorcet\Throwable\VotingMethodIsNotImplemented``` 

---------------------------------------

### Related method(s)      

* [Election::getWinner](/Docs/api-reference/Election%20Class/Election--getWinner.md)    
* [Condorcet::getDefaultMethod](/Docs/api-reference/Condorcet%20Class/Condorcet--getDefaultMethod.md)    

---------------------------------------

### Tutorial

* **[This method has explanations and examples in the Documentation Book](https://www.condorcet.io/3.AsPhpLibrary/6.Results/2.FullRanking)**    
