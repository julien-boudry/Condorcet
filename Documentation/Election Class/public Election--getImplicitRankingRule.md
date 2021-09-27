## public Election::getImplicitRankingRule

### Description    

```php
public Election->getImplicitRankingRule ( ): bool
```

Returns the corresponding setting as currently set (True by default).
If it is True then all votes expressing a partial ranking are understood as implicitly placing all the non-mentioned candidates exequos on a last rank.
If it is false, then the candidates not ranked, are not taken into account at all.
    

### Return value:   

*(bool)* True / False


---------------------------------------

### Related method(s)      

* [Election::setImplicitRanking](../Election%20Class/public%20Election--setImplicitRanking.md)    
