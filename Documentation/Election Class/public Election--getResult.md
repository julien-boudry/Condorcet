## public Election::getResult

### Description    

```php
public $Election -> getResult ( [?string method = null , array options = []] ) : \CondorcetPHP\Condorcet\Result
```

Get a full ranking from an advanced Condorcet method.    
*Have a look on the [supported method](https://github.com/julien-boudry/Condorcet/wiki/I-%23-Installation-%26-Basic-Configuration-%23-2.-Condorcet-Methods), or create [your own algorithm](https://github.com/julien-boudry/Condorcet/wiki/III-%23-C.-Extending-Condorcet-%23-1.-Add-your-own-ranking-algorithm).*
    

##### **method:** *string*   
Not requiered for use object default method. Set the string name of the algorithm for use an specific one.
    


##### **options:** *array*   
    


### Return value:   

*(\CondorcetPHP\Condorcet\Result)* An Condorcet/Result Object (implementing ArrayAccess and Iterator, can be use like an array ordered by rank)


---------------------------------------

### Related method(s)      

* [Election::getWinner](../Election%20Class/public%20Election--getWinner.md)    
* [Condorcet::getDefaultMethod](../Condorcet%20Class/public%20Condorcet--getDefaultMethod.md)    

---------------------------------------

### Examples and explanation

* **[Manual - Ranking from Condorcet Method](https://github.com/julien-boudry/Condorcet/wiki/II-%23-C.-Result-%23-2.-Get-Ranking-from-Condorcet-advanced-Methods)**    
