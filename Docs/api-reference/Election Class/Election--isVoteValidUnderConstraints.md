# public Election::isVoteValidUnderConstraints

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Election.php#L410)

## Description    

```php
public Election->isVoteValidUnderConstraints ( CondorcetPHP\Condorcet\Vote $vote ): bool
```

Test if a vote is valid with these election constraints.

## Parameter

### **vote:** *`CondorcetPHP\Condorcet\Vote`*   
A vote. Not necessarily registered in this election.    


## Return value   

*(`bool`)* Return True if vote will pass the constraints rules, else False.


---------------------------------------

## Related method(s)      

* [Election::getConstraints](/Docs/api-reference/Election%20Class/Election--getConstraints.md)    
* [Election::addConstraint](/Docs/api-reference/Election%20Class/Election--addConstraint.md)    
* [Election::clearConstraints](/Docs/api-reference/Election%20Class/Election--clearConstraints.md)    

---------------------------------------

## Tutorial

* **[This method has explanations and examples in the Documentation Book](https://docs.condorcet.io/book/3.AsPhpLibrary/5.Votes/4.VoteConstraints)**    
