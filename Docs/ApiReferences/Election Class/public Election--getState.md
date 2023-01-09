## public Election::getState

### Description    

```php
public Election->getState ( ): CondorcetPHP\Condorcet\ElectionProcess\ElectionState
```

Get the election process level.
    

### Return value:   

*(```CondorcetPHP\Condorcet\ElectionProcess\ElectionState```)*   
`ElectionState::CANDIDATES_REGISTRATION`: Candidate registered state. No votes, no result, no cache.  
`ElectionState::VOTES_REGISTRATION`: Voting registration phase. Pairwise cache can exist thanks to dynamic computation if voting phase continue after the first get result. But method result never exist.  
3: Result phase: Some method result may exist, pairwise exist. An election will dynamically return to Phase 2 if votes are added or modified.


---------------------------------------

### Related method(s)      

* [Election::setStateToVote](/Docs/ApiReferences/Election%20Class/public%20Election--setStateToVote.md)    
