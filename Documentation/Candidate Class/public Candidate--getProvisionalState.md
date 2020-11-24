## public Candidate::getProvisionalState

### Description    

```php
public Candidate->getProvisionalState ( ) : bool
```

When you create yourself the vote object, without use the Election::addVote or other native election method. And if you use string input (or array of string).
Then, these string input will be converted to into temporary candidate objects, named "provisional". because you don't create the candidate yourself. They have a provisonal statut true.
When the vote will be added for the first time to an election, provisional candidate object with a name that matches an election candidate, will be converted into the election candidate. And first ranking will be save into Vote history (Vote::getHistory).

See VoteTest::testVoteHistory() test for a demonstration. In principle this is transparent from a usage point of view. If you want to avoid any non-strict comparisons, however, you should prefer to create your votes with the Election object, or with Candidate Objects in input. But, you must never getback a candidate marked as provisional in an another election in the same time, it's will not working.
    

### Return value:   

*(bool)* True if candidate object is in a provisional state, false else.

