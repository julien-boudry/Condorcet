## public Election::getImplicitRankingRule

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Election.php#L290)

### Description    

```php
public Election->getImplicitRankingRule ( ): bool
```

Returns the corresponding setting as currently set (True by default).
If it is True then all votes expressing a partial ranking are understood as implicitly placing all the non-mentioned candidates exequos on a last rank.
If it is false, then the candidates not ranked, are not taken into account at all.
    

### Return value   

*(`bool`)* True / False


---------------------------------------

### Related method(s)      

* [Election::setImplicitRanking](/Docs/api-reference/Election%20Class/Election--setImplicitRanking.md)    
