## public Election::countValidVoteWithConstraints

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/ElectionProcess/VotesProcess.php#L65)

### Description    

```php
public Election->countValidVoteWithConstraints ( [array|string|null $tags = null , int|bool $with = true] ): int
```

Count the number of actual registered and valid votes for this election. This method doesn't ignore votes constraints, only valid votes will be counted.
    

#### **tags:** *`array|string|null`*   
Tag in string separated by commas, or an Array.    


#### **with:** *`int|bool`*   
Count Votes with this tag or without this tag.    


### Return value   

*(`int`)* Number of valid and registered votes in this election.


---------------------------------------

### Related method(s)      

* [Election::countInvalidVoteWithConstraints](/Docs/api-reference/Election%20Class/Election--countInvalidVoteWithConstraints.md)    
* [Election::countVotes](/Docs/api-reference/Election%20Class/Election--countVotes.md)    
* [Election::sumValidVoteWeightsWithConstraints](/Docs/api-reference/Election%20Class/Election--sumValidVoteWeightsWithConstraints.md)    
