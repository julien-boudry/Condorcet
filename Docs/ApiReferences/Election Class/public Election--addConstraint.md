## public Election::addConstraint

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Election.php#L353)

### Description    

```php
public Election->addConstraint ( string $constraintClass ): true
```

Add a constraint rules as a valid class path.
    

#### **constraintClass:** *`string`*   
A valid class path. Class must extend VoteConstraint class.    


### Return value   

*(`true`)* True on success.



### Throws:   

* ```CondorcetPHP\Condorcet\Throwable\VoteConstraintException``` 

---------------------------------------

### Related method(s)      

* [Election::getConstraints](/Docs/ApiReferences/Election%20Class/public%20Election--getConstraints.md)    
* [Election::clearConstraints](/Docs/ApiReferences/Election%20Class/public%20Election--clearConstraints.md)    
* [Election::isVoteValidUnderConstraints](/Docs/ApiReferences/Election%20Class/public%20Election--isVoteValidUnderConstraints.md)    

---------------------------------------

### Tutorial

* **[This method has explanations and examples in the Documentation Book](https://www.condorcet.io/3.AsPhpLibrary/5.Votes/4.VoteConstraints)**    
