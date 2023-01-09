## public static Election::setMaxParseIteration

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Election.php#L60)

### Description    

```php
public static Election::setMaxParseIteration ( ?int $maxParseIterations ): ?int
```

Maximum input for each use of Election::parseCandidate && Election::parseVote. Will throw an exception if exceeded.
    

#### **maxParseIterations:** *```?int```*   
Null will deactivate this functionality. Else, enter an integer.    


### Return value:   

*(```?int```)* *(int or null)* The new limit.


---------------------------------------

### Related method(s)      

* [static Election::setMaxVoteNumber](/Docs/ApiReferences/Election%20Class/public%20static%20Election--setMaxVoteNumber.md)    
