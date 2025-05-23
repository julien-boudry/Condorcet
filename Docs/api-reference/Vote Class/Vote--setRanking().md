# public Vote::setRanking

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Vote.php#L454)

## Description    

```php
public Vote->setRanking ( array|string $ranking [, ?float $ownTimestamp = null] ): static
```

Set a new ranking for this vote.

Note that if your vote is already linked to one ore more elections, your ranking must be compliant with all of them, else an exception is throw. For do this, you need to use only valid Candidate object, you can't register a new ranking from string if your vote is already linked to an election.

## Parameters

### **ranking:** *`array|string`*   
A Ranking. Have a look at the Wiki https://github.com/julien-boudry/Condorcet/wiki/II-%23-B.-Vote-management-%23-1.-Add-Vote to learn the available ranking formats.    

### **ownTimestamp:** *`?float`*   
Set your own timestamp metadata on Ranking. Your timestamp must be > than last registered timestamp. Else, an exception will be throw.    


## Throws:   

* ```CondorcetPHP\Condorcet\Throwable\VoteInvalidFormatException``` 

---------------------------------------

## Related

* [Vote::getRanking()](/Docs/api-reference/Vote%20Class/Vote--getRanking().md)    
* [Vote::rankingHistory](/Docs/api-reference/Vote%20Class/Vote--rankingHistory.md)    
* [Vote::__construct()](/Docs/api-reference/Vote%20Class/Vote--__construct().md)    

---------------------------------------

## Tutorial

* **[This method has explanations and examples in the Documentation Book](https://docs.condorcet.io/book/3.AsPhpLibrary/5.Votes/1.AddVotes)**    
