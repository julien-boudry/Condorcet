## public Election::setRankingAllRule

### Description    

```php
public $Election -> setRankingAllRule ( bool rule = true )
```

Set the setting and reset all result data.
If it is True then all votes expressing a partial ranking are understood as implicitly placing all the non-mentioned candidates exequos on a last rank.
If it is false, then the candidates not ranked, are not taken into account at all.    


##### **rule:** *bool*   
New rule.    



### Return value:   

Return True


---------------------------------------

### Related method(s)      

* [Election::getRankingAllRule](../Election Class/public Election--getRankingAllRule.md)    
