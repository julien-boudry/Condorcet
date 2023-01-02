## public Election::addConstraint

### Description    

```php
public Election->addConstraint ( string $constraintClass ): bool
```

Add a constraint rules as a valid class path.
    

##### **constraintClass:** *```string```*   
A valid class path. Class must extend VoteConstraint class.    


### Return value:   

*(```bool```)* True on success.



### Throws:   

* ```CondorcetPHP\Condorcet\Throwable\VoteConstraintException```

---------------------------------------

### Related method(s)      

* [Election::getConstraints](../Election%20Class/public%20Election--getConstraints.md)    
* [Election::clearConstraints](../Election%20Class/public%20Election--clearConstraints.md)    
* [Election::testIfVoteIsValidUnderElectionConstraints](../Election%20Class/public%20Election--testIfVoteIsValidUnderElectionConstraints.md)    

---------------------------------------

### Tutorial

* **[This method has explanations and examples in the Documentation Book](https://www.condorcet.io/3.AsPhpLibrary/5.Votes/4.VoteConstraints)**    
