## public Election::getResult

### Description    

```php
public Election->getResult ( [?string $method = null , array $methodOptions = []] ): CondorcetPHP\Condorcet\Result
```

Get a full ranking from an advanced Condorcet method.
*Have a look on the [supported method](https://github.com/julien-boudry/Condorcet/wiki/I-%23-Installation-%26-Basic-Configuration-%23-2.-Condorcet-Methods), or create [your own algorithm](https://github.com/julien-boudry/Condorcet/wiki/III-%23-C.-Extending-Condorcet-%23-1.-Add-your-own-ranking-algorithm).*
    

##### **method:** *```?string```*   
Not required for use election default method. Set the string name of the algorithm for use of a specific one.    


##### **methodOptions:** *```array```*   
Array of option for some methods. Look at each method documentation.    


### Return value:   

*(```CondorcetPHP\Condorcet\Result```)* An Condorcet/Result Object (implementing ArrayAccess and Iterator, can be use like an array ordered by rank)



### Throws:   

* ```CondorcetPHP\Condorcet\Throwable\AlgorithmException```

---------------------------------------

### Related method(s)      

* [Election::getWinner](../Election%20Class/public%20Election--getWinner.md)    
* [Condorcet::getDefaultMethod](../Condorcet%20Class/public%20Condorcet--getDefaultMethod.md)    

---------------------------------------

### Tutorial

* **[This method has explanations and examples in the Documentation Book](https://condorcetphp.github.io/Documentation-Book/#/3.AsPhpLibrary/6.Results/2.FullRanking)**    
