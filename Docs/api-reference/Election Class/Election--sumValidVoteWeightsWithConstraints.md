## public Election::sumValidVoteWeightsWithConstraints

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/ElectionProcess/VotesProcess.php#L98)

### Description    

```php
public Election->sumValidVoteWeightsWithConstraints ( [array|string|null $tags = null , int|bool $with = true] ): int
```

Sum total votes weight in this election. If vote weight functionality is disabled (default setting), it will return the number of registered votes. This method doesn't ignore votes constraints, only valid votes will be counted.
    

#### **tags:** *`array|string|null`*   
Tag in string separated by commas, or an Array.    


#### **with:** *`int|bool`*   
Count Votes with this tag or without this tag.    


### Return value   

*(`int`)* (Int) Total vote weight


---------------------------------------

### Related method(s)      

* [Election::countValidVoteWithConstraints](/Docs/api-reference/Election%20Class/Election--countValidVoteWithConstraints.md)    
* [Election::countInvalidVoteWithConstraints](/Docs/api-reference/Election%20Class/Election--countInvalidVoteWithConstraints.md)    
