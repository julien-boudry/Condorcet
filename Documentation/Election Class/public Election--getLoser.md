## public Election::getLoser

### Description    

```php
public $Election -> getLoser ( [ string method = null] )
```

Get the natural Condorcet loser if there is one. Alternatively you can get the loser(s) from an advanced Condorcet algorithm.    


##### **method:** *string*   
*Only if not null :*

The loser will be provided by an advanced algorithm of an available advanced Condorcet method. For most of them, it will be the same as the Condorcet Marquis there. But if it does not exist, it may be different; and in some cases they may be multiple.

If null, Natural Condorcet algorithm will be use.    



### Return value:   

Candidate object given. Null if there are no available winner or loser.

If you use an advanced method instead of Natural, you can get an array with multiples losers.

Throw an exception on error.


---------------------------------------

### Related method(s)      

* [Election::getWinner](../Election%20Class/public%20Election--getWinner.md)    
* [Election::getResult](../Election%20Class/public%20Election--getResult.md)    

---------------------------------------

### Examples and explanation

* **[Manual - Natural Condorcet](https://github.com/julien-boudry/Condorcet/wiki/II-%23-C.-Result-%23-1.-Natural-Condorcet)**    
