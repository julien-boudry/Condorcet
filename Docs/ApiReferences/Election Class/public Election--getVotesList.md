## public Election::getVotesList

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/ElectionProcess/VotesProcess.php#L101)

### Description    

```php
public Election->getVotesList ( [array|string|null $tags = null , bool $with = true] ): array
```

Get registered vote list.
    

#### **tags:** *`array|string|null`*   
Tags list as a string separated by commas or array.    


#### **with:** *`bool`*   
Get votes with these tags or without.    


### Return value:   

*(`array`)* Populated by each Vote object.


---------------------------------------

### Related method(s)      

* [Election::countVotes](/Docs/ApiReferences/Election%20Class/public%20Election--countVotes.md)    
* [Election::getVotesListAsString](/Docs/ApiReferences/Election%20Class/public%20Election--getVotesListAsString.md)    
