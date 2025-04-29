# public Vote::__construct

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Vote.php#L165)

## Description    

```php
public Vote->__construct ( array|string $ranking [, array|string|null $tags = null , ?float $ownTimestamp = null , ?CondorcetPHP\Condorcet\Election $electionContext = null] )
```

Build a vote object.

## Parameters

### **ranking:** *`array|string`*   
Equivalent to Vote::setRanking method.    

### **tags:** *`array|string|null`*   
Equivalent to Vote::addTags method.    

### **ownTimestamp:** *`?float`*   
Set your own timestamp metadata for this Ranking.    

### **electionContext:** *`?CondorcetPHP\Condorcet\Election`*   
Try to convert directly your candidates from string input to Candidate object of one election.    


## Throws:   

* ```CondorcetPHP\Condorcet\Throwable\VoteInvalidFormatException``` 

---------------------------------------

## Related

* [Vote::setRanking](/Docs/api-reference/Vote%20Class/Vote--setRanking.md)    
* [Vote::addTags](/Docs/api-reference/Vote%20Class/Vote--addTags.md)    

---------------------------------------

## Tutorial

* **[This method has explanations and examples in the Documentation Book](https://docs.condorcet.io/book/3.AsPhpLibrary/5.Votes/1.AddVotes)**    
