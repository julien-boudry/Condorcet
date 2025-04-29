# public Election::addConstraint

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Election.php#L345)

## Description    

```php
public Election->addConstraint ( string $constraintClass ): static
```

Add a constraint rules as a valid class path.

## Parameter

### **constraintClass:** *`string`*   
A valid class path. Class must extend VoteConstraint class.    


## Throws:   

* ```CondorcetPHP\Condorcet\Throwable\VoteConstraintException``` 

---------------------------------------

## Related

* [Election::getConstraints](/Docs/api-reference/Election%20Class/Election--getConstraints.md)    
* [Election::clearConstraints](/Docs/api-reference/Election%20Class/Election--clearConstraints.md)    
* [Election::isVoteValidUnderConstraints](/Docs/api-reference/Election%20Class/Election--isVoteValidUnderConstraints.md)    

---------------------------------------

## Tutorial

* **[This method has explanations and examples in the Documentation Book](https://docs.condorcet.io/book/3.AsPhpLibrary/5.Votes/5.VotesConstraints)**    
