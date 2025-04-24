# public Vote::setWeight

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Vote.php#L694)

## Description    

```php
public Vote->setWeight ( int $newWeight ): int
```

Set a vote weight. The vote weight capacity must be active at the election level for producing effect on the result.

## Parameter

### **newWeight:** *`int`*   
The new vote weight.    


## Return value   

*(`int`)* New weight.



## Throws:   

* ```CondorcetPHP\Condorcet\Throwable\VoteInvalidFormatException``` 

---------------------------------------

## Related method(s)      

* [Vote::getWeight](/Docs/api-reference/Vote%20Class/Vote--getWeight.md)    
