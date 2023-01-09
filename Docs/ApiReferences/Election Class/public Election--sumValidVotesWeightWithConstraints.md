## public Election::sumValidVotesWeightWithConstraints

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/ElectionProcess/VotesProcess.php#L80)

### Description    

```php
public Election->sumValidVotesWeightWithConstraints ( ): int
```

Sum total votes weight in this election. If vote weight functionality is disable (default setting), it will return the number of registered votes. This method don't ignore votes constraints, only valid vote will be counted.
    

### Return value:   

*(```int```)* (Int) Total vote weight


---------------------------------------

### Related method(s)      

* [Election::countValidVoteWithConstraints](/Docs/ApiReferences/Election%20Class/public%20Election--countValidVoteWithConstraints.md)    
* [Election::countInvalidVoteWithConstraints](/Docs/ApiReferences/Election%20Class/public%20Election--countInvalidVoteWithConstraints.md)    
