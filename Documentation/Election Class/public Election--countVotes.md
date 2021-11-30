## public Election::countVotes

### Description    

```php
public Election->countVotes ( [array|string|null tags = null , bool with = true] ): int
```

Count the number of actual registered and valid vote for this election. This method ignore votes constraints, only valid vote will be counted.
    

##### **tags:** *```array|string|null```*   
Tag into string separated by commas, or an Array.    


##### **with:** *```bool```*   
Count Votes with this tag ou without this tag-.    


### Return value:   

*(```int```)* Number of valid and registered vote into this election.


---------------------------------------

### Related method(s)      

* [Election::getVotesList](../Election%20Class/public%20Election--getVotesList.md)    
* [Election::countValidVoteWithConstraints](../Election%20Class/public%20Election--countValidVoteWithConstraints.md)    
