## public Vote::getWeight

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Vote.php#L629)

### Description    

```php
public Vote->getWeight ( [?CondorcetPHP\Condorcet\Election $context = null] ): int
```

Get the vote weight. The vote weight capacity must be active at the election level for producing effect on the result.
    

#### **context:** *`?CondorcetPHP\Condorcet\Election`*   
In the context of wich election? (optional).    


### Return value:   

*(`int`)* Weight. Default weight is 1.


---------------------------------------

### Related method(s)      

* [Vote::setWeight](/Docs/ApiReferences/Vote%20Class/public%20Vote--setWeight.md)    
