## public Election::testIfVoteIsValidUnderElectionConstraints

### Description    

```php
public $Election -> testIfVoteIsValidUnderElectionConstraints ( CondorcetPHP\Condorcet\Vote vote ) : bool
```

Test if a vote is valid with these election constraints.    


##### **vote:** *CondorcetPHP\Condorcet\Vote*   
A vote. Not necessarily registered in this election.    



### Return value:   

Return True if vote will pass the constraints rules, else False.


---------------------------------------

### Related method(s)      

* [Election::getConstraints](../Election%20Class/public%20Election--getConstraints.md)    
* [Election::addConstraints](../Election%20Class/public%20Election--addConstraints.md)    
* [Election::clearConstraints](../Election%20Class/public%20Election--clearConstraints.md)    

---------------------------------------

### Examples and explanation

* **[Manual - Vote Constraints](https://github.com/julien-boudry/Condorcet/wiki/II-%23-C.-Result-%23-5.-Vote-Constraints)**    
