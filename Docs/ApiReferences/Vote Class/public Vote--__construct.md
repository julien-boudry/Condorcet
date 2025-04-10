## public Vote::__construct

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Vote.php#L170)

### Description    

```php
public Vote->__construct ( array|string $ranking [, array|string|null $tags = null , ?float $ownTimestamp = null , ?CondorcetPHP\Condorcet\Election $electionContext = null] )
```

Build a vote object.
    

#### **ranking:** *`array|string`*   
Equivalent to Vote::setRanking method.    


#### **tags:** *`array|string|null`*   
Equivalent to Vote::addTags method.    


#### **ownTimestamp:** *`?float`*   
Set your own timestamp metadata for this Ranking.    


#### **electionContext:** *`?CondorcetPHP\Condorcet\Election`*   
Try to convert directly your candidates from string input to Candidate object of one election.    


### Throws:   

* ```CondorcetPHP\Condorcet\Throwable\VoteInvalidFormatException``` 

---------------------------------------

### Related method(s)      

* [Vote::setRanking](/Docs/ApiReferences/Vote%20Class/public%20Vote--setRanking.md)    
* [Vote::addTags](/Docs/ApiReferences/Vote%20Class/public%20Vote--addTags.md)    

---------------------------------------

### Tutorial

* **[This method has explanations and examples in the Documentation Book](https://www.condorcet.io/3.AsPhpLibrary/5.Votes/1.AddVotes)**    
