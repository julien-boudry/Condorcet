## public Election::testIfVoteIsValidUnderElectionConstraints

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Election.php#L354)

### Description    

```php
public Election->testIfVoteIsValidUnderElectionConstraints ( CondorcetPHP\Condorcet\Vote $vote ): bool
```

Test if a vote is valid with these election constraints.
    

#### **vote:** *`CondorcetPHP\Condorcet\Vote`*   
A vote. Not necessarily registered in this election.    


### Return value:   

*(`bool`)* Return True if vote will pass the constraints rules, else False.


---------------------------------------

### Related method(s)      

* [Election::getConstraints](/Docs/ApiReferences/Election%20Class/public%20Election--getConstraints.md)    
* [Election::addConstraints](/Docs/ApiReferences/Election%20Class/public%20Election--addConstraints.md)    
* [Election::clearConstraints](/Docs/ApiReferences/Election%20Class/public%20Election--clearConstraints.md)    

---------------------------------------

### Tutorial

* **[This method has explanations and examples in the Documentation Book](https://www.condorcet.io/3.AsPhpLibrary/5.Votes/4.VoteConstraints)**    
