## public Election::sumVotesWeight

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/ElectionProcess/VotesProcess.php#L82)

### Description    

```php
public Election->sumVotesWeight ( [array|string|null $tags = null , int|bool $with = true] ): int
```

Sum total votes weight in this election. If vote weight functionality is disable (default setting), it will return the number of registered votes. This method ignore votes constraints.
    

#### **tags:** *`array|string|null`*   
Tag into string separated by commas, or an Array.    


#### **with:** *`int|bool`*   
Count Votes with this tag ou without this tag-.    


### Return value:   

*(`int`)* (Int) Total vote weight


---------------------------------------

### Related method(s)      

* [Election::sumValidVotesWeightWithConstraints](/Docs/ApiReferences/Election%20Class/public%20Election--sumValidVotesWeightWithConstraints.md)    
