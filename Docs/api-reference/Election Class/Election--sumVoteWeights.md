# public Election::sumVoteWeights

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/ElectionProcess/VotesProcess.php#L81)

## Description    

```php
public Election->sumVoteWeights ( [array|string|null $tags = null , int|bool $with = true] ): int
```

Sum total votes weight in this election. If vote weight functionality is disabled (default setting), it will return the number of registered votes. This method ignores votes constraints.

## Parameters

### **tags:** *`array|string|null`*   
Tag in string separated by commas, or an Array.    

### **with:** *`int|bool`*   
Count Votes with this tag or without this tag.    


## Return value   

*(`int`)* (int) Total vote weight


---------------------------------------

## Related method(s)      

* [Election::sumValidVoteWeightsWithConstraints](/Docs/api-reference/Election%20Class/Election--sumValidVoteWeightsWithConstraints.md)    
