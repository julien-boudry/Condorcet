# public Vote::addTags

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Vote.php#L604)

## Description    

```php
public Vote->addTags ( array|string $tags ): bool
```

Add tag(s) on this Vote.

## Parameter

### **tags:** *`array|string`*   
Tag(s) are non-numeric alphanumeric string. They can be added by string separated by commas or an array. Tags will be trimmed.    


## Return value   

*(`bool`)* In case of success, return TRUE



## Throws:   

* ```CondorcetPHP\Condorcet\Throwable\VoteInvalidFormatException``` 

---------------------------------------

## Related

* [Vote::removeTags()](/Docs/api-reference/Vote%20Class/Vote--removeTags().md)    

---------------------------------------

## Tutorial

* **[This method has explanations and examples in the Documentation Book](https://docs.condorcet.io/book/3.AsPhpLibrary/5.Votes/2.VotesTags)**    
