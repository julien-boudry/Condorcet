## public Vote::setRanking

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Vote.php#L441)

### Description    

```php
public Vote->setRanking ( array|string $ranking [, ?float $ownTimestamp = null] ): true
```

Set a new ranking for this vote.

Note that if your vote is already linked to one ore more elections, your ranking must be compliant with all of them, else an exception is throw. For do this, you need to use only valid Candidate object, you can't register a new ranking from string if your vote is already linked to an election.
    

#### **ranking:** *`array|string`*   
A Ranking. Have a look at the Wiki https://github.com/julien-boudry/Condorcet/wiki/II-%23-B.-Vote-management-%23-1.-Add-Vote to learn the available ranking formats.    


#### **ownTimestamp:** *`?float`*   
Set your own timestamp metadata on Ranking. Your timestamp must be > than last registered timestamp. Else, an exception will be throw.    


### Return value:   

*(`true`)* In case of success, return TRUE



### Throws:   

* ```CondorcetPHP\Condorcet\Throwable\VoteInvalidFormatException```

---------------------------------------

### Related method(s)      

* [Vote::getRanking](/Docs/ApiReferences/Vote%20Class/public%20Vote--getRanking.md)    
* [Vote::getHistory](/Docs/ApiReferences/Vote%20Class/public%20Vote--getHistory.md)    
* [Vote::__construct](/Docs/ApiReferences/Vote%20Class/public%20Vote--__construct.md)    

---------------------------------------

### Tutorial

* **[This method has explanations and examples in the Documentation Book](https://www.condorcet.io/3.AsPhpLibrary/5.Votes/1.AddVotes)**    
