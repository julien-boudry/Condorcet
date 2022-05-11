## public Vote::setRanking

### Description    

```php
public Vote->setRanking ( array|string $ranking [, ?float $ownTimestamp = null] ): bool
```

Set a new ranking for this vote.

Note that if your vote is already linked to one ore more elections, your ranking must be compliant with all of them, else an exception is throw. For do this, you need to use only valid Candidate object, you can't register a new ranking from string if your vote is already linked to an election.
    

##### **ranking:** *```array|string```*   
A Ranking. Have a look at the Wiki https://github.com/julien-boudry/Condorcet/wiki/II-%23-B.-Vote-management-%23-1.-Add-Vote to learn the available ranking formats.    


##### **ownTimestamp:** *```?float```*   
Set your own timestamp metadata on Ranking. Your timestamp must be > than last registered timestamp. Else, an exception will be throw.    


### Return value:   

*(```bool```)* In case of success, return TRUE



### Throws:   

* ```CondorcetPHP\Condorcet\Throwable\VoteInvalidFormatException```

---------------------------------------

### Related method(s)      

* [Vote::getRanking](../Vote%20Class/public%20Vote--getRanking.md)    
* [Vote::getHistory](../Vote%20Class/public%20Vote--getHistory.md)    
* [Vote::__construct](../Vote%20Class/public%20Vote--__construct.md)    

---------------------------------------

### Examples and explanation

* **[Manual - Add a vote](https://github.com/julien-boudry/Condorcet/wiki/II-%23-B.-Vote-management-%23-1.-Add-Vote)**    
