# public Election::setImplicitRanking

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Election.php#L312)

## Description    

```php
public Election->setImplicitRanking ( [bool $rule = true] ): static
```

Set the setting and reset all result data.
If it is True then all votes expressing a partial ranking are understood as implicitly placing all the non-mentioned candidates exequos on a last rank.
If it is false, then the candidates not ranked, are not taken into account at all.
    

### **rule:** *`bool`*   
New rule.    


## Return value   

*(`static`)* Return True


---------------------------------------

## Related method(s)      

* [Election::implicitRankingRule](/Docs/api-reference/Election%20Class/Election--implicitRankingRule.md)    
