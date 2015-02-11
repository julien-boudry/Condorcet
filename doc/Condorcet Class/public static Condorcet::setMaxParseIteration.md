## public static Condorcet::setMaxParseIteration

### Description    

```php
public static Condorcet::setMaxParseIteration ( mixed param1 )
```

Maximum input for each use of Condorcet::parseCandidate && Condorcet::parseVote. Will throw an exception if exceeded.    
- **param1:** *mixed* Null will desactivate this functionnality. Else, enter an integer.



### Return value:   

False on error. Else, the new value (null or integer).


---------------------------------------

### Related method(s)      

* [static Condorcet::setMaxVoteNumber](../static Condorcet Class/public static Condorcet::setMaxVoteNumber.md)    
