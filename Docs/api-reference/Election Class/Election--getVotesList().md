# public Election::getVotesList

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/ElectionProcess/VotesProcess.php#L111)

## Description    

```php
public Election->getVotesList ( [array|string|null $tags = null , bool $with = true] ): array
```

Get registered votes list.

## Parameters

### **tags:** *`array|string|null`*   
Tags list as a string separated by commas or array.    

### **with:** *`bool`*   
Get votes with these tags or without.    


## Return value   

*(`array`)* Populated by each Vote object.


---------------------------------------

## Related

* [Election::countVotes](/Docs/api-reference/Election%20Class/Election--countVotes().md)    
* [Election::getVotesListAsString](/Docs/api-reference/Election%20Class/Election--getVotesListAsString().md)    
