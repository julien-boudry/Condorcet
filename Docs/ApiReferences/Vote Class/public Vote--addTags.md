## public Vote::addTags

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Vote.php#L631)

### Description    

```php
public Vote->addTags ( array|string $tags ): bool
```

Add tag(s) on this Vote.
    

#### **tags:** *`array|string`*   
Tag(s) are non-numeric alphanumeric string. They can be added by string separated by commas or an array. Tags will be trimmed.    


### Return value   

*(`bool`)* In case of success, return TRUE



### Throws:   

* ```CondorcetPHP\Condorcet\Throwable\VoteInvalidFormatException``` 

---------------------------------------

### Related method(s)      

* [Vote::removeTags](/Docs/ApiReferences/Vote%20Class/public%20Vote--removeTags.md)    

---------------------------------------

### Tutorial

* **[This method has explanations and examples in the Documentation Book](https://www.condorcet.io/3.AsPhpLibrary/5.Votes/2.VotesTags)**    
