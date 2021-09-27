## public static Election::setMaxParseIteration

### Description    

```php
public static Election::setMaxParseIteration ( ?int maxParseIterations ): ?int
```

Maximum input for each use of Election::parseCandidate && Election::parseVote. Will throw an exception if exceeded.
    

##### **maxParseIterations:** *?int*   
Null will deactivate this functionality. Else, enter an integer.    


### Return value:   

*(?int)* *(int or null)* The new limit.


---------------------------------------

### Related method(s)      

* [static Election::setMaxVoteNumber](../Election%20Class/public%20static%20Election--setMaxVoteNumber.md)    
