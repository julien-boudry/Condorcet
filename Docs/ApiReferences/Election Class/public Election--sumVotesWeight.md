## public Election::sumVotesWeight

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/ElectionProcess/VotesProcess.php#L71)

### Description    

```php
public Election->sumVotesWeight ( ): int
```

Sum total votes weight in this election. If vote weight functionality is disable (default setting), it will return the number of registered votes. This method ignore votes constraints.
    

### Return value:   

*(```int```)* (Int) Total vote weight


---------------------------------------

### Related method(s)      

* [Election::sumValidVotesWeightWithConstraints](/Docs/ApiReferences/Election%20Class/public%20Election--sumValidVotesWeightWithConstraints.md)    
