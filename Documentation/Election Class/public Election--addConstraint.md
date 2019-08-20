## public Election::addConstraint

### Description    

```php
public $Election -> addConstraint ( string class ) : bool
```

Add a constraint rules as a valid class path.    


##### **class:** *string*   
A valid class path. Class must extend VoteConstraint class.    



### Return value:   

True on success. Throw CondorcetException code 27/28/29 on error.


---------------------------------------

### Related method(s)      

* [Election::getConstraints](../Election%20Class/public%20Election--getConstraints.md)    
* [Election::clearConstraints](../Election%20Class/public%20Election--clearConstraints.md)    
* [Election::testIfVoteIsValidUnderElectionConstraints](../Election%20Class/public%20Election--testIfVoteIsValidUnderElectionConstraints.md)    

---------------------------------------

### Examples and explanation

* **[Manual - Vote Constraints](https://github.com/julien-boudry/Condorcet/wiki/II-%23-C.-Result-%23-5.-Vote-Constraints)**    
