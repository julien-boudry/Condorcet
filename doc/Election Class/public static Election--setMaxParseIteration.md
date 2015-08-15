## public static Election::setMaxParseIteration

### Description    

```php
public static Election::setMaxParseIteration ( mixed param1 )
```

Maximum input for each use of Election::parseCandidate && Election::parseVote. Will throw an exception if exceeded.    


##### **param1:** *mixed*   
Null will desactivate this functionnality. Else, enter an integer.    



### Return value:   

False on error. Else, the new value (null or integer).


---------------------------------------

### Related method(s)      

* [static Election::setMaxVoteNumber](../Election Class/public static Election--setMaxVoteNumber.md)    
