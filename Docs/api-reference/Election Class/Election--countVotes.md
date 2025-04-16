## public Election::countVotes

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/ElectionProcess/VotesProcess.php#L41)

### Description    

```php
public Election->countVotes ( [array|string|null $tags = null , int|bool $with = true] ): int
```

Count the number of actual registered and valid votes for this election. This method ignores votes constraints, only valid votes will be counted.
    

#### **tags:** *`array|string|null`*   
Tag in string separated by commas, or an Array.    


#### **with:** *`int|bool`*   
Count Votes with this tag or without this tag.    


### Return value   

*(`int`)* Number of valid and registered votes in this election.


---------------------------------------

### Related method(s)      

* [Election::getVotesList](/Docs/api-reference/Election%20Class/Election--getVotesList.md)    
* [Election::countValidVoteWithConstraints](/Docs/api-reference/Election%20Class/Election--countValidVoteWithConstraints.md)    
