# public Election::getVotesListGenerator

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/ElectionProcess/VotesProcess.php#L190)

## Description    

```php
public Election->getVotesListGenerator ( [array|string|null $tags = null , bool $with = true] ): Generator
```

Same as Election::getVotesList. But returns a PHP generator object.
Useful if you work on very large elections with an external DataHandler, because it will not use large amounts of memory.

## Parameters

### **tags:** *`array|string|null`*   
Tags list as a string separated by commas or array.    

### **with:** *`bool`*   
Get votes with these tags or without.    


## Return value   

*(`Generator`)* Populated by each Vote object.


---------------------------------------

## Related

* [Election::getVotesList()](/Docs/api-reference/Election%20Class/Election--getVotesList().md)    
