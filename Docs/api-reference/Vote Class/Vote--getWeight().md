# public Vote::getWeight

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Vote.php#L676)

## Description    

```php
public Vote->getWeight ( [?CondorcetPHP\Condorcet\Election $context = null] ): int
```

Get the vote weight. The vote weight capacity must be active at the election level for producing effect on the result.

## Parameter

### **context:** *`?CondorcetPHP\Condorcet\Election`*   
In the context of wich election? (optional).    


## Return value   

*(`int`)* Weight. Default weight is 1.


---------------------------------------

## Related

* [Vote::setWeight()](/Docs/api-reference/Vote%20Class/Vote--setWeight().md)    
