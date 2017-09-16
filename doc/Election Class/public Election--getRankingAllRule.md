## public Election::getRankingAllRule

### Description    

```php
public $Election -> getRankingAllRule ( )
```

Returns the corresponding setting as currently set (True by default).
If it is True then all votes expressing a partial ranking are understood as implicitly placing all the non-mentioned candidates exequos on a last rank.
If it is false, then the candidates not ranked, are not taken into account at all.    


### Return value:   

(bool) True / False


---------------------------------------

### Related method(s)      

* [Election::setRankingAllRule](../Election Class/public Election--setRankingAllRule.md)    
