## public Condorcet::getWinner

### Description    

```php
public $Condorcet -> getWinner ( [ mixed method] )
```

Get the natural Condorcet winner if there is one. Alternatively you can get the winner(s) from an advanced Condorcet algorithm.    
- **method:** *mixed* *Only if not null :*

The winner will be provided by an advanced algorithm of an available advanced Condorcet method. For most of them, it will be the same as the Condorcet Marquis there. But if it does not exist, it may be different; and in some cases they may be multiple.

Set to True for use object default method. Set the string name of the algorithm for use an specific one.

If null, Natural Condorcet algorithm will be use.



### Return value:   

Candidate object given. Null if there are no available winner or loser.

If you use an advanced method instead of Natural, you can get an array with multiples winners.

Throw an exception on error.


---------------------------------------

### Related method(s)      

* [Condorcet::getLoser](../Condorcet Class/public Condorcet::getLoser.md)    
* [Condorcet::getResult](../Condorcet Class/public Condorcet::getResult.md)    

---------------------------------------

### Examples and explanation

* **[Manual - Natural Condorcet](https://github.com/julien-boudry/Condorcet/wiki/II-%23-C.-Result-%23-1.-Natural-Condorcet)**    
