## public Election::getLastTimer

### Description    

```php
public $Election -> getLastTimer ( [ bool floatNumber = false] )
```

Return the last computation runtime (typically after a getResult() call.). Include only computation related methods.    


##### **floatNumber:** *bool*   
If true, return float. else, a string with 5 decimals.    



### Return value:   

Int or string (look param).


---------------------------------------

### Related method(s)      

* [Election::getGlobalTimer](../Election%20Class/public%20Election--getGlobalTimer.md)    

---------------------------------------

### Examples and explanation

* **[Manual - Timber benchmarking](https://github.com/julien-boudry/Condorcet/wiki/III-%23-A.-Avanced-features-%26-Configuration-%23-1.-Timer-Benchmarking)**    
