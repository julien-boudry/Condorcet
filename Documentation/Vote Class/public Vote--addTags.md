## public Vote::addTags

### Description    

```php
public Vote->addTags ( array|string tags ): bool
```

Add tag(s) on this Vote.
    

##### **tags:** *array|string*   
Tag(s) are non-numeric alphanumeric string. They can be added by string separated by commas or an array.    


### Return value:   

*(bool)* In case of success, return TRUE



### Throws:   

* CondorcetPHP\Condorcet\Throwable\VoteInvalidFormatException

---------------------------------------

### Related method(s)      

* [Vote::removeTags](../Vote%20Class/public%20Vote--removeTags.md)    

---------------------------------------

### Examples and explanation

* **[Manual - Add Vote](https://github.com/julien-boudry/Condorcet/wiki/II-%23-B.-Vote-management-%23-1.-Add-Vote)**    
