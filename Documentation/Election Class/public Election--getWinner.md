## public Election::getWinner

### Description    

```php
public Election->getWinner ( [?string method = null] ): CondorcetPHP\Condorcet\Candidate|array|null
```

Get the natural Condorcet winner if there is one. Alternatively you can get the winner(s) from an advanced Condorcet algorithm.
    

##### **method:** *```?string```*   
*Only if not null:    *

The winner will be provided by an advanced algorithm of an available advanced Condorcet method. For most of them, it will be the same as the Condorcet Marquis there. But if it does not exist, it may be different; and in some cases they may be multiple.    

If null, Natural Condorcet algorithm will be use.    


### Return value:   

*(```CondorcetPHP\Condorcet\Candidate|array|null```)* Candidate object given. Null if there are no available winner or loser.

If you use an advanced method instead of Natural, you can get an array with multiples winners.

Throw an exception on error.


---------------------------------------

### Related method(s)      

* [Election::getCondorcetWinner](../Election%20Class/public%20Election--getCondorcetWinner.md)    
* [Election::getLoser](../Election%20Class/public%20Election--getLoser.md)    
* [Election::getResult](../Election%20Class/public%20Election--getResult.md)    

---------------------------------------

### Examples and explanation

* **[Manual - Natural Condorcet](https://github.com/julien-boudry/Condorcet/wiki/II-%23-C.-Result-%23-1.-Natural-Condorcet)**    
