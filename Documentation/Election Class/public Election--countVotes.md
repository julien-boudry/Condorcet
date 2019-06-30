## public Election::countVotes

### Description    

```php
public $Election -> countVotes ( [ mixed tag = null, bool with = true] )
```

Count the number of actual registered and valid vote for this election. This method ignore votes constraints, only valid vote will be counted.    


##### **tag:** *mixed*   
Tag into string separated by commas, or an Array.    



##### **with:** *bool*   
Count Votes with this tag ou without this tag.    



### Return value:   

(int) Number of valid and registered vote into this election.


---------------------------------------

### Related method(s)      

* [Election::getVotesList](../Election%20Class/public%20Election--getVotesList.md)    
* [Election::countValidVoteWithConstraints](../Election%20Class/public%20Election--countValidVoteWithConstraints.md)    
