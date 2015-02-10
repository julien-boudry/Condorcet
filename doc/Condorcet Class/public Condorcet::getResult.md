## public Condorcet::getResult

### Description    

```php
public $Condorcet -> getResult ( [ mixed method = true, array options = []] )
```

Get a full ranking from an advanced Condorcet method.
*Have a look on the [supported method](https://github.com/julien-boudry/Condorcet/wiki/I-%23-Installation-%26-Basic-Configuration-%23-2.-Condorcet-Methods), or create [your own algorithm](https://github.com/julien-boudry/Condorcet/wiki/III-%23-C.-Extending-Condorcet-%23-1.-Add-your-own-ranking-algorithm).*    
- **method:** *mixed* 

- **options:** *array* *[Result options documentations]()*



### Return value:   

Ordered array or multi-dimensionnal ordered array (if tie), populated by Candidate object.


---------------------------------------

### Related method(s)      

* [Condorcet::getWinner](../Condorcet Class/public Condorcet::getWinner.md)    

---------------------------------------

### Examples and explanation

* **[Manual - Ranking from Condorcet Method](https://github.com/julien-boudry/Condorcet/wiki/II-%23-C.-Result-%23-2.-Simple-Ranking-from-Condorcet-Method%28s%29)**    
