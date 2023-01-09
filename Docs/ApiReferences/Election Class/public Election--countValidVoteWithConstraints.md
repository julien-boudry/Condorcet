## public Election::countValidVoteWithConstraints

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/ElectionProcess/VotesProcess.php#L61)

### Description    

```php
public Election->countValidVoteWithConstraints ( ): int
```

Count the number of actual registered and valid vote for this election. This method don't ignore votes constraints, only valid vote will be counted.
    

### Return value:   

*(`int`)* Number of valid and registered vote into this election.


---------------------------------------

### Related method(s)      

* [Election::countInvalidVoteWithConstraints](/Docs/ApiReferences/Election%20Class/public%20Election--countInvalidVoteWithConstraints.md)    
* [Election::countVotes](/Docs/ApiReferences/Election%20Class/public%20Election--countVotes.md)    
* [Election::sumValidVotesWeightWithConstraints](/Docs/ApiReferences/Election%20Class/public%20Election--sumValidVotesWeightWithConstraints.md)    
