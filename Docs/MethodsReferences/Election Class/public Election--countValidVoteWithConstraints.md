## public Election::countValidVoteWithConstraints

### Description    

```php
public Election->countValidVoteWithConstraints ( ): int
```

Count the number of actual registered and valid vote for this election. This method don't ignore votes constraints, only valid vote will be counted.
    

### Return value:   

*(```int```)* Number of valid and registered vote into this election.


---------------------------------------

### Related method(s)      

* [Election::countInvalidVoteWithConstraints](/Docs/MethodsReferences/Election%20Class/public%20Election--countInvalidVoteWithConstraints.md)    
* [Election::countVotes](/Docs/MethodsReferences/Election%20Class/public%20Election--countVotes.md)    
* [Election::sumValidVotesWeightWithConstraints](/Docs/MethodsReferences/Election%20Class/public%20Election--sumValidVotesWeightWithConstraints.md)    
