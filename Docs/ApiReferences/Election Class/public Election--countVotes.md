## public Election::countVotes

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/ElectionProcess/VotesProcess.php#L43)

### Description    

```php
public Election->countVotes ( [array|string|null $tags = null , int|bool $with = true] ): int
```

Count the number of actual registered and valid vote for this election. This method ignore votes constraints, only valid vote will be counted.
    

#### **tags:** *`array|string|null`*   
Tag into string separated by commas, or an Array.    


#### **with:** *`int|bool`*   
Count Votes with this tag ou without this tag-.    


### Return value:   

*(`int`)* Number of valid and registered vote into this election.


---------------------------------------

### Related method(s)      

* [Election::getVotesList](/Docs/ApiReferences/Election%20Class/public%20Election--getVotesList.md)    
* [Election::countValidVoteWithConstraints](/Docs/ApiReferences/Election%20Class/public%20Election--countValidVoteWithConstraints.md)    
