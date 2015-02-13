## public Vote::setRanking

### Description    

```php
public $Vote -> setRanking ( mixed ranking [, numeric timestamp = false] )
```

Set a new ranking for this vote.

Note that if your vote is already linked to one ore more elections, your ranking must be compliant with all of them, else an exception is throw. For do this, you need to use only valid Candidate object, you can't register a new ranking from string if your vote is already linked to an election.    
- **ranking:** *mixed* * Ranking can be add by string like "A > B = C > D" (by candidate string name)
* Multidimensionnal array like :
   ```php
   array( $candidate1, [$candidate2,$candidate4], $candidate 3 )
   ```
* Multidimensionnal array with string :
   ```php
   array( 'candidate1Name', ['candidate2Name','candidate4Name'], 'candidate3Name' )
   ```
* Or combine the 2 last ways.

Note: You can't use string candidate name if your vote is already linked to an election (by Condorcet::addVote).

- **timestamp:** *numeric* Set your own timestamp metadata on Ranking. Your timestamp must be > than last registered timestamp. Else, an exception will be throw.



### Return value:   

Vote::getRanking


---------------------------------------

### Related method(s)      

* [Vote::getRanking](../Vote Class/public Vote::getRanking.md)    
* [Vote::getHistory](../Vote Class/public Vote::getHistory.md)    
* [Vote::__construct](../Vote Class/public Vote::__construct.md)    

---------------------------------------

### Examples and explanation

* **[Manual - Add a vote](https://github.com/julien-boudry/Condorcet/wiki/II-%23-B.-Vote-management-%23-1.-Add-Vote)**    
