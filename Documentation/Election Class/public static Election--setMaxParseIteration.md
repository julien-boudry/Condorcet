## public static Election::setMaxParseIteration

### Description    

```php
public static Election::setMaxParseIteration ( mixed param1 ) : ?int
```

Maximum input for each use of Election::parseCandidate && Election::parseVote. Will throw an exception if exceeded.
    

##### **param1:** *mixed*   
Null will desactivate this functionnality. Else, enter an integer.    


### Return value:   

*(int or null)* The new limit.


---------------------------------------

### Related method(s)      

* [static Election::setMaxVoteNumber](../Election%20Class/public%20static%20Election--setMaxVoteNumber.md)    
