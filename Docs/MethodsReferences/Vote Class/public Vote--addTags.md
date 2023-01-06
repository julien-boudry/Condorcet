## public Vote::addTags

### Description    

```php
public Vote->addTags ( array|string $tags ): bool
```

Add tag(s) on this Vote.
    

#### **tags:** *```array|string```*   
Tag(s) are non-numeric alphanumeric string. They can be added by string separated by commas or an array.    


### Return value:   

*(```bool```)* In case of success, return TRUE



### Throws:   

* ```CondorcetPHP\Condorcet\Throwable\VoteInvalidFormatException```

---------------------------------------

### Related method(s)      

* [Vote::removeTags](/Docs/MethodsReferences/Vote%20Class/public%20Vote--removeTags.md)    

---------------------------------------

### Tutorial

* **[This method has explanations and examples in the Documentation Book](https://www.condorcet.io#/3.AsPhpLibrary/5.Votes/2.VotesTags)**    
