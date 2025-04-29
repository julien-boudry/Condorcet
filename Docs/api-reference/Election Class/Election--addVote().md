# public Election::addVote

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/ElectionProcess/VotesProcess.php#L188)

## Description    

```php
public Election->addVote ( CondorcetPHP\Condorcet\Vote|array|string $vote [, array|string|null $tags = null] ): CondorcetPHP\Condorcet\Vote
```

Add a vote to an election.

## Parameters

### **vote:** *`CondorcetPHP\Condorcet\Vote|array|string`*   
String or array representation. Or CondorcetPHP\Condorcet\Vote object. If you do not provide a Vote object yourself, a new one will be generated for you.    

### **tags:** *`array|string|null`*   
String separated by commas or an array. Will add tags to the vote object for you. But you can also add them yourself to the Vote object.    


## Return value   

*(`CondorcetPHP\Condorcet\Vote`)* The vote object.



## Throws:   

* ```VoteMaxNumberReachedException``` 

---------------------------------------

## Related

* [Election::parseVotes](/Docs/api-reference/Election%20Class/Election--parseVotes().md)    
* [Election::addVotesFromJson](/Docs/api-reference/Election%20Class/Election--addVotesFromJson().md)    
* [Election::removeVote](/Docs/api-reference/Election%20Class/Election--removeVote().md)    
* [Election::getVotesList](/Docs/api-reference/Election%20Class/Election--getVotesList().md)    

---------------------------------------

## Tutorial

* **[This method has explanations and examples in the Documentation Book](https://docs.condorcet.io/book/3.AsPhpLibrary/5.Votes/1.AddVotes)**    
