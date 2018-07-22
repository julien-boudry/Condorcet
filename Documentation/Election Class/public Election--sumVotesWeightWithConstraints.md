## public Election::sumVotesWeightWithConstraints

### Description    

```php
public $Election -> sumVotesWeightWithConstraints ( )
```

Sum total votes weight in this election. If vote weight functionality is disable (default setting), it will return the number of registered votes. This method don't ignore votes constraints, only valid vote will be counted.    


### Return value:   

(Int) Total vote weight


---------------------------------------

### Related method(s)      

* [Election::sumValidVotesWeightWithConstraints](../Election%20Class/public%20Election--sumValidVotesWeightWithConstraints.md)    
* [Election::countValidVoteWithConstraints](../Election%20Class/public%20Election--countValidVoteWithConstraints.md)    
* [Election::countInvalidVoteWithConstraints](../Election%20Class/public%20Election--countInvalidVoteWithConstraints.md)    
