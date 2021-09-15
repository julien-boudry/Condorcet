## public Election::addConstraint

### Description    

```php
public Election->addConstraint ( string constraintClass ): bool
```

Add a constraint rules as a valid class path.
    

##### **constraintClass:** *string*   
A valid class path. Class must extend VoteConstraint class.    


### Return value:   

*(bool)* True on success.


---------------------------------------

### Related method(s)      

* [Election::getConstraints](../Election%20Class/public%20Election--getConstraints.md)    
* [Election::clearConstraints](../Election%20Class/public%20Election--clearConstraints.md)    
* [Election::testIfVoteIsValidUnderElectionConstraints](../Election%20Class/public%20Election--testIfVoteIsValidUnderElectionConstraints.md)    

---------------------------------------

### Examples and explanation

* **[Manual - Vote Constraints](https://github.com/julien-boudry/Condorcet/wiki/II-%23-C.-Result-%23-5.-Vote-Constraints)**    
