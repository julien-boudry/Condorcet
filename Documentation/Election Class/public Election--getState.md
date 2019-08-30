## public Election::getState

### Description    

```php
public $Election -> getState ( ) : int
```

Get the election process level.
    

### Return value:   

*(int)* 1: Candidate registered state. No votes, no result, no cache.
2: Voting registration phase. Pairwise cache can exist thanks to dynamic computation if voting phase continue after the first get result. But method result never exist.
3: Result phase: Some method result may exist, pairwise exist. An election will return to Phase 2 if votes are added or modified dynamically.


---------------------------------------

### Related method(s)      

* [Election::setStateToVote](../Election%20Class/public%20Election--setStateToVote.md)    
